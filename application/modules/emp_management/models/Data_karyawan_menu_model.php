<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_karyawan_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "emp_management/data_karyawan_menu";
 	protected $table_name 				= _PREFIX_TABLE."employees";
 	protected $primary_key 				= "id";

 	/* upload */
 	protected $attachment_folder	= "./uploads/employee";
	protected $allow_type			= "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
	protected $allow_size			= "0"; // 0 for limit by default php conf (in Kb)


	function __construct()
	{
		parent::__construct();
	}

	// fix
	public function get_list_data()
	{
		$aColumns = [
			NULL,
			NULL,
			'id',
			'dt.emp_code',
			'dt.full_name',
			'dt.nick_name',
			'dt.personal_email',
			'dt.personal_phone',
			'dt.gender_name',
			'dt.date_of_birth',
			'dt.job_title_name'
		];
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*,(case when a.gender="M" then "Male" when a.gender="F" then "Female" else "" end) as gender_name 
			,o.name AS job_title_name
			from employees a
			LEFT JOIN master_job_title o ON o.id = a.job_title_id)dt';
		

		/* Paging */
		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
			$sLimit = "LIMIT ".($_GET['iDisplayStart']).", ".
			($_GET['iDisplayLength']);
		}

		/* Ordering */
		$sOrder = "";
		if(isset($_GET['iSortCol_0'])) {
			$sOrder = "ORDER BY  ";
			for ($i=0 ; $i<intval($_GET['iSortingCols']) ; $i++){
				if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
					$srcCol = $aColumns[ intval($_GET['iSortCol_'.$i])];
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sOrder .= trim($pieces[0])."
						".($_GET['sSortDir_'.$i]) .", ";
					} else {
						$sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
						".($_GET['sSortDir_'.$i]) .", ";
					}
				}
			}

			$sOrder = substr_replace($sOrder, "", -2);
			if($sOrder == "ORDER BY"){
				$sOrder = "";
			}
		}

		/* Filtering */
		$sWhere = " WHERE 1 = 1 ";
		if(isset($_GET['sSearch']) && $_GET['sSearch'] != ""){
			$sWhere .= "AND (";
			foreach ($aColumns as $c) {
				if($c !== NULL){
					$srcCol = $c;
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0])." LIKE '%".($_GET['sSearch'])."%' OR ";
					} else {
						$sWhere .= $c." LIKE '%".($_GET['sSearch'])."%' OR ";
					}
				}
			}

			$sWhere = substr_replace( $sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		for($i=0 ; $i<count($aColumns) ; $i++) {
			if(isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && isset($_GET['sSearch_'.$i]) && $_GET['sSearch_'.$i] != ''){
				if($sWhere == ""){
					$sWhere = "WHERE ";
				} else {
					$sWhere .= " AND ";
				}
				$srcString = $_GET['sSearch_'.$i];
				$findme   = '|';
				$pos = strpos($srcString, $findme);
				if ($pos !== false) {
					$srcKey = "";
					$pieces = explode($findme, trim($srcString));
					foreach ($pieces as $value) {
						if(!empty($srcKey)){
							$srcKey .= ",";
						}
						$srcKey .= "'".$value."'";
					}
					
					$srcCol = $aColumns[$i];
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0])." IN (".$srcKey.") ";
					} else {
						$sWhere .= $aColumns[$i]." IN (".$srcKey.") ";
					}
				} else {
					$srcCol = $aColumns[$i];
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0])." LIKE '%".($srcString)."%' ";
					} else {
						$sWhere .= $aColumns[$i]." LIKE '%".($srcString)."%' ";
					}
				}
			}
		}

		/* Get data to display */
		$filtered_cols = array_filter($aColumns, [$this, 'is_not_null']); // Filtering NULL value
		$sQuery = "
		SELECT  SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $filtered_cols))."
		FROM $sTable
		$sWhere
		$sOrder
		$sLimit
		";
		# echo $sQuery;exit;
		$rResult = $this->db->query($sQuery)->result();

		/* Data set length after filtering */
		$sQuery = "
			SELECT FOUND_ROWS() AS filter_total
		";
		$aResultFilterTotal = $this->db->query($sQuery)->row();
		$iFilteredTotal = $aResultFilterTotal->filter_total;

		/* Total data set length */
		$sQuery = "
			SELECT COUNT(".$sIndexColumn.") AS total
			FROM $sTable
		";
		$aResultTotal = $this->db->query($sQuery)->row();
		$iTotal = $aResultTotal->total;

		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);

		foreach($rResult as $row)
		{
			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="btn btn-xs btn-success detail-btn" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				$delete = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$delete.'
				</div>',
				$row->id,
				$row->emp_code,
				$row->full_name,
				$row->nick_name,
				$row->personal_email,
				$row->personal_phone,
				$row->gender_name,
				$row->date_of_birth,
				$row->job_title_name

			));
		}

		echo json_encode($output);
	}

	// filltering null value from array
	public function is_not_null($val){
		return !is_null($val);
	}		

	public function delete($id= "") {
		if (isset($id) && $id <> "") {
			//$this->db->trans_off(); // Disable transaction
			$this->db->trans_start(); // set "True" for query will be rolled back
			$this->db->where([$this->primary_key => $id])->delete($this->table_name);
			$this->db->trans_complete();

			return $rs = $this->db->trans_status();
		} else return null;
	}  

	// delete multi items action
	public function bulk($id= "") {
		if (is_array($id) && count($id)) {
			$err = '';
			foreach ($id as $pid) {
				//$this->db->trans_off(); // Disable transaction
				$this->db->trans_start(); // set "True" for query will be rolled back
				$this->db->where([$this->primary_key => $pid])->delete($this->table_name);
				$this->db->trans_complete();
				$deleted = $this->db->trans_status();
                if ($deleted == false) {
					if(!empty($err)) $err .= ", ";
                    $err .= $pid;
                }
			}
			
			$data = array();
			if(empty($err)){
				$data['status'] = TRUE;
			} else {
				$data['status'] = FALSE;
				$data['err'] = '<br/>ID : '.$err;
			}
			
			return $data;
		} else return null;
	}  


	// Get next number 
	public function getNextNumber() { 
		

		$cek = $this->db->query("select * from employees");
		$rs_cek = $cek->result_array();

		if(empty($rs_cek)){ 
			$num = '0001';
		}else{ 
			$cek2 = $this->db->query("select max(emp_code) as maxnum from employees");
			$rs_cek2 = $cek2->result_array();  
			$dt = $rs_cek2[0]['maxnum'];  // GDI0010
			$getnum = substr($dt,3); 
			$num = str_pad($getnum + 1, 4, 0, STR_PAD_LEFT);
			
		}

		return $num;
		
	} 


	// Upload file
	public function upload_file($id = "", $fieldname= "", $replace=FALSE, $oldfilename= "", $array=FALSE, $i=0) { 
		$data = array();
		$data['status'] = FALSE; 
		if(!empty($id) && !empty($fieldname)){ 
			// handling multiple upload (as array field)

			if($array){ 
				// Define new $_FILES array - $_FILES['file']
				$_FILES['file']['name'] = $_FILES[$fieldname]['name'];
				$_FILES['file']['type'] = $_FILES[$fieldname]['type'];
				$_FILES['file']['tmp_name'] = $_FILES[$fieldname]['tmp_name'];
				$_FILES['file']['error'] = $_FILES[$fieldname]['error'];
				$_FILES['file']['size'] = $_FILES[$fieldname]['size']; 
				// override field

			}
			// handling regular upload (as one field)
			if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
			{ 
				/*$dir = $this->attachment_folder.'/'.$id;
				if(!is_dir($dir)) {
					mkdir($dir);
				}
				if($replace){
					$this->remove_file($id, $oldfilename);
				}*/
				$config['upload_path']   = $this->attachment_folder;
				$config['allowed_types'] = $this->allow_type;
				$config['max_size'] 	 = $this->allow_size;
				
				$this->load->library('upload', $config); 
				
				if(!$this->upload->do_upload($fieldname)){  
					$err_msg = $this->upload->display_errors(); 
					$data['error_warning'] = strip_tags($err_msg);				
					$data['status'] = FALSE;
				} else {
					$fileData = $this->upload->data();
					$data['upload_file'] = $fileData['file_name'];
					$data['status'] = TRUE;
				}
			}
		}

		
		
		return $data;
	}


	public function add_data($post) { 

		$lettercode 	= ('GDI'); 
		$runningnumber 	= $this->getNextNumber(); // next count number
		$genEmpCode 	= $lettercode.$runningnumber;

		$date_of_birth 		= trim($post['date_of_birth']);
		$date_of_hire 		= trim($post['date_of_hire']);
		$date_end_prob 		= trim($post['date_end_prob']);
		$date_permanent 	= trim($post['date_permanent']);
		$date_resign_letter = trim($post['date_resign_letter']);
		$date_resign_active = trim($post['date_resign_active']);



		$upload_emp_photo = $this->upload_file('1', 'emp_photo', FALSE, '', TRUE, '');
		$emp_photo = '';
		if($upload_emp_photo['status']){
			$emp_photo = $upload_emp_photo['upload_file'];
		} else if(isset($upload_emp_photo['error_warning'])){
			echo $upload_emp_photo['error_warning']; exit;
		}

		$upload_emp_sign = $this->upload_file('1', 'emp_signature', FALSE, '', TRUE, '');
		$emp_signature = '';
		if($upload_emp_sign['status']){
			$emp_signature = $upload_emp_sign['upload_file'];
		} else if(isset($upload_emp_sign['error_warning'])){
			echo $upload_emp_sign['error_warning']; exit;
		}



		$data = [
			'emp_code' 						=> $genEmpCode,
			'full_name' 					=> trim($post['full_name']),
			'nick_name' 					=> trim($post['nick_name']),
			'personal_email' 				=> trim($post['email']),
			'personal_phone' 				=> trim($post['phone']),
			'gender' 						=> trim($post['gender']),
			'ethnic' 						=> trim($post['ethnic']),
			'nationality' 					=> trim($post['nationality']),
			//'last_education_id' 			=> trim($post['last_education']),
			'marital_status_id' 			=> trim($post['marital_status']),
			'tanggungan' 					=> trim($post['tanggungan']),
			'no_ktp' 						=> trim($post['no_ktp']),
			'sim_a' 						=> trim($post['sim_a']),
			'sim_c' 						=> trim($post['sim_c']),
			'no_npwp' 						=> trim($post['no_npwp']),
			'no_bpjs' 						=> trim($post['no_bpjs']),
			'place_of_birth' 				=> trim($post['place_of_birth']),
			'date_of_birth' 				=> date("Y-m-d", strtotime($date_of_birth)),
			'address_ktp' 					=> trim($post['address1']),
			'address_residen' 				=> trim($post['address2']),
			'postal_code_ktp' 				=> trim($post['postal_code1']),
			'postal_code_residen' 			=> trim($post['postal_code2']),
			'province_id_ktp' 				=> trim($post['province1']),
			'province_id_residen' 			=> trim($post['province2']),
			'regency_id_ktp' 				=> trim($post['regency1']),
			'regency_id_residen' 			=> trim($post['regency2']),
			'district_id_ktp' 				=> trim($post['district1']),
			'district_id_residen' 			=> trim($post['district2']),
			'village_id_ktp' 				=> trim($post['village1']),
			'village_id_residen' 			=> trim($post['village2']),
			'job_title_id' 					=> trim($post['job_title']),
			'department_id' 				=> trim($post['department']),
			'date_of_hire' 					=> date("Y-m-d", strtotime($date_of_hire)),
			'date_end_probation' 			=> date("Y-m-d", strtotime($date_end_prob)),
			'date_permanent' 				=> date("Y-m-d", strtotime($date_permanent)),
			'employment_status_id' 			=> trim($post['emp_status']),
			'shift_type' 					=> trim($post['shift_type']),
			'work_location' 				=> trim($post['work_loc']),
			'direct_id' 					=> trim($post['direct']),
			'indirect_id' 					=> trim($post['indirect']),
			'emergency_contact_name' 		=> trim($post['emergency_name']),
			'emergency_contact_phone' 		=> trim($post['emergency_phone']),
			'emergency_contact_email' 		=> trim($post['emergency_email']),
			'emergency_contact_relation' 	=> trim($post['emergency_relation']),
			'bank_name' 					=> trim($post['bank_name']),
			'bank_address' 					=> trim($post['bank_address']),
			'bank_acc_name' 				=> trim($post['bank_acc_name']),
			'bank_acc_no' 					=> trim($post['bank_acc_no']),
			'date_resign_letter' 			=> date("Y-m-d", strtotime($date_resign_letter)),
			'date_resign_active' 			=> date("Y-m-d", strtotime($date_resign_active)),
			'resign_category' 				=> trim($post['resign_category']),
			'resign_reason' 				=> trim($post['resign_reason']),
			'resign_exit_interview_feedback' 	=> trim($post['resign_exit_feedback']),
			'emp_photo' 					=> $emp_photo,
			'emp_signature' 				=> $emp_signature,
			'created_at' 					=> date("Y-m-d H:i:s"),
			'company_id' 					=> trim($post['company']),
			'division_id' 					=> trim($post['division']),
			'branch_id' 					=> trim($post['branch']),
			'section_id' 					=> trim($post['section']),
			'gender' 						=> trim($post['gender'])
		];

		$rs = $this->db->insert($this->table_name, $data);
		$lastId = $this->db->insert_id();

		if($rs){

			// add ke table education detail //
			if(isset($post['education'])){
				$item_num = count($post['education']); // cek sum
				$item_len_min = min(array_keys($post['education'])); // cek min key index
				$item_len = max(array_keys($post['education'])); // cek max key index
			} else {
				$item_num = 0;
			}

			if($item_num>0){
				for($i=$item_len_min;$i<=$item_len;$i++) 
				{
					if(isset($post['education'][$i])){
						$itemData = [
							'employee_id' 	=> $lastId,
							'education_id' 	=> trim($post['education'][$i]),
							'institution' 	=> trim($post['institution'][$i]),
							'city' 			=> trim($post['city'][$i]),
							'year' 			=> trim($post['year'][$i])
						];

						$this->db->insert('education_detail', $itemData);
					}
				}
			}
			// end add ke table education detail //

			// add ke table training detail //
			if(isset($post['training_name'])){
				$item_num_training = count($post['training_name']); // cek sum
				$item_len_min_training = min(array_keys($post['training_name'])); // cek min key index
				$item_len_training = max(array_keys($post['training_name'])); // cek max key index
			} else {
				$item_num_training = 0;
			}

			if($item_num_training>0){
				for($i=$item_len_min_training;$i<=$item_len_training;$i++) 
				{
					if(isset($post['training_name'][$i])){
						$itemData_training = [
							'employee_id' 	=> $lastId,
							'training_name' => trim($post['training_name'][$i]),
							'city' 			=> trim($post['city_training'][$i]),
							'year' 			=> trim($post['year_training'][$i])
						];

						$this->db->insert('training_detail', $itemData_training);
					}
				}
			}
			// end add ke table training detail //

			// add ke table organisasi detail //
			if(isset($post['organization_name'])){
				$item_num_org = count($post['organization_name']); // cek sum
				$item_len_min_org = min(array_keys($post['organization_name'])); // cek min key index
				$item_len_org = max(array_keys($post['organization_name'])); // cek max key index
			} else {
				$item_num_org = 0;
			}

			if($item_num_org>0){
				for($i=$item_len_min_org;$i<=$item_len_org;$i++) 
				{
					if(isset($post['organization_name'][$i])){
						$itemData_org = [
							'employee_id' 		=> $lastId,
							'organization_name' => trim($post['organization_name'][$i]),
							'institution' 		=> trim($post['institution_org'][$i]),
							'position' 			=> trim($post['position'][$i]),
							'city' 				=> trim($post['city_org'][$i]),
							'year' 				=> trim($post['year_org'][$i])
						];

						$this->db->insert('organization_detail', $itemData_org);
					}
				}
			}
			// end add ke table organisasi detail //

			// add ke table work experience detail //
			if(isset($post['company_workexp'])){
				$item_num_workexp = count($post['company_workexp']); // cek sum
				$item_len_min_workexp = min(array_keys($post['company_workexp'])); // cek min key index
				$item_len_workexp = max(array_keys($post['company_workexp'])); // cek max key index
			} else {
				$item_num_workexp = 0;
			}

			if($item_num_workexp>0){
				for($i=$item_len_min_workexp;$i<=$item_len_workexp;$i++) 
				{
					if(isset($post['company_workexp'][$i])){
						$itemData_workexp = [
							'employee_id' 		=> $lastId,
							'company' 			=> trim($post['company_workexp'][$i]),
							'position' 			=> trim($post['position_workexp'][$i]),
							'city' 				=> trim($post['city_workexp'][$i]),
							'year' 				=> trim($post['year_workexp'][$i])
						];

						$this->db->insert('work_experience_detail', $itemData_workexp);
					}
				}
			}
			// end add ke table work experience detail //




			// add ke table user //
			$pwd = '112233';
			$password = md5($pwd);

			$name = $post['full_name'];
			$username = strtolower($name);

			if ($username == trim($username) && strpos($username, ' ') !== false) {
			    $username = str_replace(" ","_",$username);
			}
			

			$data2 = [
				'name' 			=> trim($post['full_name']),
				'email' 		=> trim($post['email']),
				'username'		=> $username,
				'passwd' 		=> $password,
				'id_karyawan'	=> $lastId,
				'id_groups' 	=> 3, //user
				'base_menu'		=> 'role',
				'id_branch'		=> trim($post['branch']),
				'isaktif' 		=> 2,
				'date_insert' 	=> date("Y-m-d H:i:s")
			];
			$this->db->insert('user', $data2);
			// end add ke table user //
		}



		return $rs;
	}  

	public function edit_data($post) { 



		if(!empty($post['id'])){ 
			$date_of_birth 		= trim($post['date_of_birth']);
			$date_of_hire 		= trim($post['date_of_hire']);
			$date_end_prob 		= trim($post['date_end_prob']);
			$date_permanent 	= trim($post['date_permanent']);
			$date_resign_letter = trim($post['date_resign_letter']);
			$date_resign_active = trim($post['date_resign_active']);
			$hdnempsign 		= trim($post['hdnempsign']);
			$hdnempphoto 		= trim($post['hdnempphoto']);
			

			$upload_emp_photo = $this->upload_file('1', 'emp_photo', FALSE, '', TRUE, '');
			$emp_photo = '';
			if($upload_emp_photo['status']){
				$emp_photo = $upload_emp_photo['upload_file'];
			} else if(isset($upload_emp_photo['error_warning'])){
				echo $upload_emp_photo['error_warning']; exit;
			}

			$upload_emp_sign = $this->upload_file('1', 'emp_signature', FALSE, '', TRUE, '');
			$emp_signature = '';
			if($upload_emp_sign['status']){
				$emp_signature = $upload_emp_sign['upload_file'];
			} else if(isset($upload_emp_sign['error_warning'])){
				echo $upload_emp_sign['error_warning']; exit;
			}

			if($emp_photo == '' && $hdnempphoto != ''){
				$emp_photo = $hdnempphoto;
			}
			if($emp_signature == '' && $hdnempsign != ''){
				$emp_signature = $hdnempsign;
			}


			

			$data = [
				'full_name' 					=> trim($post['full_name']),
				'nick_name' 					=> trim($post['nick_name']),
				'personal_email' 				=> trim($post['email']),
				'personal_phone' 				=> trim($post['phone']),
				'gender' 						=> trim($post['gender']),
				'ethnic' 						=> trim($post['ethnic']),
				'nationality' 					=> trim($post['nationality']),
				'marital_status_id' 			=> trim($post['marital_status']),
				'tanggungan' 					=> trim($post['tanggungan']),
				'no_ktp' 						=> trim($post['no_ktp']),
				'sim_a' 						=> trim($post['sim_a']),
				'sim_c' 						=> trim($post['sim_c']),
				'no_npwp' 						=> trim($post['no_npwp']),
				'no_bpjs' 						=> trim($post['no_bpjs']),
				'place_of_birth' 				=> trim($post['place_of_birth']),
				'date_of_birth' 				=> date("Y-m-d", strtotime($date_of_birth)),
				'address_ktp' 					=> trim($post['address1']),
				'address_residen' 				=> trim($post['address2']),
				'postal_code_ktp' 				=> trim($post['postal_code1']),
				'postal_code_residen' 			=> trim($post['postal_code2']),
				'province_id_ktp' 				=> trim($post['province1']),
				'province_id_residen' 			=> trim($post['province2']),
				'regency_id_ktp' 				=> trim($post['regency1']),
				'regency_id_residen' 			=> trim($post['regency2']),
				'district_id_ktp' 				=> trim($post['district1']),
				'district_id_residen' 			=> trim($post['district2']),
				'village_id_ktp' 				=> trim($post['village1']),
				'village_id_residen' 			=> trim($post['village2']),
				'job_title_id' 					=> trim($post['job_title']),
				'department_id' 				=> trim($post['department']),
				'date_of_hire' 					=> date("Y-m-d", strtotime($date_of_hire)),
				'date_end_probation' 			=> date("Y-m-d", strtotime($date_end_prob)),
				'date_permanent' 				=> date("Y-m-d", strtotime($date_permanent)),
				'employment_status_id' 			=> trim($post['emp_status']),
				'shift_type' 					=> trim($post['shift_type']),
				'work_location' 				=> trim($post['work_loc']),
				'direct_id' 					=> trim($post['direct']),
				'indirect_id' 					=> trim($post['indirect']),
				'emergency_contact_name' 		=> trim($post['emergency_name']),
				'emergency_contact_phone' 		=> trim($post['emergency_phone']),
				'emergency_contact_email' 		=> trim($post['emergency_email']),
				'emergency_contact_relation' 	=> trim($post['emergency_relation']),
				'bank_name' 					=> trim($post['bank_name']),
				'bank_address' 					=> trim($post['bank_address']),
				'bank_acc_name' 				=> trim($post['bank_acc_name']),
				'bank_acc_no' 					=> trim($post['bank_acc_no']),
				'date_resign_letter' 			=> date("Y-m-d", strtotime($date_resign_letter)),
				'date_resign_active' 			=> date("Y-m-d", strtotime($date_resign_active)),
				'resign_category' 				=> trim($post['resign_category']),
				'resign_reason' 				=> trim($post['resign_reason']),
				'resign_exit_interview_feedback' 	=> trim($post['resign_exit_feedback']),
				'emp_photo' 					=> $emp_photo,
				'emp_signature' 				=> $emp_signature,
				'updated_at' 					=> date("Y-m-d H:i:s"),
				'company_id' 					=> trim($post['company']),
				'division_id' 					=> trim($post['division']),
				'branch_id' 					=> trim($post['branch']),
				'section_id' 					=> trim($post['section']),
				'gender' 						=> trim($post['gender'])
			];

			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

			if($rs){
				// add education detail //
				if(isset($post['education'])){
					$item_num = count($post['education']); // cek sum
					$item_len_min = min(array_keys($post['education'])); // cek min key index
					$item_len = max(array_keys($post['education'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						$hdnid = trim($post['hdnid'][$i]);

						if(!empty($hdnid)){ //update

							if(isset($post['education'][$i])){
								$itemData = [
									'education_id' 	=> trim($post['education'][$i]),
									'institution' 	=> trim($post['institution'][$i]),
									'city' 			=> trim($post['city'][$i]),
									'year' 			=> trim($post['year'][$i])
								];

								$this->db->update("education_detail", $itemData, "id = '".$hdnid."'");
							}

						}else{ //insert

							if(isset($post['education'][$i])){
								$itemData = [
									'employee_id' 	=> $post['id'],
									'education_id' 	=> trim($post['education'][$i]),
									'institution' 	=> trim($post['institution'][$i]),
									'city' 			=> trim($post['city'][$i]),
									'year' 			=> trim($post['year'][$i])
								];

								$this->db->insert('education_detail', $itemData);
							}

						}
						
					}
				}

				// end add education detail //

				// add training detail //
				if(isset($post['training_name'])){
					$item_num_training = count($post['training_name']); // cek sum
					$item_len_min_training = min(array_keys($post['training_name'])); // cek min key index
					$item_len_training = max(array_keys($post['training_name'])); // cek max key index
				} else {
					$item_num_training = 0;
				}

				if($item_num_training>0){
					for($i=$item_len_min_training;$i<=$item_len_training;$i++) 
					{
						$hdnid_training = trim($post['hdnid_training'][$i]);

						if(!empty($hdnid_training)){ //update

							if(isset($post['training_name'][$i])){
								$itemData_training = [
									'training_name' => trim($post['training_name'][$i]),
									'city' 			=> trim($post['city_training'][$i]),
									'year' 			=> trim($post['year_training'][$i])
								];

								$this->db->update("training_detail", $itemData_training, "id = '".$hdnid_training."'");
							}

						}else{ //insert

							if(isset($post['training_name'][$i])){
								$itemData_training = [
									'employee_id' 	=> $post['id'],
									'training_name' => trim($post['training_name'][$i]),
									'city' 			=> trim($post['city_training'][$i]),
									'year' 			=> trim($post['year_training'][$i])
								];

								$this->db->insert('training_detail', $itemData_training);
							}

						}
						
					}
				}

				// end add education detail //



				// add organisasi detail //
				if(isset($post['organization_name'])){
					$item_num_org = count($post['organization_name']); // cek sum
					$item_len_min_org = min(array_keys($post['organization_name'])); // cek min key index
					$item_len_org = max(array_keys($post['organization_name'])); // cek max key index
				} else {
					$item_num_org = 0;
				}

				if($item_num_org>0){
					for($i=$item_len_min_org;$i<=$item_len_org;$i++) 
					{
						$hdnid_org = trim($post['hdnid_org'][$i]);

						if(!empty($hdnid_org)){ //update

							if(isset($post['organization_name'][$i])){
								$itemData_org = [
									'organization_name' => trim($post['organization_name'][$i]),
									'institution' 		=> trim($post['institution_org'][$i]),
									'position' 			=> trim($post['position'][$i]),
									'city' 				=> trim($post['city_org'][$i]),
									'year' 				=> trim($post['year_org'][$i])
								];

								$this->db->update("organization_detail", $itemData_org, "id = '".$hdnid_org."'");
							}

						}else{ //insert

							if(isset($post['organization_name'][$i])){
								$itemData_org = [
									'employee_id' 	=> $post['id'],
									'organization_name' => trim($post['organization_name'][$i]),
									'institution' 		=> trim($post['institution_org'][$i]),
									'position' 			=> trim($post['position'][$i]),
									'city' 				=> trim($post['city_org'][$i]),
									'year' 				=> trim($post['year_org'][$i])
								];

								$this->db->insert('organization_detail', $itemData_org);
							}

						}
						
					}
				}

				// end add education detail //


				// add work experience detail //
				if(isset($post['company_workexp'])){
					$item_num_workexp = count($post['company_workexp']); // cek sum
					$item_len_min_workexp = min(array_keys($post['company_workexp'])); // cek min key index
					$item_len_workexp = max(array_keys($post['company_workexp'])); // cek max key index
				} else {
					$item_num_workexp = 0;
				}

				if($item_num_workexp>0){
					for($i=$item_len_min_workexp;$i<=$item_len_workexp;$i++) 
					{
						$hdnid_workexp = trim($post['hdnid_workexp'][$i]);

						if(!empty($hdnid_workexp)){ //update

							if(isset($post['company_workexp'][$i])){
								$itemData_workexp = [
									'company'	=> trim($post['company_workexp'][$i]),
									'position' 	=> trim($post['position_workexp'][$i]),
									'city' 		=> trim($post['city_workexp'][$i]),
									'year' 		=> trim($post['year_workexp'][$i])
								];

								$this->db->update("work_experience_detail", $itemData_workexp, "id = '".$hdnid_workexp."'");
							}

						}else{ //insert

							if(isset($post['company_workexp'][$i])){
								$itemData_workexp = [
									'employee_id' 	=> $post['id'],
									'company'	=> trim($post['company_workexp'][$i]),
									'position' 	=> trim($post['position_workexp'][$i]),
									'city' 		=> trim($post['city_workexp'][$i]),
									'year' 		=> trim($post['year_workexp'][$i])
								];

								$this->db->insert('work_experience_detail', $itemData_workexp);
							}

						}
						
					}
				}

				// end add education detail //


			}


			return $rs;
		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(SELECT 
					    a.*,
					    b.name AS company_name,
					    c.name AS division_name,
					    d.name AS section_name,
					    f.name AS regency_name_ktp,
                        f2.name AS regency_name_residen,
					    g.name AS village_name_ktp,
                        g2.name AS village_name_residen,
					    h.name AS department_name,
					    i.name AS emp_status_name,
					    j.full_name AS indirect_name,
					    k.name AS branch_name,
					    l.name AS marital_status_name,
					    m.name AS province_name_ktp,
						m2.name AS province_name_residen,
					    n.name AS district_name_ktp,
                        n2.name AS district_name_residen,
					    o.name AS job_title_name,
					    p.full_name AS direct_name
					FROM
					    employees a
					        LEFT JOIN
					    companies b ON b.id = a.company_id
					        LEFT JOIN
					    divisions c ON c.id = a.division_id
					        LEFT JOIN
					    sections d ON d.id = a.section_id
					        LEFT JOIN
					    regencies f ON f.id = a.regency_id_ktp
                        LEFT JOIN
					    regencies f2 ON f2.id = a.regency_id_residen
					        LEFT JOIN
					    villages g ON g.id = a.village_id_ktp
                        LEFT JOIN
					    villages g2 ON g2.id = a.village_id_residen
					        LEFT JOIN
					    departments h ON h.id = a.department_id
					        LEFT JOIN
					    master_emp_status i ON i.id = a.employment_status_id
					        LEFT JOIN
					    employees j ON j.id = a.indirect_id
					        LEFT JOIN
					    branches k ON k.id = a.branch_id
					        LEFT JOIN
					    master_marital_status l ON l.id = a.marital_status_id
					        LEFT JOIN
					    provinces m ON m.id = a.province_id_ktp
                        LEFT JOIN
					    provinces m2 ON m2.id = a.province_id_residen
					        LEFT JOIN
					    districts n ON n.id = a.district_id_ktp
                        LEFT JOIN
					    districts n2 ON n2.id = a.district_id_residen
					        LEFT JOIN
					    master_job_title o ON o.id = a.job_title_id
					        LEFT JOIN
					    employees p ON p.id = a.direct_id

			)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		
		
		return $rs;
	} 

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'code' 	=> $v["B"],
				'name' 	=> $v["C"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$sql = "select id, code, name from mother_vessel
	   		ORDER BY id ASC
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}

	public function getNewExpensesRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getExpensesRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			$msEdu = $this->db->query("select * from master_education")->result(); 
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value=""/></td>';
			$data 	.= '<td>'.$this->return_build_chosenme($msEdu,'','','','education['.$row.']','education','education','','id','name','','','',' data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','institution['.$row.']','','institution','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','city['.$row.']','','city','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','year['.$row.']','','year','text-align: right;','data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 

	// Generate expenses item rows for edit & view
	public function getExpensesRows($id,$view,$print=FALSE){ 
		
		$dt = ''; 
		
		$rs = $this->db->query("select a.*, b.name as education_name from education_detail a left join master_education b on b.id = a.education_id where a.employee_id = '".$id."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			/*if($view){
				$arrSat = json_decode(json_encode($msObat), true);
				$arrS = [];
				foreach($arrSat as $ai){
					$arrS[$ai['id']] = $ai;
				}
			}*/
			foreach ($rd as $f){
				$no = $row+1;
				$msEdu = $this->db->query("select * from master_education")->result(); 

				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';
					$dt .= '<td>'.$this->return_build_chosenme($msEdu,'',isset($f->education_id)?$f->education_id:1,'','education['.$row.']','education','education','','id','name','','','',' data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->institution,'institution['.$row.']','','institution','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->city,'city['.$row.']','','city','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->year,'year['.$row.']','','year','text-align: right;','data-id="'.$row.'" ').'</td>';
					
					$dt .= '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete" onclick="del(\''.$row.'\',\''.$f->id.'\')"></td>';
					$dt .= '</tr>';
				} else { 
					
					if($print){
						if($row == ($rs_num-1)){
							$dt .= '<tr class="item last">';
						} else {
							$dt .= '<tr class="item">';
						}
					} else {
						$dt .= '<tr>';
					} 
					$qty=$f->qty;
					if($f->qty==0){
						$qty='';
					}
					$dt .= '<td>'.$no.'</td>';
					$dt .= '<td>'.$f->education_name.'</td>';
					$dt .= '<td>'.$f->institution.'</td>';
					$dt .= '<td>'.$f->city.'</td>';
					$dt .= '<td>'.$f->year.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


	public function getNewTrainingRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getTrainingRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid_training'.$row.'" name="hdnid_training['.$row.']" value=""/></td>';
			$data 	.= '<td>'.$this->return_build_txt('','training_name['.$row.']','','training_name','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','city_training['.$row.']','','city_training','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','year_training['.$row.']','','year_training','text-align: right;','data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del-training(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 

	// Generate expenses item rows for edit & view
	public function getTrainingRows($id,$view,$print=FALSE){ 
		
		$dt = ''; 
		
		$rs = $this->db->query("select * from training_detail where employee_id = '".$id."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			/*if($view){
				$arrSat = json_decode(json_encode($msObat), true);
				$arrS = [];
				foreach($arrSat as $ai){
					$arrS[$ai['id']] = $ai;
				}
			}*/
			foreach ($rd as $f){
				$no = $row+1;
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid_training'.$row.'" name="hdnid_training['.$row.']" value="'.$f->id.'"/></td>';
					$dt .= '<td>'.$this->return_build_txt($f->training_name,'training_name['.$row.']','','training_name','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->city,'city_training['.$row.']','','city_training','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->year,'year_training['.$row.']','','year_training','text-align: right;','data-id="'.$row.'" ').'</td>';
					
					$dt .= '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete" onclick="del-training(\''.$row.'\',\''.$f->id.'\')"></td>';
					$dt .= '</tr>';
				} else { 
					
					if($print){
						if($row == ($rs_num-1)){
							$dt .= '<tr class="item last">';
						} else {
							$dt .= '<tr class="item">';
						}
					} else {
						$dt .= '<tr>';
					} 
					$qty=$f->qty;
					if($f->qty==0){
						$qty='';
					}
					$dt .= '<td>'.$no.'</td>';
					$dt .= '<td>'.$f->training_name.'</td>';
					$dt .= '<td>'.$f->city.'</td>';
					$dt .= '<td>'.$f->year.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


	public function getNewOrgRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getOrgRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid_org'.$row.'" name="hdnid_org['.$row.']" value=""/></td>';
			$data 	.= '<td>'.$this->return_build_txt('','organization_name['.$row.']','','organization_name','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','institution_org['.$row.']','','institution_org','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','position['.$row.']','','position','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','city_org['.$row.']','','city_org','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','year_org['.$row.']','','year_org','text-align: right;','data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del-org(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 

	// Generate expenses item rows for edit & view
	public function getOrgRows($id,$view,$print=FALSE){ 
		
		$dt = ''; 
		
		$rs = $this->db->query("select * from organization_detail where employee_id = '".$id."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			/*if($view){
				$arrSat = json_decode(json_encode($msObat), true);
				$arrS = [];
				foreach($arrSat as $ai){
					$arrS[$ai['id']] = $ai;
				}
			}*/
			foreach ($rd as $f){
				$no = $row+1;
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid_org'.$row.'" name="hdnid_org['.$row.']" value="'.$f->id.'"/></td>';
					$dt .= '<td>'.$this->return_build_txt($f->organization_name,'organization_name['.$row.']','','organization_name','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->institution,'institution_org['.$row.']','','institution_org','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->position,'position_org['.$row.']','','position_org','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->city,'city_org['.$row.']','','city_org','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->year,'year_org['.$row.']','','year_org','text-align: right;','data-id="'.$row.'" ').'</td>';
					
					$dt .= '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete" onclick="del-org(\''.$row.'\',\''.$f->id.'\')"></td>';
					$dt .= '</tr>';
				} else { 
					
					if($print){
						if($row == ($rs_num-1)){
							$dt .= '<tr class="item last">';
						} else {
							$dt .= '<tr class="item">';
						}
					} else {
						$dt .= '<tr>';
					} 
					$qty=$f->qty;
					if($f->qty==0){
						$qty='';
					}
					$dt .= '<td>'.$no.'</td>';
					$dt .= '<td>'.$f->organization_name.'</td>';
					$dt .= '<td>'.$f->institution.'</td>';
					$dt .= '<td>'.$f->position.'</td>';
					$dt .= '<td>'.$f->city.'</td>';
					$dt .= '<td>'.$f->year.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


	public function getNewWorkexpRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getWorkexpRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid_workexp'.$row.'" name="hdnid_workexp['.$row.']" value=""/></td>';
			$data 	.= '<td>'.$this->return_build_txt('','company_workexp['.$row.']','','company_workexp','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','position_workexp['.$row.']','','position_workexp','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','city_workexp['.$row.']','','city_workexp','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','year_workexp['.$row.']','','year_workexp','text-align: right;','data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del-workexp(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 

	// Generate expenses item rows for edit & view
	public function getWorkexpRows($id,$view,$print=FALSE){ 
		
		$dt = ''; 
		
		$rs = $this->db->query("select * from work_experience_detail where employee_id = '".$id."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			/*if($view){
				$arrSat = json_decode(json_encode($msObat), true);
				$arrS = [];
				foreach($arrSat as $ai){
					$arrS[$ai['id']] = $ai;
				}
			}*/
			foreach ($rd as $f){
				$no = $row+1;
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid_workexp'.$row.'" name="hdnid_workexp['.$row.']" value="'.$f->id.'"/></td>';
					$dt .= '<td>'.$this->return_build_txt($f->company,'company_workexp['.$row.']','','company_workexp','text-align: right;','data-id="'.$row.'" ').'</td>';
	
					$dt .= '<td>'.$this->return_build_txt($f->position,'position_workexp['.$row.']','','position_workexp','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->city,'city_workexp['.$row.']','','city_workexp','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->year,'year_workexp['.$row.']','','year_workexp','text-align: right;','data-id="'.$row.'" ').'</td>';
					
					$dt .= '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete" onclick="del-workexp(\''.$row.'\',\''.$f->id.'\')"></td>';
					$dt .= '</tr>';
				} else { 
					
					if($print){
						if($row == ($rs_num-1)){
							$dt .= '<tr class="item last">';
						} else {
							$dt .= '<tr class="item">';
						}
					} else {
						$dt .= '<tr>';
					} 
					$qty=$f->qty;
					if($f->qty==0){
						$qty='';
					}
					$dt .= '<td>'.$no.'</td>';
					$dt .= '<td>'.$f->company.'</td>';
					$dt .= '<td>'.$f->position.'</td>';
					$dt .= '<td>'.$f->city.'</td>';
					$dt .= '<td>'.$f->year.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}

	public function getDataRegency($province){ 

		$rs = $this->db->query("select * from regencies where province_id = '".$province."' order by name asc")->result(); 

		$data['msregency'] = $rs;


		return $data;

	}

	public function getDataDistrict($province,$regency){ 

		$rs = $this->db->query("select * from districts where province_id = '".$province."' and regency_id = '".$regency."' order by name asc")->result(); 

		$data['msdistrict'] = $rs;


		return $data;

	}


	public function getDataVillage($province,$regency,$district){ 

		$rs = $this->db->query("select * from villages where province_id = '".$province."' and regency_id = '".$regency."' and district_id = '".$district."' order by name asc")->result(); 

		$data['msvillage'] = $rs;


		return $data;

	}


}
