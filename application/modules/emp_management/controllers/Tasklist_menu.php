<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasklist_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "tasklist_menu"; // identify menu
 	const  LABELMASTER				= "Menu Tasklist Karyawan";
 	const  LABELFOLDER				= "emp_management"; // module folder
 	const  LABELPATH				= "tasklist_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "emp_management"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Employee Name","Task","Task Parent","Status","Progress (%)","Due Date","Solve Date","Project"];

	
	/* Export */
	public $colnames 				= ["ID","Employee Name","Project","Task","Task Parent","Status","Progress (%)","Due Date", "Solve Date","Description"];
	public $colfields 				= ["id","employee_name","project_name","task","parent_name","status_name","progress_percentage","due_date","solve_date","description"];

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
		
		$field['txttask'] 		= $this->self_model->return_build_txt('','task','task');
		$field['txtprogress'] 	= $this->self_model->return_build_txt('','progress','progress');
		$field['txtduedate'] 	= $this->self_model->return_build_txt('','due_date','due_date');
		$field['txtsolvedate'] 	= $this->self_model->return_build_txt('','solve_date','solve_date','','','readonly');
		$field['txtdesc'] 		= $this->self_model->return_build_txtarea('','description','description');
		

		$msstatus 				= $this->db->query("select * from master_tasklist_status order by order_no asc")->result(); 
		$field['selstatus'] 	= $this->self_model->return_build_select2me($msstatus,'','','','status','status','','','id','name',' ','','','',3,'-');
		$mstask 				= $this->db->query("select * from tasklist")->result(); 
		$field['seltaskparent'] = $this->self_model->return_build_select2me($mstask,'','','','task_parent','task_parent','','','id','task',' ','','','',3,'-');
		$msemp 					= $this->db->query("select * from employees where status_id = 1 ".$whr." order by full_name asc")->result(); 
		$field['selemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','',3,'-');

		$msproject 				= $this->db->query("select * from data_project")->result(); 
		$field['selproject'] 	= $this->self_model->return_build_select2me($msproject,'','','','project','project','','','id','title',' ','','','',3,'-');



		
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


 	public function getDataEmp(){
		$post = $this->input->post(null, true);
		$empid = $post['empid'];

		$rs =  $this->self_model->getDataEmployee($empid);
		

		echo json_encode($rs);
	}



}
