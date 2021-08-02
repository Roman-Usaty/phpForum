<?php

namespace app\models;

use Yii;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UploadFile;
/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $pass_hash
 * @property string|null $image
 * @property string|null $about
 * @property string|null $token
 */
class Users extends ActiveRecord implements IdentityInterface
{
    public $password;
    public $re_pass;
    public $imageFile;

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_SIGNUP = 'signup';
    const SCENARIO_CHANGE = 'change';


    
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
            self::SCENARIO_SIGNUP => ['username', 'email', 'password', 're_pass'],
            self::SCENARIO_CHANGE => ['username', 'email', 'password', 're_pass', 'image', 'about']
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
            [['username'], 'unique', 'on' => [self::SCENARIO_SIGNUP, self::SCENARIO_CHANGE]],
            [['password', 're_pass'], 'checkPass', 'on' => self::SCENARIO_SIGNUP],
            [['email', 'pass_hash', 'token'], 'string', 'max' => 255, 'on' => self::SCENARIO_SIGNUP],
            [['email'], 'email', 'message' => 'Введен не коректный адрес почты', 'on' => self::SCENARIO_SIGNUP],
            [['username', 'password'], 'required', 'message' => 'Заполните все поля', 'on' => self::SCENARIO_LOGIN],
            [['password', 're_pass'], 'changePass' , 'on' => self::SCENARIO_CHANGE],
            [['about'], 'string', 'skipOnEmpty' => true, 'on' => self::SCENARIO_SIGNUP],
            [['image'], 'string', 'max' => 255, 'skipOnEmpty' => true, 'on' => self::SCENARIO_SIGNUP],
            [['about'], 'string', 'skipOnEmpty' => false, 'on' => self::SCENARIO_SIGNUP],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'on' => self::SCENARIO_SIGNUP],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'on' => self::SCENARIO_CHANGE],
        ];
    }


    public function changePass($attributes)
    {
        if (!isset($this->password) && !isset($this->re_pass)) {
            return true;
        }
        return false;
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
            if (isset($this->imageFile)) {
                $this->upload();
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
            're_pass' => 'Повторите пароль',
            'about' => 'О себе'
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
        
    public function upload()
    {
        if ($this->validate()) {
            $fileName = hash('sha256', $this->id);
            $this->image = 'uploads/' . $fileName . '.' . $this->imageFile->extension;
            $this->deleteCopyImage($fileName);
            $this->imageFile->saveAs($this->image);
            return true;
        } else {
            return false;
        }
    }

    public function deleteCopyImage($nameWithoutExtensions)
    {
        $obj = $this;
        $files = \yii\helpers\FileHelper::findFiles('uploads/', [
            'filter' => array($obj, 'findFileByName'),
        ]);
        foreach ($files as $file) {
            unlink($file);
        }
    }

    public function findFileByName($path) {
        $path = str_replace('\\', '', $path);
        $path = stristr($path, '/');
        $extensions = stristr($path, '.');
        $extensions = str_replace('.', '', $extensions);
        $path = stristr($path, '.', true);
        $path = str_replace('/', '', $path);
        if ($path == hash('sha256', $this->id) && $extensions !== $this->imageFile->extension) {
            return true;
        }
        return false;
    }

    public function getImage($username)
    {
        $user = static::findOne(['username' => $username]);

        return $user->image;
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