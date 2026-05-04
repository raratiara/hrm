<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tax_rates_menu extends MY_Controller
{
	const  LABELMODULE				= "tax_rates_menu";
	const  LABELMASTER				= "Tax Rates";
	const  LABELFOLDER				= "salary_setting";
	const  LABELPATH				= "tax_rates_menu";
	const  LABELNAVSEG1				= "salary_setting";
	const  LABELSUBPARENTSEG1		= "Salary Setting";
	const  LABELNAVSEG2				= "";
	const  LABELSUBPARENTSEG2		= "";
	
	public $icon 					= 'fa-balance-scale';
	public $tabel_header 			= ["ID","Category","Min Bruto","Max Bruto","Rate","Effective Year"];

	public $colnames 				= ["ID","Category","Min Bruto","Max Bruto","Rate","Effective Year"];
	public $colfields 				= ["id","category","min_bruto","max_bruto","rate","effective_year"];

	public function form_field_asset()
	{
		$field = [];
		$field['txtcategory'] 		= $this->self_model->return_build_txt('','category','category','','','required');
		$field['txtminbruto'] 		= $this->self_model->return_build_txt('','min_bruto','min_bruto','','','required');
		$field['txtmaxbruto'] 		= $this->self_model->return_build_txt('','max_bruto','max_bruto','','','required');
		$field['txtrate'] 			= $this->self_model->return_build_txt('','rate','rate','','','required');
		$field['txteffectiveyear'] 	= $this->self_model->return_build_txt('','effective_year','effective_year');
		return $field;
	}

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

	public $folder_name				= self::LABELFOLDER."/".self::LABELPATH;
	public $module_name				= self::LABELMODULE;
	public $model_name				= self::LABELPATH."_model";
	public $parent_menu				= self::LABELFOLDER;
	public $subparent_menu			= self::LABELNAVSEG1;
	public $subparentitem_menu		= self::LABELNAVSEG2;
	public $sub_menu 				= self::LABELMODULE;
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
