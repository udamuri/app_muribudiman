<?php

namespace app\modules\ticket\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use yii\widgets\ActiveForm;
use frontend\models\TableTicket;
use frontend\modules\ticket\models\TicketForm;
use frontend\modules\ticket\models\TicketModel;
use kartik\mpdf\Pdf;
/**
 * Default controller for the `ticket` module
 */
class SiteController extends Controller
{
	/**
    * @inheritdoc
    */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                	[
                        'actions' => ['index', 'create-ticket', 'get-ticket', 'update-ticket', 'delete-ticket'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['all-ticket', 'get-it-support', 'set-assigned', 'set-ticket-status'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                           return Yii::$app->mycomponent->isUserRole('admin-administrasi-support', Yii::$app->user->identity->level_user);
                        }
                    ],
                    [
                        'actions' => ['ticket-report', 'pdf-report'],
                        'allow' => true,
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) {
						   return Yii::$app->mycomponent->isUserRole('admin-administrasi-support-manager', Yii::$app->user->identity->level_user);
						}
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $search = '';
        $uid = Yii::$app->user->identity->id;
        if(isset($_GET['search']))
        {
            $search =  strtolower(trim(strip_tags($_GET['search'])));
        }
        
        $query = (new \yii\db\Query())
                    ->select([
                        'ticket_id',
                        'ticket_name',
                        'ticket_desc',
                        'ticket_date_create',
                        'ticket_date_update',
                        'ticket_status',
                        'user_id',
                    ])
                    ->from('tbl_ticket');
                    
        if($search !== '')
        {

            $query->where('user_id='.$uid.'  AND lower(ticket_name) LIKE "%'.$search.'%" ')
                    ->orWhere('user_id='.$uid.'  AND lower(ticket_desc) LIKE "%'.$search.'%"');
        }
        else
        {
        	$query->where('user_id='.$uid.'');
        }
        
        $countQuery = clone $query;
        $pageSize = 10;
        $pages = new Pagination([
                'totalCount' => $countQuery->count(), 
                'pageSize'=>$pageSize
            ]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy(['ticket_id'=>SORT_DESC])
            ->all();
            
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'offset' =>$pages->offset,
            'page' =>$pages->page,
            'search' =>$search
        ]);
    }

    public function actionCreateTicket()
    {
        if (Yii::$app->request->isAjax) 
        {
            $model = new TicketForm();
            if ($model->load(Yii::$app->request->post()) && $model->create() ) 
            {
                $data = array('status'=>'success');
                return \yii\helpers\Json::encode($data);
            }
            else
            {
                Yii::$app->response->format = 'json';
                $data = array('status'=>'form-error','error-form'=>ActiveForm::validate($model));
                return \yii\helpers\Json::encode($data);
                Yii::$app->end();
            }
        }
        else
        {
            $data = array('status'=>'error');
            return \yii\helpers\Json::encode($data);
        }
    }

    public function actionGetTicket()
    {
        if (Yii::$app->request->isAjax && isset($_POST['p_id'])) 
        {
            $model = new TicketForm();
            $p_id = $_POST['p_id'];
            $gdata = $model->getTicket($p_id);
            if($gdata)
            {
                $data = array('status'=>'success','arr-data'=>$gdata);
                return \yii\helpers\Json::encode($data);
            }

            $data = array('status'=>'error');
            return \yii\helpers\Json::encode($data);
        }
    }

    public function actionUpdateTicket()
    {
        if (Yii::$app->request->isAjax && isset($_POST['p_id'])) 
        {
            $model = new TicketForm();
            $p_id = $_POST['p_id'];
            if ($model->load(Yii::$app->request->post()) && $xdata = $model->update($p_id) ) 
            {
                if($xdata == 'role-of')
                {
                    $data = array('status'=>'error-role-update');
                    return \yii\helpers\Json::encode($data);
                }
                else
                {
                    $data = array('status'=>'success','data'=>$xdata);
                    return \yii\helpers\Json::encode($data);
                }
            }
            else
            {
                Yii::$app->response->format = 'json';
                $data = array('status'=>'form-error','error-form'=>ActiveForm::validate($model));
                return \yii\helpers\Json::encode($data);
                Yii::$app->end();
            }
        }
        else
        {
            $data = array('status'=>'error');
            return \yii\helpers\Json::encode($data);
        }
    }

    public function actionAllTicket()
    {
        $search = '';
        $uid = Yii::$app->user->identity->id;
        if(isset($_GET['search']))
        {
            $search =  strtolower(trim(strip_tags($_GET['search'])));
        }
        
        $query = (new \yii\db\Query())
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
                    ->leftJoin('user us', 'us.id = tt.user_id');
                    
        if($search !== '')
        {

            $query->where('lower(ticket_name) LIKE "%'.$search.'%" ')
                    ->orWhere('lower(ticket_desc) LIKE "%'.$search.'%"');
        }
        
        $countQuery = clone $query;
        $pageSize = 10;
        $pages = new Pagination([
                'totalCount' => $countQuery->count(), 
                'pageSize'=>$pageSize
            ]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy(['ticket_id'=>SORT_DESC])
            ->all();
        

        return $this->render('allticket', [
            'models' => $models,
            'pages' => $pages,
            'offset' =>$pages->offset,
            'page' =>$pages->page,
            'search' =>$search
        ]);
    }

    public function actionGetItSupport()
    {
        //TicketModel
        if (isset($_POST['t_id']) && Yii::$app->request->isAjax) 
        {
            $model = new TicketModel();
            $arrData = $model->getItSupport($_POST['t_id']);
            $data = array('status'=>'success', 'arr-data'=>$arrData);
            return \yii\helpers\Json::encode($data);
        }
        else
        {
            $data = array('status'=>'error');
            return \yii\helpers\Json::encode($data);
        }
    }

    public function actionSetAssigned()
    {
        if (isset($_POST['t_id']) && isset($_POST['u_id']) && Yii::$app->request->isAjax) 
        {
            $model = new TicketModel();
            if($model->insertAssigned($_POST['t_id'], $_POST['u_id']))
            {
                $label_data = Yii::$app->mycomponent->assignedList($_POST['t_id']);
                $data = array('status'=>'success', 'label-data'=>$label_data);
                return \yii\helpers\Json::encode($data);
            }
            else
            {
                $data = array('status'=>'error');
                return \yii\helpers\Json::encode($data);
            }
        }
        else
        {
            $data = array('status'=>'error');
            return \yii\helpers\Json::encode($data);
        }
        
    }

    public function actionSetTicketStatus()
    {
        if (isset($_POST['t_id']) && isset($_POST['t_status']) && Yii::$app->request->isAjax) 
        {
            $model = new TicketForm();
            if($model->setTicketStatus($_POST['t_id'], $_POST['t_status']))
            {
                $status = Yii::$app->mycomponent->progressName($_POST['t_status']);
                $data = array('status'=>'success', 'label-status'=>$status);
                return \yii\helpers\Json::encode($data);
            }
            else
            {
                $data = array('status'=>'error');
                return \yii\helpers\Json::encode($data);
            }
        }
        else
        {
            $data = array('status'=>'error');
            return \yii\helpers\Json::encode($data);
        }
        
    }

    public function actionTicketReport()
    {
        $uid = Yii::$app->user->identity->id;
        
        $progress ='';
        if(isset($_GET['progress']))
        {
            $progress =  (int)$_GET['progress'];
        }

        $month ='';
        if(isset($_GET['month']))
        {
            $month =  (int)$_GET['month'];
        }

        $year ='';
        if(isset($_GET['year']))
        {
            $year =  (int)$_GET['year'];
        }
        
        $query = (new \yii\db\Query())
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
                    ->leftJoin('user us', 'us.id = tt.user_id');
            
        $ticket_status = '';        
        if($progress != '1000' && $progress !== '')
        {
            $ticket_status = ' AND ticket_status ='.$progress.' ';  
        }

        $year_query = '';
        if($month !== '' && $year !== '')
        {
            $year_query = ' AND (year(ticket_date_create)='.$year.' AND month(ticket_date_create)='.$month.' ) ';
        }

        $query->where('1 '.$ticket_status.$year_query);
        
        $countQuery = clone $query;
        $pageSize = 10;
        $pages = new Pagination([
                'totalCount' => $countQuery->count(), 
                'pageSize'=>$pageSize
            ]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy(['ticket_id'=>SORT_DESC])
            ->all();
        

        return $this->render('report', [
            'models' => $models,
            'pages' => $pages,
            'offset' =>$pages->offset,
            'page' =>$pages->page,
            'year' =>$year,
            'month' =>$month,
            'progress' =>$progress,
            //'search' =>$search
        ]);  
    }

    public function actionPdfReport()
    {
        $progress ='';
        if(isset($_GET['progress']))
        {
            $progress =  (int)$_GET['progress'];
        }

        $month ='';
        if(isset($_GET['month']))
        {
            $month =  (int)$_GET['month'];
        }

        $year ='';
        if(isset($_GET['year']))
        {
            $year =  (int)$_GET['year'];
        }

        $_model = new TicketModel();
        $_model_data = $_model->printMpdf($progress, $month, $year);


        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('privacy', ['models'=>$_model_data]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, 
            'format' => Pdf::FORMAT_A4, 
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            //'destination' => Pdf::DEST_BROWSER, 
            'destination' => Pdf::DEST_DOWNLOAD, 
            'content' => $content,  
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}', 
            'options' => ['title' => 'Krajee Report Title'],
            'methods' => [ 
                'SetHeader'=>['Ticket Report PT TRI SINAR PURNAMA'], 
                'SetFooter'=>['Page:{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }
}
