<?php
/**
 * @package yii2-google-api-client
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\google\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class AuthController
 *
 * @property-read \simialbi\yii2\google\Module $module
 */
class AuthController extends Controller
{
    /**
     * Redirect action
     *
     * @return \yii\web\Response
     */
    public function actionRedirect()
    {
        Url::remember(Yii::$app->request->referrer);

        return $this->redirect($this->module->client->authUrl);
    }

    /**
     * Oauth callback action
     *
     * @param string $code
     * @param string $error
     * @return \yii\web\Response
     * @throws ServerErrorHttpException
     */
    public function actionOauthCallback($code = null, $error = null)
    {
        if ($error) {
            throw new ServerErrorHttpException($error);
        }

        try {
            $this->module->client->setAuthToken($code);
        } catch (\Google_Exception $e) {
            throw new ServerErrorHttpException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->goBack();
    }
}