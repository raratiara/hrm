<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_order_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "job_order_menu"; // identify menu
 	const  LABELMASTER				= "Menu Job Order";
 	const  LABELFOLDER				= "job_order"; // module folder
 	const  LABELPATH				= "job_order_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "job_order"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Job Order"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Date","Order No","Order Name"];
	
	/* Export */
	public $colnames 				= ["ID","Date","Order No","Order Name"];
	public $colfields 				= ["id","id","id","id"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = []; 
		// $val='', $var_name, $id_name='', $addclass='', $addstyle='', $attrib='')
		$field['txtdatepekerjaan'] 	= $this->self_model->return_build_txtdate('','date_pekerjaan','date_pekerjaan');
		$field['txtorderno'] 		= $this->self_model->return_build_txt('','order_no','order_no','','','readonly');
		$field['txtordername'] 		= $this->self_model->return_build_txt('','order_name','order_name');
		$field['txtpic'] 			= $this->self_model->return_build_txt('','pic','pic');
		$field['txtdatetimestart'] 	= $this->self_model->return_build_txtdate('','date_time_start','date_time_start');
		$field['txtdatetimeend'] 	= $this->self_model->return_build_txtdate('','date_time_end','date_time_end'); // $val='', $var_name, $id_name='', $addclass='', $addstyle='', $attrib=''
		$field['txttotaltime'] 		= $this->self_model->return_build_txt('','total_time','total_time','','','readonly');

		$field['rdoisactive'] 		= $this->self_model->return_build_radio('', [['1','Yes'],['0','No']], 'is_active', '', 'inline');
		$msfloating 				= $this->db->query("select * from floating_crane")->result(); 
		$field['selfloatcrane'] 	= $this->self_model->return_build_select2me($msfloating,'','','','floating_crane','floating_crane','','','id','name',' ','','','',3,'-');
		$msmothervessel				= $this->db->query("select * from mother_vessel")->result(); 
		$field['selmothervessel'] 	= $this->self_model->return_build_select2me($msmothervessel,'','','','mother_vessel','mother_vessel','','','id','name',' ','','','',3,'-');
		$msstatus 					= $this->db->query("select * from status")->result(); 
		$field['selstatus'] 		= $this->self_model->return_build_select2me($msstatus,'','','','status','status','','','id','name',' ','','','',3,'-');

		
		
		
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
}
