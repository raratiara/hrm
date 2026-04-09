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
		$field['txtendprobdate']		= $this->self_model->return_build_txt('','end_prob_date','end_prob_date');

		
		
		$msstatus 				= $this->db->query("select * from master_status_candidates where id != 5 order by id asc")->result(); 
		$field['selstatus'] 	= $this->self_model->return_build_select2me($msstatus,'','','','status','status','','','id','name',' ','','','',1,'-');

		$msdiv 				= $this->db->query("select * from divisions order by name asc")->result();
		$field['seldiv'] 	= $this->self_model->return_build_select2me($msdiv,'','','','filter-division','filter-division','','','id','name',' ','','','',1,'-');

		$msposition 			= $this->db->query("select * from request_recruitment order by subject asc")->result();
		$field['selposition'] 	= $this->self_model->return_build_select2me($msposition,'','','','filter-position','filter-position','','','subject','subject',' ','','','',1,'-');


		
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


	public function get_card_data()
	{
	    $post = $this->input->post(null, true);
	    $division = $post['division'];
	    $position = $post['position'];

	    $whr_position = "";
	    if (!empty($position)) {
	        $whr_position = " and b.subject = '".$position."'";
	    }

	    $whr_division = "";
	    if (!empty($division)) {
	        $whr_division = " and d.division_id = '".$division."'";
	    }

	    // ambil semua kandidat
	    $sTable = '(SELECT 
	                    a.*,
	                    b.subject AS position_name,
	                    IF(c.name IS NULL,
	                        "Not Started",
	                        c.name) AS status_name,
	                    b.section_id,
	                    d.division_id,
	                    IF(a.status_id = 2,
	                        (SELECT 
	                                bb.name
	                            FROM
	                                candidates_step aa
	                                    LEFT JOIN
	                                master_step_recruitment bb ON bb.id = aa.step_recruitment_id
	                            WHERE
	                                aa.candidates_id = a.id
	                                    AND aa.status_id = 2),
	                        "") AS status_step
	                FROM
	                    candidates a
	                        LEFT JOIN
	                    request_recruitment b ON b.id = a.request_recruitment_id
	                        LEFT JOIN
	                    master_status_candidates c ON c.id = a.status_id
	                        LEFT JOIN
	                    sections d ON d.id = b.section_id
	                WHERE
	                    1 = 1 '.$whr_position.$whr_division.' )dt';

	    $query = $this->db->query("select id, candidate_code, full_name, position_name, email, phone, cv, status_name, status_step FROM $sTable ORDER BY status_name ASC, full_name ASC")->result();

	    // group kandidat
		$grouped = [];
		foreach ($query as $row) {
		    if ($row->status_name === "In Process" && !empty($row->status_step)) {
		        // langsung treat step sebagai status utama
		        $grouped[$row->status_step][] = [
		            'id'       => $row->id,
		            'code'     => $row->candidate_code,
		            'name'     => $row->full_name,
		            'position' => $row->position_name,
		            'email'    => $row->email,
		            'phone'    => $row->phone,
		            'cv'       => $row->cv,
		            'status'   => $row->status_step
		        ];
		    } else {
		        $grouped[$row->status_name][] = [
		            'id'       => $row->id,
		            'code'     => $row->candidate_code,
		            'name'     => $row->full_name,
		            'position' => $row->position_name,
		            'email'    => $row->email,
		            'phone'    => $row->phone,
		            'cv'       => $row->cv,
		            'status'   => $row->status_name
		        ];
		    }
		}

		// urutan custom status
		$custom_order = [
		    "Not Started",
		    "HR Interview",
		    "User Interview",
		    "Technical Test",
		    "Psycho Test",
		    "Medical Check",
		    "Offering Letter",
		    "Hired",        // langsung status sendiri
		    "Not Passed",   // langsung status sendiri
		    "Rejected"
		];


	    $data = [];
		foreach ($custom_order as $st) {
		    $items = isset($grouped[$st]) ? $grouped[$st] : [];
		    $data[] = [
		        'status' => $st,
		        'count'  => count($items),
		        'items'  => $items
		    ];
		}


	    echo json_encode([
	        'success' => true,
	        'data'    => $data
	    ]);
	}


	public function get_card_data_old()
	{
		$post = $this->input->post(null, true);
		$division = $post['division'];
		$position = $post['position'];

		$whr_position="";
		if(!empty($position)){
			$whr_position = " and b.subject = '".$position."'";
		}

		$whr_division="";
		if(!empty($division)){
			$whr_division = " and d.division_id = '".$division."'";
		}


	    // ambil semua kandidat
	    $sTable = '(SELECT 
					    a.*,
					    b.subject AS position_name,
					    IF(c.name IS NULL,
					        "Not Started",
					        c.name) AS status_name,
					    b.section_id,
					    d.division_id,
					    IF(a.status_id = 2,
					        (SELECT 
					                bb.name
					            FROM
					                candidates_step aa
					                    LEFT JOIN
					                master_step_recruitment bb ON bb.id = aa.step_recruitment_id
					            WHERE
					                aa.candidates_id = a.id
					                    AND aa.status_id = 2),
					        "") AS status_step
					FROM
					    candidates a
					        LEFT JOIN
					    request_recruitment b ON b.id = a.request_recruitment_id
					        LEFT JOIN
					    master_status_candidates c ON c.id = a.status_id
					        LEFT JOIN
					    sections d ON d.id = b.section_id
					WHERE
					    1 = 1 '.$whr_position.$whr_division.' )dt';

	    $query = $this->db->query("select id, candidate_code, full_name, position_name, email, phone, cv, status_name, status_step FROM $sTable ORDER BY status_name ASC, full_name ASC")->result();

	    // group kandidat by status
	    $grouped = [];
	    foreach ($query as $row) {
	        $grouped[$row->status_name][] = [
	            'id'       => $row->id,
	            'code'     => $row->candidate_code,
	            'name'     => $row->full_name,
	            'position' => $row->position_name,
	            'email'    => $row->email,
	            'phone'    => $row->phone,
	            'cv'       => $row->cv,
	            'status'   => $row->status_name
	        ];
	    }

	    // urutan custom status
	    $custom_order = ["Not Started", "In Process", "Hired", "Not Passed", "Rejected"];

	    $data = [];
	    foreach ($custom_order as $st) {
	        $data[] = [
	            'status' => $st,
	            'items'  => isset($grouped[$st]) ? $grouped[$st] : []
	        ];
	    }

	    echo json_encode([
	        "success" => true,
	        "data"    => $data
	    ]);
	}






	public function kanban_view()
	{
		$msdiv 				= $this->db->query("select * from divisions order by name asc")->result();
		$field['seldiv'] 	= $this->self_model->return_build_select2me($msdiv,'','','','filter-division','filter-division','','','id','name',' ','','','',1,'-');

		$msposition 			= $this->db->query("select * from request_recruitment order by subject asc")->result();
		$field['selposition'] 	= $this->self_model->return_build_select2me($msposition,'','','','filter-position','filter-position','','','subject','subject',' ','','','',1,'-');


	    $this->load->view(_TEMPLATE_PATH . "module_kanban_candidates_view", $field);
	}



	public function update_status_kanban() {
	    $id = $this->input->post('id');
	    $old_status = $this->input->post('old_status');
	    $new_status = $this->input->post('new_status');

	    if($id != '' && $new_status != ''){  

    		$step_recruitment_id = ''; $status_progress='';

    		/// NEW
	    	if($new_status == 'HR Interview' || $new_status == 'User Interview' || $new_status == 'Technical Test' || $new_status == 'Psycho Test' || $new_status == 'Medical Check' || $new_status == 'Offering Letter'){
	    		
	    		$get_status = $this->db->query("select * from master_step_recruitment where name = '".$new_status."' ")->result();
	    		$step_recruitment_id = $get_status[0]->id;
	    		$status_progress = 2;

	    	}else{
	    		if($new_status == 'Not Started'){
		    		$status_progress = '';
		    	}else{
		    		$get_status = $this->db->query("select * from master_status_candidates where name = '".$new_status."' ")->result();
	    			$status_progress = $get_status[0]->id;
		    	}
	    	}

	    	/// OLD
	    	$status_before_inprocess="";
	    	if($old_status == 'HR Interview' || $old_status == 'User Interview' || $old_status == 'Technical Test' || $old_status == 'Psycho Test' || $old_status == 'Medical Check' || $old_status == 'Offering Letter'){
	    		$status_before_inprocess = 1;
	    		$get_status_before_inprocess = $this->db->query("select * from master_step_recruitment where name = '".$old_status."' ")->result();
	    		$id_before = $get_status_before_inprocess[0]->id;

	    	}


	    	if($step_recruitment_id != ''){ //update ke proses step
	    		$getStep = $this->db->query("select * from candidates_step where candidates_id = '".$id."' and step_recruitment_id = '".$step_recruitment_id."'")->result();
	    		if(!empty($getStep)){ //ada data stepnya, tinggal update
	    			$getStepId = $getStep[0]->id;
	    			$data = [
						'status_id' => 2
					];
					$this->db->update('candidates_step', $data, "candidates_id = '".$id."' and id = '" . $getStepId . "'");

					if($status_before_inprocess == 1){

						$data_before = [
							'status_id' => 5 //done
						];
						$this->db->update('candidates_step', $data_before, "candidates_id = '".$id."' and step_recruitment_id = '" . $id_before . "'");
					}else{
						//update status depan
		        		$datahdr = [
							'status_id' 	=> 2, //in process
							'updated_date' 	=> date("Y-m-d H:i:s")
						];
						$this->db->update("candidates", $datahdr, "id = '".$id."'");
					}

	    		}else{ //blm ada data stepnya, maka insert dulu
	    			$steps =  $this->db->query("select * from master_step_recruitment")->result();
		        	if(!empty($steps)){
		        		foreach($steps as $row_step){
		        			$status_id = '';
		        			if($row_step->id == $step_recruitment_id){
		        				$status_id = 2; //in progress
		        			}
		        			$itemData2 = [
								'candidates_id' => $id,
								'step_recruitment_id' => $row_step->id,
								'status_id' => $status_id
							];

							$this->db->insert('candidates_step', $itemData2);
		        		}

		        		//update status depan 
		        		$datahdr = [
							'status_id' 	=> 2, //in process
							'updated_date' 	=> date("Y-m-d H:i:s")
						];
						$this->db->update("candidates", $datahdr, "id = '".$id."'");
		        	}

	    		}
	    		
	    	}else{ //update ke status depan
	    		if($status_progress != ''){
	    			$data = [
						'status_id' 	=> $status_progress,
						'updated_date' 	=> date("Y-m-d H:i:s")
					];

					$this->db->update("candidates", $data, "id = '".$id."'");
	    		}else{
	    			$datahdr = [
						'status_id' 	=> "",
						'updated_date' 	=> date("Y-m-d H:i:s")
					];
					$this->db->update("candidates", $datahdr, "id = '".$id."'");

	    			$steps =  $this->db->query("select * from candidates_step where candidates_id = '".$id."' ")->result();
	    			
		        	if(!empty($steps)){
		        		foreach($steps as $row_step){
			        		$data = [
								'status_id' 	=> 0
							];
							$this->db->update("candidates_step", $data, "candidates_id = '".$id."'");
		        		}
		        	}
	    		}
	    	}



			echo json_encode(['success' => true]);


	    }else{
	    	echo json_encode(['success' => false]);
	    }
	   
	    
	    
	}





}
