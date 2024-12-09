<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'roles' => [
                'role-list' => 'Liste des roles',
                'creer-role' => 'Créer un role',
                'role-show' => 'Voir les details d\'un role',
                'modifier-role' => 'Modifier un role',
                'supprimer-role' => 'Supprimer un role',
            ],
            // Users
            'users' => [
                'user-list' => 'Liste des utilisateurs',
                'user-create' => 'Creer un utilisateur',
                'user-detail' => 'Voir les details d\'un utilisateur',
                'user-edit' => 'Modifier un utilisateur',
                'user-enable' => 'Activer un utilisateur',
                'user-disable' => 'Désactiver un utilisateur',
            ],

            'point-ventes' => [
                'add-boutique'  => 'Ajouter un point de vente',
                'stock-boutique'  =>             'Voir stock d\'un point de vente',
                'show-boutique'  =>             'Voir details d\'un point de vente',
                'list-boutiques'  =>             'Liste des points de ventes',
                'add-magasin'  =>             'Ajouter un magasin',
                'list-magasins'  =>             'Liste des magasins',
                'voir-magasin'  =>             'Détails magasin',
                'list-transferts-mag'  => 'Liste transferts magasins',
                'details-transferts-mag'  =>              'Voir details transfert',
                'add-transferts-mag'  =>              'Enregistrer un transfert ',
                'valider-transferts-mag'  =>              'Valider un transfert ',
                'attribuer-mag-principal'  =>              'Attribuer magasin principal',
            ],

            'chauffeurs'  => [
                'list-chauffeurs'  =>             'Liste des chauffeurs',
                'add-chauffeur'  =>             'Ajouter un chauffeur',
                'edit-chauffeur'  =>             'Modifier chauffeur',
            ],


            'articles' => [
                'list-categories'  =>           'Liste des categories',
                'add-category'  =>           'Ajouter une categorie',
                'edit-category'  =>           'Modifier une categorie',
                'delete-category' =>           'Supprimer categorie',
                'list-articles'  =>           "Liste des articles",
                'ajouter-article'  =>           "Ajouter un article",
                'modifier-article'  =>           "Modifier un article",
                'config-taux'   =>         'Configurer les taux de conversions',
            'enregistrer-stock'      =>  'Enregistrer stock',

            ],

            'programmations-achat' => [
                'list-bons-commandes'  => 'Liste des programmations achat',
                'ajouter-bon-commande'  =>            'Ajouter une programmation achat',
                'modifier-bon-commande'  =>              'Modifier une programmation achat',
                'voir-bon-commande'  =>             'Voir detail programmation achat',
                'valider-bon-commande'  =>            'Valider une programmation achat',
                'delete-bon-commande'  =>             'Supprimer une programmation achat',
            ],

            'bon-commandes' =>  [
                'list-commandes'    =>          'Liste des commandes',
                'ajouter-commande' => 'Ajouter une commande',
                'voir-commande'  =>  'Voir details d\'une commande',
                'modifier-commande'  =>  'Modifier commande',
                'valider-commande'  => 'Valider commande',
                'ajouter-cmde-sup' =>   'Enregistrer une commande supplémentaire',
                'list-cmde-sup' =>               'Liste des commandes supplémentaires',
                'generer-facture-cmde'  =>   'Etablir une facture de commande',

            ],

            'fournisseurs' => [
                'list-fournisseurs' =>  'Liste des fournisseurs',
                'ajouter-fournisseur' =>              'Ajouter un fournisseur',
                'editer-fournisseur' =>              'Modifier un fournisseur',
                'compte-fournisseur' =>              'Voir compte fournisseur',
                'voir-fournisseur' =>              'Details fournisseur',
                'list-reglements-frs'    =>   'Liste de tous les reglements fournisseur',
                'reglements-d-un-frs'   =>    'Voir règlements d\'un fournisseur',
                'ajouter-reglement-frs'    => 'Ajouter reglement fournisseur',
                'modifier-reglement-frs'    =>             'Modifier reglement fournisseur',
                'details-d-une-facture-frs'  =>     'Détails d\'une facture fournisseur',
            ],

            'clients'  => [
                'list-clients'  =>              'Liste des clients',
                'ajouter-client'  =>             'Ajouter un client',
                'editer-client'  =>             'Modifier un client',
                'compte-client'  =>             'Voir compte client',
                'suivi-clients'  =>             'Suivi des clients',
                'list-reglements-clt'  =>              'Liste de tous les reglements client',
                'suivi-creances-btp'  =>              'Suivi des creances btp',
                'list-factures-clients' =>   'Liste des factures client',
                'report-a-nouveau' =>  'Report à nouveau',
                'reglements-d-un-client'   =>    'Voir règlements d\'un client',
                'ajouter-reglement-clt' =>              'Ajouter reglement client',
                'modifier-reglement-clt'    =>              'Modifier reglement client',
                'enregistrer-accompte'  =>              'Enregistrer un accompte client',
                'list-accomptes'    =>              'Liste des accomptes',
                'details-d-une-facture-clt' =>       'Détails d\'une facture client',
            ],

            'livraisons'     =>  [
                'list-livraisons-frs'     =>      'Liste des livraisons forunisseurs',
                'ajouter-livraison-frs'     =>    'Enregistrer livraison fournisseur',
                'ajouter-livraison-directe'   =>   'Enregistrer une livraison directe',
                'list-livraison-directe'  =>               'Liste des livraisons directes',
                'generer-bordereau' =>   'Générer un bordereau',
                'modifier-appro' =>     'Modifier approvisionnement',
                'list-livraisons-client'  =>    'Liste des livraisons client',
                'ajouter-livraison-client'  =>              'Ajouter une livraison client',
                'valider-livraison-directe' => 'Valider une livraison directe',
                'enregistrer-livraison-vente' => 'Enregistrer une livraison de vente comptant',
                'list-livraisons-vente' => 'Liste des livraisons de vente comptant',
                'valider-livraison-vente' => 'Valider une livraison de vente comptant',

            ],

            'proforma'   => [
                'list-devis'  => 'Liste des devis',
                'detail-devis'  => 'Détails d\'un devis',
                'ajouter-devis'   =>          'Enregistrer un devis',
                'generer-facture-devis' =>       'Etablir une facture de devis',
                'list-factures-devis'   =>  'Liste des factures proforma',
                'ajouter-facture-devis' =>              'Ajouter facture de proforma',
            ],

            'ventes'    =>  [
                'ajouter-vente'   => 'Enregistrer une vente au comptant',
                'list-ventes'     =>       'Liste des ventes',
                'vente-credit'  =>             'Liste des ventes à credit',
                'voir-vente'  =>    'Détails d\'une vente',
                'list-bon-ventes' => 'Liste des bons de vente',
                'voir-bon-vente' => 'Voir details bon de vente',
                'valider-bon-vente' => 'Valider bon de vente',
            ],

            'catalogue' => [
                'acces-catalogues'  =>      'Acces au catalogue',
            'modifier-catalogue'    =>             'Modifier catalogue',
            'ajouter-catalogue'     =>             'Ajouter un catalogues',
            ],

            'rapports' => [
                'acces-dashboard'   =>  'Accès au dashboard',
                'rapport-reglements-frs'    =>              'Rapport reglements fournisseur',
                'rapport-encaissements-ventes'  =>              'Rapport encaissements ventes',
                'rapport-factures-frs'  =>              'Rapport factures fournisseur',
                'rapport-factures-ventes-clt'   =>              'Rapport factures ventes-client',
                'rapport-factures-impayes-frs'  =>              'Rapport factures impayes-fournisseur',
                'rapport-factures-partielmt-solde-frs'  =>              'Rapport factures partielmt-solde-fournisseur',
                'rapport-factures-impayes-clt'  =>              'Rapport factures impayes-client',
                'rapport-factures-partielmt-solde-clt'  =>              'Rapport factures partielmt-solde-client',
                'rapport-acompte-clt'   =>              'Rapport acompte client',
                'rapport-acompte-frs'   =>              'Rapport acompte fournisseur',
                'rapport-factures-soldees-frs'  =>              'Rapport factures soldees fournisseur',
                'rapport-factures-soldees-clt'  =>              'Rapport factures soldees client',
                'rapport-livraisons-frs'    =>              'Rapport livraisons fournisseur',
                'rapport-commandes-non-livrees' =>              'Rapport commandes non livrees',
                'rapport-livraisons-clt'    =>              'Rapport livraisons client',
                'rapport-livraisons-clt-non-recues' =>              'Rapport livraisons client non recues',
                'rapport-stock-user-connecte'   =>              'Rapport stock user connecte',
                'rapport-etats-soldes-clt'  =>              'Rapport etats soldes client',
                'rapport-inventaires'   =>              'Rapport inventaires',
                'ajouter-inventaire'    =>              'Ajouter inventaire',
                'modifier-inventaire'   =>              'Modifier inventaire',
                'valider-inventaire'  =>              'Valider inventaire',
                'acces-inventaire'  =>              'Accès inventaire',
            ],

        ];

        foreach($permissions as $group => $list) {
            foreach($list as $name => $label) {
                Permission::create(['group' => $group,'name' => "$group.$name", 'label' => $label]);
            }
        }

        $role = Role::create(['name' => 'Super Admin', 'editable' => false]);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);

        // collect($permissions)->zip(collect($label))
        //     ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));
    }
}
