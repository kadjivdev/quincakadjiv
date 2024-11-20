<?php

namespace App\Http\Controllers;

use App\Models\ArticlePointVente;
use App\Models\DetailInventaire;
use App\Models\Inventaire;
use App\Models\Magasin;
use App\Models\PointVente;
use App\Models\StockMagasin;
use App\Models\UniteMesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InventaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventaires = Inventaire::with(['auteur', 'magasin'])
        ->whereNull('validated_at')
        ->orderBy('created_at', 'desc') // Tri par ordre décroissant sur created_at
        ->get();

        $i = 1;
        return view('pages.inventaires.index', compact('inventaires', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $magasin = Magasin::find($request->id);
        $articles =  Magasin::find($request->id)
            ->stock_articles()
            ->select(
                'articles.*',
                'qte_stock',
            )->get();

        $unites = UniteMesure::all();

        return view('pages.inventaires.create', compact('articles', 'unites', 'magasin'));
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
            'articles.*' => 'required',
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
                $stock = StockMagasin::where('magasin_id', $request->magasin_id)
                    ->where('article_id', $request->articles[$i])
                    ->first();

                $ligne = DetailInventaire::create([
                    'qte_stock' => $request->qte_stock[$i],
                    'qte_reel' => $request->qte_reel[$i],
                    // 'article_id' => $request->articles[$i],
                    'stock_magasin_id' => $stock->id,
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



    public function createMultiple(Request $request)
    {
        $i = 1;
        $magasin = Magasin::find($request->id);
        $articles =  Magasin::find($request->id)
            ->stock_articles()
            ->select(
                'articles.*',
                'qte_stock',
            )->get();

        $unites = UniteMesure::all();

        return view('pages.inventaires.create-multiple', compact('articles', 'unites', 'magasin', 'i'));
    }


    public function storeMultipleBack(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_inventaire' => 'required',
            //'qte_stock.*' => 'required',
            //'qte_reel.*' => 'required',
            //'articles.*' => 'required',
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

            $count = count($request->articles);
            for ($i = 0; $i < $count - 1; $i++) {
                $stock = StockMagasin::where('magasin_id', $request->magasin_id)
                    ->where('article_id', $request->articles[$i])
                    ->first();

                $ligne = DetailInventaire::create([
                    'qte_stock' => $request->qte_stock[$i],
                    'qte_reel' => $request->qte_reel[$i],
                    // 'article_id' => $request->articles[$i],
                    'stock_magasin_id' => $stock->id,
                    'inventaire_id' => $inventaire->id,
                ]);
            }
            DB::commit();
            return redirect()->route('inventaires.index')->with('success', 'Inventaire enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->route('inventaires.index')->with('error', 'Erreur enregistrement de inventaire.');
        }
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invent = Inventaire::find($id);
        $lignes =  DB::table('detail_inventaires')
            ->join('stock_magasins', 'stock_magasins.id', '=', 'detail_inventaires.stock_magasin_id')
            ->join('articles', 'articles.id', '=', 'stock_magasins.article_id')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'articles.unite_mesure_id')
            ->where('detail_inventaires.inventaire_id', $id)
            ->select('detail_inventaires.*', 'unite_mesures.unite', 'articles.nom')
            ->get();

        $i = 1;

        return view('pages.inventaires.show', compact('invent', 'lignes', 'i'));
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
     * Display a listing of the resource.
     */
    public function indexByMag($id)
    {
        $magasin = Magasin::find($id);
        // $inventaires = Inventaire::with(['auteur'])->where('magasin_id', $id)->get();
        $inventaires = Inventaire::with(['auteur'])
    ->where('magasin_id', $id)
    ->whereNull('validated_at') // Ajout de la condition whereNull sur created_at
    ->orderBy('created_at', 'desc') // Tri par ordre décroissant sur created_at
    ->get();

        $i = 1;
        return view('pages.inventaires.index-mag', compact('inventaires', 'i', 'magasin'));
    }

    public function valider($id)
    {
        $invent = Inventaire::find($id);
        $point = Magasin::find($invent->magasin_id)->pointVente;
        $magasins = Magasin::where('point_vente_id', $point->id)->get();

        DB::beginTransaction();

        try {
            $invent->validator_id = Auth::user()->id;
            $invent->validated_at = now();
            $invent->save();

            $details = $invent->details()->get();
            foreach ($details as $detail) {
                $stock_magasin = StockMagasin::where('id', $detail['stock_magasin_id'])->first();
                $stock_magasin->update(['qte_stock' => $detail['qte_reel']]);

                /*  foreach ($magasins as $key => $value) {
                    $stock_point = ArticlePointVente::where('point_vente_id', $value->point_vente_id)->first();
                    //->where('article_id', $stock_magasin->article_id)->first();

                    //dd($value->point_vente_id);
                    $stock_point->qte_stock += $detail['qte_reel'];
                    $stock_point->save();
                } */
                $stock_point = ArticlePointVente::where('point_vente_id', $point->id)
                    ->where('article_id', $stock_magasin->article_id)->first();

                //dd($value->point_vente_id);
                $stock_point->qte_stock += $detail['qte_reel'];
                $stock_point->save();
            }
            DB::commit();

            return response()->json(['redirectUrl' => route('magasins.index')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'redirectUrl' => route('magasins.index'),
                'message' => "VAlidation échouée",
            ]);
        }
    }


    public function stock_a_zero($id)
    {
        $point = PointVente::find($id);
        // $magasins = Magasin::where('point_vente_id', $point->id)->get();

        DB::beginTransaction();

        try {

            $details = $point->articles()->get();

            foreach ($details as $detail) {
                $stock_point = ArticlePointVente::where('point_vente_id', $detail->point_vente_id)
                    ->where('article_id', $detail->article_id)->first();
                $stock_point->qte_stock = 0;
                $stock_point->save();
            }

            DB::commit();

            return response()->json(['redirectUrl' => route('boutiques.index')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'redirectUrl' => route('magasins.index'),
                'message' => "VAlidation échouée",
            ]);
        }
    }
}
