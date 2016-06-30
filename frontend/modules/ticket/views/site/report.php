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
$this->registerJs('TicketObj.typex = 2',  \yii\web\View::POS_HEAD);
$this->registerJs($jsx);

?>

<div class="page-title">
    <div class="title_left">
        <h3>

         </h3>
    </div>

    <div class="title_right">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?php
                /**
                 * THE VIEW BUTTON
                 */
                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/pdf-report?progress='.$progress.'&month='.$month.'&year='.$year.' '], [
                    'class'=>'btn btn-danger', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'generated PDF file'
                ]);
            ?>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right top_search">
            <form  id="searchform" class="input-group"  action="<?=Yii::$app->homeUrl;?>ticket-report"  method="GET" > 
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <?php
                        $p10 = $progress == 1000 ? 'selected':'';
                        $p0 = $progress == Constants::waiting_queues ? 'selected':'';
                        $p1 = $progress == Constants::repair_proces ? 'selected':'';
                        $p2 = $progress == Constants::finished ? 'selected':'';
                    ?>
                     <select name="progress" class="form-control" >
                            <option <?=$p10;?> value="1000">all progress</option>
                            <option <?=$p0;?> value="<?=Constants::waiting_queues;?>"><?=Yii::$app->mycomponent->progressName(Constants::waiting_queues);?></option>
                            <option <?=$p1;?> value="<?=Constants::repair_proces;?>"><?=Yii::$app->mycomponent->progressName(Constants::repair_proces);?></option>
                            <option <?=$p2;?> value="<?=Constants::finished;?>"><?=Yii::$app->mycomponent->progressName(Constants::finished);?></option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <?php
                        $a1 = $month == 1 ? 'selected':'';
                        $a2 = $month == 2 ? 'selected':'';
                        $a3 = $month == 3 ? 'selected':'';
                        $a4 = $month == 4 ? 'selected':'';
                        $a5 = $month == 5 ? 'selected':'';
                        $a6 = $month == 6 ? 'selected':'';
                        $a7 = $month == 7 ? 'selected':'';
                        $a8 = $month == 8 ? 'selected':'';
                        $a9 = $month == 9 ? 'selected':'';
                        $a10 = $month == 10 ? 'selected':'';
                        $a11 = $month == 11 ? 'selected':'';
                        $a12 = $month == 12 ? 'selected':'';
                    ?>
                    <select name="month" class="form-control" >
                            <option <?=$a1;?> value="01">January</option>
                            <option <?=$a2;?> value="02">February</option>
                            <option <?=$a3;?> value="03">March</option>
                            <option <?=$a4;?> value="04">April</option>
                            <option <?=$a5;?> value="05">May</option>
                            <option <?=$a6;?> value="06">June</option>
                            <option <?=$a7;?> value="07">July</option>
                            <option <?=$a8;?> value="08">August</option>
                            <option <?=$a9;?> value="09">September</option>
                            <option <?=$a10;?> value="10">October</option>
                            <option <?=$a11;?> value="11">November</option>
                            <option <?=$a12;?> value="12">December</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <select name="year" class="form-control" >
                            <?php
                                for($i=(date('Y'));$i>=(date('Y')-20);$i--)
                                {
                                    $selected = '';
                                    if($year == $i)
                                    {
                                        $selected = 'selected';  
                                    }
                                    echo '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
                                }
                            ?>
                    </select>
                </div>
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
                <h3>Report</h3>            
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                        <tr class="headings">
                            <th width="1%">No</th>
                            <th></th>
                            <th>User Name</th>
                            <th>Ticket Name</th>
                            <th>Description</th>
                            <th>Create Date</th>
                            <th>Ticket Progress</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            $start = (int)$offset * (int)$page;
                            foreach ($models as $model) {
                                $start++; 
                                $assigned = Yii::$app->mycomponent->assignedListNormal($model['ticket_id']);
                                echo '<tr class="odd gradeX">
                                        <td>'.$start.'</td>
                                        <td id="avatar_username_'.$model['user_id'].'" > <img src="'.Yii::$app->mycomponent->userAvatar($model['user_id']).'" class="avatar-user"/> </td>
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