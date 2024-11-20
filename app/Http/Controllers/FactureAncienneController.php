<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\AcompteClient;
use App\Models\Client;
use App\Models\FactureAncienne;
use App\Models\CompteClient;
use App\Models\Encaissement;
use App\Models\FactureType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FactureAncienneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexAccompte(Request $request)
    {
        $accomptes = AcompteClient::with(['client'])->get();


        return view('pages.reglements-client.list-accomptes', compact('accomptes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = FactureType::all();

        return view('pages.factures-anciennes.create', compact('types'));
    }

    public function createAccompte(Request $request)
    {

        foreach ($request->all() as $key => $eachQueryParams) {
            $urlParams[] = $key;
        }

        //dd($urlParams[0]);
        $client_id = $urlParams[0];

        $client = Client::find($client_id);
        return view('pages.reglements-client.acompte-create', compact("client"));
    }

    public function updateAccompte($id_accompte){
        $accompte = AcompteClient::findOrFail($id_accompte);
        // dd($accompte);
        // $client_id = $urlParams[0];

        $client = Client::find($accompte->client_id);
        return view('pages.reglements-client.acompte-update', compact("client", "accompte"));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required',
            'montant_total' => 'required',
            'client_id' => 'required',
            'date_facture' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nbr = count(FactureAncienne::all());

        $facture = FactureAncienne::create([
            'date_facture' => $request->date_facture,
            'statut' => 'Non soldé',
            'client_id' => $request->client_id,
            'montant_facture' => $request->montant_total,
            'montant_total' => $request->montant_total,
            'num_facture' => 'FO' . date('dmY') . ($nbr + 1),
            'user_id' => Auth::user()->id,
            'facture_type_id' => $request->type_id,
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Facture ajoutée avec succès.');
    }

    public function storeAcompte(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_reglement' => 'required',
            'montant_acompte' => 'required',
            'client_id' => 'required',
            'date_acc' => 'required',
            'reference' => 'required|unique:acompte_clients,reference',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $nbr = AcompteClient::max('id');
        // $code = 'AC' . formaterCode($nbr + 1);
        $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));
        $code = 'KAD-'. 'ACC' . ($nbr + 1).'-'.date('dmY') . '-' . $lettres;

        $facture = AcompteClient::create([
            'montant_acompte' => $request->montant_acompte,
            'client_id' => $request->client_id,
            'user_id' => Auth::id(),
            'code' => $code,
            'type_reglement' => $request->type_reglement,
            'reference' => $request->reference,
            "observation_acompte_client"  => $request->observation_acompte_client ?? null,
            "date_op"  => $request->date_acc,
        ]);

        if (Auth::user()->hasRole('CAISSE')) {
            $encaissement = new Encaissement();
            $encaissement->user_id = Auth::user()->id;
            $facture->encaissements()->save($encaissement);
        }

        return redirect()->route('clients.index')
            ->with('success', 'Acompte enregistré avec succès.');
    }

    public function saveUpdateAcompte(Request $request){
        $validator = Validator::make($request->all(), [
            'type_reglement' => 'required',
            'montant_acompte' => 'required',
            'client_id' => 'required',
            'date_acc' => 'required',
            'reference' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $accompte = AcompteClient::findOrFail($request->accompte_id);
        $accompte->fill($request->all());
        
        $accompte->save();

        return redirect()->route('acomptes-clt', $accompte->client_id)->with('success', 'Acompte modifié avec succès.');
    }

    public function deleteAccompte($id_accompte) {
        $id_clt = AcompteClient::findOrFail($id_accompte)->client_id;
        AcompteClient::destroy($id_accompte);

        return redirect()->route('acomptes-clt', $id_clt)->with('success', 'Acompte supprimé avec succès.');
    }

    public function validateAcompte($id_accompte)
    {

        $facture = AcompteClient::findOrFail($id_accompte);

        $client = Client::find($facture->client_id);

        $client->acompte_total = $client->acompte_total + (float)$facture->montant_acompte;
        $client->save();

        $lastRow = CompteClient::orderBy('id', 'desc')->get()->first();

        $compte_client = CompteClient::create([
            'date_op' => $facture->date_op,
            'montant_op' => $facture->montant_acompte,
            'facture_id' => $lastRow->facture_id,
            'client_id' => $facture->client_id,
            'user_id'=> Auth::user()->id,
            'type_op' => 'Acc',
        ]);

        $facture->validated_at = Carbon::now()->format('Y-m-d H:i:s');
        $facture->validator_id = Auth::user()->id;
        $facture->save();

        return redirect()->route('acomptes-clt', $client->id)->with('success', 'Acompte validé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
