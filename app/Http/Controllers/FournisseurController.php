<?php

namespace App\Http\Controllers;

use App\Imports\FournisseurImport;
use App\Models\Article;
use App\Models\Commande;
use App\Models\FactureFournisseur;
use App\Models\Reglement;
use App\Models\CompteClient;
use App\Models\CompteFrs;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fournisseurs = Fournisseur::all();
        $total_restants = [];

        foreach ($fournisseurs as $fournisseur) {
            $id = $fournisseur->id;

            // Initialisation du total pour ce fournisseur
            $total_restant = 0;
            $facturesSimples = FactureFournisseur::with(['typeFacture'])->where("fournisseur_id", $id)
                ->whereNotNull('validated_at')
                ->whereHas('typeFacture', function ($query) {
                    $query->where('libelle', 'Simple'); // Remplacez 'nom' par le champ approprié
                })
                ->get();

            $facturesSimples__ = FactureFournisseur::with(['typeFacture'])->where("fournisseur_id", $id)
                ->whereNotNull('validated_at')
                ->whereHas('typeFacture', function ($query) {
                    $query->where('libelle', 'Simple'); // Remplacez 'nom' par le champ approprié
                })
                ->pluck('id');

            $totalReglements = Reglement::whereIn('facture_fournisseur_id', $facturesSimples__)
                ->sum('montant_regle');

            $total_du = $facturesSimples->sum('montant_total');
            // $total_solde = $facturesSimples->sum('montant_regle');
            $total_solde = $totalReglements;
            $total_restant =  $total_solde - $total_du;

            $facturesNormalises = FactureFournisseur::with(['typeFacture'])->where("fournisseur_id", $id)
                ->whereNotNull('validated_at')
                ->whereHas('typeFacture', function ($query) {
                    $query->where('libelle', 'Normalisée'); // Remplacez 'nom' par le champ approprié
                })
                ->get();

            $facturesNormalises__ = FactureFournisseur::with(['typeFacture'])->where("fournisseur_id", $id)
                ->whereNotNull('validated_at')
                ->whereHas('typeFacture', function ($query) {
                    $query->where('libelle', 'Normalisée'); // Remplacez 'nom' par le champ approprié
                })
                ->pluck('id');

            $totalReglements1 = Reglement::whereIn('facture_fournisseur_id', $facturesNormalises__)
                ->sum('montant_regle');

            $total_du1 = $facturesNormalises->sum('montant_total');
            // $total_solde1 = $facturesNormalises->sum('montant_regle');
            $total_solde1 = $totalReglements1;

            $total_restant1 = $total_solde1 - $total_du1;
            $total_restants[$id] = $total_restant1 + $total_restant;
        }

        return view('pages.achats-module.fournisseurs.index', compact('fournisseurs', 'total_restants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $articles = Article::all();
        return view('pages.achats-module.fournisseurs.create', compact('articles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            // 'email' => 'email|unique:fournisseurs,email',
            // 'phone' => 'unique:users,phone',
            // 'address' => 'string',
            'articles.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nbr = Fournisseur::max('id');
        $code = 'FR' . formaterCode($nbr + 1);
        $fournisseur = Fournisseur::create([
            'name' => $request->name,
            'code_frs' => $code,
            'email' => $request->email ?? null,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $fournisseur->articles()->attach($request->input('articles'));

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $i = 1;
        $array = [];
        $fournisseurs = Fournisseur::all();
        $fournisseur = Fournisseur::find($id);

        // $factures = FactureFournisseur::with(['typeFacture'])->where("fournisseur_id", $id)
        // ->whereNotNull('validated_at')->get();

        // $factures = FactureFournisseur::with(['typeFacture', 'reglements'])
        //     ->where('fournisseur_id', $id)
        //     ->whereNotNull('validated_at')
        //     ->get();

        $factures = FactureFournisseur::with(['typeFacture', 'reglements' => function ($query) {
            $query->whereNotNull('validated_at');
        }])
            ->where('fournisseur_id', $id)
            ->whereNotNull('validated_at')
            ->get();


        // $factures = FactureFournisseur::with(['typeFacture'])->where("fournisseur_id", $id)
        //     ->whereNotNull('validated_at')->get();

        $facturesSimples = FactureFournisseur::with(['typeFacture'])->where("fournisseur_id", $id)
            ->whereNotNull('validated_at')
            ->whereHas('typeFacture', function ($query) {
                $query->where('libelle', 'Simple'); // Remplacez 'nom' par le champ approprié
            })
            ->get();

        $facturesSimples__ = FactureFournisseur::with(['typeFacture'])->where("fournisseur_id", $id)
            ->whereNotNull('validated_at')
            ->whereHas('typeFacture', function ($query) {
                $query->where('libelle', 'Simple'); // Remplacez 'nom' par le champ approprié
            })
            ->pluck('id');

        $totalReglements = Reglement::whereIn('facture_fournisseur_id', $facturesSimples__)
            ->sum('montant_regle');

        $total_du = $facturesSimples->sum('montant_total');
        $total_solde = $totalReglements;
        $total_restant = $total_solde - $total_du;

        $facturesNormalises = FactureFournisseur::with(['typeFacture'])->where("fournisseur_id", $id)
            ->whereNotNull('validated_at')
            ->whereHas('typeFacture', function ($query) {
                $query->where('libelle', 'Normalisée'); // Remplacez 'nom' par le champ approprié
            })
            ->get();

        $facturesNormalises__ = FactureFournisseur::with(['typeFacture'])->where("fournisseur_id", $id)
            ->whereNotNull('validated_at')
            ->whereHas('typeFacture', function ($query) {
                $query->where('libelle', 'Normalisée'); // Remplacez 'nom' par le champ approprié
            })
            ->pluck('id');

        $totalReglements1 = Reglement::whereIn('facture_fournisseur_id', $facturesNormalises__)
            ->sum('montant_regle');

        $total_du1 = $facturesNormalises->sum('montant_total');
        // $total_solde1 = $facturesNormalises->sum('montant_regle');
        $total_solde1 = $totalReglements1;
        $total_restant1 = $total_solde1 - $total_du1;

        $solde = ($totalReglements1 + $totalReglements) - ($total_du + $total_du1);

        return view(
            'pages.achats-module.fournisseurs.show',
            compact(
                'i',
                'total_du',
                'fournisseur',
                'factures',
                'total_solde',
                'total_restant',
                'total_du1',
                'total_solde1',
                'total_restant1',
                'fournisseurs',
                'solde'

            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $fournisseur = Fournisseur::find($id);
        $articles = Article::all();
        // $fournisseur->with(['articles'])->get();
        return view('pages.achats-module.fournisseurs.edit', compact('fournisseur', 'articles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $fournisseur = Fournisseur::find($id);
        $fournisseur->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        $fournisseur->articles()->sync($request->input('articles'));

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur modifié avec succès.');
    }


    public function import_xls(Request $request)
    {
        $this->validate($request, [
            'upload_xls'  => 'required|mimes:xls,xlsx'
        ]);
        Excel::import(new FournisseurImport, $request->file('upload_xls'));


        return redirect()->route('fournisseurs.index')->with('status', 'Fournisseurs importés avec succès.');
    }

    public function listFrs()
    {
        $fournisseurs = DB::table('fournisseurs')
            ->join('commandes', 'commandes.fournisseur_id', '=', 'fournisseurs.id')
            ->join('ligne_commandes', 'commandes.id', '=', 'ligne_commandes.commande_id')
            ->select('fournisseurs.*')
            ->distinct()
            ->get();

        return response()->json([
            'fournisseurs'  => $fournisseurs
        ]);
    }


    public function frsListAjax(Request $request)
    {
        $fournisseurs = Fournisseur::where('name', 'LIKE', '%' . $request->input('term', '') . '%')
            ->get();

        return response()->json([
            'fournisseurs'   => $fournisseurs,
        ]);
    }

    // public function facturesParFrs($id)
    // {
    //     $facturesTous = FactureFournisseur::where('fournisseur_id', $id)->get();
    //     $factures = $facturesTous->filter(function (FactureFournisseur $facture) {
    //         return $facture->montant_total > $facture->montant_regle;
    //     });

    //     return response()->json([
    //         'factures'   => $factures->toArray()
    //     ]);
    // }

    public function facturesParFrs($id)
    {
        $facturesTous = FactureFournisseur::where('fournisseur_id', $id)->get();

        $factures = $facturesTous->filter(function (FactureFournisseur $facture) {
            $montantRegleValide = $facture->reglementsValides->sum('montant_regle');
            return $facture->montant_total > $montantRegleValide;
        })->map(function (FactureFournisseur $facture) {
            $montantRegleValide = $facture->reglementsValides->sum('montant_regle');
            return array_merge($facture->toArray(), ['montant_regle_valide' => $montantRegleValide]);
        });

        return response()->json([
            'factures' => $factures
        ]);
    }



    // public function restantParFacture($id)
    // {
    //     // Rechercher la facture par son ID
    //     $facture = FactureFournisseur::find($id);

    //     // Vérifier si la facture existe
    //     if (!$facture) {
    //         return response()->json(['error' => 'Facture introuvable.'], 404);
    //     }

    //     // Calculer le montant restant à payer
    //     $restant = $facture->montant_total - $facture->montant_regle;

    //     // Retourner le montant restant à payer sous forme de réponse JSON
    //     return response()->json([
    //         'restant' => $restant
    //     ]);
    // }

    public function restantParFacture($id)
    {
        // Rechercher la facture par son ID
        $facture = FactureFournisseur::find($id);

        // Vérifier si la facture existe
        if (!$facture) {
            return response()->json(['error' => 'Facture introuvable.'], 404);
        }

        // Calculer le montant réglé validé
        $montantRegleValide = $facture->reglementsValides->sum('montant_regle');

        // Calculer le montant restant à payer
        $restant = $facture->montant_total - $montantRegleValide;

        // Retourner le montant restant à payer sous forme de réponse JSON
        return response()->json([
            'restant' => $restant
        ]);
    }


    // public function facturesParFrs($id)
    // {
    //     // Récupérer toutes les factures du fournisseur spécifié
    //     $facturesTous = FactureFournisseur::where('fournisseur_id', $id)->get();

    //     // Filtrer les factures impayées (où montant_regle < montant_total)
    //     $facturesImpayees = $facturesTous->filter(function ($facture) {
    //         return $facture->montant_regle < $facture->montant_total;
    //     });

    //     // Calculer le montant restant à payer pour chaque facture impayée
    //     $restantsAPayer = $facturesImpayees->map(function ($facture) {
    //         return $facture->montant_total - $facture->montant_regle;
    //     });

    //     // Retourner la réponse JSON avec les factures et les montants restants à payer séparés
    //     return response()->json([
    //         'factures' => $facturesImpayees->toArray(),
    //         'restants_a_payer' => $restantsAPayer->toArray(),
    //     ]);
    // }



    // public function detailsFacture($id)
    // {
    //     $factureFrs = FactureFournisseur::find($id);
    //     $cmd = Commande::where('id', $factureFrs->commande_id)->first();
    //     $lignes =  DB::table('ligne_commandes')
    //         ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_commandes.unite_mesure_id')
    //         ->join('articles', 'articles.id', '=', 'ligne_commandes.article_id')
    //         ->where('ligne_commandes.commande_id', $cmd->id)
    //         ->select('ligne_commandes.*', 'unite_mesures.unite', 'articles.nom')
    //         ->get();
    //         dd($lignes);
    //     return view('pages.achats-module.fournisseurs.details-facture', compact('lignes', 'cmd', 'factureFrs'));
    // }


    //     public function detailsFacture($id)
    // {
    //     // Récupérer la facture
    //     $factureFrs = FactureFournisseur::find($id);

    //     // Vérifiez si la facture existe
    //     if (!$factureFrs) {
    //         return redirect()->back()->with('error', 'Facture non trouvée.');
    //     }

    //     // Récupérer la commande associée à la facture
    //     $cmd = Commande::find($factureFrs->commande_id);

    //     // Vérifiez si la commande existe
    //     if (!$cmd) {
    //         return redirect()->back()->with('error', 'Commande non trouvée.');
    //     }

    //     // Récupérer les lignes de commande
    //     $lignes = DB::table('ligne_commandes')

    //         ->where('ligne_commandes.commande_id', $cmd->id)
    //         ->select('ligne_commandes.*')
    //         ->get();

    //     // Débogage (vous pouvez commenter cette ligne après le débogage)


    //     // Retourner la vue avec les données
    //     return view('pages.achats-module.fournisseurs.details-facture', compact('lignes', 'cmd', 'factureFrs'));
    // }


    public function detailsFacture($id)
    {
        // Récupérer la facture
        $factureFrs = FactureFournisseur::find($id);

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
        return view('pages.achats-module.fournisseurs.partials.details-facture', compact('lignes', 'cmd', 'factureFrs'));
    }


    public function show_frs(Request $request)
    {
        return redirect()->route('fournisseurs.show', $request->id_frs);
    }
}
