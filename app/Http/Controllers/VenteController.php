<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\Article;
use App\Models\ArticlePointVente;
use App\Models\BonVente;
use App\Models\Client;
use App\Models\DevisDetail;
use App\Models\FactureType;
use App\Models\FactureVente;
use App\Models\Magasin;
use App\Models\PointVente;
use App\Models\StockMagasin;
use App\Models\TauxConversion;
use App\Models\TypeVente;
use App\Models\UniteMesure;
use App\Models\User;
use App\Models\Vente;
use App\Models\VenteLigne;
use App\Models\CompteClient;
use App\Models\dataVente;
use App\Models\Encaisse;
use App\Models\Encaissement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $i = 1;
        $user = Auth::user();
        if ($user->isAdmin()) {
            // $ventes = Vente::orderBy('id', 'desc')->get();
            // $ventes = Vente::all();
            $ventes = Vente::with('factureVente')->orderBy('ventes.id', 'desc')->get();
            // dd($ventes[10]);
        } elseif (Auth::user()->hasRole('CHARGE DES STOCKS ET SUIVI DES ACHATS')) {
            $ventes = Vente::with('factureVente')->orderBy('ventes.id', 'desc')->get();
        }else {
            // $ventes = Vente::where('user_id', $user->id)->get();
            // $ventes = Vente::join('facture_ventes', 'ventes.id', '=', 'facture_ventes.vente_id')
            // ->select('ventes.*', 'facture_ventes.date_facture')
            // ->where('ventes.user_id', $user->id)
            // ->orderBy('ventes.id', 'desc')
            // ->get();
            $ventes = Vente::where('user_id', $user->id)->with('factureVente')->orderBy('ventes.id', 'desc')->get();
        }
        $getClientById = function ($client_id) {
            return Client::find($client_id);
        };

        // return response()->json([
        //     'ventes'  => $ventes
        // ]);
        return view('pages.ventes-module.ventes.index', compact('ventes', 'getClientById', 'i'));
    }

    public function show_for_caisse()
    {
        $i = 1;
        $user = Auth::user();

        $ventes = Vente::with('factureVente')->orderBy('ventes.id', 'desc')->get();
        // dd($ventes[10]);

        $getClientById = function ($client_id) {
            return Client::find($client_id);
        };

        return view('pages.ventes-module.ventes.index_view', compact('ventes', 'getClientById', 'i'));
    }

    public function showEdit($id)
    {
        $pointVendueId = Auth::user()->point_vente_id;


        $articles = PointVente::find($pointVendueId)
            ->articles()
            ->wherePivot('qte_stock', '>', 0)
            ->select(
                'articles.*',
                'qte_stock',
                'prix_special',
                'prix_revendeur',
                'prix_particulier',
                'prix_btp'
            )
            ->get();

        $types = FactureType::all();
        $typeVentes = TypeVente::all();
        $vente = Vente::find($id);
        $facture = FactureVente::where('vente_id', $vente->id)->first();
        $typeFacture = $facture->facture_type_id;
        $typeFactureLib = FactureType::find($typeFacture)->libelle;
        $venteLignes = VenteLigne::where('vente_id', $vente->id)->get();
        $montantTotal =  $facture->montant_total;
        $tva =  $facture->tva;
        $montant_regle =  $facture->montant_regle;
        $taux_remise =  $facture->taux_remise;

        $aib =  $facture->aib;
        $client = Client::find($facture->client_facture);
        $montantFacture =  $facture->montant_facture;
        $typeVenteLib = TypeVente::find($vente->type_vente_id)->libelle;
        $details = [];

        foreach ($venteLignes as $eachVenteLigne) {
            $articleId = $eachVenteLigne->article_id;
            $quantite = $eachVenteLigne->qte_cmde;
            $prix_unit = $eachVenteLigne->prix_unit;
            $unite_mesure_id = $eachVenteLigne->unite_mesure_id;
            // Retrieve the details of the article based on its id
            $article = Article::find($articleId);

            if ($article) {
                // Store the details of the article along with its quantity
                $details[] = [
                    'article' => [
                        'libelle' => $article->nom,
                        'prix_unitaire' => $prix_unit,
                    ],
                    'quantite' => $quantite,
                    'unite_mesure_id' =>  UniteMesure::find($unite_mesure_id)->unite
                ];
            }
        }

        // $details now contains an array of arrays, each representing an article along with its quantity



        // dd($details);
        return view('pages.ventes-module.ventes.edit', compact('typeFactureLib', 'montant_regle', 'taux_remise',  'typeVenteLib', 'client',  'aib', 'montantFacture', 'tva', 'montantTotal', 'vente', 'details',  'articles', 'types', 'typeVentes', 'typeFacture'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pointVendueId = Auth::user()->point_vente_id;


        $articles = PointVente::find($pointVendueId)
            ->articles()
            ->wherePivot('qte_stock', '>', 0)
            ->select(
                'articles.*',
                'qte_stock',
                'prix_special',
                'prix_revendeur',
                'prix_particulier',
                'prix_btp'
            )
            ->get();

        $types = FactureType::all();
        $typeVentes = TypeVente::all();
        // dd($articles);
        return view('pages.ventes-module.ventes.create', compact('articles', 'types', 'typeVentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'prixUnits.*' => 'required',
            'unites.*' => 'required',
            'montant_facture' => 'required',
            'montant_total' => 'required',
            'montant_regle' => 'required',
            'taux_remise' => 'required',
            'type_id' => 'required',
            'date_fact' => 'required',
            // 'magasin_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // dd($request->all());
        $point_vente = Auth::user()->boutique;
        $count = count($request->qte_cdes);

        DB::beginTransaction();

        try {

            $nbrv = Vente::max('id');
            $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));

            $vente = Vente::create([
                'user_id' => Auth::user()->id,
                'type_vente_id' => $request->type_vente_id,
                'montant' => $request->montant_regle,
                'client_id' => $request->client_id,
                'date_vente' => $request->date_fact,
                'reference' => 'KAD-'. 'VTEC' . ($nbrv + 1).'-'.date('dmY') . '-' . $lettres,

            ]);

            $nbr = BonVente::max('id');
            $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));

            BonVente::create([
                'vente_id' => $vente->id,
                'code_bon' => 'KAD-'. 'BVC' . ($nbr + 1).'-'.date('dmY') . '-' . $lettres,
            ]);

            for ($i = 0; $i < $count; $i++) {
                $element = ArticlePointVente::where('article_id', $request->articles[$i])->where('point_vente_id', $point_vente->id)->first();
                $unite_base = Article::find($request->articles[$i])->unite_mesure_id;
                $article = Article::find($request->articles[$i]);

                $conversionItem = $article->getPivotValueForUnite($request->unites[$i]);
                if (!is_null($conversionItem)) {
                    $tauxConversion = $conversionItem;
                    if ($tauxConversion < 1){
                        $qte_vrai = $request->qte_cdes[$i] / $tauxConversion;
                    }else{
                        $qte_vrai = $request->qte_cdes[$i] * $tauxConversion;
                    }
                } else {
                    return redirect()->route('ventes.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }
                if ($element) {
                    $qte_stock = $element->qte_stock - (float)$qte_vrai;
                    $element->update(['qte_stock' => $qte_stock]);
                }
                // if (!is_null($point_vente->magasins[0]->id)) {
                //     $magasin = Magasin::findOrFail($point_vente->magasins[0]->id);
                //     $magasin_stock = StockMagasin::where('article_id', $request->articles[$i])
                //         ->where('magasin_id', $magasin->id)
                //         ->where('qte_stock', '>=', $request->qte_cdes[$i])
                //         ->first();
                //     if (!is_null($magasin_stock)) {
                //         $magasin_stock->qte_stock = $magasin_stock->qte_stock - (float)$qte_vrai;
                //         $magasin_stock->save();
                //     } else {
                //         return redirect()->route('ventes.index')->withErrors(['message' => 'Aucun magasin n\'a cette quantité en stock.']);
                //     }
                // }
                // dd([$conversionItem, $qte_vrai, $element]);

                $appro = VenteLigne::create([
                    'qte_cmde' => $request->qte_cdes[$i],
                    'qte_livre' => $request->qte_cdes[$i],
                    'article_id' => $request->articles[$i],
                    'prix_unit' => $request->prixUnits[$i],
                    'vente_id' => $vente->id,
                    'unite_mesure_id' => $request->unites[$i],
                ]);
            }

            dataVente::create([
                'montant_facture' => $request->montant_facture,
                'taux_remise' => (float)$request->taux_remise,
                'montant_total' => $request->montant_total,
                'montant_regle' => $request->montant_regle,
                'facture_type_id' => $request->type_id,
                'vente_id' => $vente->id,
                'tva' => (float)$request->tva,
                'aib' => (float)$request->aib,
            ]);


            DB::commit();
            return redirect()->route('ventes.index')->with('success', 'Vente enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return redirect()->route('ventes.index')->withErrors(['message' => 'Erreur enregistrement vente']);
        }
    }

    public function validateVente($id)
    {
        $vente = Vente::findOrFail($id);
        $data_vente = dataVente::where('vente_id', $id)->first();
        // dd($request->all());

        DB::beginTransaction();

        try {

            $nbr = FactureVente::max('id');
            $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));

            $facture = FactureVente::create([
                'date_facture' => $vente->date_vente,
                'vente_id' => $vente->id,
                'statut' => 'Soldée',
                'montant_facture' => $data_vente->montant_facture,
                'montant_total' => $data_vente->montant_total,
                'montant_regle' => $data_vente->montant_regle,
                'taux_remise' => (float)$data_vente->taux_remise,
                'num_facture' => 'KAD-'. 'FVC' . ($nbr + 1).'-'.date('dmY') . '-' . $lettres,

                // 'num_facture' => 'FV' . date('dmY') . ($nbr + 1),
                'user_id' => Auth::user()->id,
                'facture_type_id' => $data_vente->facture_type_id,
                'client_facture' => $vente->client_id,
                'tva' => $data_vente->tva,
                'aib' => $data_vente->aib,
            ]);


            $compte_client = CompteClient::create([
                'date_op' => $facture->date_facture,
                'montant_op' => $facture->montant_total,
                'facture_id' => $facture->id,
                'client_id' => $vente->client_id,
                'user_id'=> Auth::user()->id,
                'type_op' => 'FAC_VC',
                'cle' => $facture->id,
            ]);

            $compte_client_reg = CompteClient::create([
                'date_op' => $facture->date_facture,
                'montant_op' => $facture->montant_total,
                'facture_id' => $facture->id,
                'client_id' => $vente->client_id,
                'user_id'=> Auth::user()->id,
                'type_op' => 'REG_VC',
                'cle' => $facture->id,
            ]);

            $vente->validated_at = now();
            $vente->validator_id = Auth::id();
            $vente->save();

            DB::commit();
            return redirect()->route('ventes.index')->with('success', 'Vente validée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return redirect()->route('ventes.index')->withErrors(['message' => 'Erreur validation vente '.$e->getMessage()]);
        }
    }

    public function validateVenteBackup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'prixUnits.*' => 'required',
            'unites.*' => 'required',
            'montant_facture' => 'required',
            'montant_total' => 'required',
            'montant_regle' => 'required',
            'taux_remise' => 'required',
            'type_id' => 'required',
            'date_fact' => 'required',
            // 'magasin_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // dd($request->all());
        $point_vente = Auth::user()->boutique;
        $count = count($request->qte_cdes);

        DB::beginTransaction();

        try {
            $vente = Vente::create([
                'user_id' => Auth::user()->id,
                'type_vente_id' => $request->type_vente_id,
                'montant' => $request->montant_regle,
                'client_id' => $request->client_id,
            ]);

            BonVente::create([
                'vente_id' => $vente->id,
            ]);

            for ($i = 0; $i < $count; $i++) {
                $element = ArticlePointVente::where('article_id', $request->articles[$i])->where('point_vente_id', $point_vente->id)->first();
                $unite_base = Article::find($request->articles[$i])->unite_mesure_id;
                $article = Article::find($request->articles[$i]);

                $conversionItem = $article->getPivotValueForUnite($request->unites[$i]);
                if (!is_null($conversionItem)) {
                    $tauxConversion = $conversionItem;
                    if ($tauxConversion < 1){
                        $qte_vrai = $request->qte_cdes[$i] / $tauxConversion;
                    }else{
                        $qte_vrai = $request->qte_cdes[$i] * $tauxConversion;
                    }
                } else {
                    return redirect()->route('ventes.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }
                if ($element) {
                    $qte_stock = $element->qte_stock - (float)$qte_vrai;
                    $element->update(['qte_stock' => $qte_stock]);
                }
                // if (!is_null($point_vente->magasins[0]->id)) {
                //     $magasin = Magasin::findOrFail($point_vente->magasins[0]->id);
                //     $magasin_stock = StockMagasin::where('article_id', $request->articles[$i])
                //         ->where('magasin_id', $magasin->id)
                //         ->where('qte_stock', '>=', $request->qte_cdes[$i])
                //         ->first();
                //     if (!is_null($magasin_stock)) {
                //         $magasin_stock->qte_stock = $magasin_stock->qte_stock - (float)$qte_vrai;
                //         $magasin_stock->save();
                //     } else {
                //         return redirect()->route('ventes.index')->withErrors(['message' => 'Aucun magasin n\'a cette quantité en stock.']);
                //     }
                // }
                // dd([$conversionItem, $qte_vrai, $element]);

                $appro = VenteLigne::create([
                    'qte_cmde' => $request->qte_cdes[$i],
                    'qte_livre' => $request->qte_cdes[$i],
                    'article_id' => $request->articles[$i],
                    'prix_unit' => $request->prixUnits[$i],
                    'vente_id' => $vente->id,
                    'unite_mesure_id' => $request->unites[$i],
                ]);
            }

            $nbr = FactureVente::max('id');            
            $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));

            $facture = FactureVente::create([
                'date_facture' => $request->date_fact,
                'vente_id' => $vente->id,
                'statut' => 'Soldée',
                'montant_facture' => $request->montant_facture,
                'montant_total' => $request->montant_total,
                'montant_regle' => $request->montant_regle,
                'taux_remise' => (float)$request->taux_remise,
                'num_facture' => 'KAD-'. 'FVC' . ($nbr + 1).'-'.date('dmY') . '-' . $lettres,

                // 'num_facture' => 'FV' . date('dmY') . ($nbr + 1),
                'user_id' => Auth::user()->id,
                'facture_type_id' => $request->type_id,
                'client_facture' => $request->client_id,
                'tva' => (float)$request->tva,
                'aib' => (float)$request->aib,
            ]);


            $compte_client = CompteClient::create([
                'date_op' => $facture->date_facture,
                'montant_op' => $facture->montant_total,
                'facture_id' => $facture->id,
                'client_id' => $request->client_id,
                'user_id'=> Auth::user()->id,
                'type_op' => 'FAC_VC',
                'cle' => $facture->id,
            ]);

            $compte_client_reg = CompteClient::create([
                'date_op' => $facture->date_facture,
                'montant_op' => $facture->montant_total,
                'facture_id' => $facture->id,
                'client_id' => $request->client_id,
                'user_id'=> Auth::user()->id,
                'type_op' => 'REG_VC',
                'cle' => $facture->id,
            ]);


            DB::commit();
            return redirect()->route('ventes.index')->with('success', 'Vente enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return redirect()->route('ventes.index')->withErrors(['message' => 'Erreur enregistrement vente']);
        }
    }

    public function deleteVente($id) {
        $point_vente = Auth::user()->boutique;
        $ventes_lignes = VenteLigne::where('vente_id', $id)->get();

        foreach ($ventes_lignes as $vente_ligne) {
            $element = ArticlePointVente::where('article_id', $vente_ligne->article_id)->where('point_vente_id', $point_vente->id)->first();
            $unite_base = Article::find($vente_ligne->article_id)->unite_mesure_id;
            $article = Article::find($vente_ligne->article_id);

            $conversionItem = $article->getPivotValueForUnite($vente_ligne->unite_mesure_id);
            if (!is_null($conversionItem)) {
                $tauxConversion = $conversionItem;
                if ($tauxConversion < 1){
                    $qte_vrai = $vente_ligne->qte_cmde / $tauxConversion;
                }else{
                    $qte_vrai = $vente_ligne->qte_cmde * $tauxConversion;
                }
            } else {
                return redirect()->route('ventes.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
            }
            if ($element) {
                $qte_stock = $element->qte_stock + (float)$qte_vrai;
                $element->update(['qte_stock' => $qte_stock]);
            }
        }

        dataVente::where('vente_id', $id)->delete();
        BonVente::where('vente_id', $id)->delete();
        VenteLigne::where('vente_id', $id)->delete();
        Vente::where('id', $id)->delete();

        return redirect()->route('ventes.index')->with('success', 'Vente supprimée avec succès');
    }


    public function updateVente(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'prixUnits.*' => 'required',
            'unites.*' => 'required',
            'montant_facture' => 'required',
            'montant_total' => 'required',
            'montant_regle' => 'required',
            'taux_remise' => 'required',
            'type_id' => 'required',
            'date_fact' => 'required',
            // 'magasin_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // dd($request->all());
        $point_vente = Auth::user()->boutique;
        $count = count($request->qte_cdes);

        DB::beginTransaction();

        try {
            // Retrieve the Vente instance to update
            $vente = Vente::findOrFail($id);
            $vente->update([
                'user_id' => Auth::user()->id,
                'type_vente_id' => $request->type_vente_id,
                'montant' => $request->montant_regle,
                'client_id' => $request->client_id,
            ]);


            $ventes_lignes = VenteLigne::where('vente_id', $id)->get();

            foreach ($ventes_lignes as $vente_ligne) {
                $element = ArticlePointVente::where('article_id', $vente_ligne->article_id)->where('point_vente_id', $point_vente->id)->first();
                $unite_base = Article::find($vente_ligne->article_id)->unite_mesure_id;
                $article = Article::find($vente_ligne->article_id);

                $conversionItem = $article->getPivotValueForUnite($vente_ligne->unite_mesure_id);
                if (!is_null($conversionItem)) {
                    $tauxConversion = $conversionItem;
                    if ($tauxConversion < 1){
                        $qte_vrai = $vente_ligne->qte_cmde / $tauxConversion;
                    }else{
                        $qte_vrai = $vente_ligne->qte_cmde * $tauxConversion;
                    }
                } else {
                    return redirect()->route('ventes.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }
                if ($element) {
                    $qte_stock = $element->qte_stock + (float)$qte_vrai;
                    $element->update(['qte_stock' => $qte_stock]);
                }
            }

            dataVente::where('vente_id', $id)->delete();
            VenteLigne::where('vente_id', $id)->delete();

            for ($i = 0; $i < $count; $i++) {
                $element = ArticlePointVente::where(
                    'article_id',
                    $request->articles[$i]
                )->where('point_vente_id', $point_vente->id)->first();
                $unite_base = Article::find($request->articles[$i])->unite_mesure_id;
                $article = Article::find($request->articles[$i]);

                $conversionItem = $article->getPivotValueForUnite($request->unites[$i]);
                if (!is_null($conversionItem)) {
                    $tauxConversion = $conversionItem;
                    if ($tauxConversion < 1){
                        $qte_vrai = $request->qte_cdes[$i] / $tauxConversion;
                    }else{
                        $qte_vrai = $request->qte_cdes[$i] * $tauxConversion;
                    }
                } else {
                    return redirect()->route('ventes.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }
                if ($element) {
                    $qte_stock = $element->qte_stock - (float)$qte_vrai;
                    $element->update(['qte_stock' => $qte_stock]);
                }

                $appro = VenteLigne::create([
                    'qte_cmde' => $request->qte_cdes[$i],
                    'qte_livre' => $request->qte_cdes[$i],
                    'article_id' => $request->articles[$i],
                    'prix_unit' => $request->prixUnits[$i],
                    'vente_id' => $vente->id,
                    'unite_mesure_id' => $request->unites[$i],
                ]);
            }

            dataVente::create([
                'montant_facture' => $request->montant_facture,
                'taux_remise' => (float)$request->taux_remise,
                'montant_total' => $request->montant_total,
                'montant_regle' => $request->montant_regle,
                'facture_type_id' => $request->type_id,
                'vente_id' => $vente->id,
                'tva' => (float)$request->tva,
                'aib' => (float)$request->aib,
            ]);

            DB::commit();

            return redirect()->route('ventes.index')->with('success', 'Vente mise à jour avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('ventes.index')->withErrors(['message' => 'Erreur lors de la mise à jour de la vente']);
        }
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Vente::with('factureVente')->with('acheteur')->find($id);
        $lignes =  DB::table('vente_lignes')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'vente_lignes.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'vente_lignes.article_id')
            ->where('vente_lignes.vente_id', $id)
            ->select('vente_lignes.*', 'unite_mesures.unite', 'articles.nom')
            ->get();
        $i = 1;

        // dd($item);

        return view('pages.ventes-module.ventes.show', compact('lignes', 'item', 'i'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vente = Vente::with('factureVente')->with('acheteur')->find($id);
        $client = $vente->acheteur->nom_client;
        $lignes =  DB::table('vente_lignes')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'vente_lignes.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'vente_lignes.article_id')
            ->where('vente_lignes.vente_id', $id)
            ->select('vente_lignes.*', 'unite_mesures.unite', 'articles.nom')
            ->get();

        $types = FactureType::all();
        $typeVentes = TypeVente::all();
        $articles = Article::all();
        $data_vente = dataVente::where('vente_id', $id)->first();

        // dd($data_vente);
        
            
        return view('pages.ventes-module.ventes.edit', compact('vente', 'lignes', 'types', 'typeVentes', 'articles', 'client', 'data_vente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'prixUnits.*' => 'required',
            'unites.*' => 'required',
            'montant_facture' => 'required',
            'montant_total' => 'required',
            'montant_regle' => 'required',
            'taux_remise' => 'required',
            'type_id' => 'required',
            'date_fact' => 'required',
            // 'magasin_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // dd($request->all());
        $point_vente = Auth::user()->boutique;
        $count = count($request->qte_cdes);

        DB::beginTransaction();

        try {
            // Retrieve the Vente instance to update
            $vente = Vente::findOrFail($id);
            $vente->update([
                'user_id' => Auth::user()->id,
                'type_vente_id' => $request->type_vente_id,
                'montant' => $request->montant_regle,
                // 'client_id' => $request->client_id,
            ]);


            $ventes_lignes = VenteLigne::where('vente_id', $id)->get();

            foreach ($ventes_lignes as $vente_ligne) {
                $element = ArticlePointVente::where('article_id', $vente_ligne->article_id)->where('point_vente_id', $point_vente->id)->first();
                $unite_base = Article::find($vente_ligne->article_id)->unite_mesure_id;
                $article = Article::find($vente_ligne->article_id);

                $conversionItem = $article->getPivotValueForUnite($vente_ligne->unite_mesure_id);
                if (!is_null($conversionItem)) {
                    $tauxConversion = $conversionItem;
                    if ($tauxConversion < 1){
                        $qte_vrai = $vente_ligne->qte_cmde / $tauxConversion;
                    }else{
                        $qte_vrai = $vente_ligne->qte_cmde * $tauxConversion;
                    }
                } else {
                    return redirect()->route('ventes.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }
                if ($element) {
                    $qte_stock = $element->qte_stock + (float)$qte_vrai;
                    $element->update(['qte_stock' => $qte_stock]);
                }
            }

            dataVente::where('vente_id', $id)->delete();
            VenteLigne::where('vente_id', $id)->delete();

            for ($i = 0; $i < $count; $i++) {
                $element = ArticlePointVente::where(
                    'article_id',
                    $request->articles[$i]
                )->where('point_vente_id', $point_vente->id)->first();
                $unite_base = Article::find($request->articles[$i])->unite_mesure_id;
                $article = Article::find($request->articles[$i]);

                $conversionItem = $article->getPivotValueForUnite($request->unites[$i]);
                if (!is_null($conversionItem)) {
                    $tauxConversion = $conversionItem;
                    if ($tauxConversion < 1){
                        $qte_vrai = $request->qte_cdes[$i] / $tauxConversion;
                    }else{
                        $qte_vrai = $request->qte_cdes[$i] * $tauxConversion;
                    }
                } else {
                    return redirect()->route('ventes.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }
                if ($element) {
                    $qte_stock = $element->qte_stock - (float)$qte_vrai;
                    $element->update(['qte_stock' => $qte_stock]);
                }

                $appro = VenteLigne::create([
                    'qte_cmde' => $request->qte_cdes[$i],
                    'qte_livre' => $request->qte_cdes[$i],
                    'article_id' => $request->articles[$i],
                    'prix_unit' => $request->prixUnits[$i],
                    'vente_id' => $vente->id,
                    'unite_mesure_id' => $request->unites[$i],
                ]);
            }

            dataVente::create([
                'montant_facture' => $request->montant_facture,
                'taux_remise' => (float)$request->taux_remise,
                'montant_total' => $request->montant_total,
                'montant_regle' => $request->montant_regle,
                'facture_type_id' => $request->type_id,
                'vente_id' => $vente->id,
                'tva' => (float)$request->tva,
                'aib' => (float)$request->aib,
            ]);

            DB::commit();

            return redirect()->route('ventes.index')->with('success', 'Vente mise à jour avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('ventes.index')->withErrors(['message' => 'Erreur lors de la mise à jour de la vente']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function encaisser($vente_id){
        $vente = Vente::find($vente_id);

        $encaissement = new Encaissement();
        $encaissement->user_id = Auth::user()->id;
        $vente->encaissements()->save($encaissement);

        $vente->encaisse = 'oui';
        $vente->save();

        return redirect()->route('vente-caisse')->with('success', 'Vente encaissée avec succès');   
    }

    public function rapport_caisse(Request $request){
         
        $encaissements = Encaissement::with('encaisseable');
        
        if ($request->has('start_date') && $request->start_date != '' && $request->has('end_date') && $request->end_date != '') {   
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $encaissements = $encaissements->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        // dd($encaissements[0]->encaisseable->client_id);
        if ($request->has('client') && $request->client != null) {
            $encaissements = $encaissements->whereHas('encaisseable', function ($query) use ($request) {
                $query->where('client_id', $request->client);
            });
        }

        if(!$request->has('start_date') && !$request->has('end_date') && !$request->has('client')){
            $encaissements = array();
        }else{
            $encaissements = $encaissements->get();
        }        
        
        // dd($request->client);

        $i = 1;

        $getClient = function ($client_id) {
            return Client::find($client_id);
        };

        $montant_total = $remboursement = $achat = $avance = 0;
        foreach ($encaissements as $encaissement) {
            $reglement = 0;
            $encaisseable = $encaissement->encaisseable;
            if (($encaisseable instanceof \App\Models\ReglementClient)) {
                $reglement = $encaisseable->montant_total_regle > 0  ? $encaisseable->montant_total_regle : $encaisseable->montant_regle;
                $remboursement += $reglement;
            }elseif ($encaisseable instanceof \App\Models\Vente){
                $achat += $encaisseable->montant ?? $encaisseable->montant_regle;
            }elseif ($encaisseable instanceof \App\Models\AcompteClient){
                $avance += $encaisseable->montant ?? $encaisseable->montant_acompte;
            }

            $montant_total += ($encaisseable?->montant ?? $encaisseable?->montant_acompte) + ($reglement ?? 0);
        }

        $clients = Client::all();
        return view('pages.ventes-module.ventes.rapport_caisse', compact('encaissements', 'i', 'getClient', 'clients', 'montant_total', 'achat', 'remboursement', 'avance'));
    }
}
