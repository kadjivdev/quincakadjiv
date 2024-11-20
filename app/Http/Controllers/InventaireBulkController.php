<?php

namespace App\Http\Controllers;

use App\Models\ArticlePointVente;
use App\Models\Categorie;
use App\Models\DetailInventaire;
use App\Models\Inventaire;
use App\Models\Magasin;
use App\Models\PointVente;
use App\Models\StockMagasin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InventaireBulkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $magasin = Magasin::find($request->id);
        // $articles =  Magasin::find($request->id)
        //     ->stock_articles()
        //     ->select(
        //         'articles.*',
        //         'qte_stock',
        //     )->get();


        $articles = DB::table('stock_magasins')
            ->join('articles', 'articles.id', '=', 'stock_magasins.article_id')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'articles.unite_mesure_id')
            ->join('categories', 'categories.id', '=', 'articles.categorie_id')
            ->where('stock_magasins.magasin_id', $magasin->id)
            ->select('stock_magasins.*', 'articles.nom', 'unite_mesures.unite', 'categories.libelle')
            ->distinct()
            ->get();

        $i = 1;
        $allCategories = Categorie::all();
        if (!is_null($request->categorie)) {
            $articles->where('categories.libelle', $request->categorie);
        }

        return view('pages.inventaires.bulk.create', compact('articles', 'magasin', 'i', 'allCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_inventaire' => 'required',
            'qte_stock.*' => 'required',
            'qte_reel.*' => 'required',
            'stock_magasin.*' => 'required',
            // 'unites.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $inventaire = Inventaire::create([
                'date_inventaire' => $request->date_inventaire,
                'user_id' => Auth::user()->id,
                'magasin_id' => $request->magasin_id,
            ]);

            $count = count($request->qte_reel);
            for ($i = 0; $i < $count; $i++) {
                $stock_pvt_total = 0;

                $stock = StockMagasin::find($request->stock_magasin[$i]);
                $article_id = $stock->article_id;
                $magasin = Magasin::find($stock->magasin_id);
                $point_vente_id = $magasin->point_vente_id;

                // Calculer le stock total du point de vente pour cet article
                $stock_other_mag = StockMagasin::where('article_id', $article_id)
                                            ->where('magasin_id', '!=', $magasin->id) // Exclure le magasin actuel
                                            ->sum('qte_stock');
                $stock_pvt_total = $stock_other_mag + $request->qte_reel[$i];

                // Mettre à jour le stock total du point de vente pour cet article
                ArticlePointVente::where('point_vente_id', $point_vente_id)
                            ->where('article_id', $article_id)
                            ->update(['qte_stock' => $stock_pvt_total]);


                $ligne = DetailInventaire::create([
                    'qte_stock' => $request->qte_stock[$i],
                    'qte_reel' => $request->qte_reel[$i],
                    'stock_magasin_id' => $request->stock_magasin[$i],
                    'inventaire_id' => $inventaire->id,
                ]);

                
            }
            DB::commit();
            return redirect()->route('inventaires.index')->with('success', 'Inventaire enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('inventaires.index')->with('error', 'Erreur enregistrement de inventaire.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
