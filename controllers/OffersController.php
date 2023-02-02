<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\ChatFirebase;
use app\models\Offer;
use app\models\User;
use app\models\forms\OfferAddForm;
use app\models\forms\ChatForm;
use app\models\forms\CommentAddForm;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class OffersController extends Controller
{
  /**
   * Страница просмотра объявления
   *
   * @param int $id - id объявления
   * @param int|null $buyerId - id покупателя, null - если страница продавца
   * @return Response|string - код страницы просмотра объявления
   * @throws NotFoundHttpException
   */
  public function actionIndex(int $id, int $buyerId = null): Response|string
  {
    $offer = Offer::find()
      ->with('owner', 'categories', 'comments')
      ->where(['offer_id' => $id])
      ->one();

    if (!$offer) {
      throw new NotFoundHttpException();
    }

    $owner = $offer->owner;
    $categories = $offer->categories;
    $comments = $offer->comments;

    ArrayHelper::multisort($comments, ['comment_id'], [SORT_DESC]);

    $commentAddForm = new CommentAddForm();

    // Добавление нового комментария. Доступно только зарегистрированным пользователям.
    if (!Yii::$app->user->isGuest && $commentAddForm->load(Yii::$app->request->post())) {
      if ($commentAddForm->addComment($id)) {
        return $this->redirect(['offers/index', 'id' => $id]);
      }
    }

    $chatForm = new ChatForm();

    $buyers = null;

    // По умолчанию, адресат сообщения - владелец объявления. Это значит, что страница покупателя.
    $addressee = $owner;
    $dataProvider = null;
    // Если пользователь - владелец объявления
    if (\Yii::$app->user->can('updateOwnContent', ['resource' => $offer])) {
      // Если страница продавца, то адресат сообщения - покупатель с id = $buyerId
      if ($buyerId) {
        $addressee = User::findOne($buyerId);
      }
      // Выборка всех сообщений объявления с данным id
      $firebase = new ChatFirebase($id);
      $firebaseChats = $firebase->getValueChat();

      $userIds = [];
      if ($firebaseChats) {
        foreach ($firebaseChats as $key => $value) {
          $userIds[] = $key;
        }
      }

      $buyers = User::find()
        ->having(['in', 'user_id', $userIds])
        ->all();

      $query = User::find()
        ->having(['in', 'user_id', $userIds]);

      $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
          'pageSize' => 1,
        ],
      ]);
    }

    if (!\Yii::$app->user->can('updateOwnContent', ['resource' => $offer])) {
      $buyerId = \Yii::$app->user->id;
    }

    $messages = false;
    $chatFirebase = false;

    // Выборка всех сообщений покупателя с id = $buyerId объявления с данным id
    if ($buyerId) {
      $chatFirebase = new ChatFirebase($id, $buyerId);

      $messages = $chatFirebase->getValueChat();
      if ($messages) {
        foreach ($messages as $key => $message) {
          if ($message['fromUserId'] !== Yii::$app->user->id) {
            $chatFirebase->readMessage($key);
          }
        }
      }
    }

    // Добавление нового cообщения в чат. Доступно только зарегистрированным пользователям при наличии заполненного поля ввода сообщения.
    if (\Yii::$app->user->id !== $addressee->user_id && $chatFirebase && $chatForm->load(Yii::$app->request->post()) && !Yii::$app->user->isGuest && Yii::$app->request->isAjax) {

      $send = $chatForm->addMessage($addressee, $chatFirebase);
      $messages = $chatFirebase->getValueChat();

      //обнуляем модель, чтобы очистить форму
      $chatForm = new ChatForm();
    }
    return $this->render('index', compact('offer', 'owner', 'categories', 'comments', 'commentAddForm', 'chatForm', 'messages', 'buyers', 'buyerId', 'addressee', 'dataProvider'));
  }

  /**
   * Страница с формой добавления объявления
   *
   * @return string - код страницы с формой создания задания
   */
  public function actionAdd()
  {
    $ticketFormTitle = 'Новая публикация';

    if (Yii::$app->user->isGuest) {
      return $this->goHome();
    }

    $offerAddForm = new OfferAddForm();

    if (Yii::$app->request->getIsPost()) {
      $offerAddForm->load(Yii::$app->request->post());
      $offerId = $offerAddForm->addOffer();

      if ($offerId) {
        return $this->redirect(['offers/index', 'id' => $offerId]);
      }
    }
    return $this->render('add', compact('offerAddForm', 'ticketFormTitle'));
  }

  /**
   * Страница с формой редактирования объявления
   *
   * @param int $id - id объявления
   */
  public function actionEdit($id)
  {
    $offer = Offer::find()
      ->with('owner')
      ->where(['offer_id' => $id])
      ->one();

    // Если пользователь не обладает правом редактирования объявления (не модератор и не автор объявления),
    // то он переадресуется на страницу просмотра объявления
    if (!\Yii::$app->user->can('updateOwnContent', ['resource' => $offer])) {
      return $this->redirect(['offers/index', 'id' => $id]);
    }

    $ticketFormTitle = 'Редактировать публикацию';

    if (!$offer) {
      throw new NotFoundHttpException();
    }

    $offerAddForm = new OfferAddForm();
    $offerAddForm->autocompleteForm($offerAddForm, $offer);

    if (Yii::$app->request->getIsPost()) {
      $offerAddForm->load(Yii::$app->request->post());
      $offerId = $offerAddForm->addOffer($id);

      if ($offerId) {
        return $this->redirect(['offers/index', 'id' => $offerId]);
      }
    }
    return $this->render('add', compact('offerAddForm', 'ticketFormTitle'));
  }
}
