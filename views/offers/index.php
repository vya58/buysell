<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use \yii\helpers\Url;
use app\models\Offer;
use app\models\User;

?>

<section class="ticket">
  <div class="ticket__wrapper">
    <h1 class="visually-hidden">Карточка объявления</h1>
    <div class="ticket__content">
      <div class="ticket__img">
        <?php if ($offer->offer_image) : ?>
          <img src="<?= Html::encode(Offer::OFFER_IMAGE_UPLOAD_PATH . $offer->offer_image) ?>" alt="Изображение товара">
        <?php endif; ?>
      </div>
      <div class="ticket__info">
        <h2 class="ticket__title"><?= Html::encode($offer->offer_title) ?></h2>
        <div class="ticket__header">
          <p class="ticket__price"><span class="js-sum"><?= Html::encode($offer->offer_price) ?></span> ₽</p>
          <p class="ticket__action"><?= Html::encode($offer->offer_type) ?></p>
        </div>
        <div class="ticket__desc">
          <p><?= Html::encode($offer->offer_text) ?></p>
        </div>
        <div class="ticket__data">
          <p>
            <b>Дата добавления:</b>
            <span><?= Html::encode(Yii::$app->formatter->asDate($offer->offer_date_create, 'php:j F Y')) ?></span>
          </p>
          <p>
            <b>Автор:</b>
            <a href="<?= Url::to(['user/index', 'id' => $owner->user_id]) ?>"><?= Html::encode($owner->name) ?></a>
          </p>
          <p>
            <b>Контакты:</b>
            <a href="mailto:<?= Html::encode($owner->email) ?>"><?= Html::encode($owner->email) ?></a>
          </p>
        </div>
        <ul class="ticket__tags">
          <?php foreach ($categories as $category) : ?>
            <li>
              <a href="#" class="category-tile category-tile--small">
                <span class="category-tile__image">
                  <img src="<?= Html::encode('/web/img/' . $category->category_icon . '.jpg') ?>" srcset="<?= Html::encode('/web/img/' . $category->category_icon . '@2x.jpg') ?> 2x" alt="Иконка категории">
                </span>
                <span class="category-tile__label"><?= Html::encode($category->category_name) ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <div class="ticket__comments">
      <div class="ticket__warning">
        <?php if (Yii::$app->user->isGuest) : ?>
          <p>Отправка комментариев доступна <br>только для зарегистрированных пользователей.</p>
          <a href="<?= Url::to(['/registration/index']) ?>" class="message__link btn btn--big">Вход и регистрация</a>
        <?php endif; ?>
      </div>
      <h2 class="ticket__subtitle">Коментарии</h2>
      <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="ticket__comment-form">
          <form action="#" method="post" class="form comment-form">
            <div class="comment-form__header">
              <a href="#" class="comment-form__avatar avatar">
                <img src="<?= Yii::$app->user->identity->avatar ? Html::encode('/uploads/avatars/' . Yii::$app->user->identity->avatar) : '/img/avatar.jpg' ?>" srcset="<?= Yii::$app->user->identity->avatar ? '' : '/img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
              </a>
              <p class="comment-form__author">Вам слово</p>
            </div>
            <div class="comment-form__field">
              <div class="form__field">
                <textarea name="comment" id="comment-field" cols="30" rows="10" class="js-field">Нормальное вообще кресло! А как насч</textarea>
                <label for="comment-field">Текст комментария</label>
                <span>Обязательное поле</span>
              </div>
            </div>
            <button class="comment-form__button btn btn--white js-button" type="submit" disabled="">Отправить</button>
          </form>
        </div>
      <?php endif; ?>
      <?php if ($comments) : ?>
        <div class="ticket__comments-list">
          <ul class="comments-list">
            <?php foreach ($comments as $comment) : ?>
              <?php $user = User::findOne($comment->owner_id); ?>
              <li>
                <div class="comment-card">
                  <div class="comment-card__header">
                    <a href="#" class="comment-card__avatar avatar">
                      <img src="<?= $user->avatar ? Html::encode('/uploads/avatars/' . $user->avatar) : '/img/avatar.jpg' ?>" srcset="<?= $user->avatar ? '' : '/img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
                    </a>
                    <p class="comment-card__author"><?= Html::encode($user->name) ?></p>
                  </div>
                  <div class="comment-card__content">
                    <p><?= Html::encode($comment->comment_text) ?></p>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php else : ?>
        <div class="ticket__message">
          <p>У этой публикации еще нет ни одного комментария.</p>
        </div>
      <?php endif; ?>
    </div>
    <button class="chat-button" type="button" aria-label="Открыть окно чата"></button>
  </div>
</section>

<section class="chat visually-hidden">
  <h2 class="chat__subtitle">Чат с продавцом</h2>
  <ul class="chat__conversation">
    <li class="chat__message">
      <div class="chat__message-title">
        <span class="chat__message-author">Вы</span>
        <time class="chat__message-time" datetime="2021-11-18T21:15">21:15</time>
      </div>
      <div class="chat__message-content">
        <p>Добрый день!</p>
        <p>Какова ширина кресла? Из какого оно материала?</p>
      </div>
    </li>
    <li class="chat__message">
      <div class="chat__message-title">
        <span class="chat__message-author">Продавец</span>
        <time class="chat__message-time" datetime="2021-11-18T21:21">21:21</time>
      </div>
      <div class="chat__message-content">
        <p>Добрый день!</p>
        <p>Ширина кресла 59 см, это хлопковая ткань. кресло очень удобное, и почти новое, без сколов и прочих дефектов</p>
      </div>
    </li>
  </ul>
  <form class="chat__form">
    <label class="visually-hidden" for="chat-field">Ваше сообщение в чат</label>
    <textarea class="chat__form-message" name="chat-message" id="chat-field" placeholder="Ваше сообщение"></textarea>
    <button class="chat__form-button" type="submit" aria-label="Отправить сообщение в чат"></button>
  </form>
</section>
