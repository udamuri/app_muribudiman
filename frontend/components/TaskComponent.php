<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
//use yii\helpers\BaseFileHelper;
use app\components\Constants;
use yii\helpers\FileHelper;
use yii\db\Query;
 
class TaskComponent extends Component
{
	//Yii::$app->mycomponent->welcome();
	public function welcome()
	{
		return "Hello..Welcome to MyComponent My Name Muri Budiman";
	}

	//Yii::$app->mycomponent->isUserRole($type, $level);
	public function isUserRole($type, $level)
	{
		if(!Yii::$app->user->isGuest)
		{
			if($type === 'admin' && $level === Constants::ADMIN)
			{
				return true;
			}
			else if($type === 'manager_it' && $level === Constants::MANAGER_IT)
			{
				return true;
			}
			else if($type === 'administrasi_it' && $level === Constants::ADMINISTRASI_IT)
			{
				return true;
			}
			else if($type === 'it_support' && $level === Constants::IT_SUPPORT)
			{
				return true;
			}
			else if($type === 'karyawan' && $level === Constants::KARYAWAN)
			{
				return true;
			}
			else if($type === 'admin-administrasi-support' && ($level === Constants::ADMIN || $level === Constants::ADMINISTRASI_IT || $level === Constants::IT_SUPPORT))
			{
				return true;
			}

			return false;
		}
	}

	//Yii::$app->mycomponent->roleName($role);
	public function roleName($role)
	{
		$label = '';
		switch ($role)
		{
		    case Constants::ADMIN:
		    	$label = 'ADMINISTRATOR';
		    	break;
		    case Constants::MANAGER_IT:
		        $label = 'MANAGER';
		    	break;
		    case Constants::ADMINISTRASI_IT:
		        $label = 'ADMINISTRASI';
		    	break;
		    case Constants::IT_SUPPORT:
		        $label = 'IT SUPPORT';
		    	break;
		    case Constants::KARYAWAN:
		        $label = 'KARYAWAN';
		    	break;
		}

		return $label;
	}

	//Yii::$app->mycomponent->progressName($progress);
	public function progressName($progress)
	{
		$label = '';
		switch ($progress)
		{
		    case Constants::waiting_queues :
		    	$label = 'Waiting Queues';
		    	break;
		    case Constants::repair_proces :
		        $label = 'Repair Process';
		    	break;
		    case Constants::finished :
		        $label = 'Finished';
		    	break;
		}

		return $label;
	}

	//Yii::$app->mycomponent->statusName($role);
	public function statusName($status)
	{
		$label = '';
		switch ($status)
		{
		    case '10':
		    	$label = '<span class="act">Active</span>';
		    	break;
		    case '0':
		        $label = '<span class="oft">Off</span>';
		    	break;
		}

		return $label;
	}

	//Yii::$app->mycomponent->createDirectory($user_id);
	public function createDirectory($dir)
    {
        $directory = $dir;
		if(!is_dir($directory))
		{
			FileHelper::createDirectory($directory,0775,true);
		}
	}

	//Yii::$app->mycomponent->dateExplode($date);
	public function dateExplode($date)
	{
		$isDate = explode('-', $date);

		if($isDate)
		{
			return $isDate[2].'-'.$isDate[1].'-'.$isDate[0];
		}

		return null;
	}

	//Yii::$app->mycomponent->dateTimeExplode($date);
	public function dateTimeExplode($date)
	{
		$isDateTime = explode(' ', $date);

		if($isDateTime)
		{
			$isDate = explode('-', $isDateTime[0]);
			$date = '';
			if($isDate)
			{
				$date = $isDate[2].'-'.$isDate[1].'-'.$isDate[0];
			}

			return $date.' '.$isDateTime[1];
		}

		return null;
	}
	
	//Yii::$app->mycomponent->money_format($val, $format);
	public function money_format($val, $format)
    {
		return $format.' '.number_format($val,0,',',',');
	}
	
	//Yii::$app->mycomponent->appLanguangeDate('eng',$date, 'month_year');
    public function appLanguangeDate($lg='eng',$date='',$status='')
    {
		$label = '';
		if($lg == 'eng')
		{
			$monthEng = array("January", "February", "March",
					   "April", "May", "June",
					   "July", "August", "September",
					   "October", "November", "December");
			$year = substr($date, 0, 4); 
			$month = substr($date, 5, 2); 
			$date   = substr($date, 8, 2); 
			$timex =  '';
			$time =  substr($date, 10, 9);
			if($time)
			{
				$timex = $time;
			}
			if($status == '')
			{
				$result = $monthEng[((int)$month - 1)] ." ".$date." ,". $year.' '.$timex;
			}
			else if($status == 'month_year')
			{
				$result = $monthEng[((int)$month - 1)] .",". $year;
			}
			return $result;
		}
		
		if($lg == 'ina')
		{
			$BulanIndo = array("Januari", "Februari", "Maret",
						   "April", "Mei", "Juni",
						   "Juli", "Agustus", "September",
						   "Oktober", "November", "Desember");
			$tahun = substr($date, 0, 4); 
			$bulan = substr($date, 5, 2); 
			$tgl   = substr($date, 8, 2);
			$jamx =  '';
			$jam =  substr($date, 10, 9);
			if($jam)
			{
				$jamx = $jam;
			}
			if($status == '')
			{
				$result = $tgl ." " .$BulanIndo[((int)$bulan - 1)]." ".$tahun .' '.$jam;
			}
			else if($status == 'month_year')
			{
				$result = $BulanIndo[((int)$bulan - 1)]." ".$tahun;
			}

			return $result;
		}
    }
	
	//Yii::$app->mycomponent->getRandomUsername();
	public function getRandomUsername()
	{
		$rand = rand(0, getrandmax());
		$time = time();
		$date = date('Ymd');
		return md5($rand.$time.$date);
	}
	
	//Yii::$app->mycomponent->getUserLevel($in);
	public function getUserLevel($in)
	{
		$level = '';
		switch ($in) {
			case 0:
				$level = 'Member';
				break;
			case 34:
				$level = 'Toko';
				break;
			case 84:
				$level = 'Administrator';
				break;
		}
		
		return $level;
	}

	//Yii::$app->mycomponent->getAgama($in);
	public function getAgama($in)
	{
		$level = '';
		switch ($in) {
			case 1:
				$level = 'Islam';
				break;
			case 2:
				$level = 'Kristen';
				break;
			case 3:
				$level = 'Protestan';
				break;
			case 4:
				$level = 'Hindu';
				break;
			case 5:
				$level = 'Budha';
				break;
		}
		
		return $level;
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

	//Yii::$app->mycomponent->assignedList($tid);
	public function assignedList($tid)
	{
		$model = $this->getAssignedTicket($tid);
		$html = 'assigned To : ';
		if($model)
		{
			foreach ($model as $value) 
			{
				$html .= '<span class="asigned-pointer-style" data-toggle="tooltip" data-placement="bottom" title="'.$value['firstname'].' '.$value['lastname'].'">'.$value['username'].'</span>&nbsp;';
			}
		}

		return $html;
	}

	//Yii::$app->mycomponent->calculateDate($start, $end);
	public function calculateDate($start, $end)
	{
		$dateStart=date_create($start);
		$dateEnd=date_create($end);
		$diff = date_diff($dateStart,$dateEnd);
		
		/*$plusmin = '';
		if($dateStart > $dateEnd)
		{
			$plusmin = '+';
		} */
		$year = '';
		if($diff->y > 0)
		{
			$year = $diff->y.' Tahun, ';
		}
		
		$month = '';
		if($diff->m > 0)
		{
			$month = $diff->m.' Bulan, ';
		}
		
		$day = '';
		if($diff->d > 0)
		{
			$day = $diff->d.' Hari';
		}
		
		return $year.$month.$day;
	}
	
	//Yii::$app->mycomponent->getTimeZoneList();
	public static function getTimeZoneList()
	{
		return array(
			'UTC'=> "(GMT) UTC",
			'Pacific/Midway'       => "(GMT-11:00) Midway Island, Samoa",
			//'US/Samoa'             => "(GMT-11:00) Samoa",
			'US/Hawaii'            => "(GMT-10:00) Hawaii",
			'US/Alaska'            => "(GMT-09:00) Alaska",
			'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada), Tijuana",
			//'America/Tijuana'      => "(GMT-08:00) Tijuana",
			'US/Arizona'           => "(GMT-07:00) Arizona, Mountain Time (US &amp; Canada)",
			//'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
			'America/Chihuahua'    => "(GMT-07:00) Chihuahua, Mazatlan",
			// 'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
			'America/Mexico_City'  => "(GMT-06:00) Mexico City, Monterrey, Saskatchewan",
			// 'America/Monterrey'    => "(GMT-06:00) Monterrey",
			// 'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
			'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
			'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada), Indiana (East)",
			// 'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
			'America/Bogota'       => "(GMT-05:00) Bogota, Lima",
			// 'America/Lima'         => "(GMT-05:00) Lima",
			'America/Caracas'      => "(GMT-04:30) Caracas, Atlantic Time (Canada), La Paz, Santiago",
			// 'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
			// 'America/La_Paz'       => "(GMT-04:00) La Paz",
			// 'America/Santiago'     => "(GMT-04:00) Santiago",
			'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland, Buenos Aires, Greenland",
			// 'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
			// 'America/Godthab'      => "(GMT-03:00) Greenland",
			'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
			'Atlantic/Azores'      => "(GMT-01:00) Azores, Cape Verde Is.",
			//'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
			'Africa/Casablanca'    => "(GMT) Casablanca, Dublin, Lisbon, London, Monrovia",
			// 'Europe/Dublin'        => "(GMT) Dublin",
			// 'Europe/Lisbon'        => "(GMT) Lisbon",
			// 'Europe/London'        => "(GMT) London",
			// 'Africa/Monrovia'      => "(GMT) Monrovia",
			'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam, Belgrade, Berlin, Bratislava, Brussels",
			// 'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
			// 'Europe/Berlin'        => "(GMT+01:00) Berlin",
			// 'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
			// 'Europe/Brussels'      => "(GMT+01:00) Brussels",
			'Europe/Budapest'      => "(GMT+01:00) Budapest, Copenhagen",
			// 'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
			'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana, Madrid, Paris, Prague, Rome, Sarajevo",
			// 'Europe/Madrid'        => "(GMT+01:00) Madrid",
			// 'Europe/Paris'         => "(GMT+01:00) Paris",
			// 'Europe/Prague'        => "(GMT+01:00) Prague",
			// 'Europe/Rome'          => "(GMT+01:00) Rome",
			// 'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
			'Europe/Skopje'        => "(GMT+01:00) Skopje, Stockholm, Vienna",
			// 'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
			// 'Europe/Vienna'        => "(GMT+01:00) Vienna",
			'Europe/Warsaw'        => "(GMT+01:00) Warsaw, Zagreb",
			// 'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
			'Europe/Athens'        => "(GMT+02:00) Athens, Bucharest, Cairo, Harare, Helsinki, Istanbul",
			// 'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
			// 'Africa/Cairo'         => "(GMT+02:00) Cairo",
			// 'Africa/Harare'        => "(GMT+02:00) Harare",
			// 'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
			// 'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
			'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem, Kyiv, Minsk",
			// 'Europe/Kiev'          => "(GMT+02:00) Kyiv",
			// 'Europe/Minsk'         => "(GMT+02:00) Minsk",
			'Europe/Riga'          => "(GMT+02:00) Riga, Sofia, Tallinn, Vilnius",
			// 'Europe/Sofia'         => "(GMT+02:00) Sofia",
			// 'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
			// 'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
			'Asia/Baghdad'         => "(GMT+03:00) Baghdad, Kuwait, Nairobi, Riyadh, Tehran",
			// 'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
			// 'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
			// 'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
			// 'Asia/Tehran'          => "(GMT+03:30) Tehran",
			'Europe/Moscow'        => "(GMT+04:00) Moscow, Baku, Volgograd, Muscat, Tbilisi, Yerevan",
			// 'Asia/Baku'            => "(GMT+04:00) Baku",
			// 'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
			// 'Asia/Muscat'          => "(GMT+04:00) Muscat",
			// 'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
			// 'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
			'Asia/Kabul'           => "(GMT+04:30) Kabul",
			'Asia/Karachi'         => "(GMT+05:00) Karachi, Tashkent",
			// 'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
			'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
			'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
			'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg, Almaty, Dhaka",
			// 'Asia/Almaty'          => "(GMT+06:00) Almaty",
			// 'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
			// 'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
			'Asia/Bangkok'         => "(GMT+07:00) Bangkok, Jakarta, Novosibirsk",
			// 'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
			'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk, Chongqing, Hong Kong, Kuala Lumpur",
			// 'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
			// 'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
			// 'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
			'Australia/Perth'      => "(GMT+08:00) Perth, Singapore",
			// 'Asia/Singapore'       => "(GMT+08:00) Singapore",
			'Asia/Taipei'          => "(GMT+08:00) Taipei, Ulaan Bataar, Urumqi",
			// 'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
			// 'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
			'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk, Seoul, Tokyo",
			// 'Asia/Seoul'           => "(GMT+09:00) Seoul",
			// 'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
			'Australia/Adelaide'   => "(GMT+09:30) Adelaide, Darwin",
			//'Australia/Darwin'     => "(GMT+09:30) Darwin",
			'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk, Brisbane, Canberra, Guam, Hobart, Melbourne",
			// 'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
			// 'Australia/Canberra'   => "(GMT+10:00) Canberra",
			// 'Pacific/Guam'         => "(GMT+10:00) Guam",
			// 'Australia/Hobart'     => "(GMT+10:00) Hobart",
			// 'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
			'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby, Sydney",
			// 'Australia/Sydney'     => "(GMT+10:00) Sydney",
			'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
			'Asia/Magadan'         => "(GMT+12:00) Magadan, Auckland, Fiji",
			// 'Pacific/Auckland'     => "(GMT+12:00) Auckland",
			// 'Pacific/Fiji'         => "(GMT+12:00) Fiji",
		);
	}
	
	//Yii::$app->mycomponent->CutContent($data['content_blog'],300);
	public function CutContent($xkata,$lebar)
	{
		$kalimat = str_replace("<br>"," ",$xkata);
		$kalimat = html_entity_decode(strip_tags($kalimat), ENT_QUOTES);
		$panjang = substr($kalimat,0,$lebar); 	
		if(strlen($kalimat) > $lebar)
			{
			$cari_spasi = strpos($kalimat," ",$lebar);
			$kalimatBaru = substr($kalimat,0,$cari_spasi);
			if($cari_spasi=="") $kalimatBaru = $kalimat;
			}
		if(strlen($kalimat) <= $lebar) $kalimatBaru=$kalimat;
		//return html_entity_decode(strip_tags($kalimatBaru . ' ...'), ENT_QUOTES);
		return $kalimatBaru . ' ... ' ;
	}
	

}
?>