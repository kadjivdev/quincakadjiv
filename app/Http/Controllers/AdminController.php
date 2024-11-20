<?php

namespace App\Http\Controllers;

use App\Models\BonCommande;
use App\Models\Client;
use App\Models\Commande;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function indexClient()
    {
        $clients = Client::all();
        return view('pages.ventes-module.clients.index', compact('clients'));
    }

    public function indexBonCommandes()
    {
        $i = 1;
        $bons = BonCommande::all();
        $arrayIds = Commande::pluck('bon_commande_id')->toArray();

        return view('pages.achats-module.bon-commandes.index', compact('bons', 'i', 'arrayIds'));
    }
}
