<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Users;
use yii\web\UploadedFile;

use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Users();
        $model->scenario = Users::SCENARIO_SIGNUP;
        


        if($model->load(\Yii::$app->request->post())){

            $username = \Yii::$app->request->post()['Users']['username'];
            $model->pass_hash = \Yii::$app->security->generatePasswordHash($model->password);

            if ($model->validate() && $model->save()) {

                $model->image = 'assets\avatar_ex.png';
                $model->save();
               
                $identity = Users::findOne(['username' => $username]);
                Yii::$app->user->login($identity);

                return $this->goHome();
            }
        } 

        return $this->render('register', compact('model'));
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Users();
        $model->scenario = Users::SCENARIO_LOGIN;

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }

        $model->password = null;
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    /**
     * Displays settings page 
     * 
     * @return string
     */

    public function actionSettings()
    {
        $user = Users::findOne(['username' => \Yii::$app->user->identity->username]);
        $user->scenario = Users::SCENARIO_CHANGE;

        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post()['Users']; 
            $user->imageFile = UploadedFile::getInstance($user, "imageFile");
            $user->username = trim($post["username"]);
            $user->about = trim($post["about"]);
            if($user->validate()) {

                
                $user->save();
                Yii::$app->user->identity->username = $user->username;
                return $this->render('profile', [
                    'model' => $user,
                ]);
            } else {
                return $this->render('settings', [
                    'model' => $user,
                    'message' => 'Сохранение не удалось с ошибкой:' . $user->error,
                ]);
            }
        }

        return $this->render('settings', [
            'model' => $user,
        ]);
    }

    public function actionProfile()
    {
        $profile = Users::findOne(['username' => \Yii::$app->user->identity->username]);

        return $this->render('profile', [
            'model' => $profile,
        ]);
    }
}
