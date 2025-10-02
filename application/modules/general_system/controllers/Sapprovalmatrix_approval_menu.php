<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sapprovalmatrix_approval_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "sapprovalmatrix_approval_menu"; // identify menu
 	const  LABELMASTER				= "Approval Matrix";
 	const  LABELFOLDER				= "general_system"; // module folder
 	const  LABELPATH				= "sapprovalmatrix_approval_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "general_system_approval_matrix"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Setup Approval Matrix"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Approval Name","Approval Type","Location","Leave Type","Min","Max","Description"];
	
	/* Export */
	public $colnames 				= ["ID","Approval Name","Approval Type","Location","Leave Type","Min","Max","Description"];
	public $colfields 				= ["id","approval_name","approval_type_name","work_location_name","leave_type_name","min","max","description"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];


		$msloc 				= $this->db->query("select * from master_work_location order by name asc")->result(); 
		$field['selloc'] 	= $this->self_model->return_build_select2me($msloc,'','','','location','location','','','id','name',' ','','','',3,'-');

		$msapprovaltype 	= $this->db->query("select * from approval_matrix_mstype order by name asc")->result(); 
		$field['selapprovaltype'] 	= $this->self_model->return_build_select2me($msapprovaltype,'','','','approval_type','approval_type','','','id','name',' ','','','',3,'-');

		$msabsencetype 	= $this->db->query("select * from master_leaves where id != 3 order by name asc")->result(); 
		$field['selabsencetype'] 	= $this->self_model->return_build_select2me($msabsencetype,'','','','absence_type','absence_type','','','id','name',' ','','','',3,'-');

		$field['txtapprovalname'] 	= $this->self_model->return_build_txt('','approval_name','approval_name');
		$field['txtmin'] 	= $this->self_model->return_build_txt('','min','min');
		$field['txtmax'] 	= $this->self_model->return_build_txt('','max','max');
		$field['txtdescription']	= $this->self_model->return_build_txtarea('','description','description');
		
		
		
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




 	public function genpicrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			$location = $post['location'];

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewPicRow($location,$row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewPicRow($location,$row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}



	public function getDataRole(){
		$post = $this->input->post(null, true);
		$location = $post['location'];

		$rs =  $this->self_model->getDataRole($location);
		

		echo json_encode($rs);
	}

	public function delrowDetailPic(){ 
		$post = $this->input->post(); 
		$id = trim($post['id']); 
		
		if($id != ''){
			$rs = $this->db->delete('approval_matrix_detail',"id = '".$id."'");
		}
		
	}



}
