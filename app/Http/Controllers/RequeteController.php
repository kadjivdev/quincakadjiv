<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\Requete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequeteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requetes = Requete::with('client')->with('articles')->get();
        
        return view('pages.ventes-module.requetes.index', compact(['requetes']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $articles = Article::all();

        return view('pages.ventes-module.requetes.create', compact(['clients', 'articles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'num_demande' => 'required|integer',
            'date_demande' => 'required|date',
            'nature' => 'required|string',
            'mention' => 'required|string',
            'formulation' => 'required|string',
            'client_id' => 'required|string',
            'motif' => 'required|string',
            // 'articles' => 'required|array',
            'articles.*' => 'exists:articles,id',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png', // types de fichiers autorisés
        ]);

        if ($request->hasFile('fichier')) {
            $filePath = $request->file('fichier')->store('uploads', 'public'); // Stocke le fichier dans le dossier 'uploads'
            $validated['fichier'] = $filePath;
        }

        // Créer la requête
        $requete = Requete::create([
            'num_demande' => $request->num_demande,
            'montant' => $request->montant,
            'date_demande' => $request->date_demande,
            'nature' => $request->nature,
            'mention' => $request->mention,
            'formulation' => $request->formulation,
            'user_id' => Auth()->user()->id,
            'client_id' => $request->client_id,
            'motif' => $request->motif,
            'motif_content' => $request->autre_motif,
            'fichier' => $request->hasFile('fichier') ? $request->file('fichier')->store('uploads', 'public') : null,
        ]);


        if ($request->motif == 'Articles') {
            $requete->articles()->attach($request->articles);
        }

        return redirect()->route('requetes.index')->with('success', 'Requête enregistrée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Requete $requete)
    {
        return view('pages.ventes-module.requetes.show', compact('requete'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Requete $requete)
    {
        $clients = Client::all();
        $articles = Article::all();

        return view('pages.ventes-module.requetes.edit', compact(['clients', 'articles', 'requete']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requete = Requete::findOrFail($id);

        $validated = $request->validate([
            'num_demande' => 'required|integer',
            'date_demande' => 'required|date',
            'nature' => 'required|string',
            'mention' => 'required|string',
            'formulation' => 'required|string',
            'client_id' => 'required|string',
            // 'articles' => 'required|array',
            'articles.*' => 'exists:articles,id',
        ]);


        $requete->update([
            'num_demande' => $request->num_demande,
            'montant' => $request->montant,
            'date_demande' => $request->date_demande,
            'nature' => $request->nature,
            'mention' => $request->mention,
            'formulation' => $request->formulation,
            'client_id' => $request->client_id,
            'motif' => $request->motif,
            'motif_content' => $request->autre_motif,
        ]);

        $requete->articles()->sync($request->articles);

        return redirect()->route('requetes.index')->with('success', 'Requête modifiée avec succès');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $requete = Requete::findOrFail($id);
        $requete->articles()->detach();
        Requete::destroy($id);

        return redirect()->route('requetes.index')->with('success', 'Requête supprimée avec succès');
    }

    public function validateRequete($id)
    {
        $requete = Requete::findOrFail($id);

        $requete->update([
            'validator' => Auth::user()->id,
            'validate_at' => now()
        ]);

        CompteClient::create([
            'date_op' => $requete->date_demande,
            'montant_op' =>  $requete->montant,
            'facture_id' => null,
            'client_id' => $requete->client_id,
            'user_id' => Auth::user()->id,
            'type_op' => 'REQ',
            'cle' => $requete->id,
        ]);

        return redirect()->route('requetes.index')->with('success', 'Requête validée avec succès');
    }
}
