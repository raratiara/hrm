<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

class Profile_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "profile_menu"; // identify menu
 	const  LABELMASTER				= "Menu Profile";
 	const  LABELFOLDER				= "profile"; // module folder
 	const  LABELPATH				= "profile_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "profile"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Profile"; // 
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
		$datetimeNow = date("Y-m-d H:i:s"); 
		$dateNow = date("Y-m-d");
		$period = date("Y-m");
		$tgl = date("d");
		$date_attendance = $dateNow;
		

		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		

		$empData = $this->db->query("select full_name, shift_type from employees where id = '".$karyawan_id."'")->result(); 
		$emp_shift_type=1; $time_in=""; $time_out=""; $attendance_type="";
		if($empData[0]->shift_type == 'Reguler'){
			$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 

		}else if($empData[0]->shift_type == 'Shift'){
			
			/// NEW SCRIPT
			$datetimemax_shift3 = $dateNow.' 08:00:00';
			if($datetimeNow < $datetimemax_shift3){ //brarti dia sdg checkin shift 3 di tgl sebelumnya (late)
				$dateYesterday = date("Y-m-d", strtotime($dateNow . " -1 day"));
				$period  = date('Y-m', strtotime($dateYesterday));
			 	$tgl = date('d', strtotime($dateYesterday));
			 	$date_attendance = $dateYesterday;
			}


			$dt = $this->db->query("select 
			    a.*, 
			    b.periode, 
			    b.`".$tgl."` as 'shift', 
			    c.name,
			    case 
			        when c.shift_id = 3 then 
			            concat(date_add(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), interval 1 day), ' ', c.time_in)
			        else 
			            concat(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), ' ', c.time_in)
			    end as expected_checkin,
			    case 
			        when c.shift_id = 2 then 
			            concat(date_add(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), interval 1 day), ' 00:00:00')
			        when c.shift_id = 3 then 
			            concat(date_add(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), interval 1 day), ' ', c.time_out)
			        else 
			            concat(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), ' ', c.time_out)
			    end as expected_checkout,
			    c.time_in, c.time_out, str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d') as date_attendance
			from shift_schedule a
			left join group_shift_schedule b on b.shift_schedule_id = a.id 
			left join master_shift_time c on c.shift_id = b.`".$tgl."`
			where b.employee_id = '".$karyawan_id."'
			and a.period = '".$period."'
			")->result(); 

			if($dt[0]->shift == ""){
				$emp_shift_type=0;
			}

			
			/// END NEW SCRIPT

		}else{ //tidak ada shift type
			$emp_shift_type=0;
		} 

		if($emp_shift_type==1){
			$time_in 			= $dt[0]->time_in;
			$time_out 			= $dt[0]->time_out;
			$attendance_type 	= $dt[0]->name;
		}



		$field = [];
		$field['empid'] = $karyawan_id; 	
		
		$field['txtdateattendance']		= $this->self_model->return_build_txt($date_attendance,'date_attendance','date_attendance','','','readonly');
		
		$field['selemployee'] 			= $this->self_model->return_build_txt($empData[0]->full_name,'employee','employee','','','readonly');
		
		$field['txtimein'] 				= $this->self_model->return_build_txt($time_in,'time_in','time_in','','','readonly');
		$field['txtattendancein'] 		= $this->self_model->return_build_txt($datetimeNow,'attendance_in','attendance_in','','','readonly');
		$field['txtlatedesc'] 			= $this->self_model->return_build_txt('','late_desc','late_desc','','','readonly');
		
		$field['txtemptype'] 			= $this->self_model->return_build_txt($attendance_type,'emp_type','emp_type','','','readonly');
		
		$field['txtimeout'] 			= $this->self_model->return_build_txt($time_out,'time_out','time_out','','','readonly');
		
		$field['txtattendanceout'] 		= $this->self_model->return_build_txt('','attendance_out','attendance_out','','','readonly');
		$field['txtleavingearlydesc']	= $this->self_model->return_build_txt('','leaving_early_desc','leaving_early_desc','','','readonly');
		$field['txtdesc'] 				= $this->self_model->return_build_txtarea('','description','description');
		$msstatus 				= $this->db->query("select * from master_tasklist_status order by id asc")->result();
		$field['selstatus'] 	= $this->self_model->return_build_select2me($msstatus,'','','','flstatus','flstatus','','','id','name',' ','','','',3,'-');
		
		$msmenu = $this->db->query("select a.*, b.title as menu_name, b.* from user_akses_role a 
							left join user_menu b on b.user_menu_id = a.user_menu_id
							where role_id = ".$_SESSION["role"]." and show_menu = 1 and b.url != '#' order by b.title asc")->result();
		$field['selmenu'] 	= $this->self_model->return_build_select2me($msmenu,'','','','menu','menu','','','user_menu_id','menu_name',' ','','','',3,'-');

		$raw = [
		    ['id' => 'wfo', 'name' => 'WFO'],
		    ['id' => 'wfh', 'name' => 'WFH'],
		    ['id' => 'onsite', 'name' => 'On Site']
		];
		$msLoc = [];
		foreach ($raw as $row_raw) {
		    $obj = new stdClass();
		    $obj->id = $row_raw['id'];
		    $obj->name = $row_raw['name'];
		    $msLoc[] = $obj;
		}
		$field['selloc'] 			= $this->self_model->return_build_select2me($msLoc,'','','','location','location','','','id','name',' ','','','',3,'-');



		
		return $field;
	}

	//========================== Considering Already Fixed =======================//
 	/* Construct */
	public function __construct() {
        parent::__construct(); 
        
        
		# akses level
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]); 
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',$akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);

		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan; 
		define('_USER_EMPLOYEE_ID',$karyawan_id); 

    }

	/* Module */
	public $base_url = _URL; 
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



 	public function get_data_monthlyAttendanceSumm(){ 
 		$post = $this->input->post(null, true);
 		$year = date("Y");
		$employee 	= $post['employee'];

		


    	$rs = $this->db->query("select
			    DATE_FORMAT(date_attendance, '%m') as bln,
				SUM(CASE WHEN date_attendance_in is not null and is_late != 'Y' and leave_absences_id is null THEN 1 ELSE 0 END) AS total_ontime,
				SUM(CASE WHEN is_late = 'Y' THEN 1 ELSE 0 END) AS total_late,
				SUM(CASE WHEN is_leaving_office_early = 'Y' THEN 1 ELSE 0 END) AS total_leaving_early,
				SUM(CASE WHEN date_attendance_in is null and date_attendance_out is null and leave_absences_id is null  THEN 1 ELSE 0 END) as total_noattendance,
				count(id) as total_absensi
			FROM
				time_attendances
			where employee_id = '".$employee."' and (DATE_FORMAT(date_attendance, '%Y') = '".$year."')
			GROUP BY
			DATE_FORMAT(date_attendance, '%m')
			ORDER BY
				bln")->result(); 

		
		// Inisialisasi semua bulan 1 sampai 12 dengan nilai 0
		$bln = range(1, 12);
		$total_ontime = array_fill(0, 12, 0);
		$total_late = array_fill(0, 12, 0);
		$total_leaving_early = array_fill(0, 12, 0);
		$total_noattendance = array_fill(0, 12, 0);

		// Masukkan data dari hasil query ke posisi bulan yang sesuai
		foreach ($rs as $row) {
		    $index = $row->bln - 1; // index array mulai dari 0

		    $total_ontime[$index] = $row->total_ontime;
		    $total_late[$index] = $row->total_late;
		    $total_leaving_early[$index] = $row->total_leaving_early;
		    $total_noattendance[$index] = $row->total_noattendance;
		}

		$bln = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];


		$data = array(
			'thn' 					=> $year,
			'bln' 					=> $bln,
			'total_ontime'			=> $total_ontime,
			'total_late' 			=> $total_late,
			/*'total_overtime' 		=> $total_overtime,*/
			'total_leaving_early' 	=> $total_leaving_early,
			'total_noattendance' 	=> $total_noattendance
		);


		echo json_encode($data);

 	}


 	public function downloadFile(){ 

		$filename = $_GET['file']; // e.g., "example.pdf"

		// Set the full file path
		/*$filePath = 'documents/' . basename($filename);*/ // folder 'documents'
		$filePath = "./uploads/documents/" . basename($filename);


		if (file_exists($filePath)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
		    header('Content-Length: ' . filesize($filePath));
		    readfile($filePath);
		    exit;
		} else {
		    http_response_code(404);
		    echo "File not found.";
		}

 	}

 	
	private $taskColorMap = [];

	public function randomColor($taskName)
	{
	    $colors = [
	        '#FF6384', '#36A2EB', '#FFCE56', '#4BCC00', '#9966FF', '#FF9F40',
	        '#00aaff', '#aaff00', '#ff00ff', '#00ffaa', '#aa00ff', '#ff5500',
	        '#0055ff', '#ffaa00', '#00ff55', '#ff0055', '#55ff00', '#ffcc00',
	        '#0099ff', '#cc00ff', '#ff0099', '#99ff00', '#0033ff', '#ff3300'
	    ];

	    // Fungsi untuk konversi HEX ke RGBA
	    $hexToRgba = function ($hex, $alpha = 0.5) {
	        $hex = str_replace('#', '', $hex);
	        $r = hexdec(substr($hex, 0, 2));
	        $g = hexdec(substr($hex, 2, 2));
	        $b = hexdec(substr($hex, 4, 2));
	        return "rgba($r, $g, $b, $alpha)";
	    };

	    // Kalau sudah pernah dipakai
	    if (isset($this->taskColorMap[$taskName])) {
	        $hex = $this->taskColorMap[$taskName];
	        return [
	            'hex'  => $hex,
	            'rgba' => $hexToRgba($hex, 0.5)
	        ];
	    }

	    // Ambil urutan warna
	    $usedCount = count($this->taskColorMap);
	    if ($usedCount >= count($colors)) {
	        $hex = '#cccccc';
	        $this->taskColorMap[$taskName] = $hex;
	        return [
	            'hex'  => $hex,
	            'rgba' => $hexToRgba($hex, 0.5)
	        ];
	    }

	    $hex = $colors[$usedCount];
	    $this->taskColorMap[$taskName] = $hex;
	    return [
	        'hex'  => $hex,
	        'rgba' => $hexToRgba($hex, 0.5)
	    ];
	}



	public function randomColor_old($taskName)
	{
	    $colors = [
	        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
	        '#00aaff', '#aaff00', '#ff00ff', '#00ffaa', '#aa00ff', '#ff5500',
	        '#0055ff', '#ffaa00', '#00ff55', '#ff0055', '#55ff00', '#ffcc00',
	        '#0099ff', '#cc00ff', '#ff0099', '#99ff00', '#0033ff', '#ff3300'
	    ];

	    // Jika sudah pernah dikasih warna, pakai yang itu
	    if (isset($this->taskColorMap[$taskName])) {
	        return $this->taskColorMap[$taskName];
	    }

	    // Hitung jumlah yang sudah terpakai
	    $usedCount = count($this->taskColorMap);

	    // Kalau sudah lebih banyak task dari warna tersedia, pakai fallback
	    if ($usedCount >= count($colors)) {
	        $this->taskColorMap[$taskName] = '#cccccc'; // fallback abu
	        return '#cccccc';
	    }

	    // Assign warna dari daftar sesuai urutan
	    $this->taskColorMap[$taskName] = $colors[$usedCount];
	    return $colors[$usedCount];
	}

	public function get_data_dailyTasklist(){
 		$post = $this->input->post(null, true);
 		$fltasklistperiod 	= $post['fltasklistperiod'];
 		$flstatus 			= $post['flstatus'];

 		$whrDate="";
 		if($fltasklistperiod != ''){
 			$dataeperiod = explode(" - ",$fltasklistperiod);
 			$fromDate = $dataeperiod[0];
 			$toDate = $dataeperiod[1];

 			$whrDate = " and (DATE(a.submit_at) >= '".$fromDate."' and DATE(a.submit_at) <= '".$toDate."' )";
 		}
 		$whrStatus=""; $whrStatus2=""; $whrStatus3="";
 		if($flstatus != ''){
 			$whrStatus 	= " and b.status_id = '".$flstatus."'";
 			$whrStatus2 = " and a.status_id = '".$flstatus."'";
 			$whrStatus3 = " and status_id = '".$flstatus."'";
 		}
 		

 		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

		
		$query = $this->db->query("
	        select  
	            DATE(a.submit_at) AS submit_date,
	            b.task,
	            ROUND(AVG(a.progress_percentage), 2) AS avg_progress
	        FROM history_progress_tasklist a
	        LEFT JOIN tasklist b ON b.id = a.tasklist_id
	        where b.employee_id = '".$karyawan_id."' ".$whrDate.$whrStatus."
	        AND NOT EXISTS (
			      SELECT 1
			      FROM tasklist c
			      WHERE c.parent_id = b.id
			  )
	        GROUP BY DATE(a.submit_at), b.task
	        ORDER BY submit_date ASC
	    ");

	    $results = $query->result();

	    $dates = [];
	    $tasks = [];
	    $dataMap = [];

	    // Kumpulkan semua tanggal & task
	    foreach ($results as $row) {
	        if (!in_array($row->submit_date, $dates)) {
	            $dates[] = $row->submit_date;
	        }
	        if (!in_array($row->task, $tasks)) {
	            $tasks[] = $row->task;
	        }
	        $dataMap[$row->task][$row->submit_date] = $row->avg_progress;
	    }


	    // Ambil semua tasklist_id dari hasil query pertama
		$tasklistIds = [];
		foreach ($results as $row) {
		    $taskId = $this->db->query("
		        SELECT id, parent_id, project_id 
		        FROM tasklist 
		        WHERE task = ?
		        LIMIT 1
		    ", [$row->task])->row();

		    if ($taskId) {
		        $tasklistIds[] = $taskId->id;
		        if (!empty($taskId->parent_id) && $taskId->parent_id != 0) {
		            $tasklistIds[] = $taskId->parent_id;
		        }
		        if (!empty($taskId->project_id) && $taskId->project_id != 0) {
		            $projectIds[] = $taskId->project_id;
		        }
		    }
		}

		$tasklistIds = array_unique($tasklistIds);
		$projectIds = array_unique($projectIds ?? []);

		$whereTaskListIds = "";
		if (!empty($tasklistIds)) {
		    $whereTaskListIds = " AND a.id IN (" . implode(',', $tasklistIds) . ") ";
		}
	    

	    $datasets = [];
		foreach ($tasks as $task) {
		    $progressList = [];
		    $reached100 = false;

		    foreach ($dates as $date) {
		        if (isset($dataMap[$task][$date])) {
		            $progress = floatval($dataMap[$task][$date]);
		            $progressList[] = $progress;

		            // Tandai jika sudah pernah mencapai 100
		            if ($progress >= 100) {
		                $reached100 = true;
		            }
		        } else {
		            // Kalau tidak ada data dan sudah pernah 100, kosongkan
		            if ($reached100) {
		                $progressList[] = null; // null agar tidak tampil
		            } else {
		                $progressList[] = 0;
		            }
		        }
		    }

		    $datasets[] = [
		        'label' => $task,
		        'data' => $progressList,
		        'type' => 'bar',
		        'borderWidth' => 1,
		        'borderRadius' => 4,
		        'backgroundColor' => $this->randomColor($task)
		    ];
		}

		if($whereTaskListIds == ''){
			$data_tasklist= [];
		}else{
			//get data tasklist progress
			$data_tasklist = $this->db->query("
						    select 
						        a.id,
						        a.employee_id,
						        a.task,
						        a.progress_percentage,
						        a.parent_id,
						        a.project_id,
						        CASE 
						            WHEN a.parent_id = 0 AND EXISTS (
						                SELECT 1 FROM tasklist x WHERE x.parent_id = a.id
						            ) THEN 'parent'
						            WHEN a.parent_id != 0 THEN 'child'
						            ELSE 'standalone'
						        END AS task_type,
						        CASE
						            WHEN a.id IS NULL AND a.project_id IS NOT NULL
						                THEN LPAD(a.project_id, 5, '0')
						            WHEN a.parent_id = 0 AND a.project_id IS NOT NULL
						                THEN CONCAT(LPAD(a.project_id, 5, '0'), '.', LPAD(a.id, 5, '0'))
						            WHEN a.parent_id = 0 AND a.project_id IS NULL
						                THEN CONCAT('99999.', LPAD(a.id, 5, '0'))
						            WHEN a.parent_id != 0
						                THEN (
						                    SELECT CONCAT(
						                        LPAD(COALESCE(p.project_id, 99999), 5, '0'), '.', 
						                        LPAD(p.id, 5, '0'), '.', 
						                        LPAD(a.id, 5, '0')
						                    )
						                    FROM tasklist p
						                    WHERE p.id = a.parent_id
						                    LIMIT 1
						                )
						            ELSE CONCAT('99999.99999.', LPAD(a.id, 5, '0'))
						        END AS sort_key,
						        a.due_date,
						        a.status_id,
						        b.title AS project_name,
						        CASE 
									WHEN a.due_date IS NULL THEN NULL
									WHEN CURDATE() > a.due_date THEN 'overdue'
									WHEN a.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY) THEN 'near_due'
									ELSE 'ok'
								END AS due_flag
						    FROM tasklist a
						    LEFT JOIN data_project b ON b.id = a.project_id
						    WHERE 1=1
						        $whereTaskListIds

						    UNION ALL

						    SELECT 
						        NULL AS id,
						        NULL AS employee_id,
						        b.title AS task,
						        NULL AS progress_percentage,
						        NULL AS parent_id,
						        b.id AS project_id,
						        'project' AS task_type,
						        LPAD(b.id, 5, '0') AS sort_key,
						        NULL AS due_date,
						        NULL AS status_id,
						        b.title AS project_name,
						        NULL AS due_flag
						    FROM data_project b
						    WHERE b.id IN (" . (empty($projectIds) ? "0" : implode(',', $projectIds)) . ")

						    ORDER BY sort_key
						")->result();
		}

		


	    echo json_encode([
	        'dates' 	=> $dates,
	        'datasets' 	=> $datasets,
	        'taskList' 	=> $data_tasklist
	    ]);

 	}



 	public function get_data_dailyTasklist_old(){
 		$post = $this->input->post(null, true);
 		$fltasklistperiod 	= $post['fltasklistperiod'];
 		$flstatus 			= $post['flstatus'];

 		$whrDate="";
 		if($fltasklistperiod != ''){
 			$dataeperiod = explode(" - ",$fltasklistperiod);
 			$fromDate = $dataeperiod[0];
 			$toDate = $dataeperiod[1];

 			$whrDate = " and (DATE(a.submit_at) >= '".$fromDate."' and DATE(a.submit_at) <= '".$toDate."' )";
 		}
 		$whrStatus=""; $whrStatus2=""; $whrStatus3="";
 		if($flstatus != ''){
 			$whrStatus 	= " and b.status_id = '".$flstatus."'";
 			$whrStatus2 = " and a.status_id = '".$flstatus."'";
 			$whrStatus3 = " and status_id = '".$flstatus."'";
 		}
 		

 		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

		
		$query = $this->db->query("
	        select  
	            DATE(a.submit_at) AS submit_date,
	            b.task,
	            ROUND(AVG(a.progress_percentage), 2) AS avg_progress
	        FROM history_progress_tasklist a
	        LEFT JOIN tasklist b ON b.id = a.tasklist_id
	        where b.employee_id = '".$karyawan_id."' ".$whrDate.$whrStatus."
	        AND NOT EXISTS (
			      SELECT 1
			      FROM tasklist c
			      WHERE c.parent_id = b.id
			  )
	        GROUP BY DATE(a.submit_at), b.task
	        ORDER BY submit_date ASC
	    ");

	    $results = $query->result();

	    $dates = [];
	    $tasks = [];
	    $dataMap = [];

	    // Kumpulkan semua tanggal & task
	    foreach ($results as $row) {
	        if (!in_array($row->submit_date, $dates)) {
	            $dates[] = $row->submit_date;
	        }
	        if (!in_array($row->task, $tasks)) {
	            $tasks[] = $row->task;
	        }
	        $dataMap[$row->task][$row->submit_date] = $row->avg_progress;
	    }

	    // Buat string WHERE IN untuk filter di data_tasklist
		$whereTaskList = "";
		if (!empty($tasks)) {
		    $taskListIn = "'" . implode("','", array_map('addslashes', $tasks)) . "'";
		    $whereTaskList = " AND a.task IN ($taskListIn) ";
		}

	   

	    $datasets = [];
		foreach ($tasks as $task) {
		    $progressList = [];
		    $reached100 = false;

		    foreach ($dates as $date) {
		        if (isset($dataMap[$task][$date])) {
		            $progress = floatval($dataMap[$task][$date]);
		            $progressList[] = $progress;

		            // Tandai jika sudah pernah mencapai 100
		            if ($progress >= 100) {
		                $reached100 = true;
		            }
		        } else {
		            // Kalau tidak ada data dan sudah pernah 100, kosongkan
		            if ($reached100) {
		                $progressList[] = null; // null agar tidak tampil
		            } else {
		                $progressList[] = 0;
		            }
		        }
		    }

		    $datasets[] = [
		        'label' => $task,
		        'data' => $progressList,
		        'type' => 'bar',
		        'borderWidth' => 1,
		        'borderRadius' => 4,
		        'backgroundColor' => $this->randomColor($task)
		    ];
		}



		//get data tasklist progress
		$data_tasklist = $this->db->query("
							select 
							    a.id,
							    a.employee_id,
							    a.task,
							    a.progress_percentage,
							    a.parent_id,
							    a.project_id,
							    CASE 
							        WHEN a.parent_id = 0 AND EXISTS (
							            SELECT 1 FROM tasklist x WHERE x.parent_id = a.id
							        ) THEN 'parent'
							        WHEN a.parent_id != 0 THEN 'child'
							        ELSE 'standalone'
							    END AS task_type,
							    CASE
							        WHEN a.id IS NULL AND a.project_id IS NOT NULL
							            THEN LPAD(a.project_id, 5, '0')
							        WHEN a.parent_id = 0 AND a.project_id IS NOT NULL
							            THEN CONCAT(LPAD(a.project_id, 5, '0'), '.', LPAD(a.id, 5, '0'))
							        WHEN a.parent_id = 0 AND a.project_id IS NULL
							            THEN CONCAT('99999.', LPAD(a.id, 5, '0'))
							        WHEN a.parent_id != 0
							            THEN (
							                SELECT CONCAT(
							                    LPAD(COALESCE(p.project_id, 99999), 5, '0'), '.', 
							                    LPAD(p.id, 5, '0'), '.', 
							                    LPAD(a.id, 5, '0')
							                )
							                FROM tasklist p
							                WHERE p.id = a.parent_id
							                LIMIT 1
							            )
							        ELSE CONCAT('99999.99999.', LPAD(a.id, 5, '0'))
							    END AS sort_key,
							    a.due_date,
							    a.status_id,
							    b.title AS project_name
							FROM tasklist a
							LEFT JOIN data_project b ON b.id = a.project_id
							WHERE
							    (a.employee_id = '".$karyawan_id."')
							    OR a.id IN (
							        SELECT DISTINCT parent_id
							        FROM tasklist
							        WHERE employee_id = '".$karyawan_id."' 
							        AND parent_id IS NOT NULL
							        AND parent_id != 0
							    )
							UNION ALL

							SELECT 
							    NULL AS id,
							    NULL AS employee_id,
							    b.title AS task,
							    NULL AS progress_percentage,
							    NULL AS parent_id,
							    b.id AS project_id,
							    'project' AS task_type,
							    LPAD(b.id, 5, '0') AS sort_key,
							    NULL AS due_date,
							    NULL AS status_id,
							    b.title AS project_name
							FROM data_project b
							WHERE b.id IN (
							    SELECT DISTINCT project_id
							    FROM tasklist
							    WHERE project_id IS NOT NULL AND project_id != 0
							)

							ORDER BY sort_key;
						")->result();


	    echo json_encode([
	        'dates' 	=> $dates,
	        'datasets' 	=> $datasets,
	        'taskList' 	=> $data_tasklist
	    ]);

 	}


 	public function gettasklistrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			
			if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				$checkin = (isset($post['checkin']) && $post['checkin'] == TRUE)? TRUE:FALSE;


				echo json_encode($this->self_model->getNewExpensesRow($row,$id,$view,$checkin));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}



	public function get_data_checkout(){
 		//$post = $this->input->post(null, true);

 		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$dateNow = date("Y-m-d");
 		
		
		$rs = $this->db->query('select a.*, b.full_name as employee_name, (case when a.work_location = "wfo" then "WFO" when a.work_location = "wfh" then "WFH" when a.work_location = "onsite" then "On Site" else "" end) as work_location_name FROM time_attendances a left join employees b on b.id = a.employee_id where a.employee_id = '.$karyawan_id.' order by id desc limit 1')->result();
		$date_attendance_tomorrow = date("Y-m-d", strtotime($rs[0]->date_attendance . " +1 day"));

		$isupdate="0"; 
		if(!empty($rs)){
			if($rs[0]->attendance_type != '' && $rs[0]->attendance_type != null){
				if(
					($rs[0]->attendance_type == 'Reguler' && $rs[0]->date_attendance == $dateNow) || 
					($rs[0]->attendance_type == 'Shift 1' && $rs[0]->date_attendance == $dateNow) || 
					($rs[0]->attendance_type == 'Shift 2' && $dateNow <= $date_attendance_tomorrow) || 
					($rs[0]->attendance_type == 'Shift 3' && $dateNow <= $date_attendance_tomorrow)
				){
					$isupdate="1"; 
				}
			}
		}else{
			$rs='';
		}
		
		

	    //echo json_encode($rs);
	    echo json_encode([
	        'isupdate' 	=> $isupdate,
	        'dataabsen'	=> $rs
	    ]);

 	}


 	public function add_quick_link(){
 		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

		$post = $this->input->post(null, true);
		$menu = $post['menu'];

		if($menu != ''){

			$data = [
				'employee_id' 	=> $karyawan_id,
				'user_menu_id' 	=> $menu,
				'created_at'	=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert("quick_links", $data);

			return $rs;
			
		}else return null;

		echo json_encode($rs);

	}


	public function get_data_quicklink(){
 		//$post = $this->input->post(null, true);

 		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		
		
		$rs = $this->db->query('select a.*, b.title, b.url from quick_links a left join user_menu b on b.user_menu_id = a.user_menu_id where a.employee_id = '.$karyawan_id.' ')->result();
		

	    echo json_encode($rs);
	   

 	}



}
