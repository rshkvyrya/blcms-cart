<?php
namespace bl\cms\cart\frontend\components\user\controllers;
use dektrium\user\controllers\SecurityController as MainController;
use bl\cms\cart\common\components\user\models\LoginForm;
use yii\helpers\Url;
use yii\web\Response;


/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class SecurityController extends MainController
{
    /**
     * Displays the login page.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = \Yii::createObject(LoginForm::className());
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);
        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            $this->trigger(self::EVENT_AFTER_LOGIN, $event);

            //Checking return url
            if (!empty($model->returnUrl)) {
                $host = \Yii::$app->request->hostInfo;
                $returnUrlHost = substr($model->returnUrl, 0, strlen($host));
                if ($returnUrlHost == $host) {
                    $returnUrl = $model->returnUrl;
                }
                else {
                    $returnUrl = \Yii::$app->user->returnUrl;
                }
            }
            else {
                $returnUrl = Url::to([\Yii::$app->user->returnUrl]);
            }

            return \Yii::$app->getResponse()->redirect($returnUrl);
        }

        return $this->render('login', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }
}