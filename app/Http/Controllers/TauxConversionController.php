<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\TauxConversion;
use App\Models\UniteMesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TauxConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taux = DB::table('taux_conversions')
        ->join('unite_mesures', 'taux_conversions.unite_mesure_id', '=', 'unite_mesures.id')
        ->join('articles', 'taux_conversions.article_id', '=', 'articles.id')
        ->join('unite_mesures as article_unites', 'articles.unite_mesure_id', '=', 'article_unites.id')
        ->select('taux_conversions.*', 'articles.unite_mesure_id as unite_mesure_id', 'unite_mesures.unite as unite_mesure_nom', 'unite_mesures.id as unite_id', 'article_unites.unite as article_unite_nom', 'articles.nom as article_nom', 'articles.id as article_id')
        ->get();

        $unites = UniteMesure::all();

        return view('pages.articles.taux_convert', compact('taux', 'unites'));

        // return response()->json([
        //     'taux'  => $taux
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unite_mesure_id' => 'required',
            'taux_conversion' => 'required',
            // 'taux_conversion' => 'required|regex:/^[0-9]+(?:\.[0-9]+)?$/',
            'article_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        TauxConversion::updateOrCreate(
            [
                'unite_mesure_id' => $request->unite_mesure_id,
                'article_id' => $request->article_id,
            ],
            ['taux_conversion' => $request->taux_conversion,]
        );

        return redirect()->route('liste_taux_convert')
            ->with('success', 'Taux configuré avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function getUnitesByArticleId($id)
    {
        $unitesIds = TauxConversion::where('article_id', $id)->pluck('unite_mesure_id');
        $unites = UniteMesure::whereIn('id', $unitesIds)->get();

        return response()->json(['unites' => $unites]);
    }

    public function convertirUniteVenteEnUniteStock($article_id, $uniteVente, $qte, )
    {
        $article = Article::find($article_id);

        $conversionItem = $article->getPivotValueForUnite($uniteVente);
        if (!is_null($conversionItem)) {
            $tauxConversion = $conversionItem;
            if ($tauxConversion < 1){
                $qte_vrai = $qte / $tauxConversion;
            }else{
                $qte_vrai = $qte * $tauxConversion;
            }
            return response()->json(['qteConvertie' => $qte_vrai]);

        }
    }

    public function uniteParDefaut() {
        $articles = Article::all();
        $unites = UniteMesure::all();
        foreach ($articles as $article) {
            $unites->map(function ($item) use ($article){
                if ($article->unite_mesure_id == $item['id']) {
                    TauxConversion::updateOrCreate([
                        'unite_mesure_id' =>  $item['id'],
                        'article_id'      =>   $article->id
                    ],
                    [
                        'taux_conversion' =>  1
                    ]);
                }
            });
        }

        return  response()->json(['message' => "Opération réussie"]);
    }

    public function updateMasse(Request $request) {
        $taux = array_slice($request->taux, 25); // La soumission renvoie une duplication sur les éléments affichés, donc enlever les 25 premiers qui sont affichés
        $article_id = array_slice($request->article_id, 25);
        $unite_mesure = array_slice($request->unite_mesure, 25);
        $taux_id = array_slice($request->taux_id, 25);
        // print_r($unite_mesure);

        foreach($taux AS $key=>$value){
            TauxConversion::where('id', $taux_id[$key])
                          ->update([
                                'taux_conversion' =>  floatval(str_replace(',', '.', $value)),
                                'unite_mesure_id' => $unite_mesure[$key],
                                'article_id' => $article_id[$key],
                            ]);


            // TauxConversion::updateOrCreate(
            //     [
            //         'unite_mesure_id' => $unite_mesure[$key],
            //         'article_id' => $article_id[$key],
            //     ],
            //     [
            //         'taux_conversion' => $value,
            //         'unite_mesure_id' => $unite_mesure[$key],
            //         'article_id' => $article_id[$key],
            //     ]
            // );

            // Article::where('id', $article_id[$key])
            //         ->update(['unite_mesure_id' => $unite_mesure_base[$key]]);
        }

        return redirect()->route('liste_taux_convert')
        ->with('success', 'Taux configuré avec succès.');
    }

    public function unite_base() {
        $taux = DB::table('articles')
        ->join('unite_mesures as article_unites', 'articles.unite_mesure_id', '=', 'article_unites.id')
        ->select('articles.unite_mesure_id as unite_mesure_id', 'article_unites.unite as unite_mesure_nom', 'article_unites.id as unite_id', 'article_unites.unite as article_unite_nom', 'articles.nom as article_nom', 'articles.id as article_id')
        ->get();

        $unites = UniteMesure::all();

        return view('pages.articles.unite_base_up', compact('taux', 'unites'));
    }

    public function UniteBaseMassUpdate(Request $request) {
        if(count($request->article_id) > 25){
            $article_id = array_slice($request->article_id, 25);
            $unite_mesure_base = array_slice($request->unite_mesure_base, 25);
        }else{
            $article_id = $request->article_id;
            $unite_mesure_base = array_slice($request->unite_mesure_base, 25);
        }
        // print_r($unite_mesure);

        foreach($article_id AS $key=>$value){

            Article::where('id', $value)
                    ->update(['unite_mesure_id' => $unite_mesure_base[$key]]);
        }

        return redirect()->route('UniteBase')
        ->with('success', 'Unité de base configurée avec succès.');
    }
}
