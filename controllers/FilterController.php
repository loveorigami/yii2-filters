<?php

/**
 * Filters.
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Filter;
use app\models\Product;
use app\models\ProductFilterLink;
use yii\helpers\Json;

class FilterController extends Controller
{
    /**
     * Showing filters.
     */
    public function actionIndex()
    {
        $filters = Filter::find()->all();

        return $this->render('index', ['filters' => $filters]);
    }

    /**
     * Getting count of products related to checked filters.
     * Getting list of filters related to fetched products
     * (To disable filters that are not related to these products).
     */
    public function actionGet()
    {
        $allowed = [];

        if (isset($_POST['filters'])) {

            $filters = $_POST['filters'];
            foreach ($filters as $key => $filter) {
                if (!ctype_digit($filter)) {
                    unset($filters[$key]);
                }
            }

            // Getting product IDs.
            $countFilters = count($filters);
            $subQuery = (new \yii\db\Query)
                ->select('productId, COUNT(filterId) as checkSum')
                ->from(ProductFilterLink::tableName())
                ->where([
                    'filterId' => $filters,
                    'status'   => ProductFilterLink::STATUS_ACTIVE,
                ])
                ->groupBy('productId');
            $count = (new \yii\db\Query)
                ->select('COUNT(productId)')
                ->from([$subQuery])
                ->where([
                    'checkSum' => $countFilters,
                ])
                ->scalar();

            // Counting products.
            $count = $count ? : 0;

            if ($count) {
                // Getting related filters.
                $allowedFilters = (new \yii\db\Query)
                    ->select('DISTINCT(link.filterId)')
                    ->from([$subQuery, 'link' => ProductFilterLink::tableName()])
                    ->where(['`0`.checkSum' => $countFilters])
                    ->andWhere('link.productId=`0`.productId')
                    ->andWhere(['not in', 'filterId', $filters])
                    ->column();
                if ($allowedFilters) {
                    foreach ($allowedFilters as $filter) {
                        $allowed[$filter] = true;
                    }
                }
            }
        } else {
            $count = Product::find()->count();
        }

        $json = array('count' => $count);
        if ($allowed) {
            $json['filters'] = $allowed;
        }

        return Json::encode($json);
    }
}
