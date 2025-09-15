<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Health_daily extends MY_Controller
{ 
	/* Module */
 	const  LABELMODULE				= "health_daily"; // identify menu
 	const  LABELMASTER				= "Menu Health Daily";
 	const  LABELFOLDER				= "health"; // module folder
 	const  LABELPATH				= "health_daily"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "health"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["No","Employee","Date","Sleep Minutes","Steps","Active Calories Kcal","HR Avg BPM","HR Samples","SPO2 Avg PCT","SPO2 Min PCT","SPO2 Max PCT","SPO2 Samples","Source","Platform","Created At","Updated At","Sync ID"];

	
	/* Export */
	public $colnames 				= ["Employee","Date","Sleep Minutes","Steps","Active Calories Kcal","HR Avg BPM","HR Samples","SPO2 Avg PCT","SPO2 Min PCT","SPO2 Max PCT","SPO2 Samples","Source","Platform","Created At","Updated At","Sync ID"];
	public $colfields 				= ["full_name","date","sleep_minutes","steps","active_calories_kcal","hr_avg_bpm","hr_samples","spo2_avg_pct","spo2_min_pct","spo2_max_pct","spo2_samples","source","platform","created_at","updated_at","last_sync_runs_id"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];
		
		
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
