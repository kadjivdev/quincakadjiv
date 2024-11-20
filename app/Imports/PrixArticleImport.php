<?php

namespace App\Imports;

use App\Models\Article;
use App\Models\ArticlePointVente;
use App\Models\PointVente;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PrixArticleImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $article = Article::where('nom', $row['nomproduit'])->first();
        $points = PointVente::all();

        foreach ($points as $point) {
           $pointArticle = ArticlePointVente::updateOrCreate(
                [
                    'article_id' => $article->id,
                    'point_vente_id' => $point->id,
                ],
                [
                    'prix_special' => $row['prix_special'],
                    'prix_revendeur' => $row['prix_gros'],
                    'prix_particulier' => $row['prix_particulier'],
                    'prix_btp' => $row['prix_btp'],
                ]
            );
        }

        return $pointArticle;

    }
}
