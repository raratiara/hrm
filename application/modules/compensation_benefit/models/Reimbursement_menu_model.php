<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reimbursement_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "compensation_benefit/reimbursement_menu";
 	protected $table_name 				= _PREFIX_TABLE."medicalreimbursements";
 	protected $primary_key 				= "id";

 	/* upload */
 	protected $attachment_folder	= "./uploads/reimbursement";
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
			'dt.date_reimbursment',
			'dt.employee_name',
			'dt.reimburse_for_name',
			'dt.atas_nama',
			'dt.diagnosa',
			'dt.nominal_billing',
			'dt.nominal_reimburse',
			'dt.status_name',
			'dt.direct_id',
			'dt.current_approval_level',
			'dt.is_approver',
			'dt.current_role_id',
			'dt.current_role_name',
			'dt.is_approver_view',
			'dt.employee_id'
		];
		
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			/*$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';*/

			$whr=' where ao.employee_id = "'.$karyawan_id.'" or ao.direct_id = "'.$karyawan_id.'" or ao.is_approver_view = 1 ';
		}
		

		$sIndexColumn = $this->primary_key;
		/*$sTable = '(select a.*, b.full_name as employee_name, c.name as reimburse_for_name,
					(case 
						when a.status_id = 1 then "Waiting Approval"
					    when a.status_id = 2 then "Approved"
					    when a.status_id = 3 then "Rejected"
					    else ""
					end) as status_name, b.direct_id
					from medicalreimbursements a left join employees b on b.id = a.employee_id
					left join master_reimbursfor_type c on c.id = a.reimburse_for
					'.$whr.'
				)dt';*/


		$sTable = '(select ao.* from (select a.*, b.full_name as employee_name, c.name as reimburse_for_name,
						(case 
							when a.status_id = 1 then "Waiting Approval"
							when a.status_id = 2 then "Approved"
							when a.status_id = 3 then "Rejected"
							when a.status_id = 4 then "Request for Update"
							else ""
						end) as status_name, b.direct_id,
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
						from medicalreimbursements a left join employees b on b.id = a.employee_id
						left join master_reimbursfor_type c on c.id = a.reimburse_for
						LEFT JOIN approval_path d2 ON d2.trx_id = a.id AND d2.approval_matrix_type_id = 4
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
				$reject = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;"  href="javascript:void(0);" onclick="reject('."'".$row->id."'".','."'".$row->current_approval_level."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" style="background-color: #2c9e1fff; border-color: #2c9e1fff;" href="javascript:void(0);" onclick="approve('."'".$row->id."'".','."'".$row->current_approval_level."'".')" role="button"><i class="fa fa-check"></i></a>';
				$rfu = '<a class="btn btn-xs btn-warning" style="background-color: #fd9b00; border-color: #fd9b00;" href="javascript:void(0);" onclick="rfu('."'".$row->id."'".','."'".$row->current_approval_level."'".')" role="button">RFU</a>';
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
				$row->date_reimbursment,
				$row->employee_name,
				$row->reimburse_for_name,
				$row->atas_nama,
				$row->diagnosa,
				$row->nominal_billing,
				$row->nominal_reimburse,
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

	public function get_sisa_plafon($emp_id, $type_id){
		$year = date("Y");

		$getplafon = $this->db->query("select a.id, b.nominal_plafon, b.reimburs_type_id 
				from employees a left join master_plafon b on b.grade_id = a.grade_id and b.reimburs_type_id = '".$type_id."' where a.id = '".$emp_id."' ")->result(); 
		$plafon=0;
		if($getplafon != ''){
			$plafon = $getplafon[0]->nominal_plafon;
		}

		$getpemakaian = $this->db->query("select sum(nominal_reimburse) as total_pemakaian from medicalreimbursements where employee_id = '".$emp_id."' and reimburs_type_id = '".$type_id."' and (DATE_FORMAT(date_reimbursment, '%Y')) = '".$year."' ")->result(); 
		$pemakaian=0;
		if($getpemakaian != ''){
			$pemakaian = $getpemakaian[0]->total_pemakaian;
		}

		$sisa = $plafon-$pemakaian;
		if($sisa <= 0){
			$sisa=0;
		} 




		return $sisa;
	}


	// Get next number 
	public function getNextNumber() { 
		
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		

		$cek = $this->db->query("select * from medicalreimbursements where SUBSTRING(reimburs_no, 4, 4) = '".$period."'");
		$rs_cek = $cek->result_array();

		if(empty($rs_cek)){
			$num = '0001';
		}else{
			$cek2 = $this->db->query("select max(reimburs_no) as maxnum from medicalreimbursements where SUBSTRING(reimburs_no, 4, 4) = '".$period."'");
			$rs_cek2 = $cek2->result_array();
			$dt = $rs_cek2[0]['maxnum']; 
			$getnum = substr($dt,7); 
			$num = str_pad($getnum + 1, 4, 0, STR_PAD_LEFT);
			
		}

		return $num;
		
	} 


	public function getApprovalMatrix($work_location_id, $approval_type_id, $leave_type_id='', $amount='', $trx_id){

		if($work_location_id != '' && $approval_type_id != ''){
			if($approval_type_id == 4){ ///reimbursement
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
							$this->approvalemailservice->sendApproval('reimbursement', $trx_id, $approval_path_id);
						}
					}
				}
			}
		}

	}



	public function add_data($post) { 

		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;


		$date 		= date_create($post['date']); 
		$f_date 	= date_format($date,"Y-m-d H:i:s");


		$lettercode = ('RMB'); // code
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		
		$runningnumber = $this->getNextNumber(); // next count number
		$nextnum 	= $lettercode.'/'.$period.'/'.$runningnumber;


		$sisa_plafon = $this->get_sisa_plafon($post['employee'], $post['type']);

		
  		if(!empty($post['date']) && !empty($post['employee'])){ 
  			if($post['nominal_reimburs'] <= $sisa_plafon){ //jika masih ada plafon
  				$dataEmp = $this->db->query("select * from employees where id = '".$post['employee']."'")->result(); 
				if(!empty($dataEmp)){
					if(!empty($dataEmp[0]->work_location)){

						$data = [
							'reimburs_no' 			=> $nextnum,
							'date_reimbursment' 	=> $f_date,
							'employee_id' 			=> trim($post['employee']),
							'reimburs_type_id' 		=> trim($post['type']),
							'reimburse_for'			=> trim($post['reimburs_for']),
							'atas_nama' 			=> trim($post['atas_nama']),
							'diagnosa' 				=> trim($post['diagnosa']),
							'nominal_billing' 		=> trim($post['nominal_billing']),
							'nominal_reimburse' 	=> trim($post['nominal_reimburs']), 
							'created_at'			=> date("Y-m-d H:i:s"),
							'status_id' 			=> 1 //waiting approval
						];
						$rs = $this->db->insert($this->table_name, $data);
						$lastId = $this->db->insert_id();

						if($rs){
							///insert approval path
							$approval_type_id = 4; //Reimbursement
							$this->getApprovalMatrix($dataEmp[0]->work_location, $approval_type_id, '', trim($post['nominal_reimburs']), $lastId);


							if(isset($post['subtype'])){
								$item_num = count($post['subtype']); // cek sum
								$item_len_min = min(array_keys($post['subtype'])); // cek min key index
								$item_len = max(array_keys($post['subtype'])); // cek max key index
							} else {
								$item_num = 0;
							}

							if($item_num>0){
								for($i=$item_len_min;$i<=$item_len;$i++) 
								{
									$upload_emp_photo = $this->upload_file('1', 'document'.$i.'', FALSE, '', TRUE, $i);
									$document = '';
									if($upload_emp_photo['status']){ 
										$document = $upload_emp_photo['upload_file'];
									} else if(isset($upload_emp_photo['error_warning'])){ 
										echo $upload_emp_photo['error_warning']; exit;
									}

									if(isset($post['subtype'][$i])){
										$itemData = [
											'reimbursement_id' 	=> $lastId,
											'subtype_id' 		=> trim($post['subtype'][$i]),
											'document' 			=> $document,
											'biaya' 			=> trim($post['biaya'][$i]),
											'notes' 			=> trim($post['notes'][$i])
										];

										$this->db->insert('reimbursement_detail', $itemData);
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

  			}else{
  				echo 'Tidak ada sisa plafon reimburs'; die();
  			}

  		}else return null;

	}  

	public function edit_data($post) { 

		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$id = trim($post['id']);

		$date 		= date_create($post['date']); 
		$f_date 	= date_format($date,"Y-m-d H:i:s");
		
		$sisa_plafon = $this->get_sisa_plafon($post['employee'], $post['type']);


		if(!empty($post['id'])){ 
			$is_rfu=0;
			$getdata = $this->db->query("select * from medicalreimbursements where id = '".$post['id']."' ")->result(); 

			$curr_nominal_reimburs = $getdata[0]->nominal_reimburse;
			$sisa_plafon = $sisa_plafon+$curr_nominal_reimburs;

			if($post['nominal_reimburs'] <= $sisa_plafon){ //jika masih ada plafon
				if($getdata[0]->status_id == 4 && $karyawan_id == $getdata[0]->employee_id){ // edit RFU
					$is_rfu=1;

					$data = [
						'date_reimbursment' 	=> $f_date,
						'employee_id' 			=> trim($post['employee']),
						'reimburs_type_id' 		=> trim($post['type']),
						'reimburse_for'			=> trim($post['reimburs_for']),
						'atas_nama' 			=> trim($post['atas_nama']),
						'diagnosa' 				=> trim($post['diagnosa']),
						'nominal_billing' 		=> trim($post['nominal_billing']),
						'nominal_reimburse' 	=> trim($post['nominal_reimburs']),
						'updated_at'			=> date("Y-m-d H:i:s"),
						'status_id' 			=> 1
					];
				}else{
					$data = [
						'date_reimbursment' 	=> $f_date,
						'employee_id' 			=> trim($post['employee']),
						'reimburs_type_id' 		=> trim($post['type']),
						'reimburse_for'			=> trim($post['reimburs_for']),
						'atas_nama' 			=> trim($post['atas_nama']),
						'diagnosa' 				=> trim($post['diagnosa']),
						'nominal_billing' 		=> trim($post['nominal_billing']),
						'nominal_reimburse' 	=> trim($post['nominal_reimburs']),
						'updated_at'			=> date("Y-m-d H:i:s")
					];
				}

				$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

				if($rs){

					/// update approval path
					$getapprovallevel = $this->db->query("select * from approval_path where approval_matrix_type_id = 4 and trx_id = '".$id."'")->result(); 
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



					if(isset($post['subtype'])){
						$item_num = count($post['subtype']); // cek sum
						$item_len_min = min(array_keys($post['subtype'])); // cek min key index
						$item_len = max(array_keys($post['subtype'])); // cek max key index
					} else {
						$item_num = 0;
					}

					if($item_num>0){
						for($i=$item_len_min;$i<=$item_len;$i++) 
						{
							$hdnid = trim($post['hdnid'][$i]);

							if(!empty($hdnid)){ //update

								$hdndocument = trim($post['hdndocument'.$i]);
								$document = '';
								$upload_emp_photo = $this->upload_file('1', 'document'.$i.'', FALSE, '', TRUE, $i);
								if($upload_emp_photo['status']){ 
									$document = $upload_emp_photo['upload_file'];
								} else if(isset($upload_emp_photo['error_warning'])){ 
									echo $upload_emp_photo['error_warning']; exit;
								}

								if($document == '' && $hdndocument != ''){
									$document = $hdndocument;
								}

								if(isset($post['subtype'][$i])){
									$itemData = [
										'subtype_id' 		=> trim($post['subtype'][$i]),
										'document' 			=> $document,
										'biaya' 			=> trim($post['biaya'][$i]),
										'notes' 			=> trim($post['notes'][$i])
									];

									$this->db->update("reimbursement_detail", $itemData, "id = '".$hdnid."'");
								}

							}else{ //insert

								$upload_emp_photo = $this->upload_file('1', 'document'.$i.'', FALSE, '', TRUE, $i);
								$document = '';
								if($upload_emp_photo['status']){ 
									$document = $upload_emp_photo['upload_file'];
								} else if(isset($upload_emp_photo['error_warning'])){ 
									echo $upload_emp_photo['error_warning']; exit;
								}

								if(isset($post['subtype'][$i])){
									$itemData = [
										'reimbursement_id' 	=> $post['id'],
										'subtype_id' 		=> trim($post['subtype'][$i]),
										'document' 			=> $document,
										'biaya' 			=> trim($post['biaya'][$i]),
										'notes' 			=> trim($post['notes'][$i])
									];

									$this->db->insert('reimbursement_detail', $itemData);
								}

							}
							
						}
					}
				}

				return $rs;

			}else{
				return null;
			}

		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.full_name as employee_name, c.name as reimburse_for_name,
						d.name as reimburs_type_name
						from medicalreimbursements a left join employees b on b.id = a.employee_id
						left join master_reimbursfor_type c on c.id = a.reimburse_for 
					    left join master_reimburs_type d on d.id = a.reimburs_type_id
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
				'date_reimbursment' 	=> $v["B"],
				'employee_id' 			=> $v["C"],
				'reimburse_for' 		=> $v["D"],
				'atas_nama' 			=> $v["E"],
				'diagnosa' 				=> $v["F"],
				'nominal_billing' 		=> $v["G"],
				'nominal_reimburse' 	=> $v["H"]
				
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
			$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}



		$sql = "select a.id, a.date_reimbursment, b.full_name as employee_name, c.name as reimburse_for_name, a.atas_nama, a.diagnosa, a.nominal_billing, a.nominal_reimburse,
			(case 
				when a.status_id = 1 then 'Waiting Approval'
			    when a.status_id = 2 then 'Approved'
			    when a.status_id = 3 then 'Rejected'
			    else ''
			end) as status_name
			from medicalreimbursements a left join employees b on b.id = a.employee_id 
			left join master_reimbursfor_type c on c.id = a.reimburse_for 
			".$whr." order by a.id asc
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getNewExpensesRow($row,$type,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getExpensesRows($type,$id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			$msSubtype = $this->db->query("select * from master_reimburs_subtype where reimburs_type_id = '".$type."'")->result(); 
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value=""/></td>';
			$data 	.= '<td>'.$this->return_build_chosenme($msSubtype,'','','','subtype['.$row.']','subtype','subtype','','id','name','','','',' data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_fileinput('document'.$row.'','','','document','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','notes['.$row.']','','notes','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','biaya['.$row.']','','biaya','text-align: right;','data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getExpensesRows($type,$id,$view,$print=FALSE){ 
		$uri = $_SERVER['REQUEST_URI'];
	 	$xpl = explode("/",$uri);
	 	$url = $_SERVER['SERVER_NAME'].'/'.$xpl[1].'/uploads/reimbursement';


		$dt = ''; 
		
		$rs = $this->db->query("select a.*, b.name as subtype_name from reimbursement_detail a 
								left join master_reimburs_subtype b on b.id = a.subtype_id where a.reimbursement_id = '".$id."' ")->result(); 
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
				$msSubtype = $this->db->query("select * from master_reimburs_subtype where reimburs_type_id = '".$type."'")->result(); 

				if(!$view){ 
					$viewdoc = '';
					if($f->document != ''){
						$viewdoc = '<a href="'.base_url().'uploads/reimbursement/'.$f->document.'" target="_blank">View</a>';
					}

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';
					$dt .= '<td>'.$this->return_build_chosenme($msSubtype,'',isset($f->subtype_id)?$f->subtype_id:1,'','subtype['.$row.']','subtype','subtype','','id','name','','','',' data-id="'.$row.'" ').'</td>';
				
					$dt .= '<td>'.$this->return_build_fileinput('document'.$row.'','','','document','text-align: right;','data-id="'.$row.'" ').$viewdoc.' <input type="hidden" id="hdndocument'.$row.'" name="hdndocument'.$row.'" value="'.$f->document.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->notes,'notes['.$row.']','','notes','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->biaya,'biaya['.$row.']','','biaya','text-align: right;','data-id="'.$row.'" ').'</td>';
					
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
					
					$dt .= '<td>'.$no.'</td>';
					$dt .= '<td>'.$f->subtype_name.'</td>';
					$dt .= '<td><a href="'.base_url().'uploads/reimbursement/'.$f->document.'" target="_blank">View</a></td>';
					$dt .= '<td>'.$f->notes.'</td>';
					$dt .= '<td>'.$f->biaya.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


	public function getDataSubtype($type){ 

		$rs = $this->db->query("select * from master_reimburs_subtype where reimburs_type_id = '".$type."' ")->result(); 

		$data['mssubtype'] = $rs;


		return $data;

	}



	public function getCurrApproval($trx_id, $approval_level){
		$post 		= $this->input->post(null, true);
		

		$approval_matrix_type_id = 4; //settlement

		
		$rs =  $this->db->query("select b.* from approval_path a left join approval_path_detail b on b.approval_path_id = a.id and approval_level = ".$approval_level." where a.approval_matrix_type_id = ".$approval_matrix_type_id." and a.trx_id = ".$trx_id."")->result();
		

		return $rs;
	}


}