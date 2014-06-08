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
            $productIds = ProductFilterLink::find()
                ->select('productId')
                ->where([
                    'filterId' => $filters,
                    'status'   => ProductFilterLink::STATUS_ACTIVE,
                ])
                ->groupBy('productId')
                ->having(sprintf('COUNT(DISTINCT `filterId`)=%d', count($filters)))
                ->column();

            // Counting products.
            $count = $productIds ? count($productIds) : 0;

            if ($productIds) {
                sort($productIds);

                // Getting related filters.
                $allowedFilters = ProductFilterLink::find()
                    ->select('DISTINCT(filterId)')
                    ->where(['productId' => $productIds])
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
