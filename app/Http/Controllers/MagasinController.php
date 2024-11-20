<?php

namespace App\Http\Controllers;

use App\Models\BonVente;
use App\Models\LivraisonVenteMagasin;
use App\Models\Magasin;
use App\Models\PointVente;
use App\Models\StockMagasin;
use App\Models\User;
use App\Models\VenteLigne;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MagasinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $i = 1;
        $user = Auth::user();
        if ($user->isAdmin()) {
            $magasins = Magasin::all();
        } else {
            $magasins = Magasin::where('point_vente_id', $user->point_vente_id)->get();
        }
        return view('pages.magasins.index', compact('magasins', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $points = PointVente::all();
        return view('pages.magasins.create', compact('points'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|unique:magasins,nom',
            'adresse' => 'required',
            'point_vente_id' => 'required',
            // 'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $article = Magasin::create([
            'nom' => $request->nom,
            'adresse' => $request->adresse,
            'point_vente_id' => $request->point_vente_id,
            // 'user_id' =>  $request->user_id,
        ]);

        if ($article) {
            return redirect()->route('magasins.index')
                ->with('success', 'Magasin ajouté avec succès.');
        } else {
            return redirect()->route('magasins.index')->withErrors(['message' => 'Erreur enregistrement Bon de commande']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $magasin = Magasin::find($id);
        $articles =  $magasin->stock_articles()
            ->select(
                'articles.*',
                'qte_stock',
                'categories.libelle AS category_name',
            )
            ->join('categories', 'articles.categorie_id', '=', 'categories.id') // Join articles and categories tables
            ->get();
        $i = 1;
        return view('pages.magasins.show', compact('magasin', 'articles', 'i'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $magasin = Magasin::find($id);
        $points = PointVente::all();

        return view('pages.magasins.edit', compact('magasin', 'points'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required',
            'adresse' => 'required',
            'point_vente_id' => 'required',
            // 'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $magasin = Magasin::find($id);
        $magasin->nom = $request->nom;
        $magasin->adresse = $request->adresse;
        $magasin->point_vente_id = $request->point_vente_id;
        $magasin->save();

        return redirect()->route('magasins.index')
                ->with('success', 'Magasin modifié avec succès.');
    }
    

    public function livraisonVenteView()
    {
        // $bons = BonVente::with(['vente'])->where('validated_at', '=', null)->get();
        // $magasin = Magasin::where('point_vente_id',  Auth::user()->point_vente_id)->first();

        $point = PointVente::find(Auth::user()->point_vente_id);
        $users = $point->users()->pluck('id');
        $magasins = Magasin::where('point_vente_id', Auth::user()->point_vente_id)->get();
        $bons = DB::table('bon_ventes')
            ->join('ventes', 'ventes.id', '=', 'bon_ventes.vente_id')
            ->join('users', 'users.id', '=', 'ventes.user_id')
            ->join('clients', 'clients.id', '=', 'ventes.client_id')
            ->whereIn('ventes.user_id', $users)
            ->select('bon_ventes.*', 'clients.nom_client')
            ->get();

        return view('pages.ventes-module.ventes.livraison', compact('bons', 'magasins'));
    }

    public function validerVente(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qte_livre' => 'required',
            'magasin_id' => 'required',
            'bon_vente_id' => 'required',
            'unite.*' => 'required',
            'qte_livre.*' => 'required',
            'vente_lignes.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // dd($request->all());
        $count = count($request->qte_livre);
        DB::beginTransaction();

        try {
            for ($i = 0; $i < $count; $i++) {
                $ligne = VenteLigne::findOrFail($request->vente_lignes[$i]);
                // dd($ligne);

                $livraison = LivraisonVenteMagasin::create([
                    'vente_ligne_id' => $request->vente_lignes[$i],
                    'qte_livre' => $request->qte_livre[$i],
                    'bon_vente_id' => $request->bon_vente_id,
                    'magasin_id' => $request->magasin_id,
                    'bon_livraison_vente_comptant_id' => 0,
                    'user_id' => Auth::id(),
                    'statut' => 'Non livré',
                ]);

                $stock = StockMagasin::where('article_id', $ligne->article_id)
                    ->where('magasin_id', $request->magasin_id)
                    ->where('qte_stock', '>', 0)
                    ->first();

                if ($stock) {
                    $stock->update([
                        'qte_stock' => $stock->qte_stock - (float)$request->qte_livre[$i],
                    ]);
                }else {
                    return redirect()->back()->withErrors(['message' => 'Stock non suffisant']);
                }

                $ligne->update([
                    'qte_livre' => $ligne->qte_livre - (float)$request->qte_livre[$i],
                ]);
            }

            DB::commit();
            return redirect()->route('bons-ventes.index')->with('success', 'Livraison ajoutée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('livraion-vente')->withErrors(['message' => 'Erreur enregistrement livraison vente']);
        }
    }

    public function listMagasins()
    {
        $magasins = Magasin::all();

        return response()->json([
            'magasins'  => $magasins
        ]);
    }

    public function listStockMagasins($id)
    {
        $articles =  Magasin::find($id)
            ->stock_articles()
            ->wherePivot('qte_stock', '>', 0)
            ->select('articles.*', 'qte_stock')
            ->get();

        return response()->json([
            'articles'  => $articles
        ]);
    }

    public function valider($id)
    {
        $bon = LivraisonVenteMagasin::find($id);

        $bon->validator_id = Auth::user()->id;
        $bon->validated_at = now();
        $bon->statut = 'Livré';
        $bon->save();

        return redirect()->route('bons-ventes.index')
            ->with('success', 'Livraison validée avec succès.');
    }

    public function destroy($id) {
        $magasin = Magasin::find($id);

        // Vérification des relations avant la suppression
        $hasRelations = false;

        // Vérification de la relation pointVente
        if ($magasin->pointVente()->exists()) {
            $hasRelations = true;
        }

        // Vérification de la relation stock_articles (many-to-many)
        if ($magasin->stock_articles()->exists()) {
            $hasRelations = true;
        }

        // Si le magasin est utilisé quelque part, ne pas le supprimer
        if ($hasRelations) {
            return redirect()->back()->withErrors('Impossible de supprimer ce magasin car il est utilisé ailleurs.');
        }

        $magasin->delete();

        return redirect()->route('magasins.index')->with('success', 'Magasin supprimé avec succès.');
    }
}
