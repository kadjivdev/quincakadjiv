<?php

namespace App\Http\Controllers;

use App\Imports\ArticleImport;
use App\Imports\PointVenteStockImport;
use App\Models\PointVente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PoinVenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $points = PointVente::all();
        // dd($points);
        $i = 1;
        return view('pages.point-ventes.index', compact('points', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $users = User::all();
        return view('pages.point-ventes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|unique:point_ventes,nom',
            'adresse' => 'required',
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $point = PointVente::create($request->all());

        return redirect()->route('boutiques.index')
            ->with('success', 'Point de vente ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $detail = PointVente::with(['articles'])->whereId($id)->get();
        $i = 1;
        $point = PointVente::find($id);
        $detail = DB::table('articles')
            ->join('article_point_ventes', function ($join) use ($id) {
                $join->on('articles.id', '=', 'article_point_ventes.article_id')
                    ->where('article_point_ventes.point_vente_id', $id); // Filter on point_vente_id here
            })
            ->join('categories', 'articles.categorie_id', '=', 'categories.id')
            ->select('articles.*', 'article_point_ventes.*', 'article_point_ventes.point_vente_id as pointId', 'articles.id as articleId', 'categories.libelle as categorie')
            ->get();

        return view('pages.point-ventes.show', compact('detail', 'i', 'point'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $point = PointVente::find($id);

        
        $hasRelations = false;

        
        if ($point->employees()->exists()) {
            $hasRelations = true;
        }

        
        if ($point->articles()->exists()) {
            $hasRelations = true;
        }

        if ($point->magasins()->exists()) {
            $hasRelations = true;
        }

        // Si le magasin est utilisé quelque part, ne pas le supprimer
        if ($hasRelations) {
            return redirect()->back()->withErrors('Impossible de supprimer ce point de vente car il est utilisé ailleurs.');
        }

        $point->delete();

        return redirect()->route('boutiques.index')->with('success', 'Point de vente supprimé avec succès.');
    }

    public function listPoints()
    {
        $points = PointVente::get();

        return response()->json([
            'points'  => $points
        ]);
    }

    public function storePrix(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'point_id' => 'required',
            'article_id' => 'required',
            'prix_special' => 'required',
            // 'prix_revendeur' => 'required',
            // 'prix_particulier' => 'required',
            // 'prix_btp' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('article_point_ventes')
            ->updateOrInsert(
                ['point_vente_id' => $request->point_id, 'article_id' => $request->article_id],
                [
                    'prix_revendeur' => $request->prix_revendeur,
                    'prix_special' => $request->prix_special,
                    'prix_particulier' => $request->prix_particulier,
                    'prix_btp' => $request->prix_btp,

                ]
            );

        return redirect()->route('boutiques.index')
            ->with('success', 'Prix enregistrés avec succès.');
    }

    public function attribuer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'point_vente_id' => 'required',
            'magasin_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $point = PointVente::find($request->point_vente_id);
        $point->magasin_id = $request->magasin_id;
        $point->save();

        return redirect()->route('boutiques.index')
            ->with('success', 'Magasin attribué avec succès.');
    }

    public function import_stock(Request $request)
    {
        $this->validate($request, [
            'upload_xls'  => 'required|mimes:xls,xlsx'
        ]);

        //  dd($request->upload_xls);
        Excel::import(new PointVenteStockImport, $request->file('upload_xls'));

        return redirect()->route('boutiques.index')->with('status', 'Articles prix importés avec succès.');
    }

    public function majStockPoints()
    {
    }

    public function edit($id) {
        $point = PointVente::find($id);

        return view('pages.point-ventes.edit', compact('point'));
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string',
            'adresse' => 'required',
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $point = PointVente::find($id);
        $point->nom = $request->nom;
        $point->adresse = $request->adresse;
        $point->phone = $request->phone;
        $point->save();

        return redirect()->route('boutiques.index')
            ->with('success', 'Point de vente modifié avec succès.');
    }
}
