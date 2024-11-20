<?php

namespace App\Http\Controllers;

use App\Models\BonVente;
use App\Models\LivraisonVenteMagasin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonVenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $i = 1;
        $bons = BonVente::with(['vente'])->get();
        return view('pages.ventes-module.ventes.bons.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $i = 1;
        $bon =  BonVente::find($id);

        $livraisons = DB::table('livraison_vente_magasins')
                        ->join('vente_lignes', 'vente_lignes.id', '=', 'livraison_vente_magasins.vente_ligne_id')
                        ->join('articles', 'articles.id', '=', 'vente_lignes.article_id')
                        ->join('unite_mesures', 'unite_mesures.id', '=', 'vente_lignes.unite_mesure_id')
                        ->join('bon_ventes', 'bon_ventes.vente_id', '=', 'vente_lignes.vente_id')
                        ->join('magasins', 'livraison_vente_magasins.magasin_id', '=', 'magasins.id')
                        ->where('livraison_vente_magasins.bon_vente_id', $id)
                        ->select(
                            'magasins.nom as magasin',
                            'vente_lignes.qte_livre as livre',
                            'vente_lignes.qte_cmde',
                            'unite_mesures.unite',
                            'articles.nom',
                            'bon_ventes.code_bon',
                            'livraison_vente_magasins.*',
                            'vente_lignes.id as ligneId',
                            'bon_ventes.id as bonId',
                            'articles.id as article_id'
                        )
                        ->orderByDesc('id')
                        ->get();

         return view('pages.ventes-module.ventes.bons.show', compact('bon', 'livraisons', 'i'));
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

    public function detailsBon($id)
    {
        $articles = DB::table('vente_lignes')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'vente_lignes.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'vente_lignes.article_id')
            ->join('bon_ventes', 'bon_ventes.vente_id', '=', 'vente_lignes.vente_id')
            ->join('ventes', 'vente_lignes.vente_id', '=', 'ventes.id')
            ->join('clients', 'ventes.client_id', '=', 'clients.id')
            ->where('bon_ventes.id', $id)
            ->select(
                'vente_lignes.*',
                'unite_mesures.unite',
                'articles.nom',
                'clients.nom_client',
                'bon_ventes.code_bon',
                'bon_ventes.id as bonId',
                'articles.id as article_id'
            )
            ->get();

        return response()->json([
            'articles'  => $articles
        ]);
    }

}
