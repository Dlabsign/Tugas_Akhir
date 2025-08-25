<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'pengguna';
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // tidak pakai access token
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'flag' => 1]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null; // bisa dikosongkan jika tidak pakai "remember me"
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
}