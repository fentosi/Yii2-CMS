<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'language' => 'hu_HU',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
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
		'urlManager' => [
			'class' => 'yii\web\UrlManager',
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
		        // your url config rules
			]
		],        
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
	    'i18n' => [
	        'translations' => [
	            'app*' => [
	                'class' => 'yii\i18n\PhpMessageSource',
	                'basePath' => '@backend/messages',
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
