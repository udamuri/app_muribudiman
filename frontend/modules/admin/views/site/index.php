<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Constants;


$this->title = 'User';

$this->registerJsFile(Yii::$app->homeUrl."js/index.js", ['depends' => [\yii\web\JqueryAsset::className()], 'position' =>  \yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl."js/admin.js", ['depends' => [\yii\web\JqueryAsset::className()], 'position' =>  \yii\web\View::POS_HEAD]);
//$this->registerJsFile(Yii::$app->homeUrl."template/production/js/input_mask/jquery.inputmask.js", ['depends' => [\yii\web\JqueryAsset::className()], 'position' =>  \yii\web\View::POS_HEAD]);

$token = $this->renderDynamic('return Yii::$app->request->csrfToken;');

$jsx = <<< 'SCRIPT'
    IndexObj.initialScript();
    AdminObj.initialScript();
SCRIPT;
$this->registerJs('IndexObj.baseUrl = "'. Yii::$app->homeUrl.'"', \yii\web\View::POS_HEAD);
$this->registerJs('IndexObj.csrfToken = "'. $token.'"',  \yii\web\View::POS_HEAD);
$this->registerJs($jsx);

?>

<div class="page-title">
    <div class="title_left">
        <h3>

         </h3>
    </div>

    <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
            <form  id="searchform" class="input-group"  action="<?=Yii::$app->homeUrl;?>user"  method="GET" > 
                <input type="text" name="search" class="form-control" value="<?=$search;?>" placeholder="Search for...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">Go!</button>
                </span>
            </form>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div class="row">

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>User Data</h2>            
                <button class="btn btn-primary pull-right" id="add-new-user" href="javascript:void(0);"><i class="fa fa-plus"></i>&nbsp;Add New User</button>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                        <tr class="headings">
                            <th width="1%">No</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>User Division</th>
                            <th>User Status</th>
                            <th>Email</th>
                            <th width="9%">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            $start = (int)$offset * (int)$page;
                            foreach ($models as $model) {
                                $start++; 
                                echo '<tr class="odd gradeX">
                                        <td>'.$start.'</td>
                                        <td id="user_username_'.$model['id'].'" >'.$model['username'].'</td>
                                        <td id="user_name_'.$model['id'].'" >'.$model['firstname'].' '.$model['lastname'].'</td>
                                        <td id="user_level_user_'.$model['id'].'" >'.Yii::$app->mycomponent->roleName($model['level_user']).'</td>
                                        <td id="user_status_'.$model['id'].'" >'.Yii::$app->mycomponent->statusName($model['status']).'</td>
                                        <td id="user_email_'.$model['id'].'" >'.$model['email'].'</td>
                                        <td class="center">
                                            <a id="update-user-'.$model['id'].'" href="javascript:void(0);" class="btn btn-xs btn-success update-user" ><i class="fa fa-pencil"></i></a>
                                            <a id="delete-user-'.$model['id'].'" href="javascript:void(0);" class="btn btn-xs btn-danger delete-user" ><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>';
                            }
                        ?>
                    </tbody>

                </table>
            </div>

            <div align="right">
                <?php
                    //display pagination
                    echo LinkPager::widget([
                        'pagination' => $pages,
                    ]);
                ?>
            </div>

        </div>
    </div>
</div>

<!-- Modal Poliklinik -->
<div class="modal fade bs-example-modal-lg" id="myModalUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" id="asasad-id-close-user-add-top" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <b><i class="fa fa-users"></i>&nbsp;<span id="user_form_label"></span></b>
      </div>
      <div class="modal-body">
            <input id="p_ud" type="hidden" value="0" />

            <div class="col-lg-12 col-md-12 col-xs-12">
                <div id="box-username"  class="form-group">
                    <label class="control-label" for="username">Username</label>
                    <input id="username" type="text" class="form-control" value="" />
                    <div id="text-username" class="help-block"></div>
                </div>

                <div id="box-firstname"  class="form-group">
                    <label class="control-label" for="firstname">First Name</label>
                    <input id="firstname" type="text" class="form-control" value="" />
                    <div id="text-firstname" class="help-block"></div>
                </div>

                <div id="box-lastname"  class="form-group">
                    <label class="control-label" for="lastname">Last Name</label>
                    <input id="lastname" type="text" class="form-control" value="" />
                    <div id="text-lastname" class="help-block"></div>
                </div>

                <div id="box-email"  class="form-group">
                    <label class="control-label" for="email">Email</label>
                    <input id="email" type="text" class="form-control" value="" />
                    <div id="text-email" class="help-block"></div>
                </div>

                <div id="box-level_user"  class="form-group">
                    <label class="control-label" for="level_user">User Division</label>
                    <select id="level_user" class="form-control">
                            <option value="<?=Constants::MANAGER_IT?>">Manager IT</option>
                            <option value="<?=Constants::ADMINISTRASI_IT?>">Administrasi IT</option>
                            <option value="<?=Constants::IT_SUPPORT?>">IT Support</option>
                            <option value="<?=Constants::KARYAWAN?>">Karyawan</option>
                    </select>
       
                    <div id="text-level_user" class="help-block"></div>
                </div>

                <div id="box-password"  class="form-group">
                    <label class="control-label" for="password">Password (Default : user)</label>
                    <input id="password" type="password" class="form-control" value="user" />
                    <div id="text-password" class="help-block"></div>
                </div>

            </div>
            <div class="clearfix"></div>
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="xp-pdx-id-save-user" >Save</button>
            <button type="button" class="btn btn-default" id="xp-pdx-id-close-user" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Poliklinik -->