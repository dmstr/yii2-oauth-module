# Yii OAuth Module

The package provides a module for [Yii 2.0](https://www.yiiframework.com/) that allows you to use [OAuth 2.0](https://www.oauth.com/) authentication. It is based on
the [league/oauth2-client](https://github.com/thephpleague/oauth2-server) package.

## Features

- [x] Client credentials grant
- [x] Admin module to manage clients
- [x] User id attribute for clients
- [x] Access token encryption
- [x] Access token issuer
- [x] Access token in JWT format

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```bash
composer require dmstr/yii2-oauth-module
```

## Setup

Add the module to your web application configuration:

```php
<?php

use dmstr\oauth\Module as OAuthModule;
use dmstr\oauth\modules\admin\Module as OAuthAdminModule;

return [
    'modules' => [
        'oauth' => [
            'class' => OAuthModule::class,
            'tokenPrivateKey' => getenv('JWT_PRIVATE_KEY_FILE'), // Path to private key file. Must start with file://
            'tokenEncryptionKey' => getenv('JWT_PRIVATE_KEY_PASSPHRASE'), // optional. Only needed if you have a passphrase for your private key
            'accessTokenIssuer' => getenv('JWT_ISS'), // Issuer of the access token.
            'userIdAttribute' => 'id', // The attribute of the user model that will be added to the access token as the `sub` claim.
            // This is optional but recommended. It will allow you to manage your clients in the admin interface.
            'modules' => [
                'admin' => [
                    'class' => OAuthAdminModule::class
                ]
            ]
        ]
    ],
    // This is only needed if your using codemix/yii2-localeurls (https://github.com/codemix/yii2-localeurls)
    'components' => [
        'urlManager' => [
            'ignoreLanguageUrlPatterns' => [
                '#^oauth/token#' => '#^oauth/token#'
            ]
        ],
        'rules' => [
            // This is only needed if you want to use the admin module. It will create an url alias to the user module
            'oauth/admin/user/index' => 'user/admin/index',
            'oauth/admin/user/view' => 'user/admin/update'
        ]
     
    ]
];
```

And this to your console application configuration:

```php
[
    'controllerMap' => [
        'migrate' => [
            'migrationPath' => [
                '@vendor/dmstr/yii2-oauth-module/src/migrations'
            ]
        ]
    ]
]
```

or run

```bash
yii migrate/up --migrationPath=@vendor/dmstr/yii2-oauth-module/src/migrations
```

## General usage

First you need to generate a public and private key pair. You can use the following command to generate a key pair:

```bash
openssl genrsa -out private.key 2048
```
If you want to provide a passphrase for your private key run this command instead:

```bash
openssl genrsa -aes128 -passout pass:<your-passphrase> -out private.key 2048
```

When you have installed the module, 

Then you need to create a client. You can reach the admin client crud via `<your-base-url>/oauth/admin/client/index`. 

You have the option to add a user id to the client. This will allow you to use the client to log in as this user after authentication. If you don't
add a user id, the client will be able to access the api, but not log in. The user id is added to the access token in the `sub` claim.

To get a new access token, you can send a post request the following endpoint:

```bash
curl -X POST -H "Content-Type: application/x-www-form-urlencoded" -d "grant_type=client_credentials&client_id=<your-client-id>&client_secret=<your-client-secret>" <your-base-url>/oauth/token
```

This will return all [required information](https://www.oauth.com/oauth2-servers/access-tokens/access-token-response/) to authenticate your requests.

You can now use the access token to authenticate your requests.

## Example usage with Yii 2.0 REST API

This example shows how to use the access token to authenticate your requests in a Yii 2.0 REST API. It uses the [bizley/yii2-jwt](https://github.com/bizley/yii2-jwt) package to authenticate the requests.

```php
<?php

namespace app\api\controllers;

use Da\User\Model\User;
use bizley\jwt\JwtHttpBearerAuth;
use yii\filters\AccessControl;
use yii\rest\Controller;

class ItemsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            [
                'class' => JwtHttpBearerAuth::class,
                // We used auth() here to keep the example simple. Implementing findIdentityByAccessToken() in your user model is recommended.
                'auth' => function (Plain $token) {
                    return User::findIdentity($token->claims()->get('sub'));
                }
            ]
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                    'actions' => ['index']
                ]
            ]
        ];
        return $behaviors;
    }

    /**
     * Example action. Replace with your own.
     */
    public function actionIndex(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Item 1'
            ]
        ];
    }
}
```
