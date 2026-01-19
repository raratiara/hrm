<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Boq_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "boq_menu"; // identify menu
 	const  LABELMASTER				= "Menu BOQ";
 	const  LABELFOLDER				= "hr_menu"; // module folder
 	const  LABELPATH				= "boq_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "hr_menu"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Customer","Project","Tahun"];

	
	/* Export */
	public $colnames 				= ["ID","Customer","Project","Tahun"];
	public $colfields 				= ["id","customer_name","project_name","tahun"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		
		$field = [];

	
		$mscust 					= $this->db->query("select * from data_customer order by name asc")->result(); 
		$field['selcustomer'] 		= $this->self_model->return_build_select2me($mscust,'','','','customer_boq','customer_boq','','','id','name',' ','','','',1,'-');
		$msproject 					= array();
		$field['selproject'] 		= $this->self_model->return_build_select2me($msproject,'','','','project_boq','project_boq','project_boq','','id','project_desc',' ','','','',1,'-');
		

		$field['txttahun'] 			= $this->self_model->return_build_txt('','tahun_boq','tahun_boq');


		
		
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


 	public function getDataProject(){
		$post 		= $this->input->post(null, true);
		$customer 	= $post['customer'];

		$rs =  $this->self_model->getDataProject($customer);
		

		echo json_encode($rs);
	}



	public function genboqrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			$customer = trim($post['customer']);
			$project = trim($post['project']);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewBoqRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewBoqRow($row,$id,$customer,$project,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}



}
