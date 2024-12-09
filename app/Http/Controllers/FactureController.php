<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\Article;
use App\Models\ArticleFacture;
use App\Models\ArticlePointVente;
use App\Models\Devis;
use App\Models\DevisDetail;
use App\Models\Facture;
use App\Models\CompteClient;
use App\Models\FactureType;
use App\Models\UniteMesure;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ->orderBy('article_factures.created_at', 'desc')

        $factures = Facture::whereNull('validate_at')->orderBy('id', 'desc')->get();
        // dd($factures[32]);

        return view('pages.ventes-module.factures.index', compact('factures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ids = Facture::pluck('devis_id');
        $devis = Devis::whereNotIn('id', $ids)->orderBy('id', 'desc')->get();
        $types = FactureType::all();
        return view('pages.ventes-module.factures.create', compact('devis', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required',
            'devis_id' => 'required',
            'date_fact' => 'required',
            'montant_facture' => 'required',
            'taux_remise' => 'required',
            'montant_total' => 'required',
            'montant_regle' => 'required',
            'unite.*' => 'required',
            'qte_cmde.*' => 'required',
            'article.*' => 'required',
            'prix_unit.*' => 'required',
            'client_nom' => 'required',
            'client_id' => 'required',
            'tva' => 'required',
            'aib' => 'required',
        ]);




        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }



        $nbr = Facture::max('id');

        DB::beginTransaction();
        try {
            if ($request->montant_total == $request->montant_regle) {
                $statut = 'Soldé';
            } else {
                $statut = 'Non soldé';
            }

            $nbr = Facture::max('id');
            $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));

            
            $facture = Facture::create([
                'date_facture' => $request->date_fact,
                'statut' => $statut,
                'devis_id' => $request->devis_id,
                'client_facture' => $request->client_nom,
                'montant_facture' => $request->montant_facture,
                'montant_total' => $request->montant_total,
                'montant_regle' => $request->montant_regle,
                'taux_remise' => (float)$request->taux_remise,
                'num_facture' => 'KAD-'. 'FVP' . ($nbr + 1).'-'.date('dmY') . '-' . $lettres,
                // 'num_facture' => 'FacD' . date('dmY') . ($nbr + 1),
                'user_id' => Auth::user()->id,
                'facture_type_id' => $request->type_id,
                'tva' => (float)$request->tva,
                'aib' => (float)$request->aib,
            ]);


            // $compte_client = CompteClient::create([
            //     'date_op' => $facture->date_facture,
            //     'montant_op' => $facture->montant_total,
            //     'facture_id' => $facture->id,
            //     'client_id' => $request->client_id,
            //     'user_id'=> Auth::user()->id,
            //     'type_op' => 'FAC_VP',
            //     'cle' => $facture->id,
            // ]);

            // if((float)$request->montant_regle > 0){
            //     $compte_client_reg = CompteClient::create([
            //         'date_op' => $facture->date_facture,
            //         'montant_op' => $facture->montant_regle,
            //         'facture_id' => $facture->id,
            //         'client_id' => $request->client_id,
            //         'user_id'=> Auth::user()->id,
            //         'type_op' => 'REG_VP',
            //         'cle' => $facture->id,
            //     ]);

            // }

            // dd($compte_client);

            $count = count($request->qte_cmde);
            for ($i = 0; $i < $count; $i++) {

                $devis = DevisDetail::where('devis_id', $request->devis_id)->where('article_id', $request->article[$i])->first();

                $ligne = ArticleFacture::create([
                    'qte_cmd' => $request->qte_cmde[$i],
                    'article_id' => $request->article[$i],
                    'prix_unit' => $request->prix_unit[$i],
                    'unite_mesure_id' => $request->unite[$i],
                    'facture_id' => $facture->id,
                ]);

                $devis->update([
                    'qte_cmde' => $request->qte_cmde[$i],
                    'prix_unit' => $request->prix_unit[$i]
                ]);
            }

            DB::commit();
            return redirect()->route('factures.index')->with('success', 'Facture enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('factures.index')->with('error', 'Erreur enregistrement de facture.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $facture = DB::table('factures')
            ->join('devis', 'devis.id', '=', 'factures.devis_id')
            ->where('factures.id', $id)
            ->select('factures.*', 'devis.reference')
            ->first();
        
        $types = FactureType::all();

        // dd($facture);

        return view('pages.ventes-module.factures.show', compact('facture', 'types'));
    }

    public function articlesFacture($id)
    {
        $articles = DB::table('article_factures')
            ->join('factures', 'article_factures.facture_id', '=', 'factures.id')
            ->join('articles', 'articles.id', '=', 'article_factures.article_id')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'article_factures.unite_mesure_id')
            ->where('facture_id', $id)
            ->where('article_factures.qte_cmd', '>', 0)
            ->select('article_factures.*', 'articles.nom', 'unite_mesures.unite', 'factures.client_facture')
            ->distinct()
            ->get();

        return response()->json([
            'articles'  => $articles
        ]);
    }

    public function pdf($id)
    {
        // $data = Facture::with(['articles'])->where('id', $id)->first()->toArray();
        $data = DB::table('article_factures')
            ->join('factures', 'article_factures.facture_id', '=', 'factures.id')
            ->join('devis', 'devis.id', '=', 'factures.devis_id')
            ->join('articles', 'articles.id', '=', 'article_factures.article_id')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'article_factures.unite_mesure_id')
            ->where('facture_id', $id)
            ->select('article_factures.*', 'articles.nom', 'unite_mesures.unite', 'devis.reference', 'factures.*')
            ->distinct()
            ->get()->toArray();

        // dd($data[0]->id);

        $pdf = Pdf::loadView('pdf.factureDevis', compact('data'));
        $date =  date("Y-m-d");
        return $pdf->download($date . '.pdf');
    }

    public function validate_fact($id){

        $validator = Auth::user()->id;
        $currentDateTime = Carbon::now();
        $point_vente = Auth::user()->boutique;

        DB::beginTransaction();

        try{
            $lignes = ArticleFacture::where('facture_id', $id)->get();

            foreach ($lignes AS $ligne){
                $element = ArticlePointVente::where('article_id', $ligne->article_id )->where('point_vente_id', $point_vente->id)->first();
                $unite_base = Article::find($ligne->article_id)->unite_mesure_id;
                $article = Article::find($ligne->article_id);

                $conversionItem = $article->getPivotValueForUnite($ligne->unite_mesure_id);

                if (!is_null($conversionItem)) {
                    $tauxConversion = $conversionItem;
                    if ($tauxConversion < 1){
                        $qte_vrai = $ligne->qte_cmd / $tauxConversion;
                    }else{
                        $qte_vrai = $ligne->qte_cmd * $tauxConversion;
                    }
                } else {
                    return redirect()->route('factures.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour une ou plusieurs unité d\'article.']);
                }
                if ($element && $element->qte_stock >= $qte_vrai) {
                    $qte_stock = $element->qte_stock - (float)$qte_vrai;
                    $element->update(['qte_stock' => $qte_stock]);
                }else{
                    return redirect()->route('factures.index')->withErrors(['message' => 'Quantité insufisante pour l\'article '.$article->nom]);
                }
            }

            $facture = Facture::find($id);
            $devis = Devis::find($facture->devis_id);

            $compte_client = CompteClient::create([
                'date_op' => $facture->date_facture,
                'montant_op' => $facture->montant_total,
                'facture_id' => $facture->id,
                'client_id' => $devis->client_id,
                'user_id'=> Auth::user()->id,
                'type_op' => 'FAC_VP',
                'cle' => $facture->id,
            ]);

            if((float)$facture->montant_regle > 0){
                $compte_client_reg = CompteClient::create([
                    'date_op' => $facture->date_facture,
                    'montant_op' => $facture->montant_regle,
                    'facture_id' => $facture->id,
                    'client_id' => $devis->client_id,
                    'user_id'=> Auth::user()->id,
                    'type_op' => 'REG_VP',
                    'cle' => $facture->id,
                ]);

            }

            Facture::where('id', $id)
                        ->update([
                            'validate_by' => $validator,
                            'validate_at' => $currentDateTime
                        ]);

            DB::commit();
            return redirect()->route('factures.index')->with('success', 'Facture validée avec succès.');
        }catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return redirect()->route('factures.index')->withErrors(['message' => 'Erreur validation facture ']);
        }
    }

      /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Facture::find($id);
        $item->delete();
        return redirect()->route('factures.index')->with('success', 'Facture supprimée avec succès.');
    }

    public function edit($id) {
        // $data = Facture::with(['articles'])->where('id', $id)->first()->toArray();
        $facture = DB::table('factures')
            ->join('devis', 'devis.id', '=', 'factures.devis_id')
            ->where('factures.id', $id)
            ->select('factures.*', 'devis.reference')
            ->first();
        
        $types = FactureType::all();

        // dd($facture);

        return view('pages.ventes-module.factures.edit', compact('facture', 'types'));
        
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'type_id' => 'required',
            'devis_id' => 'required',
            'date_fact' => 'required',
            'montant_facture' => 'required',
            'taux_remise' => 'required',
            'montant_total' => 'required',
            'montant_regle' => 'required',
            'unite.*' => 'required',
            'qte_cmde.*' => 'required',
            'article.*' => 'required',
            'prix_unit.*' => 'required',
            'client_nom' => 'required',
            'client_id' => 'required',
            'tva' => 'required',
            'aib' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            if ($request->montant_total == $request->montant_regle) {
                $statut = 'Soldé';
            } else {
                $statut = 'Non soldé';
            }



            $facture = Facture::findOrFail($id);
            $facture->date_facture = $request->date_fact;
            $facture->statut = $statut;
            $facture->montant_facture = $request->montant_facture;
            $facture->montant_total = $request->montant_total;
            $facture->montant_regle = $request->montant_regle;
            $facture->taux_remise = $request->taux_remise;
            $facture->facture_type_id = $request->type_id;
            $facture->tva = (float)$request->tva;

            $facture->save();

            // CompteClient::where('facture_id', $id)->where('type_op', 'FAC_VP')->delete();
            // CompteClient::where('facture_id', $id)->where('type_op', 'REG_VP')->delete();


            // CompteClient::where('facture_id', $id)->where('client_id', $request->client_id)->where('type_op', 'FAC_VP')->delete();
            // CompteClient::where('facture_id', $id)->where('client_id', $request->client_id)->where('type_op', 'REG_VP')->delete();

            // $compte_client = CompteClient::create([
            //     'date_op' => $facture->date_facture,
            //     'montant_op' => $facture->montant_total,
            //     'facture_id' => $facture->id,
            //     'client_id' => $request->client_id,
            //     'user_id'=> Auth::user()->id,
            //     'type_op' => 'FAC_VP',
            //     'cle' => $facture->id,
            // ]);

            // if((float)$request->montant_regle > 0){
            //     $compte_client_reg = CompteClient::create([
            //         'date_op' => $facture->date_facture,
            //         'montant_op' => $facture->montant_regle,
            //         'facture_id' => $facture->id,
            //         'client_id' => $request->client_id,
            //         'user_id'=> Auth::user()->id,
            //         'type_op' => 'REG_VP',
            //         'cle' => $facture->id,
            //     ]);

            // }

            // dd($compte_client);

            ArticleFacture::where('facture_id', $id)->delete();
            DevisDetail::where('devis_id', $request->devis_id)->delete();
            $count = count($request->qte_cmde);
            for ($i = 0; $i < $count; $i++) {

                // $devis = DevisDetail::where('devis_id', $request->devis_id)->where('article_id', $request->article[$i])->first();

                $ligne = ArticleFacture::create([
                    'qte_cmd' => $request->qte_cmde[$i],
                    'article_id' => $request->article[$i],
                    'prix_unit' => $request->prix_unit[$i],
                    'unite_mesure_id' => $request->unite[$i],
                    'facture_id' => $facture->id,
                ]);

                DevisDetail::create([
                    'qte_cmde' => $request->qte_cmde[$i],
                    'article_id' => $request->article[$i],
                    'prix_unit' => $request->prix_unit[$i],
                    'unite_mesure_id' => $request->unite[$i],
                    'devis_id' => $request->devis_id,
                ]);

                // $devis->update([
                //     'qte_cmde' => $request->qte_cmde[$i],
                //     'prix_unit' => $request->prix_unit[$i]
                // ]);
            }

            DB::commit();
            return redirect()->route('factures.index')->with('success', 'Facture modifiée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('Erreur enregistrement de facture.' .$e->getMessage())->withInput();
        }
    }
}
