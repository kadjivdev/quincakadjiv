<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\Article;
use App\Models\BonCommande;
use App\Models\Commande;
use App\Models\LigneBonCommande;
use App\Models\LigneCommande;
use App\Models\UniteMesure;
use App\Models\Devis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Codedge\Fpdf\Fpdf\PDF_MC_Table;
use Codedge\Fpdf\Fpdf\ChiffreEnLettre;

class BonCommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $i = 1;
        $bons = BonCommande::with('createur')
        ->whereNull('valideur_id')
        ->orderBy('id', 'desc')
        ->get();
        $arrayIds = Commande::pluck('bon_commande_id')->toArray();
        return view('pages.achats-module.bon-commandes.index', compact('bons', 'i', 'arrayIds'));
    }

    public function listeValider()
    {
        $i = 1;

        $bons = BonCommande::with('createur')
        ->whereNotNull('valideur_id')
        ->orderBy('id', 'desc')
        ->get();

        $arrayIds = Commande::pluck('bon_commande_id')->toArray();
        return view('pages.achats-module.bon-commandes.liste-valider', compact('bons', 'i', 'arrayIds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $articles =  Article::all();
        $unites = UniteMesure::all();

        return view('pages.achats-module.bon-commandes.create', compact('articles', 'unites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_bon_cmd' => 'required',
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'unites.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $nbr = BonCommande::max('id');
            $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));

            // Création du bon de commande
            $bon = BonCommande::create([
                'date_bon_cmd' => $request->date_bon_cmd,
                'statut' => 'En attente',
                'user_id' => Auth::user()->id,
                // 'reference' => date('dmY') . '-' . $lettres . '-B' . ($nbr + 1),
                'reference' => 'KAD-' . 'PROG' . ($nbr + 1) . '-' . date('dmY') . '-' . $lettres,

            ]);

            if ($request->qte_cdes && count($request->qte_cdes) > 0) {
                $count = count($request->qte_cdes);
                for ($i = 0; $i < $count; $i++) {
                    $ligne = LigneBonCommande::create([
                        'qte_cmde' => $request->qte_cdes[$i],
                        'article_id' => $request->articles[$i],
                        'unite_mesure_id' => $request->unites[$i],
                        'bon_commande_id' => $bon->id,
                    ]);
                }
            } else {
                DB::rollback();
                return redirect()->route('bon-commandes.create')->with('error', 'Erreur enregistrement Bon de commande.');
            }


            DB::commit();
            return redirect()->route('bon-commandes.index')->with('success', 'Bon de commande enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('bon-commandes.index')->with('error', 'Erreur enregistrement Bon de commande.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bon = BonCommande::find($id);
        $nombre_commande = Commande::where('bon_commande_id', $id)->count();

        $lignes =  DB::table('ligne_bon_commandes')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_bon_commandes.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'ligne_bon_commandes.article_id')
            ->where('ligne_bon_commandes.bon_commande_id', $id)
            ->select('ligne_bon_commandes.*', 'unite_mesures.unite', 'articles.nom')
            ->get();

        $i = 1;

        return view('pages.achats-module.bon-commandes.show', compact('lignes', 'bon', 'i', 'nombre_commande'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bon = BonCommande::find($id);

        $lignes = DB::table('ligne_bon_commandes')
            ->join('unite_mesures', 'unite_mesures.id', '=', 'ligne_bon_commandes.unite_mesure_id')
            ->join('articles', 'articles.id', '=', 'ligne_bon_commandes.article_id')
            ->join('bon_commandes', 'bon_commandes.id', '=', 'ligne_bon_commandes.bon_commande_id')
            ->where('ligne_bon_commandes.bon_commande_id', $id)
            ->select(
                'ligne_bon_commandes.*',
                'unite_mesures.unite',
                'articles.nom',
                'articles.id as article_id',
                'bon_commandes.date_bon_cmd'
            )
            ->get();

        $articles =  Article::all();
        $unites = UniteMesure::all();

        return view('pages.achats-module.bon-commandes.edit', compact('bon', 'lignes', 'articles', 'unites'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'date_bon_cmd' => 'required',
            'qte_cdes.*' => 'required',
            'articles.*' => 'required',
            'unites.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // dd($request->all());
        $bon = BonCommande::find($id);
        // $bon->validator_id = Auth::id();
        // $bon->validated_at = now();
        // $bon->save();
        $count = count($request->qte_cdes);
        for ($i = 0; $i < $count; $i++) {
            LigneBonCommande::updateOrCreate(
                [
                    'bon_commande_id' => $bon->id,
                    'article_id' => $request->articles[$i],
                ],
                [
                    'qte_cmde' => $request->qte_cdes[$i],
                    'unite_mesure_id' => $request->unites[$i],
                ]
            );
        }

        return redirect()->route('bon-commandes.index')->with('success', 'Bon de commande modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bon = BonCommande::find($id);
        $bon->delete();
        return redirect()->route('bon-commandes.index')->with('success', 'Bon de commande supprimé avec succès.');
    }

    public function listUnites()
    {
        $unites = UniteMesure::get();

        return response()->json([
            'unites'  => $unites
        ]);
    }

    public function valider($id)
    {
        $bon = BonCommande::find($id);
        $bon->statut = 'Valide';
        $bon->valideur_id = Auth::user()->id;
        $bon->save();
        return response()->json(['redirectUrl' => route('bon-commandes.index')]);
    }

    public function cancelValider($id)
    {
        $bon = BonCommande::find($id);
        $bon->statut = 'En attente';
        $bon->valideur_id = null;
        $bon->save();
        return response()->json(['redirectUrl' => route('bon-commandes.index')]);
    }

    public function generatePDF($id)
    {
        $bcde = Commande::with(['BonCommande', 'Fournisseur'])->where('bon_commande_id', $id)->first();

        $pdf = new PDF_MC_Table();
        $pdf->AliasNbPages();  // To use the total number of pages
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Image("assets/img/logo.jpeg", 150, 10, 50, 30);
        $pdf->Image("assets/img/head_facture.jpg", 10, 10, 70, 30);

        $pdf->SetFont('', 'B', 10);
        $pdf->Text(150, 42, 'Cotonou, le ' . date("d m Y"));

        $pdf->SetFont('', 'B', 12);
        // $pdf->Text(10, 55, 'FOURNISSEUR');
        $pdf->SetFont('', 'B', 12);
        $pdf->Text(10, 62, utf8_decode('FOURNISSEUR : ' . $bcde->Fournisseur->name));
        $pdf->SetFont('', 'B', 12);
        $pdf->Text(10, 69, utf8_decode('OBJET : '));

        // $pdf->Text(13, 80, 'Client : '.$devis->client->nom_client);
        $pdf->SetXY(10, 73);
        $pdf->MultiCell(190, 15, utf8_decode('BON DE COMMANDE : ' . $bcde->BonCommande->reference), '', 'C');

        $pdf->SetXY(10, 85);
        $pdf->SetFont('', 'B', 12);
        $pdf->SetWidths(array(100, 20, 30, 40));
        $pdf->SetAligns(array('L', 'C', 'R', 'R'));
        $pdf->Row(array(utf8_decode('Désignation'), utf8_decode('Quantité'), utf8_decode('PU'), utf8_decode('Montant')));

        $ligne_commandes = DB::table('ligne_commandes')
            ->join('articles', 'articles.id', '=', 'ligne_commandes.article_id')
            ->where('ligne_commandes.commande_id', $bcde->id)
            ->select('*')
            ->get();
        $tot_ht = 0;
        foreach ($ligne_commandes as $ligne_commande) {
            // $art = Article::find($one_cde->article_id)->get();
            $pdf->Row(array($ligne_commande->nom, $ligne_commande->qte_cmde, number_format($ligne_commande->prix_unit, 2, ',', ' '), number_format($ligne_commande->qte_cmde * $ligne_commande->prix_unit, 2, ',', ' ')));
            $tot_ht += $ligne_commande->qte_cmde * $ligne_commande->prix_unit;
        }

        $pdf->SetWidths(array(150, 40));
        $pdf->SetAligns(array('C', 'R'));
        $pdf->Row(array('TOTAL', number_format($tot_ht, 2, ',', ' ')));

        $lettre = new ChiffreEnLettre;
        $prix_lettre = $lettre->Conversion($tot_ht);

        $pdf->SetFont('', 'B', 10);
        $pdf->CheckPageBreak(10);
        $pdf->Text($pdf->GetX(), $pdf->GetY() + 10, utf8_decode('Arrêté le présent bon de commande à la somme de : ' . $prix_lettre));

        $pdf->CheckPageBreak(45);
        $pdf->SetFont('', 'B', 10);
        $pdf->Text($pdf->GetX() + 150, $pdf->GetY() + 45, utf8_decode('LA DIRECTRICE'));
        $pdf->Text($pdf->GetX() + 142, $pdf->GetY() + 75, utf8_decode('Kadidjatou A. DJAOUGA'));

        // Générer le nom de fichier unique pour le PDF
        $fileName = uniqid('Bon_Commande_', true) . '.pdf';

        // Stocker le PDF dans le système de fichiers temporaire
        // $tempFilePath = storage_path('app/temp/' . $fileName);
        if ($pdf->Output('I', $fileName)) {

            // return redirect()->route('devis.index')->with('success', 'Proforma enregistré avec succès.');
        }
    }
}
