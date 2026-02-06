<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absence_report_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "hr_menu/absence_report_menu";
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

		$sIndexColumn = $this->primary_key;

		$dateNow = date("Y-m-d");

		$where_date=" and a.date_attendance = '".$dateNow."' ";
		if(isset($_GET['fldatestart'], $_GET['fldateend']) && $_GET['fldatestart'] != '' && $_GET['fldatestart'] != 0 && $_GET['fldateend'] != '' && $_GET['fldateend'] != 0){
			$where_date = " and (a.date_attendance between '".$_GET['fldatestart']."' and '".$_GET['fldateend']."') ";
		}

		$where_emp="";
		if(isset($_GET['flemployee']) && $_GET['flemployee'] != '' && $_GET['flemployee'] != 0){
			$where_emp = " and a.employee_id = '".$_GET['flemployee']."' ";
		}


		/*$sTable = '(select a.*, b.full_name, if(a.is_late = "Y","Late", "") as "is_late_desc", 
					(case 
					when a.leave_type != "" then concat("(",c.name,")") 
					when a.is_leaving_office_early = "Y" then "Leaving Office Early"
					else ""
					end) as is_leaving_office_early_desc
					from time_attendances a left join employees b on b.id = a.employee_id
					left join master_leaves c on c.id = a.leave_type
					'.$where_date.$where_emp.'
					
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
					where b.emp_source = "internal"
					'.$where_date.$where_emp.'
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
			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #112D80; border-color: #112D80;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
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
				$date_attendance_in = '<span style="color:red">'.$row->date_attendance_in.'</span>';
				$date_attendance_out = '<span style="color:red">'.$row->date_attendance_out.'</span>';
				$is_late_desc = '<span style="color:red">'.$row->is_late_desc.'</span>';
				$is_leaving_office_early_desc = '<span style="color:red">'.$row->is_leaving_office_early_desc.'</span>';
				$num_of_working_hours = '<span style="color:red">'.$row->num_of_working_hours.'</span>';
			}else{
				$id = $row->id;
				$dayName = $dayName;
				$date_attendance = $row->date_attendance;
				$full_name = $row->full_name;
				$attendance_type = $row->attendance_type;
				$time_in = $row->time_in;
				$time_out = $row->time_out;
				$date_attendance_in = $row->date_attendance_in;
				$date_attendance_out = $row->date_attendance_out;
				$is_late_desc = $row->is_late_desc;
				$is_leaving_office_early_desc = $row->is_leaving_office_early_desc;
				$num_of_working_hours = $row->num_of_working_hours;
			}

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

		$date_attendance 	= date_create($post['date_attendance']); 
		$post_timein 		= strtotime($post['time_in']);
		$post_timeout 		= strtotime($post['time_out']);

		$is_late=''; $is_leaving_office_early = ''; $num_of_working_hours='';

		$f_datetime_in='';
		if(!empty($post['attendance_in'])){
			$datetime_in 		= date_create($post['attendance_in']);
			$f_datetime_in 		= date_format($datetime_in,"Y-m-d H:i:s");
			$f_time_in 			= date_format($datetime_in,"H:i:s");
			$timestamp_timein 	= strtotime($f_time_in); 
			$timestamp1 		= strtotime($f_datetime_in); 

			if($timestamp_timein > $post_timein){
				$is_late='Y';
			}
		}

		$f_datetime_out='';
		if(!empty($post['attendance_out'])){
			$datetime_out 		= date_create($post['attendance_out']);
			$f_datetime_out 	= date_format($datetime_out,"Y-m-d H:i:s");
			$f_time_out 		= date_format($datetime_out,"H:i:s");
			$timestamp_timeout 	= strtotime($f_time_out);
			$timestamp2 		= strtotime($f_datetime_out);

			if($timestamp_timeout < $post_timeout){
				$is_leaving_office_early = 'Y';
			}
		}

		if(!empty($post['attendance_in']) && !empty($post['attendance_out'])){
			$num_of_working_hours = abs($timestamp2 - $timestamp1)/(60)/(60); //jam
		}

		
		

  		$data_attendances = $this->db->query("select * from time_attendances where date_attendance = '".date_format($date_attendance,"Y-m-d")."' and employee_id = '".$post['employee']."'")->result(); 

  		if(empty($data_attendances)){ 
  			$data = [
				'date_attendance' 			=> date_format($date_attendance,"Y-m-d"),
				'employee_id' 				=> trim($post['employee']),
				'attendance_type' 			=> trim($post['emp_type']),
				'time_in' 					=> trim($post['time_in']),
				'time_out' 					=> trim($post['time_out']),
				'date_attendance_in' 		=> $f_datetime_in,
				'date_attendance_out'		=> $f_datetime_out,
				'is_late'					=> $is_late,
				'is_leaving_office_early'	=> $is_leaving_office_early,
				'num_of_working_hours'		=> $num_of_working_hours,
				'created_at'				=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert($this->table_name, $data);

			return $rs;

  		}else return null;

		
	}  

	public function edit_data($post) { 
		$date_attendance 	= date_create($post['date_attendance']); 
		$post_timein 		= strtotime($post['time_in']);
		$post_timeout 		= strtotime($post['time_out']);

		$is_late=''; $is_leaving_office_early = ''; $num_of_working_hours='';

		$f_datetime_in='';
		if(!empty($post['attendance_in'])){
			$datetime_in 		= date_create($post['attendance_in']);
			$f_datetime_in 		= date_format($datetime_in,"Y-m-d H:i:s");
			$f_time_in 			= date_format($datetime_in,"H:i:s");
			$timestamp_timein 	= strtotime($f_time_in); 
			$timestamp1 		= strtotime($f_datetime_in); 

			if($timestamp_timein > $post_timein){
				$is_late='Y';
			}
		}

		$f_datetime_out='';
		if(!empty($post['attendance_out'])){
			$datetime_out 		= date_create($post['attendance_out']);
			$f_datetime_out 	= date_format($datetime_out,"Y-m-d H:i:s");
			$f_time_out 		= date_format($datetime_out,"H:i:s");
			$timestamp_timeout 	= strtotime($f_time_out);
			$timestamp2 		= strtotime($f_datetime_out);

			if($timestamp_timeout < $post_timeout){
				$is_leaving_office_early = 'Y';
			}
		}

		if(!empty($post['attendance_in']) && !empty($post['attendance_out'])){
			$num_of_working_hours = abs($timestamp2 - $timestamp1)/(60)/(60); //jam
		}
		


		if(!empty($post['id'])){
		
			$data = [
				/*'date_attendance' 		=> date_format($date_attendance,"Y-m-d"),
				'employee_id' 				=> trim($post['employee']),
				'attendance_type' 			=> trim($post['emp_type']),
				'time_in' 					=> trim($post['time_in']),
				'time_out' 					=> trim($post['time_out']),*/
				'date_attendance_in' 		=> $f_datetime_in,
				'date_attendance_out'		=> $f_datetime_out,
				'is_late'					=> $is_late,
				'is_leaving_office_early'	=> $is_leaving_office_early,
				'num_of_working_hours'		=> $num_of_working_hours,
				'updated_at'				=> date("Y-m-d H:i:s")
			];

			return  $rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(SELECT a.*, b.full_name as employee_name FROM time_attendances a left join employees b on b.id = a.employee_id
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
				'date_attendance' 	=> $v["B"],
				'employee_id' 		=> $v["C"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{ 
		$dateNow = date("Y-m-d");

		$where_date=" and a.date_attendance = '".$dateNow."' ";
		if(isset($_GET['fldatestart'], $_GET['fldateend']) && $_GET['fldatestart'] != '' && $_GET['fldatestart'] != 0 && $_GET['fldateend'] != '' && $_GET['fldateend'] != 0){
			$where_date = " and (a.date_attendance between '".$_GET['fldatestart']."' and '".$_GET['fldateend']."') ";
		}

		$where_emp="";
		if(isset($_GET['flemployee']) && $_GET['flemployee'] != '' && $_GET['flemployee'] != 0){
			$where_emp = " and a.employee_id = '".$_GET['flemployee']."' ";
		}


		
		$sql = 'select a.*, b.full_name, if(a.is_late = "Y","Late", "") as "is_late_desc", 
					(case 
					when a.leave_type != "" then concat("(",c.name,")") 
					when a.is_leaving_office_early = "Y" then "Leaving Office Early"
					else ""
					end) as is_leaving_office_early_desc
					from time_attendances a left join employees b on b.id = a.employee_id
					left join master_leaves c on c.id = a.leave_type
					where b.emp_source = "internal"
					'.$where_date.$where_emp.'
	   			ORDER BY id ASC
		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getDataEmployee($empid){ 

		$rs = $this->db->query("select * from employees where id = '".$empid."' ")->result(); 

		if($rs[0]->shift_type == 'Reguler'){
			$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 
			
			$dataX = array(
				'name' 		=> $dt[0]->name,
				'time_in' 	=> $dt[0]->time_in,
				'time_out' 	=> $dt[0]->time_out
			);
		}


		return $dataX;

	}

}