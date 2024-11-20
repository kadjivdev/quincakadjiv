<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleFacture;
use App\Models\ArticlePointVente;
use App\Models\BonLivraison;
use App\Models\Client;
use App\Models\Devis;
use App\Models\DevisDetail;
use App\Models\Facture;
use App\Models\LivraisonClient;
use App\Models\Magasin;
use App\Models\StockMagasin;
use App\Models\TauxConversion;
use App\Models\UniteMesure;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LivraisonClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {

        $bon_liv_clt = BonLivraison::select(
            'bon_livraisons.*',
            'devis.reference as devis_reference',
            'chauffeurs.nom_chauf as nom_chauf',
            'vehicules.num_vehicule as num_vehicule',
            DB::raw('(SELECT COUNT(*) FROM livraison_clients WHERE livraison_clients.bon_livraison_id = bon_livraisons.id AND livraison_clients.validated_at IS NOT NULL) as lignes_valides'),
            DB::raw('(SELECT COUNT(*) FROM livraison_clients WHERE livraison_clients.bon_livraison_id = bon_livraisons.id AND livraison_clients.validated_at IS NULL) as lignes_non_valides'),
            DB::raw('(SELECT COUNT(*) FROM livraison_clients WHERE livraison_clients.bon_livraison_id = bon_livraisons.id AND livraison_clients.comment_at IS NOT NULL) as lignes_comment')
        )
            ->join('devis', 'devis.id', '=', 'bon_livraisons.devis_id')
            ->join('chauffeurs', 'chauffeurs.id', '=', 'bon_livraisons.chauffeur_id')
            ->join('vehicules', 'vehicules.id', '=', 'bon_livraisons.vehicule_id')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('livraison_clients')
                    ->whereRaw('livraison_clients.bon_livraison_id = bon_livraisons.id')
                    ->whereNull('livraison_clients.validated_at');
            })
            ->orderBy('bon_livraisons.id', 'desc')
            ->get();

        return view('pages.ventes-module.livraisons.index', compact('bon_liv_clt'));
    }


    public function show($bon)
    {
        $i = 1;

        $bon_data = BonLivraison::join('devis', 'devis.id', '=', 'bon_livraisons.devis_id')
            ->where('bon_livraisons.id', $bon)
            ->first();

        $livraisons = DB::table('livraison_clients')
            ->where('livraison_clients.bon_livraison_id', '=', $bon)
            ->join('bon_livraisons', 'bon_livraisons.id', '=', 'livraison_clients.bon_livraison_id')
            ->join('devis', 'devis.id', '=', 'bon_livraisons.devis_id')
            ->join('devis_details', function ($join) {
                $join->on('devis.id', '=', 'devis_details.devis_id')
                    ->on('livraison_clients.article_id', '=', 'devis_details.article_id');
            })
            ->leftJoin('articles', 'devis_details.article_id', '=', 'articles.id')
            ->leftJoin('clients', 'clients.id', '=', 'devis.client_id')
            ->leftJoin('unite_mesures', 'unite_mesures.id', '=', 'livraison_clients.unite_mesure_id')
            ->leftJoin('chauffeurs', 'chauffeurs.id', '=', 'bon_livraisons.chauffeur_id')
            ->leftJoin('vehicules', 'vehicules.id', '=', 'bon_livraisons.vehicule_id')
            ->select(
                'livraison_clients.id as id',
                'livraison_clients.created_at as created_at',
                'livraison_clients.id as livraison_id',
                'articles.id as article_id',
                'articles.nom as article_nom',
                'clients.nom_client',
                'unite_mesures.unite',
                'livraison_clients.qte_livre',
                'devis_details.prix_unit',
                'bon_livraisons.date_livraison',
                'chauffeurs.nom_chauf',
                'vehicules.num_vehicule',
                'livraison_clients.validated_at as validated_at',
                'livraison_clients.comment',

            )
            ->distinct()
            ->get();

        // return response()->json([
        //     'clients'   => $livraisons,
        // ]);

        return view('pages.ventes-module.livraisons.show', compact('livraisons', 'i', 'bon_data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $devis = Devis::whereIn('id', function ($query) {
            $query->select('devis_id')
                ->from('factures');
        })->orderByDesc('id')
            ->get();

        // $devis = Devis::whereNull('validated_at')->orderBy('id', 'desc')->get();
        $vehicules = Vehicule::all();
        $point = Auth::user()->boutique;
        if ($point) {
            $magasins = $point->magasins;
        }

        $clients = Client::all();

        return view('pages.ventes-module.livraisons.create', compact('devis', 'magasins', 'vehicules', 'clients'));
    }

    public function validation($ligne)
    {

        DB::beginTransaction();

        try {

            $point_vente = Auth::user()->point_vente_id;

            $livraison_ligne = LivraisonClient::find($ligne);

            $bon_livraison = BonLivraison::find($livraison_ligne->bon_livraison_id);

            $ligne_devis = DevisDetail::where('article_id', $livraison_ligne->article_id)->where('devis_id', $bon_livraison->devis_id)->first();

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


            $ligne_devis->update([
                'qte_cmde' => $ligne_devis->qte_cmde - (float)$livraison_ligne->qte_livre
            ]);

            $livraison_ligne->validator_id = Auth::user()->id;
            $livraison_ligne->validated_at = now();
            $livraison_ligne->save();

            DB::commit();
            return redirect()->route('deliveries.show', $livraison_ligne->bon_livraison_id)->with('success', 'Livraison enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('deliveries.show', $livraison_ligne->bon_livraison_id)->withErrors(['message' => 'Erreur enregistrement Bon de commande ' . $e->getMessage()]);
        }


        return view('pages.ventes-module.livraisons.show', $livraison_ligne->bon_livraison_id);
    }


    public function rejeter(Request $request, $ligne)
    {
        DB::beginTransaction();

        try {


            $livraison_ligne = LivraisonClient::find($ligne);

            $bon_livraison = BonLivraison::find($livraison_ligne->bon_livraison_id);

            $livraison_ligne->comment = $request->comment;

            $livraison_ligne->comment_at = now();

            $livraison_ligne->save();


            DB::commit();
            return redirect()->route('deliveries.show', $livraison_ligne->bon_livraison_id)->with('success', 'Commentaire enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('deliveries.show', $livraison_ligne->bon_livraison_id)->withErrors(['message' => 'Erreur enregistrement ' . $e->getMessage()]);
        }

        return view('pages.ventes-module.livraisons.show', $livraison_ligne->bon_livraison_id);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'devis_id' => 'required',
            'magasin_id' => 'required',
            'chauffeur_id' => 'required',
            'adr_livraison' => 'required',
            'vehicule_id' => 'required',
            // 'tel_chauffeur' => 'required',
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'prixUnits.*' => 'required',
            'unites.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $point_vente = Auth::user()->point_vente_id;

        $count = count($request->qte_cdes);

        DB::beginTransaction();

        try {

            $nbr = BonLivraison::max('id');

            $code = formaterCodeBon($nbr + 1);
            $bonLivraison = BonLivraison::create([
                'date_livraison' => now(),
                'vehicule_id' => $request->vehicule_id,
                'devis_id' => $request->devis_id,
                'code_bon' => $code,
                'chauffeur_id' => $request->chauffeur_id,
                'adr_livraison' => $request->adr_livraison,
            ]);

            for ($i = 0; $i < $count; $i++) {

                $ligne = DevisDetail::where('article_id', $request->articles[$i])->where('devis_id', $request->devis_id)->first();
                $element = ArticlePointVente::where('article_id', $request->articles[$i])->where('point_vente_id', $point_vente)->first();

                $unite = UniteMesure::where('unite', $request->unites[$i])->first();
                $article = Article::find($request->articles[$i]);
                $conversionItem = $article->getPivotValueForUnite($request->unites[$i]);
                if (!is_null($conversionItem)) {
                    if ($conversionItem < 1) {
                        $qte_vrai = $request->qte_cdes[$i] / $conversionItem;
                    } else {
                        $qte_vrai = $request->qte_cdes[$i] * $conversionItem;
                    }
                } else {
                    return redirect()->route('deliveries.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }

                $magasin = Magasin::find($request->magasin_id);
                $stockMag = StockMagasin::where('article_id', $request->articles[$i])->where('magasin_id', $magasin->id)->first();

                if ($stockMag && $stockMag->qte_stock >= $qte_vrai) {
                    $qte_stock = $stockMag->qte_stock - (float)$qte_vrai;
                    $stockMag->qte_stock = $qte_stock;
                    $stockMag->save();
                } else {
                    return redirect()->route('deliveries.create')->withErrors(['message' => 'Quantité insufisante pour l\'article ' . $article->nom]);
                }

                if ($element) {
                    $qte_stock = $element->qte_stock - (float)$qte_vrai;
                    $element->qte_stock = $qte_stock;
                    $element->save();
                }

                LivraisonClient::create([
                    'qte_livre' => $request->qte_cdes[$i],
                    'magasin_id' => $request->magasin_id,
                    'article_id' => $request->articles[$i],
                    'prix_unit' => $request->prixUnits[$i],
                    'bon_livraison_id' => $bonLivraison->id,
                    'unite_mesure_id' => $request->unites[$i],
                    'user_id' => Auth::user()->id,
                ]);

                // $ligne->update([
                //     'qte_cmde' => $ligne->qte_cmde - (float)$request->qte_cdes[$i]
                // ]);
            }

            DB::commit();
            return redirect()->route('deliveries.index')->with('success', 'Livraison enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('deliveries.index')->withErrors(['message' => 'Erreur enregistrement Bon de commande ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Tente de trouver l'enregistrement LivraisonClient avec l'ID du bon de livraison
        $liv = LivraisonClient::where('bon_livraison_id', $id)->first();

        // Vérifie si l'enregistrement a été trouvé
        if (!$liv) {
            // Optionnel : ajouter un message d'erreur à la session
            session()->flash('error', 'La livraison demandée n\'a pas été trouvée.');

            // Redirige vers la route des livraisons
            return redirect()->route('deliveries.index');
        }

        // Récupère l'identifiant de bon_livraison depuis l'enregistrement trouvé
        $bon_id = $liv->bon_livraison_id;

        // Supprime l'enregistrement LivraisonClient
        $liv->delete();

        // Vérifie si l'enregistrement BonLivraison existe avant de tenter de le supprimer
        if ($bon_id) {
            $bonLivraison = BonLivraison::find($bon_id);
            if ($bonLivraison) {
                $bonLivraison->delete();
            }
        }

        // Optionnel : ajouter un message de succès à la session
        session()->flash('success', 'La livraison et l\'enregistrement associé ont été supprimés avec succès.');

        // Redirige vers la route des livraisons
        return redirect()->route('deliveries.index');
    }


    public function supprimerLivraison(int $id)
    {
        // Trouver la livraison par son ID
        $liv = LivraisonClient::find($id);

        // Vérifier si la livraison existe
        if (!$liv) {
            return redirect()->route('deliveries.index')->with('error', 'La livraison demandée n\'a pas été trouvée.');
        }

        // Récupérer l'ID du bon de livraison
        $bon_id = $liv->bon_livraison_id;

        // Supprimer la livraison
        $liv->delete();

        // Rediriger avec un message de succès
        return redirect()->route('deliveries.show', $bon_id)->with('success', 'Livraison supprimée avec succès.');
    }
}
