<?php

namespace app\controllers;

use yii\web\Controller;

class DashboardController extends Controller
{
    public function actionSuperadmin()
    {
        return $this->render('superadmin');
    }

    public function actionAsisten()
    {
        return $this->render('asisten');
    }

    public function actionKepala()
    {
        return $this->render('kepala');
    }
}
