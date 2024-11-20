<?php

namespace App\Imports;

use App\Models\Article;
use App\Models\ArticlePointVente;
use App\Models\Magasin;
use App\Models\PointVente;
use App\Models\StockMagasin;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PointVenteStockImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $article = Article::where('nom', 'LIKE', '%' . $row['nomproduit'] . '%')->first();
        $magasin = Magasin::where('nom', 'LIKE', '%' . $row['magasin'] . '%')->first();
        $article_magasin = StockMagasin::where('article_id', $article->id)->where('magasin_id', $magasin->id)->first();
        // $magasin = Magasin::where('nom', $row['magasin'])->first();
        $point = $magasin->pointVente;

        if ($article_magasin) {
            $article_magasin->update([
                'qte_stock' => $row['qte_stock'],
            ]);
            $articlePointVente = ArticlePointVente::where('article_id', $article->id)
                ->where('point_vente_id', $point->id)
                ->first();

            if ($articlePointVente) {
                $qteStockForPoint = $articlePointVente->qte_stock;
                $articlePointVente->qte_stock += $qteStockForPoint;
                $articlePointVente->save();
            } else {
                ArticlePointVente::create([
                    'article_id' => $article->id,
                    'point_vente_id' => $point->id,
                    'qte_stock' =>  (float)$row['qte_stock'],
                ]);
            }
        }

        return $article_magasin;
    }
}
