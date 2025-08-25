<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;

    private $_user = false;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || $this->password !== $user->password) {
                $this->addError($attribute, 'Nama/Password salah.');
            }
        }
    }


    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 3600); // session 1 jam
        }
        return false;
    }


    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
