<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Magasin;
use App\Models\PointVente;
use App\Models\StockMagasin;
use App\Models\StockTransfert;
use App\Models\UniteMesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransfertStockController extends Controller
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
    public function create()
    {
        $pointVendueId = Auth::user()->point_vente_id;
        $articles = PointVente::find($pointVendueId)
            ->articles()
            ->wherePivot('qte_stock', '>', 0)
            ->select('articles.*', 'qte_stock', 'prix_special')
            ->get();
        $unites = UniteMesure::all();
        $magasins =  Magasin::all();
        return view('pages.transferts.create', compact(['articles','unites', 'magasins']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'magasin_depart_id' => 'required',
            'magasin_dest_id' => 'required',
            'qte_transfert.*' => 'required',
            'articles.*' => 'required',
            'prixUnits.*' => 'required',
            'unites.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $count = count($request->qte_cdes);

        DB::beginTransaction();

        try {

            for ($i = 0; $i < $count; $i++) {

                $mag_dep = Magasin::find($request->magasin_depart_id);
                $mag_dest = Magasin::find($request->magasin_dest_id);
                $element = StockMagasin::where('article_id', $request->articles[$i])->where('magasin_id', $mag_dep->id)->first();

                $unite = UniteMesure::where('unite', $request->unites[$i])->first();
                $article = Article::find($request->articles[$i]);
                $conversionItem = $article->getPivotValueForUnite($request->unites[$i]);
                if (!is_null($conversionItem)) {
                    $qte_vrai = $request->qte_cdes[$i] * $conversionItem;
                } else {
                    return redirect()->route('deliveries.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }

                $magasin = Magasin::find($request->magasin_id);
                $stockMag = StockMagasin::where('article_id', $request->articles[$i])->where('magasin_id', $magasin->id)->first();

                if ($stockMag) {
                    $qte_stock = $stockMag->qte_stock - (float)$qte_vrai;
                    $stockMag->qte_stock = $qte_stock;
                    $stockMag->save();
                }

                if ($element) {
                    $qte_stock = $element->qte_stock - (float)$qte_vrai;
                    $element->qte_stock = $qte_stock;
                    $element->save();
                }
                StockTransfert::create([
                    'qte_transfert' => $request->qte_transfert[$i],
                    'magasin_depart_id' => $request->magasin_depart_id,
                    'magasin_dest_id' => $request->magasin_dest_id,
                    'article_id' => $request->articles[$i],
                    'unite_mesure_id' => $request->unites[$i],
                    'user_id' => Auth::user()->id,
                ]);
            }

            DB::commit();
            return redirect()->route('deliveries.index')->with('success', 'Livraison enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('deliveries.index')->withErrors(['message' => 'Erreur enregistrement Bon de commande']);
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
