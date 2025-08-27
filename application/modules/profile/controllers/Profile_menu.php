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
		$field = [];


		$msstatus 				= $this->db->query("select * from master_tasklist_status order by id asc")->result(); 
		$field['selstatus'] 	= $this->self_model->return_build_select2me($msstatus,'','','','flstatus','flstatus','','','id','name',' ','','','',3,'-');

		/*$field['master_emp'] = $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); */
		
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



}
