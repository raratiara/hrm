<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "emp_management/absensi_menu";
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
			'id',
			'dt.date_attendance',
			'dt.full_name',
			'dt.attendance_type',
			'dt.time_in',
			'dt.time_out',
			'dt.date_attendance_in',
			'dt.date_attendance_out',
			'dt.is_late_desc',
			'dt.is_leaving_office_early_desc',
			'dt.num_of_working_hours'
		];
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name, if(a.is_late = "Y","Late", "") as "is_late_desc", 
					if(a.is_leaving_office_early = "Y","Leaving Office Early","") as "is_leaving_office_early_desc" 
					from time_attendances a left join employees b on b.id = a.employee_id)dt';
		

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
				$row->date_attendance,
				$row->full_name,
				$row->attendance_type,
				$row->time_in,
				$row->time_out,
				$row->date_attendance_in,
				$row->date_attendance_out,
				$row->is_late_desc,
				$row->is_leaving_office_early_desc,
				$row->num_of_working_hours


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
		$datetime_in 		= date_create($post['attendance_in']); 
		$datetime_out 		= date_create($post['attendance_out']); 

		$f_datetime_in 		= date_format($datetime_in,"Y-m-d H:i:s");
		$f_datetime_out 	= date_format($datetime_out,"Y-m-d H:i:s");
		$f_time_in 			= date_format($datetime_in,"H:i:s");
		$f_time_out 		= date_format($datetime_out,"H:i:s");

		$timestamp1 		= strtotime($f_datetime_in); 
		$timestamp2 		= strtotime($f_datetime_out);
		$timestamp_timein 	= strtotime($f_time_in); 
		$timestamp_timeout 	= strtotime($f_time_out);
		$post_timein 		= strtotime($post['time_in']);
		$post_timeout 		= strtotime($post['time_out']);


		$is_late=''; $is_leaving_office_early = '';
		if($timestamp_timein > $post_timein){
			$is_late='Y';
		}
		if($timestamp_timeout < $post_timeout){
			$is_leaving_office_early = 'Y';
		}

  		$num_of_working_hours = abs($timestamp2 - $timestamp1)/(60); //menit


		
		

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

		return $rs = $this->db->insert($this->table_name, $data);
	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){

			$data = [
				'full_name' 					=> trim($post['full_name']),
				'nick_name' 					=> trim($post['nick_name']),
				'personal_email' 				=> trim($post['email']),
				'personal_phone' 				=> trim($post['phone']),
				'gender' 						=> trim($post['gender']),
				'ethnic' 						=> trim($post['ethnic']),
				'nationality' 					=> trim($post['nationality']),
				'last_education_id' 			=> trim($post['last_education']),
				'marital_status_id' 			=> trim($post['marital_status']),
				'tanggungan' 					=> trim($post['tanggungan']),
				'no_ktp' 						=> trim($post['no_ktp']),
				'sim_a' 						=> trim($post['sim_a']),
				'sim_c' 						=> trim($post['sim_c']),
				'no_npwp' 						=> trim($post['no_npwp']),
				'no_bpjs' 						=> trim($post['no_bpjs']),
				'place_of_birth' 				=> trim($post['place_of_birth']),
				'date_of_birth' 				=> trim($post['date_of_birth']),
				'address_1' 					=> trim($post['address1']),
				'address_2' 					=> trim($post['address2']),
				'postal_code' 					=> trim($post['postal_code']),
				'province_id' 					=> trim($post['province']),
				'regency_id' 					=> trim($post['regency']),
				'district_id' 					=> trim($post['district']),
				'village_id' 					=> trim($post['village']),
				'job_title_id' 					=> trim($post['job_title']),
				'department_id' 				=> trim($post['department']),
				'date_of_hire' 					=> trim($post['date_of_hire']),
				'date_end_probation' 			=> trim($post['date_end_prob']),
				'date_permanent' 				=> trim($post['date_permanent']),
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
				'date_resign_letter' 			=> trim($post['date_resign_letter']),
				'date_resign_active' 			=> trim($post['date_resign_active']),
				'resign_category' 				=> trim($post['resign_category']),
				'resign_reason' 				=> trim($post['resign_reason']),
				'resign_exit_interview_feedback' 	=> trim($post['resign_exit_feedback']),
				/*'emp_photo' 					=> trim($post['emp_photo']),
				'emp_signature' 				=> trim($post['emp_signature']),*/
				'updated_at' 					=> date("Y-m-d H:i:s"),
				'company_id' 					=> trim($post['company']),
				'division_id' 					=> trim($post['division']),
				'branch_id' 					=> trim($post['branch']),
				'section_id' 					=> trim($post['section'])
			];

			return  $rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(SELECT 
					    a.*,
					    b.name AS company_name,
					    c.name AS division_name,
					    d.name AS section_name,
					    e.name AS last_education_name,
					    f.name AS regency_name,
					    g.name AS village_name,
					    h.name AS department_name,
					    i.name AS emp_status_name,
					    j.full_name AS indirect_name,
					    k.name AS branch_name,
					    l.name AS marital_status_name,
					    m.name AS province_name,
					    n.name AS district_name,
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
					    master_education e ON e.id = a.last_education_id
					        LEFT JOIN
					    regencies f ON f.id = a.regency_id
					        LEFT JOIN
					    villages g ON g.id = a.village_id
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
					    provinces m ON m.id = a.province_id
					        LEFT JOIN
					    districts n ON n.id = a.district_id
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
