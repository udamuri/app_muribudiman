<?php

namespace frontend\modules\profile\models;

use Yii;

class UserForm extends \yii\db\ActiveRecord
{
    public $username;
    public $email;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

   /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                    
            ['username', 'required'],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'unique', 'targetClass' => '\frontend\modules\profile\models\UserForm', 'message' => 'This username has already been taken.'],
            //['username', 'validateUniqueUsername'],

            ['email','required'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\frontend\modules\profile\models\UserForm', 'message' => 'This email has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'email' => 'Email',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    /*public function update()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->username = trim(strip_tags($this->username));
            $user->email = trim(strip_tags($this->email));
            if ($user->save(false)) {
                 return true;
            }
        }

        return null;
    }*/
    
    /**
     * Finds user by [[userid]]
     *
     * @return User|null
     */
    /*public static function getUser()
    {
        $this->_user = static::findOne(Yii::$app->user->identity->id);
        return $this->_user;
    }*/
}