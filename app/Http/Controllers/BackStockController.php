<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\Article;
use App\Models\ArticlePointVente;
use App\Models\BackStock;
use App\Models\BackStockArticle;
use App\Models\Client;
use App\Models\Magasin;
use App\Models\StockMagasin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BackStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $backs = BackStock::with(['provenance', 'destination', 'client'])->orderBy('id', 'desc')->get();
        $i=1;

        return view('pages.ventes-module.back_stock.index', compact(['i', 'backs']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $magasins = Magasin::all();
        $articles = Article::all();

        return view('pages.ventes-module.back_stock.create', compact(['clients', 'magasins', 'articles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'unites.*' => 'required',
            'prixUnits.*' => 'required',
            'date_back' => 'required',
            'magasin_id' => 'required',
            'magasin_from_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // dd($request->all());
        // $point_vente = Auth::user()->boutique;
        $count = count($request->qte_cdes);

        DB::beginTransaction();

        try {

            $nbrv = BackStock::max('id');
            $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));

            $back = BackStock::create([
                'user_id' => Auth::user()->id,
                'from_magasin_id' => $request->magasin_from_id,
                'to_magasin_id' => $request->magasin_id,
                'montant_total' => $request->montant_total,
                'date_op' => $request->date_back,
                'client_id' => $request->client_id,
                'reference' => 'KAD-'. 'BACK' . ($nbrv + 1).'-'.date('dmY') . '-' . $lettres,
            ]);

            $magasin_from = Magasin::where('id', $request->magasin_from_id)->first();

            for ($i = 0; $i < $count; $i++) {
                $element = ArticlePointVente::where('article_id', $request->articles[$i])->where('point_vente_id', $magasin_from->point_vente_id)->first();
                $magasinStock = StockMagasin::where('article_id', $request->articles[$i])->where('magasin_id', $magasin_from->id)->first();
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
                    return redirect()->route('back_stock.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }
                // if ($element) {
                //     $qte_stock = $element->qte_stock - (float)$qte_vrai;
                //     $element->update(['qte_stock' => $qte_stock]);
                // }

                if ($element && $magasinStock->qte_stock >= $qte_vrai && $element->qte_stock >= $qte_vrai) {
                    BackStockArticle::create([
                        'qte_back' => $request->qte_cdes[$i],
                        'qte_vrai' => $qte_vrai,
                        'prix_unit' => $request->prixUnits[$i],
                        'article_id' => $request->articles[$i],
                        'unite_mesure_id' => $request->unites[$i],
                        'back_stock_id' => $back->id,
                    ]);             
                }else{
                    return redirect()->back()->withErrors(['message' => 'Quantité insufisante pour l\'article '.$article->nom]);
                }
            }


            DB::commit();
            return redirect()->route('back_stock.index')->with('success', 'Retour de stock enregistré avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return redirect()->route('back_stock.index')->withErrors(['message' => 'Erreur enregistrement '.$e->getMessage()]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $back = BackStock::where('id', $id)->first();
        $lignes = BackStockArticle::where('back_stock_id', $id)->with('article')->with('unite_mesure')->get();

        $clients = Client::all();
        $magasins = Magasin::all();
        $articles = Article::all();

        return view('pages.ventes-module.back_stock.edit', compact(['back', 'clients', 'magasins', 'articles', 'lignes']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'unites.*' => 'required',
            'prixUnits.*' => 'required',
            'date_back' => 'required',
            'magasin_id' => 'required',
            'magasin_from_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // dd($request->all());
        // $point_vente = Auth::user()->boutique;
        $count = count($request->qte_cdes);

        DB::beginTransaction();

        try {
            $back = BackStock::findOrFail($id);

            $back->update([
                'user_id' => Auth::user()->id,
                'from_magasin_id' => $request->magasin_from_id,
                'to_magasin_id' => $request->magasin_id,
                'montant_total' => $request->montant_total,
                'date_op' => $request->date_back,
                'client_id' => $request->client_id
            ]);

            $lignes = BackStockArticle::where('back_stock_id', $id)->get();

            $magasin_from = Magasin::where('id', $request->magasin_from_id)->first();            

            for ($i = 0; $i < $count; $i++) {
                $element = ArticlePointVente::where('article_id', $request->articles[$i])->where('point_vente_id', $magasin_from->point_vente_id)->first();
                $magasinStock = StockMagasin::where('article_id', $request->articles[$i])->where('magasin_id', $magasin_from->id)->first();
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
                    return redirect()->route('back_stock.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }
            }

            
            BackStockArticle::where('back_stock_id', $id)->delete();

            for ($i = 0; $i < $count; $i++) {
                $element = ArticlePointVente::where('article_id', $request->articles[$i])->where('point_vente_id', $magasin_from->point_vente_id)->first();
                $magasinStock = StockMagasin::where('article_id', $request->articles[$i])->where('magasin_id', $magasin_from->id)->first();
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
                    return redirect()->route('back_stock.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }
                // if ($element) {
                //     $qte_stock = $element->qte_stock - (float)$qte_vrai;
                //     $element->update(['qte_stock' => $qte_stock]);
                // }

                if ($element && $magasinStock->qte_stock >= $qte_vrai && $element->qte_stock >= $qte_vrai) {
                    BackStockArticle::create([
                        'qte_back' => $request->qte_cdes[$i],
                        'qte_vrai' => $qte_vrai,
                        'prix_unit' => $request->prixUnits[$i],
                        'article_id' => $request->articles[$i],
                        'unite_mesure_id' => $request->unites[$i],
                        'back_stock_id' => $back->id,
                    ]);             
                }else{
                    return redirect()>back()->withErrors(['message' => 'Quantité insufisante pour l\'article '.$article->nom]);
                }
            }


            DB::commit();
            return redirect()->route('back_stock.index')->with('success', 'Retour de stock modifié avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return redirect()->route('back_stock.index')->withErrors(['message' => 'Erreur enregistrement '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        BackStockArticle::where('back_stock_id', $id)->delete();
        BackStock::where('id', $id)->delete();

        return redirect()->route('back_stock.index')->with('success', 'Retour de stock supprimé avec succès');
    }

    public function validation(string $id)
    {
        $back = BackStock::where('id', $id)->first();
        $lignes = BackStockArticle::where('back_stock_id', $id)->with('article')->with('unite_mesure')->get();

        $clients = Client::all();
        $magasins = Magasin::all();
        $articles = Article::all();

        return view('pages.ventes-module.back_stock.validation', compact(['back', 'clients', 'magasins', 'articles', 'lignes']));
    }

    public function back_validate($id){
        $back = BackStock::findOrFail($id);
        $lignes = BackStockArticle::where('back_stock_id', $id)->get();
        
        DB::beginTransaction();

        try{
            foreach ($lignes as $ligne) {
                $magasin_from = Magasin::where('id', $back->from_magasin_id)->first();
                $element = ArticlePointVente::where('article_id', $ligne->article_id)->where('point_vente_id', $magasin_from->point_vente_id)->first();
                $magasinStock = StockMagasin::where('article_id', $ligne->article_id)->where('magasin_id', $magasin_from->id)->first();

                $magasin_to = Magasin::where('id', $back->to_magasin_id)->first();
                $elementTo = ArticlePointVente::where('article_id', $ligne->article_id)->where('point_vente_id', $magasin_to->point_vente_id)->first();
                $magasinStockTo = StockMagasin::where('article_id', $ligne->article_id)->where('magasin_id', $magasin_to->id)->first();

                if ($element && $magasinStock->qte_stock >= $ligne->qte_vrai && $element->qte_stock >= $ligne->qte_vrai) {
                    $qte_stock = $element->qte_stock - (float)$ligne->qte_vrai;
                    $element->update(['qte_stock' => $qte_stock]);
                    $magasinStock->update(['qte_stock' => $qte_stock]);
                }  
                
                if ($elementTo && $magasinStockTo->qte_stock >= $ligne->qte_vrai && $elementTo->qte_stock >= $ligne->qte_vrai) {
                    $qte_stock = $elementTo->qte_stock + (float)$ligne->qte_vrai;
                    $elementTo->update(['qte_stock' => $qte_stock]);
                    $magasinStockTo->update(['qte_stock' => $qte_stock]);
                }  
            }


            $back->validate_at = now();
            $back->validator = Auth::id();
            $back->save();

            DB::commit();
            return redirect()->route('back_stock.index')->with('success', 'Retour de stock validé avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return redirect()->route('back_stock.index')->withErrors(['message' => 'Erreur validation Retour '.$e->getMessage()]);
        }
    }
}
