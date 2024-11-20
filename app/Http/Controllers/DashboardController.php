<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\LivraisonDirecte;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $facturesDevis = Facture::all();
        $ventes = Vente::all();
        $factures_directes = LivraisonDirecte::all();
        $factures = $facturesDevis->concat($factures_directes)->concat($ventes);

        $montantTotalParMois = $factures->groupBy(function ($facture) {
            return $facture->updated_at->format('m-Y');
        })->map(function ($facturesParMois) {
            return $facturesParMois->sum('montant_total');
        });

        $chiffre_affaire_mensuel = [];
        foreach ($montantTotalParMois as $mois => $montantTotal) {
            array_push($chiffre_affaire_mensuel, ['mois' => $mois, 'montant' => $montantTotal]);
        }

        $collect = collect($chiffre_affaire_mensuel);
        // CA par article
        $chiffreAffairesParArticle = [];

        // $ventes = Vente::with('lignesVente')->get();
        // foreach ($ventes as $vente) {
        //     foreach ($vente->lignesVente as $venteLigne) {
        //         $articleId = $venteLigne->article_id;
        //         $prixUnitaire = $venteLigne->prix_unit;
        //         $quantiteVendue = $venteLigne->qte_cmde;
        //         $montantVente = $prixUnitaire * $quantiteVendue;

        //         if (array_key_exists($articleId, $chiffreAffairesParArticle)) {
        //             $chiffreAffairesParArticle[$articleId]['chiffre_affaires'] += $montantVente;
        //             $chiffreAffairesParArticle[$articleId]['nombre_ventes'] += 1;
        //         } else {
        //             $chiffreAffairesParArticle[$articleId] = [
        //                 'article_id' => $articleId,
        //                 'chiffre_affaires' => $montantVente,
        //                 'nombre_ventes' => 1,
        //             ];
        //         }
        //     }
        // }

        // $livraisonsDirectes = LivraisonDirecte::with('ligneCommande')->get();

        // foreach ($livraisonsDirectes as $livraisonDirecte) {
        //     $articleId = $livraisonDirecte->ligneCommande->article_id;
        //     $prixUnitaire = $livraisonDirecte->prix_unit;
        //     $quantiteLivre = $livraisonDirecte->qte_livre;
        //     $montantLivraison = $prixUnitaire * $quantiteLivre;

        //     if (array_key_exists($articleId, $chiffreAffairesParArticle)) {
        //         $chiffreAffairesParArticle[$articleId]['chiffre_affaires'] += $montantLivraison;
        //         $chiffreAffairesParArticle[$articleId]['nombre_livraisons'] += 1;
        //     } else {
        //         $chiffreAffairesParArticle[$articleId] = [
        //             'article_id' => $articleId,
        //             'chiffre_affaires' => $montantLivraison,
        //             'nombre_livraisons' => 1,
        //         ];
        //     }
        // }

        // $devis = Devis::with('details')->get();

        // foreach ($devis as $devi) {
        //     foreach ($devi->details as $devisLigne) {
        //         $articleId = $devisLigne->article_id;
        //         $prixUnitaire = $devisLigne->prix_unit;
        //         $quantiteCommandee = $devisLigne->qte_cmde;
        //         $montantDevis = $prixUnitaire * $quantiteCommandee;

        //         if (array_key_exists($articleId, $chiffreAffairesParArticle)) {
        //             $chiffreAffairesParArticle[$articleId]['chiffre_affaires'] += $montantDevis;
        //             $chiffreAffairesParArticle[$articleId]['nombre_devis'] += 1;
        //         } else {
        //             $chiffreAffairesParArticle[$articleId] = [
        //                 'article_id' => $articleId,
        //                 'chiffre_affaires' => $montantDevis,
        //                 'nombre_devis' => 1,
        //             ];
        //         }
        //     }
        // }

        // $chiffreAffairesParArticle = Arr::map($chiffreAffairesParArticle, function ($item) {
        //     $article = Article::find($item->article_id);

        //     $categorie = null;
        //     if ($article) {
        //         $categorie = Categorie::find($article->categorie_id);
        //     }

        //     $item->categorie = $categorie ? $categorie->nom : null;
        //     $item->nom_article = $article ? $article->nom : null;

        //     return $item;
        // });

        return view('pages.dashboard', compact('chiffreAffairesParArticle', 'collect', 'chiffre_affaire_mensuel'));
    }
}
