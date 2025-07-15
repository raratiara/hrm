<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "profile/profile_menu";
 	protected $table_name 				= _PREFIX_TABLE."employees";
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
			'dt.id',
			'dt.id',
			'dt.id'
		];
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select * from employees)dt';
		

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
				$row->id,
				$row->id,
				$row->id

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

	

	public function getRowData($id) { 
		$yearMonth 	= date("Y-m");
		$datenow 	= date("Y-m-d");


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
					    left join master_grade r on r.id = a.grade_id)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		$leave = $this->db->query("select a.*, b.status_approval from time_attendances a left join leave_absences b on b.id = a.leave_absences_id
			where a.employee_id = '".$id."'
			and (a.leave_absences_id is not null or a.leave_absences_id != '') 
			and (DATE_FORMAT(a.date_attendance, '%Y-%m') = '".$yearMonth."')
			and b.status_approval = 2")->result(); //yg udh di approve

		$workhours = $this->db->query("select sum(a.num_of_working_hours) as ttl_workhours #a.*, b.status_approval 
					from time_attendances a left join leave_absences b on b.id = a.leave_absences_id
					where a.employee_id = '".$id."' and (DATE_FORMAT(a.date_attendance, '%Y-%m') = '".$yearMonth."')")->result();

		$tasklist = $this->db->query("select a.status_id, b.name as status_name, COUNT(*) AS total
					FROM tasklist a left join master_tasklist_status b on b.id = a.status_id
					WHERE a.employee_id = '".$id."' GROUP BY a.status_id")->result();
		$statusTotals = [];
		foreach ($tasklist as $item) {
		    $statusName = strtolower($item->status_name); // biar konsisten huruf kecil semua (opsional)
		    $statusTotals[$statusName] = $item->total;
		}
		$ttl_tasklist_open = isset($statusTotals['open']) ? $statusTotals['open'] : 0;
		$ttl_tasklist_inprogress = isset($statusTotals['progress']) ? $statusTotals['progress'] : 0;
		$ttl_tasklist_closed = isset($statusTotals['closed']) ? $statusTotals['closed'] : 0;


		$ttl_leave=0; $ttl_workhours=0;
		if(!empty($leave)){
			$ttl_leave = count($leave);
		}
		if(!empty($workhours)){
			$ttl_workhours = $workhours[0]->ttl_workhours;
		}

		$ttl_sisa_cuti=0;
		$sisaCuti = $this->db->query("select sum(sisa_cuti) as ttl_sisa_cuti from total_cuti_karyawan where employee_id = '".$id."' and status = 1")->result(); 
		if(!empty($sisaCuti)){
			$ttl_sisa_cuti = $sisaCuti[0]->ttl_sisa_cuti;
		}
		$ttl_plafon_reimburs = '-';

		$listBday = $this->db->query("select a.full_name as name, a.emp_code, a.emp_photo, if(b.name = '' or  b.name is null,'-',b.name) as divname
					FROM employees a left join divisions b on b.id = a.division_id
					WHERE DATE_FORMAT(a.date_of_birth, '%m-%d') = '".date("m-d")."' and a.status_id = 1
					")->result(); 

		/*$listInfo = $this->db->query("select * from office_info
					where (show_date_start is not null and show_date_start != '0000-00-00' and show_date_end is not null and show_date_end != '0000-00-00' and ('".$datenow."' between show_date_start and show_date_end))
					or
					((show_date_start is not null and show_date_start != '0000-00-00') and (show_date_end is null or show_date_end = '0000-00-00') and ('".$datenow."' >= show_date_start))
					or 
					((show_date_end is not null and show_date_end != '0000-00-00') and (show_date_start is null or show_date_start = '0000-00-00') and ('".$datenow."' <= show_date_end))
					")->result(); */
		$listInfo = $this->db->query("select * from office_info
					where (show_date_start is not null and show_date_end is not null and ('".$datenow."' between show_date_start and show_date_end))
					or
					((show_date_start is not null) and (show_date_end is null) and ('".$datenow."' >= show_date_start))
					or 
					((show_date_end is not null) and (show_date_start is null) and ('".$datenow."' <= show_date_end))
					")->result(); 

		/*$listInfo = $this->db->query("select * from office_info
					where id='180' 
					")->result(); */

		
		
		$dataX = array(
			'dtEmp' 					=> $rs,
			/*'ttl_leave' 				=> $ttl_leave,
			'ttl_workhours' 			=> $workhours,*/
			'ttl_tasklist_open' 		=> $ttl_tasklist_open,
			'ttl_tasklist_inprogress' 	=> $ttl_tasklist_inprogress,
			'ttl_tasklist_closed' 		=> $ttl_tasklist_closed,
			'ttl_sisa_cuti' 			=> $ttl_sisa_cuti,
			'ttl_plafon_reimburs' 		=> $ttl_plafon_reimburs,
			'birthdays' 				=> $listBday,
			'events' 					=> $listInfo
			
		);

		return $dataX;
	} 

	
	public function eksport_data()
	{
		$sql = "select * from employees
	   		ORDER BY id ASC
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}

	

}
