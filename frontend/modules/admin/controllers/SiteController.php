<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use yii\widgets\ActiveForm;
use frontend\modules\admin\models\UserForm;
use frontend\modules\admin\models\UserUpdateForm;
use common\models\User;

/**
 * Default controller for the `admin` module
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
                        'actions' => ['index', 'create-user', 'get-user', 'update-user', 'delete-user'],
                        'allow' => true,
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) {
						   return Yii::$app->mycomponent->isUserRole('admin', Yii::$app->user->identity->level_user);
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
        if(isset($_GET['search']))
        {
            $search =  strtolower(trim(strip_tags($_GET['search'])));
        }
        
        $query = (new \yii\db\Query())
                    ->select([
                        'id',
                        'username',
                        'email',
                        'firstname',
                        'lastname',
                        'level_user',
                        'status',
                    ])
                    ->from('user');
                    
        if($search !== '')
        {
            $query->where('lower(username) LIKE "%'.$search.'%" ')
                    ->orWhere('lower(firstname) LIKE "%'.$search.'%"')
                    ->orWhere('lower(lastname) LIKE "%'.$search.'%"');
        }
        
        $countQuery = clone $query;
        $pageSize = 10;
        $pages = new Pagination([
                'totalCount' => $countQuery->count(), 
                'pageSize'=>$pageSize
            ]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy(['id'=>SORT_DESC])
            ->all();
            
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'offset' =>$pages->offset,
            'page' =>$pages->page,
            'search' =>$search
        ]);
    }

    public function actionCreateUser()
    {
        if (Yii::$app->request->isAjax) 
        {
            $model = new UserForm(['scenario' => 'insert']);
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

    public function actionGetUser()
    {
        if (Yii::$app->request->isAjax && isset($_POST['p_id'])) 
        {
            $model = new UserForm();
            $p_id = $_POST['p_id'];
            $gdata = $model->getUser($p_id);
            if($gdata)
            {
                $data = array('status'=>'success','arr-data'=>$gdata);
                return \yii\helpers\Json::encode($data);
            }

            $data = array('status'=>'error');
            return \yii\helpers\Json::encode($data);
        }
    }

    public function actionUpdateUser()
    {
        if (Yii::$app->request->isAjax && isset($_POST['p_id'])) 
        {
            $model = new UserUpdateForm();
            $p_id = $_POST['p_id'];
            if ($model->load(Yii::$app->request->post()) && $xdata = $model->update($p_id) ) 
            {
                $data = array('status'=>'success','data'=>$xdata);
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

    public function actionDeleteUser()
    {
        if (Yii::$app->request->isAjax && isset($_POST['p_id'])) 
        {
            $model = new UserUpdateForm();
            $p_id = $_POST['p_id'];
            if ($model->load(Yii::$app->request->post()) && $xdata = $model->updateOf($p_id) ) 
            {
                $data = array('status'=>'success','data'=>$xdata);
                return \yii\helpers\Json::encode($data);
            }
        }
        else
        {
            $data = array('status'=>'error');
            return \yii\helpers\Json::encode($data);
        }
    }


}
