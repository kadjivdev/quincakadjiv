<?php

namespace App\Http\Controllers;

use App\Imports\ArticleImport;
use App\Imports\PrixArticleImport;
use App\Models\Article;
use App\Models\ArticlePointVente;
use App\Models\Categorie;
use App\Models\Magasin;
use App\Models\PointVente;
use App\Models\StockMagasin;
use App\Models\TauxConversion;
use App\Models\UniteMesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ArticleController extends Controller
{
    public function listArticles(Request $request)
    {
        $articles = Article::with('categorie')->where('nom', 'LIKE', '%' . $request->input('term', '') . '%')
            ->get();

        return response()->json([
            'articles'  => $articles
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles_all = Article::all();
        return view('pages.articles.index', compact('articles_all'));
    }

    public function check_article(Request $request)
    {
        $articles_all = Article::all();
        $articles = Article::with(['categorie', 'uniteBase'])->where("id", $request->id_art_sel)->get();
        $unites = UniteMesure::all();
        $points = PointVente::all();

        $i = 1;
        return view('pages.articles.index', compact('i', 'articles_all', 'articles', 'unites', 'points'));
    }

    public function allArticles(Request $request)
    {
        $articles_all = Article::all();
        $articles = $articles_all;
        $unites = UniteMesure::all();
        $points = PointVente::all();
        $i = 1;
        return view('pages.articles.index', compact('i', 'articles_all', 'articles', 'unites', 'points'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categorie::all();
        $unites = UniteMesure::all();
        return view('pages.articles.create', compact('categories', 'unites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|unique:articles,nom',
            'stock_alert' => 'required|numeric',
            'unite_mesure_id' => 'required',
            'categorie_id' => 'required',
            'magasins.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (count(Article::all()) == 0) {
            $nbr = 0;
        } else {
            $nbr = Article::latest()->first()->id;
        }

        $categorie = Categorie::find($request->categorie_id);
        $code = 'ART' . premiereLettre($categorie->libelle) . derniereLettre($categorie->libelle) . '-' . ($nbr + 1);
        $article = Article::create([
            'nom' => $request->nom,
            // 'code_article' => $code,
            'stock_alert' => $request->stock_alert,
            'unite_mesure_id' => $request->unite_mesure_id,
            'categorie_id' => $request->categorie_id,
        ]);

        foreach ($request->magasins as $value) {
            $magasin = Magasin::find($value);
            ArticlePointVente::create([
                'article_id' => $article->id,
                'point_vente_id' => $magasin->point_vente_id,
            ]);

            StockMagasin::create([
                'article_id' => $article->id,
                'magasin_id' => $value
            ]);
        }

        return redirect()->route('articles.index')
            ->with('success', 'Article ajouté avec succès.');
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
        // $article = Article::find($id);
        $categories = Categorie::all();
        $unites = UniteMesure::all();

        //     $articleDetails = DB::table('articles')
        // ->join('categories', 'articles.categorie_id', '=', 'categories.id')
        // ->join('unite_mesures', 'articles.unite_mesure_id', '=', 'unite_mesures.id')
        // ->select('articles.*', 'categories.libelle', 'unite_mesures.unite')
        // ->get();

        $article = Article::join('categories', 'articles.categorie_id', '=', 'categories.id')
            ->join('unite_mesures', 'articles.unite_mesure_id', '=', 'unite_mesures.id')
            ->where('articles.id', $id)
            ->select('articles.*',  'categories.libelle', 'unite_mesures.unite')
            ->first();

        $stock_magasins = StockMagasin::where('article_id', $id)->get();
        $magasins = Magasin::all();

        // dd($magasins);


        // return response()->json([
        //     'article'  => $articleDetails
        // ]);

        return view('pages.articles.edit', compact('article', 'categories', 'unites', 'stock_magasins', 'magasins'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'nom' => 'required|string',
            'stock_alert' => 'required|numeric',
            'categorie_id' => 'required',
            'unite_mesure_id' => 'required',
            'magasins.*' => 'required',
        ]);

        $article = Article::find($id);
        $article->update([
            'nom' => $request->nom,
            'stock_alert' => $request->stock_alert,
            'unite_mesure_id' => $request->unite_mesure_id,
            'categorie_id' => $request->categorie_id,
        ]);

        $stock_magasins = StockMagasin::where('article_id', $id)->get();
        $id_magasins = $stock_magasins->pluck('magasin_id')->toArray();

        $mag_to_del = array_diff($id_magasins, $request->magasins);
        $new_mag = array_diff($request->magasins, $id_magasins);

        foreach ($new_mag as $new) {
            $magasin = Magasin::find($new);
            ArticlePointVente::create([
                'article_id' => $article->id,
                'point_vente_id' => $magasin->point_vente_id,
            ]);

            StockMagasin::create([
                'article_id' => $article->id,
                'magasin_id' => $new
            ]);
        }

        foreach ($mag_to_del as $for_del) {
            $magasin = Magasin::find($for_del);
            $art_point_vente = ArticlePointVente::where('article_id', $article->id)->where('point_vente_id', $magasin->point_vente_id)->first();
            $stock_mag = StockMagasin::where('article_id', $article->id)->where('magasin_id', $magasin->id)->first();

            if ($art_point_vente->qte_stock > 0 || $stock_mag->qte_stock > 0) {
                return redirect()->back()->withErrors('Le magasin ' . $magasin->nom . ' ne peut être supprimé pour raison de stock disponible pour cet article')->withInput();
            } else {
                $art_point_vente->delete();
                $stock_mag->delete();
            }
        }

        return redirect()->route('articles.index')
            ->with('success', 'Article modifié avec succès.');
    }


    public function addStock(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'article_id' => 'required|string',
            'qte_stock' => 'required',
            'unite_mesure_id' => 'required',
            'point_vente_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect("/articles")->withErrors($validator)->withInput();
        }

        $article = Article::find($request->article_id);

        $conversionItem = $article->getPivotValueForUnite($request->unite_mesure_id);
        // dd($conversionItem);
        if (!is_null($conversionItem)) {
            $qte_vrai = $request->qte_stock * $conversionItem;
        } else {
            return redirect()->route('articles.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
        }

        ArticlePointVente::updateOrCreate(
            [
                'article_id' => $request->article_id,
                'point_vente_id' => $request->point_vente_id,
            ],
            ['qte_stock' => $qte_vrai]
        );
       
        return redirect()->route('articles.index')
            ->with('success', 'Stock enregistré avec succès.');
    }


    public function import_xls(Request $request)
    {
        $this->validate($request, [
            'upload_xls'  => 'required|mimes:xls,xlsx'
        ]);

        //  $articles = DB::table('articles')->delete();

        Excel::import(new ArticleImport, $request->file('upload_xls'));

        return redirect()->route('articles.index')->with('status', 'Articles importés avec succès.');
    }

    public function import_prix(Request $request)
    {
        $this->validate($request, [
            'upload_xls'  => 'required|mimes:xls,xlsx'
        ]);
        //  dd($request->all());
        Excel::import(new PrixArticleImport, $request->file('upload_xls'));

        return redirect()->route('articles.index')->with('status', 'Articles prix importés avec succès.');
    }
}
