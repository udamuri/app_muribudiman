<?php
namespace frontend\modules\admin\models;

use app\components\Constants;
use yii\base\Model;
use common\models\User;
use Yii;


class UserUpdateForm extends Model
{
    public $firstname;
    public $lastname;
    public $level_user;
  	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['firstname', 'filter', 'filter' => 'trim'],
            ['firstname', 'required'],
            ['firstname', 'string', 'min' => 2, 'max' => 50],

            ['lastname', 'filter', 'filter' => 'trim'],
            ['lastname', 'string', 'min' => 2, 'max' => 50],

            ['level_user', 'integer'],
            ['level_user', 'required'],
            ['level_user', 'in', 'range' => [Constants::KARYAWAN, Constants::IT_SUPPORT, Constants::ADMINISTRASI_IT, Constants::MANAGER_IT] ]
        ];
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
            $update->firstname = trim(strip_tags($this->firstname));
            $update->lastname = trim(strip_tags($this->lastname));
            $update->level_user = trim(strip_tags($this->level_user));
            if($update->save(false)) 
            {
               return  array(
                    'firstname'=>$update->firstname,
                    'lastname'=>$update->lastname,
                    'level_user'=>Yii::$app->mycomponent->roleName($update->level_user),
                );
            }
        }
        
        return null;
    }

    public function updateOf($did)
    {
     
    
            $update = User::findOne($did);
            $_status = $update->status;
            $_label = '';
            if($_status == '10')
            {
                $_status = 0;
                $_label = Yii::$app->mycomponent->statusName(0);
            }
            else
            {
                $_status = 10;
                $_label = Yii::$app->mycomponent->statusName(10);
            }
            $update->status = $_status;
            if($update->save(false)) 
            {
               return  $_label;
            }
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