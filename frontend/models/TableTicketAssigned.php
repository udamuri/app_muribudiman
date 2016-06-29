<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_ticket_assigned".
 *
 * @property integer $ticket_id
 * @property integer $user_id
 * @property string $assigned_date
 * @property integer $assigned_user_id
 */
class TableTicketAssigned extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_ticket_assigned';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ticket_id', 'user_id', 'assigned_date', 'assigned_user_id'], 'required'],
            [['ticket_id', 'user_id', 'assigned_user_id'], 'integer'],
            [['assigned_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ticket_id' => 'Ticket ID',
            'user_id' => 'User ID',
            'assigned_date' => 'Assigned Date',
            'assigned_user_id' => 'Assigned User ID',
        ];
    }
}
