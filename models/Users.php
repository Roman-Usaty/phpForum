<?php

namespace app\models;

use Yii;

use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $pass_hash
 * @property string|null $token
 */
class Users extends ActiveRecord implements IdentityInterface
{
    public $password;
    public $re_pass;

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_SIGNUP = 'signup';


    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_LOGIN => ['username', 'password'],
            self::SCENARIO_SIGNUP => ['username', 'email', 'password', 're_pass']
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 're_pass'], 'required', 'message' => 'Заполните поля', 'on' => self::SCENARIO_SIGNUP],
            [['username'], 'string', 'max' => 24, 'message' => 'Имя пользователя не длиннее 24 символов', 'on' => self::SCENARIO_SIGNUP],
            [['username'], 'unique', 'on' => self::SCENARIO_SIGNUP],
            [['password', 're_pass'], 'checkPass', 'on' => self::SCENARIO_SIGNUP],
            [['email', 'pass_hash', 'token'], 'string', 'max' => 255, 'on' => self::SCENARIO_SIGNUP],
            [['email'], 'email', 'message' => 'Введен не коректный адрес почты', 'on' => self::SCENARIO_SIGNUP],
            [['username', 'password'], 'required', 'message' => 'Заполните все поля', 'on' => self::SCENARIO_LOGIN]
        ];
    }


    public function checkPass($attributes)
    {
        if ($this->password == $this->re_pass) {
            return true;
        }
        return $this->addError($attributes, 'Пароли не совпадают');
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->token = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Почта',
            'password' => 'Пароль',
            're_pass' => 'Повторите пароль'
        ];
    }

    public function login()
    {
        $user = $this->findByUsername($this->username);

        if (is_null($user)) {
            return $this->addError('username', 'Имя пользователя неверно');
        }
        
        if (Yii::$app->security->validatePassword($this->password, $user->pass_hash) && !is_null($user)) {
            return Yii::$app->user->login($user);
        }

        
        return $this->addError('password', 'Пароль неверен');
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

     /* * 
     *  {@inheritdoc}
     * */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->token;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($token)
    {
        return $this->getAuthKey() === $token;
    } 

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
}
