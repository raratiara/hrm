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
		$field['txtduedate'] 	= $this->self_model->return_build_type_date('','due_date','due_date','due_date');
		$field['txtsolvedate'] 	= $this->self_model->return_build_txt('','solve_date','solve_date','','','readonly');
		$field['txtprogressdate'] 	= $this->self_model->return_build_txt('','progress_date','progress_date','','','readonly');
		$field['txtrequestdate'] 	= $this->self_model->return_build_txt('','request_date','request_date','','','readonly');
		$field['txtdesc'] 		= $this->self_model->return_build_txtarea('','description','description');
		

		$msstatus 				= $this->db->query("select * from master_tasklist_status where name != 'Open' order by order_no asc")->result(); 
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



	public function get_tasklist_gantt(){
		$post 		= $this->input->post(null, true);
		$employee_id 	= $post['employee_id'];


		/*$whr = '';
		if($employee_id != ''){
			$whr = ' where a.employee_id = "'.$employee_id.'" ';
		}*/


		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}



		
		

		$rs =  $this->db->query('select dt.* from (select a.*, b.full_name as employee_name, c.task as parent_name, d.name as status_name, e.title as project_name, if(a.parent_id = 0 and (select id from tasklist where parent_id = a.id limit 1) != "","yes","no") as is_parent, b.direct_id, DATE_FORMAT(a.created_at, "%Y-%m-%d") as date_create
					from tasklist a left join employees b on b.id = a.employee_id
					left join tasklist c on c.id = a.parent_id
					left join master_tasklist_status d on d.id = a.status_id
					left join data_project e on e.id = a.project_id
					'.$whr.' )dt where is_parent = "no"')->result(); 
		

		echo json_encode($rs);
	}


	public function save_task() { 

		$table_name = "tasklist";
		$post = $this->input->post(null, true);

	    /*$data = [
	        'task' => $this->input->post('name'),
	        'status_name' => $this->input->post('status'),
	        'progress_percentage' => $this->input->post('progress'),
	        'progress_date' => $this->input->post('start'),
	        'solve_date' => $this->input->post('end'),
	        'project_name' => $this->input->post('project'),
	        'parent_name' => $this->input->post('parent'),
	        'description' => $this->input->post('description')
	    ];*/

	    if ($post['id'] == '') {  //add

	    	if(!empty($post['task']) && !empty($post['status']) && !empty($post['due_date'])){  
	  			$cekdata = $this->db->query("select * from tasklist where task = '".$post['task']."'")->result(); 
	  			if(empty($cekdata)){ 
	  				$solve_date="";
		  			if($post['progress'] == 100){
		  				$solve_date = date("Y-m-d H:i:s");
		  			}
		  			$request_date="";
		  			if($post['status'] == 4){ //request
		  				$request_date = date("Y-m-d H:i:s");
		  			}
		  			$progress_date="";
		  			if($post['status'] == 2){ //progress
		  				$progress_date = date("Y-m-d H:i:s");
		  			}

		  			$data = [
						'employee_id' 			=> trim($post['employee'] ?? ''),
						'task' 					=> trim($post['task'] ?? ''),
						'status_id' 			=> trim($post['status'] ?? ''),
						'progress_percentage'	=> trim($post['progress'] ?? ''),
						'due_date' 				=> trim($post['due_date'] ?? ''), //$f_due_date,
						'project_id' 			=> trim($post['project'] ?? ''),
						'parent_id' 			=> trim($post['task_parent'] ?? ''),
						'description' 			=> trim($post['description'] ?? ''),
						'created_at'			=> date("Y-m-d H:i:s"),
						'request_date' 			=> $request_date,
						'progress_date' 		=> $progress_date,
						'solve_date' 			=> $solve_date
						
						
					];
					$rs = $this->db->insert($table_name, $data);
					$lastId = $this->db->insert_id();
					if($rs){
						$data2 = [
							'tasklist_id' 			=> $lastId,
							'progress_percentage'	=> trim($post['progress'] ?? ''),
							'submit_at'				=> date("Y-m-d H:i:s")
						];
						$this->db->insert("history_progress_tasklist", $data2);


						// if($post['status'] == 1){ //Open
						// 	$updDate = [
						// 		'open_date'		=> date("Y-m-d")
						// 	];
						// 	$this->db->update($table_name, $updDate, "id = '".$lastId."'");
						// } 
						if($post['status'] == 2){ //Progress
							$updDate = [
								'progress_date'	=> date("Y-m-d")
							];
							$this->db->update($table_name, $updDate, "id = '".$lastId."'");
						}else if($post['status'] == 4){ //Request
							$updDate = [
								'request_date'	=> date("Y-m-d")
							];
							$this->db->update($table_name, $updDate, "id = '".$lastId."'");
						}


						echo json_encode(['success' => true, 'id' => $lastId]);
					}else{
						$id=0;
	  					echo json_encode(['failed' => true, 'id' => $id]);
					}
	  			}else{
	  				///echo "Submit Failed. Data already exists"; die();
	  				$id=0;
	  				echo json_encode(['failed' => true, 'id' => $id]);
	  			}
	  			

	  		}else{
	  			$id=0;
	  			echo json_encode(['failed' => true, 'id' => $id]);
	  		}




	        /*$this->db->insert('tasklist', $data);
	        $id = $this->db->insert_id();*/

	    } 
	    else { //update

	        /*$id = $this->input->post('id');
	        $this->db->where('id', $id);
	        $this->db->update('tasklist', $data);

	        echo json_encode(['success' => true, 'id' => $id]);*/

	        $id = $post['id'];
	        $dataTasklist = $this->db->query("select * from tasklist where id = '".$id."'")->result(); 

	        $is_request = 0; $is_progress = 0; $is_closed = 0;
	        //$request_date = $dataTasklist[0]->request_date;
	        if($post['status'] == 4 && ($dataTasklist[0]->request_date == '' || $dataTasklist[0]->request_date == null || $dataTasklist[0]->request_date == '0000-00-00')){
	        	//$request_date = date("Y-m-d H:i:s");
	        	$is_request = 1;
	        }
	        //$progress_date = $dataTasklist[0]->progress_date;
	        if($post['status'] == 2 && ($dataTasklist[0]->progress_date == '' || $dataTasklist[0]->progress_date == null || $dataTasklist[0]->progress_date == '0000-00-00')){
	        	//$progress_date = date("Y-m-d H:i:s");
	        	$is_progress = 1;
	        }

	        //$solve_date = $dataTasklist[0]->solve_date;
  			if(($post['progress'] == 100 || $post['status'] == 3) && ($dataTasklist[0]->solve_date == '' || $dataTasklist[0]->solve_date == null || $dataTasklist[0]->solve_date == '0000-00-00') ){
  				//$solve_date = date("Y-m-d H:i:s");
  				$is_closed = 1;
  			}
		
			$data = [
				'employee_id' 			=> trim($post['employee'] ?? ''),
				'task' 					=> trim($post['task'] ?? ''),
				'progress_percentage'	=> trim($post['progress'] ?? ''),
				'parent_id' 			=> trim($post['task_parent'] ?? ''),
				'status_id' 			=> trim($post['status'] ?? ''),
				'due_date' 				=> trim($post['due_date'] ?? ''), 
				'project_id' 			=> trim($post['project'] ?? ''),
				'description' 			=> trim($post['description'] ?? ''),
				'updated_at'			=> date("Y-m-d H:i:s")
			];

			$rs = $this->db->update($table_name, $data, "id = '".$id."'");
			if($rs){
				$data2 = [
					'tasklist_id' 			=> $post['id'],
					'progress_percentage'	=> trim($post['progress'] ?? ''),
					'submit_at'				=> date("Y-m-d H:i:s")
				];
				$this->db->insert("history_progress_tasklist", $data2);


				if($is_request == 1){ //Request
					$updDate = [
						'request_date'	=> date("Y-m-d")
					];
					$this->db->update($table_name, $updDate, "id = '".$id."'");
				}
				if($is_progress == 1){ //Progress
					$updDate = [
						'progress_date'	=> date("Y-m-d")
					];
					$this->db->update($table_name, $updDate, "id = '".$id."'");
				}
				if($is_closed == 1){ //Closed
					$updDate = [
						'solve_date'	=> date("Y-m-d")
					];
					$this->db->update($table_name, $updDate, "id = '".$id."'");
				}


				echo json_encode(['success' => true, 'id' => $id]);

			}else{
				echo json_encode(['failed' => true, 'id' => $id]);
			}



	    }

	    
	}


	public function delete_task() { 

		$table_name = "tasklist";
		$post = $this->input->post(null, true);
		$id = trim($post['id']); 

		if($id != ''){
			$rs = $this->db->delete('tasklist',"id = '".$id."'");
			if($rs){
				echo json_encode(['success' => true, 'id' => $id]);
			}else{
				echo json_encode(['failed' => true, 'id' => $id]);
			}
		}else{
			echo json_encode(['failed' => true, 'id' => $id]);
		}

	    
	}


	public function update_task_dates(){
		$post = $this->input->post(null, true);

		$id 		= trim($post['id']); 
		$start_date = trim($post['start_date']); 
		$end_date 	= trim($post['end_date']); 



		if($id != ''){
			$data = [
				'progress_date' => $start_date,
				'due_date' 		=> $end_date,
				'updated_at'	=> date("Y-m-d H:i:s")
			];

			$rs = $this->db->update("tasklist", $data, "id = '".$id."'");

			if($rs){
				echo json_encode(['success' => true, 'id' => $id]);
			}else{
				echo json_encode(['failed' => true, 'id' => $id]);
			}
		}else{
			echo json_encode(['failed' => true, 'id' => $id]);
		}

	}

	public function update_task_progress(){
		$post = $this->input->post(null, true);

		$id 		= trim($post['id']); 
		$progress 	= trim($post['progress']); 



		if($id != ''){
			$data = [
				'progress_percentage' 	=> $progress,
				'updated_at'			=> date("Y-m-d H:i:s")
			];

			$rs = $this->db->update("tasklist", $data, "id = '".$id."'");

			if($rs){
				echo json_encode(['success' => true, 'id' => $id]);
			}else{
				echo json_encode(['failed' => true, 'id' => $id]);
			}
		}else{
			echo json_encode(['failed' => true, 'id' => $id]);
		}

	}


}
