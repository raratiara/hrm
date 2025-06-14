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


		$msemp 				= $this->db->query("select * from employees")->result(); 
		$field['selemp'] 	= $this->self_model->return_build_select2me($msemp,'','','','fldashemp','fldashemp','','','id','full_name',' ','','','',3,'-');

		$field['master_emp'] = $this->db->query("select * from employees order by full_name asc")->result(); 
		
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

		
		$ttl_emp = $this->db->query("select count(id) as ttl from employees")->result(); 
		$ttl_projects = $this->db->query("select count(id) as ttl from tasklist ")->result(); 
		$ttl_attendance = $this->db->query("select count(id) as ttl from time_attendances ")->result(); 
		$ttl_reimbursement = $this->db->query("select sum(nominal_reimburse) as ttl from medicalreimbursements ")->result(); 
		$ttl_leaves = $this->db->query("select sum(total_leave) as ttl from leave_absences ")->result(); 
		$ttl_overtimes = $this->db->query("select sum(num_of_hour) as ttl from overtimes ")->result(); 

		


		$rs = array(
			'ttl_emp' 			=> $ttl_emp[0]->ttl,
			'ttl_projects' 		=> $ttl_projects[0]->ttl,
			'ttl_attendance'	=> $ttl_attendance[0]->ttl,
			'ttl_reimbursement'	=> 'Rp. '.$ttl_reimbursement[0]->ttl,
			'ttl_leaves'		=> $ttl_leaves[0]->ttl,
			'ttl_overtimes' 	=> $ttl_overtimes[0]->ttl.' hrs'
		);


		
		echo json_encode($rs);
 	}


 	public function get_data_empbyGen(){
 		$post = $this->input->post(null, true);
 		$dateperiod = $post['dateperiod'];
		$employee 	= $post['employee'];


		$data_emp = $this->db->query("select year(date_of_birth) as year_of_birth from employees")->result(); 

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



}
