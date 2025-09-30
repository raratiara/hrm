<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dash_health_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "dash_health_menu"; // identify menu
 	const  LABELMASTER				= "Menu Dashboard Health";
 	const  LABELFOLDER				= "dashboard"; // module folder
 	const  LABELPATH				= "dash_health_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "dashboard"; // adjusted 1st sub parent segment
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
		

		$msemp 				= $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
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
 		
 		$post = $this->input->post(null, true);
		$flemp 	= $post['flemp'];
		$fldateperiod 	= $post['fldateperiod'];


		
		$whr_emp = "";
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


		

		$health_daily = $this->db->query("select * from health_daily where 1=1 ".$whr_emp.$whr_period_daily." ")->result(); 

		$sleep_hours="0"; $lastLog=""; $sleep_percent="0"; $sleep_mins="0";
		$calories="0"; $hr_avg_bpm="0"; $spo2_avg_pct="0"; $steps="0"; 
		$sleep_hours_format = "0";
		$fatigue_percentage = '';
		$fatigue_category = '-';
		$bpm ="0"; $bpm_desc="-";
		$spo2 ="0"; $spo2_desc ="-";

		
		if (!empty($health_daily)) {
			/*$lastLog 		= $health_daily[0]->date;
			$calories 		= $health_daily[0]->active_calories_kcal;
			$hr_avg_bpm 	= $health_daily[0]->hr_avg_bpm;
			$spo2_avg_pct 	= $health_daily[0]->spo2_avg_pct;
			$steps 			= $health_daily[0]->steps;*/

			//hitung rata2
		    $cnt_sleep=0; $sum_sleep=0;
		    $cnt_steps=0; $sum_steps=0; 
			foreach($health_daily as $row){
			    if($row->sleep_minutes != 0){
			    	$cnt_sleep += 1;
			    	$sum_sleep += $row->sleep_minutes;
			    }
			    if($row->steps != 0){
			    	$cnt_steps += 1;
			    	$sum_steps += $row->steps;
			    }
			} 
			$avg_sleep=0; $avg_steps=0;
			if($cnt_sleep !=0){
				$avg_sleep = floor($sum_sleep/$cnt_sleep); 
			}
			if($cnt_steps !=0){
				$avg_steps = floor($sum_steps/$cnt_steps);
			}
			

		    $sleep_minutes = $avg_sleep;


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

		}


		$health_raw_spo2 = $this->db->query("select * from health_raw_spo2 where 1=1 ".$whr_emp.$whr_period." ")->result(); 
			
		if(!empty($health_raw_spo2)){
			$cnt_spo2=0; $sum_spo2=0;
			foreach($health_raw_spo2 as $row2){
				if($row2->pct != '' && $row2->pct != 0){
					$cnt_spo2 += 1;
			    	$sum_spo2 += $row2->pct;
				}
			}
			$avg_spo2=0;
			if($cnt_spo2 !=0){
				$avg_spo2 = round($sum_spo2/$cnt_spo2,2);
			}
			


			$spo2 = $avg_spo2;

			if ($spo2 >= 95) {
			    $spo2_desc = "Excellent";
			} else if ($spo2 >= 91) { 
			    $spo2_desc = "Good";
			} else if($spo2 < 91) { // berarti <91
			    $spo2_desc = "Not Good";
			}
		}
		


		$health_raw_hr = $this->db->query("select * from health_raw_hr where 1=1 ".$whr_emp.$whr_period." ")->result(); 
		
		if(!empty($health_raw_hr)){
			$cnt_bpm=0; $sum_bpm=0;
			foreach($health_raw_hr as $row3){
				if($row3->bpm !='' && $row3->bpm !=0){
					$cnt_bpm += 1;
			    	$sum_bpm += $row3->bpm;
				}
			}
			$avg_bpm=0;
			if($cnt_bpm !=0){
				$avg_bpm = round($sum_bpm/$cnt_bpm,2);
			}
			

			$bpm = $avg_bpm;

			if ($bpm >= 60 && $bpm <= 100) {
			    $bpm_desc = "Normal";
			} else if ($bpm < 60) {
			    $bpm_desc = "Low";
			} else if($bpm > 100) { // > 100
			    $bpm_desc = "High";
			}
		}


		
		$result = $this->calculateFatigue($sleep_hours_format, $bpm, $spo2);
		$fatigue_percentage = $result['percentage'].'%';
		$fatigue_category = $result['category'];

		

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


 	public function get_data_total_emp(){
 		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

 		$post = $this->input->post(null, true);
		$flemp 	= $post['flemp'];
		$fldateperiod 	= $post['fldateperiod'];


		//$whr_emp = " and employee_id = '".$karyawan_id."'";
		$whr_emp = "";
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


		

		$health_daily = $this->db->query("select * from health_daily where 1=1 ".$whr_emp.$whr_period_daily." order by date desc limit 1")->result(); 

		$sleep_hours="0"; $lastLog=""; $sleep_percent="0"; $sleep_mins="0";
		$calories="0"; $hr_avg_bpm="0"; $spo2_avg_pct="0"; $steps="0"; 
		$sleep_hours_format = "0";
		$fatigue_percentage = '';
		$fatigue_category = '-';
		$bpm ="0"; $bpm_desc="-";
		$spo2 ="0"; $spo2_desc ="-";

		if (!empty($health_daily)) {
			$lastLog 		= $health_daily[0]->date;
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



		    $health_raw_spo2 = $this->db->query("select * from health_raw_spo2 where 1=1 and DATE_FORMAT(ts_utc, '%Y-%m-%d') = '".$lastLog."' ".$whr_emp." order by ts_utc desc limit 1")->result(); 
			
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
			


			$health_raw_hr = $this->db->query("select * from health_raw_hr where 1=1 and DATE_FORMAT(ts_utc, '%Y-%m-%d') = '".$lastLog."' ".$whr_emp." order by ts_utc desc limit 1")->result(); 
			
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

		}


		
		$result = $this->calculateFatigue($sleep_hours_format, $bpm, $spo2);
		$fatigue_percentage = $result['percentage'].'%';
		$fatigue_category = $result['category'];

		

		$rs = array(
			'empid' 	=> $karyawan_id,
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


 	public function calculateFatigue($sleepHours=0, $bpm=0, $spo2=0) {
	    $score = 0;
	    $minScore = 0;
	    $maxScore = 0;

	    // Sleep
	    if ($sleepHours > 0) { // 0 = tidak ada data
	        $minScore += 1; $maxScore += 3;
	        if ($sleepHours < 5) $score += 3;
	        elseif ($sleepHours < 7) $score += 2;
	        else $score += 1;
	    }

	    // SpO2
	    if ($spo2 > 0) { // 0 = tidak ada data
	        $minScore += 1; $maxScore += 3;
	        if ($spo2 < 90) $score += 3;
	        elseif ($spo2 < 95) $score += 2;
	        else $score += 1;
	    }

	    // BPM
	    if ($bpm > 0) { // 0 = tidak ada data
	        $minScore += 1; $maxScore += 3;
	        if ($bpm > 100) $score += 3;
	        elseif ($bpm > 90) $score += 2;
	        else $score += 1;
	    }

	    // Kalau semua kosong (0)
	    if ($maxScore == 0) {
	        return [
	            "score" => 0,
	            "percentage" => 0,
	            "category" => "-"
	        ];
	    }

	    // Hitung persentase
	    $fatiguePercent = (($score - $minScore) / ($maxScore - $minScore)) * 100;

	    // Kategori
	    if ($fatiguePercent <= 30) $status = "Low";
	    elseif ($fatiguePercent <= 65) $status = "Moderate";
	    else $status = "High";

	    return [
	        "score" => $score,
	        "percentage" => round($fatiguePercent, 2),
	        "category" => $status
	    ];

	}


 	public function calculateFatigue_old($sleepHours, $bpm, $spo2) { 
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


 	public function get_data_steps_emp(){
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



    	/*$rs = $this->db->query("select id, employee_id, date, steps from health_daily where 1=1 ".$whr_emp.$whr_period_daily." group by date ORDER BY date")->result();*/ 

    	$rs = $this->db->query("select h.*
				FROM health_daily h
				JOIN (
				    SELECT date, MAX(id) AS max_id
				    FROM health_daily
				    WHERE 1=1  ".$whr_emp.$whr_period_daily."
				    GROUP BY date
				) x ON h.date = x.date AND h.id = x.max_id
				ORDER BY h.date;
				")->result(); 

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



 	public function get_data_steps() {
	    $getdata = $this->db->query("SELECT * FROM user WHERE user_id = '".$_SESSION['id']."'")->result(); 
	    $karyawan_id = $getdata[0]->id_karyawan;

	    $post = $this->input->post(null, true);
	    $flemp  = $post['flemp'];
	    $fldateperiod = $post['fldateperiod'];

	    //$whr_emp=" and employee_id = '".$karyawan_id."'";
	    $whr_emp = "";
	    if(!empty($flemp)){ 
	        $whr_emp = " and a.employee_id = '".$flemp."'";
	    }

	    $whr_period_daily = ""; 
	    if($fldateperiod != ''){
	        $exp = explode(" - ", $fldateperiod);
	        $start = $exp[0];
	        $end   = $exp[1];

	        $whr_period_daily = " and (a.date between '".$start."' and '".$end."')";
	    }

	    // ambil data max id per employee per date
	    $rs = $this->db->query("
	        SELECT h.*, x.empname
	        FROM health_daily h
	        JOIN (
	            SELECT a.date, a.employee_id, MAX(a.id) AS max_id, b.full_name as empname
	            FROM health_daily a
	            LEFT JOIN employees b ON b.id = a.employee_id
	            WHERE 1=1 and a.steps != 0 ".$whr_emp.$whr_period_daily."
	            GROUP BY a.date, a.employee_id
	        ) x ON h.id = x.max_id
	        ORDER BY h.date ASC
	    ")->result();

	    // setelah ambil $rs
	    $dates = [];
	    $employees = [];
	    foreach ($rs as $row) {
	        $dates[$row->date] = true; 
	        $employees[$row->employee_id] = $row->empname; // simpan empname langsung
	    }

	    // buat urut tanggal
	    $dates = array_keys($dates);
	    sort($dates);

	    $datasets = [];
	    $colors = ['#4e79a7','#f28e2b','#e15759','#76b7b2','#59a14f','#edc949','#af7aa1','#ff9da7']; 
	    $colorIndex = 0;

	    foreach ($employees as $empid => $empname) {
	        $data_steps = [];
	        

	        foreach ($dates as $d) {
	            $found = null;
	            foreach ($rs as $row) {
	                if ($row->employee_id == $empid && $row->date == $d) {
	                    $found = $row;
	                    break;
	                }
	            }

	            if ($found) {
	                $data_steps[] = $found->steps;
	               
	            } else {
	                // kalau tidak ada, skip (biar null = Chart.js gak gambar bar)
	                $data_steps[] = null;
	               
	            }
	        }

	        $datasets[] = [
	            'label'       => $empname,   // ← tampilkan nama karyawan
	            'data'        => $data_steps,
	            'backgroundColor' => "#FFE16D",//$colors[$colorIndex % count($colors)],
	            'borderRadius' => 2,
	            'barThickness'=> 12
	        ];

	        $colorIndex++;
	    }

	    $data = [
	        'date'     => $dates,
	        'datasets' => $datasets
	    ];

	    echo json_encode($data);
	}


 	public function get_data_sleeps() {
	    $getdata = $this->db->query("SELECT * FROM user WHERE user_id = '".$_SESSION['id']."'")->result(); 
	    $karyawan_id = $getdata[0]->id_karyawan;

	    $post = $this->input->post(null, true);
	    $flemp  = $post['flemp'];
	    $fldateperiod = $post['fldateperiod'];

	    //$whr_emp=" and employee_id = '".$karyawan_id."'";
	    $whr_emp = "";
	    if(!empty($flemp)){ 
	        $whr_emp = " and a.employee_id = '".$flemp."'";
	    }

	    $whr_period_daily = ""; 
	    if($fldateperiod != ''){
	        $exp = explode(" - ", $fldateperiod);
	        $start = $exp[0];
	        $end   = $exp[1];

	        $whr_period_daily = " and (a.date between '".$start."' and '".$end."')";
	    }

	    // ambil data max id per employee per date
	    $rs = $this->db->query("
	        SELECT h.*, x.empname
	        FROM health_daily h
	        JOIN (
	            SELECT a.date, a.employee_id, MAX(a.id) AS max_id, b.full_name as empname
	            FROM health_daily a
	            LEFT JOIN employees b ON b.id = a.employee_id
	            WHERE 1=1 and a.sleep_minutes != 0 ".$whr_emp.$whr_period_daily."
	            GROUP BY a.date, a.employee_id
	        ) x ON h.id = x.max_id
	        ORDER BY h.date ASC
	    ")->result();

	    // setelah ambil $rs
	    $dates = [];
	    $employees = [];
	    foreach ($rs as $row) {
	        $dates[$row->date] = true; 
	        $employees[$row->employee_id] = $row->empname; // simpan empname langsung
	    }

	    // buat urut tanggal
	    $dates = array_keys($dates);
	    sort($dates);

	    $datasets = [];
	    $colors = ['#4e79a7','#f28e2b','#e15759','#76b7b2','#59a14f','#edc949','#af7aa1','#ff9da7']; 
	    $colorIndex = 0;

	    foreach ($employees as $empid => $empname) {
	        $data_hours = [];
	        $data_mins  = [];

	        foreach ($dates as $d) {
	            $found = null;
	            foreach ($rs as $row) {
	                if ($row->employee_id == $empid && $row->date == $d) {
	                    $found = $row;
	                    break;
	                }
	            }

	            if ($found) {
	                $sleep_hours = round($found->sleep_minutes / 60, 1);
	                $data_hours[] = $sleep_hours;
	                $data_mins[]  = $found->sleep_minutes;
	            } else {
	                // kalau tidak ada, skip (biar null = Chart.js gak gambar bar)
	                $data_hours[] = null;
	                $data_mins[]  = null;
	            }
	        }

	        $datasets[] = [
	            'label'       => $empname,   // ← tampilkan nama karyawan
	            'data'        => $data_hours,
	            'sleeps_mins' => $data_mins,
	            'backgroundColor' => "#6EABC6",//$colors[$colorIndex % count($colors)],
	            'borderRadius' => 2,
	            'barThickness'=> 12
	        ];

	        $colorIndex++;
	    }

	    $data = [
	        'date'     => $dates,
	        'datasets' => $datasets
	    ];

	    echo json_encode($data);
	}




 	public function get_data_sleeps_emp(){
 		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		
 		$post = $this->input->post(null, true);
 		$flemp 	= $post['flemp'];
 		$fldateperiod 	= $post['fldateperiod'];


		//$whr_emp=" and employee_id = '".$karyawan_id."'";
		$whr_emp="";
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


    	
    	$rs = $this->db->query("select h.*
				FROM health_daily h
				JOIN (
				    SELECT date, MAX(id) AS max_id
				    FROM health_daily
				    WHERE 1=1 ".$whr_emp.$whr_period_daily."
				    GROUP BY date, employee_id
				) x ON h.date = x.date AND h.id = x.max_id
				ORDER BY h.date;
				")->result(); 



		$date=[]; $sleeps=[]; $sleeps_mins=[];
		foreach($rs as $row){
			$date[] 	= $row->date;
			// ubah ke jam
		    $sleep_hours 	= round($row->sleep_minutes / 60, 1); // 1 decimal
			$sleeps[] 		= $sleep_hours;
			$sleeps_mins[] 	= $row->sleep_minutes;
			
		}


		$data = array(
			'date' 			=> $date,
			'sleeps'		=> $sleeps,
			'sleeps_mins' 	=> $sleeps_mins
		);


		echo json_encode($data);


 	}


 	public function get_data_vitalSigns_emp()
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
			    WHERE bpm != '' and hr.employee_id = ".$employee_id.$whr_period."

			    UNION

			    SELECT sp.ts_utc, hr.bpm, sp.pct
			    FROM health_raw_spo2 sp
			    LEFT JOIN health_raw_hr hr 
			        ON hr.ts_utc = sp.ts_utc 
			       AND hr.employee_id = sp.employee_id
			    WHERE pct != '' and sp.employee_id = ".$employee_id.$whr_period2."

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


 	public function get_data_vitalSigns()
	{ 
	    $post = $this->input->post(null, true);
	    $flemp       = $post['flemp'];
	    $fldateperiod = $post['fldateperiod'];

	    $whr_emp = ""; $whr_emp2 = "";
	    if(!empty($flemp)){ 
	        $whr_emp  = " and hr.employee_id = '".$flemp."'";
	        $whr_emp2 = " and sp.employee_id = '".$flemp."'";
	    }

	    $whr_period=""; $whr_period2="";
	    if($fldateperiod != ''){
	        $exp = explode(" - ",$fldateperiod);
	        $start = $exp[0];
	        $end   = $exp[1];

	        $whr_period  = " and (DATE_FORMAT(hr.ts_utc, '%Y-%m-%d') between '".$start."' and '".$end."')";
	        $whr_period2 = " and (DATE_FORMAT(sp.ts_utc, '%Y-%m-%d') between '".$start."' and '".$end."')";
	    }
	    
	    $sql = "
	        select hr.ts_utc, hr.bpm, sp.pct, hr.employee_id, e.full_name as empname
	        FROM health_raw_hr hr
	        LEFT JOIN health_raw_spo2 sp 
	            ON hr.ts_utc = sp.ts_utc 
	           AND hr.employee_id = sp.employee_id
	        LEFT JOIN employees e ON e.id = hr.employee_id
	        WHERE bpm != '' ".$whr_emp.$whr_period."

	        UNION

	        SELECT sp.ts_utc, hr.bpm, sp.pct, sp.employee_id, e.full_name as empname
	        FROM health_raw_spo2 sp
	        LEFT JOIN health_raw_hr hr 
	            ON hr.ts_utc = sp.ts_utc 
	           AND hr.employee_id = sp.employee_id
	        LEFT JOIN employees e ON e.id = sp.employee_id
	        WHERE pct != '' ".$whr_emp2.$whr_period2."

	        ORDER BY ts_utc ASC
	    ";

	    $rs = $this->db->query($sql)->result();

	    // --- Grouping per employee ---
	    $all_timestamps = [];
	    $datasets = [];
	    $grouped = [];

	    foreach ($rs as $row) {
	        $all_timestamps[$row->ts_utc] = true;
	        $grouped[$row->employee_id]['name'] = $row->empname;
	        $grouped[$row->employee_id]['data'][$row->ts_utc] = [
	            'bpm' => $row->bpm,
	            'spo2'=> $row->pct
	        ];
	    }

	    $labels = array_keys($all_timestamps);
	    sort($labels);

	    // bikin dataset per employee
	    foreach ($grouped as $empid => $g) {
	        $bpm_data  = [];
	        $spo2_data = [];

	        foreach ($labels as $ts) {
	            if (isset($g['data'][$ts])) {
	                $bpm_data[]  = $g['data'][$ts]['bpm'];
	                $spo2_data[] = $g['data'][$ts]['spo2'];
	            } else {
	                $bpm_data[]  = null; // skip kalau tidak ada
	                $spo2_data[] = null;
	            }
	        }

	        $datasets[] = [
	            'label' => $g['name'].' - BPM',
	            'data'  => $bpm_data,
	            'borderColor' => '#102fe3',//'#38406F',
	            'backgroundColor' => '#102fe3',//'#38406F',
	            'yAxisID' => 'y',
	            'tension' => 0.4,
	            'fill' => false,
	            'pointRadius' => 3,
	            'pointHoverRadius' => 4,
	            'borderWidth' => 2
	        ];

	       

	        $datasets[] = [
	            'label' => $g['name'].' - SpO2',
	            'data'  => $spo2_data,
	            'borderColor' => '#ED1B24',
	            'backgroundColor' => '#ED1B24',
	            //'yAxisID' => 'y1',
	            'yAxisID' => 'y',
	            'tension' => 0.4,
	            'fill' => false,
	            'pointRadius' => 3,
	            'pointHoverRadius' => 4,
	            'borderWidth' => 2
	        ];
	    }



	    $data = [
	        'labels'   => $labels,
	        'datasets' => $datasets
	    ];

	    echo json_encode($data);
	}



}
