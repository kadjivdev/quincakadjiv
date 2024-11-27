<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\Article;
use App\Models\Client;
use App\Models\Devis;
use App\Models\DevisDetail;
use App\Models\Facture;
use App\Models\PointVente;
use App\Models\UniteMesure;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DevisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $i = 1;
        
        $devis = Devis::whereNotIn('id', function($query) {
            $query->select('devis_id')
                ->from('factures');
        })->orderByDesc('id')
        ->get();

        $devisIds = Facture::pluck('devis_id');
        return view('pages.ventes-module.devis.index', compact('devis', 'devisIds', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('seuil', '>', 'credit_total')->get();

        $pointVendueId = Auth::user()->point_vente_id;
        $articles = PointVente::find($pointVendueId)
            ->articles()
            ->wherePivot('qte_stock', '>', 0)
            ->select('articles.*', 'qte_stock', 'prix_special')
            ->get();
        $unites = UniteMesure::all();
        return view('pages.ventes-module.devis.create', compact('unites', 'clients', 'articles', 'pointVendueId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'date_pf' => 'required',
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'unites.*' => 'required',
            'prixUnits.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $nbr = Devis::max('id');
        $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));
        // dd($lettres);
        DB::beginTransaction();

        try {
            $devis = Devis::create([
                'date_devis' => $request->date_pf,
                'statut' => 'Lancée',
                'client_id' => $request->client_id,
                'reference' => 'KAD-'. 'D' . ($nbr + 1).'-'.date('dmY') . '-' . $lettres,
                'user_id' => Auth::user()->id,
            ]);

            $count = count($request->qte_cdes);
            for ($i = 0; $i < $count; $i++) {
                DevisDetail::create([
                    'qte_cmde' => $request->qte_cdes[$i],
                    'article_id' => $request->articles[$i],
                    'prix_unit' => $request->prixUnits[$i],
                    'unite_mesure_id' => $request->unites[$i],
                    'devis_id' => $devis->id,
                ]);
            }

            DB::commit();

            // return redirect()->route('generate-proforma');

            return redirect()->route('devis.index')->with('success', 'Proforma enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('devis.index')->with('error', 'Erreur enregistrement du proforma.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Devis::find($id);
        $lignes =  DB::table('devis_details')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'devis_details.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'devis_details.article_id')
            ->where('devis_details.devis_id', $id)
            ->select('devis_details.*', 'unite_mesures.unite', 'articles.nom')
            ->get();
        $i = 1;

        return view('pages.ventes-module.devis.show', compact('lignes', 'item', 'i'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $devis = Devis::find($id);
        $client = $devis->client->nom_client;
        $lignes = DB::table('devis_details')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'devis_details.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'devis_details.article_id')
            ->join('devis', 'devis.id', '=', 'devis_details.devis_id')
            ->where('devis_details.devis_id', $id)
            ->select('devis_details.*', 'unite_mesures.unite', 'articles.nom', 'devis.date_devis')
            ->get();

        $articles =  Article::all();
        $unites = UniteMesure::all();

        return view('pages.ventes-module.devis.edit', compact('devis', 'lignes', 'articles', 'unites', 'client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Devis::find($id);
        $itemId = $item->id;
        // DB::table('devis_details')
        //     ->where('devis', $itemId)
        //     ->delete();
        $count = count($request->qte_cdes);
        for ($i = 0; $i < $count; $i++) {
            DevisDetail::updateOrCreate(
                [
                    'devis_id' => $itemId,
                    'article_id' => $request->articles[$i],
                ],
                [
                    'qte_cmde' => $request->qte_cdes[$i],
                    'prix_unit' => $request->prixUnits[$i],
                    'unite_mesure_id' => $request->unites[$i],
                ]
            );
        }

        return redirect()->route('devis.index')->with('success', 'Proforma modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Devis::find($id);
        $item->delete();
        return redirect()->route('devis.index')->with('success', 'Proforma supprimé avec succès.');
    }

    public function valider($id)
    {
        $item = Devis::find($id);
        $item->statut = 'Valide';
        $item->valideur_id = Auth::user()->id;
        $item->validated_at = now();
        $item->save();
        return response()->json(['redirectUrl' => route('devis.index')]);

        // return redirect()->route('item-commandes.index')->with('success', 'item de commande validé avec succès.');
    }

    public function lignesDevis($id)
    {
        $articles = DB::table('devis_details')
            ->join('devis', 'devis_details.devis_id', '=', 'devis.id')
            ->join('articles', 'articles.id', '=', 'devis_details.article_id')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'devis_details.unite_mesure_id')
            ->join('clients', 'devis.client_id', '=', 'clients.id') // Ajout de la jointure avec la table clients
            ->where('devis_id', $id)
            ->where('devis_details.qte_cmde', '>', 0)
            ->select(
                'devis_details.*',
                'articles.nom',
                'unite_mesures.unite',
                'clients.nom_client',
                'clients.id',
                'clients.seuil'
            )
            ->distinct()
            ->get();

        // dd($articles);

        return response()->json([
            'articles'  => $articles
        ]);
    }

    public function listArticlesPoint()
    {
        // Récupère les articles qui sont vendus dans le point de vente du user connecté
        $pointVendueId = Auth::user()->point_vente_id;
        $articles = PointVente::find($pointVendueId)
            ->articles()
            ->wherePivot('qte_stock', '>', 0)
            ->get();

        return response()->json([
            'articles'  => $articles
        ]);
    }

    public function pdf($id)
    {
        // $data = Facture::with(['articles'])->where('id', $id)->first()->toArray();
        $data = DB::table('devis_details')
            ->join('devis', 'devis.id', '=', 'devis_details.devis_id')
            ->join('clients', 'devis.client_id', '=', 'clients.id')
            ->join('articles', 'articles.id', '=', 'devis_details.article_id')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'devis_details.unite_mesure_id')
            ->where('devis_id', $id)
            ->select(
                'devis_details.*',
                'articles.nom',
                'clients.nom_client',
                'unite_mesures.unite',
                'devis.*',
                DB::raw('SUM(devis_details.prix_unit * devis_details.qte_cmde) as total_amount')
            )
            ->distinct()
            ->get()->toArray();


        $pdf = Pdf::loadView('pdf.devis', compact('data'));
        $date =  date("Y-m-d");
        return $pdf->download($date . '.pdf');
    }
}
