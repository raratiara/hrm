<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cctv_surveilance_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "cctv_surveilance_menu"; // identify menu
 	const  LABELMASTER				= "Menu CCTV Surveilance";
 	const  LABELFOLDER				= "cctv_surveilance"; // module folder
 	const  LABELPATH				= "cctv_surveilance_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "cctv_surveilance"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "CCTV Surveilance"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Floating Crane","CCTV Code","CCTV Name"];
	
	/* Export */
	public $colnames 				= ["ID","Floating Crane","CCTV Code","CCTV Name"];
	public $colfields 				= ["id","id","id","id"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];
		$field['txtcode'] 		= $this->self_model->return_build_txt('','code','code');
		$field['txtname'] 		= $this->self_model->return_build_txt('','name','name');
		$field['txtposisi'] 		= $this->self_model->return_build_txt('','posisi','posisi');
		$field['txtipcctv'] 		= $this->self_model->return_build_txt('','ip_cctv','ip_cctv');
		$field['txtipserver'] 		= $this->self_model->return_build_txt('','ip_server','ip_server');
		$field['txtlinkrtsp'] 		= $this->self_model->return_build_txt('','rtsp','rtsp');
		$field['txtthumbnail'] 		= $this->self_model->return_build_txt('','thumbnail','thumbnail');
		$field['txtlinkembed'] 		= $this->self_model->return_build_txt('','embed','embed');
		$field['txtlatitude'] 		= $this->self_model->return_build_txt('','latitude','latitude');
		$field['txtlongitude'] 		= $this->self_model->return_build_txt('','longitude','longitude');

		$field['rdoisactive'] 	= $this->self_model->return_build_radio('', [['1','Yes'],['0','No']], 'is_active', '', 'inline');
		$msfloating 			= $this->db->query("select * from floating_crane")->result(); 
		$field['selfloatcrane'] = $this->self_model->return_build_select2me($msfloating,'','','','floating_crane','floating_crane','','','id','name',' ','','','',3,'-');
		$mstypestreaming 			= $this->db->query("select * from mother_vessel")->result(); 
		$field['seltypestreaming'] = $this->self_model->return_build_select2me($mstypestreaming,'','','','type_streaming','type_streaming','','','id','name',' ','','','',3,'-');
		
		
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


	public function get_cctv()
	{
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			$cctv = $post['cctv'];
			$jmlcctv = $post['jmlcctv'];


			if(isset($cctv))
			{
				$rs =  $this->self_model->getTblCctv($cctv,$jmlcctv);

				echo json_encode($rs);

			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


}
