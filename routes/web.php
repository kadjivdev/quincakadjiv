<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\AnnulationLivraisonFournisseurController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BonCommandeController;
use App\Http\Controllers\BonLivraisonController;
use App\Http\Controllers\BonVenteController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ChaufeurController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\FactureAncienneController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\InventaireBulkController;
use App\Http\Controllers\InventaireController;
use App\Http\Controllers\LivraisonClientController;
use App\Http\Controllers\LivraisonDirecteController;
use App\Http\Controllers\LivraisonSupplementController;
use App\Http\Controllers\LivraisonVenteComptController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MagasinController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PoinVenteController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\ReglementClientController;
use App\Http\Controllers\ReglementController;
use App\Http\Controllers\RequeteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplementController;
use App\Http\Controllers\TauxConversionController;
use App\Http\Controllers\TransfertStockController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\UniteMesureController;
use App\Http\Controllers\UpdateSystemByAdmin;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiculeController;
use App\Http\Controllers\VenteController;
use App\Livewire\BonCommande;
use App\Livewire\BonCommandeLivewire;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/login', [LoginController::class, 'showLogin'])->name('login-form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('fournisseurs', FournisseurController::class);

    Route::resource('categories', CategorieController::class);
    Route::patch('/categories/{id}/update', [CategorieController::class, 'update'])->name('categories.update');
    Route::put('/categories/{id}', [CategorieController::class, 'destroy'])->name('categories.destroy');
    Route::get('/categories/{id}/retrieve', [CategorieController::class, 'retrieve'])->name('categories.retrieve');

    Route::resource('articles', ArticleController::class);
    Route::resource('bon-commandes', BonCommandeController::class);
    Route::resource('commandes', CommandeController::class);
    Route::resource('magasins', MagasinController::class);
    Route::resource('boutiques', PoinVenteController::class);
    Route::resource('livraisons', ApprovisionnementController::class);
    Route::resource('annulation-approvisionnement', AnnulationLivraisonFournisseurController::class);
    Route::resource('supplements', SupplementController::class);
    Route::resource('tauxSupplements', TauxConversionController::class);
    Route::resource('approsSuppl', LivraisonSupplementController::class);
    Route::resource('unites', UniteMesureController::class)->only(['create', 'store', 'index']);

    // vente module routes
    Route::resource('deliveries', LivraisonClientController::class);
    Route::resource('devis', DevisController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('factures', FactureController::class);
    Route::resource('ventes', VenteController::class);
    Route::resource('livraisonsDirectes', LivraisonDirecteController::class);
    Route::resource('reglements', ReglementController::class);
    Route::resource('reglements-clt', ReglementClientController::class);
    Route::resource('factures-anciennes', FactureAncienneController::class);
    Route::resource('chauffeurs', ChaufeurController::class);
    Route::resource('vehicules', VehiculeController::class);
    Route::resource('transferts', TransfertStockController::class);
    Route::resource('inventaires', InventaireController::class);
    Route::resource('inventaires-bulk', InventaireBulkController::class);
    Route::resource('bons-ventes', LivraisonVenteComptController::class);
    Route::resource('agents', AgentController::class);

    Route::put('reglement/validate/{id}', [ReglementController::class, 'validateReg'])->name('validate-reg');
    Route::delete('reglement/delete/{id}', [ReglementController::class, 'deleteReg'])->name('delete-reg');

    Route::post('/valider/{id}', [LivraisonClientController::class, 'validation'])->name('validation-liv-clt');
    Route::post('/rejeter/{id}', [LivraisonClientController::class, 'rejeter'])->name('rejeter-liv-clt');
    Route::delete('/delete-liv/{id}', [LivraisonClientController::class, 'supprimerLivraison'])->name('delete-liv');


    Route::post('/valider-vc/{id}', [LivraisonVenteComptController::class, 'validation'])->name('validation-liv-clt-vc');
    Route::post('/rejeter-vc/{id}', [LivraisonVenteComptController::class, 'rejeter'])->name('rejeter-liv-clt-vc');
    Route::delete('/delete-liv-vc/{id}', [LivraisonVenteComptController::class, 'supprimerLivraison'])->name('delete-liv-vc');



    Route::get('/inventaire-create/{id}', [InventaireController::class, 'create'])->name('inventaire-create');
    Route::get('/inventaire-multiple/{id}', [InventaireController::class, 'createMultiple'])->name('inventaire-multiple');
    Route::post('/inventaire-multiple-store', [InventaireController::class, 'storeMultipleBack'])->name('inventaire-multiple-store');

    Route::get('/inventaire-bulk/{id}', [InventaireBulkController::class, 'create'])->name('inventaire-bulk');
    Route::get('/valider-inventaire/{id}', [InventaireController::class, 'valider'])->name('valider-inventaire');
    Route::get('/magasin-inventaires/{id}', [InventaireController::class, 'indexByMag'])->name('magasin-inventaires');
    Route::get('/valider-livraison-vente/{id}', [MagasinController::class, 'valider'])->name('valider-livraison-vente');
    Route::get('/livraison-vente', [MagasinController::class, 'livraisonVenteView'])->name('livraison-vente-view');
    Route::post('/livraison-vente-store', [MagasinController::class, 'validerVente'])->name('livraison-vente-store');

    Route::get('/details-factureFrs/{id}', [FournisseurController::class, 'detailsFacture'])->name('details-factureFrs');
    Route::get('/details-facture/{id}', [FournisseurController::class, 'detailsFacture']);

    Route::get('/valider/{id}', [BonCommandeController::class, 'valider'])->name('valider');
    Route::get('/cancel-valider/{id}', [BonCommandeController::class, 'cancelValider'])->name('cancel-valider');
    Route::get('/liste-valider', [BonCommandeController::class, 'listeValider'])->name('liste-valider');


    Route::get('/lister-valider-bon', [CommandeController::class, 'listerValiderBon'])->name('lister-valider-bon');
    Route::get('/valider-bon/{id}', [CommandeController::class, 'valider'])->name('valider-bon');
    Route::put('/validerLivraison/{id}', [LivraisonDirecteController::class, 'update'])->name('validerLivraison');
    Route::get('/taux-par-defaut', [TauxConversionController::class, 'uniteParDefaut'])->name('uniteParDefaut');
    Route::get('/tauxConvert', [TauxConversionController::class, 'index'])->name('liste_taux_convert');
    // Route::get('/detail-sup/{id}', [SupplementController::class, 'show'])->name('detail-sup');
    Route::post('/add-acompte', [FactureAncienneController::class, 'storeAcompte'])->name('acompte.store');

    // routes for pdf
    Route::get('/devis-pdf/{id}', [DevisController::class, 'pdf'])->name('devis-pdf');
    Route::get('/facture-pdf/{id}', [FactureController::class, 'pdf'])->name('facture-pdf');

    Route::post('/chauffeur-import', [ChaufeurController::class, 'import_xls'])->name('chauffeur-import');
    Route::post('/client-import', [ClientController::class, 'import_xls'])->name('client-import');
    Route::post('/compte-client-import', [ClientController::class, 'import_ran_client_xls'])->name('compte-client-import');
    Route::post('/frs-import', [FournisseurController::class, 'import_xls'])->name('frs-import');
    Route::post('/article-import', [ArticleController::class, 'import_xls'])->name('article-import');
    Route::post('/prix-import', [ArticleController::class, 'import_prix'])->name('prix-import');
    Route::post('/categorie-import', [CategorieController::class, 'import_xls'])->name('categorie-import');
    Route::post('/report-nouveau', [ClientController::class, 'reportNouveau'])->name('report-nouveau');
    Route::post('/stock-import', [PoinVenteController::class, 'import_stock'])->name('stock-import');
    Route::post('/acompte', [FactureAncienneController::class, 'storeAcompte'])->name('acompte-store');
    Route::get('/accompte', [FactureAncienneController::class, 'createAccompte'])->name('acompte-create');
    Route::get('/list-accomptes', [FactureAncienneController::class, 'indexAccompte'])->name('acompte-index');

    Route::post('/article-stock', [ArticleController::class, 'addStock'])->name('article-stock');
    Route::post('/prix-store', [PoinVenteController::class, 'storePrix'])->name('prix-store');
    Route::post('/taux-store', [ArticleController::class, 'storeTaux'])->name('taux-store');
    Route::post('/supplement-store', [SupplementController::class, 'store'])->name('supplement-store');
    Route::get('/supplement-cmd/{id}', [SupplementController::class, 'create'])->name('supplement-create');
    Route::get('/reglement-frs/{id}', [ReglementController::class, 'reglementParFrs'])->name('reglements-frs');
    Route::put('/valider-reglement-frs/{id}', [ReglementController::class, 'validated_reg_frs'])->name('validated_reg_frs');
    Route::get('/acompte-clt/{id}', [ReglementClientController::class, 'getAccompteByClient'])->name('acomptes-clt');
    Route::get('/acompte-clt/validate/{id}', [FactureAncienneController::class, 'validateAcompte'])->name('validate-accompte');
    Route::delete('/acompte-clt/delete/{id}', [FactureAncienneController::class, 'deleteAccompte'])->name('delete-accompte');
    Route::get('/acompte-clt/update/{id}', [FactureAncienneController::class, 'updateAccompte'])->name('update-accompte');
    Route::put('/acompte/update/save', [FactureAncienneController::class, 'saveUpdateAcompte'])->name('acompte-save-update');

    Route::get('/reglement-clt/{id}', [ReglementClientController::class, 'reglementParClt'])->name('reglements-clt');
    Route::get('/real-reglement-clt/{id}', [ReglementClientController::class, 'regByCltNotHisto'])->name('real-reglements-clt');
    Route::delete('/reglement-clt/del/{id}', [ReglementClientController::class, 'delReg'])->name('reglement-del-clt');
    Route::get('/reglement-clt/validate/{id}', [ReglementClientController::class, 'validateReg'])->name('reglement-clt-validate');
    Route::get('/articlesFrs/{id}', [FournisseurController::class, 'articlesParFrs'])->name('articlesParFrs');
    Route::get('/reglement-clt-to-valid', [ReglementClientController::class, 'regByCltToValid'])->name('reglements-clt-to-valid');

    // Ajax routes

    Route::get('/articles-supl/{id}', [SupplementController::class, 'lignesSup']);
    Route::get('/articles-frs/{id}/{bonId}', [CommandeController::class, 'articlesParFournisseur'])->name('articles-frs');
    Route::get('/unites-list', [BonCommandeController::class, 'listUnites'])->name('unites-list');
    Route::get('/articles-list', [ArticleController::class, 'listArticles'])->name('articles-list');
    Route::get('/usersList', [UserController::class, 'listUsers'])->name('listUsers');
    Route::get('/lignesCommande/{id}', [CommandeController::class, 'lignesCommande'])->name('lignesCommande');
    Route::get('/points-list', [PoinVenteController::class, 'listPoints'])->name('points-list');
    Route::get('/articlesCommande/{id}', [SupplementController::class, 'articlesCommande'])->name('articlesCommande');
    Route::get('/lignesDevis/{id}', [DevisController::class, 'lignesDevis'])->name('lignesDevis');
    Route::get('/articles-point', [DevisController::class, 'listArticlesPoint'])->name('listArticlesPoint');
    Route::get('/lignesBonventes/{id}', [BonVenteController::class, 'detailsBon'])->name('lignesBonventes');
    Route::get('/magasins-list', [MagasinController::class, 'listMagasins'])->name('magasins-list');
    Route::get('/details-reglement/{id}', [ReglementController::class, 'detailsReglement']);
    Route::get('/valider-reglement/{id}', [ReglementController::class, 'detailValiderReglement']);
    // Route::post('/valider-post-reglement/{id}', [ReglementController::class, 'validerReglement']);
    Route::post('/valider-post-reglement/{id}', [ReglementController::class, 'validerReglement'])->name('valider.post.reglement');


    Route::get('/getUnitesByArticle', [UniteMesureController::class, 'getUnitesByArticle'])->name('unite-list-by-cat');

    Route::get('/articlesFacture/{id}', [FactureController::class, 'articlesFacture']);
    Route::get('/getUnitesByArticle/{id}', [TauxConversionController::class, 'getUnitesByArticleId']);
    Route::get('/convertirUnite/{article}/{unite}/{qte}', [TauxConversionController::class, 'convertirUniteVenteEnUniteStock']);
    Route::get('/frsListAjax', [FournisseurController::class, 'frsListAjax']);
    Route::get('/facturesFrs/{id}', [FournisseurController::class, 'facturesParFrs']);
    Route::get('/restantFacturesFrs/{id}', [FournisseurController::class, 'restantParFacture']);
    Route::get('/cltListAjax', [ClientController::class, 'cltListAjax']);
    Route::get('/allClients', [ClientController::class, 'allClients']);
    Route::get('/facturesClt/{id}', [ClientController::class, 'facturesParClt']);
    Route::get('/chaufListAjax', [ChaufeurController::class, 'chaufListAjax']);
    Route::get('/pointUsers/{id}', [UserController::class, 'usersByPoint']);

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/generate-proforma/{id}', [ProformaController::class, 'generatePDF']);
    Route::get('/generate_bon_cde/{id}', [BonCommandeController::class, 'generatePDF']);
    Route::get('/generate_bordereau_lvr/{id}', [BonLivraisonController::class, 'generatePDF']);

    Route::get('/rapport_reglement_frs', [RapportController::class, 'rapport_reglement_frs'])->name('rap_reg_frs');
    Route::get('/rapport_reglement_clt', [RapportController::class, 'rapport_reglement_clt'])->name('rap_reg_clt');
    Route::get('/rapport_livraison_frs', [RapportController::class, 'rapport_livraison_frs'])->name('rap_liv_frs');
    Route::get('/rapport_livraison_frs_detail/{liv}', [RapportController::class, 'rapport_livraison_frs_detail'])->name('rap_liv_frs_detail');
    Route::get('/rapport_factures_ventes', [RapportController::class, 'ventes'])->name('rap_fact_vte');
    Route::get('/rapport_fact_frs', [RapportController::class, 'facturesFrs'])->name('rap_fact_frs');
    Route::get('/rapport_fact_vte_clt', [RapportController::class, 'facturesVteClt'])->name('rap_fact_vte_clt');
    Route::get('/rapport_fact_clt_non_reg', [RapportController::class, 'facturesCltSansReglemt'])->name('facturesCltSansReglemt');

    Route::get('/rapport_vente_journaliere', [RapportController::class, 'rapport_vente_journaliere'])->name('rapport_vente_journaliere');
    // Route::get('/rapport_vente_journaliere_detail', [RapportController::class, 'rapport_vente_journaliere_detail'])->name('rapport_vente_journaliere_detail');

    Route::get('/rapport_vente_journaliere_detail/{facture}/{vente_id}', [RapportController::class, 'rapport_vente_journaliere_detail'])
    ->name('rapport_vente_journaliere_detail');

    Route::post('/article_by_id', [ArticleController::class, 'check_article']);
    Route::get('/articless', [ArticleController::class, 'index'])->name('index_articles');
    Route::get('/all-articles', [ArticleController::class, 'allArticles'])->name('articles.all_articles');

    // UPDATE ADMIN
    Route::put('/update_art_point_vte', [UpdateSystemByAdmin::class, 'update_point_vente_article'])->name('update_art_point_vte');
    Route::put('/update_stock_point_vte', [UpdateSystemByAdmin::class, 'update_stock_on_point_vte'])->name('update_stk_point_vte');
    Route::put('/update_unite_mesure', [UpdateSystemByAdmin::class, 'update_unite_mesure'])->name('update_unite_mesure');
    Route::put('/update_compte_client', [UpdateSystemByAdmin::class, 'updateCompteClient'])->name('update_cpt_clt');
    Route::put('/update_compte_frs', [UpdateSystemByAdmin::class, 'updateCompteFrs'])->name('update_cpt_frs');


    Route::post('/show_frs', [FournisseurController::class, 'show_frs']);
    Route::post('/updateMasse', [TauxConversionController::class, 'updateMasse'])->name('TauxSupplementMassUpdate');
    Route::post('/updateBaseMasse', [TauxConversionController::class, 'UniteBaseMassUpdate'])->name('UniteBaseMassUpdate');

    Route::get('/unite_base', [TauxConversionController::class, 'unite_base'])->name('UniteBase');
    Route::post('/valider_facture/{id}', [FactureController::class, 'validate_fact'])->name('validate_facture');

    Route::delete('delete-vente/{id}', [VenteController::class, 'deleteVente'])->name('vente-del');
    Route::get('validate-vente/{id}', [VenteController::class, 'validateVente'])->name('vente-validate');
    Route::get('/vente-caisse', [VenteController::class, 'show_for_caisse'])->name('vente-caisse');
    Route::get('/vente-caisse/{id}', [VenteController::class, 'encaisser'])->name('ventes-encaisser');
    Route::get('/rapport-caisse', [VenteController::class, 'rapport_caisse'])->name('rapport-caisse');

    Route::resource('requetes', RequeteController::class);
    Route::resource('transports', TransportController::class);
    Route::post('/valider-requete/{id}', [RequeteController::class, 'validateRequete'])->name('valider-requete');
    Route::post('/valider-transport/{id}', [TransportController::class, 'validateRequete'])->name('valider-transport');

    Route::get('devis_client/{client_id}', [ClientController::class, 'devisByClient']);
});
