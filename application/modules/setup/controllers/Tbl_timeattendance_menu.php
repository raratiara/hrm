<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tbl_timeattendance_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "tbl_timeattendance_menu"; // identify menu
 	const  LABELMASTER				= "Menu Tbl Time Attendance";
 	const  LABELFOLDER				= "setup"; // module folder
 	const  LABELPATH				= "tbl_timeattendance_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "setup"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["id","date_attendance","employee_id","attendance_type","time_in","time_out","date_attendance_in","date_attendance_out","is_late","is_leaving_office_early","num_of_working_hours","created_at","updated_at","leave_type","notes","photo"];

	
	/* Export */
	public $colnames 				= ["id","date_attendance","employee_id","attendance_type","time_in","time_out","date_attendance_in","date_attendance_out","is_late","is_leaving_office_early","num_of_working_hours","created_at","updated_at","leave_type","notes","photo"];
	public $colfields 				= ["id","id","id","id"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];
		
		$field['txtemail'] 		= $this->self_model->return_build_txt('','email','email','','','readonly');
		$field['txtpwd'] 		= $this->self_model->return_build_txt('','pwd','pwd');
		$field['txtusername']	= $this->self_model->return_build_txt('','username','username');

		$msemp 					= $this->db->query("select * from employees")->result(); 
		$field['selname'] 		= $this->self_model->return_build_select2me($msemp,'','','','name','name','','','id','full_name',' ','','','',3,'-');
		$field['selstatus'] 	= $this->self_model->return_build_radio('', [['2','Active'],['0','Not Active']], 'status', '', 'inline');



		
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


 	public function getDataEmp(){
		$post = $this->input->post(null, true);
		$empid = $post['empid'];

		$rs =  $this->self_model->getDataEmployee($empid);
		

		echo json_encode($rs);
	}



}
