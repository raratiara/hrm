<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_recruitment_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "request_recruitment/request_recruitment_menu";
 	protected $table_name 				= _PREFIX_TABLE."request_recruitment";
 	protected $primary_key 				= "id";

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
			'dt.request_number',
			'dt.subject',
			'dt.request_date',
			'dt.required_date',
			'dt.section_name',
			'dt.headcount',
			'dt.job_level_name',
			'dt.status_emp',
			'dt.requested_by_name',
			'dt.status_name',
			'dt.current_approval_level',
			'dt.is_approver',
			'dt.current_role_id',
			'dt.current_role_name',
			'dt.is_approver_view',
			'dt.requested_by',
			'dt.direct_id'
		];
		
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr=' where (ao.requested_by = "'.$karyawan_id.'" or ao.direct_id = "'.$karyawan_id.'" or ao.is_approver_view = 1) ';
		}

		$sIndexColumn = $this->primary_key;

		

		/*$sTable = '(select a.*, b.name as section_name, c.name as job_level_name, 
					e.full_name as requested_by_name, e.direct_id,
					(case when a.status = "waiting_approval" then "Waiting Approval" else a.status end) as status_name 
					from request_recruitment a 
					left join sections b on b.id = a.section_id
					left join master_job_level c on c.id = a.job_level_id
					left join employees e on e.id = a.requested_by)dt';*/


		$sTable = '(select ao.* from (select a.*, b.name as section_name, c.name as job_level_name, 
					e.full_name as requested_by_name, e.direct_id,
					(case when a.status = "draft" then "Draft"
					when a.status = "waiting_approval" then "Waiting Approval"
					when a.status = "approved" then "Approved"
					when a.status = "rejected" then "Rejected"
					when a.status = "cancelled" then "Cancelled"
					when a.status = "closed" then "Closed"
					else ""
					end) as status_name,
					max(d2.current_approval_level) AS current_approval_level,
					max(h.role_id) AS current_role_id,
					max(i.role_name) AS current_role_name,
					GROUP_CONCAT(g.employee_id) AS all_employeeid_approver,
					max(
						IF(
							i.role_name = "Direct",
							e.direct_id,
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
						WHEN max(i.role_name) = "Direct" AND max(e.direct_id) = '.$karyawan_id.' THEN 1  
						ELSE 0 
					END AS is_approver  
					from request_recruitment a 
					left join sections b on b.id = a.section_id
					left join master_job_level c on c.id = a.job_level_id
					left join employees e on e.id = a.requested_by
					LEFT JOIN approval_path d2 ON d2.trx_id = a.id AND d2.approval_matrix_type_id = 10
					LEFT JOIN approval_matrix bb ON bb.id = d2.approval_matrix_id
					LEFT JOIN approval_matrix_detail cc ON cc.approval_matrix_id = bb.id
					LEFT JOIN approval_matrix_role dd ON dd.id = cc.role_id
					LEFT JOIN approval_path_detail ee ON ee.approval_path_id = d2.id AND ee.approval_level = cc.approval_level
					LEFT JOIN approval_matrix_role_pic g ON g.approval_matrix_role_id = cc.role_id
					LEFT JOIN approval_matrix_detail h ON h.approval_matrix_id = d2.approval_matrix_id AND h.approval_level = d2.current_approval_level
					LEFT JOIN approval_matrix_role i ON i.id = h.role_id
					GROUP BY a.id)ao 
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
				/*if($row->status_name == 'Waiting Approval' && $row->requested_by == $karyawan_id){*/
				if($row->status_name == 'Waiting Approval' && $is_approver == 1){
					$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
				}
			}
			$delete_bulk = "";
			$delete = "";
			/*if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}*/

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$delete.'
				</div>',
				$row->id,
				$row->request_number,
				$row->subject,
				$row->request_date,
				$row->required_date,
				$row->section_name,
				$row->headcount,
				$row->job_level_name,
				$row->status_emp,
				$row->requested_by_name,
				$row->status_name
				

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
		
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		

		$cek = $this->db->query("select * from request_recruitment where SUBSTRING(request_number, 5, 4) = '".$period."'"); 
		$rs_cek = $cek->result_array();

		if(empty($rs_cek)){
			$num = '0001';
		}else{
			$cek2 = $this->db->query("select max(request_number) as maxnum from request_recruitment where SUBSTRING(request_number, 5, 4) = '".$period."'");
			$rs_cek2 = $cek2->result_array();
			$dt = $rs_cek2[0]['maxnum']; 
			$getnum = substr($dt,9); 
			$num = str_pad($getnum + 1, 4, 0, STR_PAD_LEFT);
			
		}

		return $num;
		
	} 


	public function getApprovalMatrix($work_location_id, $approval_type_id, $leave_type_id='', $amount='', $trx_id){

		if($work_location_id != '' && $approval_type_id != ''){
			if($approval_type_id == 10){ ///Request Recruitment
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
							'approval_matrix_type_id' 	=> $approval_type_id, //cash advance
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
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;


		$request_date 	= trim($post['request_date']);
		$required_date 	= trim($post['required_date']);

		/*error_reporting(E_ALL);
		ini_set('display_errors', 1);*/

		$lettercode = ('RCR'); // code
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		
		$runningnumber = $this->getNextNumber(); // next count number
		$nextnum 	= $lettercode.'/'.$period.'/'.$runningnumber;

		
  		if(!empty($post['subject'])){ 
  			$dataEmp = $this->db->query("select * from employees where id = '".$post['request_by']."'")->result(); 
			if(!empty($dataEmp)){
				if(!empty($dataEmp[0]->work_location)){

					$data = [
		  				'request_number' 	=> $nextnum,
						'subject' 			=> trim($post['subject']),
						'request_date' 		=> date("Y-m-d", strtotime($request_date)),
						'required_date' 	=> date("Y-m-d", strtotime($required_date)),
						'section_id' 		=> trim($post['section']),
						'headcount' 		=> trim($post['headcount']),
						'job_level_id' 		=> trim($post['joblevel']),
						'status_emp'		=> trim($post['empstatus']),
						'justification' 	=> trim($post['justification']),
						'requested_by'		=> trim($post['request_by']),
						'status'			=> trim($post['status']),
						'created_at'		=> date("Y-m-d H:i:s")
					]; 

					$rs = $this->db->insert($this->table_name, $data);
					$lastId = $this->db->insert_id();

					if($rs){

						///insert approval path
						$approval_type_id = 10; //request recruitment
						$this->getApprovalMatrix($dataEmp[0]->work_location, $approval_type_id, '', '', $lastId);



						if(isset($post['type'])){
							$item_num = count($post['type']); // cek sum
							$item_len_min = min(array_keys($post['type'])); // cek min key index
							$item_len = max(array_keys($post['type'])); // cek max key index
						} else {
							$item_num = 0;
						}

						if($item_num>0){
							for($i=$item_len_min;$i<=$item_len;$i++) 
							{
								if(isset($post['type'][$i])){
									$itemData = [
										'request_recruitment_id' 	=> $lastId,
										'requirement_type' 			=> trim($post['type'][$i]),
										'requirement_text' 			=> trim($post['description'][$i])
									];

									$this->db->insert('recruitment_requirements', $itemData);
								}
							}
						}


						// add job
						if(isset($post['level_job'])){
							$item_num2 = count($post['level_job']); // cek sum
							$item_len_min2 = min(array_keys($post['level_job'])); // cek min key index
							$item_len2 = max(array_keys($post['level_job'])); // cek max key index
						} else {
							$item_num2 = 0;
						}

						if($item_num2>0){
							for($j=$item_len_min2;$j<=$item_len2;$j++) 
							{
								if(isset($post['level_job'][$j])){
									$itemData2 = [
										'request_recruitment_id' 	=> $lastId,
										'priority_level' 			=> trim($post['level_job'][$j]),
										'responsibility' 			=> trim($post['description_job'][$j])
									];

									$this->db->insert('recruitment_job_descriptions', $itemData2);
								}
							}
						}



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
		$request_date 	= trim($post['request_date']);
		$required_date 	= trim($post['required_date']);
		
		if(!empty($post['id'])){
		
			$data = [
				'subject' 			=> trim($post['subject']),
				'request_date' 		=> date("Y-m-d", strtotime($request_date)),
				'required_date' 	=> date("Y-m-d", strtotime($required_date)),
				'section_id' 		=> trim($post['section']),
				'headcount' 		=> trim($post['headcount']),
				'job_level_id' 		=> trim($post['joblevel']),
				'status_emp'		=> trim($post['empstatus']),
				'justification' 	=> trim($post['justification']),
				'requested_by'		=> trim($post['request_by']),
				'status'			=> trim($post['status']),
				'updated_at'		=> date("Y-m-d H:i:s")
			];

			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

			if($rs){
				if(isset($post['type'])){
					$item_num = count($post['type']); // cek sum
					$item_len_min = min(array_keys($post['type'])); // cek min key index
					$item_len = max(array_keys($post['type'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						$hdnid = trim($post['hdnid'][$i]);
						if(!empty($hdnid)){ //update

							if(isset($post['type'][$i])){
								$itemData = [
									'requirement_type' 	=> trim($post['type'][$i]),
									'requirement_text' 	=> trim($post['description'][$i])
								];

								$this->db->update("recruitment_requirements", $itemData, "id = '".$hdnid."'");
							}

						}else{ //insert

							if(isset($post['type'][$i])){
								$itemData = [
									'request_recruitment_id' 	=> $post['id'],
									'requirement_type' 			=> trim($post['type'][$i]),
									'requirement_text' 			=> trim($post['description'][$i])
								];

								$this->db->insert('recruitment_requirements', $itemData);
							}

						}
						
					}
				}


				// add job
				if(isset($post['level_job'])){
					$item_num2 = count($post['level_job']); // cek sum
					$item_len_min2 = min(array_keys($post['level_job'])); // cek min key index
					$item_len2 = max(array_keys($post['level_job'])); // cek max key index
				} else {
					$item_num2 = 0;
				}

				if($item_num2>0){
					for($j=$item_len_min2;$j<=$item_len2;$j++) 
					{
						$hdnid_job = trim($post['hdnid_job'][$j]);
						if(!empty($hdnid_job)){ //update

							if(isset($post['level_job'][$j])){
								$itemData2 = [
									'priority_level' 	=> trim($post['level_job'][$j]),
									'responsibility' 	=> trim($post['description_job'][$j])
								];

								$this->db->update("recruitment_job_descriptions", $itemData2, "id = '".$hdnid_job."'");
							}

						}else{ //insert

							if(isset($post['level_job'][$j])){
								$itemData2 = [
									'request_recruitment_id' 	=> $post['id'],
									'priority_level' 			=> trim($post['level_job'][$j]),
									'responsibility' 			=> trim($post['description_job'][$j])
								];

								$this->db->insert('recruitment_job_descriptions', $itemData2);
							}

						}
						
					}
				}




			}

			return $rs;
		} else return null;
	}  

	public function getRowData($id) { 
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;



		/*$mTable = '(select a.*, b.name as section_name, c.name as job_level_name, 
					e.full_name as requested_by_name, e.direct_id,
					(case when a.status = "waiting_approval" then "Waiting Approval" else a.status end) as status_name 
					from request_recruitment a 
					left join sections b on b.id = a.section_id
					left join master_job_level c on c.id = a.job_level_id
					left join employees e on e.id = a.requested_by
			)dt';*/

		$mTable = '(select ao.* from (select a.*, b.name as section_name, c.name as job_level_name, 
					e.full_name as requested_by_name, e.direct_id,
					(case when a.status = "draft" then "Draft"
					when a.status = "waiting_approval" then "Waiting Approval"
					when a.status = "approved" then "Approved"
					when a.status = "rejected" then "Rejected"
					when a.status = "cancelled" then "Cancelled"
					when a.status = "closed" then "Closed"
					else ""
					end) as status_name,
					max(d2.current_approval_level) AS current_approval_level,
					max(h.role_id) AS current_role_id,
					max(i.role_name) AS current_role_name,
					GROUP_CONCAT(g.employee_id) AS all_employeeid_approver,
					max(
						IF(
							i.role_name = "Direct",
							e.direct_id,
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
						WHEN max(i.role_name) = "Direct" AND max(e.direct_id) = '.$karyawan_id.' THEN 1  
						ELSE 0 
					END AS is_approver  
					from request_recruitment a 
					left join sections b on b.id = a.section_id
					left join master_job_level c on c.id = a.job_level_id
					left join employees e on e.id = a.requested_by
					LEFT JOIN approval_path d2 ON d2.trx_id = a.id AND d2.approval_matrix_type_id = 10
					LEFT JOIN approval_matrix bb ON bb.id = d2.approval_matrix_id
					LEFT JOIN approval_matrix_detail cc ON cc.approval_matrix_id = bb.id
					LEFT JOIN approval_matrix_role dd ON dd.id = cc.role_id
					LEFT JOIN approval_path_detail ee ON ee.approval_path_id = d2.id AND ee.approval_level = cc.approval_level
					LEFT JOIN approval_matrix_role_pic g ON g.approval_matrix_role_id = cc.role_id
					LEFT JOIN approval_matrix_detail h ON h.approval_matrix_id = d2.approval_matrix_id AND h.approval_level = d2.current_approval_level
					LEFT JOIN approval_matrix_role i ON i.id = h.role_id
					GROUP BY a.id)ao 
				)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		


		$isdirect = 0;
		if($rs->direct_id == $karyawan_id){
			$isdirect = 1;
		}
		
		$data = array(
			'rowdata' 	=> $rs,
			'isdirect' 	=> $isdirect
		);
		
		return $data;
	} 

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'year' 			=> $v["B"],
				'section_id' 	=> $v["C"],
				'job_level_id' 	=> $v["D"],
				'mpp' 			=> $v["E"],
				'notes' 		=> $v["F"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{ 
		
		
		$sql = 'select a.*, b.name as section_name, c.name as job_level_name, 
					e.full_name as requested_by_name,
					(case when a.status = "waiting_approval" then "Waiting Approval" else a.status end) as status_name 
					from request_recruitment a 
					left join sections b on b.id = a.section_id
					left join master_job_level c on c.id = a.job_level_id
					left join employees e on e.id = a.requested_by
	   			ORDER BY a.id ASC
		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}



	public function getNewReqRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getReqRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			
			$raw = [
			    ['id' => 'education', 'name' => 'Education'],
			    ['id' => 'skill', 'name' => 'Skill'],
			    ['id' => 'experience', 'name' => 'Experience'],
			    ['id' => 'certification', 'name' => 'Certification'],
			    ['id' => 'other', 'name' => 'Other'],
			];
			$msType = [];
			foreach ($raw as $row_raw) {
			    $obj = new stdClass();
			    $obj->id = $row_raw['id'];
			    $obj->name = $row_raw['name'];
			    $msType[] = $obj;
			}


			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value=""/></td>';
			
			$data 	.= '<td>'.$this->return_build_chosenme($msType,'','','','type['.$row.']','type','type','','id','name','','','',' data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txtarea('','description['.$row.']','','description','text-align: right;','data-id="'.$row.'" ').'</td>';
			
			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	
	// Generate expenses item rows for edit & view
	public function getReqRows($id,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select * from recruitment_requirements where request_recruitment_id = '".$id."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				
				$raw = [
				    ['id' => 'education', 'name' => 'Education'],
				    ['id' => 'skill', 'name' => 'Skill'],
				    ['id' => 'experience', 'name' => 'Experience'],
				    ['id' => 'certification', 'name' => 'Certification'],
				    ['id' => 'other', 'name' => 'Other'],
				];

				$msType = [];

				foreach ($raw as $row_raw) {
				    $obj = new stdClass();
				    $obj->id = $row_raw['id'];
				    $obj->name = $row_raw['name'];
				    $msType[] = $obj;
				}

				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$this->return_build_chosenme($msType,'',isset($f->requirement_type)?$f->requirement_type:1,'','type['.$row.']','type','type','','id','name','','','',' data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txtarea($f->requirement_text,'description['.$row.']','','description','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td><input type="button" class="btn btn-md btn-danger ibtnDel" id="btndel" value="Delete" onclick="del(\''.$row.'\',\''.$f->id.'\')"></td>';
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
					
					$dt .= '<td>'.$no.'</td>';
					$dt .= '<td>'.$f->requirement_type.'</td>';
					$dt .= '<td>'.$f->requirement_text.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}



	public function getNewJobRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getJobRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid_job'.$row.'" name="hdnid_job['.$row.']" value=""/></td>';
			
			$data 	.= '<td>'.$this->return_build_txt('','level_job['.$row.']','','level_job','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txtarea('','description_job['.$row.']','','description_job','text-align: right;','data-id="'.$row.'" ').'</td>';
			
			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDelJob btn btn-md btn-danger " onclick="delJob(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	
	// Generate expenses item rows for edit & view
	public function getJobRows($id,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select * from recruitment_job_descriptions where request_recruitment_id = '".$id."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid_job'.$row.'" name="hdnid_job['.$row.']" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->priority_level,'level_job['.$row.']','','level_job','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txtarea($f->responsibility,'description_job['.$row.']','','description_job','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td><input type="button" class="btn btn-md btn-danger ibtnDelJob" id="btndeljob" value="Delete" onclick="delJob(\''.$row.'\',\''.$f->id.'\')"></td>';
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
					
					$dt .= '<td>'.$no.'</td>';
					$dt .= '<td>'.$f->priority_level.'</td>';
					$dt .= '<td>'.$f->responsibility.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


	
}