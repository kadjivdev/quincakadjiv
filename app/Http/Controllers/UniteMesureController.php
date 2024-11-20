<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\UniteMesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UniteMesureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unites = UniteMesure::all();
        $i = 1;
        return view('pages.taux.index', compact( 'unites', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.taux.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unite' => 'required|unique:unite_mesures,unite',
            'abbrev' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        UniteMesure::create([
            'unite' => $request->unite,
            'abbrev' => $request->abbrev,
        ]);

        return redirect()->route('unites.index')
            ->with('success', 'Unité ajoutée avec succès.');
    }


    public function getUnitesByArticle(Request $request , $id){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
           return redirect()->back()->withErrors($validator)->withInput();
        }
        $article =  Article::find($request->id); 
        $uniteOfArticle = $article->uniteBase();
          

        return response()->json($uniteOfArticle);

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
