<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail_health_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "detail_health_menu"; // identify menu
 	const  LABELMASTER				= "Menu Detail Health";
 	const  LABELFOLDER				= "profile"; // module folder
 	const  LABELPATH				= "detail_health_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "profile"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Dashboard"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Floating Crane","CCTV Code","CCTV Name"];
	
	/* Export */
	public $colnames 				= ["Date","Order No","Order Name","Floating Crane","Mother Vessel","Activity","Datetime Start","Datetime End","Total Time","Degree","Degree 2","PIC","Status"];
	public $colfields 				= ["date","order_no","order_name","floating_crane_name","mother_vessel_name","activity_name","datetime_start","datetime_end","total_time","degree","degree_2","pic","status_name"];




	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];


		$msemp 				= $this->db->query("select * from employees where status_id = 1 and id = '".$karyawan_id."' order by full_name asc")->result(); 
		$field['selemp'] 	= $this->self_model->return_build_select2me($msemp,'','','','flemp','flemp','','','id','full_name',' ','','','',3,'-');

		$msdiv 				= $this->db->query("select * from divisions order by name asc")->result(); 
		$field['seldiv'] 	= $this->self_model->return_build_select2me($msdiv,'','','','fldiv','fldiv','','','id','name',' ','','','',1,'-');

		$field['master_emp'] = $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		
		return $field;
	}

	//========================== Considering Already Fixed =======================//
 	/* Construct */
	public function __construct() {
        parent::__construct(); 
        
        
		# akses level
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]); 
		/*define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',$akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);*/
    }

	/* Module */
 	public $folder_name				= self::LABELFOLDER."/".self::LABELPATH; // module path
 	public $module_name				= self::LABELMODULE;
 	public $model_name				= self::LABELPATH."_model";

	/* Navigation */
 	public $parent_menu				= self::LABELFOLDER;
 	public $subparent_menu			= self::LABELNAVSEG1;
 	public $subparentitem_menu		= self::LABELNAVSEG2;
 	public $sub_menu 				= self::LABELMODULE;

	/* Label */
 	public $label_parent_modul		= self::LABELFOLDER;
 	public $label_subparent_modul	= self::LABELSUBPARENTSEG1;
 	public $label_subparentitem_modul	= self::LABELSUBPARENTSEG2;
 	public $label_modul				= self::LABELMASTER;
 	public $label_list_data			= "Daftar Data ".self::LABELMASTER;
 	public $label_add_data			= "Tambah Data ".self::LABELMASTER;
 	public $label_update_data		= "Edit Data ".self::LABELMASTER;
 	public $label_sukses_disimpan 	= "Data berhasil disimpan";
 	public $label_gagal_disimpan 	= "Data gagal disimpan";
 	public $label_delete_data		= "Hapus Data ".self::LABELMASTER;
 	public $label_sukses_dihapus 	= "Data berhasil dihapus";
 	public $label_gagal_dihapus 	= "Data gagal dihapus";
 	public $label_detail_data		= "Datail Data ".self::LABELMASTER;
 	public $label_import_data		= "Import Data ".self::LABELMASTER;
 	public $label_sukses_diimport 	= "Data berhasil diimport";
 	public $label_gagal_diimport 	= "Import data di baris : ";
 	public $label_export_data		= "Export";
 	public $label_gagal_eksekusi 	= "Eksekusi gagal karena ketiadaan data";

	//============================== Additional Method ==============================//


 	public function get_data_total(){
 		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

 		$post = $this->input->post(null, true);
		$flemp 	= $post['flemp'];
		$fldateperiod 	= $post['fldateperiod'];


		$whr_emp = " and employee_id = '".$karyawan_id."'";
		if(!empty($flemp)){
			$whr_emp = " and employee_id = '".$flemp."'";
		}

		$whr_period_daily=""; $whr_period="";
		if($fldateperiod != ''){
			$exp = explode(" - ",$fldateperiod);
			$start = $exp[0];
			$end = $exp[1];

			$whr_period_daily = " and (date between '".$start."' and '".$end."')";
			$whr_period = " and (DATE_FORMAT(ts_utc, '%Y-%m-%d') between '".$start."' and '".$end."')";
		}


		
		$health_raw_spo2 = $this->db->query("select * from health_raw_spo2 where 1=1 ".$whr_emp.$whr_period." order by ts_utc desc limit 1")->result(); 
		$spo2 ="0"; $spo2_desc ="-";
		if(!empty($health_raw_spo2)){
			$spo2 = $health_raw_spo2[0]->pct;

			if ($spo2 >= 95) {
			    $spo2_desc = "Excellent";
			} else if ($spo2 >= 91) { 
			    $spo2_desc = "Good";
			} else if($spo2 < 91) { // berarti <91
			    $spo2_desc = "Not Good";
			}
		}
		


		$health_raw_hr = $this->db->query("select * from health_raw_hr where 1=1 ".$whr_emp.$whr_period." order by ts_utc desc limit 1")->result(); 
		$bpm ="0"; $bpm_desc="-";
		if(!empty($health_raw_hr)){
			$bpm = $health_raw_hr[0]->bpm;

			if ($bpm >= 60 && $bpm <= 100) {
			    $bpm_desc = "Normal";
			} else if ($bpm < 60) {
			    $bpm_desc = "Low";
			} else if($bpm > 100) { // > 100
			    $bpm_desc = "High";
			}
		}
		


		$health_daily = $this->db->query("select * from health_daily where 1=1 ".$whr_emp.$whr_period_daily." order by date desc limit 1")->result(); 
		$sleep_hours="0"; $lastLog=""; $sleep_percent="0"; $sleep_mins="0";
		$calories="0"; $hr_avg_bpm="0"; $spo2_avg_pct="0"; $steps="0"; 
		$sleep_hours_format = "0";
		$fatigue_percentage = '';
		$fatigue_category = '-';
		if (!empty($health_daily)) {
			$lastLog 		=  $health_daily[0]->date;
			$calories 		= $health_daily[0]->active_calories_kcal;
			$hr_avg_bpm 	= $health_daily[0]->hr_avg_bpm;
			$spo2_avg_pct 	= $health_daily[0]->spo2_avg_pct;
			$steps 			= $health_daily[0]->steps;

		    $sleep_minutes = $health_daily[0]->sleep_minutes;
		    // hitung persentase dari 8 jam (480 menit)
		    $sleep_percent = min(100, round(($sleep_minutes / 480) * 100));

		    // ubah ke jam
		    $sleep_hours_format = round($sleep_minutes / 60, 1); // 1 decimal
			$hours 				= floor($sleep_minutes / 60);
			$minutes 			= $sleep_minutes % 60;
			$sleep_hours 		= $hours;
			$sleep_mins 		= $minutes;

		    // kategori 
		    if ($sleep_hours_format >= 7 && $sleep_hours_format <= 9) {
		        $sleep_desc = "Optimal";
		    } else if ($sleep_hours_format >= 5) {
		        $sleep_desc = "Fair";
		    } else {
		        $sleep_desc = "Poor";
		    }


		    $result = $this->calculateFatigue($sleep_hours_format, $bpm, $spo2);
			$fatigue_percentage = $result['percentage'].'%';
			$fatigue_category = $result['category'];

		}


		
		

		

		$rs = array(
			'bpm'		=> $bpm,
			'bpm_desc' 	=> $bpm_desc,
			'spo2' 		=> $spo2,
			'spo2_desc' => $spo2_desc,
			'sleep_hours' 	=> $sleep_hours,
			'sleep_mins' 	=> $sleep_mins,
			'sleep_desc' 	=> $sleep_desc,
			'sleep_percent' => $sleep_percent,
			'fatigue_percentage' 	=> $fatigue_percentage,
			'fatigue_category' 		=> $fatigue_category,
			'steps'			=> $steps,
			'calories' 		=> $calories,
			'hr_avg_bpm' 	=> $hr_avg_bpm,
			'spo2_avg_pct'	=> $spo2_avg_pct
			
			
		);


		
		echo json_encode($rs);
 	}


 	public function calculateFatigue($sleepHours, $bpm, $spo2) { 
	    $score = 0;

	    // Sleep
	    if ($sleepHours < 5) $score += 3;
	    elseif ($sleepHours < 7) $score += 2;
	    else $score += 1;

	    // BPM
	    if ($bpm > 90) $score += 3;
	    elseif ($bpm > 80) $score += 2;
	    else $score += 1;

	    // SpO2
	    if ($spo2 < 94) $score += 3;
	    elseif ($spo2 < 96) $score += 2;
	    else $score += 1;

	    // Hitung persentase
	    $percentage = round(($score / 9) * 100);

	    // Tentukan kategori
	    if ($percentage >= 67) {
	        $category = "High";
	    } elseif ($percentage >= 34) {
	        $category = "Moderate";
	    } else {
	        $category = "Low";
	    }

	    // Return dalam bentuk array
	    return [
	        'score'      => $score,
	        'percentage' => $percentage,
	        'category'   => $category
	    ];
	}


 	public function get_data_steps(){
 		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

 		$post = $this->input->post(null, true);
 		$flemp 	= $post['flemp'];
 		$fldateperiod 	= $post['fldateperiod'];


		$whr_emp=" and employee_id = '".$karyawan_id."'";
		if(!empty($flemp)){ 
			$whr_emp=" and employee_id = '".$flemp."'";
		}
		$whr_period_daily=""; 
		if($fldateperiod != ''){
			$exp = explode(" - ",$fldateperiod);
			$start = $exp[0];
			$end = $exp[1];

			$whr_period_daily = " and (date between '".$start."' and '".$end."')";
		}



    	$rs = $this->db->query("select id, employee_id, date, steps from health_daily where 1=1 ".$whr_emp.$whr_period_daily." group by date ORDER BY date")->result(); 

		$date=[]; $steps=[]; 
		foreach($rs as $row){
			$date[] 	= $row->date;
			$steps[] 	= $row->steps;
			
		}


		$data = array(
			'date' 	=> $date,
			'steps'	=> $steps
		);


		echo json_encode($data);


 	}


 	public function get_data_sleeps(){
 		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		
 		$post = $this->input->post(null, true);
 		$flemp 	= $post['flemp'];
 		$fldateperiod 	= $post['fldateperiod'];


		$whr_emp=" and employee_id = '".$karyawan_id."'";
		if(!empty($flemp)){ 
			$whr_emp=" and employee_id = '".$flemp."'";
		}
		$whr_period_daily=""; 
		if($fldateperiod != ''){
			$exp = explode(" - ",$fldateperiod);
			$start = $exp[0];
			$end = $exp[1];

			$whr_period_daily = " and (date between '".$start."' and '".$end."')";
		}


    	$rs = $this->db->query("select id, employee_id, date, sleep_minutes from health_daily where 1=1 ".$whr_emp.$whr_period_daily." group by date ORDER BY date")->result(); 

		$date=[]; $sleeps=[]; 
		foreach($rs as $row){
			$date[] 	= $row->date;
			$sleeps[] 	= $row->sleep_minutes;
			
		}


		$data = array(
			'date' 		=> $date,
			'sleeps'	=> $sleeps
		);


		echo json_encode($data);


 	}


 	public function get_data_vitalSigns()
	{
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		
 		$post = $this->input->post(null, true);
 		$flemp 	= $post['flemp'];
 		$fldateperiod 	= $post['fldateperiod'];


		$employee_id = $karyawan_id;
		if(!empty($flemp)){ 
			$employee_id = $flemp;
		}
		$whr_period=""; $whr_period2="";
		if($fldateperiod != ''){
			$exp = explode(" - ",$fldateperiod);
			$start = $exp[0];
			$end = $exp[1];

			$whr_period = " and (DATE_FORMAT(hr.ts_utc, '%Y-%m-%d') between '".$start."' and '".$end."')";
			$whr_period2 = " and (DATE_FORMAT(sp.ts_utc, '%Y-%m-%d') between '".$start."' and '".$end."')";
		}
	    

	    $sql = "
			    select hr.ts_utc, hr.bpm, sp.pct
			    FROM health_raw_hr hr
			    LEFT JOIN health_raw_spo2 sp 
			        ON hr.ts_utc = sp.ts_utc 
			       AND hr.employee_id = sp.employee_id
			    WHERE hr.employee_id = ".$employee_id.$whr_period."

			    UNION

			    SELECT sp.ts_utc, hr.bpm, sp.pct
			    FROM health_raw_spo2 sp
			    LEFT JOIN health_raw_hr hr 
			        ON hr.ts_utc = sp.ts_utc 
			       AND hr.employee_id = sp.employee_id
			    WHERE sp.employee_id = ".$employee_id.$whr_period2."

			    ORDER BY ts_utc ASC
			";



	    $rs = $this->db->query($sql)->result();


	    $ts_utc = [];
	    $bpm 	= [];
	    $spo2 	= [];

	    if(!empty($rs)){
	    	foreach ($rs as $row) {
		        $ts_utc[]	= $row->ts_utc;
		        $bpm[]  	= $row->bpm;
		        $spo2[]   	= $row->pct;
		    }
	    }
	    

	    $data = array(
	        'ts_utc'	=> $ts_utc,
	        'bpm'   	=> $bpm,
	        'spo2'    	=> $spo2
	    );

	    echo json_encode($data);
	}


 	


}
