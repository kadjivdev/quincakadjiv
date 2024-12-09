<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\FactureAncienne;
use App\Models\FactureFournisseur;
use App\Models\FactureType;
use App\Models\LivraisonDirecte;
use App\Models\Reglement;
use App\Models\ReglementClient;
use App\Models\TypeVente;
use App\Models\User;
use App\Models\Vente;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Approvisionnement;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\Devis;
use App\Models\DevisDetail;
use App\Models\FactureVente;
use App\Models\Livraison_pv;
use App\Models\VenteLigne;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RapportController extends Controller
{

    public function rapport_livraison_frs(Request $request)
    {

        $livraisons = Livraison_pv::join('chauffeurs', 'livraison_pvs.chauffeur_id', '=', 'chauffeurs.id')
            ->join('vehicules', 'livraison_pvs.vehicule_id', '=', 'vehicules.id')
            ->orderBy('livraison_pvs.created_at', 'desc') // Spécifier la table pour éviter les ambiguïtés
            ->select('livraison_pvs.*', 'livraison_pvs.id as cle', 'chauffeurs.*', 'vehicules.*') // Sélectionner les colonnes nécessaires
            ->get();


        //  return response()->json([
        //         'tableau'  => $commandes
        //     ]);
        return view('pages.rapport.rapport_liv_frs', compact('livraisons'));
    }

    public function rapport_livraison_frs_detail(string $id)
    {

        $liv = Livraison_pv::join('chauffeurs', 'livraison_pvs.chauffeur_id', '=', 'chauffeurs.id')
            ->join('vehicules', 'livraison_pvs.vehicule_id', '=', 'vehicules.id')
            ->where('livraison_pvs.id', $id) // Utiliser le nom de table correct et ajouter le préfixe de table
            ->select('livraison_pvs.*', 'chauffeurs.*', 'vehicules.*') // Correction ici
            ->get();


        $liste_appro = Approvisionnement::join('commandes', 'approvisionnements.commande_id', '=', 'commandes.id')
            ->join('ligne_commandes', 'approvisionnements.ligne_commande_id', '=', 'ligne_commandes.id')
            ->join('articles', 'ligne_commandes.article_id', '=', 'articles.id')
            ->join('unite_mesures', 'approvisionnements.unite_mesure_id', '=', 'unite_mesures.id')
            ->where('livraison_pv_id', $id)
            ->whereNotNull('approvisionnements.validated_at')
            ->select('approvisionnements.*', 'approvisionnements.created_at as liv_at', 'articles.*', 'unite_mesures.*', 'commandes.reference as ref_cmd')
            ->orderBy('approvisionnements.created_at', 'desc') // Spécifier la table pour éviter les ambiguïtés
            ->get();



        //  return response()->json([
        //         'tableau'  => $liv
        //     ]);
        return view('pages.rapport.rapport_liv_frs_detail', compact('liv', 'liste_appro'));
    }

    public function rapport_livraison_frs_details(Request $request)
    {

        $cmds = Commande::with('fournisseur')
            ->whereNotNull('validated_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $commandes = [];

        $commandes = [];

        foreach ($cmds as $cmd) {
            $lignes = LigneCommande::where('commande_id', $cmd->id)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($lignes as $ligne) {
                $livraison = Approvisionnement::join('ligne_commandes', 'approvisionnements.ligne_commande_id', '=', 'ligne_commandes.id')
                    ->join('articles', 'ligne_commandes.article_id', '=', 'articles.id')
                    ->join('unite_mesures', 'approvisionnements.unite_mesure_id', '=', 'unite_mesures.id')
                    ->join('magasins', 'approvisionnements.magasin_id', '=', 'magasins.id')
                    ->select(
                        'articles.nom as article_nom',
                        'approvisionnements.qte_livre as qte_liv',
                        'approvisionnements.validated_at as date_liv',
                        'unite_mesures.unite as unite_mesure_nom',
                        'magasins.nom as magasin_nom'
                    )
                    ->where('approvisionnements.ligne_commande_id', $ligne->id)
                    ->get();



                if ($livraison->count() > 0) {
                    $articles = [];

                    foreach ($livraison as $liv) {
                        $articles[] = [
                            'id' => $liv->id,
                            'nom' => $liv->article_nom,
                            'date_liv' => $liv->date_liv,
                            'unite' => $liv->unite_mesure_nom,
                            'magasin' => $liv->magasin_nom,
                            'qte_cmd' => $ligne->quantity,
                            'qte_liv' => $liv->qte_liv,
                            'qte_rest' => $ligne->quantity - $liv->qte_liv,
                            // Ajoutez d'autres propriétés d'article si nécessaire
                        ];
                    }

                    $commandes[] = (object) [
                        'id' => $cmd->id,
                        'reference' => $cmd->reference,
                        'articles' => $articles
                        // Ajoutez d'autres propriétés de commande si nécessaire
                    ];
                }
            }
        }

        //  return response()->json([
        //         'tableau'  => $commandes
        //     ]);
        return view('pages.rapport.rapport_liv_frs', compact('commandes'));
    }


    public function rapport_reglement_frs(Request $request)
    {

        $reglements = Reglement::with(['facture'])->orderBy('id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $reglements = $reglements->whereBetween('reglements.date_reglement', [$startDate, $endDate]);
        }

        if ($request->has('type_reglement') &&  $request->type_reglement != '') {
            $reglements = $reglements->where('reglements.type_reglement', $request->type_reglement);
        }

        $reglements = $reglements->get();


        return view('pages.rapport.reglement_fournisseur', compact('reglements'));
    }

    public function rapport_reglement_clt(Request $request)
    {

        $clients = Client::all();

        $reglements = CompteClient::with(['client'])
            ->where('type_op', 'not like', 'FAC%')
            ->orderBy('id', 'desc');

        if ($request->has('start_date') && $request->start_date != '' && $request->has('end_date') && $request->end_date != '') {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $reglements = $reglements->whereBetween('compte_clients.date_op', [$startDate, $endDate]);
        }

        if ($request->has('type_reglement') &&  $request->type_reglement != '') {
            if ($request->type_reglement == 'Accompte') {
                $reglements = $reglements->where('type_op', 'like', 'ACC%');
            } elseif ($request->type_reglement == 'Reglement') {
                $reglements = $reglements->where('type_op', 'like', 'REG%');
            }
        }

        if ($request->has('client') && $request->client != '') {
            $reglements = $reglements->where('compte_clients.client_id', $request->client);
        }

        $reglements = $reglements->get();


        return view('pages.rapport.reglement_client', compact('reglements', 'clients'));
    }

    public function ventes(Request $request)
    {
        $ventes = Vente::with(['typeVente', 'acheteur']);
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $ventes = $ventes->whereBetween('ventes.created_at', [$startDate, $endDate]);
        }

        if ($request->has('type_vente') &&  $request->type_vente != '') {
            $ventes = $ventes->where('ventes.type_vente_id', $request->type_vente);
        }

        $ventes = $ventes->get();

        $i = 1;
        $typeVentes = TypeVente::all();
        return view('pages.rapport.rapport_vente', compact('ventes', 'i', 'typeVentes'));
    }

    public function ventesAll(Request $request)
    {
        $ventes = Vente::with(['typeVente', 'acheteur']);
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $ventes = $ventes->whereBetween('ventes.created_at', [$startDate, $endDate]);
        }

        $devis = Devis::with(['client']);
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $devis = $devis->whereBetween('devis.created_at', [$startDate, $endDate]);
        }

        // if ($request->has('type_vente') &&  $request->type_vente != '') {
        //     $ventes = $ventes->where('ventes.type_vente_id', $request->type_vente);
        // }

        $ventes = $ventes->get();
        $devis = $devis->get();

        // dd($devis[0]->montant_total);

        $ventesAll = $ventes->concat($devis);
        $ventesAllSorted = $ventesAll->sortByDesc('id')->values();

        // dd($ventesAllSorted);

        $total_proforma = $devis->sum('montant_total');
        $total_comptant = $ventes->sum('montant');

        $i = 1;
        $typeVentes = TypeVente::all();
        return view('pages.rapport.rapport_vente_all', compact('ventesAllSorted', 'i', 'total_comptant', 'total_proforma'));
    }

    public function facturesFrs(Request $request)
    {
        $factures = FactureFournisseur::with(['fournisseur', 'typeFacture']);
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $factures = $factures->whereBetween('facture_fournisseurs.created_at', [$startDate, $endDate]);
        }

        if ($request->has('type_fact') &&  $request->type_fact != '') {
            $factures = $factures->where('facture_fournisseurs.facture_type_id', $request->type_fact);
        }

        $typeFacture = FactureType::all();
        $factures = $factures->get();
        $i = 1;

        return view('pages.rapport.rapport_fact_frs', compact('factures', 'i', 'typeFacture'));
    }

    public function facturesVteClt(Request $request)
    {
        $users_ids = User::where('point_vente_id', Auth::user()->point_vente_id)->pluck('id');
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $facturesDevis = Facture::with(['typeFacture', 'client'])
                ->whereIn("user_id", $users_ids)
                ->whereBetween('created_at', [$startDate, $endDate]);

            $facturesAnciennes = FactureAncienne::with(['typeFacture', 'client'])
                ->whereIn("user_id", $users_ids)
                ->whereBetween('created_at', [$startDate, $endDate]);

            $factures_simples = LivraisonDirecte::with(['typeFacture', 'client'])->whereIn("user_id", $users_ids)
                ->whereNotNull('validated_at')
                ->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $facturesDevis = Facture::with(['typeFacture', 'client'])->whereIn("user_id", $users_ids);
            $facturesAnciennes = FactureAncienne::with(['typeFacture', 'client'])->whereIn("user_id", $users_ids);
            $factures_simples = LivraisonDirecte::with(['typeFacture', 'client'])->whereIn("user_id", $users_ids)->whereNotNull('validated_at');
        }

        $facturesDevis = $facturesDevis->get();
        $facturesAnciennes = $facturesAnciennes->get();
        $factures_simples = $factures_simples->get();
        $facturesTous = $facturesDevis->concat($factures_simples)->concat($facturesAnciennes);
        $i = 1;

        return view('pages.rapport.rapport_fact_vte_clt', compact('facturesTous', 'i'));
    }

    public function facturesCltSansReglemt(Request $request)
    {
        $users_ids = User::where('point_vente_id', Auth::user()->point_vente_id)->pluck('id');
        $reglmt_factures_ids = ReglementClient::whereNotNull('validated_at')->pluck('facture_id');
        $reglmt_factOld_ids = ReglementClient::whereNotNull('validated_at')->pluck('facture_ancienne_id');
        $reglmt_livraison_ids = ReglementClient::whereNotNull('validated_at')->pluck('livraison_directe_id');

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $facturesDevis = Facture::with(['typeFacture', 'client'])
                ->whereIn("user_id", $users_ids)
                ->whereNotIn('id', $reglmt_factures_ids)
                ->whereBetween('created_at', [$startDate, $endDate]);

            $facturesAnciennes = FactureAncienne::with(['typeFacture', 'client'])
                ->whereIn("user_id", $users_ids)
                ->whereNotIn('id', $reglmt_factOld_ids)
                ->whereBetween('created_at', [$startDate, $endDate]);

            $factures_simples = LivraisonDirecte::with(['typeFacture', 'client'])
                ->whereIn("user_id", $users_ids)
                ->whereNotIn('id', $reglmt_livraison_ids)
                ->whereNotNull('validated_at')
                ->whereBetween('created_at', [$startDate, $endDate]);
        } else {

            $facturesDevis = Facture::with(['typeFacture', 'client'])
                ->whereIn("user_id", $users_ids)
                ->whereNotIn('id', $reglmt_factures_ids);
            $facturesAnciennes = FactureAncienne::with(['typeFacture', 'client'])
                ->whereIn("user_id", $users_ids)
                ->whereNotIn('id', $reglmt_factOld_ids);
            $factures_simples = LivraisonDirecte::with(['typeFacture', 'client'])
                ->whereIn("user_id", $users_ids)
                ->whereNotIn('id', $reglmt_livraison_ids)
                ->whereNotNull('validated_at');
        }

        $facturesDevis = $facturesDevis->get();
        $facturesAnciennes = $facturesAnciennes->get();
        $factures_simples = $factures_simples->get();
        $facturesTous = $facturesDevis->concat($factures_simples)->concat($facturesAnciennes);
        $i = 1;

        return view('pages.rapport.rapport_fact_clt_non_regl', compact('i', 'facturesTous'));
    }


    // gestion du rapport journalier des ventes comptant et proforma

    public function rapport_vente_journaliere(Request $request){
        // Récupérer les factures comptants avec leur type de vente
        $factures_comptants = FactureVente::select('facture_ventes.*', 'clients.nom_client AS nom_client', 'facture_types.libelle AS type_vente')
            ->join('clients', 'facture_ventes.client_facture', '=', 'clients.id')
            ->join('facture_types', 'facture_ventes.facture_type_id', '=', 'facture_types.id')
            ->where('facture_ventes.date_facture', $request->date_facture)
            ->get();

        // dd($factures_comptants);

        // Récupérer les factures proforma avec leur type de vente
        $factures_proforma = Facture::select('factures.*', 'facture_types.libelle AS type_vente')
            ->join('facture_types', 'factures.facture_type_id', '=', 'facture_types.id')
            ->where('factures.date_facture', $request->date_facture)
            ->get();

        // Fusionner les deux collections en une seule
        $merged_factures = $factures_comptants->concat($factures_proforma);
        // dd($merged_factures);

        // Trier la collection fusionnée par ordre décroissant de l'ID
        $merged_factures_sorted = $merged_factures->sortByDesc('id')->values();

        // Calculer le total des montants
        $total_comptant = $factures_comptants->sum('montant_total');
        $total_proforma = $factures_proforma->sum('montant_total');


        // return view('pages.rapport.rapport_vente_journaliere_globale', compact('ventes', 'i', 'typeVentes'));
        //  return response()->json([
        //                 'tableau'  => $merged_factures_sorted
        //             ]);*

        // dd($merged_factures_sorted);

        return view('pages.rapport.rapport_vente_journaliere', [
            'factures' => $merged_factures_sorted,
            'total_comptant' => $total_comptant,
            'total_proforma' => $total_proforma,
        ]);
    }

    public function rapport_vente_journaliere_detail($facture, $vente_id)
    {
       // Récupération des détails de facture comptants (FactureVente)
        $factures_comptants = FactureVente::select('facture_ventes.*', 'clients.nom_client AS nom_client', 'facture_types.libelle AS type_vente')
        ->join('clients', 'facture_ventes.client_facture', '=', 'clients.id')
        ->join('facture_types', 'facture_ventes.facture_type_id', '=', 'facture_types.id')
        ->where('facture_ventes.id', $facture)
        ->first();

        // Récupération des détails de facture proforma (Facture)
        $factures_proforma = Facture::select('factures.*', 'facture_types.libelle AS type_vente')
        ->join('facture_types', 'factures.facture_type_id', '=', 'facture_types.id')
        ->where('factures.id', $facture)
        ->first();

        $la_facture = null;
        $detail_facture = null;

        // Vérification et traitement des résultats pour les factures comptants
        if ($vente_id != 'aaa') {
            $detail_facture = VenteLigne::select('vente_lignes.*', 'articles.nom AS nom_article')
                ->join('articles', 'vente_lignes.article_id', '=', 'articles.id')
                ->where('vente_id', $factures_comptants->vente_id)
                ->get();

            $la_facture = $factures_comptants;
        }

        // Vérification et traitement des résultats pour les factures proforma
        if ($vente_id == 'aaa') {
            $detail_facture = DevisDetail::select('devis_details.*', 'articles.nom AS nom_article', 'unite_mesures.unite AS unite_mesure')
                ->join('articles', 'devis_details.article_id', '=', 'articles.id')
                ->join('unite_mesures', 'devis_details.unite_mesure_id', '=', 'unite_mesures.id')
                ->where('devis_id', $factures_proforma->devis_id)
                ->get();

            $la_facture = $factures_proforma;
        }

        // dd($la_facture);

        // Retourner la réponse JSON ou vue

        // Si vous souhaitez retourner une réponse JSON
        // return response()->json([
        //     'facture' => $la_facture,
        //     'detail_facture' => $detail_facture,
        // ]);

        // Si vous souhaitez retourner une vue
        return view('pages.rapport.rapport_vente_journaliere_detail', [
            'facture' => $la_facture,
            'detail_facture' => $detail_facture,
        ]);

    }
}
