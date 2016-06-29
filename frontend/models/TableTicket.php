<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_ticket".
 *
 * @property integer $ticket_id
 * @property string $ticket_name
 * @property string $ticket_desc
 * @property string $ticket_date_create
 * @property string $ticket_date_update
 * @property integer $ticket_status
 * @property integer $user_id
 */
class TableTicket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_ticket';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ticket_name', 'ticket_desc', 'ticket_desc_support','ticket_date_create', 'ticket_date_update', 'ticket_status', 'user_id'], 'required'],
            [['ticket_desc', 'ticket_desc_support'], 'string', 'max' => 255],
            [['ticket_date_create', 'ticket_date_update'], 'safe'],
            [['ticket_status', 'user_id'], 'integer'],
            [['ticket_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ticket_id' => 'Ticket ID',
            'ticket_name' => 'Ticket Name',
            'ticket_desc' => 'Ticket Desc',
            'ticket_date_create' => 'Ticket Date Create',
            'ticket_date_update' => 'Ticket Date Update',
            'ticket_status' => 'Ticket Status',
            'user_id' => 'User ID',
        ];
    }
}
