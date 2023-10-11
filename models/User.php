<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public static $users;
    public static function tableName()
    {
        return 'users';
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    public static function findByUsername($login)
    {
        $user = self::find()->where("login='$login'")->one();
        if (strcasecmp($user['login'], $login) === 0) {
            return new static($user);
        }

        //User::find()->where("login=$login")->one();
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        $password_md = md5($password);
        return $this->password === $password_md;
    }
}
