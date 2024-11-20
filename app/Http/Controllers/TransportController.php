<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompteClient;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $i = 0;
        $transports = Transport::with('client')->get();

        return view('pages.ventes-module.transport.index', compact(['i', 'transports']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();

        return view('pages.ventes-module.transport.create', compact(['clients']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'montant' => 'required|integer',
            'date_op' => 'required|date',
            'client_id' => 'required|string',
        ]);

        Transport::create([
            'montant' => $request->montant,
            'date_op' => $request->date_op,
            'client_id' => $request->client_id,
            'observation' => $request->observation
        ]);

        return redirect()->route('transports.index')->with('success', 'Requête de transport enregistrée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transport = Transport::with('client')->findOrFail($id);

        return view('pages.ventes-module.transport.show', compact(['transport']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transport = Transport::with('client')->findOrFail($id);
        $clients = Client::all();

        return view('pages.ventes-module.transport.edit', compact(['transport', 'clients']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'montant' => 'required|integer',
            'date_op' => 'required|date',
            'client_id' => 'required|string',
        ]);

        Transport::where('id', $id)->update([
            'montant' => $request->montant,
            'date_op' => $request->date_op,
            'client_id' => $request->client_id,
            'observation' => $request->observation
        ]);

        return redirect()->route('transports.index')->with('success', 'Requête de transport modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Transport::destroy($id);

        return redirect()->route('transports.index')->with('success', 'Requête de transport supprimée avec succès');
    }

    public function validateRequete($id) {
        $transport = Transport::findOrFail($id);

        $transport->update([
            'validator' => Auth::user()->id,
            'validate_at' =>now()
        ]);

        CompteClient::create([
            'date_op' => $transport->date_op,
            'montant_op' =>  $transport->montant,
            'facture_id' => null,
            'client_id' => $transport->client_id,
            'user_id'=> Auth::user()->id,
            'type_op' => 'TRP',
            'cle' => $transport->id,
        ]);

        return redirect()->route('transports.index')->with('success', 'Requête de transport validée avec succès');
    }
}
