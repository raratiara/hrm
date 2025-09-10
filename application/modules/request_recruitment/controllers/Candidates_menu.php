<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Candidates_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "candidates_menu"; // identify menu
 	const  LABELMASTER				= "Menu Candidates";
 	const  LABELFOLDER				= "request_recruitment"; // module folder
 	const  LABELPATH				= "candidates_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "request_recruitment"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["No","Code","Name","Position","Email","Phone","CV","Status"];

	
	/* Export */
	public $colnames 				= ["Code","Name","Position","Email","Phone","Status"];
	public $colfields 				= ["candidate_code","full_name","position_name","email","phone","status_name"];


	/* Form Field Asset */
	public function form_field_asset()
	{
		

		$field = [];
		
		$field['txtposition']	= $this->self_model->return_build_txt('','position','position','','','readonly');
		$field['txtname']		= $this->self_model->return_build_txt('','name','name','','','readonly');
		$field['txtemail']		= $this->self_model->return_build_txt('','email','email','','','readonly');
		$field['txtphone']		= $this->self_model->return_build_txt('','phone','phone','','','readonly');
		$field['txtcv'] 		= $this->self_model->return_build_fileinput('cv','cv');
		$field['txtjoindate']			= $this->self_model->return_build_txt('','join_date','join_date');
		$field['txtcontractsigndate']	= $this->self_model->return_build_txt('','contract_sign_date','contract_sign_date');
		
		
		$msstatus 				= $this->db->query("select * from master_status_candidates where id != 5 order by id asc")->result(); 
		$field['selstatus'] 	= $this->self_model->return_build_select2me($msstatus,'','','','status','status','','','id','name',' ','','','',1,'-');


		
		return $field;
	}

	//========================== Considering Already Fixed =======================//
 	/* Construct */
	public function __construct() {
        parent::__construct();
		# akses level
		$akses = $this->self_model->user_akses($this->module_name);

		$getdata = $this->db->query("select a.*, b.job_level_id from user a left join employees b on b.id = a.id_karyawan where a.user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$job_level_id = $getdata[0]->job_level_id; 

		if($akses['role_id'] == '3'){ //user biasa
			if($job_level_id <= 5 && $job_level_id != 0){ //job levelnya manager ke atas
				define('_USER_ACCESS_LEVEL_VIEW',1);
				define('_USER_ACCESS_LEVEL_ADD',1);
				define('_USER_ACCESS_LEVEL_UPDATE',1);
				define('_USER_ACCESS_LEVEL_DELETE',1);
				define('_USER_ACCESS_LEVEL_DETAIL',1);
				define('_USER_ACCESS_LEVEL_IMPORT',1);
				define('_USER_ACCESS_LEVEL_EKSPORT',1);
			}else{
				define('_USER_ACCESS_LEVEL_VIEW',0);
				define('_USER_ACCESS_LEVEL_ADD',0);
				define('_USER_ACCESS_LEVEL_UPDATE',0);
				define('_USER_ACCESS_LEVEL_DELETE',0);
				define('_USER_ACCESS_LEVEL_DETAIL',0);
				define('_USER_ACCESS_LEVEL_IMPORT',0);
				define('_USER_ACCESS_LEVEL_EKSPORT',0);
			}
		}else{
			define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
			define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
			define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
			define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
			define('_USER_ACCESS_LEVEL_DETAIL',$akses["detail"]);
			define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
			define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);
		}

		
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


 	public function getDataStep(){
		$post = $this->input->post(null, true);
		$id = $post['id'];
		$save_method = $post['save_method'];

		$rs =  $this->self_model->getDataStep($id,$save_method);
		

		echo json_encode($rs);
	}




}
