<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\Article;
use App\Models\BonCommande;
use App\Models\Commande;
use App\Models\CompteFrs;
use App\Models\FactureFournisseur;
use App\Models\FactureType;
use App\Models\Fournisseur;
use App\Models\LigneCommande;
use App\Models\UniteMesure;
use Carbon\Carbon;
use COM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        DB::statement("SET sql_mode = ''"); // Pour désactiver temporairement le mode strict qui impose l'inclusion de tous les select dans le group BY

        $commandes = DB::table('commandes')
            ->join('fournisseurs', 'fournisseurs.id', '=', 'commandes.fournisseur_id')
            ->join('bon_commandes', 'bon_commandes.id', '=', 'commandes.bon_commande_id')
            ->select(
                'commandes.*',
                'fournisseurs.name',
                'bon_commandes.id as bonId',
                'bon_commandes.reference as bonRef'
            )
            ->whereNull('validator_id')
            ->orderBy('commandes.created_at', 'desc')
            ->get();

        foreach ($commandes as $commande) {
            // Récupérer le montant total pour la commande en question
            $facture = DB::table('facture_fournisseurs')
                ->where('commande_id', $commande->id)
                ->first(); // Utilisation de first() pour récupérer le premier résultat trouvé

            if ($facture) {
                // Assigner le montant total à la propriété total_montant de l'objet $commande
                $commande->total_montant = $facture->montant_total;
            } else {
                // Si aucune facture n'est trouvée, assigner null ou une valeur par défaut
                $commande->total_montant = null;
            }
        }

        // return response()->json([
        //     'datas'  => $commandes
        // ]);

        // return view('pages.achats-module.commandes.index', compact('commandes'));
        return view('pages.achats-module.commandes.index', compact('commandes'));
    }

    public function listerValiderBon()
    {

        DB::statement("SET sql_mode = ''"); // Pour désactiver temporairement le mode strict qui impose l'inclusion de tous les select dans le group BY

        $commandes = DB::table('commandes')
            ->join('fournisseurs', 'fournisseurs.id', '=', 'commandes.fournisseur_id')
            ->join('bon_commandes', 'bon_commandes.id', '=', 'commandes.bon_commande_id')
            ->select(
                'commandes.*',
                'fournisseurs.name',
                'bon_commandes.id as bonId',
                'bon_commandes.reference as bonRef'
            )
            ->whereNotNull('validator_id')
            ->orderBy('commandes.created_at', 'desc')
            ->get();

        foreach ($commandes as $commande) {
            // Récupérer le montant total pour la commande en question
            $facture = DB::table('facture_fournisseurs')
                ->where('commande_id', $commande->id)
                ->first(); // Utilisation de first() pour récupérer le premier résultat trouvé

            if ($facture) {
                // Assigner le montant total à la propriété total_montant de l'objet $commande
                $commande->total_montant = $facture->montant_total;
            } else {
                // Si aucune facture n'est trouvée, assigner null ou une valeur par défaut
                $commande->total_montant = null;
            }
        }

        // return response()->json([
        //     'datas'  => $commandes
        // ]);

        // return view('pages.achats-module.commandes.index', compact('commandes'));
        return view('pages.achats-module.commandes.lister-valider', compact('commandes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fournisseurs = Fournisseur::all();
        $bons = BonCommande::where('statut', 'Valide')
            ->whereNotIn('id', function ($query) {
                $query->select('bon_commande_id')
                    ->from('commandes')
                    ->whereNotNull('validated_at');
            })->orderBy('created_at', 'desc')
            ->get();
        $types = FactureType::all();

        return view('pages.achats-module.commandes.create', compact('fournisseurs', 'bons', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_cmd' => 'required',
            'transport' => 'required',
            'charge_decharge' => 'required',
            'autre' => 'required',
            'bon_id' => 'required|exists:bon_commandes,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'type_id' => 'required|exists:facture_types,id',
            'montant_facture' => 'required',
            'montant_total' => 'required',
            'montant_regle' => 'required',
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'unites.*' => 'required',
            'prixUnits.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $nbr = Commande::max('id');
            $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));

            $commande = Commande::create([
                'date_cmd' => $request->date_cmd,
                'statut' => 'Lancée',
                'fournisseur_id' => $request->fournisseur_id,
                'bon_commande_id' => $request->bon_id,
                'transport' => $request->transport,
                'charge_decharge' => $request->charge_decharge,
                'autre' => $request->autre,
                // 'reference' => date('dmY') . '-' . $lettres . '-C' . ($nbr + 1),
                'reference' => 'KAD-' . 'C' . ($nbr + 1) . '-' . date('dmY') . '-' . $lettres,
                'user_id' => Auth::user()->id,
            ]);
            if ($request->montant_total == $request->montant_regle) {
                $statut = 'Soldé';
            } else {
                $statut = 'Non soldé';
            }

            $nbr = count(Commande::all());
            $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));
            $facture =  FactureFournisseur::create([
                'date_facture' => Carbon::now(),
                'statut' => $statut,
                'commande_id' => $commande->id,
                'fournisseur_id' => $request->fournisseur_id,
                'montant_facture' => $request->montant_facture,
                'montant_total' => $request->montant_total,
                'montant_regle' => $request->montant_regle,
                'taux_remise' => $request->taux_remise,
                'tva' => $request->tva,
                'aib' => $request->aib,
                'ref_facture' => 'KAD-' . 'COM' . ($nbr + 1) . '-' . date('dmY') . '-' . $lettres,
                // 'ref_facture' => 'F' . date('dmY') . ($nbr + 1),
                'user_id' => Auth::id(),
                'facture_type_id' => $request->type_id,
            ]);

            // $compte_frs = CompteFrs::create([
            //     'date_op' => $facture->date_facture,
            //     'montant_op' => $facture->montant_facture,
            //     'facture_id' =>  $facture->id,
            //     'fournisseur_id' => $facture->fournisseur_id,
            //     'user_id'=> $facture->user_id,
            //     'type_op' => 'FAC',
            //     'cle' => $facture->id,
            // ]);

            $count = count($request->qte_cdes);
            for ($i = 0; $i < $count; $i++) {
                $unite_id = UniteMesure::where('unite', $request->unites[$i])->first()->id;

                $ligne = LigneCommande::create([
                    'qte_cmde' => $request->qte_cdes[$i],
                    'quantity' => $request->qte_cdes[$i],
                    'article_id' => $request->articles[$i],
                    'prix_unit' => $request->prixUnits[$i],
                    'unite_mesure_id' => $unite_id,
                    'commande_id' => $commande->id,
                ]);
            }
            DB::commit();

            return redirect()->route('commandes.index')->with('success', 'commande enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('commandes.index')->with('error', 'Erreur enregistrement Bon de commande.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bon = Commande::find($id);
        $lignes =  DB::table('ligne_commandes')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_commandes.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'ligne_commandes.article_id')
            ->where('ligne_commandes.commande_id', $id)
            ->select('ligne_commandes.*', 'unite_mesures.unite', 'articles.nom')
            ->get();


        $facture = DB::table('facture_fournisseurs')
            ->where('commande_id', $id)
            ->first(); // Utilisation de first() pour récupérer le premier résultat trouvé


        if ($facture) {
            // Assigner le montant total à la propriété total_montant de l'objet $commande

            $total_ht = $facture->montant_facture;
            $tva = $facture->montant_total * ($facture->tva / 100);
            $aib = $facture->montant_total * ($facture->aib / 100);
            $total_ttc = $facture->montant_total;
        } else {
            // Si aucune facture n'est trouvée, assigner null ou une valeur par défaut
            $total_ht = null;
            $total_tva = null;
            $total_aib = null;
            $total_ttc = null;
        }

        // $total = $lignes->sum(function ($article) {
        //     return $article->prix_unit * $article->quantity;
        // });

        return view('pages.achats-module.commandes.show', compact('lignes', 'bon', 'total_ht', 'aib', 'tva', 'total_ttc'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Commande::find($id);
        $frs = $item->fournisseur->name;
        $facture = $item->facture;
        $lignes = DB::table('ligne_commandes')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_commandes.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'ligne_commandes.article_id')
            ->join('commandes', 'commandes.id', '=', 'ligne_commandes.commande_id')
            ->where('ligne_commandes.commande_id', $id)
            ->select(
                'ligne_commandes.*',
                'unite_mesures.unite',
                'articles.nom',
                'commandes.date_cmd',
                'articles.id as article_id'
            )
            ->get();
        $types = FactureType::all();


        return view('pages.achats-module.commandes.edit', compact('item', 'types', 'lignes', 'frs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'date_cmd' => 'required',
            // 'commande_id' => 'required',
            'fournisseur_id' => 'required',
            'type_id' => 'required|exists:facture_types,id',
            'montant_facture' => 'required',
            'montant_total' => 'required',
            'montant_regle' => 'required',
            'qte_cmde.*' => 'required',
            'article.*' => 'required',
            'unite.*' => 'required',
            'prix_unit.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // dd($request->all());
        $item = Commande::find($id);
        // $item->validator_id = Auth::id();
        // $item->validated_at = now();
        // $item->save();

        $facture = $item->facture;
        $facture->update([
            'fournisseur_id' => $item->fournisseur_id,
            'montant_facture' => $request->montant_facture,
            'montant_total' => $request->montant_total,
            'montant_regle' => $request->montant_regle,
            'taux_remise' => (float)$request->taux_remise,
            'tva' => (float)$request->tva,
            'aib' => (float)$request->aib,
            'facture_type_id' => $request->type_id,
        ]);

        $count = count($request->qte_cmde);
        for ($i = 0; $i < $count; $i++) {

            LigneCommande::updateOrCreate(
                [
                    'commande_id' => $id,
                    'article_id' => $request->article[$i],
                ],
                [
                    'qte_cmde' => $request->qte_cmde[$i],
                    'quantity' => $request->qte_cmde[$i],
                    'prix_unit' => $request->prix_unit[$i],
                    'unite_mesure_id' => $request->unite[$i],
                ]
            );
        }
        return redirect()->route('commandes.index')->with('success', 'Commande modifiée avec succès.');
    }


    public function articlesParFournisseur($id, $bonId)
    {
        $lignes = DB::table('ligne_commandes')
            ->join('commandes', 'commandes.id', '=', 'ligne_commandes.commande_id')
            ->where('commandes.bon_commande_id', $bonId)
            ->select('ligne_commandes.article_id')
            ->get()
            ->pluck('article_id');
        $articles = DB::table('articles')
            ->join('article_fournisseurs', 'article_fournisseurs.article_id', '=', 'articles.id')
            ->join('fournisseurs', 'fournisseurs.id', '=', 'article_fournisseurs.fournisseur_id')
            ->join('ligne_bon_commandes', function ($join) use ($bonId, $lignes) {
                $join->on('ligne_bon_commandes.article_id', '=', 'articles.id')
                    ->where('ligne_bon_commandes.bon_commande_id', '=', $bonId)
                    ->whereNotIn('ligne_bon_commandes.article_id', $lignes);
            })
            ->leftJoin('unite_mesures', 'ligne_bon_commandes.unite_mesure_id', '=', 'unite_mesures.id')
            ->where('fournisseurs.id', $id)
            ->select('articles.*', 'ligne_bon_commandes.qte_cmde', 'unite_mesures.unite')
            ->distinct()
            ->get();

        return response()->json([
            'articles' => $articles
        ]);
    }

    public function lignesCommande($id)
    {
        $articles = DB::table('ligne_commandes')
            ->join('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->join('fournisseurs', 'fournisseurs.id', '=', 'commandes.fournisseur_id')
            ->join('articles', 'articles.id', '=', 'ligne_commandes.article_id')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_commandes.unite_mesure_id')
            ->where('commande_id', $id)
            ->whereNotNull('commandes.validated_at')
            ->where('ligne_commandes.qte_cmde', '>', 0)
            ->select('ligne_commandes.*', 'articles.nom', 'unite_mesures.unite', 'fournisseurs.name as fournisseur', 'commandes.transport as transport', 'commandes.charge_decharge as charge_decharge', 'commandes.autre as autre')
            ->distinct()
            ->get();

        return response()->json([
            'articles'  => $articles
        ]);
    }

    public function detailsCommande($id)
    {

        $lignes = DB::table('ligne_commandes')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_commandes.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'ligne_commandes.article_id')
            ->join('commandes', 'commandes.id', '=', 'ligne_commandes.commande_id')
            ->where('ligne_commandes.commande_id', $id)
            ->select(
                'ligne_commandes.*',
                'unite_mesures.unite',
                'articles.nom',
                'commandes.date_cmd',
                'articles.id as article_id'
            )
            ->get();

        return response()->json([
            'lignes'  => $lignes
        ]);
    }

    public function valider($id)
    {
        $bon = Commande::find($id);
        $facture = $bon->facture;

        $bon->validator_id = Auth::user()->id;
        $bon->validated_at = now();
        $bon->save();

        $facture->validator_id = Auth::user()->id;
        $facture->validated_at = now();
        $facture->save();

        $compte_frs = CompteFrs::create([
            'date_op' => $facture->date_facture,
            'montant_op' => $facture->montant_facture,
            'facture_id' =>  $facture->id,
            'fournisseur_id' => $facture->fournisseur_id,
            'user_id' => $facture->user_id,
            'type_op' => 'FAC',
            'cle' => $facture->id,
        ]);


        return response()->json(['redirectUrl' => route('commandes.index')]);
    }

    public function destroy(string $id)
    {
        $cmd = Commande::find($id);
        $cmd->delete();
        return redirect()->route('commandes.index')->with('success', 'Commande supprimée avec succès.');
    }
}
