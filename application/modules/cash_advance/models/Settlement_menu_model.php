<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settlement_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "cash_advance/settlement_menu";
 	protected $table_name 				= _PREFIX_TABLE."settlement";
 	protected $primary_key 				= "id";

 	/* upload */
 	protected $attachment_folder	= "./uploads/cashadvance/settlement";
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
			'dt.settlement_number',
			'dt.ca_number',
			'dt.settlement_date',
			'dt.prepared_by_name',
			'dt.requested_by_name',
			'dt.settlement_amount',
			'dt.status_name',
			'dt.direct_id',
			'dt.prepared_by',
			'dt.requested_by',
			'dt.current_approval_level',
			'dt.is_approver',
			'dt.current_role_id',
			'dt.current_role_name',
			'dt.is_approver_view'
		];
		
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			/*$whr=' where (a.prepared_by = "'.$karyawan_id.'" or a.requested_by = "'.$karyawan_id.'" or d.direct_id = "'.$karyawan_id.'") ';*/
			$whr=' where (ao.prepared_by = "'.$karyawan_id.'" or ao.requested_by = "'.$karyawan_id.'" or ao.direct_id = "'.$karyawan_id.'" or ao.is_approver_view = 1) ';
		}


		$sIndexColumn = $this->primary_key;
		/*$sTable = '(select a.*, b.ca_number, c.full_name as prepared_by_name, d.full_name as requested_by_name, e.name as status_name, d.direct_id
					from settlement a 
					left join cash_advance b on b.id = a.cash_advance_id
					left join employees c on c.id = a.prepared_by
					left join employees d on d.id = a.requested_by
					left join master_status_cashadvance e on e.id = a.status_id
					'.$whr.'
				)dt';*/

		$sTable = '(select ao.* from (select a.*, b.full_name as prepared_by_name, c.full_name as requested_by_name
					, d.name as status_name, c.direct_id, ab.ca_number,
					max(d2.current_approval_level) AS current_approval_level,
					max(h.role_id) AS current_role_id,
					max(i.role_name) AS current_role_name,
					GROUP_CONCAT(g.employee_id) AS all_employeeid_approver,
					max(
						IF(
							i.role_name = "Direct",
							c.direct_id,
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
						WHEN max(i.role_name) = "Direct" AND max(c.direct_id) = '.$karyawan_id.' THEN 1  
						ELSE 0 
					END AS is_approver   
					from settlement a left join employees b on b.id = a.prepared_by
					left join cash_advance ab on ab.id = a.cash_advance_id
					left join employees c on c.id = a.requested_by
					left join master_status_cashadvance d on d.id = a.status_id
					LEFT JOIN approval_path d2 ON d2.trx_id = a.id AND d2.approval_matrix_type_id = 3
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
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #112D80; border-color: #112D80;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				if(($row->status_name == 'Waiting Approval' || $row->status_name == 'Request for update') && ($row->prepared_by == $karyawan_id || $row->requested_by == $karyawan_id)){

					$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
				}
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';

				if(($row->status_name == 'Waiting Approval' || $row->status_name == 'Request for update') && ($row->prepared_by == $karyawan_id || $row->requested_by == $karyawan_id)){

					$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
				}
			}

			$reject=""; 
			$approve="";
			// if($row->status_name == 'Waiting Approval' && $row->direct_id == $karyawan_id){
			if($row->status_name == 'Waiting Approval' && $is_approver == 1){
				/*$reject = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="reject('."'".$row->id."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" href="javascript:void(0);" onclick="approve('."'".$row->id."'".')" role="button"><i class="fa fa-check"></i></a>';*/

				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}



			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$delete.'
				</div>',
				$row->id,
				$row->settlement_number,
				$row->ca_number,
				$row->settlement_date,
				$row->prepared_by_name,
				$row->requested_by_name,
				$row->settlement_amount,
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
				//$fieldname = 'document';

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



	// Get next number 
	public function getNextNumber() { 
		
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		

		$cek = $this->db->query("select * from settlement where SUBSTRING(settlement_number, 5, 4) = '".$period."'");
		$rs_cek = $cek->result_array();

		if(empty($rs_cek)){
			$num = '0001';
		}else{
			$cek2 = $this->db->query("select max(settlement_number) as maxnum from settlement where SUBSTRING(settlement_number, 5, 4) = '".$period."'");
			$rs_cek2 = $cek2->result_array();
			$dt = $rs_cek2[0]['maxnum']; 
			$getnum = substr($dt,9); 
			$num = str_pad($getnum + 1, 4, 0, STR_PAD_LEFT);
			
		}

		return $num;
		
	} 


	public function getApprovalMatrix($work_location_id, $approval_type_id, $leave_type_id='', $amount='', $trx_id){

		if($work_location_id != '' && $approval_type_id != ''){
			if($approval_type_id == 3){ ///settlement
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

							// send emailing to approver
							$this->approvalemailservice->sendApproval('settlement', $trx_id, $approval_path_id);
						}
					}
				}
			}
		}

	}


	public function add_data($post) { 

		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

		$lettercode = ('STL'); // code
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		
		$runningnumber = $this->getNextNumber(); // next count number
		$nextnum 	= $lettercode.'/'.$period.'/'.$runningnumber;


		
  		if(!empty($post['requested_by'])){ 
  			$dataEmp = $this->db->query("select * from employees where id = '".$post['requested_by']."'")->result(); 
			if(!empty($dataEmp)){

				if(!empty($dataEmp[0]->work_location)){
					$upload_doc = $this->upload_file('1', 'settlement_document', FALSE, '', TRUE);
					$document = '';
					if($upload_doc['status']){ 
						$document = $upload_doc['upload_file'];
					} else if(isset($upload_doc['error_warning'])){ 
						echo $upload_doc['error_warning']; exit;
					}

					//bukti transfer
					$upload_doc_buktitransfer = $this->upload_file('1', 'bukti_transfer', FALSE, '', TRUE);
					$document_buktitransfer = '';
					if($upload_doc_buktitransfer['status']){ 
						$document_buktitransfer = $upload_doc_buktitransfer['upload_file'];
					} else if(isset($upload_doc_buktitransfer['error_warning'])){ 
						echo $upload_doc_buktitransfer['error_warning']; exit;
					}



					$data = [
						'settlement_number'	=> $nextnum,
						'settlement_date' 	=> trim($post['request_date']),
						'cash_advance_id' 	=> trim($post['ca_number']), 
						'prepared_by' 		=> $karyawan_id,
						'requested_by'		=> trim($post['requested_by']),
						'total_cost' 		=> trim($post['total_cost_sett']),
						'settlement_amount' => trim($post['settlement_amount']),
						'document' 			=> $document,
						'status_id' 		=> 1, //waiting approval
						'bukti_transfer' 	=> $document_buktitransfer,
						'no_rekening' 		=> trim($post['no_rekening']),
						'nama_rekening' 	=> trim($post['nama_rekening']),
						'bank_rekening' 	=> trim($post['bank'])
					];
					$rs = $this->db->insert($this->table_name, $data);
					$lastId = $this->db->insert_id();

					if($rs){
						if(isset($post['post_budget_sett'])){
							$item_num = count($post['post_budget_sett']); // cek sum
							$item_len_min = min(array_keys($post['post_budget_sett'])); // cek min key index
							$item_len = max(array_keys($post['post_budget_sett'])); // cek max key index
						} else {
							$item_num = 0;
						}

						if($item_num>0){
							for($i=$item_len_min;$i<=$item_len;$i++) 
							{
								if(isset($post['post_budget_sett'][$i])){
									$itemData = [
										'settlement_id' => $lastId,
										'post_budget_id'	=> trim($post['post_budget_sett'][$i]),
										'amount' 		=> trim($post['amount_sett'][$i]),
										'ppn_pph' 		=> trim($post['ppn_pph_sett'][$i]),
										'total_amount'	=> trim($post['total_amount_sett'][$i]),
										'notes' 		=> trim($post['notes_sett'][$i])
									];

									$this->db->insert('settlement_details', $itemData);
								}
							}
						}


						///insert approval path
						$approval_type_id = 3; //settlement
						$this->getApprovalMatrix($dataEmp[0]->work_location, $approval_type_id, '', trim($post['settlement_amount']), $lastId);


						return [
						    "status" => true,
						    "msg"    => "Data berhasil disimpan"
						];

					}else{
						return [
						    "status" => false,
						    "msg"    => "Data gagal disimpan"
						];
					}
				}else{
					
					return [
					    "status" => false,
					    "msg"    => "Work Location not found"
					];
				}
			}else{
				
				return [
				    "status" => false,
				    "msg"    => "Employee not found"
				];
			}

  		}else return null;

	}  

	public function edit_data($post) { 

		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$id = $post['id'];

		if(!empty($post['id'])){ 

			if($post['action_type'] == 'approval'){ 
				$approval_matrix_type_id = 3; //settlement
				$getCurrApp = $this->db->query("select * from approval_path where approval_matrix_type_id = ".$approval_matrix_type_id." and trx_id = '".$id."' ")->result();
				$approval_level="";
				if(!empty($getCurrApp)){
					$approval_level = $getCurrApp[0]->current_approval_level;
				}

				$CurrApprovalId=""; $approval_path_id="";
				$CurrApproval = $this->getCurrApproval($id, $approval_level);
				if(!empty($CurrApproval)){
					$CurrApprovalId		= $CurrApproval[0]->id;
					$approval_path_id	= $CurrApproval[0]->approval_path_id;
				}

				$maxApproval = $this->getMaxApproval($id); 
				if($approval_level == $maxApproval){   //last approver
					$data = [
						'status_id'		=> 2, //approved
						'approval_date'	=> date("Y-m-d H:i:s")
					];
					
					$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
					if($rs){
						//update approval path
						if(!empty($CurrApprovalId)){
							$updApproval = [
								'status' 		=> "Approved",
								'approval_by' 	=> $karyawan_id,
								'approval_date'	=> date("Y-m-d H:i:s")
							];
							$this->db->update("approval_path_detail", $updApproval, "id = '".$CurrApprovalId."'");
						}

						return [
						    "status" => true,
						    "msg"    => "Data berhasil disimpan"
						];

					}else{
						return [
						    "status" => false,
						    "msg"    => "Data gagal disimpan"
						];
					}
					
					
				}else{
					$next_level = $approval_level+1;
					
					if(!empty($CurrApprovalId)){
						$data2 = [
							'current_approval_level' => $next_level
						];
						$rs = $this->db->update("approval_path", $data2, "id = '".$approval_path_id."'");
						
						if($rs){
							$data = [
								'status' 		=> "Approved",
								'approval_by' 	=> $karyawan_id,
								'approval_date'	=> date("Y-m-d H:i:s")
							];
							$this->db->update("approval_path_detail", $data, "id = '".$CurrApprovalId."'");

							$dataApprovalDetail = [
								'approval_path_id' 	=> $approval_path_id, 
								'approval_level' 	=> $next_level
							];
							$this->db->insert("approval_path_detail", $dataApprovalDetail);

							// send emailing to approver
							$this->approvalemailservice->sendApproval('settlement', $id, $approval_path_id);


							return [
							    "status" => true,
							    "msg"    => "Data berhasil disimpan"
							];

						}else{
							return [
							    "status" => false,
							    "msg"    => "Data gagal disimpan"
							];
						}
						
					}else{
						return [
						    "status" => false,
						    "msg"    => "Approver not found"
						];
					}
				}


			}else{ 
				

				$upload_doc = $this->upload_file('1', 'fpu_document', FALSE, '', TRUE);
				$document = '';
				if($upload_doc['status']){ 
					$document = $upload_doc['upload_file'];
				} else if(isset($upload_doc['error_warning'])){ 
					echo $upload_doc['error_warning']; exit;
				}
				$hdndoc = $post['hdndoc'];

				if($document == '' && $hdndoc != ''){
					$document = $hdndoc;
				}

				//bukti transfer
				$upload_doc_buktitransfer = $this->upload_file('1', 'bukti_transfer', FALSE, '', TRUE);
				$document_buktitransfer = '';
				if($upload_doc_buktitransfer['status']){ 
					$document_buktitransfer = $upload_doc_buktitransfer['upload_file'];
				} else if(isset($upload_doc_buktitransfer['error_warning'])){ 
					echo $upload_doc_buktitransfer['error_warning']; exit;
				}
				$hdndoc_buktitransfer = $post['hdndoc_buktitransfer'];

				if($document_buktitransfer == '' && $hdndoc_buktitransfer != ''){
					$document_buktitransfer = $hdndoc_buktitransfer;
				}


				$is_rfu=0; 
				$getdata = $this->db->query("select * from settlement where id = '".$post['id']."'")->result(); 
				if($getdata[0]->status_id == 4 && ($karyawan_id == $getdata[0]->prepared_by || $karyawan_id == $getdata[0]->requested_by)){ // edit RFU
					$is_rfu=1; 

					$data = [
						'requested_by'		=> trim($post['requested_by']),
						'total_cost' 		=> trim($post['total_cost_sett']),
						'settlement_amount' => trim($post['settlement_amount']),
						'document' 			=> $document,
						'updated_at'		=> date("Y-m-d H:i:s"),
						'status_id' 		=> 1,
						'bukti_transfer' 	=> $document_buktitransfer,
						'no_rekening' 		=> trim($post['no_rekening']),
						'nama_rekening' 	=> trim($post['nama_rekening']),
						'bank_rekening' 	=> trim($post['bank'])
					];
				}else{
					$data = [
						'requested_by'		=> trim($post['requested_by']),
						'total_cost' 		=> trim($post['total_cost_sett']),
						'settlement_amount' => trim($post['settlement_amount']),
						'document' 			=> $document,
						'updated_at'		=> date("Y-m-d H:i:s"),
						'bukti_transfer' 	=> $document_buktitransfer,
						'no_rekening' 		=> trim($post['no_rekening']),
						'nama_rekening' 	=> trim($post['nama_rekening']),
						'bank_rekening' 	=> trim($post['bank'])
					];
				}

				
				$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

				if($rs){
					/// update approval path
					$getapprovallevel = $this->db->query("select * from approval_path where approval_matrix_type_id = 3 and trx_id = '".$id."'")->result(); 
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



					if(isset($post['name'])){
						$item_num = count($post['name']); // cek sum
						$item_len_min = min(array_keys($post['name'])); // cek min key index
						$item_len = max(array_keys($post['name'])); // cek max key index
					} else {
						$item_num = 0;
					}

					if($item_num>0){
						for($i=$item_len_min;$i<=$item_len;$i++) 
						{
							$hdnid = trim($post['hdnid_sett'][$i]);

							if(!empty($hdnid)){ //update
								if(isset($post['name'][$i])){
									$itemData = [
										'post_budget_id'	=> trim($post['post_budget_sett'][$i]),
										'amount' 		=> trim($post['amount_sett'][$i]),
										'ppn_pph' 		=> trim($post['ppn_pph_sett'][$i]),
										'total_amount'	=> trim($post['total_amount_sett'][$i]),
										'notes' 		=> trim($post['notes_sett'][$i])
									];

									$this->db->update("settlement_details", $itemData, "id = '".$hdnid."'");
								}
							}else{ //insert
								if(isset($post['name'][$i])){
									$itemData = [
										'settlement_id' => $post['id'],
										'post_budget_id'	=> trim($post['post_budget_sett'][$i]),
										'amount' 		=> trim($post['amount_sett'][$i]),
										'ppn_pph' 		=> trim($post['ppn_pph_sett'][$i]),
										'total_amount'	=> trim($post['total_amount_sett'][$i]),
										'notes' 		=> trim($post['notes_sett'][$i])
									];

									$this->db->insert('settlement_details', $itemData);
								}
							}
						}
					}

					return [
					    "status" => true,
					    "msg"    => "Data berhasil disimpan"
					];
				}else{
					return [
					    "status" => false,
					    "msg"    => "Data gagal disimpan"
					];
				}

			}

		}else{
			return [
			    "status" => false,
			    "msg"    => "Data not found"
			];
		}

		
		
	}  

	public function getRowData($id) { 

		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;


		/*$mTable = '(select a.*, b.ca_number, c.full_name as prepared_by_name, d.full_name as requested_by_name, e.name as status_name, d.direct_id, b.total_cost as total_cost_ca
					from settlement a 
					left join cash_advance b on b.id = a.cash_advance_id
					left join employees c on c.id = a.prepared_by
					left join employees d on d.id = a.requested_by
					left join master_status_cashadvance e on e.id = a.status_id
					)dt';*/


		$mTable = '(select ao.* from (select a.*, b.full_name as prepared_by_name, c.full_name as requested_by_name
					, d.name as status_name, c.direct_id, ab.ca_number,
					max(d2.current_approval_level) AS current_approval_level,
					max(h.role_id) AS current_role_id,
					max(i.role_name) AS current_role_name,
					GROUP_CONCAT(g.employee_id) AS all_employeeid_approver,
					max(
						IF(
							i.role_name = "Direct",
							c.direct_id,
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
						WHEN max(i.role_name) = "Direct" AND max(c.direct_id) = '.$karyawan_id.' THEN 1  
						ELSE 0 
					END AS is_approver   
					from settlement a left join employees b on b.id = a.prepared_by
					left join cash_advance ab on ab.id = a.cash_advance_id
					left join employees c on c.id = a.requested_by
					left join master_status_cashadvance d on d.id = a.status_id
					LEFT JOIN approval_path d2 ON d2.trx_id = a.id AND d2.approval_matrix_type_id = 3
					LEFT JOIN approval_matrix bb ON bb.id = d2.approval_matrix_id
					LEFT JOIN approval_matrix_detail cc ON cc.approval_matrix_id = bb.id
					LEFT JOIN approval_matrix_role dd ON dd.id = cc.role_id
					LEFT JOIN approval_path_detail ee ON ee.approval_path_id = d2.id AND ee.approval_level = cc.approval_level
					LEFT JOIN approval_matrix_role_pic g ON g.approval_matrix_role_id = cc.role_id
					LEFT JOIN approval_matrix_detail h ON h.approval_matrix_id = d2.approval_matrix_id AND h.approval_level = d2.current_approval_level
					LEFT JOIN approval_matrix_role i ON i.id = h.role_id
					GROUP BY a.id)ao 
					where (ao.prepared_by = "'.$karyawan_id.'" or ao.requested_by = "'.$karyawan_id.'" or ao.direct_id = "'.$karyawan_id.'" or ao.is_approver_view = 1) 
					)dt';


		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;


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
				'settlement_number' => $v["B"],
				'settlement_date' 	=> $v["C"],
				'cash_advance_id' 	=> $v["D"],
				'prepared_by' 		=> $v["E"],
				'requested_by' 		=> $v["F"],
				'total_cost' 		=> $v["G"],
				'settlement_amount' => $v["H"],
				'status_id' 		=> $v["I"]
				
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
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr=' and (a.prepared_by = "'.$karyawan_id.'" or a.requested_by = "'.$karyawan_id.'" or d.direct_id = "'.$karyawan_id.'") ';
		}


		$sql = "select a.*, b.ca_number, c.full_name as prepared_by_name, d.full_name as requested_by_name, d.direct_id, e.name as status_name
				from settlement a 
				left join cash_advance b on b.id = a.cash_advance_id
				left join employees c on c.id = a.prepared_by
				left join employees d on d.id = a.requested_by
				left join master_status_cashadvance e on e.id = a.status_id
				".$whr."
				order by a.id asc

		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getNewSettRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getSettRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			$msPostbudget = $this->db->query("select * from master_post_budget")->result(); 
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid_sett'.$row.'" name="hdnid_sett['.$row.']" value=""/></td>';

			/*$data 	.= '<td>'.$this->return_build_txt('','name_sett['.$row.']','','name_sett','text-align: right;','data-id="'.$row.'" ').'</td>';*/
			$data 	.= '<td>'.$this->return_build_chosenme($msPostbudget,'','','','post_budget_sett['.$row.']','post_budget_sett','post_budget_sett','','id','name','','','',' data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','amount_sett['.$row.']','','amount_sett','text-align: right;','data-id="'.$row.'" onkeyup="set_total_amount_sett(this)" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ppn_pph_sett['.$row.']','','ppn_pph_sett','text-align: right;','data-id="'.$row.'" onkeyup="set_total_amount2_sett(this)" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','total_amount_sett['.$row.']','','total_amount_sett','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txtarea('','notes_sett['.$row.']','','notes_sett','text-align: right;','data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="btn btn-md btn-danger ibtnDel" onclick="del_sett(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getSettRows($id,$view,$print=FALSE){ 

		$dt = ''; 
		
		$data_sett = $this->db->query("select * from settlement where id = '".$id."' ")->result();
		if(!empty($data_sett)){ 
			$rs = $this->db->query("select a.*, b.name as post_budget_name from settlement_details a left join master_post_budget b on b.id = a.post_budget_id where a.settlement_id = '".$id."' ")->result(); 
		}else{ 
			$rs = $this->db->query("select a.*, b.name as post_budget_name from cash_advance_details a left join master_post_budget b on b.id = a.post_budget_id where a.cash_advance_id = '".$id."' ")->result(); 
		}
		
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				$msPostbudget = $this->db->query("select * from master_post_budget")->result(); 
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid_sett'.$row.'" name="hdnid_sett['.$row.']" value="'.$f->id.'"/></td>';

					/*$dt .= '<td>'.$this->return_build_txt($f->name,'name_sett['.$row.']','','name_sett','text-align: right;','data-id="'.$row.'" ').'</td>';*/
					$dt .= '<td>'.$this->return_build_chosenme($msPostbudget,'',isset($f->post_budget_id)?$f->post_budget_id:1,'','post_budget_sett['.$row.']','post_budget_sett','post_budget_sett','','id','name','','','',' data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->amount,'amount_sett['.$row.']','','amount_sett','text-align: right;','data-id="'.$row.'" onkeyup="set_total_amount_sett(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->ppn_pph,'ppn_pph_sett['.$row.']','','ppn_pph_sett','text-align: right;','data-id="'.$row.'" onkeyup="set_total_amount2_sett(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_amount,'total_amount_sett['.$row.']','','total_amount_sett','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txtarea($f->notes,'notes_sett['.$row.']','','notes_sett','text-align: right;','data-id="'.$row.'" ').'</td>';

					
					$dt .= '<td><input type="button" class="btn btn-md btn-danger ibtnDel" id="btndel" value="Delete" onclick="del_sett(\''.$row.'\',\''.$f->id.'\')"></td>';
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
					$dt .= '<td>'.$f->post_budget_name.'</td>';
					$dt .= '<td>'.$f->amount.'</td>';
					$dt .= '<td>'.$f->ppn_pph.'</td>';
					$dt .= '<td>'.$f->total_amount.'</td>';
					$dt .= '<td>'.$f->notes.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


	public function getDataCashadvance($id){ 

		$rs = $this->db->query("select * from cash_advance where id = '".$id."' ")->result(); 


		return $rs;

	}


	public function getMaxApproval($trx_id){ 
		$post 		= $this->input->post(null, true);
		

		$approval_matrix_type_id = 3; //settlement
		$rs =  $this->db->query("select b.*, a.current_approval_level, c.role_name from approval_path a 
				left join approval_matrix_detail b on b.approval_matrix_id = a.approval_matrix_id
				left join approval_matrix_role c on c.id = b.role_id
				where approval_matrix_type_id = ".$approval_matrix_type_id." and trx_id = ".$trx_id." 
				order by b.approval_level desc limit 1 ")->result();
		

		return $rs[0]->approval_level;
	}

	public function getCurrApproval($trx_id, $approval_level){
		$post 		= $this->input->post(null, true);
		

		$approval_matrix_type_id = 3; //settlement

		
		$rs =  $this->db->query("select b.* from approval_path a left join approval_path_detail b on b.approval_path_id = a.id and approval_level = ".$approval_level." where a.approval_matrix_type_id = ".$approval_matrix_type_id." and a.trx_id = ".$trx_id."")->result();
		

		return $rs;
	}



}