<?php

namespace App\Http\Controllers;
// namespace Codedge\Fpdf\Fpdf;

use App\Models\Commande;
use App\Models\Devis;
use App\Models\DevisDetail;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\PDF_MC_Table;
use Codedge\Fpdf\Fpdf\ChiffreEnLettre;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BonLivraisonController extends Controller
{
    public function generatePDF($id){
        $bcde = Commande::with(['BonCommande', 'Fournisseur'])->where('bon_commande_id', $id)->first();

        $pdf = new PDF_MC_Table();
        $pdf->AliasNbPages();  // To use the total number of pages
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Image("assets/img/logo.jpeg", 150, 10, 50, 30);
        $pdf->Image("assets/img/head_facture.jpg", 10, 10, 70, 30);

        $pdf->SetFont('', 'B', 10);
        $pdf->Text(150, 42, 'Cotonou, le '. date("d m Y"));

        $pdf->SetFont('', 'B', 12);
        // $pdf->Text(10, 55, 'FOURNISSEUR');
        $pdf->SetFont('', 'B', 12);
        $pdf->setXY(10,45);
        $pdf->MultiCell(190, 15, utf8_decode('BORDERAU DE LIVRAISON N° : '), '', 'C');
        $pdf->Text(10, 62, utf8_decode('DESTINATION : '));
        $pdf->SetFont('', 'B', 12);
        $pdf->Text(10, 69, utf8_decode('CLIENT : '));
        
        $pdf->SetXY(10, 75);
        $pdf->SetFont('', 'B', 12);
        $pdf->SetWidths(array(120, 35, 35));
        $pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'R'));
        $pdf->Row(array(utf8_decode('DESIGNATION'), utf8_decode('TONNAGE'), utf8_decode('DETAILS')));

        $ligne_commandes = DB::table('ligne_commandes')
        ->join('articles', 'articles.id', '=', 'ligne_commandes.article_id')
        ->where('ligne_commandes.commande_id', $bcde->id)
        ->select('*')
        ->get();
        $tot_ht = 0;
        foreach($ligne_commandes AS $ligne_commande){
            // $art = Article::find($one_cde->article_id)->get();
            $pdf->Row(array($ligne_commande->nom, $ligne_commande->qte_cmde, $ligne_commande->prix_unit));
            $tot_ht += $ligne_commande->qte_cmde*$ligne_commande->prix_unit;
        }

        $lettre = new ChiffreEnLettre;
        $prix_lettre = $lettre->Conversion($tot_ht);

        $pdf->SetFont('', 'BU', 10);
        $pdf->CheckPageBreak(15);
        $pdf->Text($pdf->GetX(), $pdf->GetY()+10, utf8_decode('LIVREUR'));
        $pdf->Text($pdf->GetX()+80, $pdf->GetY()+10, utf8_decode('CHAUFFEUR'));
        $pdf->Text($pdf->GetX()+150, $pdf->GetY()+10, utf8_decode('RECEPTIONNAIRE'));

        // Générer le nom de fichier unique pour le PDF
        $fileName = uniqid('Bon_de_de_livraison_', true) . '.pdf';

        // Stocker le PDF dans le système de fichiers temporaire
        // $tempFilePath = storage_path('app/temp/' . $fileName);
        if($pdf->Output('I', $fileName)){
        
        // return redirect()->route('devis.index')->with('success', 'Proforma enregistré avec succès.');
        }
    }
}
