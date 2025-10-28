<?php

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;
use app\models\Pengguna; // Pastikan namespace ini benar

class PenggunaFixture extends ActiveFixture
{
    public $modelClass = Pengguna::class;
}
