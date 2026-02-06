<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_customer extends MY_Controller
{
	/* Costs */
 	const  LABELMODULE				= "data_customer"; // identify menu
 	const  LABELMASTER				= "Customer";
 	const  LABELFOLDER				= "project"; // module folder
 	const  LABELPATH				= "data_customer"; // controller file (lowercase)
 	const  LABELNAVSEG1				= ""; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= ""; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Kode","Nama","Alamat","Contact","Telp","Email","Status","NPWP"];
	
	/* Export */
	public $colnames 				= ["ID","Kode","Nama","Alamat","Contact","Telp","Email","Status","NPWP"];
	public $colfields 				= ["id","code","name","address","contact_name","contact_phone","contact_email","status","npwp"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];
		$field['txtcode'] 			= $this->self_model->return_build_txt('','code','code');
		$field['txtname'] 			= $this->self_model->return_build_txt('','name','name');
		$field['txtaddress'] 		= $this->self_model->return_build_txtarea('','address','address');
		
		// BOF contact
		$field['datacontact'] 		= '';
		$field['txtcontactname'] 	= $this->self_model->return_build_txt('','contact_name','contact_name');
		$field['txtcontactphone'] 	= $this->self_model->return_build_txt('','contact_phone','contact_phone');
		$field['txtcontactemail'] 	= $this->self_model->return_build_txt('','contact_email','contact_email');
		$field['txtnpwp'] 			= $this->self_model->return_build_txt('','customer_npwp','customer_npwp','','','required');
		$field['txtsistemlembur'] 	= $this->self_model->return_build_radio('', [['sistem_lembur','Ya','required'],['tidak_sistem_lembur','Tidak','required']], 'sistem_lembur', '', 'inline');
		$field['txtnominallembur'] 	= $this->self_model->return_build_txt('','nominal_lembur','nominal_lembur');
		$field['txtpostalcode'] 	= $this->self_model->return_build_txt('','postal_code','postal_code');
		$field['txttglpembayaranlembur'] = $this->self_model->return_build_txt('','tgl_pembayaran_lembur','tgl_pembayaran_lembur');
		

		$oStatus 					= $this->db->select('id,description')->order_by('idx_seq', 'ASC')->where("active='1'")->get(_PREFIX_TABLE."option_general_status")->result();
		$field['seloStatus'] 		= $this->self_model->return_build_select($oStatus,'','','','id_status','id_status','','','id','description');

		$msprovince 					= $this->db->query("select * from provinces order by name asc")->result(); 
		$field['selprovince'] 			= $this->self_model->return_build_select2me($msprovince,'','','','province','province','','','id','name',' ','','','',3,'-');
		$msregency 						= array();
		$field['selregency'] 			= $this->self_model->return_build_select2me($msregency,'','','','regency','regency','regency','','id','name',' ','','','',3,'-');
		$msdistrict 					= array();
		$field['seldistrict'] 			= $this->self_model->return_build_select2me($msdistrict,'','','','district','district','district','','id','name',' ','','','',3,'-');
		$msvillage 						= array(); 
		$field['selvillage'] 			= $this->self_model->return_build_select2me($msvillage,'','','','village','village','village','','id','name',' ','','','',3,'-');


		
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
		//define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_IMPORT',0);
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
 	public $label_modul				= "Data ".self::LABELMASTER;
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



	public function getDataDistrict(){
		$post 		= $this->input->post(null, true);
		$province 	= $post['province'];
		$regency 	= $post['regency'];

		$rs =  $this->self_model->getDataDistrict($province,$regency);
		

		echo json_encode($rs);
	}

	public function getDataRegency(){
		$post 		= $this->input->post(null, true);
		$province 	= $post['province'];

		$rs =  $this->self_model->getDataRegency($province);
		

		echo json_encode($rs);
	}

	public function getDataVillage(){
		$post 		= $this->input->post(null, true);
		$province 	= $post['province'];
		$regency 	= $post['regency'];
		$district 	= $post['district'];

		$rs =  $this->self_model->getDataVillage($province,$regency,$district);
		

		echo json_encode($rs);
	}




}