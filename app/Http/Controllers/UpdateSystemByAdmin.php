<?php

namespace App\Http\Controllers;

use App\Models\AcompteClient;
use App\Models\Article;
use App\Models\CompteClient;
use App\Models\ArticlePointVente;
use App\Models\CompteFrs;
use App\Models\Facture;
use App\Models\FactureAncienne;
use App\Models\FactureFournisseur;
use App\Models\Magasin;
use App\Models\PointVente;
use App\Models\Reglement;
use App\Models\ReglementClient;
use App\Models\StockMagasin;
use App\Models\UniteMesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UpdateSystemByAdmin extends Controller
{
    public function update_point_vente_article(Request $request){
        $articles = Article::all();
        $pointVenteIds = PointVente::pluck('id');

        foreach ($pointVenteIds as $pointVenteId) {
            foreach ($articles as $article) {
                $existingEntry = ArticlePointVente::where('article_id', $article->id)
                                                ->where('point_vente_id', $pointVenteId)
                                                ->first();

                if (!$existingEntry) {
                    $articlePointVente = new ArticlePointVente();
                    $articlePointVente->point_vente_id = $pointVenteId;
                    $articlePointVente->article_id = $article->id;
                    $articlePointVente->qte_stock = 0;
                    $articlePointVente->save();
                }
            }
        }

        return redirect()->route('roles.index')
                        ->with('success','Les articles ont été mis à jour sur les divers points de vente avec succès');
    }

    public function update_stock_on_point_vte(){

        $articles = Article::all();
        $pointVenteIds = PointVente::pluck('id');

        foreach ($articles as $article) {
            foreach ($pointVenteIds as $pointVenteId) {
                $magasinByPointVteIds = Magasin::where('point_vente_id', $pointVenteId)->pluck('id');

                $qte = StockMagasin::whereIn('magasin_id', $magasinByPointVteIds)
                               ->where('article_id', $article->id)
                               ->sum('qte_stock');

                ArticlePointVente::where('qte_stock', 0)
                ->where('article_id', $article->id)
                ->where('point_vente_id', $pointVenteId)
                ->update(['qte_stock' => $qte]);

            }
        }

        return redirect()->route('roles.index')
                        ->with('success','Les stocks ont été mis à jour sur les divers points de vente avec succès');

    }

    // public function updateUnitMesureIds(){
    //     Article::where('unite_mesure_id', 27)->update(['unite_mesure_id' => 9]);
    //     Article::where('unite_mesure_id', 28)->update(['unite_mesure_id' => 14]);
    //     Article::where('unite_mesure_id', 29)->update(['unite_mesure_id' => 15]);
    //     Article::where('unite_mesure_id', 30)->update(['unite_mesure_id' => 11]);
    //     Article::where('unite_mesure_id', 31)->update(['unite_mesure_id' => 10]);
    //     Article::where('unite_mesure_id', 32)->update(['unite_mesure_id' => 12]);
    //     Article::where('unite_mesure_id', 33)->update(['unite_mesure_id' => 4]);
    //     Article::where('unite_mesure_id', 34)->update(['unite_mesure_id' => 16]);
    //     Article::where('unite_mesure_id', 35)->update(['unite_mesure_id' => 19]);
    //     Article::where('unite_mesure_id', 36)->update(['unite_mesure_id' => 18]);
    //     Article::where('unite_mesure_id', 37)->update(['unite_mesure_id' => 1]);
    //     Article::where('unite_mesure_id', 38)->update(['unite_mesure_id' => 6]);
    //     Article::where('unite_mesure_id', 39)->update(['unite_mesure_id' => 23]);
    //     Article::where('unite_mesure_id', 40)->update(['unite_mesure_id' => 13]);
    //     Article::where('unite_mesure_id', 41)->update(['unite_mesure_id' => 20]);
    //     Article::where('unite_mesure_id', 42)->update(['unite_mesure_id' => 21]);
    //     Article::where('unite_mesure_id', 43)->update(['unite_mesure_id' => 24]);
    //     Article::where('unite_mesure_id', 44)->update(['unite_mesure_id' => 2]);
    //     Article::where('unite_mesure_id', 45)->update(['unite_mesure_id' => 22]);
    //     Article::where('unite_mesure_id', 46)->update(['unite_mesure_id' => 5]);
    // }

    public function updateUnitMesureIdsForTable($model){
        // $updates = [
        //     27 => 9, 28 => 14, 29 => 15, 30 => 11,
        //     31 => 10, 32 => 12, 33 => 4, 34 => 16,
        //     35 => 19, 36 => 18, 37 => 1, 38 => 6,
        //     39 => 23, 40 => 13, 41 => 20, 42 => 21,
        //     43 => 24, 44 => 2, 45 => 22, 46 => 5,
        // ];

        $updates = [
            1 => 13, 3 => 19, 4 => 12
        ];

        foreach ($updates as $oldId => $newId) {
            $model::where('unite_mesure_id', $oldId)->update(['unite_mesure_id' => $newId]);
        }
    }

    public function update_unite_mesure(){
        $tables = [
            \App\Models\Article::class,
            \App\Models\LigneBonCommande::class,
            \App\Models\LigneCommande::class,
            \App\Models\DevisDetail::class,
            \App\Models\LivraisonClient::class,
            \App\Models\LivraisonDirecte::class,
            \App\Models\Approvisionnement::class,
            \App\Models\LigneSupplementCommande::class,
            \App\Models\TauxConversion::class,
            // \App\Models\StockTransfert::class,
            \App\Models\VenteLigne::class,
            \App\Models\ArticleFacture::class,
            // \App\Models\SupplementCommande::class,
            // \App\Models\StockTransfert::class,
        ];

        foreach ($tables as $table) {
            $this->updateUnitMesureIdsForTable($table);
        }

        // UniteMesure::where('id', '>', 26)->delete();

        return redirect()->route('roles.index')
                        ->with('success','Les unités de mésure ont été mises à jour avec succès');

    }

    public function updateCompteClient(){

        // -------------------Règlement-------------------
        $reglements = ReglementClient::all();
        foreach($reglements AS $reglement){
            $cle = $reglement->id;
            $date_op = $reglement->date_reglement;
            $montant_op = $reglement->montant_regle;
            $type_op = 'REG';
            $facture_id = 15 ;
            // $facture_id = $reglement->facture_id ;
            $client_id  = $reglement->client_id ;
            $user_id   = $reglement->user_id;

            $compte_client = new CompteClient();
            $compte_client->date_op = $date_op;
            $compte_client->montant_op = $montant_op;
            $compte_client->type_op = $type_op;
            $compte_client->facture_id = $facture_id;
            $compte_client->client_id = $client_id;
            $compte_client->user_id = $user_id;
            $compte_client->cle = $cle;
            $compte_client->save();
        }

        // -------------------Accompte CLient-------------------
        $accomptes = AcompteClient::all();
        foreach($accomptes AS $accompte){
            $cle = $accompte->id;
            $date_op = $accompte->created_at;
            $montant_op = $accompte->montant_acompte;
            $type_op = 'ACC';
            $facture_id = 15 ;
            $client_id  = $accompte->client_id ;
            $user_id   = $accompte->user_id;

            $compte_client = new CompteClient();
            $compte_client->date_op = $date_op;
            $compte_client->montant_op = $montant_op;
            $compte_client->type_op = $type_op;
            $compte_client->facture_id = $facture_id;
            $compte_client->client_id = $client_id;
            $compte_client->user_id = $user_id;
            $compte_client->cle = $cle;
            $compte_client->save();
        }

        // -------------------Factures-------------------
        $factures = Facture::with('devis')->get();
        foreach($factures AS $facture){
            $date_op = $facture->date_facture;
            $montant_op = $facture->montant_facture;
            $type_op = 'FAC';
            $facture_id = $facture->id ;
            $client_id  = $facture->devis->client_id ;
            $user_id = $facture->user_id;

            $compte_client = new CompteClient();
            $compte_client->date_op = $date_op;
            $compte_client->montant_op = $montant_op;
            $compte_client->type_op = $type_op;
            $compte_client->facture_id = $facture_id;
            $compte_client->client_id = $client_id;
            $compte_client->user_id = Auth::user()->id;
            $compte_client->cle = $facture_id;
            $compte_client->save();
        }

        // -------------------Factures Anciennes-------------------
        $factures = FactureAncienne::all();
        foreach($factures AS $facture){
            $date_op = $facture->date_facture;
            $montant_op = $facture->montant_facture;
            $type_op = 'FAC_AC';
            $facture_id = $facture->id ;
            $client_id  = $facture->client_id ;
            $user_id = $facture->user_id;

            $compte_client = new CompteClient();
            $compte_client->date_op = $date_op;
            $compte_client->montant_op = $montant_op;
            $compte_client->type_op = $type_op;
            $compte_client->facture_id = $facture_id;
            $compte_client->client_id = $client_id;
            $compte_client->user_id = $user_id;
            $compte_client->cle = $facture_id;
            $compte_client->save();
        }

        return redirect()->route('roles.index')
                        ->with('success','Les comptes clients ont mis à jour avec succès');
    }

    public function updateCompteFrs(){

        // -------------------Règlement-------------------
    //     $reglements = Reglement::with('facture')
    // ->whereNotNull('validated_at')
    // ->get();
    $reglements = Reglement::join('facture_fournisseurs', 'reglements.facture_fournisseur_id', '=', 'facture_fournisseurs.id')
    ->whereNotNull('reglements.validated_at')
    ->whereNotNull('facture_fournisseurs.validated_at')
    ->select('reglements.*') // Sélectionnez les colonnes de la table reglements que vous souhaitez récupérer
    ->get();




        foreach($reglements AS $reglement){
            // print_r($reglement->facture);
            // echo '<br><br>'.$reglement->facture->fournisseur_id;
            $cle = $reglement->id;
            $date_op = $reglement->date_reglement;
            $montant_op = $reglement->montant_regle;
            $type_op = 'REG';
            $facture_id = $reglement->facture_fournisseur_id ;
            // $facture_id = $reglement->facture_id ;
            $frs_id  = $reglement->facture->fournisseur_id ;
            $user_id   = $reglement->user_id;

            $compte_frs = new CompteFrs();
            $compte_frs->date_op = $date_op;
            $compte_frs->montant_op = $montant_op;
            $compte_frs->type_op = $type_op;
            $compte_frs->facture_id = $facture_id;
            $compte_frs->fournisseur_id = $frs_id;
            $compte_frs->user_id = $user_id;
            $compte_frs->cle = $cle;
            $compte_frs->save();
        }

        // // -------------------Factures-------------------

        $factures = FactureFournisseur::whereNotNull('validated_at')->get();

        foreach($factures AS $facture){
            $date_op = $facture->date_facture;
            $montant_op = $facture->montant_total;
            $type_op = 'FAC';
            $facture_id = $facture->id ;
            $frs_id  = $facture->fournisseur_id ;
            $user_id = $facture->user_id;

            $compte_frs = new CompteFrs();
            $compte_frs->date_op = $date_op;
            $compte_frs->montant_op = $montant_op;
            $compte_frs->type_op = $type_op;
            $compte_frs->facture_id = $facture_id;
            $compte_frs->fournisseur_id = $frs_id;
            $compte_frs->user_id = $user_id;
            $compte_frs->cle = $cle;
            $compte_frs->save();
        }

        return redirect()->route('roles.index')
                        ->with('success','Les comptes fournisseurs ont mis à jour avec succès');
    }

}
