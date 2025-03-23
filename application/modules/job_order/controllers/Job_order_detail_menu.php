<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_order_detail_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "job_order_detail_menu"; // identify menu
 	const  LABELMASTER				= "Menu Job Order Detail";
 	const  LABELFOLDER				= "job_order"; // module folder
 	const  LABELPATH				= "job_order_detail_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "job_order"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Job Order Detail"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Floating Crane","Mother Vessel", "Job Order","Activity", "Datetime Start", "Datetime End", "Degree", "Degree 2"];
	

	/* Export */
	public $colnames 				= ["ID","Job Order","Activity"];
	public $colfields 				= ["id","id","id"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = []; 
		
		$field['txtdatetimestart'] 	= $this->self_model->return_build_txtdate('','date_time_start','date_time_start');
		$field['txtdatetimeend'] 	= $this->self_model->return_build_txtdate('','date_time_end','date_time_end'); 
		$field['txtdegree'] 		= $this->self_model->return_build_txt('','degree','degree','','','');
		$field['txtdegree_2'] 		= $this->self_model->return_build_txt('','degree_2','degree_2','','','');
		$field['txtsla'] 		= $this->self_model->return_build_txt('','sla','sla','','','readonly');
		$msjob	= $this->db->query("select * from job_order where order_status = 2")->result(); 
		$field['seljoborder'] 	= $this->self_model->return_build_select2me($msjob,'','','','job_order','job_order','','','id','order_name',' ','','','',3,'-');
		$msactivity				= $this->db->query("select * from activity")->result(); 
		$field['selactivity'] 	= $this->self_model->return_build_select2me($msactivity,'','','','activity','activity','','','id','activity_name',' ','','','',3,'-');
		

		
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

 	public function get_data_sla(){
		$post = $this->input->post(null, true);
		$activity = $post['activity'];

		
		$data = $this->db->query("select * from sla where activity_id = '".$activity."'")->result();
		
		
		echo json_encode($data);
	}



}
