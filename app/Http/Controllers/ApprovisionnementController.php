<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\Approvisionnement;
use App\Models\Article;
use App\Models\ArticlePointVente;
use App\Models\Commande;
use App\Models\Vehicule;
use App\Models\Chauffeur;
use App\Models\Compte_pv;
use App\Models\Facture;
use App\Models\FactureType;
use App\Models\Fournisseur;
use App\Models\LigneCommande;
use App\Models\Livraison_pv;
use App\Models\Magasin;
use App\Models\StockMagasin;
use App\Models\TauxConversion;
use App\Models\UniteMesure;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sign;

class ApprovisionnementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $i = 1;

        $appros = DB::table('approvisionnements')
            ->join('ligne_commandes', 'ligne_commandes.id', '=', 'approvisionnements.ligne_commande_id')
            ->join('articles', 'ligne_commandes.article_id', '=', 'articles.id')
            ->leftJoin('magasins', 'magasins.id', '=', 'approvisionnements.magasin_id')
            ->leftJoin('unite_mesures', 'unite_mesures.id', '=', 'approvisionnements.unite_mesure_id')
            ->leftJoin('commandes', 'commandes.id', '=', 'ligne_commandes.commande_id')
            ->select(
                'approvisionnements.id',
                'articles.id as article_id',
                'articles.nom as article_nom',
                'magasins.nom as magasin',
                'unite_mesures.unite',
                'approvisionnements.qte_livre',
                'approvisionnements.magasin_id',
                'approvisionnements.validator_id',
                'approvisionnements.validated_at',
                'ligne_commandes.prix_unit',
                'approvisionnements.date_livraison',
                'commandes.reference as ref_commande'
            )
            ->whereNull('approvisionnements.validated_at')
            ->orderBy('approvisionnements.id', 'desc')
            ->get();
        // dd($appros);

        $magasins = Magasin::all();

        return view('pages.achats-module.approvisionnements.index', compact('appros', 'i', 'magasins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $commandes = Commande::with('fournisseur')->whereNotNull('validated_at')->whereNotIn('id', $lignes)->get();

        $chauffeurs = Chauffeur::all();
        $vehicules = Vehicule::all();
        // $lignes = LigneCommande::where('qte_cmde', '=', 0)->pluck('commande_id');
        // $commandes = Commande::with('fournisseur')
        //     ->whereNotNull('validated_at')
        //     ->whereNotIn('id', $lignes)
        //     ->orderBy('created_at', 'desc')
        //     ->get();


        // Sélectionner les IDs des commandes pour lesquelles au moins une partie de la quantité commandée reste à livrer
        // $lignesPartiellementLivrees = LigneCommande::select('commande_id')
        // ->groupBy('commande_id')
        // ->havingRaw('ligne_commandes.qte_cmde > (select sum(quantity) from ligne_commandes where commande_id = ligne_commandes.commande_id)')
        // ->pluck('commande_id');

        // $lignesPartiellementLivrees = LigneCommande::select('commande_id', 'qte_cmde')
        // ->groupBy('commande_id', 'qte_cmde')
        // ->havingRaw('qte_cmde > (
        //     select sum(quantity) from ligne_commandes
        //     where ligne_commandes.commande_id = LigneCommande.commande_id
        // )')
        // ->pluck('commande_id');


        // Sélectionner les commandes correspondantes en excluant celles dont toutes les parties ont été livrées

        $cmds = Commande::with('fournisseur')
            ->whereNotNull('validated_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $commandes = [];

        foreach ($cmds as $cmd) {
            $lignes = LigneCommande::where('commande_id', $cmd->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $toutesLivrees = true; // Signal pour vérifier si toutes les lignes de la commande ont été livrées

            foreach ($lignes as $ligne) {
                $totalLivré = Approvisionnement::where('ligne_commande_id', $ligne->id)
                    ->sum('qte_livre');
                $qteRestanteALivrer = $ligne->quantity - $totalLivré;

                if ($qteRestanteALivrer > 0) {
                    $toutesLivrees = false; // Au moins une ligne n'a pas été totalement livrée
                    break; // Pas besoin de vérifier les autres lignes si une n'a pas été livrée
                }
            }

            if (!$toutesLivrees) {
                // Si toutes les lignes de la commande ont été livrées, on l'ajoute au tableau final
                $commandes[] =
                    (object) [
                        'id' => $cmd->id,
                        'reference' => $cmd->reference,

                        // Ajoutez d'autres objets de commandes si nécessaire
                    ];
            }
        }


        //     $lignes = LigneCommande::join('Commandes', 'Commandes.id', '=', 'ligne_commandes.commande_id')
        // ->whereNotNull('Commandes.validated_at')
        // ->orderBy('Commandes.created_at', 'desc')
        // ->get();

        // $tab = [];

        // foreach ($lignes as $line) {

        //     $totalLivré = Approvisionnement::where('ligne_commande_id', $line->id)
        //     ->groupBy('ligne_commande_id')
        //     ->sum('qte_livre');

        //     // Calcul de la quantité restante à livrer
        //     $qteRestanteALivrer = $line->qte_cmde - $totalLivré;

        //     if ($qteRestanteALivrer > 0) {
        //         $tab[] = [
        //             'commande_id' => $line->id,
        //             'qte_restante' => $qteRestanteALivrer
        //         ];
        //     }
        // }

        // return response()->json([
        //     'tableau'  => $commandes
        // ]);

        $magasins = Magasin::all();

        $facturesNonSoldesClients = Facture::where('montant_total', '>', DB::raw('montant_regle'))->pluck('devis_id');
        $clients =  DB::table('clients')
            // ->whereNotIn('id', function ($query) use ($facturesNonSoldesClients) {
            //     $query->select('client_id')
            //         ->from('devis')
            //         ->whereColumn('devis.client_id', '=', 'clients.id')
            //         ->whereIn('id', $facturesNonSoldesClients);
            // })
            ->where('seuil', '>', 'credit_total')
            ->get();
        $types = FactureType::all();

        return view('pages.achats-module.approvisionnements.create', compact('commandes', 'magasins', 'clients', 'types', 'vehicules', 'chauffeurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'date_cmd' => 'required',
            'date_livraison' => 'required',
            'commande_id' => 'required',
            'chauffeur_id' => 'required',
            'vehicule_id' => 'required',
            'cout_revient' => 'required',
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'magasin_id'  => 'required',
            'prixUnits.*' => 'required',
            'unites.*' => 'required',
            'lignes.*' => 'required',
            'commandes.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // dd([$request->all()]);

        $magasin = Magasin::find($request->magasin_id);

        $point_vente = $magasin->pointVente->id;
        $count = count($request->qte_cdes);
        $nbr = Livraison_pv::max('id');
        $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));
        DB::beginTransaction();

        try {

            $liv_pv = Livraison_pv::create([
                'date_liv' => $request->date_livraison,
                'ref_liv' => 'KAD-'. 'LIVPV' . ($nbr + 1).'-'.date('dmY') . '-' . $lettres,
                'cout_revient' => $request->cout_revient,
                'user_id' => Auth::user()->id,
                'chauffeur_id' => $request->chauffeur_id,
                'vehicule_id' => $request->vehicule_id,
                'magasin_id' => $request->magasin_id,
            ]);

            for ($i = 0; $i < $count; $i++) {
                $ligne = LigneCommande::find($request->lignes[$i]);
                // $element = StockMagasin::where('article_id', $ligne->article_id)->where('magasin_id', $magasin->id)->first();

                $unite = UniteMesure::where('unite', $request->unites[$i])->first();
                $article = Article::find($ligne->article_id);

                $conversionItem = $article->getPivotValueForUnite($unite->id);
                if (!is_null($conversionItem)) {
                    $tauxConversion = $conversionItem;
                    if ($tauxConversion < 1){
                        $qte_vrai = $request->qte_cdes[$i] / $tauxConversion;
                    }else{
                        $qte_vrai = $request->qte_cdes[$i] * $tauxConversion;
                    }
                } else {
                    return redirect()->route('livraisons.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
                }
                // dd($request->chauffeur_id);
                $appro = Approvisionnement::create([
                    'date_livraison' => $request->date_livraison,
                    'qte_livre' => $request->qte_cdes[$i],
                    'ligne_commande_id' => $request->lignes[$i],
                    'unite_mesure_id' => $unite->id,
                    'magasin_id' => $request->magasin_id,
                    'user_id' => Auth::user()->id,
                    'chauffeur_id' => $request->chauffeur_id,
                    'vehicule_id' => $request->vehicule_id,
                    'livraison_pv_id' => $liv_pv->id,
                    'commande_id' => $request->commandes[$i],
                ]);

            }

            $compte_pv = Compte_pv::create([
                'date_op' => $request->date_livraison,
                'montant_op' => $request->cout_revient,
                'user_id' => Auth::user()->id,
                'point_vente_id' => $point_vente,
                'livraison_pv_id' => $liv_pv->id,
                'facture_id' => 0,
                'cle' => $liv_pv->id,
                'type_op' => 'APPRO',
            ]);

            DB::commit();
            return redirect()->route('livraisons.index')->with('success', 'Livraison enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('livraisons.index')->withErrors(['message' => 'Erreur enregistrement livraison magasin'.$e]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'qte_livre' => 'required',
            'magasin_id'  => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // dd($request->all());
        $appro = Approvisionnement::find($id);

        DB::beginTransaction();

        try {
            $magasin = Magasin::find($request->magasin_id);

            $ligne = LigneCommande::find($appro->ligne_commande_id);
            $element = StockMagasin::where('article_id', $ligne->article_id)->where('magasin_id', $request->magasin_id)->first();
            $unite = UniteMesure::find($appro->unite_mesure_id);
            $article = Article::find($ligne->article_id);

            // if ((float)$request->qte_livre > (float)$ligne->qte_cmde) {
            if (bccomp($request->qteLivre, $ligne->qteCmde, 2) === 1) {
                return redirect()->route('livraisons.index')->with('error', 'La quantité restante est inférieure à la quantité à livrer');
            }

            $conversionItem = $article->getPivotValueForUnite($unite->id);
            if (!is_null($conversionItem)) {
                $tauxConversion = $conversionItem;
                if ($tauxConversion < 1){
                    $qte_vrai = $request->qte_livre / $tauxConversion;
                }else{
                    $qte_vrai = $request->qte_livre * $tauxConversion;
                }
            } else {
                return redirect()->route('livraisons.index')->withErrors(['message' => 'Vous n\'avez pas configuré de taux de conversion pour cette unité d\'article.']);
            }

            $point_vente = $magasin->pointVente;
            $stock_point = ArticlePointVente::where('article_id', $ligne->article_id)->where('point_vente_id', $point_vente->id)->first();

            // dd($point_vente, $stock_point, $element, $ligne);
            if (is_null($stock_point)) {
                $article = ArticlePointVente::create([
                    'point_vente_id' => $point_vente->id,
                    'article_id' => $ligne->article_id,
                    'qte_stock' => $qte_vrai
                ]);
            } else {
                $qte_stockNew = $stock_point->qte_stock + (float)$qte_vrai;
                $stock_point->update(['qte_stock' => $qte_stockNew]);
            }

            if (is_null($element)) {
                $article = StockMagasin::create([
                    'magasin_id' => $magasin->id,
                    'article_id' => $ligne->article_id,
                    'qte_stock' => $qte_vrai
                ]);

                $qte_stockNew = $article->qte_stock + $qte_vrai;
            } else {
                $qte_stockNew = $element->qte_stock + (float)$qte_vrai;
                $element->update(['qte_stock' => $qte_stockNew]);
            }

            $appro->update([
                'qte_livre' => $request->qte_livre,
                'magasin_id' => $request->magasin_id,
                'validator_id' => Auth::id(),
                'validated_at' => now(),
            ]);

            $ligne->update([
                'qte_cmde' => $ligne->qte_cmde - (float)$request->qte_livre
            ]);

            DB::commit();
            return redirect()->route('livraisons.index')->with('success', 'Livraison enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('livraisons.index')->withErrors(['message' => 'Erreur enregistrement livraison magasin']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        Approvisionnement::find($id)->delete();

        return redirect()->route('livraisons.index');
    }
}
