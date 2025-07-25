<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dash_employee_details_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "dash_employee_details_menu"; // identify menu
 	const  LABELMASTER				= "Menu Dashboard Employee Details";
 	const  LABELFOLDER				= "dashboard"; // module folder
 	const  LABELPATH				= "dash_employee_details_menu"; // controller file (lowercase)
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
		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and division_id = '".$fldiv."'";
		}

		
		$shiftType = $this->db->query("select SUM(CASE WHEN shift_type = 'Reguler' THEN 1 ELSE 0 END) AS total_reguler, SUM(CASE WHEN shift_type = 'Shift' THEN 1 ELSE 0 END) AS total_shift
 			from employees where status_id = 1 ".$whereDiv."")->result(); 
		$joLevel = $this->db->query("select SUM(CASE WHEN job_level_id <= 5 THEN 1 ELSE 0 END) AS total_managerial,
 					SUM(CASE WHEN job_level_id > 5 THEN 1 ELSE 0 END) AS total_nonmanagerial
 					from employees where status_id = 1 ".$whereDiv."")->result(); 
		$grade = $this->db->query("select SUM(CASE WHEN grade_id = 1 THEN 1 ELSE 0 END) AS total_grade_a,
								 	SUM(CASE WHEN grade_id = 2 THEN 1 ELSE 0 END) AS total_grade_b,
								  	SUM(CASE WHEN grade_id = 3 THEN 1 ELSE 0 END) AS total_grade_c,
								   	SUM(CASE WHEN grade_id = 4 THEN 1 ELSE 0 END) AS total_grade_d
								from employees where status_id = 1 ".$whereDiv." ")->result(); 
		


		$rs = array(
			'total_reguler' 		=> $shiftType[0]->total_reguler,
			'total_shift'			=> $shiftType[0]->total_shift,
			'total_managerial'		=> $joLevel[0]->total_managerial,
			'total_nonmanagerial'	=> $joLevel[0]->total_nonmanagerial,
			'total_grade_a'			=> $grade[0]->total_grade_a,
			'total_grade_b'			=> $grade[0]->total_grade_b,
			'total_grade_c'			=> $grade[0]->total_grade_c,
			'total_grade_d'			=> $grade[0]->total_grade_d
		);


		
		echo json_encode($rs);
 	}


 	public function get_data_empbyGen(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and division_id = '".$fldiv."'";
		}


		$data_emp = $this->db->query("select year(date_of_birth) as year_of_birth from employees where status_id = 1 ".$whereDiv."")->result(); 

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

 	public function get_data_empbyMaritalStatus(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and division_id = '".$fldiv."'";
		}


		$data_emp = $this->db->query("select * from employees where status_id = 1 ".$whereDiv."")->result(); 

		$ttl_tk0=0; 	$ttl_tk2=0; 	$ttl_k0=0;			$ttl_k1=0; 		$ttl_k3=0;
		$ttl_tk1=0; 	$ttl_tk3=0; 	$ttl_undefined=0; 	$ttl_k2=0; 

		foreach($data_emp as $row){
			$maritalStat = $row->marital_status_id;

			if ($maritalStat == 1) {
		        $ttl_tk0 += 1;
		    } elseif ($maritalStat == 2) {
		        $ttl_tk1 += 1;
		    } elseif ($maritalStat == 3) {
		        $ttl_tk2 += 1;
		    } elseif ($maritalStat == 4) {
		        $ttl_tk3 += 1;
		    } elseif ($maritalStat == 5) {
	         	$ttl_k0 += 1;
		    } elseif ($maritalStat == 6) {
	         	$ttl_k1 += 1;
		    }elseif ($maritalStat == 7) {
	         	$ttl_k2 += 1;
		    }elseif ($maritalStat == 8) {
	         	$ttl_k3 += 1;
		    } else {
		        $ttl_undefined += 1;
		    }
		}

		
		$rs = array(
			'ttl_tk0' 	=> $ttl_tk0,
			'ttl_tk1' 	=> $ttl_tk1,
			'ttl_tk2'	=> $ttl_tk2,
			'ttl_tk3'	=> $ttl_tk3,
			'ttl_k0'	=> $ttl_k0,
			'ttl_k1'	=> $ttl_k1,
			'ttl_k2'	=> $ttl_k2,
			'ttl_k3'	=> $ttl_k3,
			'ttl_undefined' => $ttl_undefined
		);
		
		echo json_encode($rs);

 	}


 	public function get_data_empbyDivGender(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and a.division_id = '".$fldiv."'";
		}

		
		$rs = $this->db->query("select
				    a.division_id, b.name as division_name,
				    SUM(CASE WHEN a.gender = 'M' THEN 1 ELSE 0 END) AS total_laki_laki,
				    SUM(CASE WHEN a.gender = 'F' THEN 1 ELSE 0 END) AS total_perempuan,
				    COUNT(*) AS total_karyawan
				FROM
				    employees a
				    left join divisions b on b.id = a.division_id
				where a.status_id = 1 and a.division_id != 0 ".$whereDiv."
				GROUP BY
				    a.division_id ")->result(); 

		$divisions=[]; $total_male=[]; $total_female=[];
		foreach($rs as $row){
			$divisions[] 	= $row->division_name;
			$total_male[] 	= $row->total_laki_laki;
			$total_female[]	= $row->total_perempuan;
		}


		$data = array(
			'divisions' 	=> $divisions,
			'total_male' 	=> $total_male,
			'total_female' 	=> $total_female
		);


		echo json_encode($data);
 	}


 	public function get_data_empStatus(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and a.division_id = '".$fldiv."'";
		}


    	$rs = $this->db->query("select a.employment_status_id AS status, b.name as status_name,
					COUNT(*) AS total
				FROM
					employees a left join master_emp_status b on b.id = a.employment_status_id where a.status_id = 1 ".$whereDiv."
				GROUP BY
					a.employment_status_id
				ORDER BY
					status  ")->result(); 

		$status=[]; $total=[]; 
		foreach($rs as $row){
			$status[] 	= $row->status_name;
			$total[] 	= $row->total;
			
		}


		$data = array(
			'status' 	=> $status,
			'total'		=> $total
		);


		echo json_encode($data);


 	}



 	public function get_data_projectSummary(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and b.division_id = '".$fldiv."'";
		}


    	$rs = $this->db->query("select 
								    c.id AS division_id,
								    c.name AS division_name,
								    SUM(CASE WHEN a.status_id = 1 THEN 1 ELSE 0 END) AS total_open,
								    SUM(CASE WHEN a.status_id = 2 THEN 1 ELSE 0 END) AS total_inprogress,
								    SUM(CASE WHEN a.status_id = 3 THEN 1 ELSE 0 END) AS total_closed
								FROM divisions c
								LEFT JOIN employees b ON b.division_id = c.id
								LEFT JOIN tasklist a ON a.employee_id = b.id and a.status_id != ''
								where 1=1 ".$whereDiv."
								GROUP BY c.id, c.name

						 ")->result(); 

		$division_name=[]; $total_open=[]; $total_inprogress=[]; $total_closed=[]; 
		foreach($rs as $row){
			$division_name[] 		= $row->division_name;
			$total_open[] 			= $row->total_open;
			$total_inprogress[] 	= $row->total_inprogress;
			$total_closed[] 		= $row->total_closed;
			
		}


		$data = array(
			'division_name' 	=> $division_name,
			'total_open'		=> $total_open,
			'total_inprogress' 	=> $total_inprogress,
			'total_closed' 		=> $total_closed
		);


		echo json_encode($data);

 	}



}
