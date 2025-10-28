<?php

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;
use app\models\Laboratorium; // Pastikan namespace ini benar

class LaboratoriumFixture extends ActiveFixture
{
    public $modelClass = Laboratorium::class;
}
