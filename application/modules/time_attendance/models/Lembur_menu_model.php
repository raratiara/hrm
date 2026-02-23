<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lembur_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "time_attendance/lembur_menu";
 	protected $table_name 				= _PREFIX_TABLE."overtimes";
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
			'dt.date_overtime',
			'dt.full_name',
			'dt.datetime_start',
			'dt.datetime_end',
			'dt.num_of_hour',
			'dt.amount',
			'dt.reason',
			'dt.status_name',
			'dt.direct_id',
			'dt.type_name',
			'dt.count_day',
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
			$whr=' where ao.employee_id = "'.$karyawan_id.'" or ao.direct_id = "'.$karyawan_id.'" or ao.is_approver_view = 1  ';
		}


		$sIndexColumn = $this->primary_key;
		/*$sTable = '(select a.*, b.full_name,  b.direct_id,
					(case 
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					end) as status_name,
					(case 
					when a.type = 1 then "Lembur Hari Kerja"
					when a.type = 2 then "Kerja di Hari Libur"
					else ""
					end) as type_name  
					from overtimes a left join employees b on b.id = a.employee_id
					'.$whr.'
				)dt';*/


		$sTable = '(select ao.* from (select a.*, b.full_name,  b.direct_id,
					(case 
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					end) as status_name,
					(case 
					when a.type = 1 then "Lembur Hari Kerja"
					when a.type = 2 then "Kerja di Hari Libur"
					else ""
					end) as type_name,
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
					from overtimes a left join employees b on b.id = a.employee_id
				    LEFT JOIN approval_path d2 ON d2.trx_id = a.id AND (d2.approval_matrix_type_id = 5 or d2.approval_matrix_type_id = 6)
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

		$getdirect = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$direct_karyawan_id = $getdirect[0]->id_karyawan;

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
				if($row->status_name == 'Waiting Approval' && $karyawan_id == $row->employee_id){
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
			/*if($row->status_name == 'Waiting Approval' && $row->direct_id == $direct_karyawan_id){*/
			if($row->status_name == 'Waiting Approval' && $is_approver == 1){
			
				$reject = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="reject('."'".$row->id."'".','."'".$row->current_approval_level."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" style="background-color: #2c9e1fff; border-color: #2c9e1fff;" href="javascript:void(0);" onclick="approve('."'".$row->id."'".','."'".$row->current_approval_level."'".')" role="button"><i class="fa fa-check"></i></a>';
			}
			

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$reject.'
					'.$approve.'
				</div>',
				$row->id,
				$row->type_name,
				$row->full_name,
				$row->datetime_start,
				$row->datetime_end,
				$row->num_of_hour,
				$row->amount,
				$row->count_day,
				$row->reason,
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

	public function dayCount($from, $to) {
	    $first_date = strtotime($from);
	    $second_date = strtotime($to);
	    $days_diff = $second_date - $first_date;
	    return date('d',$days_diff);
	}


	public function getApprovalMatrix($work_location_id, $approval_type_id, $type='', $diff_day='', $trx_id){

		if($work_location_id != '' && $approval_type_id != ''){
			if($approval_type_id == 5 || $approval_type_id == 6){ ///Overtime Lembur Hari Kerja/Kerja di Hari Libur
				
					if($diff_day == ''){
						$diff_day=0;
					}
					
					$getmatrix = $this->db->query("select * from approval_matrix where approval_type_id = '".$approval_type_id."' and work_location_id = '".$work_location_id."' and (
							(".$diff_day." >= min and ".$diff_day." <= max and min != '' and max != '') or
							(".$diff_day." >= min and min != '' and max = '') or
							(".$diff_day." <= max and max != '' and min = '')
						)  ")->result(); 

					if(empty($getmatrix)){ 
						
						$getmatrix = $this->db->query("select * from approval_matrix where approval_type_id = '".$approval_type_id."' and work_location_id = '".$work_location_id."' and ((min is null or min = '') and (max is null or max = ''))  ")->result(); 
					}

					
					if(!empty($getmatrix)){
						$approvalMatrixId = $getmatrix[0]->id;
						if($approvalMatrixId != ''){
							$dataApproval = [
								'approval_matrix_type_id' 	=> $approval_type_id, //Absence
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
								$this->approvalemailservice->sendApproval('overtimes', $trx_id, $approval_path_id);
							}
						}
					}

				
			}

		}

	}


	public function add_data($post) { 

		if($post['type'] != '' && $post['employee'] != '' && $post['datetime_start'] != '' && $post['datetime_end'] != ''){

			$dataEmp = $this->db->query("select * from employees where id = '".$post['employee']."'")->result(); 
			if(!empty($dataEmp)){
				if(!empty($dataEmp[0]->work_location)){
					if($post['type'] == '1'){ //lembur hari kerja

						$datetime_start = date('Y-m-d H:i:s', strtotime($post['datetime_start']));
						$datetime_end = date('Y-m-d H:i:s', strtotime($post['datetime_end']));
						/*$date_overtime = date('Y-m-d', strtotime($post['date']));*/

						$start = strtotime($datetime_start);
						$end = strtotime($datetime_end);

						$selisihDetik = $end - $start;
						$num_of_hour = floor($selisihDetik / 3600);
						/*$menit = floor(($selisihDetik % 3600) / 60);*/

						$biaya='50000';
						$amount = $num_of_hour*$biaya;


						$data = [
							/*'date_overtime' 			=> $date_overtime,*/
							'type' 						=> trim($post['type']),
							'employee_id' 				=> trim($post['employee']),
							'datetime_start' 			=> $datetime_start,
							'datetime_end' 				=> $datetime_end,
							'num_of_hour' 				=> $num_of_hour,
							'amount' 					=> $amount,
							'reason' 					=> trim($post['reason']),
							'status_id' 				=> 1,
							'created_at'				=> date("Y-m-d H:i:s")
						];

						$diff = $num_of_hour;
					}else if($post['type'] == '2'){ //masuk di hari libur

						$datetime_start = date('Y-m-d', strtotime($post['datetime_start']));
						$datetime_end = date('Y-m-d', strtotime($post['datetime_end']));

						$count_day = $this->dayCount($datetime_start, $datetime_end);
						$data = [
							/*'date_overtime' 			=> $date_overtime,*/
							'type' 						=> trim($post['type']),
							'employee_id' 				=> trim($post['employee']),
							'datetime_start' 			=> $datetime_start,
							'datetime_end' 				=> $datetime_end,
							'count_day' 				=> $count_day,
							'reason' 					=> trim($post['reason']),
							'status_id' 				=> 1,
							'created_at'				=> date("Y-m-d H:i:s")
						];

						$diff = $count_day;
					}
					
					
					$rs = $this->db->insert($this->table_name, $data);
					$lastId = $this->db->insert_id();

					if($rs){
						///insert approval path
						if($post['type'] == 1){
							$approval_type_id = 5; //Overtime - Lembur Hari Kerja
						}else{
							$approval_type_id = 6; //Overtime - Kerja di Hari Libur
						}
						
						$this->getApprovalMatrix($dataEmp[0]->work_location, $approval_type_id, '', $diff, $lastId);

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

			

		}else{
			return [
			    "status" => false,
			    "msg"    => "Type, Employee, Date start & end must be filled"
			];
		}
		
	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){

			if($post['type'] != '' && $post['employee'] != '' && $post['datetime_start'] != '' && $post['datetime_end'] != ''){

				if($post['type'] == '1'){ //lembur hari kerja

					$datetime_start = date('Y-m-d H:i:s', strtotime($post['datetime_start']));
					$datetime_end = date('Y-m-d H:i:s', strtotime($post['datetime_end']));
					/*$date_overtime = date('Y-m-d', strtotime($post['date']));*/

					$start = strtotime($datetime_start);
					$end = strtotime($datetime_end);

					$selisihDetik = $end - $start;
					$num_of_hour = floor($selisihDetik / 3600);
					/*$menit = floor(($selisihDetik % 3600) / 60);*/

					$biaya='50000';
					$amount = $num_of_hour*$biaya;

					$data = [
						/*'date_overtime' 			=> $date_overtime,*/
						'employee_id' 				=> trim($post['employee']),
						'datetime_start' 			=> $datetime_start,
						'datetime_end' 				=> $datetime_end,
						'num_of_hour' 				=> $num_of_hour,
						'amount' 					=> $amount,
						'reason' 					=> trim($post['reason']),
						'updated_at'				=> date("Y-m-d H:i:s")
					];
				}else if($post['type'] == '2'){ //masuk di hari libur
					$datetime_start = date('Y-m-d', strtotime($post['datetime_start']));
					$datetime_end = date('Y-m-d', strtotime($post['datetime_end']));

					$count_day = $this->dayCount($datetime_start, $datetime_end);
					$data = [
						/*'date_overtime' 			=> $date_overtime,*/
						'employee_id' 				=> trim($post['employee']),
						'datetime_start' 			=> $datetime_start,
						'datetime_end' 				=> $datetime_end,
						'count_day' 				=> $count_day,
						'reason' 					=> trim($post['reason']),
						'updated_at'				=> date("Y-m-d H:i:s"),
						'status_dayoff_available' 	=> 1 //available
					];
				}

			
				
				$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

				if($rs){
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
			else{
				return [
				    "status" => false,
				    "msg"    => "Type, Employee, Date start & end must be filled"
				];
			}

		} else{
			return [
			    "status" => false,
			    "msg"    => "Data not found"
			];
		}
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.full_name, b.direct_id,
					(case 
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					end) as status_name,
					(case 
					when a.type = 1 then "Lembur Hari Kerja"
					when a.type = 2 then "Kerja di Hari Libur"
					else ""
					end) as type_name  
					from overtimes a left join employees b on b.id = a.employee_id

			)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		
		
		return $rs;
	} 

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$timestamp1 = strtotime($v["D"]); //Y-m-d H:i:s
			$timestamp2 = strtotime($v["E"]); //Y-m-d H:i:s

			
			$num_of_hour= abs($timestamp2 - $timestamp1); //jam
			$biaya='50000';
			$amount = $num_of_hour*$biaya;

			$data = [
				'date_overtime' 	=> $v["B"],
				'employee_id' 		=> $v["C"],
				'datetime_start' 	=> $v["D"],
				'datetime_end' 		=> $v["E"],
				'num_of_hour' 		=> $num_of_hour,
				'amount' 			=> $amount,
				'reason' 			=> $v["F"],
				'created_at' 		=> $v["G"],
				'status_id' 		=> $v["H"]
				
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



		$sql = 'select a.id, a.date_overtime, b.full_name, a.datetime_start, a.datetime_end, a.num_of_hour, a.amount,a.reason, a.count_day,
				(case 
				when a.status_id = 1 then "Waiting Approval"
				when a.status_id = 2 then "Approved"
				when a.status_id = 3 then "Rejected"
				else ""
				end) as status_name,
				(case 
					when a.type = 1 then "Lembur Hari Kerja"
					when a.type = 2 then "Kerja di Hari Libur"
					else ""
					end) as type_name 
				from overtimes a left join employees b on b.id = a.employee_id
				'.$whr.'
				order by a.id asc
		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}




}
