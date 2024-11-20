<?php

namespace App\Http\Controllers;

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


class AnnulationLivraisonFournisseurController extends Controller
{
    public function index()
    {
        $livraisons = Livraison_pv::join('chauffeurs', 'livraison_pvs.chauffeur_id', '=', 'chauffeurs.id')
        ->join('vehicules', 'livraison_pvs.vehicule_id', '=', 'vehicules.id')
        ->orderBy('livraison_pvs.created_at', 'desc') // Spécifier la table pour éviter les ambiguïtés
        ->select('livraison_pvs.*', 'livraison_pvs.id as cle', 'chauffeurs.*', 'vehicules.*') // Sélectionner les colonnes nécessaires
        ->get();


        //  return response()->json([
        //         'tableau'  => $commandes
        //     ]);

        // return view('pages.achats-module.annulation-approvisionnements.index', compact('appros', 'i', 'magasins'));

        return view('pages.achats-module.annulation-approvisionnements.index', compact('livraisons'));
}

public function show(int $id)
{

    $liv = Livraison_pv::join('chauffeurs', 'livraison_pvs.chauffeur_id', '=', 'chauffeurs.id')
    ->join('vehicules', 'livraison_pvs.vehicule_id', '=', 'vehicules.id')
    ->where('livraison_pvs.id', $id) // Utiliser le nom de table correct et ajouter le préfixe de table
    ->select('livraison_pvs.*', 'chauffeurs.*', 'vehicules.*') // Correction ici
    ->get();


    $liste_appro = Approvisionnement::join('commandes', 'approvisionnements.commande_id', '=', 'commandes.id')
    ->join('ligne_commandes', 'approvisionnements.ligne_commande_id', '=', 'ligne_commandes.id')
    ->join('articles', 'ligne_commandes.article_id', '=', 'articles.id')
    ->join('unite_mesures', 'approvisionnements.unite_mesure_id', '=', 'unite_mesures.id')
    ->where('livraison_pv_id', $id)
    ->whereNotNull('approvisionnements.validated_at')
->select('approvisionnements.*', 'approvisionnements.created_at as liv_at', 'articles.*', 'unite_mesures.*','commandes.reference as ref_cmd')
    ->orderBy('approvisionnements.created_at', 'desc') // Spécifier la table pour éviter les ambiguïtés
    ->get();



    //  return response()->json([
    //         'tableau'  => $liv
    //     ]);
    return view('pages.achats-module.annulation-approvisionnements.show', compact('liv', 'liste_appro'));
}
}
