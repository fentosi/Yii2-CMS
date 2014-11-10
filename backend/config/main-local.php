<?php

$config = [
	
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'H624eORvX2J4XQAuoE4p2pE89YgMtXRK',
        ],        
    ],
    'modules' => [
    	'gridview' =>  [
    		'class' => '\kartik\grid\Module'
    	],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
    	'class' => 'yii\debug\Module',
    	'allowedIPs' => ['127.0.0.1', '::1', '118.93.94.100'],
    ];
	$config['modules']['gii'] = [
    	'class' => 'yii\gii\Module',
    	'allowedIPs' => ['127.0.0.1', '::1', '118.93.94.100'],
    ];
}

return $config;
