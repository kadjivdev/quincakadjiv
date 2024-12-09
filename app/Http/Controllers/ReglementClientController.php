<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\AcompteClient;
use App\Models\Client;
use App\Models\Facture;
use App\Models\FactureAncienne;
use App\Models\LivraisonDirecte;
use App\Models\ReglementClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\CompteClient;
use App\Models\Encaissement;
use App\Models\EncaisseReglement;
use App\Models\FactureVente;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ReglementClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reglements = ReglementClient::with(['client'])->orderBy('id', 'desc')->get();
        // dd($reglements);
        return view('pages.reglements-client.index', compact('reglements'));
    }

    public function regByCltNotHisto($id_clt)
    {
        $reglements = ReglementClient::where('client_id', $id_clt)->with(['client'])->orderBy('id', 'desc')->get(); 
        // dd($reglements);
        return view('pages.reglements-client.reglement_by_clt_not_histo', compact('reglements'));
    }

    public function regByCltToValid()
    {
        $reglements = ReglementClient::where('validated_at', null)->with(['client'])->orderBy('id', 'desc')->get(); 
        // dd($reglements);
        return view('pages.reglements-client.reglement_by_clt_to_valid', compact('reglements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $clients = Client::all();

        foreach ($request->all() as $key => $eachQueryParams) {
            $urlParams[] = $key;
        }

        //  dd($urlParams[0]);
        $client_id = $urlParams[0];

        $client = Client::find($client_id);





        return view('pages.reglements-client.create', compact('clients', 'client'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'montant_regle' => 'required|string',
            'type_reglement' => 'required',
            'client_id' => 'required',
            'date_reglement' => 'required',
            'reference' => 'required|unique:reglement_clients,reference',
            'facture_ref' => 'required',
            'observations' => 'nullable',
            'preuve_decharge' => 'required_if:type_reglement,Décharge|mimes:pdf,png,jpg|max:1024'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nbr = ReglementClient::max('id');
        $code = 'RG' . formaterCode($nbr + 1);

        if (substr($request->facture_ref, 0, 2) == 'FO') {
            $facture =  FactureAncienne::where('num_facture', $request->facture_ref)->first();
            $facture_ancienne_id = $facture ? $facture->id : null;
            $livraison_directe_id = null;
            $facture_id = null;
        } elseif (substr($request->facture_ref, 0, 2) == 'LD') {
            $facture =  LivraisonDirecte::where('num_facture', $request->facture_ref)->first();
            $livraison_directe_id = $facture ? $facture->id : null;
            $facture_ancienne_id = null;
            $facture_id = null;
        } else {
            $facture =  Facture::where('num_facture', $request->facture_ref)->first();
            $facture_id = $facture ? $facture->id : null;
            $livraison_directe_id = null;
            $facture_ancienne_id = null;
        }

        // dd($livraison_directe_id, $facture_ancienne_id, $facture_id);

        if($facture_id==null && $livraison_directe_id==null && $facture_ancienne_id==null){
            return redirect()->back()->withErrors('Référence facture inconnue')->withInput();
        }

        if ($request->hasFile('preuve_decharge')) {
            $fileName = time() . '_' . $request->preuve_decharge->getClientOriginalName();
            $request->file('preuve_decharge')->storeAs('public/uploads', $fileName);
        } else {
            $fileName = null;
        }
        $mont_restant = $facture->montant_total - $facture->montant_regle;
        if ((float)$request->montant_regle > $mont_restant) {
            $mont_reglement = $mont_restant;
            $mont_reglement_reste = (float)$request->montant_regle - $mont_restant;
        } else {
            $mont_reglement = $request->montant_regle;
        }

        $reglement = ReglementClient::create([
            'montant_regle' => $mont_reglement,
            'montant_total_regle' => $request->montant_regle,
            'client_id' => $request->client_id,
            'code' => $code,
            'type_reglement' => $request->type_reglement,
            'date_reglement' => $request->date_reglement,
            'reference' => $request->reference,
            'preuve_decharge' => $fileName,
            'observations' => $request->observations,
            'livraison_directe_id' => $livraison_directe_id,
            'facture_ancienne_id' => $facture_ancienne_id,
            'facture_id' => $facture_id,
            'user_id' => Auth::id()
        ]);

        

        if (Auth::user()->hasRole('CAISSE')) {
            $encaissement = new Encaissement();
            $encaissement->user_id = Auth::user()->id;
            $reglement->encaissements()->save($encaissement);
        }

        return redirect()->route('reglements-clt.index')
            ->with('success', 'Règlement ajouté avec succès.');
    }

    public function validateReg($id_reg)
    {
        DB::beginTransaction();

        try{
            $reglement = ReglementClient::find($id_reg);
            
            if ($reglement->facture_id){
                $facture =  Facture::find($reglement->facture_id);
            }elseif($reglement->facture_ancienne_id) {
                $facture =  FactureAncienne::find($reglement->facture_ancienne_id);
            }elseif($reglement->livraison_directe_id) {
                $facture =  LivraisonDirecte::find($reglement->livraison_directe_id);
            }

            
            $mont_restant = $facture->montant_total - $facture->montant_regle;
            if ((float)$reglement->montant_regle > $mont_restant) {
                $mont_reglement = $mont_restant;
            } else {
                $mont_reglement = $reglement->montant_regle;
            }
            
            $compte_client = CompteClient::create([
                'date_op' => $reglement->date_reglement,
                'montant_op' => $reglement->montant_regle,
                'facture_id' => $reglement->facture_id,
                'client_id' => $reglement->client_id,
                'user_id'=> Auth::user()->id,
                'type_op' => 'REG_VP',
                'cle' => $reglement->id,
            ]);

            $montant = $facture->montant_regle + $mont_reglement;

            $facture->update([
                'montant_regle' => $montant,
            ]);

            $client = Client::find($reglement->client_id);

            if ((float)$reglement->montant_total_regle > $mont_restant) {
                $nbr = AcompteClient::max('id');
                $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));
                $code = 'KAD-'. 'ACC' . ($nbr + 1).'-'.date('dmY') . '-' . $lettres;

                $acompte = (float)$reglement->montant_total_regle - $mont_restant;
                $acc =  AcompteClient::create([
                    'montant_acompte' => $acompte,
                    'reglement_client_id' => $reglement->id,
                    'client_id' => $reglement->client_id,
                    'code' => $code,
                    'user_id' => Auth::id(),
                    'type_reglement' => $reglement->type_reglement,
                    'date_op' =>  $reglement->date_reglement,
                    'validated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'validator_id' => Auth::user()->id
                ]);

                if (Auth::user()->hasRole('CAISSE')) {
                    $encaissement = new Encaissement();
                    $encaissement->user_id = Auth::user()->id;
                    $acc->encaissements()->save($encaissement);
                }

                $compte_client_acc = CompteClient::create([
                    'date_op' => $reglement->date_reglement,
                    'montant_op' =>  $acompte,
                    'facture_id' => $reglement->facture_id,
                    'client_id' => $reglement->client_id,
                    'user_id'=> Auth::user()->id,
                    'type_op' => 'ACC_REG',
                    'cle' => $acc->id,
                ]);

                $client->acompte_total = $client->acompte_total + $acompte;
                $client->save();
            }

            $reglement->validated_at =  now();
            $reglement->validator_id =   Auth::id();
            $reglement->save();

            DB::commit();
            return redirect()->route('real-reglements-clt', $client->id )
                ->with('success', 'Règlement validé avec succès.');
        }catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('real-reglements-clt', $client->id )->withErrors(['message' => 'Erreur validation règlement '.$e->getMessage()]);
        }
    }

    public function delReg($id){
        $reglement = ReglementClient::find($id);
        ReglementClient::destroy($id);
        
        return redirect()->route('real-reglements-clt', $reglement->client_id )
        ->with('success', 'Règlement validé avec succès.');
    }


    public function edit($id)
    {
        $reglement = ReglementClient::find($id);
        if ($reglement->facture_id){
            $facture =  Facture::find($reglement->facture_id);
        }elseif($reglement->facture_ancienne_id) {
            $facture =  FactureAncienne::find($reglement->facture_ancienne_id);
        }elseif($reglement->livraison_directe_id) {
            $facture =  LivraisonDirecte::find($reglement->livraison_directe_id);
        }
        
        $client = Client::find($reglement->client_id);
        // dd($facture);
        return view('pages.reglements-client.edit', compact('reglement', 'client', 'facture'));
    }

    public function show($id) {
        $reglement = ReglementClient::find($id);
        if ($reglement->facture_id){
            $facture =  Facture::find($reglement->facture_id);
        }elseif($reglement->facture_ancienne_id) {
            $facture =  FactureAncienne::find($reglement->facture_ancienne_id);
        }elseif($reglement->livraison_directe_id) {
            $facture =  LivraisonDirecte::find($reglement->livraison_directe_id);
        }
        
        $client = Client::find($reglement->client_id);
        // dd($facture);
        return view('pages.reglements-client.show', compact('reglement', 'client', 'facture'));
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'montant_regle' => 'required|string',
            'type_reglement' => 'required',
            'date_reglement' => 'required',
            'reference' => 'required',
            'preuve_decharge' => 'required_if:type_reglement,Décharge|mimes:pdf,png,jpg|max:1024'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $reglement = ReglementClient::find($id);
        
        if ($reglement->facture_id){
            $facture =  Facture::find($reglement->facture_id);
        }elseif($reglement->facture_ancienne_id) {
            $facture =  FactureAncienne::find($reglement->facture_ancienne_id);
        }elseif($reglement->livraison_directe_id) {
            $facture =  LivraisonDirecte::find($reglement->livraison_directe_id);
        }

        // dd($request->all());

        if ($request->hasFile('preuve_decharge')) {
            $fileName = time() . '_' . $request->preuve_decharge->getClientOriginalName();
            $request->file('preuve_decharge')->storeAs('public/uploads', $fileName);
        } else {
            $fileName = null;
        }

        $reglement->update([
            'montant_regle' => $request->montant_regle,
            'type_reglement' => $request->type_reglement,
            'date_reglement' => $request->date_reglement,
            'reference' => $request->reference,
            'preuve_decharge' => $fileName,
            // 'validator_id' => Auth::id(),
            // 'validated_at' => now(),
        ]);
        $reglement->validator_id = Auth::id();
        $reglement->validated_at = now();
        $reglement->save();
        $montant = $facture->montant_regle + (float)$request->montant_regle;

        $facture->update([
            'montant_regle' => $montant,
        ]);
        return redirect()->route('reglements-clt.index')
            ->with('success', 'Règlement modifié avec succès.');
    }

    public function reglementParClt($id)
    {
        $client = Client::find($id);
        $compte = CompteClient::where('client_id', $client->id)
        ->with(['facture' => function ($query) {
            $query->select('id', 'devis_id'); // Sélectionnez les colonnes de la table facture
        }])
        ->orderBy('id', 'desc')
        ->get();

        $solde = 0;

        foreach ($compte as $transaction) {
            if ($transaction->type_op == "FAC" || $transaction->type_op == "FAC_AC" || $transaction->type_op == "FAC_VP" || $transaction->type_op == "FAC_VC" || $transaction->type_op == "FAC_RAN" || $transaction->type_op == "TRP") {
                // Si c'est un règlement ou un acompte, on soustrait le montant
                $solde -= $transaction->montant_op;
            } else {
                // Pour tout autre type d'opération, on ajoute le montant
                $solde += $transaction->montant_op;
            }

            if ($transaction->type_op == "FAC_VC") {
                $facture = FactureVente::find($transaction->facture_id);
                $transaction->vente_id = $facture->vente_id;
                // dd($transaction);
            }

            // dd($transaction);
        }

        // dd($transaction);
        return view('pages.reglements-client.list-regl-clt', compact('client', 'compte', 'solde'));

    }


    public function getAccompteByClient($id)
    {

        $client = Client::find($id);

        $accomptes = AcompteClient::where('client_id', $client->id)->get();

        $getDateReglementByRegId = function ($reglement_client_id) {
            ReglementClient::find($reglement_client_id);
        };


        return view('pages.reglements-client.list-acompte-clt', compact('client', 'accomptes', 'getDateReglementByRegId'));
    }
}
