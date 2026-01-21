<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
class Absensi_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "time_attendance/absensi_menu";
 	protected $table_name 				= _PREFIX_TABLE."time_attendances";
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
			'dt.date_attendance',
			'dt.full_name',
			'dt.attendance_type',
			'dt.time_in',
			'dt.time_out',
			'dt.date_attendance_in',
			'dt.date_attendance_out',
			'dt.is_late_desc',
			'dt.is_leaving_office_early_desc',
			'dt.num_of_working_hours',
			'dt.holiday_flag'
		];
		
		$karyawan_id = $_SESSION['worker'];

		$whr = ' WHERE 1=1 ';

		// FILTER: cuma internal
		$whr .= ' AND (b.emp_source = "internal") ';

		// FILTER role (kalau bukan super user & HR admin)
		if($_SESSION['role'] != 1 && $_SESSION['role'] != 4){
			$whr .= ' AND (a.employee_id = "'.$karyawan_id.'" OR b.direct_id = "'.$karyawan_id.'") ';
		}


		$sIndexColumn = $this->primary_key;
		/*$sTable = '(select a.*, b.full_name, if(a.is_late = "Y","Late", "") as "is_late_desc", 
					(case 
					when a.leave_type != "" then concat("(",c.name,")") 
					when a.is_leaving_office_early = "Y" then "Leaving Office Early"
					else ""
					end) as is_leaving_office_early_desc
					from time_attendances a left join employees b on b.id = a.employee_id
					left join master_leaves c on c.id = a.leave_type
					'.$whr.'
				)dt';*/

		$sTable = '(SELECT 
					    a.*,
					    b.full_name,
					    IF(a.is_late = "Y","Late", "") AS is_late_desc,
					    (CASE 
					        WHEN a.leave_type != "" THEN CONCAT("(", c.name, ")") 
					        WHEN a.is_leaving_office_early = "Y" THEN "Leaving Office Early"
					        ELSE ""
					     END) AS is_leaving_office_early_desc,
					    CASE 
					    	WHEN a.date_attendance_in IS NOT NULL THEN ""
					        WHEN o.id IS NOT NULL THEN ""
					        WHEN b.shift_type = "Reguler" AND DAYOFWEEK(a.date_attendance) IN (1,7) THEN "Holiday"
					        WHEN h.date IS NOT NULL THEN "Holiday"
					        WHEN a.leave_absences_id IS NOT NULL THEN "Holiday"
					        WHEN b.shift_type = "Shift" AND (
					            CASE DAY(a.date_attendance)
					                WHEN 1  THEN gss.`01` WHEN 2  THEN gss.`02` WHEN 3  THEN gss.`03`
					                WHEN 4  THEN gss.`04` WHEN 5  THEN gss.`05` WHEN 6  THEN gss.`06`
					                WHEN 7  THEN gss.`07` WHEN 8  THEN gss.`08` WHEN 9  THEN gss.`09`
					                WHEN 10 THEN gss.`10` WHEN 11 THEN gss.`11` WHEN 12 THEN gss.`12`
					                WHEN 13 THEN gss.`13` WHEN 14 THEN gss.`14` WHEN 15 THEN gss.`15`
					                WHEN 16 THEN gss.`16` WHEN 17 THEN gss.`17` WHEN 18 THEN gss.`18`
					                WHEN 19 THEN gss.`19` WHEN 20 THEN gss.`20` WHEN 21 THEN gss.`21`
					                WHEN 22 THEN gss.`22` WHEN 23 THEN gss.`23` WHEN 24 THEN gss.`24`
					                WHEN 25 THEN gss.`25` WHEN 26 THEN gss.`26` WHEN 27 THEN gss.`27`
					                WHEN 28 THEN gss.`28` WHEN 29 THEN gss.`29` WHEN 30 THEN gss.`30`
					                WHEN 31 THEN gss.`31`
					            END
					        ) IS NULL THEN "Holiday"
					        ELSE ""
					    END AS holiday_flag,
					    CASE 
					    	WHEN a.date_attendance_in IS NOT NULL THEN ""
					        WHEN o.id IS NOT NULL THEN ""
					        WHEN b.shift_type = "Reguler" AND DAYOFWEEK(a.date_attendance) IN (1,7) THEN "Weekend"
					        WHEN h.date IS NOT NULL THEN "Master Holiday"
					        WHEN a.leave_absences_id IS NOT NULL THEN "Leave"
					        WHEN b.shift_type = "Shift" AND (
					            CASE DAY(a.date_attendance)
					                WHEN 1  THEN gss.`01` WHEN 2  THEN gss.`02` WHEN 3  THEN gss.`03`
					                WHEN 4  THEN gss.`04` WHEN 5  THEN gss.`05` WHEN 6  THEN gss.`06`
					                WHEN 7  THEN gss.`07` WHEN 8  THEN gss.`08` WHEN 9  THEN gss.`09`
					                WHEN 10 THEN gss.`10` WHEN 11 THEN gss.`11` WHEN 12 THEN gss.`12`
					                WHEN 13 THEN gss.`13` WHEN 14 THEN gss.`14` WHEN 15 THEN gss.`15`
					                WHEN 16 THEN gss.`16` WHEN 17 THEN gss.`17` WHEN 18 THEN gss.`18`
					                WHEN 19 THEN gss.`19` WHEN 20 THEN gss.`20` WHEN 21 THEN gss.`21`
					                WHEN 22 THEN gss.`22` WHEN 23 THEN gss.`23` WHEN 24 THEN gss.`24`
					                WHEN 25 THEN gss.`25` WHEN 26 THEN gss.`26` WHEN 27 THEN gss.`27`
					                WHEN 28 THEN gss.`28` WHEN 29 THEN gss.`29` WHEN 30 THEN gss.`30`
					                WHEN 31 THEN gss.`31`
					            END
					        ) IS NULL THEN "No Shift"
					        ELSE ""
					    END AS holiday_type,
					    CASE 
					        WHEN o.id IS NOT NULL THEN "Y"
					        ELSE "N"
					    END AS overtime_flag
					FROM time_attendances a
					LEFT JOIN employees b ON b.id = a.employee_id
					LEFT JOIN master_leaves c ON c.id = a.leave_type
					LEFT JOIN master_holidays h ON h.date = a.date_attendance
					LEFT JOIN overtimes o 
					       ON o.employee_id = a.employee_id
					      AND a.date_attendance BETWEEN DATE(o.datetime_start) AND DATE(o.datetime_end)
					      AND o.status_id = 2 
					      AND o.type = 2
					LEFT JOIN group_shift_schedule gss 
					       ON gss.employee_id = a.employee_id
					      AND gss.periode = DATE_FORMAT(a.date_attendance, "%Y-%m")
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

		$dateNow = date("Y-m-d");
		$dateTomorrow = date("Y-m-d", strtotime($dateNow . " +1 day"));

		foreach($rResult as $row)
		{
			$date_attendance_tomorrow = date("Y-m-d", strtotime($row->date_attendance . " +1 day"));

			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #343851; border-color: #343851;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$isupdate="0"; $isdelete="0";
			if($row->attendance_type != '' && $row->attendance_type != null){
				if(
					($row->attendance_type == 'Reguler' && $row->date_attendance == $dateNow) || 
					($row->attendance_type == 'Shift 1' && $row->date_attendance == $dateNow) || 
					($row->attendance_type == 'Shift 2' && $dateNow <= $date_attendance_tomorrow) || 
					/*($row->attendance_type == 'Shift 3' && $row->date_attendance == $dateNow)*/
					($row->attendance_type == 'Shift 3' && $dateNow <= $date_attendance_tomorrow)
				){
					$isupdate="1"; $isdelete="1";
				}
				/*if($row->attendance_type == 'Shift 3' && $row->date_attendance == $dateTomorrow){
					$isdelete="1";
				}*/
			}
			
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1" && $isupdate == "1" )  {
				
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}

			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				//$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				if($isdelete == "1"){
					
					$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
				}
			}

			$date_attendance_in = $row->date_attendance_in; 
			if($row->date_attendance_in == '0000-00-00 00:00:00'){
				$date_attendance_in='';
			}
			$date_attendance_out = $row->date_attendance_out; 
			if($row->date_attendance_out == '0000-00-00 00:00:00'){
				$date_attendance_out='';
			}
			$num_of_working_hours = $row->num_of_working_hours;
			if($row->num_of_working_hours == '0.00'){
				$num_of_working_hours='';
			}

			$dayName = date('l', strtotime($row->date_attendance));

			if($row->holiday_flag == 'Holiday'){ //libur
				$id = '<span style="color:red">'.$row->id.'</span>';
				$dayName = '<span style="color:red">'.$dayName.'</span>';
				$date_attendance = '<span style="color:red">'.$row->date_attendance.'</span>';
				$full_name = '<span style="color:red">'.$row->full_name.'</span>';
				$attendance_type = '<span style="color:red">'.$row->attendance_type.'</span>';
				$time_in = '<span style="color:red">'.$row->time_in.'</span>';
				$time_out = '<span style="color:red">'.$row->time_out.'</span>';
				$date_attendance_in = '<span style="color:red">'.$date_attendance_in.'</span>';
				$date_attendance_out = '<span style="color:red">'.$date_attendance_out.'</span>';
				$is_late_desc = '<span style="color:red">'.$row->is_late_desc.'</span>';
				$is_leaving_office_early_desc = '<span style="color:red">'.$row->is_leaving_office_early_desc.'</span>';
				$num_of_working_hours = '<span style="color:red">'.$num_of_working_hours.'</span>';
			}else{
				$id = $row->id;
				$dayName = $dayName;
				$date_attendance = $row->date_attendance;
				$full_name = $row->full_name;
				$attendance_type = $row->attendance_type;
				$time_in = $row->time_in;
				$time_out = $row->time_out;
				$date_attendance_in = $date_attendance_in;
				$date_attendance_out = $date_attendance_out;
				$is_late_desc = $row->is_late_desc;
				$is_leaving_office_early_desc = $row->is_leaving_office_early_desc;
				$num_of_working_hours = $num_of_working_hours;
			}
			

			if($delete_bulk == ""){
				array_push($output["aaData"],array(
					'<div class="action-buttons">
						'.$detail.'
						'.$edit.'
						'.$delete.'
					</div>',
					$id,
					$dayName,
					$date_attendance,
					$full_name,
					$attendance_type,
					$time_in,
					$time_out,
					$date_attendance_in,
					$date_attendance_out,
					$is_late_desc,
					$is_leaving_office_early_desc,
					$num_of_working_hours


				));
			}else{
				array_push($output["aaData"],array(
					$delete_bulk,
					'<div class="action-buttons">
						'.$detail.'
						'.$edit.'
						'.$delete.'
					</div>',
					$id,
					$dayName,
					$date_attendance,
					$full_name,
					$attendance_type,
					$time_in,
					$time_out,
					$date_attendance_in,
					$date_attendance_out,
					$is_late_desc,
					$is_leaving_office_early_desc,
					$num_of_working_hours


				));
			}
			
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


	public function add_data($post) { 

		/*$date_attendance 	= date_create($post['date_attendance']);*/
		$date_attendance 	= $post['date_attendance']; 
		/*$post_timein 		= strtotime($post['time_in']);
		$post_timeout 		= strtotime($post['time_out']);*/

		$is_late=''; //$is_leaving_office_early = ''; $num_of_working_hours='';

		/*$f_datetime_in='';
		if(!empty($post['attendance_in']) && $post['attendance_in'] != '0000-00-00 00:00:00'){
			
			$f_datetime_in 		= $post['attendance_in'];
			$f_time_in 			= date("H:i:s", strtotime($post['attendance_in']));
			$timestamp_timein 	= strtotime($post['attendance_in']); 

			if($post['emp_type'] == 'Shift 3'){
				$date_attendance = date("Y-m-d", strtotime($date_attendance . " +1 day"));
			}

			$schedule 			= $date_attendance.' '.$post['time_in'];
			$post_timein 		= strtotime($schedule); 
			
			if($timestamp_timein > $post_timein){
				$is_late='Y';
			}
		}*/

		
		
		if($post['date_attendance'] == ''){
			echo 'Date Attendance is not valid'; die();
		}else if($post['emp_type'] == ''){
			echo 'Shift Schedule not found'; die();
		}else{
			if(!empty($post['attendance_in']) && $post['attendance_in'] != '0000-00-00 00:00:00'){
				$f_datetime_in 		= $post['attendance_in'];
				$f_time_in 			= date("H:i:s", strtotime($post['attendance_in']));
				$timestamp_timein 	= strtotime($post['attendance_in']); 

				if($post['emp_type'] == 'Shift 3'){
					$date_attendance = date("Y-m-d", strtotime($date_attendance . " +1 day"));
				}

				$schedule 			= $date_attendance.' '.$post['time_in'];
				$post_timein 		= strtotime($schedule); 

				$schedule_out 		= $date_attendance.' '.$post['time_out'];
				$post_timeout 		= strtotime($schedule_out); 

				if($timestamp_timein > $post_timeout){ //jika checkin di atas waktu checkout
					echo "Check-in time has expired"; die();
				}else{
					if($timestamp_timein > $post_timein){
						$is_late='Y';
					}

					$data_attendances = $this->db->query("select * from time_attendances where date_attendance = '".$post['date_attendance']."' and employee_id = '".$post['hdnempid']."'")->result(); 

			  		//if(empty($data_attendances)){ 
			  			$data = [
							'date_attendance' 			=> $post['date_attendance'],
							'employee_id' 				=> trim($post['hdnempid']),
							/*'attendance_type' 			=> trim($post['emp_type']),
							'time_in' 					=> trim($post['time_in']),
							'time_out' 					=> trim($post['time_out']),*/
							'date_attendance_in' 		=> $f_datetime_in,
							'is_late'					=> $is_late,
							'created_at'				=> date("Y-m-d H:i:s"),
							'notes' 					=> trim($post['description']),
							'work_location' 			=> trim($post['location']),
							'lat_checkin' 				=> trim($post['latitude']),
							'long_checkin' 				=> trim($post['longitude'])
							/*'time_zone_checkin' 		=> $work_location_time_zone,
							'utc_offset_checkin' 		=> $work_location_utc_offset,
							'datetime_local_checkin' 	=> $datetime_local,
							'utc_time_checkin' 			=> $datetime_utc*/
						];

						$rs = $this->db->insert("time_attendances_log", $data);

						if($rs){
							/// masukin data pertama checkin ke table time attendances, kalo log bisa berkali kali
							if(empty($data_attendances)){
								$data2 = [
									'date_attendance' 			=> $post['date_attendance'],
									'employee_id' 				=> trim($post['hdnempid']),
									'attendance_type' 			=> trim($post['emp_type']),
									'time_in' 					=> trim($post['time_in']),
									'time_out' 					=> trim($post['time_out']),
									'date_attendance_in' 		=> $f_datetime_in,
									'is_late'					=> $is_late,
									'created_at'				=> date("Y-m-d H:i:s"),
									'notes' 					=> trim($post['description']),
									'work_location' 			=> trim($post['location']),
									'lat_checkin' 				=> trim($post['latitude']),
									'long_checkin' 				=> trim($post['longitude'])
								];
								$this->db->insert($this->table_name, $data2);
							}

							$upd_emp = [
								'last_lat' 				=> trim($post['latitude']),
								'last_long' 			=> trim($post['longitude'])
							];
							$this->db->update("employees", $upd_emp, "id='".trim($post['hdnempid'])."'");
						}

						return $rs;

			  		// }else{
			  		// 	echo "Cannot double check in"; die();
			  		// }
				}
				

			}else{
				echo "Attendance IN not valid"; die();
			}
			
		}
		
	}  


	public function add_data_old($post) { 

		/*$date_attendance 	= date_create($post['date_attendance']);*/
		$date_attendance 	= $post['date_attendance']; 
		/*$post_timein 		= strtotime($post['time_in']);
		$post_timeout 		= strtotime($post['time_out']);*/

		$is_late=''; //$is_leaving_office_early = ''; $num_of_working_hours='';

		/*$f_datetime_in='';
		if(!empty($post['attendance_in']) && $post['attendance_in'] != '0000-00-00 00:00:00'){
			
			$f_datetime_in 		= $post['attendance_in'];
			$f_time_in 			= date("H:i:s", strtotime($post['attendance_in']));
			$timestamp_timein 	= strtotime($post['attendance_in']); 

			if($post['emp_type'] == 'Shift 3'){
				$date_attendance = date("Y-m-d", strtotime($date_attendance . " +1 day"));
			}

			$schedule 			= $date_attendance.' '.$post['time_in'];
			$post_timein 		= strtotime($schedule); 
			
			if($timestamp_timein > $post_timein){
				$is_late='Y';
			}
		}*/

		
		
		if($post['date_attendance'] == ''){
			echo 'Date Attendance is not valid'; die();
		}else if($post['emp_type'] == ''){
			echo 'Shift Schedule not found'; die();
		}else{
			if(!empty($post['attendance_in']) && $post['attendance_in'] != '0000-00-00 00:00:00'){
				$f_datetime_in 		= $post['attendance_in'];
				$f_time_in 			= date("H:i:s", strtotime($post['attendance_in']));
				$timestamp_timein 	= strtotime($post['attendance_in']); 

				if($post['emp_type'] == 'Shift 3'){
					$date_attendance = date("Y-m-d", strtotime($date_attendance . " +1 day"));
				}

				$schedule 			= $date_attendance.' '.$post['time_in'];
				$post_timein 		= strtotime($schedule); 

				$schedule_out 		= $date_attendance.' '.$post['time_out'];
				$post_timeout 		= strtotime($schedule_out); 

				if($timestamp_timein > $post_timeout){ //jika checkin di atas waktu checkout
					echo "Check-in time has expired"; die();
				}else{
					if($timestamp_timein > $post_timein){
						$is_late='Y';
					}

					$data_attendances = $this->db->query("select * from time_attendances where date_attendance = '".$post['date_attendance']."' and employee_id = '".$post['hdnempid']."'")->result(); 

			  		if(empty($data_attendances)){ 
			  			$data = [
							//'date_attendance' 			=> date_format($date_attendance,"Y-m-d"),
							// 'employee_id' 			=> trim($post['employee']),
							'date_attendance' 			=> $post['date_attendance'],
							'employee_id' 				=> trim($post['hdnempid']),
							'attendance_type' 			=> trim($post['emp_type']),
							'time_in' 					=> trim($post['time_in']),
							'time_out' 					=> trim($post['time_out']),
							'date_attendance_in' 		=> $f_datetime_in,
							//'date_attendance_out'		=> $f_datetime_out,
							'is_late'					=> $is_late,
							//'is_leaving_office_early'	=> $is_leaving_office_early,
							//'num_of_working_hours'		=> $num_of_working_hours,
							'created_at'				=> date("Y-m-d H:i:s"),
							'notes' 					=> trim($post['description']),
							'work_location' 			=> trim($post['location']),
							'lat_checkin' 				=> trim($post['latitude']),
							'long_checkin' 				=> trim($post['longitude'])
						];
						$rs = $this->db->insert($this->table_name, $data);

						return $rs;

			  		}else{
			  			echo "Cannot double check in"; die();
			  		}
				}
				

			}else{
				echo "Attendance IN not valid"; die();
			}
			
		}
		
	}  


	public function edit_data($post) { 
		
		$date_attendance 	= date_create($post['date_attendance']); 
		$post_timein 		= strtotime($post['time_in']);
		$post_timeout 		= strtotime($post['time_out']);

		//$is_late=''; 
		$is_leaving_office_early = ''; $num_of_working_hours='';


		if(!empty($post['id'])){

			$f_datetime_in 		= $post['attendance_in'];
			$timestamp1 		= strtotime($f_datetime_in); 

			$cek_emp = $this->db->query("select * from time_attendances where id = '".$post['id']."' ")->result(); 
			$is_attendance_type=1;
			if($cek_emp[0]->attendance_type == 'Reguler'){

				$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 

			}else if($cek_emp[0]->attendance_type == 'Shift 1' || $cek_emp[0]->attendance_type == 'Shift 2' || $cek_emp[0]->attendance_type == 'Shift 3'){ 

				$tgl 	= date("d", strtotime($cek_emp[0]->date_attendance));
				$period = date("Y-m", strtotime($cek_emp[0]->date_attendance));

				$dt = $this->db->query("select a.*, b.periode
						, b.`".$tgl."` as 'shift' 
						, c.time_in, c.time_out, c.name 
						from shift_schedule a
						left join group_shift_schedule b on b.shift_schedule_id = a.id 
						left join master_shift_time c on c.shift_id = b.`".$tgl."`
						where b.employee_id = '".$cek_emp[0]->employee_id."' and a.period = '".$period."' ")->result(); 
			}else{
				$is_attendance_type=0;
			}


			if($is_attendance_type == 0){
				echo "Attendance type not found"; die();
			}else{
				$date_attendance = $cek_emp[0]->date_attendance;

				if($cek_emp[0]->attendance_type == 'Shift 2' || $cek_emp[0]->attendance_type == 'Shift 3'){
					$date_attendance = date("Y-m-d", strtotime($date_attendance . " +1 day"));
				}

				$datetime_out = $date_attendance.' '.$dt[0]->time_out;
				

				if(!empty($post['attendance_out']) && $post['attendance_out'] != '0000-00-00 00:00:00'){
					
					$f_datetime_out 	= $post['attendance_out'];
					$timestamp2 		= strtotime($f_datetime_out);
					$timestamp_timeout 	= strtotime($f_datetime_out);
					$post_timeout 		= strtotime($datetime_out);

					if($timestamp_timeout < $post_timeout){
						$is_leaving_office_early = 'Y';
					}

					if(!empty($post['attendance_in']) && $post['attendance_in'] != '0000-00-00 00:00:00' && !empty($post['attendance_out']) && $post['attendance_out'] != '0000-00-00 00:00:00'){
						$num_of_working_hours = abs($timestamp2 - $timestamp1)/(60)/(60); //jam

						$data = [
							/*'date_attendance' 		=> date_format($date_attendance,"Y-m-d"),
							'employee_id' 				=> trim($post['employee']),
							'attendance_type' 			=> trim($post['emp_type']),
							'time_in' 					=> trim($post['time_in']),
							'time_out' 					=> trim($post['time_out']),*/
							//'date_attendance_in' 		=> $f_datetime_in,
							'date_attendance_out'		=> $f_datetime_out,
							//'is_late'					=> $is_late,
							'is_leaving_office_early'	=> $is_leaving_office_early,
							'num_of_working_hours'		=> $num_of_working_hours,
							'updated_at'				=> date("Y-m-d H:i:s"),
							'notes' 					=> trim($post['description']),
							'work_location' 			=> trim($post['location']),
							'lat_checkout' 				=> trim($post['latitude']),
							'long_checkout' 			=> trim($post['longitude'])
						];

						$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
						if($rs){

							$data2 = [
								'date_attendance' 			=> $cek_emp[0]->date_attendance,
								'employee_id' 				=> $cek_emp[0]->employee_id,
								'date_attendance_out' 		=> $f_datetime_out,
								'is_leaving_office_early'	=> $is_leaving_office_early,
								'num_of_working_hours'		=> $num_of_working_hours,
								'updated_at'				=> date("Y-m-d H:i:s"),
								'notes' 					=> trim($post['description']),
								'lat_checkout' 				=> trim($post['latitude']),
								'long_checkout' 			=> trim($post['longitude']),
								'work_location' 			=> trim($post['location'])
								/*'time_zone_checkout' 		=> $work_location_time_zone,
								'utc_offset_checkout' 		=> $work_location_utc_offset,
								'datetime_local_checkout' 	=> $datetime_local,
								'utc_time_checkout' 		=> $datetime_utc*/
							];

							$this->db->insert("time_attendances_log", $data2);


							$upd_emp = [
								'last_lat' 				=> trim($post['latitude']),
								'last_long' 			=> trim($post['longitude'])
							];
							$this->db->update("employees", $upd_emp, "id='".$cek_emp[0]->employee_id."'");



							if(isset($post['hdnid'])){
								$item_num = count($post['hdnid']); // cek sum
								$item_len_min = min(array_keys($post['hdnid'])); // cek min key index
								$item_len = max(array_keys($post['hdnid'])); // cek max key index
							} else {
								$item_num = 0;
							}

							if($item_num>0){
								for($i=$item_len_min;$i<=$item_len;$i++) 
								{
									$hdnid = trim($post['hdnid'][$i]);
									if(!empty($hdnid)){ //update
										$currTask = $this->db->query("select * from tasklist where id = '".$hdnid."' ")->result();
											$currProgress = $currTask[0]->progress_percentage;
											$currStatus = $currTask[0]->status_id;

										$itemData = [
											'progress_percentage' 	=> trim($post['progress_percentage'][$i]),
											'status_id' 			=> trim($post['status'][$i]),
											'updated_at' 			=> date("Y-m-d H:i:s")
										];
										$rd = $this->db->update("tasklist", $itemData, "id = '".$hdnid."'");
										if($rd){
											if($currProgress != trim($post['progress_percentage'][$i]) || $currStatus != trim($post['status'][$i])){ //jika ada perubahan maka masukin ke table progress
												$dataprogress = [
													'time_attendances_id' 	=> $post['id'],
													'tasklist_id' 			=> $hdnid,
													'progress_percentage'	=> trim($post['progress_percentage'][$i]),
													'submit_at'				=> date("Y-m-d H:i:s")
												];
												$this->db->insert("history_progress_tasklist", $dataprogress);
											}
										}
									}
								}
							}
						}

						return $rs;

					}else{
						echo "Attendance In not valid"; die();
					}

				}else{
					echo "Attendance Out not valid"; die();
				}

			}
			
		} else return null;
	} 


	public function edit_data_old($post) { 
		
		$date_attendance 	= date_create($post['date_attendance']); 
		$post_timein 		= strtotime($post['time_in']);
		$post_timeout 		= strtotime($post['time_out']);

		//$is_late=''; 
		$is_leaving_office_early = ''; $num_of_working_hours='';


		if(!empty($post['id'])){

			$f_datetime_in 		= $post['attendance_in'];
			$timestamp1 		= strtotime($f_datetime_in); 

			$cek_emp = $this->db->query("select * from time_attendances where id = '".$post['id']."' ")->result(); 
			$is_attendance_type=1;
			if($cek_emp[0]->attendance_type == 'Reguler'){

				$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 

			}else if($cek_emp[0]->attendance_type == 'Shift 1' || $cek_emp[0]->attendance_type == 'Shift 2' || $cek_emp[0]->attendance_type == 'Shift 3'){ 

				$tgl 	= date("d", strtotime($cek_emp[0]->date_attendance));
				$period = date("Y-m", strtotime($cek_emp[0]->date_attendance));

				$dt = $this->db->query("select a.*, b.periode
						, b.`".$tgl."` as 'shift' 
						, c.time_in, c.time_out, c.name 
						from shift_schedule a
						left join group_shift_schedule b on b.shift_schedule_id = a.id 
						left join master_shift_time c on c.shift_id = b.`".$tgl."`
						where b.employee_id = '".$cek_emp[0]->employee_id."' and a.period = '".$period."' ")->result(); 
			}else{
				$is_attendance_type=0;
			}


			if($is_attendance_type == 0){
				echo "Attendance type not found"; die();
			}else{
				$date_attendance = $cek_emp[0]->date_attendance;

				if($cek_emp[0]->attendance_type == 'Shift 2' || $cek_emp[0]->attendance_type == 'Shift 3'){
					$date_attendance = date("Y-m-d", strtotime($date_attendance . " +1 day"));
				}

				$datetime_out = $date_attendance.' '.$dt[0]->time_out;
				

				if(!empty($post['attendance_out']) && $post['attendance_out'] != '0000-00-00 00:00:00'){
					
					$f_datetime_out 	= $post['attendance_out'];
					$timestamp2 		= strtotime($f_datetime_out);
					$timestamp_timeout 	= strtotime($f_datetime_out);
					$post_timeout 		= strtotime($datetime_out);

					if($timestamp_timeout < $post_timeout){
						$is_leaving_office_early = 'Y';
					}

					if(!empty($post['attendance_in']) && $post['attendance_in'] != '0000-00-00 00:00:00' && !empty($post['attendance_out']) && $post['attendance_out'] != '0000-00-00 00:00:00'){
						$num_of_working_hours = abs($timestamp2 - $timestamp1)/(60)/(60); //jam

						$data = [
							/*'date_attendance' 		=> date_format($date_attendance,"Y-m-d"),
							'employee_id' 				=> trim($post['employee']),
							'attendance_type' 			=> trim($post['emp_type']),
							'time_in' 					=> trim($post['time_in']),
							'time_out' 					=> trim($post['time_out']),*/
							//'date_attendance_in' 		=> $f_datetime_in,
							'date_attendance_out'		=> $f_datetime_out,
							//'is_late'					=> $is_late,
							'is_leaving_office_early'	=> $is_leaving_office_early,
							'num_of_working_hours'		=> $num_of_working_hours,
							'updated_at'				=> date("Y-m-d H:i:s"),
							'notes' 					=> trim($post['description']),
							'work_location' 			=> trim($post['location']),
							'lat_checkout' 				=> trim($post['latitude']),
							'long_checkout' 			=> trim($post['longitude'])
						];

						$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
						if($rs){
							if(isset($post['hdnid'])){
								$item_num = count($post['hdnid']); // cek sum
								$item_len_min = min(array_keys($post['hdnid'])); // cek min key index
								$item_len = max(array_keys($post['hdnid'])); // cek max key index
							} else {
								$item_num = 0;
							}

							if($item_num>0){
								for($i=$item_len_min;$i<=$item_len;$i++) 
								{
									$hdnid = trim($post['hdnid'][$i]);
									if(!empty($hdnid)){ //update
										$currTask = $this->db->query("select * from tasklist where id = '".$hdnid."' ")->result();
											$currProgress = $currTask[0]->progress_percentage;
											$currStatus = $currTask[0]->status_id;

										$itemData = [
											'progress_percentage' 	=> trim($post['progress_percentage'][$i]),
											'status_id' 			=> trim($post['status'][$i]),
											'updated_at' 			=> date("Y-m-d H:i:s")
										];
										$rd = $this->db->update("tasklist", $itemData, "id = '".$hdnid."'");
										if($rd){
											if($currProgress != trim($post['progress_percentage'][$i]) || $currStatus != trim($post['status'][$i])){ //jika ada perubahan maka masukin ke table progress
												$dataprogress = [
													'time_attendances_id' 	=> $post['id'],
													'tasklist_id' 			=> $hdnid,
													'progress_percentage'	=> trim($post['progress_percentage'][$i]),
													'submit_at'				=> date("Y-m-d H:i:s")
												];
												$this->db->insert("history_progress_tasklist", $dataprogress);
											}
										}
									}
								}
							}
						}

						return $rs;

					}else{
						echo "Attendance In not valid"; die();
					}

				}else{
					echo "Attendance Out not valid"; die();
				}

			}
			
		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(SELECT a.*, b.full_name as employee_name, (case when a.work_location = "wfo" then "WFO" when a.work_location = "wfh" then "WFH" when a.work_location = "onsite" then "On Site" else "" end) as work_location_name FROM time_attendances a left join employees b on b.id = a.employee_id
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

		$whr = ' WHERE 1=1 ';

		// FILTER: cuma internal
		$whr .= ' AND (b.emp_source = "internal") ';

		// FILTER role (kalau bukan super user & HR admin)
		if($_SESSION['role'] != 1 && $_SESSION['role'] != 4){
			$whr .= ' AND (a.employee_id = "'.$karyawan_id.'" OR b.direct_id = "'.$karyawan_id.'") ';
		}




		$sql = "select a.*, b.full_name, if(a.is_late = 'Y','Late', '') as 'is_late_desc', 
					(case 
					when a.leave_type != '' then concat('(',c.name,')') 
					when a.is_leaving_office_early = 'Y' then 'Leaving Office Early'
					else ''
					end) as is_leaving_office_early_desc
					from time_attendances a left join employees b on b.id = a.employee_id
					left join master_leaves c on c.id = a.leave_type
					".$whr."
	   			ORDER BY a.id ASC
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getDataEmployee($empid,$date=''){ 

		$period = date("Y-m", strtotime($date));
		$tgl = date("d", strtotime($date));

		$rs = $this->db->query("select * from employees where id = '".$empid."' ")->result(); 

		/*if($rs[0]->shift_type == 'Reguler'){
			$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 
			
			$dataX = array(
				'name' 		=> $dt[0]->name,
				'time_in' 	=> $dt[0]->time_in,
				'time_out' 	=> $dt[0]->time_out
			);
		}*/

		$emp_shift_type=1;
		if($rs[0]->shift_type == 'Reguler'){
			$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 
			
		}else if($rs[0]->shift_type == 'Shift'){
			$dt = $this->db->query("select a.*, b.periode
					, b.`".$tgl."` as 'shift' 
					, c.time_in, c.time_out, c.name 
					from shift_schedule a
					left join group_shift_schedule b on b.shift_schedule_id = a.id  
					left join master_shift_time c on c.shift_id = b.`".$tgl."`
					where a.employee_id = '".$empid."' and b.periode = '".$period."' ")->result(); 

		}else{ //tidak ada shift type
			$emp_shift_type=0;
		} 


		if($emp_shift_type==1){
			$dataX = array(
				'name' 		=> $dt[0]->name,
				'time_in' 	=> $dt[0]->time_in,
				'time_out' 	=> $dt[0]->time_out
			);

			return $dataX;
		}else return null;


	}


	public function getNewExpensesRow($row,$id,$view=FALSE,$checkin=FALSE)
	{ 

		if(!$view){ 

			$tasklist = $this->db->query("select a.*, b.full_name as employee_name, c.task as parent_name, d.name as status_name, e.title as project_name 
			from tasklist a left join employees b on b.id = a.employee_id
			left join tasklist c on c.id = a.parent_id
			left join master_tasklist_status d on d.id = a.status_id
			left join data_project e on e.id = a.project_id where a.employee_id = '".$id."' and a.status_id != 3")->result();

			if(!empty($tasklist)){
				
				
				$row = 0; 
				$rs_num = count($tasklist); 
				
				/*if($view){
					$arrSat = json_decode(json_encode($msObat), true);
					$arrS = [];
					foreach($arrSat as $ai){
						$arrS[$ai['id']] = $ai;
					}
				}*/
				foreach ($tasklist as $f){
					$task = $f->task;
					if($f->parent_name != '' && $f->parent_name != null){
						$task = $f->parent_name.' - '.$f->task;
					}

					$no = $row+1;
					$msStatus = $this->db->query("select * from master_tasklist_status order by order_no asc ")->result(); 

					
					if($checkin){ 
						$dt .= '<tr>';

						$dt .= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';

						$dt .= '<td>'.$task.'</td>';
						$dt .= '<td>'.$f->project_name.'</td>';
						$dt .= '<td>'.$f->due_date.'</td>';
						$dt .= '<td>'.$f->progress_percentage.'</td>';
						$dt .= '<td>'.$f->status_name.'</td>';
						
						$dt .= '</tr>';
					}else{ 
						$dt .= '<tr>';

						$dt .= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';

						$dt .= '<td>'.$task.'</td>';
						$dt .= '<td>'.$f->project_name.'</td>';
						$dt .= '<td>'.$f->due_date.'</td>';
						$dt .= '<td>'.$this->return_build_txt($f->progress_percentage,'progress_percentage['.$row.']','','progress_percentage','text-align: right;','data-id="'.$row.'" ').'</td>';

						
						$dt .= '<td>'.$this->return_build_chosenme($msStatus,'',isset($f->status_id)?$f->status_id:1,'','status['.$row.']','status','status','','id','name','','','',' data-id="'.$row.'" ').'</td>';
					
			
						$dt .= '</tr>';
					}


					$row++;
				}
				
				return [$dt,$row];

			}else{

				$data = '<td colspan="5">No Data</td>';
				
				return $data;
			}

		}else{ 

			$tasklist = $this->db->query("select a.*, b.full_name as employee_name, c.task as parent_name, d.name as status_name, e.title as project_name 
				from history_progress_tasklist aa
				left join tasklist a on a.id = aa.tasklist_id
				left join employees b on b.id = a.employee_id
				left join tasklist c on c.id = a.parent_id
				left join master_tasklist_status d on d.id = a.status_id
				left join data_project e on e.id = a.project_id
				where aa.time_attendances_id = '".$id."'")->result();

			if(!empty($tasklist)){
				
				$row = 0; 
				$rs_num = count($tasklist); 
				
				/*if($view){
					$arrSat = json_decode(json_encode($msObat), true);
					$arrS = [];
					foreach($arrSat as $ai){
						$arrS[$ai['id']] = $ai;
					}
				}*/
				foreach ($tasklist as $f){
					$task = $f->task;
					if($f->parent_name != '' && $f->parent_name != null){
						$task = $f->parent_name.' - '.$f->task;
					}

					$no = $row+1;
					
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
					$dt .= '<td>'.$task.'</td>';
					$dt .= '<td>'.$f->project_name.'</td>';
					$dt .= '<td>'.$f->due_date.'</td>';
					$dt .= '<td>'.$f->progress_percentage.'</td>';
					$dt .= '<td>'.$f->status_name.'</td>';
					$dt .= '</tr>';

					$row++;
				}
				
				return [$dt,$row];

			}else{

				$data = '<td colspan="5">No Data</td>';
				
				return $data;
			}

		}

		


	} 
	
	

}
