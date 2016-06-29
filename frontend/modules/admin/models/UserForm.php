<?php
namespace frontend\modules\admin\models;

use app\components\Constants;
use yii\base\Model;
use common\models\User;
use Yii;


class UserForm extends Model
{
    public $username;
    public $firstname;
    public $lastname;
    public $email;
    public $level_user;
    public $password;
  	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required', 'on' => 'insert'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['firstname', 'filter', 'filter' => 'trim'],
            ['firstname', 'required'],
            ['firstname', 'string', 'min' => 2, 'max' => 50],

            ['lastname', 'filter', 'filter' => 'trim'],
            ['lastname', 'string', 'min' => 2, 'max' => 50],

            ['level_user', 'integer'],
            ['level_user', 'required'],
            ['level_user', 'in', 'range' => [Constants::KARYAWAN, Constants::IT_SUPPORT, Constants::ADMINISTRASI_IT, Constants::MANAGER_IT] ],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'on' => 'insert'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required', 'on' => 'insert'],
            ['password', 'string', 'min' => 4],
        ];
    }

    /*
    public function validateUsername()
    {
        $user = User::findByUsername(Yii::$app->user->identity->id);

        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', 'Incorrect username or password.');
        }
    }
    */
	
    /**
     * Create Pasien.
     *
     * @return user|null the saved model or null if saving fails
    */
    public function create()
    {
        if ($this->validate()) {
            $create = new User();
            $create->username = trim(strip_tags($this->username));
            $create->firstname = trim(strip_tags($this->firstname));
            $create->lastname = trim(strip_tags($this->lastname));
            $create->email = $this->email;
            $create->level_user = $this->level_user;
            $create->setPassword($this->password);
            $create->generateAuthKey();
            if($create->save(false)) 
            {
               return true;
            }
        }

        return null;
    }

    /**
     * Update Pasien.
     *
     * @return user|null the saved model or null if saving fails
    */
    public function update($did)
    {
     
        if ($this->validate()) {
            $update = User::findOne($did);
            //$update->username = trim(strip_tags($this->username));
            $update->firstname = trim(strip_tags($this->firstname));
            $update->lastname = trim(strip_tags($this->lastname));
            $update->level_user = trim(strip_tags($this->level_user));
            //$update->email = $this->email;
            if($update->save(false)) 
            {
               return  array(
                    'firstname'=>'firstname',
                    'lastname'=>$update->lastname,
                    'level_user'=>$update->level_user,
                );
            }
        }
        
        return null;
    }

    public function getUser($u_id)
    {
        $model = $this->findModel($u_id);

        $arrData = array(
                'id'=>$model['id'],
                'username'=>$model['username'],
                'firstname'=>$model['firstname'],
                'lastname'=>$model['lastname'],
                'email'=>$model['email'],
                'level_user'=>$model['level_user'],
            );
        if($arrData)
        {
            return $arrData;   
        }

        return null;
       
    }
	

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }
	
	/**
     * Finds the UserPost model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserPost the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
