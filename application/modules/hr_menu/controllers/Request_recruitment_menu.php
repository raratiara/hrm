<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_recruitment_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "request_recruitment_menu"; // identify menu
 	const  LABELMASTER				= "Menu Request Recruitment";
 	const  LABELFOLDER				= "hr_menu"; // module folder
 	const  LABELPATH				= "request_recruitment_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "hr_menu"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Request Number","Subject","Request Date","Required Date","Section","Headcount","Job Level","Emp Status","Request By","Status"];

	
	/* Export */
	public $colnames 				= ["ID","Request Number","Subject","Request Date","Required Date","Section","Headcount","Job Level","Emp Status","Request By","Status"];
	public $colfields 				= ["id","year","section_name","level_name","mpp","id","year","section_name","level_name","mpp","id"];


	/* Form Field Asset */
	public function form_field_asset()
	{
		

		$field = [];
		$field['txtreqnumber'] 	= $this->self_model->return_build_txt('','req_number','req_number','','','readonly');
		$field['selsubject']		= $this->self_model->return_build_txt('','subject','subject');
		$field['txtrequestdate']	= $this->self_model->return_build_txt('','request_date','request_date');
		$field['txtrequireddate']	= $this->self_model->return_build_txt('','required_date','required_date');
		$field['txtheadcount']		= $this->self_model->return_build_txt('','headcount','headcount');
		$field['txtjustification']	= $this->self_model->return_build_txtarea('','justification','justification');
		$field['txtstatus']		= $this->self_model->return_build_txt('','status','status');
		$field['txtrejectreason']	= $this->self_model->return_build_txtarea('','reject_reason','reject_reason');

		$mssection 				= $this->db->query("select * from sections order by name asc")->result(); 
		$field['selsection'] 	= $this->self_model->return_build_select2me($mssection,'','','','section','section','','','id','name',' ','','','',1,'-');

		$msjoblevel 			= $this->db->query("select * from master_job_level order by name asc")->result();
		$field['seljoblevel'] 	= $this->self_model->return_build_select2me($msjoblevel,'','','','joblevel','joblevel','','','id','name',' ','','','',1,'-');

		$msempstatus 			= $this->db->query("select * from master_emp_status where name in ('Contract','Freelance','Permanent') order by name asc")->result();
		$field['selempstatus'] 	= $this->self_model->return_build_select2me($msempstatus,'','','','empstatus','empstatus','','','name','name',' ','','','',1,'-');

		$msemp 				= $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		$field['selrequestby'] 	= $this->self_model->return_build_select2me($msemp,'','','','request_by','request_by','','','id','full_name',' ','','','',3,'-');


		
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



 	public function genreqrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewReqRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewReqRow($row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	public function genjobrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewJobRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewJobRow($row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	public function reject(){
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

		$post = $this->input->post(null, true);
		$id = $post['id'];
		$reason = $post['reason'];

		if($id != ''){

			$data = [
				'status' 		=> 'rejected',
				'reject_reason' => $reason,
				'approved_date'	=> date("Y-m-d H:i:s"),
				'approved_by' 	=> $karyawan_id
			];
			$rs = $this->db->update('request_recruitment', $data, "id = '".$id."'");

			return $rs;
			
		}else return null;

		echo json_encode($rs);

	}


	public function approve(){
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

		$post = $this->input->post(null, true);
		$id = $post['id'];

		if($id != ''){

			$data = [
				'status' 		=> 'approved',
				'approved_date'	=> date("Y-m-d H:i:s"),
				'approved_by' 	=> $karyawan_id
			];
			$rs = $this->db->update('request_recruitment', $data, "id = '".$id."'");

			return $rs;
			
		}else return null;

		echo json_encode($rs);

	}




}
