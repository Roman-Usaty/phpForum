<?php

namespace app\models;

use Yii;

use yii\base\Model;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $pass_hash
 * @property string|null $token
 */
class Users extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'pass_hash'], 'required'],
            [['username'], 'string', 'max' => 24],
            [['email', 'pass_hash', 'token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'pass_hash' => 'Pass Hash',
            'token' => 'Token',
        ];
    }
}
