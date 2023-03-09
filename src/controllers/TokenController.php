<?php

namespace dmstr\oauth\controllers;

use DateInterval;
use Exception;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use dmstr\oauth\components\repositories\AccessTokenRepository;
use dmstr\oauth\components\repositories\ClientRepository;
use dmstr\oauth\components\repositories\ScopeRepository;
use dmstr\oauth\Module;
use Yii;
use yii\filters\Cors;
use yii\helpers\Json;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response as WebResponse;

/**
 * @property Module $module
 */
class TokenController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'issue-access-token';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // Only offer json as a response type
        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => WebResponse::FORMAT_JSON
        ];
        // Cors config
        $behaviors['cors'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['OPTIONS', 'POST'],
                'Access-Control-Allow-Credentials' => false, // See beforeAction for more info
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Allow-Headers' => ['Origin', 'Content-Type', 'Accept', 'Authorization'],
            ]
        ];
        return $behaviors;
    }

    /**
     * Set some global properties for every request and response in this controller
     *
     * @inheritdoc
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {

        // see: https://www.yiiframework.com/doc/guide/2.0/en/rest-authentication
        Yii::$app->getUser()->enableSession = false;
        Yii::$app->getUser()->loginUrl = null;

        // can't be set in Cors filter when Origin = '*', so we set this here
        $this->response->getHeaders()->set('Access-Control-Allow-Credentials', 'true');

        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        $verbs = parent::verbs();
        $verbs['issue-access-token'] = ['POST'];
        return $verbs;
    }

    /**
     * Issues access tokens for a given client
     *
     * @throws HttpException
     * @throws Exception
     */
    public function actionIssueAccessToken()
    {
        // Initialize auth server with implemented repositories
        $server = new AuthorizationServer(
            new ClientRepository(),
            new AccessTokenRepository([
                'issuer' => $this->module->accessTokenIssuer,
                'userIdAttribute' => $this->module->userIdAttribute
            ]),
            new ScopeRepository(),
            $this->module->tokenPrivateKey,
            $this->module->tokenEncryptionKey
        );

        // Enable the client_credentials grant type. Currently, we only need this grant
        $server->enableGrantType(
            new ClientCredentialsGrant(),
            new DateInterval($this->module->accessTokenTtl)
        );

        // Prepare response and return the processed response
        // We use guzzles response and request classes because they implement psr7 which is needed by league oauth package
        $response = new Response();
        try {
            $response = $server->respondToAccessTokenRequest(ServerRequest::fromGlobals(), $response);
        } catch (OAuthServerException $exception) {
            $response = $exception->generateHttpResponse($response);
        } catch (Exception $exception) {
            throw new HttpException(500, $exception->getMessage());
        }

        // Update status code and return body data as array
        $this->response->setStatusCode($response->getStatusCode());
        return Json::decode($response->getBody());
    }
}
