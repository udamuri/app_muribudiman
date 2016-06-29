<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Constants;


$this->title = 'Ticket';

$this->registerJsFile(Yii::$app->homeUrl."js/index.js", ['depends' => [\yii\web\JqueryAsset::className()], 'position' =>  \yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::$app->homeUrl."js/ticket.js", ['depends' => [\yii\web\JqueryAsset::className()], 'position' =>  \yii\web\View::POS_HEAD]);
//$this->registerJsFile(Yii::$app->homeUrl."template/production/js/input_mask/jquery.inputmask.js", ['depends' => [\yii\web\JqueryAsset::className()], 'position' =>  \yii\web\View::POS_HEAD]);

$token = $this->renderDynamic('return Yii::$app->request->csrfToken;');

$jsx = <<< 'SCRIPT'
    IndexObj.initialScript();
    TicketObj.initialScript();
SCRIPT;
$this->registerJs('IndexObj.baseUrl = "'. Yii::$app->homeUrl.'"', \yii\web\View::POS_HEAD);
$this->registerJs('IndexObj.csrfToken = "'. $token.'"',  \yii\web\View::POS_HEAD);
$this->registerJs('TicketObj.typex = 1',  \yii\web\View::POS_HEAD);
$this->registerJs($jsx);

?>

<div class="page-title">
    <div class="title_left">
        <h3>

         </h3>
    </div>

    <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
            <form  id="searchform" class="input-group"  action="<?=Yii::$app->homeUrl;?>user-ticket"  method="GET" > 
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
                <h3>Ticket Service</h3>            
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                        <tr class="headings">
                            <th width="1%">No</th>
                            <th>User Name</th>
                            <th>Ticket Name</th>
                            <th>Description</th>
                            <th>Create Date</th>
                            <th>Ticket Progress</th>
                            <th width="8%">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            $start = (int)$offset * (int)$page;
                            foreach ($models as $model) {
                                $start++; 
                                $assigned = Yii::$app->mycomponent->assignedList($model['ticket_id']);
                                echo '<tr class="odd gradeX">
                                        <td>'.$start.'</td>
                                        <td id="all_ticket_user_name_'.$model['ticket_id'].'" >
                                            '.$model['firstname'].' '.$model['lastname'].'<br>
                                            ('.Yii::$app->mycomponent->roleName($model['level_user']).')
                                        </td>
                                        <td id="all_ticket_name_'.$model['ticket_id'].'" >
                                            '.$model['ticket_name'].'
                                            <div id="assigned-data-ticket-'.$model['ticket_id'].'" class="assigned-user">
                                                '. $assigned .'
                                            </div>
                                        </td>
                                        <td id="all_ticket_desc_'.$model['ticket_id'].'" >'.$model['ticket_desc'].'</td>
                                        <td id="all_ticket_date_create_'.$model['ticket_id'].'" >'.$model['ticket_date_create'].'</td>
                                        <td id="all_ticket_status_'.$model['ticket_id'].'" >'.Yii::$app->mycomponent->progressName($model['ticket_status']).'</td>
                                        <td class="center">
                                            <a id="all-update-ticket-'.$model['ticket_id'].'" href="javascript:void(0);" class="btn btn-xs btn-success all-update-ticket" data-toggle="tooltip" data-placement="bottom" title="Update Ticket" ><i class="fa fa-pencil"></i></a>
                                            <a id="all-assigned-ticket-'.$model['ticket_id'].'" href="javascript:void(0);" class="btn btn-xs btn-primary all-assigned-ticket" data-toggle="tooltip" data-placement="bottom" title="Assigned Ticket To" ><i class="fa fa-users"></i></a>
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

<!-- Modal Ticket -->
<div class="modal fade bs-example-modal-lg" id="myModalTicketStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" id="asasad-id-close-status-ticket-add-top" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <b><i class="fa fa-users"></i>&nbsp;<span id="process_ticket_form_label"></span></b>
      </div>
      <div class="modal-body">
            <input id="p_ud" type="hidden" value="0" />
            <div class="col-lg-12 col-md-12 col-xs-12">
                    <button id="ticket-process-data-<?=Constants::waiting_queues?>" class="btn btn-danger btn-lg ticket-process-data"><?=Yii::$app->mycomponent->progressName(Constants::waiting_queues)?></button>
                    <button id="ticket-process-data-<?=Constants::repair_proces?>" class="btn btn-warning btn-lg ticket-process-data"><?=Yii::$app->mycomponent->progressName(Constants::repair_proces)?></button>
                    <button id="ticket-process-data-<?=Constants::finished?>"class="btn btn-primary btn-lg ticket-process-data"><?=Yii::$app->mycomponent->progressName(Constants::finished)?></button>
            </div>
            <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Ticket -->

<!-- Modal Ticket -->
<div class="modal fade bs-example-modal-lg" id="myModalItSupport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" id="asasad-id-close-assignet-add-top" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <b><i class="fa fa-users"></i>&nbsp;<span id="assigned_form_label"></span></b>
      </div>
      <div class="modal-body">
            <input type="hidden" id="t_idx" value="0" />
            <div id="it-support-container" class="row">

            </div>
            <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Ticket -->