<?php

namespace tests\unit;

use app\models\LoginForm;
use Yii;

class LoginFormTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        Yii::$app->db->open();
    }

    /** ✅ Login berhasil karena username & password benar */
    public function testLoginDenganCorrectCredentials()
    {
        $model = new LoginForm([
            'username' => 'superadmin', // harus ada di DB
            'password' => '123',
        ]);

        $this->assertTrue($model->validate(), 'Validasi gagal padahal data benar');
    }

    /** ❌ Password salah */
    public function testLoginDenganPassword()
    {
        $model = new LoginForm([
            'username' => 'admin',
            'password' => 'salah',
        ]);

        $this->assertFalse($model->validate(), 'Password salah seharusnya gagal');
        $this->assertArrayHasKey('password', $model->errors);
    }

    /** ⚠️ Field kosong */
    public function testLoginDenganFormKosong()
    {
        $model = new LoginForm([]);
        $this->assertFalse($model->validate());
        $this->assertArrayHasKey('username', $model->errors);
        $this->assertArrayHasKey('password', $model->errors);
    }

    /** ❌ Username tidak ditemukan di database */
    public function testLoginDenganUserTidakDitemukan()
    {
        $model = new LoginForm([
            'username' => 'tidakada',
            'password' => 'bebas',
        ]);

        $this->assertFalse($model->validate(), 'Harus gagal jika user tidak ditemukan');
        $this->assertArrayHasKey('password', $model->errors, 'Error harus muncul di field password');
        $this->assertEquals('Nama/Password salah.', $model->getFirstError('password'));
    }
}
