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
	public $tabel_header 			= ["ID","Employee Name","Destination","Start Date","End Date","Reason","Status"];

	
	/* Export */
	public $colnames 				= ["ID","Employee Name","Destination","Start Date","End Date","Reason","Status"];
	public $colfields 				= ["id","full_name","destination","start_date","end_date","reason","status_name"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr=' and id = "'.$karyawan_id.'" or direct_id = "'.$karyawan_id.'" ';
		}



		$field = [];
		
		$field['txtdestination']	= $this->self_model->return_build_txt('','destination','destination');
		$field['txtreason'] 		= $this->self_model->return_build_txtarea('','reason','reason');
		$field['txtstartdate'] 		= $this->self_model->return_build_txt('','start_date','start_date');
		$field['txtenddate'] 		= $this->self_model->return_build_txt('','end_date','end_date');
		
		$msemp 					= $this->db->query("select * from employees where status_id = 1 ".$whr." order by full_name asc")->result(); 
		$field['selemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','',3,'-');


		
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


 	public function reject(){
		$post = $this->input->post(null, true);
		$id = $post['id'];

		if($id != ''){

			$data = [
				'status_id' 	=> 3,
				'approval_date'	=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->update('business_trip', $data, "id = '".$id."'");

			return $rs;
			
		}else{
			$rs=null;
		}

		echo json_encode($rs);

	}

	public function approve(){
		$post = $this->input->post(null, true);
		$id = $post['id'];

		if($id != ''){
			$data = [
				'status_id' 	=> 2, 
				'approval_date'	=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->update('business_trip', $data, "id = '".$id."'");
			
			return $rs;

		}else{
			$rs=null;
		}

		echo json_encode($rs);

	}


	public function genbustriprow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			 
			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewBustripRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewBustripRow($row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}

	public function delrowDetailBustrip(){ 
		$post = $this->input->post(); 
		$id = trim($post['id']); 
		
		if($id != ''){
			$rs = $this->db->delete('business_trip_detail',"id = '".$id."'");
		}
		
	}

 	
}
