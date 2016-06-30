<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Constants;

$this->title = 'Report PDF';

?>
<table id="example" class="table table-striped responsive-utilities jambo_table">
    <thead>
        <tr class="headings">
            <th width="5%">No</th>
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
            $start = 0;
            foreach ($models as $model) {
                $start++; 
                echo '<tr class="odd gradeX">
                        <td>'.$start.'</td>
                        <td id="avatar_username_'.$model['user_id'].'" > <img src="'.Yii::$app->mycomponent->userAvatar($model['user_id']).'" class="avatar-user"/> </td>
                        <td>
                            '.$model['firstname'].' '.$model['lastname'].'<br>
                            ('.$model['level_user'].')
                        </td>
                        <td>
                            '.$model['ticket_name'].'
                            <div class="assigned-user">
                                '. str_replace('&nbsp', '|', $model['assigned']) .'
                            </div>
                        </td>
                        <td>'.$model['ticket_desc'].'</td>
                        <td>'.$model['ticket_date_create'].'</td>
                        <td>'.$model['ticket_status'].'</td>
                    </tr>';
            }
        ?>
    </tbody>

</table>
