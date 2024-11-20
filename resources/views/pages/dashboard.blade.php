@extends('layout.template')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1 class="text-dark">Tableau de Bord</h1>
            <nav>
                <ol class="breadcrumb">
                    <!-- <li class="breadcrumb-item"><a href="index.html">Home</a></li> -->
                    <li class="breadcrumb-item text-dark active">Tableau de Bord</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-8">
                    <div class="row">

                        <!-- Sales Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card _card_body sales-card">
                                <div class="card-body  d-flex flex-column  d-flex align-items-center justify-content-around">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <h5 class="card-title">Articles en rupture <span></span></h5>

                                    <div class="d-flex align-items-center">

                                        <div class="ps-3">
                                            <strong class="">145 sur 500</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Sales Card -->

                        <!-- Revenue Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card _card_body revenue-card">
                                <div class="card-body d-flex flex-column  d-flex align-items-center justify-content-around">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-cash"></i>
                                    </div>
                                    <h5 class="card-title">Dette Fournisseur <span></span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="ps-3">
                                            <strong>5 sur 25</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Revenue Card -->

                        <!-- Customers Card -->
                        <div class="col-xxl-4 col-xl-12">
                            <div class="card info-card _card_body customers-card">
                                <div class="card-body d-flex flex-column  d-flex align-items-center justify-content-around">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <h5 class="card-title">Créance Client <span></span></h5>

                                    <div class="d-flex align-items-center">

                                        <div class="ps-3">
                                            <strong>301 sur 800</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- End Customers Card -->

                        <!-- Reports -->
                        <div class="col-12">
                            <div class="card">
                                <div class="filter">
                                    <button type="button" data-bs-toggle="dropdown" class="btn btn-warning "
                                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem; margin-right:10px">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filtrer</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="#">Aujourd'hui</a></li>
                                        <li><a class="dropdown-item" href="#">Ce Mois</a></li>
                                        <li><a class="dropdown-item" href="#">Cette Année</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Evolution de ventes - Achats - Clients <span>/Aujourd'hui</span>
                                    </h5>

                                    <!-- Line Chart -->
                                    <div id="reportsChart"></div>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", () => {
                                            new ApexCharts(document.querySelector("#reportsChart"), {
                                                series: [{
                                                    name: 'Ventes',
                                                    data: [31, 40, 28, 51, 42, 82, 56],
                                                }, {
                                                    name: 'Achats',
                                                    data: [11, 32, 45, 32, 34, 52, 41]
                                                }, {
                                                    name: 'Clients',
                                                    data: [15, 11, 32, 18, 9, 24, 11]
                                                }],
                                                chart: {
                                                    height: 350,
                                                    type: 'area',
                                                    toolbar: {
                                                        show: false
                                                    },
                                                },
                                                markers: {
                                                    size: 4
                                                },
                                                colors: ['#4154f1', '#2eca6a', '#ff771d'],
                                                fill: {
                                                    type: "gradient",
                                                    gradient: {
                                                        shadeIntensity: 1,
                                                        opacityFrom: 0.3,
                                                        opacityTo: 0.4,
                                                        stops: [0, 90, 100]
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                stroke: {
                                                    curve: 'smooth',
                                                    width: 2
                                                },
                                                xaxis: {
                                                    type: 'datetime',
                                                    categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z",
                                                        "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z",
                                                        "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z",
                                                        "2018-09-19T06:30:00.000Z"
                                                    ]
                                                },
                                                tooltip: {
                                                    x: {
                                                        format: 'dd/MM/yy HH:mm'
                                                    },
                                                }
                                            }).render();
                                        });
                                    </script>
                                    <!-- End Line Chart -->

                                </div>

                            </div>
                        </div><!-- End Reports -->

                        <div class="col-12">
                            <div class="card">
                                <div class="filter">
                                    <button type="button" data-bs-toggle="dropdown" class="btn btn-warning "
                                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem; margin-right:10px">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filtrer</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="#">Aujourd'hui</a></li>
                                        <li><a class="dropdown-item" href="#">Ce Mois</a></li>
                                        <li><a class="dropdown-item" href="#">Cette Année</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Chiffres d'affaire - Mois <span>/Aujourd'hui</span>
                                    </h5>
                                    <div id="marketChart" class="market-line" style="min-height: 315px;">
                                        <canvas id="myChart" width="400" height="400"></canvas>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body border_orange">
                            <h5 class="card-title">Recents Achats <span></span></h5>
                            <div class="activity">
                                <div class="activity-item d-flex">
                                    <div class="activite-label">32 min</div>
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="activity-content">
                                        Achat enregistré chez le Fournisseur pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">56 min</div>
                                    <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                                    <div class="activity-content">
                                        Achat enregistré chez le Fournisseur pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">2 hrs</div>
                                    <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                                    <div class="activity-content">
                                        Achat enregistré chez le Fournisseur pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">1 day</div>
                                    <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                                    <div class="activity-content">
                                        Achat enregistré chez le Fournisseur pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">2 days</div>
                                    <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                    <div class="activity-content">
                                        Achat enregistré chez le Fournisseur pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div>

                                <div class="activity-item d-flex">
                                    <div class="activite-label">4 weeks</div>
                                    <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                                    <div class="activity-content">
                                        Achat enregistré chez le Fournisseur pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body border_orange">
                            <h5 class="card-title">Recentes Ventes <span></span></h5>
                            <div class="activity">
                                <div class="activity-item d-flex">
                                    <div class="activite-label">32 min</div>
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="activity-content">
                                        Vente enregistrée pour le client pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">56 min</div>
                                    <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                                    <div class="activity-content">
                                        Vente enregistrée pour le client pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">2 hrs</div>
                                    <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                                    <div class="activity-content">
                                        Vente enregistrée pour le client pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">1 day</div>
                                    <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                                    <div class="activity-content">
                                        Vente enregistrée pour le client pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">2 days</div>
                                    <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                    <div class="activity-content">
                                        Vente enregistrée pour le client pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">4 weeks</div>
                                    <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                                    <div class="activity-content">
                                        Vente enregistrée pour le client pour un montant de <a href="#"
                                            class="fw-bold text-warning">
                                            1 560 000</a> FCFA
                                    </div>
                                </div><!-- End activity item-->

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($collect->pluck('montant')) !!},
                datasets: [{
                    label: 'Mois',
                    data: {!! json_encode($collect->pluck('mois')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
