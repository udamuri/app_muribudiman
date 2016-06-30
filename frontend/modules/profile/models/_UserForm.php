<?php
namespace frontend\modules\petugas\models;

use app\components\Constants;
use yii\base\Model;
use frontend\models\TablePetugas;
use Yii;


class PetugasForm extends Model
{
    public $petugas_id;
    public $petugas_nama;
    public $petugas_jk;
    public $petugas_telfon;
    public $petugas_alamat;
    public $petugas_tp_lahir;
    public $petugas_tg_lahir;
  	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			['petugas_nama', 'filter', 'filter' => 'trim'],
            ['petugas_nama', 'required'],
            ['petugas_nama', 'string', 'max' => 200],

			['petugas_jk', 'integer'],
			['petugas_jk', 'required'],
			['petugas_jk', 'in', 'range' => [Constants::LAKILAKI, Constants::PEREMPUAN] ],

            ['petugas_tp_lahir', 'filter', 'filter' => 'trim'],
            ['petugas_tp_lahir', 'required'],
            ['petugas_tp_lahir', 'string', 'max' => 100],
		
            ['petugas_tg_lahir', 'required'],
            ['petugas_tg_lahir', 'date', 'format' => 'dd-mm-yyyy'],

            ['petugas_alamat', 'filter', 'filter' => 'trim'],
            ['petugas_alamat', 'required'],
            ['petugas_alamat', 'string', 'max' => 255],

            ['petugas_telfon', 'filter', 'filter' => 'trim'],
            ['petugas_telfon', 'required'],
            ['petugas_telfon', 'string', 'max' => 20],
            		
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
            $create = new TablePetugas();
            $create->petugas_nama = trim(strip_tags($this->petugas_nama));
            $create->petugas_jk = $this->petugas_jk;
            $create->petugas_tp_lahir = trim(strip_tags($this->petugas_tp_lahir));
            $create->petugas_tg_lahir = Yii::$app->mycomponent->dateExplode($this->petugas_tg_lahir);
            $create->petugas_alamat = trim(strip_tags($this->petugas_alamat));
            $create->petugas_telfon = trim(strip_tags($this->petugas_telfon));
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
            $update = TablePetugas::findOne($did);
            $update->petugas_nama = trim(strip_tags($this->petugas_nama));
            $update->petugas_jk = $this->petugas_jk;
            $update->petugas_tp_lahir = trim(strip_tags($this->petugas_tp_lahir));
            $update->petugas_tg_lahir = Yii::$app->mycomponent->dateExplode($this->petugas_tg_lahir);
            $update->petugas_alamat = trim(strip_tags($this->petugas_alamat));
            $update->petugas_telfon = trim(strip_tags($this->petugas_telfon));
            if($update->save(false)) 
            {
               return  array(
                    'status'=>'success',
                    'petugas_nama'=>$update->petugas_nama,
                    'petugas_telfon'=>$update->petugas_telfon,
                );
            }
        }
        
        return null;
    }

    public function getPetugas($p_id)
    {
        $model = $this->findModel($p_id);

        $arrData = array(
                'petugas_id'=>$model['petugas_id'],
                'petugas_nama'=>$model['petugas_nama'],
                'petugas_jk'=>$model['petugas_jk'],
                'petugas_telfon'=>$model['petugas_telfon'],
                'petugas_tg_lahir'=>Yii::$app->mycomponent->dateExplode($model['petugas_tg_lahir']),
                'petugas_alamat'=>$model['petugas_alamat'],
                'petugas_tp_lahir'=>$model['petugas_tp_lahir'],
            );
        if($arrData)
        {
            return $arrData;   
        }

        return null;
       
    }
	

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'petugas_id' => 'ID Petugas',
            'petugas_nama' => 'Nama Petugas',
            'petugas_jk' => 'Jenis Kelamin',
            'petugas_telfon' => 'Telfon',
            'petugas_alamat' => 'Alamat',
            'petugas_tp_lahir' => 'Tempat Lahir',
            'petugas_tg_lahir' => 'Tanggal Lahir',
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
        if (($model = TablePetugas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
