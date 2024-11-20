<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignPermissionsToAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigner les nouvelles permissions au role admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $names = [


            'acces-dashboard',
            'rapport-reglements-frs',
            'rapport-encaissements-ventes',
            'rapport-factures-frs',
            'rapport-factures-ventes-clt',
            'rapport-factures-impayes-frs',
            'rapport-factures-partielmt-solde-frs',
            'rapport-factures-impayes-clt',
            'rapport-factures-partielmt-solde-clt',
            'rapport-acompte-clt',
            'rapport-acompte-frs',
            'rapport-factures-soldees-frs',
            'rapport-factures-soldees-clt',
            'rapport-livraisons-frs',
            'rapport-commandes-non-livrees',
            'rapport-livraisons-clt',
            'rapport-livraisons-clt-non-recues',
            'rapport-stock-user-connecte',
            'rapport-etats-soldes-clt',
            'rapport-inventaires',
            'ajouter-inventaire',
            'modifier-inventaire',
            'acces-inventaire',
        ];

        $label = [



            'Accès au dashboard',
            'Rapport reglements fournisseur',
            'Rapport encaissements ventes',
            'Rapport factures fournisseur',
            'Rapport factures ventes-client',
            'Rapport factures impayes-fournisseur',
            'Rapport factures partielmt-solde-fournisseur',
            'Rapport factures impayes-client',
            'Rapport factures partielmt-solde-client',
            'Rapport acompte client',
            'Rapport acompte fournisseur',
            'Rapport factures soldees fournisseur',
            'Rapport factures soldees client',
            'Rapport livraisons fournisseur',
            'Rapport commandes non livrees',
            'Rapport livraisons client',
            'Rapport livraisons client non recues',
            'Rapport stock user connecte',
            'Rapport etats soldes client',
            'Rapport inventaires',
            'Ajouter inventaire',
            'Modifier inventaire',
            'Accès inventaire',
        ];

        collect($names)->zip(collect($label))
            ->each(fn ($item) => Permission::create(['name' => $item[0], 'label' => $item[1]]));

        $role = Role::where('name', 'ADMIN')->first();

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $this->info('Permissions assignées avec succès!');
    }
}
