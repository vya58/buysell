<?php

/** @var yii\web\View $this */
/** @var string $content */

//use Yii;
use app\assets\AppAsset;
use \yii\helpers\Url;
use yii\helpers\Html;

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
<html lang="ru" class="<?= $this->params['htmlClass'] ?>">

<head>
  <title>Куплю Продам</title>
  <?php $this->head() ?>
</head>

<body class="<?= $this->params['bodyClass'] ?>">

  <?php $this->beginBody() ?>

  <main>
    <?= $content ?>
  </main>

  <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
