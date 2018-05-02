<?php
/**
 * @package yii2-google-api-client
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\google\components;

use Google_Client;
use Google_Exception;
use yii\base\Component;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Google API Client component
 *
 * ```php
 * [
 *     'client' => [
 *         'credentials' => [
 *             'client_id' => 'xxxxxxx.apps.googleusercontent.com',
 *             'project_id' => 'my-project-id',
 *             'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
 *             'token_uri' => 'https://accounts.google.com/o/oauth2/token',
 *             'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
 *             'client_secret' => 'xxxxxxxxxxxxxxxxxxxxxxxx',
 *             'redirect_uris' => [
 *                 'https://exmaple.com/google/auth/oauth-callback'
 *             ]
 *         ]
 *     ]
 * ]
 * ```
 *
 * @package simialbi\yii2\google\components
 *
 * @property array $authToken
 *
 * @property-read Google_Client $api
 * @property-read string $authUrl
 */
class GoogleApiClient extends Component
{
    /**
     * @var string|array the configuration json
     */
    public $credentials;

    /**
     * @var boolean If this is provided with the value true, and the authorization request is
     * granted, the authorization will include any previous authorizations granted to this user/application
     * combination for other scopes.
     */
    public $includeGrantedScopes = true;

    /**
     * @var array|string Will append any scopes not previously requested to the scope parameter.
     * A single string will be treated as a scope to request. An array of strings will each
     * be appended.
     */
    public $scopes;

    /**
     * @var string The OAuth 2.0 Redirect URI
     */
    public $redirectUri;

    /**
     * @var Google_Client The Google API Client
     */
    private $client;

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!isset($this->credentials)) {
            throw new InvalidConfigException(Yii::t('simialbi/google/notifications', 'The "credentials" param is mandatory'));
        }
    }

    /**
     * Configures Google API Client component and returns it
     *
     * @return Google_Client
     * @throws Google_Exception
     */
    public function getApi()
    {
        if (null !== $this->client) {
            return $this->client;
        }

        $this->client = new Google_Client();
        $this->client->setAuthConfig($this->credentials);
        $this->client->setIncludeGrantedScopes($this->includeGrantedScopes);
        if (!empty($this->scopes)) {
            $this->client->addScope($this->scopes);
        }
        if (!empty($this->redirectUri)) {
            $this->client->setRedirectUri($this->redirectUri);
        }
        if (Yii::$app->session && Yii::$app->session->has('googleApiToken')) {
            $this->client->setAccessToken(Yii::$app->session->get('googleApiToken'));
        }

        return $this->client;
    }

    /**
     * Returns authentication url
     *
     * @return string
     * @throws Google_Exception
     */
    public function getAuthUrl()
    {
        return $this->getApi()->createAuthUrl();
    }

    /**
     * Access token getter
     *
     * @return array access token
     * @throws Google_Exception
     */
    public function getAuthToken()
    {
        return $this->getApi()->getAccessToken();
    }

    /**
     * Access token setter
     *
     * @param string $code string code from accounts.google.com
     * @throws Google_Exception
     */
    public function setAuthToken($code)
    {
        $token = $this->getApi()->fetchAccessTokenWithAuthCode($code);
        $this->getApi()->setAccessToken($token);

        if (Yii::$app->session) {
            Yii::$app->session->set('token', $token);
        }
    }
}