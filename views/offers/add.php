<?php

use app\models\Category;
use app\models\Offer;
use app\models\forms\OfferAddForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use \yii\helpers\Html;

/** @var yii\web\View $this */
/** @var OfferAddForm $offerAddForm */

?>

<section class="ticket-form">
  <div class="ticket-form__wrapper">
    <h1 class="ticket-form__title"><?= $ticketFormTitle ?></h1>
    <div class="ticket-form__tile">

      <?php $form = ActiveForm::begin([
        'id' => 'offer-add-form',
        'method' => 'post',
        'options' => [
          'class' => 'ticket-form__form form',
          'enctype' => 'multipart/form-data',
          'autocomplete' => 'off',
        ]
      ]); ?>
      <div class="ticket-form__avatar-container js-preview-container <?= $offerAddForm->offerImage ? 'uploaded' : '' ?>">
        <div class="ticket-form__avatar js-preview">
          <img src="<?= $offerAddForm->offerImage ? Html::encode(Offer::OFFER_IMAGE_UPLOAD_PATH . $offerAddForm->offerImage) : '' ?>" srcset="" alt="">
        </div>
        <div class="ticket-form__field-avatar">
          <?= $form->field($offerAddForm, 'offerImage')->fileInput(['class' => 'visually-hidden js-file-field', 'placeholder' => 'Загрузить фото…'])->label('<span class="ticket-form__text-upload">Загрузить фото…</span><span class="ticket-form__text-another">Загрузить другое фото…</span>') ?>
        </div>
      </div>
      <div class="ticket-form__content">
        <div class="ticket-form__row">
          <div class="form__field">
            <?= $form->field($offerAddForm, 'offerTitle')->textInput(['options' => ['class' => 'js-field']])->label('Название') ?>
            <span>Обязательное поле</span>
          </div>
        </div>
        <div class="ticket-form__row">
          <div class="form__field">
            <?= $form->field($offerAddForm, 'offerText')->textarea(['cols' => 30, 'rows' => 10, 'options' => ['class' => 'js-field']])->label('Описание') ?>
            <span>Обязательное поле</span>
          </div>
        </div>
        <div class="ticket-form__row">
          <?= $form->field($offerAddForm, 'categories')->dropDownList(ArrayHelper::map(
            Category::find()->all(),
            'category_id',
            'category_name'
          ), ['class' => 'form__select js-multiple-select', 'placeholder' => "Выбрать категорию публикации", 'multiple' => true])->label(false); ?>
        </div>
        <div class="ticket-form__row">
          <div class="form__field form__field--price">
            <?= $form->field($offerAddForm, 'offerPrice')->input('number', ['options' => ['class' => 'js-field', 'min' => 1]])->label('Цена') ?>
            <span>Обязательное поле</span>
          </div>
          <div class="form__switch switch">
            <?= $form->field($offerAddForm, 'offerType')->radioList(
              [
                Offer::OFFER_TYPE['buy'] => 'Куплю',
                Offer::OFFER_TYPE['sell'] => 'Продам'
              ],
              [
                'class' => 'form__switch switch',
                'item' => function ($index, $label, $name, $checked, $value) {
                  return

                    Html::beginTag('div', ['class' => 'switch__item']) .
                    Html::radio($name, $checked, ['value' => $value, 'id' => $index, 'class' => 'visually-hidden']) .
                    Html::label($label, $index, ['class' => 'switch__button']) .
                    Html::endTag('div');
                }
              ]

            )->label(false) ?>
          </div>
        </div>
      </div>

      <button class="form__button btn btn--medium js-button" type="submit">Опубликовать</button>
      <?php ActiveForm::end(); ?>

    </div>
  </div>
</section>
