<?php

namespace App\Http\Controllers;
// namespace Codedge\Fpdf\Fpdf;

use App\Models\Devis;
use App\Models\DevisDetail;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\PDF_MC_Table;
use Codedge\Fpdf\Fpdf\ChiffreEnLettre;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProformaController extends Controller
{
    public function generatePDF($id)
    {
        $devis = Devis::with('Client')->find($id);
        $ligne_devis =  DB::table('devis_details')
        ->join('articles', 'articles.id', '=', 'devis_details.article_id')
        ->where('devis_details.devis_id', $devis->id)
        ->select('*')
        ->get();

        $pdf = new PDF_MC_Table();
        $pdf->AliasNbPages();  // To use the total number of pages
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Image("assets/img/logo.jpeg", 150, 10, 50, 30);
        $pdf->Image("assets/img/head_facture.jpg", 10, 10, 70, 30);

        $pdf->SetFont('', 'B', 10);
        $pdf->Text(150, 42, 'Cotonou, le '. date("d m Y"));

        $pdf->SetFont('', 'B', 12);
        $pdf->Text(10, 55, 'Facture Proforma');
        $pdf->Text(10, 62, utf8_decode('N° '.$devis->reference));
        $pdf->SetFont('', 'BU', 12);
        $pdf->Text(10, 69, utf8_decode('DESTINATION :'));
        $pdf->SetFont('', '', 12);
        $pdf->Text(45, 69, utf8_decode($devis->client->address));

        $pdf->Text(135, 80, 'Client : ');
        $pdf->SetFont('', 'B', 12);
        $pdf->Text(150, 80, $devis->client->nom_client);

        $pdf->SetXY(10, 85);
        $pdf->SetFont('', 'B', 12);
        $pdf->SetWidths(array(100, 20, 30, 40));
        $pdf->SetAligns(array('L', 'L', 'C', 'C', 'C', 'R'));
        $pdf->Row(array(utf8_decode('Désignation'), utf8_decode('Quantité en tonne'), utf8_decode('PU. HT (FCFA)'), utf8_decode('Montant HT (FCFA)')));

        $pdf->SetFont('', '', 12);
        $tot_ht = 0;
        foreach($ligne_devis AS $one_devis){
            $pdf->Row(array($one_devis->nom, $one_devis->qte_cmde, $one_devis->prix_unit, number_format($one_devis->qte_cmde*$one_devis->prix_unit, 2, ',', ' ')));
            $tot_ht += $one_devis->qte_cmde*$one_devis->prix_unit;
        }


        $real_tht = $tot_ht/1.19;
        $tva = $real_tht*0.18;
        $aib = $real_tht*0.01;
        $ttc = $tot_ht;
        $pdf->SetWidths(array(150, 40));
        $pdf->SetAligns(array('R', 'C'));
        $pdf->Row(array('TOTAL HT', number_format($real_tht, 2, ',', ' ')));

        $pdf->Row(array('TVA', number_format($tva, 2, ',', ' ')));
        $pdf->Row(array('AIB', number_format($aib, 2, ',', ' ')));
        $pdf->SetFont('', 'B', 12);
        $pdf->Row(array('TOTAL TTC', number_format($ttc, 2, ',', ' ') ));

        $lettre = new ChiffreEnLettre;
        $prix_lettre = $lettre->Conversion($tot_ht);

        $pdf->SetFont('', 'B', 8);
        $pdf->CheckPageBreak(10);
        $pdf->Text($pdf->GetX(), $pdf->GetY()+10, utf8_decode('Arrêté la présente facture sur la somme de : '.$prix_lettre));
        $pdf->CheckPageBreak(40);
        $pdf->Text($pdf->GetX()+30, $pdf->GetY()+30, utf8_decode('NB : Les marchandises livrées ne sont ni reprises ni échangées. Merci pour la compréhension '));
        $pdf->Text($pdf->GetX()+45, $pdf->GetY()+40, utf8_decode('KADJIV SARL vous remercie de votre passage et espère vous revoir bientôt'));
        
        $pdf->SetXY(0, $pdf->GetY()+40);
        $pdf->CheckPageBreak(55);
        $pdf->SetFont('', 'B', 10);
        $pdf->Text($pdf->GetX()+150, $pdf->GetY()+10, utf8_decode('Service Facturation'));
        $pdf->Image("assets/img/proforma_sign.jpg", $pdf->GetX()+120, $pdf->GetY()+15, 70, 30);

        // Générer le nom de fichier unique pour le PDF
        $fileName = uniqid('proforma_', true) . '.pdf';

        // Stocker le PDF dans le système de fichiers temporaire
        // $tempFilePath = storage_path('app/temp/' . $fileName);
        if($pdf->Output('I', $fileName)){
        
        // return redirect()->route('devis.index')->with('success', 'Proforma enregistré avec succès.');
        }
    }
}
