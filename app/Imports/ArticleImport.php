<?php

namespace App\Imports;

use App\Models\Article;
use App\Models\ArticlePointVente;
use App\Models\Categorie;
use App\Models\Magasin;
use App\Models\PointVente;
use App\Models\StockMagasin;
use App\Models\UniteMesure;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;

class ArticleImport implements ToModel, WithHeadingRow, WithEvents
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (count(Article::all()) == 0) {
            $nbr = 0;
        } else {
            $nbr = Article::latest()->first()->id;
        }

        $categorie = Categorie::find($row['cat']);
        if (!is_null($categorie)) {
            $code = 'ART' . premiereLettre($categorie->libelle) . derniereLettre($categorie->libelle) . '-' . ($nbr + 1);
        } else {
            $code = 'ART' . ($nbr + 1);
        }

        $unite = UniteMesure::where('unite', $row['unite_de_base'])->first();
        if (is_null($unite)) {
            $unite = new UniteMesure([
                'unite' => $row['unite_de_base'],
            ]);
            $unite->save();
        }



        $article = Article::create([
            'nom' => $row['nomproduit'],
            'stock_alert' => $row['alerte'],
            'unite_mesure_id' => $unite->id,
            'categorie_id' => $categorie->id,
        ]);



        return  $article;
    }




    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                $magasins = Magasin::all();
                $articles = Article::all();

                foreach ($magasins as $magasin) {
                    $point = $magasin->pointVente;

                    foreach ($articles as $article) {

                        StockMagasin::create([
                            'article_id' => $article->id,
                            'magasin_id' => $magasin->id,
                        ]);

                        ArticlePointVente::updateOrCreate([
                            'article_id' => $article->id
                        ], [
                            'point_vente_id' => $point->id
                        ]);
                    }
                }
            },

        ];
    }
}
