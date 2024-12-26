<?php

namespace App\Http\Controllers;

use App\Models\BonLivraisonVenteComptant;
use App\Models\BonVente;
use App\Models\Chauffeur;
use App\Models\LivraisonClientVenteComptant;
use App\Models\LivraisonVenteMagasin;
use App\Models\Magasin;
use App\Models\PointVente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\VenteLigne;
use App\Models\StockMagasin;
use App\Models\Vehicule;
use App\Models\Vente;
use App\Models\ArticlePointVente;
use App\Models\UniteMesure;
use App\Models\Article;

class LivraisonVenteComptController extends Controller
{
    public function index()
    {
        $bon_liv_clt = BonLivraisonVenteComptant::select(
            'bon_livraison_vente_comptants.*',
            'ventes.reference as vente_reference',
            'chauffeurs.nom_chauf as nom_chauf',
            'vehicules.num_vehicule as num_vehicule',
            DB::raw('(SELECT COUNT(*) FROM livraison_vente_magasins WHERE livraison_vente_magasins.bon_livraison_vente_comptant_id = bon_livraison_vente_comptants.id AND livraison_vente_magasins.validated_at IS NOT NULL) as lignes_valides'),
            DB::raw('(SELECT COUNT(*) FROM livraison_vente_magasins WHERE livraison_vente_magasins.bon_livraison_vente_comptant_id = bon_livraison_vente_comptants.id AND livraison_vente_magasins.validated_at IS NULL) as lignes_non_valides'),
            DB::raw('(SELECT COUNT(*) FROM livraison_vente_magasins WHERE livraison_vente_magasins.bon_livraison_vente_comptant_id = bon_livraison_vente_comptants.id AND livraison_vente_magasins.comment_at IS NOT NULL) as lignes_comment')
        )
            ->join('ventes', 'ventes.id', '=', 'bon_livraison_vente_comptants.vente_id')
            ->join('chauffeurs', 'chauffeurs.id', '=', 'bon_livraison_vente_comptants.chauffeur_id')
            ->join('vehicules', 'vehicules.id', '=', 'bon_livraison_vente_comptants.vehicule_id')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('livraison_vente_magasins')
                    ->whereRaw('livraison_vente_magasins.bon_livraison_vente_comptant_id = bon_livraison_vente_comptants.id')
                    ->whereNull('livraison_vente_magasins.validated_at');
            })
            ->orderBy('bon_livraison_vente_comptants.id', 'desc')
            ->get();


        return view('pages.ventes-module.ventes.bons.index', compact('bon_liv_clt'));
    }


    public function create()
    {
        // $bons = BonVente::with(['vente'])->where('validated_at', '=', null)->get();
        // $magasin = Magasin::where('point_vente_id',  Auth::user()->point_vente_id)->first();

        $vehicules = Vehicule::all();
        $chauffeurs = Chauffeur::all();
        $point = PointVente::find(Auth::user()->point_vente_id);
        $users = $point->users()->pluck('id');
        $magasins = Magasin::where('point_vente_id', Auth::user()->point_vente_id)->get();
        $bons = DB::table('bon_ventes')
            ->join('ventes', 'ventes.id', '=', 'bon_ventes.vente_id')
            ->join('users', 'users.id', '=', 'ventes.user_id')
            ->join('clients', 'clients.id', '=', 'ventes.client_id')
            ->whereIn('ventes.user_id', $users)
            ->orderBy('id', 'desc')
            ->select('bon_ventes.*', 'clients.nom_client')
            ->get();

        $clientsAvecBons = DB::table('bon_ventes')
            ->join('ventes', 'ventes.id', '=', 'bon_ventes.vente_id')
            ->join('clients', 'clients.id', '=', 'ventes.client_id')
            ->whereIn('ventes.user_id', $users)
            ->distinct() // Assure qu'on récupère uniquement des clients uniques
            ->select('clients.id', 'clients.nom_client')
            ->orderBy('clients.nom_client', 'asc') // Tri optionnel par nom
            ->get();

        // dd($bons);

        return view('pages.ventes-module.ventes.livraison', compact('bons', 'magasins', 'vehicules', 'chauffeurs', 'clientsAvecBons'));
    }

    public function getBonsParClient($client_id)
    {
        $bons = BonVente::whereHas('vente', function ($query) use ($client_id) {
            $query->where('client_id', $client_id);
        })
            ->orderBy('created_at', 'desc')
            ->get(['id', 'code_bon']); // On récupère uniquement les champs nécessaires

        return response()->json($bons);
    }

    // public function create() {

    //     $vehicules = Vehicule::all();
    //     $point = PointVente::find(Auth::user()->point_vente_id);
    //     $users = $point->users()->pluck('id');
    //     $magasins = Magasin::where('point_vente_id', Auth::user()->point_vente_id)->pluck('id');
    //     $bons = DB::table('bon_ventes')
    //     ->join('ventes', 'ventes.id', '=', 'bon_ventes.vente_id')
    //     ->join('users', 'users.id', '=', 'ventes.user_id')
    //     ->join('clients', 'clients.id', '=', 'ventes.client_id')
    //     ->whereIn('ventes.user_id', $users)
    //     ->select('bon_ventes.*', 'clients.nom_client')
    //     ->get();

    //     return view('pages.ventes-module.ventes.livraison', compact('bons', 'vehicules','magasins'));

    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qte_livre' => 'required',
            'magasin_id' => 'required',
            'bon_vente_id' => 'required',
            'unite.*' => 'required',
            'qte_livre.*' => 'required',
            'vente_lignes.*' => 'required',
            'article.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // dd($request->all());
        // dd($request->bon_vente_id);
        $count = count($request->qte_livre);
        DB::beginTransaction();

        try {

            $nbr = BonLivraisonVenteComptant::max('id');
            $code = formaterCodeBon($nbr + 1);
            $vente = BonVente::findOrFail($request->bon_vente_id);

            $bonLivraison = BonLivraisonVenteComptant::create([
                'date_livraison' => now(),
                'vehicule_id' => $request->vehicule_id,
                'vente_id' => $vente->vente_id,
                'ref_bon' => $code,
                'chauffeur_id' => $request->chauffeur_id,
                'adr_livraison' => $request->adr_livraison,
            ]);

            for ($i = 0; $i < $count; $i++) {
                $ligne = VenteLigne::findOrFail($request->vente_lignes[$i]);
                // dd($ligne);

                $livraison = LivraisonVenteMagasin::create([

                    'vente_ligne_id' => $request->vente_lignes[$i],
                    'qte_livre' => $request->qte_livre[$i],
                    'bon_vente_id' => $request->bon_vente_id,
                    'magasin_id' => $request->magasin_id,
                    'bon_livraison_vente_comptant_id' => $bonLivraison->id,
                    'user_id' => Auth::id(),
                    'statut' => 'Non livré',
                    'unite_mesure_id' => $request->unite[$i],
                    'article_id' => $request->article[$i],


                ]);

                // $stock = StockMagasin::where('article_id', $ligne->article_id)
                //     ->where('magasin_id', $request->magasin_id)
                //     ->where('qte_stock', '>', 0)
                //     ->first();

                // if ($stock) {
                //     $stock->update([
                //         'qte_stock' => $stock->qte_stock - (float)$request->qte_livre[$i],
                //     ]);
                // }else {
                //     return redirect()->back()->withErrors(['message' => 'Stock non suffisant']);
                // }

                // $ligne->update([
                //     'qte_livre' => $ligne->qte_livre - (float)$request->qte_livre[$i],
                // ]);
            }

            DB::commit();
            return redirect()->route('bons-ventes.index')->with('success', 'Livraison ajoutée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);

            // return redirect()->route('bons-ventes.index')->withErrors(['message' => $e.' Erreur enregistrement livraison vente']);
        }
    }

    public function validation($ligne)
    {

        DB::beginTransaction();

        try {

            $point_vente = Auth::user()->point_vente_id;

            $livraison_ligne = LivraisonVenteMagasin::find($ligne);

            $bon_livraison = BonLivraisonVenteComptant::find($livraison_ligne->bon_livraison_vente_comptant_id);

            $ligne_ventes = VenteLigne::where('article_id', $livraison_ligne->article_id)->where('vente_id', $bon_livraison->vente_id)->first();

            $element = ArticlePointVente::where('article_id', $livraison_ligne->article_id)->where('point_vente_id', $point_vente)->first();

            $unite = UniteMesure::where('unite', $livraison_ligne->unite_mesure_id)->first();

            $article = Article::find($livraison_ligne->article_id);

            $conversionItem = $article->getPivotValueForUnite($livraison_ligne->unite_mesure_id);
            if (!is_null($conversionItem)) {
                if ($conversionItem < 1) {
                    $qte_vrai = $livraison_ligne->qte_livre / $conversionItem;
                } else {
                    $qte_vrai = $livraison_ligne->qte_livre * $conversionItem;
                }
            } else {
                return redirect()->route('validation-liv-clt')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
            }

            $magasin = Magasin::find($livraison_ligne->magasin_id);

            $stockMag = StockMagasin::where('article_id', $livraison_ligne->article_id)->where('magasin_id', $magasin->id)->first();

            if ($stockMag && $stockMag->qte_stock >= $qte_vrai) {
                $qte_stock = $stockMag->qte_stock - (float)$qte_vrai;
                $stockMag->qte_stock = $qte_stock;
                $stockMag->save();
            } else {
                return redirect()->route('validation-liv-clt')->withErrors(['message' => 'Quantité insufisante pour l\'article ' . $article->nom]);
            }

            if ($element) {
                $qte_stock = $element->qte_stock - (float)$qte_vrai;
                $element->qte_stock = $qte_stock;
                $element->save();
            }


            $ligne_ventes->update([
                'qte_livre' => $ligne_ventes->qte_livre - (float)$livraison_ligne->qte_livre
            ]);

            $livraison_ligne->validator_id = Auth::user()->id;
            $livraison_ligne->validated_at = now();
            $livraison_ligne->save();

            DB::commit();
            return redirect()->route('bons-ventes.show', ['bons_vente' => $livraison_ligne->bon_livraison_vente_comptant_id])
                ->with('success', 'Livraison enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('bons-ventes.show', ['bons_vente' => $livraison_ligne->bon_livraison_vente_comptant_id])
                ->withErrors(['message' => 'Erreur enregistrement Bon de commande ' . $e->getMessage()]);
        }


        return view('pages.ventes-module.ventes.bons.show', $livraison_ligne->bon_livraison_vente_comptant_id);
    }



    public function rejeter(Request $request, $ligne)
    {
        DB::beginTransaction();

        try {


            $livraison_ligne = LivraisonVenteMagasin::find($ligne);

            $bon_livraison = BonLivraisonVenteComptant::find($livraison_ligne->bon_livraison_vente_comptant_id);

            $livraison_ligne->comment = $request->comment;

            $livraison_ligne->comment_at = now();

            $livraison_ligne->save();


            DB::commit();
            return redirect()->route('bons-ventes.show', $livraison_ligne->bon_livraison_vente_comptant_id)->with('success', 'Commentaire enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('bons-ventes.show', $livraison_ligne->bon_livraison_vente_comptant_id)->withErrors(['message' => 'Erreur enregistrement ' . $e->getMessage()]);
        }

        return view('pages.ventes-module.livraisons.show', $livraison_ligne->bon_livraison_vente_comptant_id);
    }

    public function show($bon)
    {
        $i = 1;

        // Rechercher les données de bon_livraison_vente_comptant
        $bon_data = BonLivraisonVenteComptant::join('ventes', 'ventes.id', '=', 'bon_livraison_vente_comptants.vente_id')
            ->where('bon_livraison_vente_comptants.id', $bon)
            ->first();

        // Rechercher les livraisons liées au bon
        $livraisons = DB::table('livraison_vente_magasins')
            ->join('bon_livraison_vente_comptants', 'bon_livraison_vente_comptants.id', '=', 'livraison_vente_magasins.bon_livraison_vente_comptant_id')
            ->join('ventes', 'ventes.id', '=', 'bon_livraison_vente_comptants.vente_id')
            ->join('vente_lignes', function ($join) {
                $join->on('ventes.id', '=', 'vente_lignes.vente_id')
                    ->on('livraison_vente_magasins.article_id', '=', 'vente_lignes.article_id');
            })
            ->leftJoin('articles', 'vente_lignes.article_id', '=', 'articles.id')
            ->leftJoin('clients', 'clients.id', '=', 'ventes.client_id')
            ->leftJoin('unite_mesures', 'unite_mesures.id', '=', 'livraison_vente_magasins.unite_mesure_id')
            ->leftJoin('chauffeurs', 'chauffeurs.id', '=', 'bon_livraison_vente_comptants.chauffeur_id')
            ->leftJoin('vehicules', 'vehicules.id', '=', 'bon_livraison_vente_comptants.vehicule_id')
            ->where('livraison_vente_magasins.bon_livraison_vente_comptant_id', $bon)
            ->select(
                'livraison_vente_magasins.id as id',
                'livraison_vente_magasins.created_at as created_at',
                'articles.id as article_id',
                'articles.nom as article_nom',
                'clients.nom_client',
                'unite_mesures.unite',
                'livraison_vente_magasins.qte_livre',
                'vente_lignes.prix_unit',
                'bon_livraison_vente_comptants.date_livraison',
                'chauffeurs.nom_chauf',
                'vehicules.num_vehicule',
                'livraison_vente_magasins.validated_at as validated_at',
                'livraison_vente_magasins.comment'
            )
            ->distinct()
            ->get();

        // Vérifier si des données sont trouvées
        if ($livraisons->isEmpty()) {
            return response()->json(['message' => 'Aucune livraison trouvée pour ce bon.'], 404);
        }

        // Retourner les données JSON
        // return response()->json([
        //     'clients' => $livraisons,
        //     'bon_data' => $bon_data
        // ]);

        return view('pages.ventes-module.ventes.bons.show', compact('livraisons', 'i', 'bon_data'));
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Tente de trouver l'enregistrement LivraisonClient avec l'ID du bon de livraison
        $liv = LivraisonVenteMagasin::where('bon_livraison_vente_comptant_id', $id)->first();

        // Vérifie si l'enregistrement a été trouvé
        if (!$liv) {
            // Optionnel : ajouter un message d'erreur à la session
            session()->flash('error', 'La livraison demandée n\'a pas été trouvée.');

            // Redirige vers la route des livraisons
            return redirect()->route('bons-ventes.index');
        }

        // Récupère l'identifiant de bon_livraison depuis l'enregistrement trouvé
        $bon_id = $liv->bon_livraison_vente_comptant_id;

        // Supprime l'enregistrement LivraisonClient
        $liv->delete();

        // Vérifie si l'enregistrement BonLivraison existe avant de tenter de le supprimer
        if ($bon_id) {
            $bonLivraison = BonLivraisonVenteComptant::find($bon_id);
            if ($bonLivraison) {
                $bonLivraison->delete();
            }
        }

        // Optionnel : ajouter un message de succès à la session
        session()->flash('success', 'La livraison et l\'enregistrement associé ont été supprimés avec succès.');

        // Redirige vers la route des livraisons
        return redirect()->route('bons-ventes.index');
    }
}
