<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

use \yii\web\Request;
$baseUrl = str_replace('/frontend/web', '', (new Request)->getBaseUrl());

return [
    'id' => 'app-frontend',
    'name' => 'Ticketing System',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'baseUrl' => $baseUrl,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mycomponent' => [
            'class' => 'app\components\TaskComponent',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'=>[
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                'home'=>'site/index',
                'about'=>'site/about',
                'contact'=>'site/contact',
                //'signup'=>'site/signup',
                'profile'=>'profile/site/index',
                'login'=>'site/login',
                'logout'=>'site/logout',
                'user'=> 'admin/site/index',
                'user-ticket'=> 'ticket/site/index',
                'all-ticket'=> 'ticket/site/all-ticket',
                'ticket-report'=> 'ticket/site/ticket-report',
                'pdf-report'=> 'ticket/site/pdf-report',
            ],
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'ticket' => [
            'class' => 'app\modules\ticket\Module',
        ],
        'profile' => [
            'class' => 'app\modules\profile\Module',
        ],
    ],
    'params' => $params,
];
