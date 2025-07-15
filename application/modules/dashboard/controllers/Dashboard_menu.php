<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "dashboard_menu"; // identify menu
 	const  LABELMASTER				= "Menu Dashboard";
 	const  LABELFOLDER				= "dashboard"; // module folder
 	const  LABELPATH				= "dashboard_menu"; // controller file (lowercase)
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
		$field['selemp'] 	= $this->self_model->return_build_select2me($msemp,'','','','fldashemp','fldashemp','','','id','full_name',' ','','','',3,'-');

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
 		$dateperiod = $post['dateperiod'];
		$employee 	= $post['employee'];

		
		$ttl_emp = $this->db->query("select count(id) as ttl from employees where status_id = 1")->result(); 
		$ttl_attendance = $this->db->query("select count(distinct(date_attendance)) as ttl_absences_days from time_attendances ")->result(); 
		$ttl_latelogin = $this->db->query("select count(id) as ttl from time_attendances where is_late = 'Y' ")->result(); 
		$ttl_earlylogin = $this->db->query("select count(id) as ttl from time_attendances where is_late != 'Y' or is_late = '' or is_late is null ")->result(); 
		$ttl_leaves = $this->db->query("select sum(total_leave) as ttl from leave_absences where status_approval = 2")->result();
		$ttl_overtimes = $this->db->query("select count(id) as ttl FROM overtimes where status_id = 2 ")->result(); 
		$ttl_holidays = $this->db->query("select count(id) as ttl from master_holidays where day not in ('Sabtu','Minggu') and (DATE_FORMAT(date, '%Y')) = '".date("Y")."' ")->result();
		$topEmp = $this->db->query("select dt.* from (SELECT 
						  a.employee_id, b.full_name, b.personal_email, c.name as divname, b.emp_photo,
						  SUM(CASE WHEN a.is_late = 'Y' THEN 1 ELSE 0 END) AS total_late,
						  SUM(a.num_of_working_hours) AS total_jam_kerja,
						  (
						    SUM(CASE WHEN a.is_late = 'Y' THEN 1 ELSE 0 END)
						    - SUM(a.num_of_working_hours)
						  ) AS disiplin_score
						FROM time_attendances a left join employees b on b.id = a.employee_id
						left join divisions c on c.id = b.division_id
						WHERE a.date_attendance_in IS NOT NULL 
						  AND a.date_attendance_out IS NOT NULL and b.status_id = 1
						GROUP BY a.employee_id
						ORDER BY disiplin_score ASC) dt
						where dt.total_jam_kerja != '0.00'
						limit 5
					")->result();
		


		$rs = array(
			'ttl_emp' 			=> $ttl_emp[0]->ttl,
			'ttl_attendance'	=> $ttl_attendance[0]->ttl_absences_days,
			'ttl_latelogin' 	=> $ttl_latelogin[0]->ttl,
			'ttl_earlylogin' 	=> $ttl_earlylogin[0]->ttl,
			'ttl_leaves'		=> $ttl_leaves[0]->ttl,
			'ttl_overtimes' 	=> $ttl_overtimes[0]->ttl,
			'ttl_holidays' 		=> $ttl_holidays[0]->ttl,
			'topEmp' 			=> $topEmp
		);


		
		echo json_encode($rs);
 	}


 	public function get_data_empbyGen(){
 		$post = $this->input->post(null, true);
 		$dateperiod = $post['dateperiod'];
		$employee 	= $post['employee'];


		$data_emp = $this->db->query("select year(date_of_birth) as year_of_birth from employees where status_id = 1")->result(); 

		$boomer=0; 		$gen_x=0; 		$gen_z=0;
		$gen_mill=0; 	$gen_alpha=0; 	$unkgen=0;

		foreach($data_emp as $row){
			$birthYear = $row->year_of_birth;

			if ($birthYear >= 1946 && $birthYear <= 1964) {
		        $boomer += 1;
		    } elseif ($birthYear >= 1965 && $birthYear <= 1980) {
		        $gen_x += 1;
		    } elseif ($birthYear >= 1981 && $birthYear <= 1996) {
		        $gen_mill += 1;
		    } elseif ($birthYear >= 1997 && $birthYear <= 2012) {
		        $gen_z += 1;
		    } elseif ($birthYear >= 2013) {
	         	$gen_alpha += 1;
		    } else {
		        $unkgen += 1;
		    }
		}

		
		$rs = array(
			'ttl_boomer' 	=> $boomer,
			'ttl_gen_x' 	=> $gen_x,
			'ttl_gen_mill'	=> $gen_mill,
			'ttl_gen_z'		=> $gen_z,
			'ttl_gen_alpha'	=> $gen_alpha,
			'ttl_unkgen' 	=> $unkgen
		);
		
		echo json_encode($rs);

 	}



 	public function get_data_workLoc(){
 		$post = $this->input->post(null, true);
 		$dateperiod = $post['dateperiod'];
		$employee 	= $post['employee'];


		$data_att = $this->db->query("select * from time_attendances")->result(); 

		$wfo=0; 		
		$wfh=0; 
		$unkloc=0;	

		foreach($data_att as $row){
			$loc = $row->work_location;

			if ($loc == 'wfo') {
		        $wfo += 1;
		    } elseif ($loc == 'wfh') {
		        $wfh += 1;
		    } else {
		        $unkloc += 1;
		    }
		}

		
		$rs = array(
			'ttl_wfo' 		=> $wfo,
			'ttl_wfh' 		=> $wfh,
			'ttl_unkloc'	=> $unkloc
		);
		
		echo json_encode($rs);

 	}


 	public function get_data_empbyDeptGender(){
 		$post = $this->input->post(null, true);
 		$dateperiod = $post['dateperiod'];
		$employee 	= $post['employee'];

		
		$rs = $this->db->query("select
				    a.department_id, b.name as department_name,
				    SUM(CASE WHEN a.gender = 'M' THEN 1 ELSE 0 END) AS total_laki_laki,
				    SUM(CASE WHEN a.gender = 'F' THEN 1 ELSE 0 END) AS total_perempuan,
				    COUNT(*) AS total_karyawan
				FROM
				    employees a
				    left join departments b on b.id = a.department_id
				where a.status_id = 1
				GROUP BY
				    a.department_id ")->result(); 

		$departments=[]; $total_male=[]; $total_female=[];
		foreach($rs as $row){
			$departments[] 	= $row->department_name;
			$total_male[] 	= $row->total_laki_laki;
			$total_female[]	= $row->total_perempuan;
		}


		$data = array(
			'departments' 	=> $departments,
			'total_male' 	=> $total_male,
			'total_female' 	=> $total_female
		);


		echo json_encode($data);
 	}


 	public function get_data_monthlyAttSumm(){
 		$post = $this->input->post(null, true);
 		$dateperiod = $post['dateperiod'];
		$employee 	= $post['employee'];

		$where_date="";
		if($dateperiod != ''){
			$where_date = " and DATE_FORMAT(date_attendance, '%Y-%m') = '".$dateperiod."'";
		}
		$where_emp="";
		if($employee != ''){
			$where_emp = " and employee_id = '".$employee."'";
		}


    	$rs = $this->db->query("select
				    DATE_FORMAT(date_attendance, '%Y-%m') AS tahun_bulan,
				    COUNT(*) AS total_absensi
				FROM
				    time_attendances where 1=1
				".$where_date.$where_emp."
				GROUP BY
				    DATE_FORMAT(date_attendance, '%Y-%m')
				ORDER BY
				    tahun_bulan ")->result(); 

		$periode=[]; $total_absensi=[]; 
		foreach($rs as $row){
			$periode[] 			= $row->tahun_bulan;
			$total_absensi[] 	= $row->total_absensi;
			
		}


		$data = array(
			'periode' 		=> $periode,
			'total_absensi'	=> $total_absensi
		);


		echo json_encode($data);


 	}



 	public function get_data_attStatistic(){
 		$post = $this->input->post(null, true);
 		$dateperiod = $post['dateperiod'];
		$employee 	= $post['employee'];

		$where_date="";
		if($dateperiod != ''){
			$where_date = " and DATE_FORMAT(date_attendance, '%Y-%m') = '".$dateperiod."'";
		}
		$where_emp="";
		if($employee != ''){
			$where_emp = " and employee_id = '".$employee."'";
		}


    	$rs = $this->db->query("select
						    ta.hari,
						    ta.total_on_work_time,
						    ta.total_late,
						    IFNULL(ot.total_overtime, 0) AS total_overtime,
						    ta.total_leaving_early,
						    ta.total_leave,
						    ta.total_absent,
						    ta.total_absensi
						FROM
						(
						    SELECT
						        DATE(date_attendance) AS hari,
						        SUM(CASE WHEN is_late != 'Y' AND is_leaving_office_early != 'Y' AND leave_absences_id IS NULL THEN 1 ELSE 0 END) AS total_on_work_time,
						        SUM(CASE WHEN is_late = 'Y' THEN 1 ELSE 0 END) AS total_late,
						        SUM(CASE WHEN is_leaving_office_early = 'Y' THEN 1 ELSE 0 END) AS total_leaving_early,
						        SUM(CASE WHEN leave_absences_id != '' OR leave_absences_id IS NOT NULL THEN 1 ELSE 0 END) AS total_leave,
						        SUM(CASE WHEN (date_attendance_in IS NULL AND date_attendance_out IS NULL) AND leave_absences_id IS NULL THEN 1 ELSE 0 END) AS total_absent,
						        COUNT(id) AS total_absensi
						    FROM time_attendances
						    WHERE date_attendance IS NOT NULL
						    GROUP BY DATE(date_attendance)
						) AS ta
						LEFT JOIN (
						    SELECT
						        date_overtime,
						        COUNT(id) AS total_overtime
						    FROM overtimes
						    GROUP BY date_overtime
						) AS ot ON ta.hari = ot.date_overtime
						ORDER BY ta.hari
						 ")->result(); 

		$hari=[]; $total_on_work_time=[]; $total_late=[]; $total_overtime=[]; 
		$total_leave=[]; $total_absent=[]; $total_leaving_early=[];
		foreach($rs as $row){
			$hari[] 				= $row->hari;
			$total_on_work_time[] 	= $row->total_on_work_time;
			$total_late[] 			= $row->total_late;
			$total_overtime[] 		= $row->total_overtime;
			$total_leaving_early[] 	= $row->total_leaving_early;
			$total_leave[] 			= $row->total_leave;
			$total_absent[] 		= $row->total_absent;
			
		}


		$data = array(
			'hari' 					=> $hari,
			'total_on_work_time'	=> $total_on_work_time,
			'total_late' 			=> $total_late,
			'total_overtime' 		=> $total_overtime,
			'total_leaving_early' 	=> $total_leaving_early,
			'total_leave' 			=> $total_leave,
			'total_absent' 			=> $total_absent
		);


		echo json_encode($data);

 	}



 	public function get_data_attPercentage(){
 		$post = $this->input->post(null, true);
 		$dateperiod = $post['dateperiod'];
		$employee 	= $post['employee'];


    	$rs = $this->db->query("select
				    COUNT(*) AS total_absen,
				    SUM(CASE WHEN (date_attendance_in is not null or date_attendance_out is not null) and leave_absences_id is null THEN 1 ELSE 0 END) AS total_hadir,
				    SUM(CASE WHEN (date_attendance_in is null and date_attendance_out is null) or leave_absences_id is not null THEN 1 ELSE 0 END) AS total_tidak_hadir,
				    ROUND(SUM(CASE WHEN (date_attendance_in is not null or date_attendance_out is not null) and leave_absences_id is null THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) AS persen_hadir,
				    ROUND(SUM(CASE WHEN (date_attendance_in is null and date_attendance_out is null) or leave_absences_id is not null THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) AS persen_tidak_hadir
				FROM time_attendances;
				 ")->result(); 


    	echo json_encode($rs);


 	}



 	public function get_data_workhrsPercentage(){
 		$post = $this->input->post(null, true);
 		$dateperiod = $post['dateperiod'];
		$employee 	= $post['employee'];


    	/*$rs = $this->db->query("select
					    ROUND(SUM(TIMESTAMPDIFF(MINUTE, date_attendance_in, date_attendance_out)) / 60, 2) AS total_worked_hours,
					    COUNT(*) * 8 AS total_standar_hours,
					    ROUND(SUM(TIMESTAMPDIFF(MINUTE, date_attendance_in, date_attendance_out)) / 60 / (COUNT(*) * 8) * 100, 2) AS persen_worked,
					    ROUND(100 - (SUM(TIMESTAMPDIFF(MINUTE, date_attendance_in, date_attendance_out)) / 60 / (COUNT(*) * 8) * 100), 2) AS persen_idle
					FROM
					    time_attendances
					WHERE
					    date_attendance_in IS NOT NULL AND date_attendance_out IS NOT NULL;

				 ")->result(); */

		$rs = $this->db->query("select 
								   ROUND(AVG(num_of_working_hours), 2) AS avg_jam_kerja,
								   8-(ROUND(AVG(num_of_working_hours), 2)) as sisa
								FROM time_attendances;
				 			")->result(); 


    	echo json_encode($rs);


 	}



}
