<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perjalanan_dinas_menu extends MY_Controller
{ 
	/* Module */
 	const  LABELMODULE				= "perjalanan_dinas_menu"; // identify menu
 	const  LABELMASTER				= "Menu Perjalanan Dinas";
 	const  LABELFOLDER				= "compensation_benefit"; // module folder
 	const  LABELPATH				= "perjalanan_dinas_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "compensation_benefit"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Date","Employee Name","Reimburs For","Atas Nama","Diagnosa","Nominal Billing","Nominal Reimburs","Status"];

	
	/* Export */
	public $colnames 				= ["ID","Date","Employee Name","Reimburs For","Atas Nama","Diagnosa","Nominal Billing","Nominal Reimburs","Status"];
	public $colfields 				= ["id","date_reimbursment","employee_name","reimburse_for_name","atas_nama","diagnosa","nominal_billing","nominal_reimburse","id"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];
		
		$field['txtdate'] 				= $this->self_model->return_build_txt('','date','date');
		$field['txtnominalreimburs'] 	= $this->self_model->return_build_txt('','nominal_reimburs','nominal_reimburs','','','readonly');
		$field['txtatasnama'] 			= $this->self_model->return_build_txt('','atas_nama','atas_nama');
		$field['txtdiagnosa'] 			= $this->self_model->return_build_txt('','diagnosa','diagnosa');
		$field['txtnominalbilling'] 	= $this->self_model->return_build_txt('','nominal_billing','nominal_billing');

		$msemp 					= $this->db->query("select * from employees")->result(); 
		$field['selemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','',3,'-');
		$msreimbursfor 			= $this->db->query("select * from master_reimbursfor_type")->result(); 
		$field['selreimbursfor'] 	= $this->self_model->return_build_select2me($msreimbursfor,'','','','reimburs_for','reimburs_for','','','id','name',' ','','','',3,'-');
		$mstype 			= $this->db->query("select * from master_reimburs_type")->result(); 
		$field['seltype'] 	= $this->self_model->return_build_select2me($mstype,'','','','type','type','','','id','name',' ','','','',3,'-');

		
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


 	public function genexpensesrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			$type = trim($post['type']); 

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewExpensesRow($row,$type);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewExpensesRow($row,$type,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}

	public function delrowDetailReimburs(){ 
		$post = $this->input->post(); 
		$id = trim($post['id']); 
		
		if($id != ''){
			$rs = $this->db->delete('cctv',"id = '".$id."'");
		}
		
	}

	public function getDataSubtype(){
		$post = $this->input->post(null, true);
		$type = $post['type'];

		$rs =  $this->self_model->getDataSubtype($type);
		

		echo json_encode($rs);
	}



}
