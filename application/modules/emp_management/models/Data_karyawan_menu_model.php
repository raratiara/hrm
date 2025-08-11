<?php
defined('BASEPATH') OR exit('No direct script access allowed');

				
class Data_karyawan_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "emp_management/data_karyawan_menu";
 	protected $table_name 				= _PREFIX_TABLE."employees";
 	protected $primary_key 				= "id";

 	/* upload */
 	/*protected $attachment_folder	= "./uploads/employee";*/
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
			'dt.job_title_name',
			'dt.status_name'
		];
		
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1 && $getdata[0]->id_groups != 4){ //bukan super user && bukan HR admin
			$whr=' where a.id = "'.$karyawan_id.'" or a.direct_id = "'.$karyawan_id.'" ';
		}

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*,(case when a.gender="M" then "Male" when a.gender="F" then "Female" else "" end) as gender_name, o.name AS job_title_name, if(a.status_id=1,"Active","Not Active") as status_name
			from employees a LEFT JOIN master_job_title o ON o.id = a.job_title_id
			'.$whr.' )dt';
		

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
				
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #343851; border-color: #343851;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';


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
					$row->job_title_name,
					$row->status_name

				));
			}else{
				array_push($output["aaData"],array(
					'<div class="action-buttons">
						'.$detail.'
						'.$edit.'
					</div>',
					$row->id,
					$row->emp_code,
					$row->full_name,
					$row->nick_name,
					$row->personal_email,
					$row->personal_phone,
					$row->gender_name,
					$row->date_of_birth,
					$row->job_title_name,
					$row->status_name

				));
			}

			/*array_push($output["aaData"],array(
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
				$row->job_title_name,
				$row->status_name

			));*/
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
	public function getNextNumber($code) { 
		

		$cek = $this->db->query("select * from employees WHERE LEFT(emp_code, 7) = '".$code."'");
		$rs_cek = $cek->result_array();

		if(empty($rs_cek)){ 
			$num = '0001';
		}else{ 
			$cek2 = $this->db->query("select max(emp_code) as maxnum from employees WHERE LEFT(emp_code, 7) = '".$code."' ");
			$rs_cek2 = $cek2->result_array();  
			$dt = $rs_cek2[0]['maxnum']; 
			$getnum = substr($dt,7); 
			$num = str_pad($getnum + 1, 4, 0, STR_PAD_LEFT);
			
		}

		return $num;
		
	} 


	// Upload file
	public function upload_file($attachment_folder, $id = "", $fieldname= "", $replace=FALSE, $oldfilename= "", $array=FALSE, $i=0) { 
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
				/*$config['upload_path']   = $this->attachment_folder;*/
				$config['upload_path']   = $attachment_folder;
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


	public function generate_jatah_cuti_karyawan_baru($employee_id, $period_start){
		/*$employee_id 	= $_GET['empid'];
		$period_start 	= $_GET['datestart'];*/

		if(!empty($employee_id) && !empty($period_start)){
			$rs = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$employee_id."' ")->result(); 

			if(empty($rs)){
				$period_end = date('Y-m-d', strtotime('+1 year', strtotime($period_start)) );
				$data = [
						'employee_id' 	=> $employee_id,
						'period_start' 	=> $period_start,
						'period_end' 	=> $period_end,
						'sisa_cuti' 	=> 12,
						'status' 		=> 1,
						'created_date'	=> date("Y-m-d H:i:s")
					];

				$exec = $this->db->insert('total_cuti_karyawan', $data);
				//echo 'Sukses Generate [Employee ID: '.$employee_id.']'; die();
			}
			/*else{
				echo 'Gagal Generate'; die();
			}*/
		}
		/*else{
			echo 'Gagal Generate. Data sudah ada'; die();
		}*/

	}


	public function add_data($post) { 
		/*error_reporting(E_ALL);
		ini_set('display_errors', 1);*/


		$date_of_birth 		= trim($post['date_of_birth'] ?? '');
		$date_of_hire 		= trim($post['date_of_hire'] ?? '');
		$date_end_prob 		= trim($post['date_end_prob'] ?? '');
		$date_permanent 	= trim($post['date_permanent'] ?? '');
		$date_resign_letter = trim($post['date_resign_letter'] ?? '');
		$date_resign_active = trim($post['date_resign_active'] ?? '');

		$dateofHired = date("Y-m-d", strtotime($date_of_hire));

		if($post['company'] == ''){
			echo "Please fill the Company"; die();
		}else if(!isset($post['shift_type'])){
			echo "Please fill the Shift Type"; die();
		}else if(!isset($post['status'])){
			echo "Please choose Status"; die();
		}else{
			
			//NBI[2DIGITTAHUNBLN][4DIGITNOURUT]

			if($dateofHired != ''){
    			$YMdateofHired = date('ym', strtotime($dateofHired));
    		}else{
    			$yearcode = date("y");
				$monthcode = date("m");
				$YMdateofHired = $yearcode.$monthcode;
    		}

			if($post['company'] == '2'){ //NBID
				$lettercode 	= ('NBI'); 
			}else if ($post['company'] == '1'){
				$lettercode 	= ('GDI'); 
			}else{ //selain 1 atau 2, value tidak diketahui
				$lettercode 	= ('NNN'); 
			}
			
			$code = $lettercode.$YMdateofHired; 
			$runningnumber 	= $this->getNextNumber($code); // next count number
			$genEmpCode 	= $lettercode.$YMdateofHired.$runningnumber;



			$upload_dir = './uploads/employee/'.$genEmpCode.'/'; // nama folder
			// Cek apakah folder sudah ada
			if (!is_dir($upload_dir)) {
			    // Jika belum ada, buat folder
			    mkdir($upload_dir, 0755, true); // 0755 = permission, true = recursive
			}



			$upload_emp_photo = $this->upload_file($upload_dir, '1', 'emp_photo', FALSE, '', TRUE, '');
			$emp_photo = '';
			if($upload_emp_photo['status']){
				$emp_photo = $upload_emp_photo['upload_file'];
			} else if(isset($upload_emp_photo['error_warning'])){
				echo $upload_emp_photo['error_warning']; exit;
			}

			$upload_emp_sign = $this->upload_file($upload_dir, '1', 'emp_signature', FALSE, '', TRUE, '');
			$emp_signature = '';
			if($upload_emp_sign['status']){
				$emp_signature = $upload_emp_sign['upload_file'];
			} else if(isset($upload_emp_sign['error_warning'])){
				echo $upload_emp_sign['error_warning']; exit;
			}

			$upload_foto_ktp = $this->upload_file($upload_dir, '1', 'foto_ktp', FALSE, '', TRUE, '');
			$foto_ktp = '';
			if($upload_foto_ktp['status']){
				$foto_ktp = $upload_foto_ktp['upload_file'];
			} else if(isset($upload_foto_ktp['error_warning'])){
				echo $upload_foto_ktp['error_warning']; exit;
			}

			$upload_foto_npwp = $this->upload_file($upload_dir, '1', 'foto_npwp', FALSE, '', TRUE, '');
			$foto_npwp = '';
			if($upload_foto_npwp['status']){
				$foto_npwp = $upload_foto_npwp['upload_file'];
			} else if(isset($upload_foto_npwp['error_warning'])){
				echo $upload_foto_npwp['error_warning']; exit;
			}

			$upload_foto_bpjs = $this->upload_file($upload_dir, '1', 'foto_bpjs', FALSE, '', TRUE, '');
			$foto_bpjs = '';
			if($upload_foto_bpjs['status']){
				$foto_bpjs = $upload_foto_bpjs['upload_file'];
			} else if(isset($upload_foto_bpjs['error_warning'])){
				echo $upload_foto_bpjs['error_warning']; exit;
			}

			$upload_foto_sima = $this->upload_file($upload_dir, '1', 'foto_sima', FALSE, '', TRUE, '');
			$foto_sima = '';
			if($upload_foto_sima['status']){
				$foto_sima = $upload_foto_sima['upload_file'];
			} else if(isset($upload_foto_sima['error_warning'])){
				echo $upload_foto_sima['error_warning']; exit;
			}

			$upload_foto_simc = $this->upload_file($upload_dir, '1', 'foto_simc', FALSE, '', TRUE, '');
			$foto_simc = '';
			if($upload_foto_simc['status']){
				$foto_simc = $upload_foto_simc['upload_file'];
			} else if(isset($upload_foto_simc['error_warning'])){
				echo $upload_foto_simc['error_warning']; exit;
			}



			$data = [
				'emp_code' 						=> $genEmpCode,
				'full_name' 					=> trim($post['full_name'] ?? ''),
				'nick_name' 					=> trim($post['nick_name'] ?? ''),
				'personal_email' 				=> trim($post['email'] ?? ''),
				'personal_phone' 				=> trim($post['phone'] ?? ''),
				'gender' 						=> trim($post['gender'] ?? ''),
				'ethnic' 						=> trim($post['ethnic'] ?? ''),
				'nationality' 					=> trim($post['nationality'] ?? ''),
				//'last_education_id' 			=> trim($post['last_education']),
				'marital_status_id' 			=> trim($post['marital_status'] ?? ''),
				/*'tanggungan' 					=> trim($post['tanggungan']),*/
				'no_ktp' 						=> trim($post['no_ktp'] ?? ''),
				'sim_a' 						=> trim($post['sim_a'] ?? ''),
				'sim_c' 						=> trim($post['sim_c'] ?? ''),
				'no_npwp' 						=> trim($post['no_npwp'] ?? ''),
				'no_bpjs' 						=> trim($post['no_bpjs'] ?? ''),
				'place_of_birth' 				=> trim($post['place_of_birth'] ?? ''),
				'date_of_birth' 				=> date("Y-m-d", strtotime($date_of_birth)),
				'address_ktp' 					=> trim($post['address1'] ?? ''),
				'address_residen' 				=> trim($post['address2'] ?? ''),
				'postal_code_ktp' 				=> trim($post['postal_code1'] ?? ''),
				'postal_code_residen' 			=> trim($post['postal_code2'] ?? ''),
				'province_id_ktp' 				=> trim($post['province1'] ?? ''),
				'province_id_residen' 			=> trim($post['province2'] ?? ''),
				'regency_id_ktp' 				=> trim($post['regency1'] ?? ''),
				'regency_id_residen' 			=> trim($post['regency2'] ?? ''),
				'district_id_ktp' 				=> trim($post['district1'] ?? ''),
				'district_id_residen' 			=> trim($post['district2'] ?? ''),
				'village_id_ktp' 				=> trim($post['village1'] ?? ''),
				'village_id_residen' 			=> trim($post['village2'] ?? ''),
				'job_title_id' 					=> trim($post['job_title'] ?? ''),
				'department_id' 				=> trim($post['department'] ?? ''),
				'date_of_hire' 					=> $dateofHired,
				'date_end_probation' 			=> date("Y-m-d", strtotime($date_end_prob)),
				'date_permanent' 				=> date("Y-m-d", strtotime($date_permanent)),
				'employment_status_id' 			=> trim($post['emp_status'] ?? ''),
				'shift_type' 					=> trim($post['shift_type'] ?? ''),
				'work_location' 				=> trim($post['work_loc'] ?? ''),
				'direct_id' 					=> trim($post['direct'] ?? ''),
				'indirect_id' 					=> trim($post['indirect'] ?? ''),
				'emergency_contact_name' 		=> trim($post['emergency_name'] ?? ''),
				'emergency_contact_phone' 		=> trim($post['emergency_phone'] ?? ''),
				'emergency_contact_email' 		=> trim($post['emergency_email'] ?? ''),
				'emergency_contact_relation' 	=> trim($post['emergency_relation'] ?? ''),
				'bank_name' 					=> trim($post['bank_name'] ?? ''),
				'bank_address' 					=> trim($post['bank_address'] ?? ''),
				'bank_acc_name' 				=> trim($post['bank_acc_name'] ?? ''),
				'bank_acc_no' 					=> trim($post['bank_acc_no'] ?? ''),
				'date_resign_letter' 			=> date("Y-m-d", strtotime($date_resign_letter)),
				'date_resign_active' 			=> date("Y-m-d", strtotime($date_resign_active)),
				'resign_category' 				=> trim($post['resign_category'] ?? ''),
				'resign_reason' 				=> trim($post['resign_reason'] ?? ''),
				'resign_exit_interview_feedback' 	=> trim($post['resign_exit_feedback'] ?? ''),
				'emp_photo' 					=> $emp_photo,
				'emp_signature' 				=> $emp_signature,
				'created_at' 					=> date("Y-m-d H:i:s"),
				'company_id' 					=> trim($post['company'] ?? ''),
				'division_id' 					=> trim($post['division'] ?? ''),
				'branch_id' 					=> trim($post['branch'] ?? ''),
				'section_id' 					=> trim($post['section'] ?? ''),
				'status_id' 					=> trim($post['status'] ?? ''),
				'job_level_id' 					=> trim($post['job_level'] ?? ''),
				'grade_id' 						=> trim($post['grade'] ?? ''),
				'foto_ktp' 						=> $foto_ktp,
				'foto_npwp' 					=> $foto_npwp,
				'foto_bpjs' 					=> $foto_bpjs,
				'foto_sima' 					=> $foto_sima,
				'foto_simc' 					=> $foto_simc
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
						$upload_file_ijazah = $this->upload_file($upload_dir, '1', 'file_ijazah'.$i.'', FALSE, '', TRUE, $i);
						$file_ijazah = '';
						if($upload_file_ijazah['status']){ 
							$file_ijazah = $upload_file_ijazah['upload_file'];
						} else if(isset($upload_file_ijazah['error_warning'])){ 
							echo $upload_file_ijazah['error_warning']; exit;
						}

						if(isset($post['education'][$i])){
							$itemData = [
								'employee_id' 	=> $lastId,
								'education_id' 	=> trim($post['education'][$i]),
								'institution' 	=> trim($post['institution'][$i]),
								'city' 			=> trim($post['city'][$i]),
								'year' 			=> trim($post['year'][$i]),
								'file_ijazah' 	=> $file_ijazah
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
						$upload_file_sertifikat = $this->upload_file($upload_dir, '1', 'file_sertifikat'.$i.'', FALSE, '', TRUE, $i);
						$file_sertifikat = '';
						if($upload_file_sertifikat['status']){ 
							$file_sertifikat = $upload_file_sertifikat['upload_file'];
						} else if(isset($upload_file_sertifikat['error_warning'])){ 
							echo $upload_file_sertifikat['error_warning']; exit;
						}

						if(isset($post['training_name'][$i])){
							$itemData_training = [
								'employee_id' 		=> $lastId,
								'training_name' 	=> trim($post['training_name'][$i]),
								'city' 				=> trim($post['city_training'][$i]),
								'year' 				=> trim($post['year_training'][$i]),
								'file_sertifikat' 	=> $file_sertifikat
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
				$username = strtolower(trim($name));

				/*if ($username == trim($username) && strpos($username, ' ') !== false) {
				    $username = str_replace(" ","_",$username);
				}*/

				$words = explode(' ', $username);
				if (count($words) > 1) {
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


				//add jatah cuti
				$this->generate_jatah_cuti_karyawan_baru($lastId,$dateofHired);
				//end add jatah cuti

			}



			return $rs;
		
		}


	}  


	public function edit_data($post) { 

		$upload_dir = './uploads/employee/'.$post['emp_code'].'/'; // nama folder
		// Cek apakah folder sudah ada
		if (!is_dir($upload_dir)) {
		    // Jika belum ada, buat folder
		    mkdir($upload_dir, 0755, true); // 0755 = permission, true = recursive
		}


		if(!empty($post['id'])){ 
			$date_of_birth 		= trim($post['date_of_birth'] ?? '');
			$date_of_hire 		= trim($post['date_of_hire'] ?? '');
			$date_end_prob 		= trim($post['date_end_prob'] ?? '');
			$date_permanent 	= trim($post['date_permanent'] ?? '');
			$date_resign_letter = trim($post['date_resign_letter'] ?? '');
			$date_resign_active = trim($post['date_resign_active'] ?? '');
			$hdnempsign 		= trim($post['hdnempsign'] ?? '');
			$hdnempphoto 		= trim($post['hdnempphoto'] ?? '');

			$hdnfotoktp 		= trim($post['hdnfotoktp'] ?? '');
			$hdnfotonpwp 		= trim($post['hdnfotonpwp'] ?? '');
			$hdnfotobpjs 		= trim($post['hdnfotobpjs'] ?? '');
			$hdnfotosima 		= trim($post['hdnfotosima'] ?? '');
			$hdnfotosimc 		= trim($post['hdnfotosimc'] ?? '');
			

			$upload_emp_photo = $this->upload_file($upload_dir, '1', 'emp_photo', FALSE, '', TRUE, '');
			$emp_photo = '';
			if($upload_emp_photo['status']){
				$emp_photo = $upload_emp_photo['upload_file'];
			} else if(isset($upload_emp_photo['error_warning'])){
				echo $upload_emp_photo['error_warning']; exit;
			}

			$upload_emp_sign = $this->upload_file($upload_dir, '1', 'emp_signature', FALSE, '', TRUE, '');
			$emp_signature = '';
			if($upload_emp_sign['status']){
				$emp_signature = $upload_emp_sign['upload_file'];
			} else if(isset($upload_emp_sign['error_warning'])){
				echo $upload_emp_sign['error_warning']; exit;
			}

			$upload_foto_ktp = $this->upload_file($upload_dir, '1', 'foto_ktp', FALSE, '', TRUE, '');
			$foto_ktp = '';
			if($upload_foto_ktp['status']){
				$foto_ktp = $upload_foto_ktp['upload_file'];
			} else if(isset($upload_foto_ktp['error_warning'])){
				echo $upload_foto_ktp['error_warning']; exit;
			}

			$upload_foto_npwp = $this->upload_file($upload_dir, '1', 'foto_npwp', FALSE, '', TRUE, '');
			$foto_npwp = '';
			if($upload_foto_npwp['status']){
				$foto_npwp = $upload_foto_npwp['upload_file'];
			} else if(isset($upload_foto_npwp['error_warning'])){
				echo $upload_foto_npwp['error_warning']; exit;
			}

			$upload_foto_bpjs = $this->upload_file($upload_dir, '1', 'foto_bpjs', FALSE, '', TRUE, '');
			$foto_bpjs = '';
			if($upload_foto_bpjs['status']){
				$foto_bpjs = $upload_foto_bpjs['upload_file'];
			} else if(isset($upload_foto_bpjs['error_warning'])){
				echo $upload_foto_bpjs['error_warning']; exit;
			}

			$upload_foto_sima = $this->upload_file($upload_dir, '1', 'foto_sima', FALSE, '', TRUE, '');
			$foto_sima = '';
			if($upload_foto_sima['status']){
				$foto_sima = $upload_foto_sima['upload_file'];
			} else if(isset($upload_foto_sima['error_warning'])){
				echo $upload_foto_sima['error_warning']; exit;
			}

			$upload_foto_simc = $this->upload_file($upload_dir, '1', 'foto_simc', FALSE, '', TRUE, '');
			$foto_simc = '';
			if($upload_foto_simc['status']){
				$foto_simc = $upload_foto_simc['upload_file'];
			} else if(isset($upload_foto_simc['error_warning'])){
				echo $upload_foto_simc['error_warning']; exit;
			}

			if($emp_photo == '' && $hdnempphoto != ''){
				$emp_photo = $hdnempphoto;
			}
			if($emp_signature == '' && $hdnempsign != ''){
				$emp_signature = $hdnempsign;
			}
			if($foto_ktp == '' && $hdnfotoktp != ''){
				$foto_ktp = $hdnfotoktp;
			}
			if($foto_npwp == '' && $hdnfotonpwp != ''){
				$foto_npwp = $hdnfotonpwp;
			}
			if($foto_bpjs == '' && $hdnfotobpjs != ''){
				$foto_bpjs = $hdnfotobpjs;
			}
			if($foto_sima == '' && $hdnfotosima != ''){
				$foto_sima = $hdnfotosima;
			}
			if($foto_simc == '' && $hdnfotosimc != ''){
				$foto_simc = $hdnfotosimc;
			}



			$data = [
				'full_name' 					=> trim($post['full_name'] ?? ''),
				'nick_name' 					=> trim($post['nick_name'] ?? ''),
				'personal_email' 				=> trim($post['email'] ?? ''),
				'personal_phone' 				=> trim($post['phone'] ?? ''),
				'gender' 						=> trim($post['gender'] ?? ''),
				'ethnic' 						=> trim($post['ethnic'] ?? ''),
				'nationality' 					=> trim($post['nationality'] ?? ''),
				'marital_status_id' 			=> trim($post['marital_status'] ?? ''),
				/*'tanggungan' 					=> trim($post['tanggungan']),*/
				'no_ktp' 						=> trim($post['no_ktp'] ?? ''),
				'sim_a' 						=> trim($post['sim_a'] ?? ''),
				'sim_c' 						=> trim($post['sim_c'] ?? ''),
				'no_npwp' 						=> trim($post['no_npwp'] ?? ''),
				'no_bpjs' 						=> trim($post['no_bpjs'] ?? ''),
				'place_of_birth' 				=> trim($post['place_of_birth'] ?? ''),
				'date_of_birth' 				=> date("Y-m-d", strtotime($date_of_birth)),
				'address_ktp' 					=> trim($post['address1'] ?? ''),
				'address_residen' 				=> trim($post['address2'] ?? ''),
				'postal_code_ktp' 				=> trim($post['postal_code1'] ?? ''),
				'postal_code_residen' 			=> trim($post['postal_code2'] ?? ''),
				'province_id_ktp' 				=> trim($post['province1'] ?? ''),
				'province_id_residen' 			=> trim($post['province2'] ?? ''),
				'regency_id_ktp' 				=> trim($post['regency1'] ?? ''),
				'regency_id_residen' 			=> trim($post['regency2'] ?? ''),
				'district_id_ktp' 				=> trim($post['district1'] ?? ''),
				'district_id_residen' 			=> trim($post['district2'] ?? ''),
				'village_id_ktp' 				=> trim($post['village1'] ?? ''),
				'village_id_residen' 			=> trim($post['village2'] ?? ''),
				'job_title_id' 					=> trim($post['job_title'] ?? ''),
				'department_id' 				=> trim($post['department'] ?? ''),
				'date_of_hire' 					=> date("Y-m-d", strtotime($date_of_hire)),
				'date_end_probation' 			=> date("Y-m-d", strtotime($date_end_prob)),
				'date_permanent' 				=> date("Y-m-d", strtotime($date_permanent)),
				'employment_status_id' 			=> trim($post['emp_status'] ?? ''),
				'shift_type' 					=> trim($post['shift_type'] ?? ''),
				'work_location' 				=> trim($post['work_loc'] ?? ''),
				'direct_id' 					=> trim($post['direct'] ?? ''),
				'indirect_id' 					=> trim($post['indirect'] ?? ''),
				'emergency_contact_name' 		=> trim($post['emergency_name'] ?? ''),
				'emergency_contact_phone' 		=> trim($post['emergency_phone'] ?? ''),
				'emergency_contact_email' 		=> trim($post['emergency_email'] ?? ''),
				'emergency_contact_relation' 	=> trim($post['emergency_relation'] ?? ''),
				'bank_name' 					=> trim($post['bank_name'] ?? ''),
				'bank_address' 					=> trim($post['bank_address'] ?? ''),
				'bank_acc_name' 				=> trim($post['bank_acc_name'] ?? ''),
				'bank_acc_no' 					=> trim($post['bank_acc_no'] ?? ''),
				'date_resign_letter' 			=> date("Y-m-d", strtotime($date_resign_letter)),
				'date_resign_active' 			=> date("Y-m-d", strtotime($date_resign_active)),
				'resign_category' 				=> trim($post['resign_category'] ?? ''),
				'resign_reason' 				=> trim($post['resign_reason'] ?? ''),
				'resign_exit_interview_feedback' 	=> trim($post['resign_exit_feedback'] ?? ''),
				'emp_photo' 					=> $emp_photo,
				'emp_signature' 				=> $emp_signature,
				'updated_at' 					=> date("Y-m-d H:i:s") ?? '',
				'company_id' 					=> trim($post['company'] ?? ''),
				'division_id' 					=> trim($post['division'] ?? ''),
				'branch_id' 					=> trim($post['branch'] ?? ''),
				'section_id' 					=> trim($post['section'] ?? ''),
				'gender' 						=> trim($post['gender'] ?? ''),
				'status_id' 					=> trim($post['status'] ?? ''),
				'job_level_id' 					=> trim($post['job_level'] ?? ''),
				'grade_id' 						=> trim($post['grade'] ?? ''),
				'foto_ktp' 						=> $foto_ktp,
				'foto_npwp' 					=> $foto_npwp,
				'foto_bpjs' 					=> $foto_bpjs,
				'foto_sima' 					=> $foto_sima,
				'foto_simc' 					=> $foto_simc
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

							$hdnfile_ijazah = trim($post['hdnfile_ijazah'.$i]);
							$file_ijazah = '';
							$upload_file_ijazah = $this->upload_file($upload_dir, '1', 'file_ijazah'.$i.'', FALSE, '', TRUE, $i);
							if($upload_file_ijazah['status']){ 
								$file_ijazah = $upload_file_ijazah['upload_file'];
							} else if(isset($upload_file_ijazah['error_warning'])){ 
								echo $upload_file_ijazah['error_warning']; exit;
							}

							if($file_ijazah == '' && $hdnfile_ijazah != ''){
								$file_ijazah = $hdnfile_ijazah;
							}

							if(isset($post['education'][$i])){
								$itemData = [
									'education_id' 	=> trim($post['education'][$i]),
									'institution' 	=> trim($post['institution'][$i]),
									'city' 			=> trim($post['city'][$i]),
									'year' 			=> trim($post['year'][$i]),
									'file_ijazah' 	=> $file_ijazah
								];

								$this->db->update("education_detail", $itemData, "id = '".$hdnid."'");
							}

						}else{ //insert
							$upload_file_ijazah = $this->upload_file($upload_dir, '1', 'file_ijazah'.$i.'', FALSE, '', TRUE, $i);
							$file_ijazah = '';
							if($upload_file_ijazah['status']){ 
								$file_ijazah = $upload_file_ijazah['upload_file'];
							} else if(isset($upload_file_ijazah['error_warning'])){ 
								echo $upload_file_ijazah['error_warning']; exit;
							}

							if(isset($post['education'][$i])){
								$itemData = [
									'employee_id' 	=> $post['id'],
									'education_id' 	=> trim($post['education'][$i]),
									'institution' 	=> trim($post['institution'][$i]),
									'city' 			=> trim($post['city'][$i]),
									'year' 			=> trim($post['year'][$i]),
									'file_ijazah' 	=> $file_ijazah
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
							$hdnfile_sertifikat = trim($post['hdnfile_sertifikat'.$i]);
							$file_sertifikat = '';
							$upload_file_sertifikat = $this->upload_file($upload_dir, '1', 'file_sertifikat'.$i.'', FALSE, '', TRUE, $i);
							if($upload_file_sertifikat['status']){ 
								$file_sertifikat = $upload_file_sertifikat['upload_file'];
							} else if(isset($upload_file_sertifikat['error_warning'])){ 
								echo $upload_file_sertifikat['error_warning']; exit;
							}

							if($file_sertifikat == '' && $hdnfile_sertifikat != ''){
								$file_sertifikat = $hdnfile_sertifikat;
							}

							if(isset($post['training_name'][$i])){
								$itemData_training = [
									'training_name' 	=> trim($post['training_name'][$i]),
									'city' 				=> trim($post['city_training'][$i]),
									'year' 				=> trim($post['year_training'][$i]),
									'file_sertifikat' 	=> $file_sertifikat
								];

								$this->db->update("training_detail", $itemData_training, "id = '".$hdnid_training."'");
							}

						}else{ //insert
							$upload_file_sertifikat = $this->upload_file($upload_dir, '1', 'file_sertifikat'.$i.'', FALSE, '', TRUE, $i);
							$file_sertifikat = '';
							if($upload_file_sertifikat['status']){ 
								$file_sertifikat = $upload_file_sertifikat['upload_file'];
							} else if(isset($upload_file_sertifikat['error_warning'])){ 
								echo $upload_file_sertifikat['error_warning']; exit;
							}

							if(isset($post['training_name'][$i])){
								$itemData_training = [
									'employee_id' 		=> $post['id'],
									'training_name' 	=> trim($post['training_name'][$i]),
									'city' 				=> trim($post['city_training'][$i]),
									'year' 				=> trim($post['year_training'][$i]),
									'file_sertifikat' 	=> $file_sertifikat
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
					    p.full_name AS direct_name,
					    (case when a.gender = "M" then "Male"
					    when a.gender = "F" then "Female"
					    else ""
					    end) as gender_name,
					    if(a.status_id = "1","Active","Not Active") as status_name,
					    q.name as job_level_name,
					    r.name as grade_name
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
					    left join master_job_level q on q.id = a.job_level_id
					    left join master_grade r on r.id = a.grade_id

			)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		
		
		return $rs;
	} 



	public function import_data($list_data){  
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		if (isset($list_data[0][0]) && is_array($list_data[0][0])) {
		    $list_data[0] = $list_data[0][0];
		}

		// Lewati header (baris ke-0 dan ke-1)
		for ($i = 2; $i < count($list_data); $i++) {
            $row = $list_data[$i];
            $baris = $i+1;


            /// UPDATE DATA
         	if($row[58] != '' && $list_data[0][58] == 'Employee Code') 
         	{ 
         		$getID = $this->db->query("select id, full_name, branch_id, personal_email from employees where emp_code = '".$row[58]."'")->result();
            	$employee_id = $getID[0]->id;

         		if($employee_id != ''){ echo 'tes 11'; die();
            		$data = [
		                'full_name' 		=> trim($row[0]),
		                'nick_name' 		=> trim($row[1]),
		                'personal_email'	=> trim($row[2]),
		                'personal_phone' 	=> trim($row[3]),
		                'gender' 			=> trim($row[4]),
		                'ethnic' 			=> trim($row[5]),
		                'nationality' 		=> trim($row[6]),
		                'marital_status_id' => trim((int)$row[7]),
		                'tanggungan' 		=> trim($row[8]),
		                'no_ktp' 			=> trim($row[9]),
		                'sim_a' 			=> trim($row[10]),
		                'sim_c' 			=> trim($row[11]),
		                'no_npwp' 			=> trim($row[12]),
		                'no_bpjs' 			=> trim($row[13]),
		                'place_of_birth' 	=> trim($row[14]),
		                'date_of_birth'  	=> trim($row[15]),
		                'address_ktp' 		=> trim($row[16]),
		                'postal_code_ktp' 	=> trim($row[17]),
		                'province_id_ktp' 	=> trim((int)$row[18]),
		                'regency_id_ktp' 	=> trim((int)$row[19]),
		                'district_id_ktp' 	=> trim((int)$row[20]),
		                'village_id_ktp' 	=> trim((int)$row[21]),
		                'address_residen' 	=> trim($row[22]),
		                'postal_code_residen' 	=> trim($row[23]),
		                'province_id_residen' 	=> trim((int)$row[24]),
		                'regency_id_residen' 	=> trim((int)$row[25]),
		                'district_id_residen' 	=> trim((int)$row[26]),
		                'village_id_residen' 	=> trim((int)$row[27]),
		                'job_level_id' 		=> trim((int)$row[28]),
		                'job_title_id' 		=> trim((int)$row[29]),
		                'division_id' 		=> trim((int)$row[30]),
		                'department_id' 	=> trim((int)$row[31]), 
		                'section_id' 		=> trim((int)$row[32]),
		                'date_of_hire' 		=> trim($row[33]),
		                'date_end_probation' 	=> trim($row[34]),
		                'date_permanent' 		=> trim($row[35]),
		                'employment_status_id' 	=> trim((int)$row[36]),
		                'branch_id' 		=> trim((int)$row[37]),
		                'grade_id' 			=> trim((int)$row[38]),
		                'status_id' 		=> trim((int)$row[39]),
		                'shift_type' 		=> trim($row[40]),
		                'work_location' 	=> trim($row[41]),
		                'direct_id' 		=> trim((int)$row[42]),
		                'indirect_id' 		=> trim((int)$row[43]),
		                'emergency_contact_name' 		=> trim($row[44]),
		                'emergency_contact_phone' 		=> trim($row[45]),
		                'emergency_contact_email' 		=> trim($row[46]),
		                'emergency_contact_relation' 	=> trim($row[47]),
		                'bank_name' 	=> trim($row[48]),
		                'bank_address' 	=> trim($row[49]),
		                'bank_acc_name' => trim($row[50]),
		                'bank_acc_no' 	=> trim($row[51]),
		                'date_resign_letter' 	=> trim($row[52]),
		                'date_resign_active' 	=> trim($row[53]),
		                'resign_category' 		=> trim($row[54]),
		                'resign_reason' 		=> trim($row[55]),
		                'resign_exit_interview_feedback' => trim($row[56]),
		                'company_id' 	=> trim($row[57])
		            ];

		            $rs = $this->db->update($this->table_name, $data, [$this->primary_key => $employee_id]);


		            if($row[0] != $getID[0]->full_name || $row[2] != $getID[0]->personal_email || $row[37] != $getID[0]->branch_id){
		            	//update tbl user
		            	$name = $row[0];
						$username = strtolower(trim($name));

						/*if ($username == trim($username) && strpos($username, ' ') !== false) {
						    $username = str_replace(" ","_",$username);
						}*/

						$words = explode(' ', $username);
						if (count($words) > 1) {
							$username = str_replace(" ","_",$username);
						}


		            	$data2 = [
							'name' 			=> trim($name),
							'email' 		=> trim($row[2]),
							'username'		=> trim($username),
							'id_branch'		=> trim($row[37])
						];
						$this->db->update("user", $data2, "id_karyawan = '".$employee_id."'");
	            		//end update tbl user
		            }
		            

		            //START add folder upload 
					$upload_dir = './uploads/employee/'.$row[58].'/'; // nama folder
					// Cek apakah folder sudah ada
					if (!is_dir($upload_dir)) {
					    // Jika belum ada, buat folder
					    mkdir($upload_dir, 0755, true); // 0755 = permission, true = recursive
					}
					//END add folder upload 



		            if (!$rs) $error .=",baris ". $baris;
		           	
            	}else{ echo 'tes 22'; die();
            		$error .=",baris ". $baris;
            	} 

         	}else{  /// INSERT DATA
         		if($row[0] != ''){ //full name tidak kosong
	            	$employee = $this->db->query("select * from employees where full_name = '".$row[0]."'")->result(); 

	            	if(empty($employee)){
	            		if($row[33] != ''){
	            			$dateofHired = $row[33];
	            			$YMdateofHired = date('ym', strtotime($dateofHired));
	            		}else{
	            			$yearcode = date("y");
							$monthcode = date("m");
							$YMdateofHired = $yearcode.$monthcode;
	            		}
	            		


	            		if($row[57] != ''){ //company harus diisi, karna pengaruh dlm pembentukan emp code
	            			if($row[57] == '2'){ //NBID
								$lettercode 	= ('NBI'); 
							}else if ($row[57] == '1'){
								$lettercode 	= ('GDI'); 
							}else{ //selain 1 atau 2, value tidak diketahui
								$lettercode 	= ('NNN'); 
							}
							
							$code = $lettercode.$YMdateofHired; 
							$runningnumber 	= $this->getNextNumber($code); // next count number
							$genEmpCode 	= $lettercode.$YMdateofHired.$runningnumber;



			            	$data = [
			            		'emp_code' 			=> $genEmpCode,
				                'full_name' 		=> trim($row[0]),
				                'nick_name' 		=> trim($row[1]),
				                'personal_email'	=> trim($row[2]),
				                'personal_phone' 	=> trim($row[3]),
				                'gender' 			=> trim($row[4]),
				                'ethnic' 			=> trim($row[5]),
				                'nationality' 		=> trim($row[6]),
				                'marital_status_id' => trim((int)$row[7]),
				                'tanggungan' 		=> trim($row[8]),
				                'no_ktp' 			=> trim($row[9]),
				                'sim_a' 			=> trim($row[10]),
				                'sim_c' 			=> trim($row[11]),
				                'no_npwp' 			=> trim($row[12]),
				                'no_bpjs' 			=> trim($row[13]),
				                'place_of_birth' 	=> trim($row[14]),
				                'date_of_birth'  	=> trim($row[15]),
				                'address_ktp' 		=> trim($row[16]),
				                'postal_code_ktp' 	=> trim($row[17]),
				                'province_id_ktp' 	=> trim((int)$row[18]),
				                'regency_id_ktp' 	=> trim((int)$row[19]),
				                'district_id_ktp' 	=> trim((int)$row[20]),
				                'village_id_ktp' 	=> trim((int)$row[21]),
				                'address_residen' 	=> trim($row[22]),
				                'postal_code_residen' 	=> trim($row[23]),
				                'province_id_residen' 	=> trim((int)$row[24]),
				                'regency_id_residen' 	=> trim((int)$row[25]),
				                'district_id_residen' 	=> trim((int)$row[26]),
				                'village_id_residen' 	=> trim((int)$row[27]),
				                'job_level_id' 		=> trim((int)$row[28]),
				                'job_title_id' 		=> trim((int)$row[29]),
				                'division_id' 		=> trim((int)$row[30]),
				                'department_id' 	=> trim((int)$row[31]), 
				                'section_id' 		=> trim((int)$row[32]),
				                'date_of_hire' 		=> trim($row[33]),
				                'date_end_probation' 	=> trim($row[34]),
				                'date_permanent' 		=> trim($row[35]),
				                'employment_status_id' 	=> trim((int)$row[36]),
				                'branch_id' 		=> trim((int)$row[37]),
				                'grade_id' 			=> trim((int)$row[38]),
				                'status_id' 		=> trim((int)$row[39]),
				                'shift_type' 		=> trim($row[40]),
				                'work_location' 	=> trim($row[41]),
				                'direct_id' 		=> trim((int)$row[42]),
				                'indirect_id' 		=> trim((int)$row[43]),
				                'emergency_contact_name' 		=> trim($row[44]),
				                'emergency_contact_phone' 		=> trim($row[45]),
				                'emergency_contact_email' 		=> trim($row[46]),
				                'emergency_contact_relation' 	=> trim($row[47]),
				                'bank_name' 	=> trim($row[48]),
				                'bank_address' 	=> trim($row[49]),
				                'bank_acc_name' => trim($row[50]),
				                'bank_acc_no' 	=> trim($row[51]),
				                'date_resign_letter' 	=> trim($row[52]),
				                'date_resign_active' 	=> trim($row[53]),
				                'resign_category' 		=> trim($row[54]),
				                'resign_reason' 		=> trim($row[55]),
				                'resign_exit_interview_feedback' => trim($row[56]),
				                'company_id' 	=> trim($row[57])
				            ];

				            $rs = $this->db->insert($this->table_name, $data);
				            $lastId = $this->db->insert_id();

				            if($rs){
				            	// add ke table user //
								$pwd = '112233';
								$password = md5($pwd);

								$name = $row[0];
								$username = strtolower(trim($name));

								/*if ($username == trim($username) && strpos($username, ' ') !== false) {
								    $username = str_replace(" ","_",$username);
								}*/

								$words = explode(' ', $username);
								if (count($words) > 1) {
									$username = str_replace(" ","_",$username);
								}
								

								$data2 = [
									'name' 			=> trim($row[0]),
									'email' 		=> trim($row[2]),
									'username'		=> trim($username),
									'passwd' 		=> trim($password),
									'id_karyawan'	=> $lastId,
									'id_groups' 	=> 3, //user
									'base_menu'		=> 'role',
									'id_branch'		=> trim($row[37]),
									'isaktif' 		=> 2,
									'date_insert' 	=> date("Y-m-d H:i:s")
								];
								$this->db->insert('user', $data2);
								// end add ke table user //


								//START add folder upload 
								$upload_dir = './uploads/employee/'.$genEmpCode.'/'; // nama folder
								// Cek apakah folder sudah ada
								if (!is_dir($upload_dir)) {
								    // Jika belum ada, buat folder
								    mkdir($upload_dir, 0755, true); // 0755 = permission, true = recursive
								}
								//END add folder upload 

				            }
				            
				           
							if (!$rs) $error .=",baris ". $baris;
	            		}else{
	            			$error .=", company kosong baris ". $baris;
	            		}
	            	}else{
	            		$error .=",nama employee sudah ada baris ". $baris;
	            	}
	            }else{ 
	            	$error .=",nama kosong baris ". $baris;
	            }
         	}

        }


		return $error;

	}

	public function import_data_old($list_data)
	{  
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'full_name' 	=> $v["B"],
				'nick_name' 	=> $v["C"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}


		return $error;
	}

	public function eksport_data()
	{
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1 && $getdata[0]->id_groups != 4){ //bukan super user && bukan HR admin
			$whr=' where a.id = "'.$karyawan_id.'" or a.direct_id = "'.$karyawan_id.'" ';
		}

		
		$sql = 'select a.*,(case when a.gender="M" then "Male" when a.gender="F" then "Female" else "" end) as gender_name, o.name AS job_title_name, if(a.status_id=1,"Active","Not Active") as status_name
			from employees a LEFT JOIN master_job_title o ON o.id = a.job_title_id
			'.$whr.'
	   		ORDER BY a.full_name ASC
		';

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
			$data 	.= '<td>'.$this->return_build_fileinput('file_ijazah'.$row.'','','','file_ijazah','text-align: right;','data-id="'.$row.'" ').'</td>';


			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 

	// Generate expenses item rows for edit & view
	public function getExpensesRows($id,$view,$print=FALSE){ 
		
		$dt = ''; 
		
		$rs = $this->db->query("select a.*, b.name as education_name, c.emp_code from education_detail a left join master_education b on b.id = a.education_id left join employees c on c.id = a.employee_id where a.employee_id = '".$id."' ")->result(); 
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
					$viewdoc = '';
					if($f->file_ijazah != ''){
						$viewdoc = '<a href="'.base_url().'uploads/employee/'.$f->emp_code.'/'.$f->file_ijazah.'" target="_blank">View</a>';
					}

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';
					$dt .= '<td>'.$this->return_build_chosenme($msEdu,'',isset($f->education_id)?$f->education_id:1,'','education['.$row.']','education','education','','id','name','','','',' data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->institution,'institution['.$row.']','','institution','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->city,'city['.$row.']','','city','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->year,'year['.$row.']','','year','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_fileinput('file_ijazah'.$row.'','','','file_ijazah','text-align: right;','data-id="'.$row.'" ').$viewdoc.' <input type="hidden" id="hdnfile_ijazah'.$row.'" name="hdnfile_ijazah'.$row.'" value="'.$f->file_ijazah.'"/></td>';
					
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
					$dt .= '<td><a href="'.base_url().'uploads/employee/'.$f->emp_code.'/'.$f->file_ijazah.'" target="_blank">View</a></td>';
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
			$data 	.= '<td>'.$this->return_build_fileinput('file_sertifikat'.$row.'','','','file_sertifikat','text-align: right;','data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del-training(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 

	// Generate expenses item rows for edit & view
	public function getTrainingRows($id,$view,$print=FALSE){ 
		
		$dt = ''; 
		
		$rs = $this->db->query("select a.*, b.emp_code from training_detail a left join employees b on b.id = a.employee_id where a.employee_id = '".$id."' ")->result(); 
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
					$viewdoc = '';
					if($f->file_sertifikat != ''){
						$viewdoc = '<a href="'.base_url().'uploads/employee/'.$f->emp_code.'/'.$f->file_sertifikat.'" target="_blank">View</a>';
					}

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid_training'.$row.'" name="hdnid_training['.$row.']" value="'.$f->id.'"/></td>';
					$dt .= '<td>'.$this->return_build_txt($f->training_name,'training_name['.$row.']','','training_name','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->city,'city_training['.$row.']','','city_training','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->year,'year_training['.$row.']','','year_training','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_fileinput('file_sertifikat'.$row.'','','','file_sertifikat','text-align: right;','data-id="'.$row.'" ').$viewdoc.' <input type="hidden" id="hdnfile_sertifikat'.$row.'" name="hdnfile_sertifikat'.$row.'" value="'.$f->file_sertifikat.'"/></td>';
					
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
					$dt .= '<td><a href="'.base_url().'uploads/employee/'.$f->emp_code.'/'.$f->file_sertifikat.'" target="_blank">View</a></td>';
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
