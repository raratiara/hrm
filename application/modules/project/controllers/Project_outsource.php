<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_outsource extends MY_Controller
{ 
	/* Module */
 	const  LABELMODULE				= "project_outsource"; // identify menu
 	const  LABELMASTER				= "Project Outsource";
 	const  LABELFOLDER				= "project"; // module folder
 	const  LABELPATH				= "project_outsource"; // controller file (lowercase)
 	const  LABELNAVSEG1				= ""; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= ""; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Code","Nama Project", "Customer","Lokasi","Jenis Pekerjaan","Management Fee","Period Start","Period End","Jenis Layanan"];
	
	/* Export */
	public $colnames 				= ["ID","Code","Nama Project", "Customer","Lokasi","Jenis Pekerjaan","Management Fee","Period Start","Period End","Jenis Layanan"];
	public $colfields 				= ["id","code","project_name","customer_name","lokasi","jenis_pekerjaan","management_fee", "periode_start","periode_end","jenis_layanan"];


	/* Form Field Asset */
	public function form_field_asset()
	{ 
		$field = [];

		$field['txtkodeproject'] 	= $this->self_model->return_build_txt('','kode_project','kode_project');
		$field['txtnamaproject'] 	= $this->self_model->return_build_txt('','nama_project','nama_project');
		
		$field['txtperiodstart']	= $this->self_model->return_build_txtdate('','periode_start','periode_start');	
		$field['txtperiodend']	= $this->self_model->return_build_txtdate('','periode_end','periode_end');
		$field['txtjenislayanan'] 	= $this->self_model->return_build_txt('','jenis_layanan','jenis_layanan');
		$field['txtjenispekerjaan'] = $this->self_model->return_build_txt('','jenis_pekerjaan','jenis_pekerjaan');
		$field['txtmanagementfee'] 	= $this->self_model->return_build_txt('','management_fee','management_fee');
		

		$mslokasi 					= array();
		$field['sellokasi'] 		= $this->self_model->return_build_select2me($mslokasi,'','','','lokasi','lokasi','','','id','name',' ','','','',3,'-');

		$mscust 					= $this->db->query("select *, if(code != '', concat(code,' - ',name),name) as customer_name from data_customer order by name asc")->result(); 
		$field['seloCustomer'] 		= $this->self_model->return_build_select2me($mscust,'','','','customer','customer','','','id','customer_name',' ','','','',3,'-');
		
		
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




	public function getDataLokasi(){
		$post 		= $this->input->post(null, true);
		$customer 	= $post['customer'];

		$rs =  $this->self_model->getDataLokasi($customer);
		

		echo json_encode($rs);
	}

 
 	
}
