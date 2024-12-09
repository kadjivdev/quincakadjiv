<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Facture;
use App\Models\FactureVente;
use App\Models\LigneCommande;
use App\Models\LivraisonDirecte;
use App\Models\UniteMesure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LivraisonDirecteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $i = 1;

        $livraisons = DB::table('livraison_directes')
            ->join('clients', 'clients.id', '=', 'livraison_directes.client_id')
            ->join('ligne_commandes', 'ligne_commandes.id', '=', 'livraison_directes.ligne_commande_id')
            ->join('articles', 'articles.id', '=', 'ligne_commandes.article_id')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_commandes.unite_mesure_id')
            ->select(
                'livraison_directes.id',
                'livraison_directes.client_id',
                'articles.nom as article_nom',
                'clients.nom_client',
                'unite_mesures.unite',
                'livraison_directes.qte_livre',
                'livraison_directes.prix_vente',
                'livraison_directes.date_livraison',
                'livraison_directes.ligne_commande_id',
                'livraison_directes.validated_at',
                'ligne_commandes.prix_unit',
            )
            ->distinct()
            ->get();

        $facturesNonSoldesClients = Facture::where('montant_total', '>', DB::raw('montant_regle'))->pluck('devis_id');
        $clients =  DB::table('clients')
            ->whereNotIn('id', function ($query) use ($facturesNonSoldesClients) {
                $query->select('client_id')
                    ->from('devis')
                    ->whereColumn('devis.client_id', '=', 'clients.id')
                    ->whereIn('id', $facturesNonSoldesClients);
            })
            ->get();

        return view('pages.ventes-module.livraison-directes.index', compact('clients', 'livraisons', 'i'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            // 'commande_id' => 'required',
            'unite.*' => 'required',
            'qte_cmde.*' => 'required',
            'article.*' => 'required',
            'prix_unit.*' => 'required',
            'ligne_id.*' => 'required',

        ]);
        // dd($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        DB::beginTransaction();

        try {

            $count = count($request->qte_cmde);
            for ($i = 0; $i < $count; $i++) {
                $nbr = LivraisonDirecte::max('id');
                $ligne = LigneCommande::find($request->ligne_id[$i]);

                LivraisonDirecte::create([
                    'date_livraison' => Carbon::now(),
                    'client_id' => $request->client_id,
                    'user_id' => Auth::user()->id,
                    'qte_livre' => $request->qte_cmde[$i],
                    'ligne_commande_id' => $request->ligne_id[$i],
                    'prix_vente' => $request->prix_unit[$i],
                    'unite_mesure_id' => $ligne->unite_mesure_id,
                    'num_facture' => 'LD' . date('dmY') . ($nbr + 1),
                    'facture_type_id' => $request->type_id,
                ]);

                $ligne->update([
                    'qte_cmde' => $ligne->qte_cmde - (float)$request->qte_cmde[$i]
                ]);
            }
            DB::commit();

            return redirect()->route('livraisonsDirectes.index')->with('success', 'Livraison directe enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('livraisonsDirectes.index')->with('error', 'Erreur enregistrement de facture.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'prix_vente' => 'required',
            'ligne_id' => 'required',
            'montant_regle' => 'nullable',
        ]);

        // dd($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $livraison = LivraisonDirecte::find($id);
        DB::beginTransaction();
        try {
            $nbr = LivraisonDirecte::max('id');

            $montant = (float)$request->prix_vente * (float)$livraison->qte_livre;
            $livraison->update([
                'validated_at' => Carbon::now(),
                'client_id' => $request->client_id,
                'validator_id' => Auth::user()->id,
                'ligne_commande_id' => $request->ligne_id,
                // 'remise' => $request->remise,
                // 'tva' => $request->tva,
                // 'aib' => $request->aib,
                'prix_vente' => $request->prix_vente,
                'montant_facture' => $montant,
                'montant_total' => $montant,
                'montant_regle' => $request->montant_regle,
                'ref_livraison' => 'L' . date('dmY') . ($nbr + 1),

            ]);

            DB::commit();

            return redirect()->route('livraisonsDirectes.index')->with('success', 'Livraison directe validée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('livraisonsDirectes.index')->with('error', 'Erreur modification de livraison directe.');
        }
    }
}
