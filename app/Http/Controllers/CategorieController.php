<?php

namespace App\Http\Controllers;

use App\Imports\CategorieImport;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::all();
        return view('pages.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'libelle' => 'required|string',
    //     ]);
    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $user = Categorie::create([
    //         'libelle' => $request->libelle,
    //     ]);

    //     return redirect()->route('categories.index')
    //         ->with('success', 'Catégorie ajouté avec succès.');
    // }

    public function store(Request $request)
    {
        // Définir les règles de validation
        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string',
        ]);

        // Vérifier si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Vérifier si un enregistrement avec le même libelle existe déjà
        $exists = Categorie::where('libelle', $request->libelle)->exists();

        if ($exists) {
            // Rediriger avec un message d'erreur si le libelle existe déjà
            return redirect()->back()->withErrors(['libelle' => 'Cette catégorie existe déjà.'])->withInput();
        }

        // Créer le nouvel enregistrement
        $user = Categorie::create([
            'libelle' => $request->libelle,
        ]);

        // Rediriger avec un message de succès
        return redirect()->route('categories.index')
            ->with('success', 'Catégorie ajoutée avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $la_categorie = Categorie::find($id);
        $categories = Categorie::All();

        return view('pages.categories.show', compact('categories', 'la_categorie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function retrieve(Request $request,$id) {
        $categorie = Categorie::findOrFail($id);

        return response()->json($categorie);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $categorie = Categorie::find($id);
        $categorie->update([
            'libelle' => $request->libelle
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Categorie::find($id);
        $item->delete();
        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }


    public function import_xls(Request $request)
    {
        $this->validate($request, [
            'upload'  => 'required'
        ]);

        Excel::import(new CategorieImport, $request->file('upload'));

        return redirect()->route('categories.index')->with('status', 'Categories importées avec succès.');
    }
}
