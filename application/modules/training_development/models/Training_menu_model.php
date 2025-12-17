<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Training_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "training_development/training_menu";
 	protected $table_name 				= _PREFIX_TABLE."employee_training";
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
			'dt.id',
			'dt.full_name',
			'dt.training_name',
			'dt.training_date',
			'dt.location',
			'dt.trainer',
			'dt.notes',
			'dt.status_name',
			'dt.direct_id',
			'dt.file_sertifikat',
			'dt.emp_code',
			'dt.current_approval_level',
			'dt.is_approver',
			'dt.current_role_id',
			'dt.current_role_name',
			'dt.is_approver_view',
			'dt.employee_id'
		];
		
		
		$karyawan_id = $_SESSION['worker'];
		$whr='';
		if($_SESSION['role'] != 1){ //bukan super user
			/*$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';*/
			$whr = ' where ao.employee_id = "' . $karyawan_id . '" or ao.direct_id = "' . $karyawan_id . '" or ao.is_approver_view = 1  ';
		}
		

		$sIndexColumn = $this->primary_key;
		/*$sTable = '(select a.*, b.full_name, b.direct_id, b.emp_code,
					(case
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					 end) as status_name
					from employee_training a left join employees b on b.id = a.employee_id
					'.$whr.'
				)dt';*/

		$sTable = '(select ao.* from (select a.*, b.full_name, b.direct_id, b.emp_code,
						(case
						when a.status_id = 1 then "Waiting Approval"
						when a.status_id = 2 then "Approved"
						when a.status_id = 3 then "Rejected"
						when a.status_id = 4 then "Request for Update"
						else ""
						 end) as status_name,
						max(d2.current_approval_level) AS current_approval_level,
						max(h.role_id) AS current_role_id,
						max(i.role_name) AS current_role_name,
						GROUP_CONCAT(g.employee_id) AS all_employeeid_approver,
						max(
							IF(
								i.role_name = "Direct",
								b.direct_id,
								(
									SELECT GROUP_CONCAT(employee_id) 
									FROM approval_matrix_role_pic 
									WHERE approval_matrix_role_id = h.role_id
								)
							)
						) AS current_employeeid_approver,
						CASE 
							WHEN FIND_IN_SET('.$karyawan_id.', GROUP_CONCAT(g.employee_id)) > 0 THEN 1 
							ELSE 0 
						END AS is_approver_view,
						CASE 
							WHEN FIND_IN_SET(
								'.$karyawan_id.', 
								(
									SELECT GROUP_CONCAT(employee_id) 
									FROM approval_matrix_role_pic 
									WHERE approval_matrix_role_id = max(h.role_id)
								)
							) > 0 THEN 1
							WHEN max(i.role_name) = "Direct" AND max(b.direct_id) = '.$karyawan_id.' THEN 1  
							ELSE 0 
						END AS is_approver  
						from employee_training a left join employees b on b.id = a.employee_id
						LEFT JOIN approval_path d2 ON d2.trx_id = a.id AND d2.approval_matrix_type_id = 8
						LEFT JOIN approval_matrix bb ON bb.id = d2.approval_matrix_id
						LEFT JOIN approval_matrix_detail cc ON cc.approval_matrix_id = bb.id
						LEFT JOIN approval_matrix_role dd ON dd.id = cc.role_id
						LEFT JOIN approval_path_detail ee ON ee.approval_path_id = d2.id AND ee.approval_level = cc.approval_level
						LEFT JOIN approval_matrix_role_pic g ON g.approval_matrix_role_id = cc.role_id
						LEFT JOIN approval_matrix_detail h ON h.approval_matrix_id = d2.approval_matrix_id AND h.approval_level = d2.current_approval_level
						LEFT JOIN approval_matrix_role i ON i.id = h.role_id
						GROUP BY a.id) ao
						'.$whr.'
					)dt';

		

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
			$is_approver = 0;
			if($row->is_approver == 1){
				$is_approver = 1;
			}



			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #343851; border-color: #343851;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				if(($row->status_name == 'Waiting Approval' || $row->status_name == 'Request for Update') && $row->employee_id == $karyawan_id){
					$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
				}
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}

			$reject=""; 
			$approve="";
			$rfu="";
			/*if($row->status_name == 'Waiting Approval' && $row->direct_id == $karyawan_id){*/
			if($row->status_name == 'Waiting Approval' && $is_approver == 1){
				$reject = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="reject('."'".$row->id."'".','."'".$row->current_approval_level."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" style="background-color: #2c9e1fff; border-color: #2c9e1fff;" href="javascript:void(0);" onclick="approve('."'".$row->id."'".','."'".$row->current_approval_level."'".')" role="button"><i class="fa fa-check"></i></a>';
				$rfu = '<a class="btn btn-xs btn-warning" style="background-color: #fd9b00; border-color: #fd9b00;" href="javascript:void(0);" onclick="rfu('."'".$row->id."'".','."'".$row->current_approval_level."'".')" role="button">RFU</a>';
			}

			$file_sertifikat ="";
			if($row->file_sertifikat != ''){
				$file_sertifikat = '<a href="'.base_url().'uploads/'.$row->emp_code.'/'.$row->file_sertifikat.'" target="_blank">View</a>';
			}
			

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$reject.'
					'.$approve.'
					'.$rfu.'
				</div>',
				$row->id,
				$row->full_name,
				$row->training_name,
				$row->training_date,
				$row->location,
				$row->trainer,
				$row->notes,
				$row->status_name,
				$file_sertifikat

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


	public function getApprovalMatrix($work_location_id, $approval_type_id, $leave_type_id='', $amount='', $trx_id){

		if($work_location_id != '' && $approval_type_id != ''){
			if($approval_type_id == 8){ ///Training
				if($amount == ''){
					$amount=0;
				}
				
				$getmatrix = $this->db->query("select * from approval_matrix where approval_type_id = '".$approval_type_id."' and work_location_id = '".$work_location_id."' and (
						(".$amount." >= min and ".$amount." <= max and min != '' and max != '') or
						(".$amount." >= min and min != '' and max = '') or
						(".$amount." <= max and max != '' and min = '')
					)  ")->result(); 

				if(empty($getmatrix)){
					$getmatrix = $this->db->query("select * from approval_matrix where approval_type_id = '".$approval_type_id."' and work_location_id = '".$work_location_id."' and ((min is null or min = '') and (max is null or max = ''))   ")->result(); 
				}

				
				if(!empty($getmatrix)){
					$approvalMatrixId = $getmatrix[0]->id;
					if($approvalMatrixId != ''){
						$dataApproval = [
							'approval_matrix_type_id' 	=> $approval_type_id, 
							'trx_id' 					=> $trx_id,
							'approval_matrix_id' 		=> $approvalMatrixId,
							'current_approval_level' 	=> 1
						];
						$rs = $this->db->insert("approval_path", $dataApproval);
						$approval_path_id = $this->db->insert_id();
						if($rs){
							$dataApprovalDetail = [
								'approval_path_id' 	=> $approval_path_id, 
								'approval_level' 	=> 1
							];
							$this->db->insert("approval_path_detail", $dataApprovalDetail);
						}
					}
				}
			}
		}

	}


	public function add_data($post) { 

		$training_date 		= date_create($post['training_date']); 
		$f_training_date	= date_format($training_date,"Y-m-d H:i:s");
		
		
		
  		if(!empty($post['employee'])){ 
  			$dataemp = $this->db->query("select * from employees where id = '".$post['employee']."'")->result();

			if(!empty($dataemp)){
				if(!empty($dataemp[0]->work_location)){

					$upload_dir = './uploads/'.$dataemp[0]->emp_code.'/'; // nama folder
					// Cek apakah folder sudah ada
					if (!is_dir($upload_dir)) {
					    // Jika belum ada, buat folder
					    mkdir($upload_dir, 0755, true); // 0755 = permission, true = recursive
					}


					$upload_file_sertifikat = $this->upload_file($upload_dir, '1', 'doc_sertifikat', FALSE, '', TRUE, '');
					$file_sertifikat = '';
					if($upload_file_sertifikat['status']){
						$file_sertifikat = $upload_file_sertifikat['upload_file'];
					} else if(isset($upload_file_sertifikat['error_warning'])){
						echo $upload_file_sertifikat['error_warning']; exit;
					}



		  			$data = [
						'employee_id' 			=> trim($post['employee']),
						'training_name' 		=> trim($post['training_name']),
						'training_date'			=> $f_training_date,
						'location' 				=> trim($post['location']),
						'trainer' 				=> trim($post['trainer']),
						'notes' 				=> trim($post['notes']),
						'status_id' 			=> 1, //waiting approval
						'created_at'			=> date("Y-m-d H:i:s"),
						'file_sertifikat' 		=> $file_sertifikat
						
					];
					$rs = $this->db->insert($this->table_name, $data);
					$lastId = $this->db->insert_id();

					if($rs){
						///insert approval path
						$approval_type_id = 8; //Training
						$this->getApprovalMatrix($dataemp[0]->work_location, $approval_type_id, '', '', $lastId);
					}

					return $rs; 
				}else{
					echo "Work Location not found";
				}
			}else{
				echo "Employee not found"; 
			}

  		}else return null;

	}  

	public function edit_data($post) { 
		
		$karyawan_id = $_SESSION['worker'];
		$id = trim($post['id']);


		$training_date 		= date_create($post['training_date']); 
		$f_training_date	= date_format($training_date,"Y-m-d H:i:s");


		if(!empty($post['id'])){ 
			$dataemp = $this->db->query("select * from employees where id = '".$post['employee']."'")->result();

  			$upload_dir = './uploads/'.$dataemp[0]->emp_code.'/'; // nama folder
			// Cek apakah folder sudah ada
			if (!is_dir($upload_dir)) {
			    // Jika belum ada, buat folder
			    mkdir($upload_dir, 0755, true); // 0755 = permission, true = recursive
			}

			$hdndoc_sertifikat 		= trim($post['hdndoc_sertifikat']);
			$upload_file_sertifikat = $this->upload_file($upload_dir, '1', 'doc_sertifikat', FALSE, '', TRUE, '');
			$file_sertifikat = '';
			if($upload_file_sertifikat['status']){
				$file_sertifikat = $upload_file_sertifikat['upload_file'];
			} else if(isset($upload_file_sertifikat['error_warning'])){
				echo $upload_file_sertifikat['error_warning']; exit;
			}

			if($file_sertifikat == '' && $hdndoc_sertifikat != ''){
				$file_sertifikat = $hdndoc_sertifikat;
			}


			$is_rfu=0;
			$getdata = $this->db->query("select * from employee_training where id = '".$post['id']."' ")->result(); 
			if($getdata[0]->status_id == 4 && $karyawan_id == $getdata[0]->employee_id){ // edit RFU
				$is_rfu=1;
				$data = [
					'employee_id' 			=> trim($post['employee']),
					'training_name' 		=> trim($post['training_name']),
					'training_date'			=> $f_training_date,
					'location' 				=> trim($post['location']),
					'trainer' 				=> trim($post['trainer']),
					'notes' 				=> trim($post['notes']),
					'updated_at'			=> date("Y-m-d H:i:s"),
					'file_sertifikat' 		=> $file_sertifikat,
					'status_id' 			=> 1
					
				];
			}else{
				$data = [
					'employee_id' 			=> trim($post['employee']),
					'training_name' 		=> trim($post['training_name']),
					'training_date'			=> $f_training_date,
					'location' 				=> trim($post['location']),
					'trainer' 				=> trim($post['trainer']),
					'notes' 				=> trim($post['notes']),
					'updated_at'			=> date("Y-m-d H:i:s"),
					'file_sertifikat' 		=> $file_sertifikat
					
				];
			}


			
			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
			if($rs){
				/// update approval path
				$getapprovallevel = $this->db->query("select * from approval_path where approval_matrix_type_id = 8 and trx_id = '".$id."'")->result(); 
				$approval_level = $getapprovallevel[0]->current_approval_level;
				$CurrApproval 	= $this->getCurrApproval($id, $approval_level);
				$CurrApprovalPathId	= $CurrApproval[0]->approval_path_id;

				if($is_rfu == 1){
					$updapproval_path = [
						'current_approval_level' => 1
					];
					$this->db->update("approval_path", $updapproval_path, "id = '".$getapprovallevel[0]->id."' ");

					$this->db->delete('approval_path_detail',"approval_path_id = '".$CurrApprovalPathId."'and approval_level != 1");

					$updApproval2 = [
						'status' 		=> "",
						'approval_by' 	=> "",
						'approval_date'	=> ""
					];
					$this->db->update("approval_path_detail", $updApproval2, "approval_path_id = '".$CurrApprovalPathId."' and approval_level = '1' ");
					
				}
			}


			return $rs;

		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.full_name, b.emp_code,
					(case
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					 end) as status_name
					from employee_training a left join employees b on b.id = a.employee_id
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
				'employee_id' 	=> $v["B"],
				'training_name' => $v["C"],
				'training_date' => $v["D"],
				'location' 		=> $v["E"],
				'trainer' 		=> $v["F"],
				'notes' 		=> $v["G"],
				'status_id' 	=> $v["H"],
				'created_at' 	=> date("Y-m-d H:i:s")
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		 
		$karyawan_id = $_SESSION['worker'];
		$whr='';
		if($_SESSION['role'] != 1){ //bukan super user
			$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}


		
		$sql = 'select a.*, b.full_name, b.direct_id, b.emp_code,
				(case
				when a.status_id = 1 then "Waiting Approval"
				when a.status_id = 2 then "Approved"
				when a.status_id = 3 then "Rejected"
				else ""
				 end) as status_name
				from employee_training a left join employees b on b.id = a.employee_id
				'.$whr.'
				order by a.id asc

		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getCurrApproval($trx_id, $approval_level){
		
		$approval_matrix_type_id = 8; //training

		
		$rs =  $this->db->query("select b.* from approval_path a left join approval_path_detail b on b.approval_path_id = a.id and approval_level = ".$approval_level." where a.approval_matrix_type_id = ".$approval_matrix_type_id." and a.trx_id = ".$trx_id."")->result();
		

		return $rs;
	}



}