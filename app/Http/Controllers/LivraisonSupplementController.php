<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use App\Models\Article;
use App\Models\ArticlePointVente;
use App\Models\LigneCommande;
use App\Models\LigneSupplementCommande;
use App\Models\Magasin;
use App\Models\SupplementCommande;
use App\Models\TauxConversion;
use App\Models\UniteMesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LivraisonSupplementController extends Controller
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
        $commandes = SupplementCommande::all();
        $magasins = Magasin::all();
        return view('pages.achats-module.supplements.livraison', compact('commandes', 'magasins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            // 'date_cmd' => 'required',
            'date_livraison' => 'required',
            'supplement_id' => 'required',
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'magasin_id' => 'required',
            'prixUnits.*' => 'required',
            'unites.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $magasin = Magasin::find($request->magasin_id);

        $point_vente = $magasin->pointVente->id;

        DB::beginTransaction();

        try {
            $count = count($request->qte_cdes);

            for ($i = 0; $i < $count; $i++) {
                $ligne = LigneSupplementCommande::find($request->articles[$i]);
                $element = ArticlePointVente::where('article_id', $ligne->article_id)->where('point_vente_id', $point_vente)->first();

                $unite = UniteMesure::where('unite', $request->unites[$i])->first();
                $unite_base = Article::whereId($ligne->article_id)->first()->unite_mesure_id;

                $conversionItem = TauxConversion::where('parent_id', $unite_base)->first();
                if (!is_null($conversionItem)) {
                    $qte_vrai = $request->qte_cdes[$i] * $conversionItem->taux_conversion;
                } else {
                    return redirect()->route('livraisons.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }

                if ($element == null) {
                    $article = ArticlePointVente::create([
                        'point_vente_id' => $point_vente,
                        'article_id' => $ligne->article_id,
                    ]);
                    $qte_stock = $article->qte_stock + $qte_vrai;
                    $article->update(['qte_stock' => $qte_stock]);
                } else {
                    $qte_stock = $element->qte_stock + $qte_vrai;
                    $element->update(['qte_stock' => $qte_stock]);
                }
                $appro = Approvisionnement::create([
                    'date_livraison' => $request->date_livraison,
                    'qte_livre' => $request->qte_cdes[$i],
                    'ligne_commande_id' => $request->articles[$i],
                    'unite_mesure_id' => $unite->id,
                    'magasin_id' => $request->magasin_id,
                    'user_id' => Auth::user()->id,
                ]);
                $ligne->update([
                    'qte_cmde' => $ligne->qte_cmde - (float)$request->qte_cdes[$i]
                ]);
            }
            DB::commit();
            return redirect()->route('livraisons.index')->with('success', 'Livraison enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('livraisons.index')->withErrors(['message' => 'Erreur enregistrement Bon de commande']);
        }
    }
}
