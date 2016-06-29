<?php
namespace frontend\modules\ticket\models;

use app\components\Constants;
use yii\base\Model;
use frontend\models\TableTicket;
use Yii;


class TicketForm extends Model
{
    public $ticket_id;
    public $ticket_name;
    public $ticket_desc;
    public $ticket_date_create;
    public $ticket_date_update;
    public $ticket_status;
    public $user_id;
  	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['ticket_name', 'filter', 'filter' => 'trim'],
            ['ticket_name', 'required'],
            ['ticket_name', 'string', 'min' => 2, 'max' => 50], 

            ['ticket_desc', 'filter', 'filter' => 'trim'],
            ['ticket_desc', 'required'],
            ['ticket_desc', 'string', 'min' => 2, 'max' => 255],
        ];
    }

    /**
     * Create Pasien.
     *
     * @return user|null the saved model or null if saving fails
    */
    public function create()
    {
        if ($this->validate()) {
            $create = new TableTicket();
            $create->ticket_name = trim(strip_tags($this->ticket_name));
            $create->ticket_desc = trim(strip_tags($this->ticket_desc));
            $create->ticket_date_create = date('Y-m-d');
            $create->ticket_date_update = date('Y-m-d');
            $create->ticket_status = Constants::waiting_queues;
            $create->user_id = Yii::$app->user->identity->id;
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
            $update = TableTicket::findOne($did);
            if($update->ticket_status == Constants::waiting_queues && $update->user_id == Yii::$app->user->identity->id)
            {
                $update->ticket_name = trim(strip_tags($this->ticket_name));
                $update->ticket_desc = trim(strip_tags($this->ticket_desc));
                $update->ticket_date_update = date('Y-m-d');
                if($update->save(false)) 
                {
                   return  array(
                        'ticket_name'=>$update->ticket_name,
                        'ticket_desc'=>$update->ticket_desc,
                    );
                }
            }
            else
            {
                return 'role-of';
            }
        }
        
        return null;
    }

    public function getTicket($u_id)
    {
        $model = $this->findModel($u_id);

        $arrData = array(
                'ticket_id'=>$model['ticket_id'],
                'ticket_name'=>$model['ticket_name'],
                'ticket_desc'=>$model['ticket_desc'],
            );

        if($arrData)
        {
            return $arrData;   
        }

        return null;
       
    }

    public function setTicketStatus($tid, $tstatus)
    {
        $model = $this->findModel($tid);
        $model->ticket_status = $tstatus;
        $model->save(false);

        return true;
    }
	

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ticket_id' => 'ID',
            'ticket_name' => 'Ticket Name',
            'ticket_desc' => 'Ticket Description',
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
        if (($model = TableTicket::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}