<?php

/** @var yii\web\View $this */
/** @var string $content */

//use Yii;
use app\assets\AppAsset;
use \yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\OfferSearchWidget;
use app\models\forms\OfferSearchForm;

AppAsset::register($this);

//$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
$this->registerMetaTag(['name' => 'description', 'content' => 'Доска объявлений — современный веб-сайт, упрощающий продажу или покупку абсолютно любых вещей.']);
$this->registerMetaTag(['http-equiv' => 'X-UA-Compatible', 'content' => 'ie=edge']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/img/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <title>Куплю Продам</title>
  <?php $this->head() ?>
</head>

<body>

  <?php $this->beginBody() ?>
  <header class="header <?= !Yii::$app->user->isGuest ? 'header--logged' : '' ?>">
    <div class="header__wrapper">
      <a class="header__logo logo" href="<?= Url::to(['/site']) ?>">
        <img src="/img/logo.svg" width="179" height="34" alt="Логотип Куплю Продам">
      </a>
      <nav class="header__user-menu">
        <ul class="header__list">
          <li class="header__item">
            <a href="<?= Url::to(['/site']) ?>">Публикации</a><!-- TO DO Поменять ссылку, когда будет готова MyTicket -->
          </li>
          <li class="header__item">
            <a href="<?= Url::to(['comments/index/' . Yii::$app->user->id]) ?>">Комментарии</a>
          </li>
        </ul>
      </nav>
      <?=/* OfferSearchWidget::widget(['model' =>'search' ?: null])*/ '' ?>
      <?php
      $model = new OfferSearchForm();

      if (isset($this->params['query'])) {
        $model->autocompleteForm($model, $this->params['query']);
      }

      $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['site/search'],
        'options' => [
          'class' => 'search',
          'autocomplete' => 'off',
        ],
      ]); ?>
      <?= $form->field($model, 'search')->input([/*'class' => 'visually-hidden js-file-field', */'placeholder' => 'Поиск'])->label(false) ?>
      <div class="search__icon"></div>
      <div class="search__close-btn"></div>
      <?php ActiveForm::end();
      ?>

      <!--
      <form class="search" method="get" action="<?= Url::to(['site/search']); ?>" autocomplete="off">
        <input type="search" name="query" placeholder="Поиск" aria-label="Поиск">
        <div class="search__icon"></div>
        <div class="search__close-btn"></div>
      </form>
      -->
      <a class="<?= 'header__avatar avatar' ?>" href="#">
        <?php if (!Yii::$app->user->isGuest) : ?>
          <img src="<?= Yii::$app->user->identity->avatar ? Html::encode('/uploads/avatars/' . Yii::$app->user->identity->avatar) : '/img/avatar.jpg' ?>" srcset="<?= Yii::$app->user->identity->avatar ? '' : '/img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
        <?php endif; ?>
      </a>
      <a class="header__input" href="<?= Url::to(['/registration']) ?>">Вход и регистрация</a>
    </div>
  </header>

  <main class="page-content">
    <?= $content ?>
  </main>

  <footer class="page-footer">
    <div class="page-footer__wrapper">
      <div class="page-footer__col">
        <a href="#" class="page-footer__logo-academy" aria-label="Ссылка на сайт HTML-Академии">
          <svg width="132" height="46">
            <use xlink:href="img/sprite_auto.svg#logo-htmlac"></use>
          </svg>
        </a>
        <p class="page-footer__copyright">© 2019 Проект Академии</p>
      </div>
      <div class="page-footer__col">
        <a href="<?= Url::to(['site/index']) ?>" class="page-footer__logo logo">
          <img src="/img/logo.svg" width="179" height="35" alt="Логотип Куплю Продам">
        </a>
      </div>
      <div class="page-footer__col">
        <ul class="page-footer__nav">
          <?php if (Yii::$app->user->isGuest) : ?>
            <li>
              <a href="<?= Url::to(['/registration']) ?>">Вход и регистрация</a>
            </li>
          <?php else : ?>
            <li>
              <a href="<?= Url::to(['site/logout']) ?>">Выход</a>
            </li>
            <li>
              <a href="<?= Url::to(['offers/add']) ?>">Создать объявление</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </footer>

  <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
