<aside id="sidebar" class="sidebar">

    <img src="{{asset('assets/img/kadjiv.png')}}" class="img-fluid" alt="BootstrapBrain Logo" width="200" height="80">
    <hr>

    <ul class="sidebar-nav" id="sidebar-nav">
        @can('rapports.acces-dashboard')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('/') ? 'actif_menu' : '' }}" href="{{route('dashboard')}}">
                <i class="bi bi-grid"></i>
                <span>Tableau de bord</span>
            </a>
        </li>
        @endcan

        <li class="nav-item ">
            <a class="nav-link collapsed {{ request()->is('articles') ? 'actif_menu' : '' }} {{ request()->is('categories') ? 'actif_menu' : '' }} {{ request()->is('tauxSupplements') ? 'actif_menu' : '' }}" data-bs-target="#articles-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide {{ request()->is('articles') ? 'actif_menu' : '' }} "></i><span>Gestion
                    des Articles</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="articles-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                @can('articles.list-categories')
                <li>
                    <a href="{{ route('categories.index') }}" class="nav-link">
                        <i class="bi bi-circle"></i><span>Catégories </span>
                    </a>
                </li>
                @endcan
                @can('articles.list-articles')
                <li>
                    <a href="{{ route('articles.index') }}" class="nav-link">
                        <i class="bi bi-circle"></i>
                        <span>Articles</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li>

        <li class="nav-item ">
            <a class="nav-link collapsed {{ request()->is('fournisseurs') ? 'actif_menu' : ''}} {{ request()->is('reglements') ? 'actif_menu' : ''}} {{ request()->is('fournisseurs/create') ? 'actif_menu' : ''}}" data-bs-target="#frs-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i>
                <span>Gestion des fournisseurs</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="frs-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                @can('fournisseurs.list-fournisseurs')
                <li>
                    <a href="{{ route('fournisseurs.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Fournisseur </span>
                    </a>
                </li>
                @endcan
                @can('fournisseurs.list-reglements-frs')
                <li>
                    <a href="{{ route('reglements.index') }}" class="nav-link">
                        <i class="bi bi-circle"></i>
                        <span>Règlements frs</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li>

        <li class="nav-heading">Achats</li>

        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('bon-commandes') ? 'actif_menu' : '' }} {{ request()->is('commandes') ? 'actif_menu' : '' }} {{ request()->is('supplements') ? 'actif_menu' : '' }}" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Gestion des Commandes</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                @can('programmations-achat.list-bons-commandes')
                <li>
                    <a href="{{ route('bon-commandes.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Programmation d'achats</span>
                    </a>
                </li>
                @endcan

                @can('bon-commandes.list-commandes')
                <li>
                    <a href="{{ route('commandes.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Bons de commande </span>
                    </a>
                </li>
                @endcan
                @can('bon-commandes.list-cmde-sup')
                <li>
                    <a href="{{ route('supplements.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Commandes supplémentaires </span>
                    </a>
                </li>
                @endcan
            </ul>
        </li><!-- End Components Nav -->

        @can('livraisons.list-livraisons-frs')
        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('livraisons') ? 'actif_menu' : '' }}" href="{{ route('livraisons.index') }}">
                <i class="bi bi-person"></i>
                <span>Appros / Livraisons</span>
            </a>
        </li>
        @endcan

        <li class="nav-heading">Ventes</li>

        <li class="nav-item">
            <a class="nav-link collapsed 
            {{ request()->is('clients') ? 'actif_menu' : '' }} 
             {{ request()->is('devis') ? 'actif_menu' : '' }} 
                  {{ request()->is('ventes') ? 'actif_menu' : '' }}
                  {{ request()->is('livraisonsDirectes') ? 'actif_menu' : '' }} 
                   {{ request()->is('factures') ? 'actif_menu' : '' }}
                    {{ request()->is('deliveries') ? 'actif_menu' : '' }}
                  " data-bs-target="#ventes-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Gestion des ventes</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="ventes-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                @can('clients.list-clients')
                <li>
                    <a href="{{ route('clients.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Clients</span>
                    </a>
                </li>
                @endcan

                @can('proforma.list-devis')
                <li>
                    <a href="{{ route('devis.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Proforma</span>
                    </a>
                </li>
                @endcan
                @can('ventes.list-ventes')
                <li>
                    <a href="{{ route('ventes.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Vente au comptant</span>
                    </a>
                </li>
                @endcan
                @can('ventes.list-ventes')
                <li>
                    <a href="{{ route('vente-caisse') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Caisse</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('rapport-caisse') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Rapport Caisse</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('requetes.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Requêtes</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transports.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Transports</span>
                    </a>
                </li>
                @endcan
                @can('livraisons.list-livraison-directe')
                <li>
                    <a href="{{ route('livraisonsDirectes.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Livraisons non physique </span>
                    </a>
                </li>
                @endcan
                @can('clients.list-factures-clients')
                <li>
                    <a href="{{ route('factures.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Facturation/vte non soldé</span>
                    </a>
                </li>
                @endcan

                @can('livraisons.list-livraisons-client')
                <li>
                    <a href="{{ route('deliveries.index') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Livraison client </span>
                    </a>
                </li>
                @endcan
            </ul>
        </li>

        <li class="nav-heading">Validations</li>

        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('reglement-clt-to-valid') ? 'actif_menu' : '' }}" data-bs-target="#validations-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Validations</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="validations-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                @can('clients.list-clients')
                <li>
                    <a href="{{ route('reglements-clt-to-valid') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Règlements Clients</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li>

        <li class="nav-heading">Rapports</li>

        <li class="nav-item">
            <a class="nav-link collapsed 
            {{ request()->is('rapport_vente_journaliere') ? 'actif_menu' : '' }}
            {{ request()->is('rapport_livraison_frs') ? 'actif_menu' : '' }}
            {{ request()->is('rapport_reglement_frs') ? 'actif_menu' : '' }}
            {{ request()->is('rapport_reglement_clt') ? 'actif_menu' : '' }}
            {{ request()->is('rapport_factures_ventes') ? 'actif_menu' : '' }}
            {{ request()->is('rapport_fact_frs') ? 'actif_menu' : '' }}
            {{ request()->is('rapport_fact_frs') ? 'actif_menu' : '' }}
            {{ request()->is('rapport_fact_vte_clt') ? 'actif_menu' : '' }}
            {{ request()->is('rapport_fact_clt_non_reg') ? 'actif_menu' : '' }}

            " data-bs-target="#rapport-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Rapports</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="rapport-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

                <li>
                    <a href="{{ url('/rapport_vente_journaliere') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Rapport des ventes journalières</span>
                    </a>
                </li>


                <li>
                    <a href="{{ url('/rapport_livraison_frs') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Rapport Livraison fournisseur</span>
                    </a>
                </li>

                @can('rapports.rapport-reglements-frs')
                <li>
                    <a href="{{ url('/rapport_reglement_frs') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Rapport règlement fournisseur</span>
                    </a>
                </li>
                @endcan

                {{-- @can('rapports.rapport-reglements-clt') --}}
                <li>
                    <a href="{{ url('/rapport_reglement_clt') }}" class="nav-link ">
                        <i class="bi bi-circle"></i><span>Rapport règlement Client</span>
                    </a>
                </li>
                {{-- @endcan --}}

                @can('rapports.rapport-factures-ventes-clt')
                <li>
                    <a href="{{ url('/rapport_factures_ventes') }}"
                        class="nav-link ">
                        <i class="bi bi-circle"></i><span>Ventes</span>
                    </a>
                </li>
                @endcan
                @can('rapports.rapport-factures-frs')
                <li>
                    <a href="{{ route('rap_fact_frs') }}"
                        class="nav-link">
                        <i class="bi bi-circle"></i><span>Factures frs</span>
                    </a>
                </li>
                @endcan
                @can('rapports.rapport-factures-ventes-clt')
                <li>
                    <a href="{{ route('rap_fact_vte_clt') }}"
                        class="nav-link ">
                        <i class="bi bi-circle"></i><span>Factures client</span>
                    </a>
                </li>
                @endcan
                @can('rapports.rapport-factures-impayes-clt')
                <li>
                    <a href="{{ route('facturesCltSansReglemt') }}"
                        class="nav-link ">
                        <i class="bi bi-circle"></i><span>Factures clt non réglées</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li><!-- End Components Nav -->

        <li class="nav-heading">Paramètres</li>

        <li class="nav-item">
            <a class="nav-link collapsed
            {{ request()->is('roles') ? 'actif_menu' : '' }}
             {{ request()->is('users') ? 'actif_menu' : '' }}
            " data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-bar-chart"></i><span>Gestion des rôles</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                @can('roles.role-list')
                <li>
                    <a class="nav-link " href="{{ route('roles.index') }}">
                        <i class="bi bi-circle"></i><span>Liste des rôles</span>
                    </a>
                </li>
                @endcan
                @can('users.user-list')
                <li>
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="bi bi-circle"></i><span>Liste des utilisateurs</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li><!-- End Charts Nav -->
        @can('point-ventes.list-boutiques')
        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('factures-anciennes/create') ? 'actif_menu' : '' }}" href="{{ route('factures-anciennes.create') }}">
                <i class="bi bi-arrow-return-right"></i>
                <span>Report à nouveau</span>
            </a>
        </li>
        @endcan

        @can('point-ventes.list-boutiques')
        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('unites') ? 'actif_menu' : '' }}" href="{{ route('unites.index') }}">
                <i class="bi bi-unity"></i>
                <span>Unités de mesure</span>
            </a>
        </li>
        @endcan

        @can('point-ventes.list-boutiques')
        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('boutiques') ? 'actif_menu' : '' }}" href="{{ route('boutiques.index') }}">
                <i class="bi bi-shop"></i>
                <span>Points de vente</span>
            </a>
        </li>
        @endcan
        @can('point-ventes.list-magasins')
        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('magasins') ? 'actif_menu' : '' }}" href="{{ route('magasins.index') }}">
                <i class="bi bi-houses"></i>
                <span>Gestion Magasins</span>
            </a>
        </li>
        @endcan
        @can('ventes.list-bon-ventes')
        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('bons-ventes') ? 'actif_menu' : '' }}" href="{{ route('bons-ventes.index') }}">
                <i class="bi bi-cart-check"></i>
                <span>Livraisons vente comptant</span>
            </a>
        </li>
        @endcan
        <!-- @can('chauffeurs.list-chauffeurs') -->
        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('chauffeurs') ? 'actif_menu' : '' }}" href="{{ route('chauffeurs.index') }}">
                <i class="bi bi-car-front"></i>
                <span>Gestion des chauffeurs</span>
            </a>
        </li>
        <!-- @endcan -->

        {{-- @can('agent.list-agents') --}}
        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('agents') ? 'actif_menu' : '' }}" href="{{ route('agents.index') }}">
                <i class="bi bi-people"></i>
                <span>Gestion des agents</span>
            </a>
        </li>
        {{-- @endcan --}}

        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('vehicules') ? 'actif_menu' : '' }}" href="{{ route('vehicules.index') }}">
                <i class="bi bi-car-front"></i>
                <span>Gestion des Véhicules</span>
            </a>
        </li>

    </ul>

</aside><!-- End Sidebar-->

<script src="{{ asset('assets/js/jquery3.6.min.js') }}"></script>


<script>
    $(document).ready(function() {
        var sidebarLinks = $('.sidebar-nav a');

        sidebarLinks.click(function(e) {
            sidebarLinks.removeClass('active');
            $(this).addClass('active');
            if ($(this).hasClass('collapsed')) {
                var parent = $(this).closest('.nav-item');
                parent.addClass('active');

                if (!parent.find('.collapse').hasClass('show')) {
                    parent.find('.collapse').addClass('show');
                }
            }
            $('.nav-item.active .collapse.show').not(parent.find('.collapse')).removeClass('show');
        });
    });
</script>