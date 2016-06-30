<?php
namespace frontend\modules\ticket\models;

use yii\base\Model;
use Yii;
use app\components\Constants;
use frontend\models\TableTicketAssigned;

/**
 * Signup form
 */
class TicketModel extends Model
{
	
	public function insertAssigned($tid,$uid)
	{
		$find = TableTicketAssigned::find()->where(['ticket_id'=>$tid, 'user_id'=>$uid] )->one();
		if(!$find)
		{
			$model = new TableTicketAssigned();
			$model->ticket_id = $tid;
			$model->user_id = $uid;
			$model->assigned_date = date('Y-m-d H:i:s');
			$model->assigned_user_id = Yii::$app->user->identity->id;
			$model->save(false);
			return true;
		}
		
		return false;
	}

	public function getItSupport($tid)
	{
		$row = (new \yii\db\Query())
			->select([
				'id', 
				'username', 
				'firstname',
				'lastname'
			])
			->from('user')
			->where(['level_user' => Constants::IT_SUPPORT])
			->andWhere(['status' => 10])
			->andWhere(['NOT IN', 'id',  $this->getMemberActive($tid) ])
			->all();
		$arrData = array();
	
		if($row)
		{
			foreach ($row as $value) {
				$arrData[] = array(
					'u_id'=>$value['id'],
					'username'=>$value['username'],
					'firstname'=>$value['firstname'],
					'lastname'=>$value['lastname']
				);
			}
			
			return $arrData;
		}
		else
		{
			return false;
		}
		
	}

	private function getMemberActive($tid)
	{
		$array = TableTicketAssigned::find()->select(['user_id'])->where(['ticket_id'=>$tid])->all();
		$arrayData = array();
		foreach($array as $value)
		{
			$arrayData[] = $value['user_id'];
		}

		return $arrayData;
	}

	public function getAssignedTicket($tid)
	{
		$row = (new \yii\db\Query())
			->select([
				'tta.user_id', 
				'tta.ticket_id', 
				'usr.username',
				'usr.firstname',
				'usr.lastname'
			])
			->from('tbl_ticket_assigned tta')
			->leftJoin('user usr', 'usr.id = tta.user_id')
			->where(['tta.ticket_id'=>$tid])
			->all();
		$arrData = array();
	
		if($row)
		{
			foreach ($row as $value) {
				$arrData[] = array(
					'user_id'=>$value['user_id'],
					'ticket_id'=>$value['ticket_id'],
					'username'=>$value['username'],
					'firstname'=>$value['firstname'],
					'lastname'=>$value['lastname']
				);
			}
			
			return $arrData;
		}
		else
		{
			return false;
		}
		
	}

	public function printMpdf($progress = 1000, $month = 0, $year= 0)
	{

		$ticket_status = '';        
        if($progress != 1000 && $progress != '' )
        {
            $ticket_status = ' AND ticket_status ='.$progress.' ';  
        }

        $year_query = '';
        if($month !== '' && $year !== '' && $month !== 0 && $year !== 0)
        {
            $year_query = ' AND (year(ticket_date_create)='.$year.' AND month(ticket_date_create)='.$month.' ) ';
        }

		$row = (new \yii\db\Query())
			->select([
				'tt.ticket_id',
                'tt.ticket_name',
                'tt.ticket_desc',
                'tt.ticket_date_create',
                'tt.ticket_date_update',
                'tt.ticket_status',
                'tt.user_id',
                'us.firstname',
                'us.lastname',
                'us.level_user',
			])
			->from('tbl_ticket tt')
            ->leftJoin('user us', 'us.id = tt.user_id')
            ->where('1 '.$ticket_status.$year_query)
			->all();
		$arrData = array();
	
		if($row)
		{
			foreach ($row as $value) {
				$arrData[] = array(
					'ticket_id'=>$value['ticket_id'],
					'user_id'=>$value['user_id'],
					'ticket_name'=>$value['ticket_name'],
					'ticket_desc'=>$value['ticket_desc'],
					'ticket_date_create'=>$value['ticket_date_create'],
					'ticket_date_update'=>$value['ticket_date_update'],
					'ticket_status'=>Yii::$app->mycomponent->progressName($value['ticket_status']),
					'firstname'=>$value['firstname'],
					'lastname'=>$value['lastname'],
					'level_user'=>Yii::$app->mycomponent->roleName($value['level_user']),
					'assigned'=>Yii::$app->mycomponent->assignedListNormal($value['ticket_id']),
				);
			}
			
			return $arrData;
		}
		else
		{
			return false;
		}
		
	}
	
}
