<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\LigneSupplementCommande;
use App\Models\SupplementCommande;
use App\Models\UniteMesure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commandes = DB::table('supplement_commandes')
            ->join('commandes', 'commandes.id', '=', 'supplement_commandes.commande_id')
            ->join('fournisseurs', 'fournisseurs.id', '=', 'commandes.fournisseur_id')
            ->select('supplement_commandes.*', 'fournisseurs.name', 'commandes.reference as ref')
            ->paginate(20);

            $i = 1;
        return view('pages.achats-module.supplements.index', compact('commandes', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $commande = Commande::find($id);

        return view('pages.achats-module.supplements.create', compact('commande'));
    }

    public function articlesCommande($id)
    {
        $articles = DB::table('articles')
            ->join('ligne_commandes', 'ligne_commandes.article_id', '=', 'articles.id')
            ->join('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_commandes.unite_mesure_id')
            ->where('ligne_commandes.commande_id', $id)
            ->where('ligne_commandes.qte_cmde', '=', 0)
            ->select('articles.*', 'ligne_commandes.prix_unit', 'ligne_commandes.qte_cmde', 'unite_mesures.unite')
            ->distinct()
            ->get();

        return response()->json([
            'articles'  => $articles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'date_cmd' => 'required',
            'commande_id' => 'required',
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'unites.*' => 'required',
            'prixUnits.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nbr = Commande::max('id');

        DB::beginTransaction();

        try {
            $supplement = SupplementCommande::create([
                'date_cmd' => Carbon::now(),
                'statut' => 'Lancée',
                'commande_id' => $request->commande_id,
                'user_id' => Auth::user()->id,
            ]);

            $count = count($request->qte_cdes);
            for ($i = 0; $i < $count; $i++) {
                $unite_id = UniteMesure::where('unite', $request->unites[$i])->first()->id;

                $ligne = LigneSupplementCommande::create([
                    'qte_cmde' => $request->qte_cdes[$i],
                    'article_id' => $request->articles[$i],
                    'prix_unit' => $request->prixUnits[$i],
                    'unite_mesure_id' => $unite_id,
                    'supplement_commande_id' => $supplement->id,
                ]);
            }
            DB::commit();

            return redirect()->route('supplements.index')->with('success', 'commande enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('supplements.index')->with('error', 'Erreur enregistrement Bon de commande.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bon = Commande::find($id);
        $lignes =  DB::table('ligne_supplement_commandes')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_supplement_commandes.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'ligne_supplement_commandes.article_id')
            ->join('supplement_commandes', 'supplement_commandes.id', '=', 'ligne_supplement_commandes.supplement_commande_id')
            ->where('supplement_commandes.commande_id', $id)
            ->select('ligne_supplement_commandes.*', 'unite_mesures.unite', 'articles.nom')
            ->get();
        return view('pages.achats-module.supplements.show', compact('lignes', 'bon'));
    }

    public function lignesSup($id)
    {
        $articles = DB::table('ligne_supplement_commandes')
            ->join('articles', 'articles.id', '=', 'ligne_supplement_commandes.article_id')
            ->join('supplement_commandes', 'ligne_supplement_commandes.supplement_commande_id', '=', 'supplement_commandes.commande_id')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_supplement_commandes.unite_mesure_id')
            ->where('commande_id', $id)
            ->where('ligne_supplement_commandes.qte_cmde', '>', 0)
            ->select('ligne_supplement_commandes.*', 'articles.nom', 'unite_mesures.unite')
            ->distinct()
            ->get();

        return response()->json([
            'articles'  => $articles
        ]);
    }
}
