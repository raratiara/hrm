<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_detail_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "dashboard_detail_menu"; // identify menu
 	const  LABELMASTER				= "Menu Dashboard Detail";
 	const  LABELFOLDER				= "dashboard"; // module folder
 	const  LABELPATH				= "dashboard_detail_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "dashboard"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Dashboard"; // 
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


		$field['txtstartdate'] 	= $this->self_model->return_build_txtdate('','start_date','start_date');
		$field['txtenddate'] 	= $this->self_model->return_build_txtdate('','end_date','end_date');
		
		
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


 	// Get List Data
	public function get_data_activity()
	{ 
		
		$id = $_GET['idx'];

		if(_USER_ACCESS_LEVEL_VIEW == "1") {
			$this->self_model->get_list_data($id);
		} else {
			$this->load->view('errors/html/error_hacks_401');
		}
	}

	public function get_cctv()
	{
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			$cctv = $post['cctv'];


			if(isset($cctv))
			{
				$rs =  $this->self_model->getTblCctv($cctv);

				echo json_encode($rs);

			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	public function get_detailJobGraph(){
		
		$post = $this->input->post(null, true);
		$cctv = $post['cctv'];
		$start_date = $post['start_date'];
		$end_date = $post['end_date'];

		if($start_date != ''){ 
			$start_date = date("Y-m-d", strtotime($start_date));
		}
		if($end_date != ''){
			$end_date = date("Y-m-d", strtotime($end_date));
		}


		$rs =  $this->self_model->getJob($cctv, $start_date, $end_date);

		
		echo json_encode($rs);
	}

	public function get_detailActivityGraph(){
		$post = $this->input->post(null, true);
		$jobId = $post['jobId'];

		$rs =  $this->self_model->getActivity($jobId);

		
		echo json_encode($rs);
	}



	



}
