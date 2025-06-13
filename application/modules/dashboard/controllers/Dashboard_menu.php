<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "dashboard_menu"; // identify menu
 	const  LABELMASTER				= "Menu Dashboard";
 	const  LABELFOLDER				= "dashboard"; // module folder
 	const  LABELPATH				= "dashboard_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "dashboard"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Dashboard"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Floating Crane","CCTV Code","CCTV Name"];
	
	/* Export */
	public $colnames 				= ["Date","Order No","Order Name","Floating Crane","Mother Vessel","Activity","Datetime Start","Datetime End","Total Time","Degree","Degree 2","PIC","Status"];
	public $colfields 				= ["date","order_no","order_name","floating_crane_name","mother_vessel_name","activity_name","datetime_start","datetime_end","total_time","degree","degree_2","pic","status_name"];




	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];
		$id_fc = $_GET['id'];

	
		$field['txtstartdate'] 		= $this->self_model->return_build_txtdate('','start_date','start_date');
		$field['txtenddate'] 		= $this->self_model->return_build_txtdate('','end_date','end_date');
		$msfloating 				= $this->db->query("select * from floating_crane")->result(); 
		$field['selfloatcrane'] 	= $this->self_model->return_build_select2me($msfloating,'','','','floating_crane','floating_crane','','','id','name',' ','','','',3,'-');
		$field['txtdatetimestart']	= $this->self_model->return_build_txt('','txtdatetimestart','txtdatetimestart','','','readonly');
		$msorder 					= $this->db->query("select * from job_order where floating_crane_id = '".$id_fc."'")->result(); 
		$field['selordername'] 	= $this->self_model->return_build_select2me($msorder,'','','','order_name','order_name','','','id','order_name',' ','','','',3,'-');
		$field['txtcurrdatetime'] 	= $this->self_model->return_build_txt('','txtcurrdatetime','txtcurrdatetime','','','readonly');
		$field['txtmothervessel'] 	= $this->self_model->return_build_txt('','txtmothervessel','txtmothervessel','','','readonly');
		$field['txtprocesstime'] 	= $this->self_model->return_build_txt('','txtprocesstime','txtprocesstime','','','readonly');

		
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
		$orderid = $_GET['orderid'];
//echo $orderid; die();
		if(_USER_ACCESS_LEVEL_VIEW == "1") {
			$this->self_model->get_list_data($id, $orderid);
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

	public function get_data_realtime()
	{
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			$idfc = $post['idfc'];
			$orderid = $post['orderid'];

			if(isset($idfc))
			{
				$rs =  $this->self_model->getTblRealtime($idfc, $orderid);

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
		$fcId = $post['fcId'];

		$rs =  $this->self_model->getActivity($jobId, $fcId);

		
		echo json_encode($rs);
	}

	public function get_detailwaktuAct(){
		$post = $this->input->post(null, true);
		$activity = $post['activity'];
		$job = $post['jobId'];
		$fcId = $post['fcId'];

		$rs =  $this->self_model->getdetailwaktuAct($activity, $job, $fcId);

		
		echo json_encode($rs);
	}

	
	public function get_data_waktu_activity()
	{ 
		
		$job = $_GET['job'];
		$activity = $_GET['activity'];
		$fcId = $_GET['fcId'];

		if(_USER_ACCESS_LEVEL_VIEW == "1") {
			$this->self_model->get_list_data_waktu($job, $activity, $fcId);
		} else {
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	// Export Data Activity Monitor
	public function eksport_activity_monitor()
	{ 
		$post = $this->input->post(null, true);
		$id = $post['id'];

		if(_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_EKSPORT == "1") { 
			$header	= "Report Activity Monitor";
			$data 	= $this->self_model->eksport_data_activity_monitor($id);
			$this->self_model->export_to_csv($this->colnames,$this->colfields, $data, $header ,"report_dashboard_activity_monitor");
		} else {
			$this->load->view('errors/html/error_hacks_401');
		}
	}

	public function get_Data_FC(){
		$post = $this->input->post(null, true);
		$id_fc = $post['id_fc'];

		$data['datafc'] =  $this->self_model->getDataFC($id_fc);

		$data['msorder'] = $this->db->query("select dt.* from(
							select a.id, a.job_order_id, b.order_name,
							Row_number() over (partition by a.job_order_id order by id desc) as 'RowN'
							from job_order_detail a
							LEFT JOIN job_order b ON b.id = a.job_order_id
							WHERE b.floating_crane_id = '".$id_fc."')dt where dt.RowN = 1")->result();
		
		
		echo json_encode($data);
	}

	public function get_sla_cycle_percentage(){
		$post = $this->input->post(null, true);
		$fcId = $post['fcId'];
		$orderid = $post['orderid'];

		$rs =  $this->self_model->getslaCyclePercentage($fcId, $orderid);

		
		echo json_encode($rs);
	}

	public function get_Data_By_Order(){
		$post = $this->input->post(null, true);
		$orderid = $post['orderid'];
		$id_fc = $post['id_fc'];

		$rs =  $this->self_model->getDataByOrder($orderid, $id_fc);
		

		echo json_encode($rs);
	}



}
