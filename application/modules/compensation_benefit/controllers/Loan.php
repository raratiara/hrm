<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan extends MY_Controller
{ 
	/* Module */
 	const  LABELMODULE				= "loan"; // identify menu
 	const  LABELMASTER				= "Pinjaman";
 	const  LABELFOLDER				= "compensation_benefit"; // module folder
 	const  LABELPATH				= "loan"; // controller file (lowercase)
 	const  LABELNAVSEG1				= ""; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= ""; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Nama Karyawan","Nominal","Tenor","Sisa Tenor","Bunga","Cicilan","Start Angsuran"];
	
	/* Export */
	public $colnames 				= ["ID","Nama Karyawan","Nominal","Tenor","Sisa Tenor","Bunga","Cicilan","Start Angsuran"];
	public $colfields 				= ["id","id_employee","nominal_pinjaman","tenor","sisa_tenor","bunga_per_bulan", "nominal_cicilan_per_bulan","date_start_cicilan"];


	/* Form Field Asset */
	public function form_field_asset()
	{ 
		$field = [];

		$oKaryawan 									= $this->db->query("select * from employees where status_id = 1  order by full_name asc")->result(); 
		$field['seloPic'] 							= $this->self_model->return_build_select2me($oKaryawan,'','','','id_employee','id_employee','','','id','full_name',' ','','','',3,'-');

		$field['txt_nominal_pinjaman']				= $this->self_model->return_build_txt('','nominal_pinjaman','nominal_pinjaman');
		$field['txt_tenor'] 						= $this->self_model->return_build_txt('','tenor','tenor');
		$field['txt_sisa_tenor'] 					= $this->self_model->return_build_txt('','sisa_tenor','sisa_tenor');
		$field['txt_bunga_per_bulan'] 				= $this->self_model->return_build_txt('','bunga_per_bulan','bunga_per_bulan');
		$field['txt_nominal_cicilan_per_bulan']  	= $this->self_model->return_build_txt('','nominal_cicilan_per_bulan','nominal_cicilan_per_bulan');
		$field['txt_date_pengajuan'] 				= $this->self_model->return_build_txtdate('','date_pengajuan','date_pengajuan');
		$field['txt_date_persetujuan'] 				= $this->self_model->return_build_txtdate('','date_persetujuan','date_persetujuan');
		$field['txt_date_pencairan'] 				= $this->self_model->return_build_txtdate('','date_pencairan','date_pencairan');
		$field['txt_date_start_cicilan'] 			= $this->self_model->return_build_txtdate('','date_start_cicilan','date_start_cicilan');
  
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
		define('_USER_ACCESS_LEVEL_IMPORT',0);
		define('_USER_ACCESS_LEVEL_EKSPORT',0);
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
