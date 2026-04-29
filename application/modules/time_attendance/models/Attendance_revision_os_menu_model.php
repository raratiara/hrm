<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_revision_os_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "time_attendance/attendance_revision_os_menu";
 	protected $table_name 				= _PREFIX_TABLE."time_attendances_revision";
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
			'id',
			'dt.full_name',
			'dt.date_attendance',
			'dt.status',
			'dt.is_approver',
			'dt.current_approval_level'
		];
		
		
		$karyawan_id = $_SESSION['worker'];
		$whr='';
		if($_SESSION['role'] != 1 && $_SESSION['role'] != 4){ //bukan super user && bukan HR admin
			//$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
			$whr=' where ao.employee_id = "'.$karyawan_id.'" or ao.direct_id = "'.$karyawan_id.'" or ao.is_approver_view = 1 ';
		}
		

		$sIndexColumn = $this->primary_key;
		
			$sTable = '(SELECT ao.* 
						FROM (
						    SELECT 
						        a.id,
						        a.date_attendance,
						        a.employee_id,
						        a.attendance_type,
						        a.time_in,
						        a.time_out,
						        a.date_attendance_in,
						        a.date_attendance_out,
						        a.is_late,
						        a.is_leaving_office_early,
						        a.num_of_working_hours, a.attachment, a.created_at, a.created_by, a.description,
						        b.full_name, 
						        (CASE
						            WHEN a.status_approval = 1 THEN "Waiting Approval"
						            WHEN a.status_approval = 2 THEN "Approved"
						            WHEN a.status_approval = 3 THEN "Rejected"
						        END) AS status,
						        max(b.direct_id) AS direct_id,
						        max(d.current_approval_level) AS current_approval_level,
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
						    FROM time_attendances_revision a
						    LEFT JOIN employees b ON b.id = a.employee_id
						    LEFT JOIN approval_path d ON d.trx_id = a.id AND d.approval_matrix_type_id = 13
						    LEFT JOIN approval_matrix bb ON bb.id = d.approval_matrix_id
						    LEFT JOIN approval_matrix_detail cc ON cc.approval_matrix_id = bb.id
						    LEFT JOIN approval_matrix_role dd ON dd.id = cc.role_id
						    LEFT JOIN approval_path_detail ee ON ee.approval_path_id = d.id AND ee.approval_level = cc.approval_level
						    LEFT JOIN approval_matrix_role_pic g ON g.approval_matrix_role_id = cc.role_id
						    LEFT JOIN approval_matrix_detail h ON h.approval_matrix_id = d.approval_matrix_id AND h.approval_level = d.current_approval_level
						    LEFT JOIN approval_matrix_role i ON i.id = h.role_id
						    where b.emp_source = "outsource"
							GROUP BY a.id
						) ao
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

		
		$direct_karyawan_id = $_SESSION['worker'];

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
			/*if($row->current_role_name == 'Direct'){
				if($row->direct_id == $direct_karyawan_id){
					$is_approver = 1;
				}
			}else{
				if($row->is_approver == 1){
					$is_approver = 1;
				}
			}*/
			if($row->is_approver == 1){
				$is_approver = 1;
			}


			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #112D80; border-color: #112D80;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1" && $row->status == 'Waiting Approval')  {
				
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}

			$reject=""; 
			$approve="";
			// if($row->status == 'Waiting Approval' && $row->direct_id == $direct_karyawan_id){
			if($row->status == 'Waiting Approval' && $is_approver == 1){
				/*$reject = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="reject('."'".$row->id."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" href="javascript:void(0);" onclick="approve('."'".$row->id."'".')" role="button"><i class="fa fa-check"></i></a>';*/

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
				$row->full_name,
				$row->date_attendance,
				$row->status

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


	public function cek_ttl_dayoff($employee_id){
		$overtimes = $this->db->query("select 
								    employee_id,
								    SUM(count_day - COALESCE(ttl_dayoff_used, 0)) AS total_sisa_dayoff
								FROM overtimes
								WHERE type = 2 
								  AND status_id = 2
								  AND employee_id = '".$employee_id."'
								GROUP BY employee_id;
								")->result(); 

		if(!empty($overtimes)){
			return $overtimes[0]->total_sisa_dayoff;
		}else return 0;
		
	}

	public function update_table_overtimes($employee, $diff_day){

		$tmp_ttl_pengajuan = $diff_day;
		$dataOvertimes = $this->db->query("select * from overtimes where type = 2 and employee_id = '".$employee."' and status_id = 2 and status_dayoff_available = 1")->result(); 

		if($tmp_ttl_pengajuan != 0){
			foreach($dataOvertimes as $rowOvertimes){
				$count_day 	= $rowOvertimes->count_day;
				$kuota 		= $rowOvertimes->count_day-$rowOvertimes->ttl_dayoff_used;
				
				if($tmp_ttl_pengajuan > $kuota){ 
					$ttl_dayoff_used = $kuota;
				}else{ 
					$ttl_dayoff_used = $tmp_ttl_pengajuan;
				}
				$sumdayoff = $rowOvertimes->ttl_dayoff_used+$ttl_dayoff_used;

				$status_dayoff_available='';
				if($count_day == $sumdayoff){
					$status_dayoff_available=0;
				}
				
				if($status_dayoff_available==0){
					$dataUpd = [
						'ttl_dayoff_used' 			=> $sumdayoff,
						'status_dayoff_available' 	=> $status_dayoff_available
					];
					$this->db->update('overtimes', $dataUpd, "id = '".$rowOvertimes->id."'");
				}else{
					$dataUpd = [
						'ttl_dayoff_used' => $sumdayoff
					];
					$this->db->update('overtimes', $dataUpd, "id = '".$rowOvertimes->id."'");
				}
				
				$tmp_ttl_pengajuan = $tmp_ttl_pengajuan-$ttl_dayoff_used;
				

			}
		}
		
	}


	public function pengembalian_jatah_dayoff($employee, $diff_day){

		$ttl_pengembalian = $diff_day;
		$dataOvertimes = $this->db->query("select * from overtimes where type = 2 and employee_id = '".$employee."' and status_id = 2 order by id desc")->result(); 
		foreach($dataOvertimes as $rowOvertimes){
			$kuota = $rowOvertimes->count_day;
			$curr_ttl_dayoff_used = $rowOvertimes->ttl_dayoff_used;
			
			if($ttl_pengembalian > $kuota){
				$yg_sudah_dikembalikan = $ttl_pengembalian-$kuota;
			}else{
				$yg_sudah_dikembalikan = $ttl_pengembalian;
			}
			$sisa_pengembalian = $ttl_pengembalian-$yg_sudah_dikembalikan;
			$ttl_dayoff_used = $curr_ttl_dayoff_used-$yg_sudah_dikembalikan;
			
			$dataUpd = [
				'ttl_dayoff_used' => $ttl_dayoff_used,
				'status_dayoff_available' => 1
			];
			$this->db->update('overtimes', $dataUpd, "id = '".$rowOvertimes->id."'");
			

			$ttl_pengembalian = $sisa_pengembalian;

		}
	}


	public function getApprovalMatrix($work_location_id, $approval_type_id, $leave_type_id='', $diff_day='', $trx_id){

		if($work_location_id != '' && $approval_type_id != ''){
			if($approval_type_id == 13){ ///attendance revision
					
				$getmatrix = $this->db->query("select * from approval_matrix where approval_type_id = '".$approval_type_id."' and work_location_id = '".$work_location_id."' ")->result(); 

				if(empty($getmatrix)){
					$getmatrix = $this->db->query("select * from approval_matrix where approval_type_id = '".$approval_type_id."' and work_location_id = '".$work_location_id."' ")->result(); 
				}

				
				if(!empty($getmatrix)){
					$approvalMatrixId = $getmatrix[0]->id;
					if($approvalMatrixId != ''){
						$dataApproval = [
							'approval_matrix_type_id' 	=> $approval_type_id, //attendance revision
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
							$this->approvalemailservice->sendApproval('attendance_revision', $trx_id, $approval_path_id);

						}
					}
				}

				
			}

		}

	}


	public function add_data($post) { 

		$f_attendance_date 	= date_create($post['attendance_date']);
		$date_attendance 	= date_format($f_attendance_date,"Y-m-d");

	

		if($post['employee'] != '' && $post['attendance_date'] != ''){
			$dataEmp = $this->db->query("select * from employees where id = '".$post['employee']."'")->result(); 
			if(!empty($dataEmp)){
				if($post['absence_type'] == 'Shift 3' || $post['absence_type'] == 'Shift Malam'){
					$date_attendance = date("Y-m-d", strtotime($date_attendance . " +1 day"));
				}

				$schedule 			= $date_attendance.' '.$post['time_in'];
				$post_timein 		= strtotime($schedule); 

				$schedule_out 		= $date_attendance.' '.$post['time_out'];
				$post_timeout 		= strtotime($schedule_out); 


				$is_late=''; $is_leaving_office_early = ''; $num_of_working_hours='';
				$date_attendance_in = "";
				if(!empty($post['attendance_in']) && $post['attendance_in'] != '0000-00-00 00:00:00'){
					$date_attendance_in = date('Y-m-d H:i:s', strtotime($post['attendance_in']));

					$f_time_in 			= date("H:i:s", strtotime($post['attendance_in']));
					$timestamp_timein 	= strtotime($post['attendance_in']); 

					if($timestamp_timein > $post_timeout){ //jika checkin di atas waktu checkout
						return [
						    "status" => false,
						    "msg" 	 => "Check-in time has expired"
						];
					}

					if($timestamp_timein > $post_timein){
						$is_late='Y';
					}
				}

				$date_attendance_out = "";
				if(!empty($post['attendance_out']) && $post['attendance_out'] != '0000-00-00 00:00:00'){
					$date_attendance_out 	= date('Y-m-d H:i:s', strtotime($post['attendance_out']));
					$f_time_out 			= date("H:i:s", strtotime($post['attendance_out']));
					$timestamp_timeout 		= strtotime($post['attendance_out']); 

					if($timestamp_timeout < $post_timeout){
						$is_leaving_office_early = 'Y';
					}
					
				}

				if(!empty($post['attendance_in']) && $post['attendance_in'] != '0000-00-00 00:00:00' && !empty($post['attendance_out']) && $post['attendance_out'] != '0000-00-00 00:00:00'){ 
					$num_of_working_hours = abs($timestamp_timeout - $timestamp_timein)/(60)/(60); //jam
				}



				$data_attendances = $this->db->query("select * from time_attendances where date_attendance = '".$date_attendance."' and employee_id = '".$post['employee']."'")->result(); 

		  		if(empty($data_attendances)){ 

		  			//upload 
					$dataU = array();
					$dataU['status'] = FALSE; 
					$fieldname='attachment';
					if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
		            { 
		               
		                $config['upload_path']   = "./uploads/attendance_revision";
		                $config['allowed_types'] = "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
		                $config['max_size']      = "0"; 
		                
		                $this->load->library('upload', $config); 
		                
		                if(!$this->upload->do_upload($fieldname)){ 
		                    $err_msg = $this->upload->display_errors(); 
		                    $dataU['error_warning'] = strip_tags($err_msg);              
		                    $dataU['status'] = FALSE;
		                } else { 
		                    $fileData = $this->upload->data();
		                    $dataU['upload_file'] = $fileData['file_name'];
		                    $dataU['status'] = TRUE;
		                }
		            }
		            $document = '';
					if($dataU['status']){ 
						$document = $dataU['upload_file'];
					} else if(isset($dataU['error_warning'])){ 
						//echo $dataU['error_warning']; exit;
						$document = 'ERROR : '.$dataU['error_warning'];
					}
		            //end upload


		  			$data = [
						'date_attendance' 			=> $date_attendance,
						'employee_id' 				=> trim($post['employee']),
						'attendance_type' 			=> trim($post['absence_type']),
						'time_in' 					=> trim($post['time_in']),
						'time_out' 					=> trim($post['time_out']),
						'date_attendance_in' 		=> $date_attendance_in,
						'date_attendance_out'		=> $date_attendance_out,
						'is_leaving_office_early'	=> $is_leaving_office_early,
						'is_late'					=> $is_late,
						'num_of_working_hours'		=> $num_of_working_hours,
						'attachment'				=> $document,
						'status_approval'			=> 1, //waiting approval
						'created_at'				=> date("Y-m-d H:i:s"),
						'created_by'				=> $_SESSION['worker'],
						'description' 				=> trim($post['description']),
						'work_location' 			=> trim($post['location'])
					];

					$rs = $this->db->insert("time_attendances_revision", $data);
					$lastId = $this->db->insert_id();

					if($rs){

						///insert approval path
						$approval_type_id = 13; //attendance revision
						$this->getApprovalMatrix($dataEmp[0]->work_location, $approval_type_id, "", "", $lastId);


						return [
						    "status" => true,
						    "msg" => "Data berhasil disimpan"
						];
					}else{
						return [
						    "status" => false,
						    "msg" 	 => "Data gagal disimpan"
						];
					}

		  		}else{
		  			return [
					    "status" => false,
					    "msg" 	 => "Cannot double absen"
					];
		  		}
			}else{
				return [
				    "status" => false,
				    "msg" 	 => "Employee not found"
				];
			}
			
		}else{
			
			return [
			    "status" => false,
			    "msg" 	 => "Please fill Employee & Attendance Date"
			];
		}
		
	}  

	

	public function edit_data($post) {

		if(!empty($post['id'])){

			if($post['attendance_date'] != '' && $post['absence_type'] != ''){

				$getcurrData = $this->db->query("select * from time_attendances_revision where id = '".$post['id']."' ")->result();

				if(empty($getcurrData)){
					return [
					    "status" => false,
					    "msg" 	 => "Data tidak ditemukan"
					];
				}

				if($getcurrData[0]->status_approval == 1){ //waiting approval

					$f_attendance_date 	= date_create($post['attendance_date']);
					$date_attendance 	= date_format($f_attendance_date,"Y-m-d");

					if($post['absence_type'] == 'Shift 3' || $post['absence_type'] == 'Shift Malam'){
						$date_attendance = date("Y-m-d", strtotime($date_attendance . " +1 day"));
					}

					$schedule 		= $date_attendance.' '.$post['time_in'];
					$post_timein 	= strtotime($schedule);

					$schedule_out 	= $date_attendance.' '.$post['time_out'];
					$post_timeout 	= strtotime($schedule_out);

					$is_late=''; $is_leaving_office_early = ''; $num_of_working_hours='';
					$date_attendance_in = "";
					if(!empty($post['attendance_in']) && $post['attendance_in'] != '0000-00-00 00:00:00'){
						$date_attendance_in = date('Y-m-d H:i:s', strtotime($post['attendance_in']));

						$timestamp_timein = strtotime($post['attendance_in']);

						if($timestamp_timein > $post_timeout){
							return [
							    "status" => false,
							    "msg" 	 => "Check-in time has expired"
							];
						}

						if($timestamp_timein > $post_timein){
							$is_late='Y';
						}
					}

					$date_attendance_out = "";
					if(!empty($post['attendance_out']) && $post['attendance_out'] != '0000-00-00 00:00:00'){
						$date_attendance_out 	= date('Y-m-d H:i:s', strtotime($post['attendance_out']));
						$timestamp_timeout 		= strtotime($post['attendance_out']);

						if($timestamp_timeout < $post_timeout){
							$is_leaving_office_early = 'Y';
						}
					}

					if(!empty($post['attendance_in']) && $post['attendance_in'] != '0000-00-00 00:00:00' && !empty($post['attendance_out']) && $post['attendance_out'] != '0000-00-00 00:00:00'){
						$num_of_working_hours = abs($timestamp_timeout - $timestamp_timein)/(60)/(60);
					}

					///get document///
					$hdnattachment = $post['hdnattachment'];
					$dataU = array();
					$dataU['status'] = FALSE;
					$fieldname='attachment';
					if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
		            {
		                $config['upload_path']   = "./uploads/attendance_revision";
		                $config['allowed_types'] = "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
		                $config['max_size']      = "0";

		                $this->load->library('upload', $config);

		                if(!$this->upload->do_upload($fieldname)){
		                    $err_msg = $this->upload->display_errors();
		                    $dataU['error_warning'] = strip_tags($err_msg);
		                    $dataU['status'] = FALSE;
		                } else {
		                    $fileData = $this->upload->data();
		                    $dataU['upload_file'] = $fileData['file_name'];
		                    $dataU['status'] = TRUE;
		                }
		            }
		            $document = '';
					if($dataU['status']){
						$document = $dataU['upload_file'];
					} else if(isset($dataU['error_warning'])){
						$document = 'ERROR : '.$dataU['error_warning'];
					}

	            	if($document == '' && $hdnattachment != ''){
	            		$document = $hdnattachment;
	            	}
	            	///end get document///

					$data = [
						'date_attendance' 			=> $date_attendance,
						'attendance_type' 			=> trim($post['absence_type']),
						'time_in' 					=> trim($post['time_in']),
						'time_out' 					=> trim($post['time_out']),
						'date_attendance_in' 		=> $date_attendance_in,
						'date_attendance_out'		=> $date_attendance_out,
						'is_leaving_office_early'	=> $is_leaving_office_early,
						'is_late'					=> $is_late,
						'num_of_working_hours'		=> $num_of_working_hours,
						'attachment'				=> $document,
						'description' 				=> trim($post['description']),
						'work_location' 			=> trim($post['location']),
						'updated_at'				=> date("Y-m-d H:i:s")
					];

					$rs = $this->db->update("time_attendances_revision", $data, [$this->primary_key => trim($post['id'])]);

					if($rs){
						return [
						    "status" => true,
						    "msg" => "Data berhasil disimpan"
						];
					}else{
						return [
						    "status" => false,
						    "msg" 	 => "Data gagal disimpan"
						];
					}

				}else{
					return [
					    "status" => false,
					    "msg" 	 => "Cannot edit approved data"
					];
				}

			}else{
				return [
				    "status" => false,
				    "msg" 	 => "Attendance date & absence type must be filled"
				];
			}

		} else{
			return [
			    "status" => false,
			    "msg" 	 => "ID tidak ditemukan"
			];
		}
	}

	public function getRowData($id) { 

		$karyawan_id = $_SESSION['worker'];

		$whr='';
		if($_SESSION['role'] != 1 && $_SESSION['role'] != 4){ //bukan super user && bukan HR admin
			//$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
			$whr=' where ao.employee_id = "'.$karyawan_id.'" or ao.direct_id = "'.$karyawan_id.'" or ao.is_approver_view = 1 ';
		}


		$mTable = '(SELECT ao.* 
					FROM (
					    SELECT 
					        a.id,
					        a.date_attendance,
					        a.employee_id,
					        a.attendance_type,
					        a.time_in,
					        a.time_out,
					        a.date_attendance_in,
					        a.date_attendance_out,
					        a.is_late,
					        a.is_leaving_office_early,
					        a.num_of_working_hours, a.attachment, a.created_at, a.created_by, a.description,
					        b.full_name, a.work_location,
					        (CASE
					            WHEN a.status_approval = 1 THEN "Waiting Approval"
					            WHEN a.status_approval = 2 THEN "Approved"
					            WHEN a.status_approval = 3 THEN "Rejected"
					        END) AS status,
					        max(b.direct_id) AS direct_id,
					        max(d.current_approval_level) AS current_approval_level,
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
					    FROM time_attendances_revision a
					    LEFT JOIN employees b ON b.id = a.employee_id
					    LEFT JOIN approval_path d ON d.trx_id = a.id AND d.approval_matrix_type_id = 13
					    LEFT JOIN approval_matrix bb ON bb.id = d.approval_matrix_id
					    LEFT JOIN approval_matrix_detail cc ON cc.approval_matrix_id = bb.id
					    LEFT JOIN approval_matrix_role dd ON dd.id = cc.role_id
					    LEFT JOIN approval_path_detail ee ON ee.approval_path_id = d.id AND ee.approval_level = cc.approval_level
					    LEFT JOIN approval_matrix_role_pic g ON g.approval_matrix_role_id = cc.role_id
					    LEFT JOIN approval_matrix_detail h ON h.approval_matrix_id = d.approval_matrix_id AND h.approval_level = d.current_approval_level
					    LEFT JOIN approval_matrix_role i ON i.id = h.role_id
					    where b.emp_source = "outsource"
						GROUP BY a.id
					) ao
					'.$whr.'
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
		
		$karyawan_id = $_SESSION['worker'];
		$whr='';
		if($_SESSION['role'] != 1 && $_SESSION['role'] != 4){ //bukan super user && bukan HR admin
			$whr=' and a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}




		$sql = 'select a.*, b.full_name, c.name as leave_name,
					(case
						when a.status_approval = 1 then "Waiting Approval"
						when a.status_approval = 2 then "Approved"
						when a.status_approval = 3 then "Rejected"
						 end) as status, b.direct_id
					from leave_absences a left join employees b on b.id = a.employee_id
					left join master_leaves c on c.id = a.masterleave_id
					where b.emp_source = "outsource"
					'.$whr.'
	   			ORDER BY id ASC
		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function get_data_emp($karyawan_id, $attendance_date){ 
		$dateNow = $attendance_date;

		$empData = $this->db->query("select full_name, shift_type from employees where id = '".$karyawan_id."'")->result(); 
		$emp_shift_type=1; $time_in=""; $time_out=""; $attendance_type="";
		if($empData[0]->shift_type == 'Reguler'){
			$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 

		}else if($empData[0]->shift_type == 'Shift'){
			
			/// NEW SCRIPT
			$datetimemax_shift3 = $dateNow.' 08:00:00';
			if($datetimeNow < $datetimemax_shift3){ //brarti dia sdg checkin shift 3 di tgl sebelumnya (late)
				$dateYesterday = date("Y-m-d", strtotime($dateNow . " -1 day"));
				$period  = date('Y-m', strtotime($dateYesterday));
			 	$tgl = date('d', strtotime($dateYesterday));
			 	$date_attendance = $dateYesterday;
			}


			$dt = $this->db->query("select 
			    a.*, 
			    b.periode, 
			    b.`".$tgl."` as 'shift', 
			    c.name,
			    case 
			        when c.shift_id = 3 then 
			            concat(date_add(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), interval 1 day), ' ', c.time_in)
			        else 
			            concat(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), ' ', c.time_in)
			    end as expected_checkin,
			    case 
			        when c.shift_id = 2 then 
			            concat(date_add(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), interval 1 day), ' 00:00:00')
			        when c.shift_id = 3 then 
			            concat(date_add(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), interval 1 day), ' ', c.time_out)
			        else 
			            concat(str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d'), ' ', c.time_out)
			    end as expected_checkout,
			    c.time_in, c.time_out, str_to_date(concat(a.period, '-', '".$tgl."'), '%Y-%m-%d') as date_attendance
			from shift_schedule_os a
			left join group_shift_schedule_os b on b.shift_schedule_id = a.id 
			left join master_shift_time c on c.shift_id = b.`".$tgl."`
			where b.employee_id = '".$karyawan_id."'
			and a.period = '".$period."'
			")->result(); 

			if($dt[0]->shift == ""){
				$emp_shift_type=0;
			}

			
			/// END NEW SCRIPT

		}else{ //tidak ada shift type
			$emp_shift_type=0;
		} 

		if($emp_shift_type==1){
			$time_in 			= $dt[0]->time_in;
			$time_out 			= $dt[0]->time_out;
			$attendance_type 	= $dt[0]->name;
		}


		$rs = [
			'time_in' 	=> $time_in,
			'time_out' 	=> $time_out,
 			'attendance_type' => $attendance_type
			
		];

		return $rs;

	}

	

}

