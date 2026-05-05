<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bpjs_configuration_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "bpjs_configuration_menu";
 	const  LABELMASTER				= "BPJS Configuration";
 	const  LABELFOLDER				= "salary_setting";
 	const  LABELPATH				= "bpjs_configuration_menu";
 	const  LABELNAVSEG1				= "salary_setting";
 	const  LABELSUBPARENTSEG1		= "Salary Setting";
 	const  LABELNAVSEG2				= "";
 	const  LABELSUBPARENTSEG2		= "";
	
	/* View */
	public $icon 					= 'fa-medkit';
	public $tabel_header 			= ["ID","BPJS Type","Employee (%)","Employer (%)","Salary Cap","Tax Ded"];

	/* Export */
	public $colnames 				= ["ID","BPJS Type","Employee (%)","Employer (%)","Salary Cap","Tax Ded"];
	public $colfields 				= ["id","bpjs_type","employee_percentage","employer_percentage","salary_cap","tax_ded"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];
		$field['txtbpjstype'] 			= $this->self_model->return_build_txt('','bpjs_type','bpjs_type','','','required');
		$field['txtemployee'] 			= $this->self_model->return_build_txt('','employee_percentage','employee_percentage');
		$field['txtemployer'] 			= $this->self_model->return_build_txt('','employer_percentage','employer_percentage');
		$field['txtsalarycap'] 			= $this->self_model->return_build_txt('','salary_cap','salary_cap');
		$field['seltaxded'] 			= $this->self_model->return_build_radio('', [['yes','Yes'],['no','No']], 'tax_ded', '', 'inline');
		
		return $field;
	}

	//========================== Considering Already Fixed =======================//
 	/* Construct */
	public function __construct() {
        parent::__construct();
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
 	public $folder_name				= self::LABELFOLDER."/".self::LABELPATH;
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
