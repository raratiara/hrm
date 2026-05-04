<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_components_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "salary_components_menu"; // identify menu
 	const  LABELMASTER				= "Salary Components";
 	const  LABELFOLDER				= "salary_setting"; // module folder
 	const  LABELPATH				= "salary_components_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "salary_setting"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Salary Setting"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-money';
	public $tabel_header 			= ["ID","Name","Type","Default Amount","Fixed","Active","Sort Order"];

	
	/* Export */
	public $colnames 				= ["ID","Name","Type","Default Amount","Fixed","Active","Sort Order","Description"];
	public $colfields 				= ["id","name","type","default_amount","is_fixed_name","is_active_name","sort_order","description"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];
		$field['txtname'] 			= $this->self_model->return_build_txt('','name','name','','','required');
		$field['seltype'] 			= $this->self_model->return_build_radio('', [['earning','Earning'],['deduction','Deduction']], 'type', '', 'inline');
		$field['txtdefaultamount'] 	= $this->self_model->return_build_txt('','default_amount','default_amount','','','','number');
		$field['selisfixed'] 		= $this->self_model->return_build_radio('', [['1','Yes'],['0','No']], 'is_fixed', '', 'inline');
		$field['selisactive'] 		= $this->self_model->return_build_radio('', [['1','Active'],['0','Not Active']], 'is_active', '', 'inline');
		$field['txtsortorder'] 		= $this->self_model->return_build_txt('','sort_order','sort_order');
		$field['txtdescription'] 	= $this->self_model->return_build_txtarea('','description','description');
		
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
 	public $label_detail_data		= "Detail Data ".self::LABELMASTER;
 	public $label_import_data		= "Import Data ".self::LABELMASTER;
 	public $label_sukses_diimport 	= "Data berhasil diimport";
 	public $label_gagal_diimport 	= "Import data di baris : ";
 	public $label_export_data		= "Export";
 	public $label_gagal_eksekusi 	= "Eksekusi gagal karena ketiadaan data";

}
