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

    public function rules()
    {
        return [
            [['login', 'password', 'authKey'], 'required'],
            [['login', 'password'], 'match', 'pattern' => '/^[a-zA-Z0-9\-\s]+$/', 'message' => 'Please enter only letters and numbers.'],
            [['date'], 'safe'],
            [['login'], 'string', 'max' => 15, 'min' => 3],
            [['password', 'authKey'], 'string', 'min' => 8, 'max' => 45],
            [['email'], 'string', 'max' => 100],
            [['login'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'date' => 'Date',
        ];
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

    public function getAssignment()
    {
        return $this->hasMany(AuthAssignment::class, ['user_id' => 'id']);
    }
}
