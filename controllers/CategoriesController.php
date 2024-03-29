<?php

namespace app\controllers;

use app\models\Category;
use app\src\service\OfferCategoryService;
use app\src\service\OfferService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class CategoriesController extends Controller
{
  /**
   * Страница просмотра объявлений соответствующей категории
   *
   * @param int $id - id категории
   */
  public function actionIndex(int $id)
  {
    $query = OfferService::getCategoryOffers($id);

    $countOffers = (int) $query->count();

    if (!$countOffers) {
      return $this->goHome();
    }

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => Yii::$app->params['pageSize'],
      ],
      'sort' => [
        'defaultOrder' => [
          'offer_date_create' => SORT_DESC,
        ]
      ],
    ]);

    // Категории для section class="tickets-list"
    $category = Category::findOne($id);

    // Категории для section class="categories-list"
    $offerCategories = OfferCategoryService::getOfferCategories();

    return $this->render('index', compact('offerCategories', 'dataProvider', 'category', 'countOffers'));
  }
}
