<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\CompteFrs;
use App\Models\Facture;
use App\Models\FactureFournisseur;
use App\Models\Fournisseur;
use App\Models\Reglement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ReglementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reglements = Reglement::with(['facture'])
            ->where('validated_at', null)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.reglements.index', compact('reglements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fournisseurs = Fournisseur::all();
        return view('pages.reglements.create', compact('fournisseurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     if($request->montant_restant >= $request->montant_regle ){
    //         $validator = Validator::make($request->all(), [
    //             'montant_regle' => 'required|string',
    //             'montant_restant' => 'required',
    //             'type_reglement' => 'required',
    //             'date_reglement' => 'required',
    //             'reference' => 'required',
    //             'preuve_decharge' => 'required_if:type_reglement,Décharge|mimes:pdf,png,jpg|max:1024'
    //         ]);

    //         if ($validator->fails()) {
    //             return redirect()->back()->withErrors($validator)->withInput();
    //         }

    //         $nbr = Reglement::max('id');
    //         $code = 'RG' . formaterCode($nbr + 1);

    //         $facture =  FactureFournisseur::find($request->facture_fournisseur_id);
    //         // dd($request->all());

    //         if ($request->hasFile('preuve_decharge')) {
    //             $fileName = time() . '_' . $request->preuve_decharge->getClientOriginalName();
    //             $request->file('preuve_decharge')->storeAs('public/uploads', $fileName);
    //         } else {
    //             $fileName = null;
    //         }
    //         $reglement = Reglement::create([
    //             'montant_regle' => $request->montant_regle,
    //             'code' => $code,
    //             'nature_compte_paiement' => $request->nature_compte_paiement ?? null,
    //             'type_reglement' => $request->type_reglement,
    //             'date_reglement' => $request->date_reglement,
    //             'reference' => $request->reference,
    //             'preuve_decharge' => $fileName,
    //             'facture_fournisseur_id' => $request->facture_fournisseur_id,
    //             'user_id' => Auth::id(),
    //         ]);



    //         return redirect()->route('reglements.index')
    //             ->with('success', 'Règlement ajouté avec succès.');
    //     }

    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'montant_regle' => 'required|numeric|min:0', // Assurez-vous que le montant réglé est numérique et positif
            'montant_restant' => 'required|numeric|min:0', // Assurez-vous que le montant restant est numérique et positif
            'type_reglement' => 'required',
            'date_reglement' => 'required|date',
            'reference' => 'required',
            'preuve_decharge' => 'required_if:type_reglement,Décharge|mimes:pdf,png,jpg|max:1024'
        ]);

        // Vérifier si la validation a échoué
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator) // Retourner les erreurs de validation à la vue
                ->withInput(); // Retourner les données entrées par l'utilisateur
        }

        // Vérifier si le montant restant est inférieur au montant réglé
        if ($request->montant_restant < $request->montant_regle) {
            return redirect()->back()
                ->withErrors('Le montant réglé ne peut pas être supérieur au montant restant à payer.')
                ->withInput();
        }

        // Générer le code de règlement
        $nbr = Reglement::max('id');
        $code = 'RG' . formaterCode($nbr + 1);

        // Traitement du fichier de preuve de décharge s'il est présent
        $fileName = null;
        if ($request->hasFile('preuve_decharge')) {
            $fileName = time() . '_' . $request->preuve_decharge->getClientOriginalName();
            $request->file('preuve_decharge')->storeAs('public/uploads', $fileName);
        }

        // Créer le règlement dans la base de données
        $reglement = Reglement::create([
            'montant_regle' => $request->montant_regle,
            'code' => $code,
            'nature_compte_paiement' => $request->nature_compte_paiement ?? null,
            'type_reglement' => $request->type_reglement,
            'date_reglement' => $request->date_reglement,
            'reference' => $request->reference,
            'preuve_decharge' => $fileName,
            'facture_fournisseur_id' => $request->facture_fournisseur_id,
            'user_id' => Auth::id(),
        ]);

        // Redirection avec un message de succès
        return redirect()->route('reglements.index')
            ->with('success', 'Règlement ajouté avec succès.');
    }


    public function edit($id)
    {
        $reglement = Reglement::findOrFail($id);
        $facture = FactureFournisseur::findOrFail($reglement->facture_fournisseur_id);
        $frs = Fournisseur::findOrFail($facture->fournisseur_id);
        $restant = doubleval($facture->montant_total) - doubleval($facture->montant_regler);

        // dd($reglement);
        return view('pages.reglements.edit', compact('reglement', 'frs', 'facture', 'restant'));
    }

    // public function update(Request $request, string $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'montant_regle' => 'required|string',
    //         'type_reglement' => 'required',
    //         'date_reglement' => 'required',
    //         'reference' => 'required',
    //         'preuve_decharge' => 'required_if:type_reglement,Décharge|mimes:pdf,png,jpg|max:1024'
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }
    //     $reglement = Reglement::find($id);

    //     $facture =  FactureFournisseur::find($reglement->facture_fournisseur_id);
    //     // dd($request->all());

    //     if ($request->hasFile('preuve_decharge')) {
    //         $fileName = time() . '_' . $request->preuve_decharge->getClientOriginalName();
    //         $request->file('preuve_decharge')->storeAs('public/uploads', $fileName);
    //     } else {
    //         $fileName = null;
    //     }

    //     $reglement->update([
    //         'montant_regle' => $request->montant_regle,
    //         'type_reglement' => $request->type_reglement,
    //         'date_reglement' => $request->date_reglement,
    //         'reference' => $request->reference,
    //         'preuve_decharge' => $fileName,
    //         'nature_compte_paiement' => $request->nature_compte_paiement ?? $reglement->nature_compte_paiement,
    //         // 'validator_id' => Auth::id(),
    //         // 'validated_at' => now(),
    //     ]);
    //     // $reglement->validator_id = Auth::id();
    //     // $reglement->validated_at = now();
    //     $reglement->save();

    //     $montant = $facture->montant_regle + (float)$request->montant_regle;
    //     if ($facture->montant_total == $montant) {
    //         $statut = 'Soldé';
    //     } else {
    //         $statut = 'Non soldé';
    //     }

    //     $facture->update([
    //         'montant_regle' => $montant,
    //         'statut' => $statut,
    //     ]);

    //     // $compte_frs = CompteFrs::create([
    //     //     'date_op' => $reglement->date_reglement,
    //     //     'montant_op' => $reglement->montant_regle,
    //     //     'facture_id' =>  $reglement->facture_fournisseur_id,
    //     //     'fournisseur_id' => $facture->fournisseur_id,
    //     //     'user_id'=> Auth::user()->id,
    //     //     'type_op' => 'REG',
    //     //     'cle' => $facture->id,
    //     // ]);

    //     return redirect()->route('reglements.index')
    //         ->with('success', 'Règlement modifié avec succès.');
    // }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'montant_regle' => 'required|numeric|min:0', // Assurez-vous que le montant réglé est numérique et positif
            'type_reglement' => 'required',
            'date_reglement' => 'required|date',
            'reference' => 'required',
            'preuve_decharge' => 'required_if:type_reglement,Décharge|mimes:pdf,png,jpg|max:1024'
        ]);

        // Vérifier si la validation a échoué
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Récupérer le règlement à mettre à jour
        $reglement = Reglement::findOrFail($id);

        // Récupérer la facture associée
        $facture = FactureFournisseur::findOrFail($reglement->facture_fournisseur_id);
        $restant = doubleval($facture->montant_total) - doubleval($facture->montant_regler);

        // Vérifier si le montant restant est inférieur au montant réglé

        if ($restant < doubleval($request->montant_regle)) {
            return redirect()->back()
                ->withErrors('Le montant réglé ne peut pas être supérieur au montant restant à payer.')
                ->withInput();
        }

        // Traitement du fichier de preuve de décharge s'il est présent
        $fileName = $reglement->preuve_decharge; // Conserver l'ancien fichier si aucun nouveau n'est fourni
        if ($request->hasFile('preuve_decharge')) {
            $fileName = time() . '_' . $request->preuve_decharge->getClientOriginalName();
            $request->file('preuve_decharge')->storeAs('public/uploads', $fileName);
        }

        // Mettre à jour le règlement dans la base de données
        $reglement->update([
            'montant_regle' => $request->montant_regle,
            'type_reglement' => $request->type_reglement,
            'date_reglement' => $request->date_reglement,
            'reference' => $request->reference,
            'preuve_decharge' => $fileName,
            'nature_compte_paiement' => $request->nature_compte_paiement ?? $reglement->nature_compte_paiement,
        ]);

        // Mettre à jour le montant réglé de la facture et le statut de la facture
        $montant = $facture->montant_regle + (float)$request->montant_regle;
        $statut = ($facture->montant_total == $montant) ? 'Soldé' : 'Non soldé';

        $facture->update([
            'montant_regle' => $montant,
            'statut' => $statut,
        ]);

        // Redirection avec un message de succès
        return redirect()->route('reglements.index')
            ->with('success', 'Règlement modifié avec succès.');
    }

    public function validerReglement($id)
    {
        // Trouver le règlement
        $reglement = Reglement::find($id);
        if (!$reglement) {
            return response()->json(['error' => 'Règlement non trouvé'], 404);
        }

        // Trouver la facture associée
        $facture = FactureFournisseur::find($reglement->facture_fournisseur_id);
        if (!$facture) {
            return response()->json(['error' => 'Facture non trouvée'], 404);
        }

        // Mettre à jour le règlement
        $reglement->validator_id = Auth::id();
        $reglement->validated_at = now();
        $reglement->save();

        // Créer l'entrée CompteFrs
        $compte_frs = CompteFrs::create([
            'date_op' => $reglement->date_reglement,
            'montant_op' => $reglement->montant_regle,
            'facture_id' => $reglement->facture_fournisseur_id,
            'fournisseur_id' => $facture->fournisseur_id,
            'user_id' => Auth::user()->id,
            'type_op' => 'REG',
            'cle' => $facture->id,
        ]);

        return response()->json(['redirectUrl' => route('reglements.index')]);
    }


    public function reglementParFrs($id)
    {
        $frs = Fournisseur::find($id);
        $compte = CompteFrs::where('fournisseur_id', $frs->id)
            ->orderBy('id', 'desc')
            ->get();

        $solde = 0;
        $factures = 0;
        $reglements = 0;

        foreach ($compte as $transaction) {
            if ($transaction->type_op == "FAC" || $transaction->type_op == "FAC_AC" || $transaction->type_op == "FAC_VP" || $transaction->type_op == "FAC_VC") {
                // Si c'est un règlement ou un acompte, on soustrait le montant
                $solde -= $transaction->montant_op;
                $factures += $transaction->montant_op;
            } else {
                // Pour tout autre type d'opération, on ajoute le montant
                $solde += $transaction->montant_op;
                $reglements += $transaction->montant_op;
            }
        }

        // dd($compte);
        // return view('pages.reglements-client.list-regl-clt', compact('client', 'compte', 'solde'));
        return view('pages.reglements.list-regl-frs', compact('frs', 'compte', 'solde', 'factures', 'reglements'));
    }

    public function detailsReglement($id)
    {
        // Récupérer la facture
        $reglementFrs = Reglement::find($id);

        // Vérifiez si la facture existe
        if (!$reglementFrs) {
            return response()->json(['error' => 'Règlement non trouvé.'], 404);
        }

        // Récupérer la facture associée au reglement
        $factureFrs = FactureFournisseur::find($reglementFrs->facture_fournisseur_id);

        // Vérifiez si la facture existe
        if (!$factureFrs) {
            return response()->json(['error' => 'Facture non trouvée.'], 404);
        }

        // Récupérer la commande associée à la facture
        $cmd = Commande::find($factureFrs->commande_id);

        // Vérifiez si la commande existe
        if (!$cmd) {
            return response()->json(['error' => 'Commande non trouvée.'], 404);
        }

        // Récupérer les lignes de commande
        $lignes = DB::table('ligne_commandes')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_commandes.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'ligne_commandes.article_id')
            ->where('ligne_commandes.commande_id', $cmd->id)
            ->select('ligne_commandes.*', 'unite_mesures.unite', 'articles.nom')
            ->get();

        // Retourner la vue partielle avec les données
        return view('pages.reglements.partials.details-reglement', compact('lignes', 'cmd', 'factureFrs', 'reglementFrs'));
    }

    public function detailValiderReglement($id)
    {
        // Récupérer la facture
        $reglementFrs = Reglement::find($id);

        // Vérifiez si la facture existe
        if (!$reglementFrs) {
            return response()->json(['error' => 'Règlement non trouvé.'], 404);
        }

        // Récupérer la facture associée au reglement
        $factureFrs = FactureFournisseur::find($reglementFrs->facture_fournisseur_id);

        // Vérifiez si la facture existe
        if (!$factureFrs) {
            return response()->json(['error' => 'Facture non trouvée.'], 404);
        }

        // Récupérer la commande associée à la facture
        $cmd = Commande::find($factureFrs->commande_id);

        // Vérifiez si la commande existe
        if (!$cmd) {
            return response()->json(['error' => 'Commande non trouvée.'], 404);
        }

        // Récupérer les lignes de commande
        $lignes = DB::table('ligne_commandes')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_commandes.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'ligne_commandes.article_id')
            ->where('ligne_commandes.commande_id', $cmd->id)
            ->select('ligne_commandes.*', 'unite_mesures.unite', 'articles.nom')
            ->get();

        // Retourner la vue partielle avec les données
        return view('pages.reglements.partials.valider-reglement', compact('lignes', 'cmd', 'factureFrs', 'reglementFrs'));
    }
}
