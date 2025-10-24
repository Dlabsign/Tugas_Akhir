<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
class Pengguna extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $new_password;
    public $confirm_password;
    public static function tableName()
    {
        return 'pengguna';
    }

    public function rules()
    {
        return [
            [['semester', 'lab_id', 'flag'], 'default', 'value' => null],
            [['password', 'type', 'username'], 'required'],
            [['type', 'semester', 'flag', 'nim'], 'integer'],
            [['created_at', 'update_at', ' confirm_password', 'new_password'], 'safe'],
            [['password', 'username'], 'string', 'max' => 255],
            [['lab_id'], 'string', 'max' => 50],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => 'Password',
            'type' => 'Type',
            'username' => 'username',
            'semester' => 'Semester',
            'lab_id' => 'Lab',
            'created_at' => 'Created At',
            'update_at' => 'Updated At',
            'flag' => 'Flag',
            'nim' => 'NIM',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['username', 'password', 'type', 'semester', 'lab', 'nim'];
        $scenarios['update'] = ['username', 'new_password', 'confirm_password', 'type', 'semester', 'lab', 'nim'];
        return $scenarios;
    }
    // Implementasi IdentityInterface
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'flag' => 1]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // jika tidak pakai token
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
        return null; // tidak pakai rememberMe
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }

    // Password
    public function setPassword($password)
    {
        // langsung simpan plain text
        $this->password = $password;
    }


    public function validatePassword($password)
    {
        // langsung bandingkan string
        return $this->password === $password;
    }


    public function getLaboratorium()
    {
        return $this->hasOne(Laboratorium::class, ['id' => 'lab_id']);
    }


    public static function getUserType()
    {
        return [
            1 => 'Superadmin',
            2 => 'Kepala Laboratorium',
            3 => 'Asisten Laboratorium',
        ];
    }

    public function getTypeLabel()
    {
        $list = self::getUserType();
        return isset($list[$this->type]) ? $list[$this->type] : null;
    }

    // Soft delete
    public function delete()
    {
        $this->flag = 0;
        return $this->save(false, ['flag']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->flag = 1;
            }
            return true;
        }
        return false;
    }
}