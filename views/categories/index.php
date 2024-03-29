<?php

/** @var yii\web\View $this */

use app\widgets\CategoryWidget;
use yii\helpers\Html;
use yii\widgets\ListView;

?>

<section class="categories-list">
  <h1 class="visually-hidden">Сервис объявлений "Куплю - продам"</h1>
  <?= count($offerCategories) ? CategoryWidget::widget(['offerCategories' => $offerCategories, 'contextId' => $this->context->id]) : '' ?>
</section>
<section class="tickets-list">
  <h2 class="visually-hidden">Предложения из категории <?= ($category && isset($category->category_name)) ? Html::encode($category->category_name) : '' ?></h2>
  <div class="tickets-list__wrapper">
    <div class="tickets-list__header">
      <p class="tickets-list__title"><?= ($category && isset($category->category_name)) ? Html::encode($category->category_name) : '' ?> <b class="js-qty"><?= $countOffers ? Html::encode($countOffers) : '' ?></b></p>
    </div>

    <?= ListView::widget(
      [
        'dataProvider' => $dataProvider,
        'itemView' => '_offers',
        'layout' => "<ul>{items}</ul>\n<div class='tickets-list__pagination'>{pager}</div>",
        'summary' => false,
        'emptyText' => 'Объявления отсутствуют',
        'emptyTextOptions' => [
          'tag' => 'p',
          'class' => 'pagination',
        ],
        'itemOptions' => [
          'tag' => 'li',
          'class' => 'tickets-list__item',
        ],
        'pager' => [
          // Подключение кастомного CategoryPager вместо yii\widgets\LinkPager из "коробки"
          'class' => 'app\widgets\CategoryPager',
          'prevPageLabel' => false,
          'nextPageLabel' => 'дальше',
          'disableCurrentPageButton' => true,
          'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'active'],
          'options' => [
            'tag' => 'ul',
            'class' => 'pagination',
          ],
        ],
      ]
    ) ?>
  </div>
</section>
