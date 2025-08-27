<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dash_performance_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "dash_performance_menu"; // identify menu
 	const  LABELMASTER				= "Menu Dashboard Performance";
 	const  LABELFOLDER				= "dashboard"; // module folder
 	const  LABELPATH				= "dash_performance_menu"; // controller file (lowercase)
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


 	
 	public function get_data_softskillAnalysis(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];
 		//$flyear 	= $post['flyear'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and c.id = '".$fldiv."'";
		}
		$whereYear="";
		/*if(!empty($flyear)){ 
			$whereYear=" and a.year = '".$flyear."'";
		}*/


    	$rs = $this->db->query("select 
								    c.id AS softskill_id,
								    c.name AS softskill_name,
								    COALESCE(SUM(a.final_score), 0) AS total
								FROM master_softskill c
								LEFT JOIN performance_appraisal_softskill a 
								       ON a.softskill_id = c.id
								LEFT JOIN performance_appraisal b 
								       ON b.id = a.performance_appraisal_id
								LEFT JOIN employees d 
								       ON d.id = b.employee_id
								LEFT JOIN divisions e 
								       ON e.id = d.division_id 
								GROUP BY c.name
								ORDER BY c.name;

								")->result(); 

		$softskill_name=[]; $total=[]; 
		foreach($rs as $row){
			$softskill_name[] 	= $row->softskill_name;
			$total[] 			= $row->total;
			
		}


		$data = array(
			'softskill_name' => $softskill_name,
			'total'			=> $total
		);


		echo json_encode($data);

 	}


 	public function get_data_topPerformers(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];
 		//$flyear 	= $post['flyear'];

		$whereDiv = "";
		$whereYear = "";

		if (!empty($fldiv)) {
		    $whereDiv = " AND b.division_id = '" . $fldiv . "'";
		}

		/*if (!empty($flyear)) {
		    $whereYear = " AND a.year = '" . $flyear . "'";
		}*/


		
		$rs = $this->db->query("select *
							    FROM (
							        SELECT a.id, a.employee_id, a.year, a.status_id, 
							               a.total_final_score, a.total_final_score_softskill,
							               a.score_val, a.score, b.full_name, b.division_id,
							               ROW_NUMBER() OVER (PARTITION BY a.employee_id ORDER BY a.id DESC) AS rn
							        FROM performance_appraisal a
							        LEFT JOIN employees b ON b.id = a.employee_id
							        WHERE b.status_id = 1
							        $whereDiv
							        $whereYear
							    ) t
							    WHERE rn = 1
							    ORDER BY score_val DESC
							    LIMIT 5
							 ")->result(); 

		$emp=[]; $hardskill=[]; $softskill=[];
		foreach($rs as $row){
			$emp[] 	= $row->full_name;
			$hardskill[] 	= $row->total_final_score;
			$softskill[]	= $row->total_final_score_softskill;
		}


		$data = array(
			'emp' 		=> $emp,
			'hardskill' => $hardskill,
			'softskill' => $softskill
		);


		echo json_encode($data);
 	}


 	public function get_data_achieveTarget(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];
 		//$flyear 	= $post['flyear'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and c.id = '".$fldiv."'";
		}
		$whereYear="";
		/*if(!empty($flyear)){ 
			$whereYear=" and a.year = '".$flyear."'";
		}*/


    	$rs = $this->db->query("select 
								    c.id AS division_id,
								    c.name AS division_name,
								    COALESCE(SUM(a.total_final_score), 0) AS total_score,
								    COUNT(DISTINCT b.id) AS total_employee, 
								    COUNT(DISTINCT a.employee_id) AS total_employee_with_appraisal, 
								    CASE 
								        WHEN COUNT(DISTINCT b.id) = 0 THEN 0
								        ELSE ROUND(SUM(a.total_final_score) / COUNT(DISTINCT b.id), 2)
								    END AS avg_score
								FROM divisions c
								LEFT JOIN employees b 
								       ON b.division_id = c.id 
								      AND b.status_id = 1 ".$whereDiv."
								LEFT JOIN performance_appraisal a 
								       ON a.employee_id = b.id ".$whereYear."
								GROUP BY c.id, c.name
								ORDER BY c.name
								")->result(); 

		$division_name=[]; $total=[]; 
		foreach($rs as $row){
			$division_name[] 	= $row->division_name;
			$total[] 			= $row->avg_score;
			
		}


		$data = array(
			'division_name' => $division_name,
			'total'			=> $total
		);


		echo json_encode($data);


 	}



 	public function get_data_divScore(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and b.division_id = '".$fldiv."'";
		}


    	$rs = $this->db->query("select 
									c.id AS division_id,
									c.name AS division_name,
									SUM(CASE WHEN a.score = 'A' THEN 1 ELSE 0 END) AS total_a,
									SUM(CASE WHEN a.score = 'B' THEN 1 ELSE 0 END) AS total_b,
									SUM(CASE WHEN a.score = 'C' THEN 1 ELSE 0 END) AS total_c,
								    SUM(CASE WHEN a.score = 'D' THEN 1 ELSE 0 END) AS total_d
								FROM divisions c
								LEFT JOIN employees b ON b.division_id = c.id
								LEFT JOIN performance_appraisal a ON a.employee_id = b.id and a.status_id = 2 
								".$whereDiv."
								GROUP BY c.id, c.name
						 ")->result(); 

		$division_name=[]; $total_a=[]; $total_b=[]; $total_c=[]; $total_d=[]; 
		foreach($rs as $row){
			$division_name[] 	= $row->division_name;
			$total_a[] 			= $row->total_a;
			$total_b[] 			= $row->total_b;
			$total_c[] 			= $row->total_c;
			$total_d[] 			= $row->total_d;
		}


		$data = array(
			'division_name' => $division_name,
			'total_a'		=> $total_a,
			'total_b' 		=> $total_b,
			'total_c' 		=> $total_c,
			'total_d' 		=> $total_d
		);


		echo json_encode($data);

 	}



}
