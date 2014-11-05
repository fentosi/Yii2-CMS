<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
	    'i18n' => [
	        'translations' => [
	            'app*' => [
	                'class' => 'yii\i18n\PhpMessageSource',
	                'basePath' => '@frontend/messages',
	                'sourceLanguage' => 'en',
	                'fileMap' => [
						'app' => 'app.php',
						'app/tag' => 'tag.php',
						'app/poll' => 'poll.php',
						'app/user' => 'user.php',
						'app/form' => 'form.php',
						'app/content' => 'content.php',
	                ],
	            ],
	        ],
	    ],        
    ],
    'params' => $params,
];
