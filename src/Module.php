<?php
/**
 * @package yii2-google-api-client
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\google;

use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use Yii;
use yii\helpers\Url;

/**
 * Google api client module
 *
 * ```php
 * [
 *     'modules' => [
 *         'google' => [
 *             'class' => 'simialbi\yii2\google\Module',
 *             'components' => [
 *                 'client' => [
 *                     'class' => 'simialbi\yii2\google\components\GoogleApiClient',
 *                     'credentials' => '{"client_id":"xxxxxxx.apps.googleusercontent.com"[...]}',
 *                     // 'scopes' => [],
 *                     // 'redirectUri' => '',
 *                     // 'includeGrantedScopes' => true
 *                 ]
 *             ],
 *             'serviceConfiguration' => [
 *                 'analytics' => []
 *             ]
 *         ]
 *     ]
 * ]
 * ```
 *
 * @property-read \simialbi\yii2\google\components\GoogleApiClient $client
 */
class Module extends \simialbi\yii2\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'simialbi\yii2\google\controllers';

    /**
     * @var array
     */
    public $serviceConfiguration = [];

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!$this->has('client')) {
            throw new InvalidConfigException(Yii::t('simialbi/google/notifications', 'The "client" component is mandatory'));
        }
        if (!isset($this->client->redirectUri)) {
            $this->client->redirectUri = Url::to([$this->id.'/auth/oauth-callback'], Yii::$app->request->isSecureConnection ? 'https' : 'http');
        }

        $this->registerTranslations();
    }

    /**
     * Returns the google api client component.
     * @return \simialbi\yii2\google\components\GoogleApiClient
     * @throws InvalidConfigException
     */
    public function getClient()
    {
        $client = $this->get('client');
        /* @var \simialbi\yii2\google\components\GoogleApiClient $client */

        return $client;
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'simialbi\yii2\google\commands';
        }
    }
}