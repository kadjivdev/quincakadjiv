<?php

namespace App\Http\Controllers;

use App\Imports\ChauffeurImport;
use App\Models\Chauffeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ChaufeurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chauffeurs = Chauffeur::all();
        $i = 1;
        return view('pages.chauffeurs.index',  compact('chauffeurs','i'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        return view('pages.chauffeurs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|unique:chauffeurs,tel_chauf',
            'permis' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Chauffeur::create([
            'nom_chauf' => $request->name,
            'tel_chauf' => $request->phone,
            'permis' => $request->permis,
        ]);

        // $fournisseur->articles()->attach($request->input('articles'));

        return redirect()->route('chauffeurs.index')
            ->with('success', 'Chauffeur ajouté avec succès.');
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
        $chauffeur = Chauffeur::find($id);
        // $fournisseur->with(['articles'])->get();
        return view('pages.chauffeurs.edit', compact('chauffeur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required',
            'permis' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $chauffeur = Chauffeur::find($id);
        $chauffeur->update([
            'nom_chauf' => $request->name,
            'tel_chauf' => $request->phone,
            'permis' => $request->permis,
        ]);

        return redirect()->route('chauffeurs.index')
            ->with('success', 'Chauffeur modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function import_xls(Request $request)
    {
        $this->validate($request, [
            'upload_xls'  => 'required'
        ]);

        Excel::import(new ChauffeurImport, $request->file('upload_xls'));

        return redirect()->route('chauffeurs.index')->with('status', 'Chauffeurs importés avec succès.');
    }

    public function chaufListAjax(Request $request)
    {
        $chauffeurs = CHauffeur::where('nom_chauf', 'LIKE', '%' . $request->input('term', '') . '%')
        ->get();

        return response()->json([
            'chauffeurs'   => $chauffeurs,
        ]);
    }
}
