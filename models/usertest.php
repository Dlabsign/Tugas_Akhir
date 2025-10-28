<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    // -------------------------------
    // 1. Nama tabel
    // -------------------------------
    public static function tableName()
    {
        return 'pengguna';
    }

    // -------------------------------
    // 2. IdentityInterface
    // -------------------------------
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // tidak pakai access token
    }

    // -------------------------------
    // 3. Cari user berdasarkan username aktif (flag = 1)
    // -------------------------------
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'flag' => 1]);
    }

    // -------------------------------
    // 4. Key identitas untuk session login
    // -------------------------------
    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // Jika kolom auth_key tidak ada, gunakan ID sebagai fallback
        return (string) ($this->auth_key ?? $this->id);
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    // -------------------------------
    // 5. Validasi password
    // -------------------------------
    public function validatePassword($password)
    {
        // Jika password di DB sudah di-hash
        if (Yii::$app->security->validatePassword($password, $this->password)) {
            return true;
        }

        // Jika belum di-hash (mode testing / data lama)
        return $this->password === $password;
    }

    // -------------------------------
    // 6. Rule tambahan (optional)
    // -------------------------------
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 255],
            [['flag'], 'integer'],
        ];
    }

    // -------------------------------
    // 7. Helper untuk buat user baru (hash otomatis)
    // -------------------------------
    public static function createUser($username, $password)
    {
        $user = new self();
        $user->username = $username;
        $user->password = Yii::$app->security->generatePasswordHash($password);
        $user->flag = 1;
        $user->auth_key = Yii::$app->security->generateRandomString();
        return $user->save(false);
    }
}
