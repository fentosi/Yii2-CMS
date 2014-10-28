<?php

namespace backend\controllers;

class FieldController extends \yii\web\Controller
{
    public function actionCreate()
    {
        return $this->render('create');
    }

    public function actionView()
    {
        return $this->render('view');
    }

}
