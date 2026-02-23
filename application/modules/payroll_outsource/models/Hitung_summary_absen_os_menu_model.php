<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_summary_absen_os_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "payroll_outsource/hitung_summary_absen_os_menu";
 	protected $table_name 				= _PREFIX_TABLE."summary_absen_outsource";
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
			'dt.project_name',
			'dt.month_name',
			'dt.tahun_penggajian',
			'dt.tgl_start_absen',
			'dt.tgl_end_absen',
			'dt.project_id'
		];
		
		
		$karyawan_id = $_SESSION['worker'];

		$sIndexColumn = $this->primary_key;

		$dateNow = date("Y-m-d");

		
		$where_project = "";
			if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
			$where_project = " and a.project_id = '".$_GET['flproject']."' ";
		}

		$sTable = '(select a.*, b.name_indo as month_name, c.project_name from summary_absen_outsource a 
					left join master_month b on b.id = a.bulan_penggajian
					left join project_outsource c on c.id = a.project_id 
					where 1=1 '.$where_project.'
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
			/*$cek_payslip = $this->db->query("select id from payroll_slip where employee_id = ".$row->emp_id." and periode_bulan = ".$row->bulan." and periode_tahun = '".$row->tahun."' ")->result();*/
			$cek_payslip = '';


			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #343851; border-color: #343851;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1" && empty($cek_payslip))  {
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1" && empty($cek_payslip))  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}

			$print_absen = '<a class="btn btn-default btn-xs" onclick="getReport_summ_absen_os('."'".$row->id."'".')"><i class="fa fa-download"></i> Report</a>';

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$print_absen.'
					'.$detail.'
					'.$edit.'
					'.$delete.'
				</div>',
				$row->id,
				$row->project_name,
				$row->month_name,
				$row->tahun_penggajian,
				$row->tgl_start_absen,
				$row->tgl_end_absen

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


	public function add_data($post)
	{
	    $getperiod_start = date_create($post['period_start']);
	    $getperiod_end   = date_create($post['period_end']);

	    $period_start = date_format($getperiod_start, "Y-m-d");
	    $period_end   = date_format($getperiod_end, "Y-m-d");

	    if (
	        empty($post['penggajian_month']) ||
	        empty($post['penggajian_year']) ||
	        empty($period_start) ||
	        empty($period_end)
	    ) {
	        
	        return [
			    "status" => false,
			    "msg" 	 => "Bulan Tahun Penggajian & Periode Absensi harus diisi"
			];
	    }

	    $bulan = trim($post['penggajian_month']);
	    $tahun = trim($post['penggajian_year']);

	    /* ===============================
	       FILTER EMPLOYEE / PROJECT
	    =============================== */

	    $filter_employee = "";
	    $filter_project  = "";

	    if ($post['is_all_project'] == 'Karyawan' && !empty($post['employeeIds'])) {
	        $ids = implode(',', array_map('intval', $post['employeeIds']));
	        $filter_employee = " AND b.id IN ($ids) ";
	    }

	    if ($post['is_all_project'] == 'Sebagian' && !empty($post['projectIds'])) {
	        $ids = implode(',', array_map('intval', $post['projectIds']));
	        $filter_project = " AND b.project_id IN ($ids) ";
	    }

	    /* ===============================
	       QUERY AGGREGASI (NO LOOP QUERY)
	    =============================== */

	    $sql = "
	    SELECT 
	        b.id as emp_id,
	        b.project_id,
	        b.total_hari_kerja,

	        SUM(CASE 
	            WHEN a.leave_absences_id IS NULL 
	            AND a.date_attendance_in IS NOT NULL 
	            THEN 1 ELSE 0 END) as total_masuk,

	        SUM(CASE 
	            WHEN a.leave_absences_id IS NOT NULL 
	            AND a.leave_type != 5 
	            AND h.status_approval = 2 
	            THEN 1 ELSE 0 END) as total_cuti,

	        SUM(CASE 
	            WHEN a.leave_absences_id IS NOT NULL 
	            AND a.leave_type = 5 
	            AND h.status_approval = 2 
	            THEN 1 ELSE 0 END) as total_sakit,

	        SUM(CASE WHEN a.is_late = 'Y' THEN 1 ELSE 0 END) as total_late,

	        SUM(IFNULL(i.num_of_hour,0)) as total_jam_lembur,
	        SUM(IFNULL(i.amount,0)) as total_lembur,
	        
	        SUM(IFNULL(a.num_of_working_hours,0)) as total_jam_kerja

	    FROM employees b
	    LEFT JOIN time_attendances a 
	        ON a.employee_id = b.id
	        AND a.date_attendance BETWEEN ? AND ?

	    LEFT JOIN leave_absences h 
	        ON h.id = a.leave_absences_id

	    LEFT JOIN overtimes i 
	        ON i.employee_id = a.employee_id 
	        AND a.date_attendance = DATE(i.datetime_start)
	        AND i.type = 1 
	        AND i.status_id = 2

	    WHERE b.emp_source = 'outsource'
	    AND b.status_id = 1
	    $filter_employee
	    $filter_project

	    GROUP BY b.id
	    ";

	    $data_summary = $this->db->query(
	        $sql,
	        [$period_start, $period_end]
	    )->result();

	    if (empty($data_summary)) {
	        return [
			    "status" => false,
			    "msg" 	 => "Data gagal disimpan"
			];
	    }

	    /* ===============================
	       PROCESS PER PROJECT (HEADER)
	    =============================== */

	    $insert_batch = [];

	    foreach ($data_summary as $row) {

	        if (empty($row->project_id)) continue;

	        /* ---- cek / buat header per project ---- */

	        $header = $this->db
	            ->where('project_id', $row->project_id)
	            ->where('bulan_penggajian', $bulan)
	            ->where('tahun_penggajian', $tahun)
	            ->get('summary_absen_outsource')
	            ->row();

	        if (!$header) {

	            $this->db->insert('summary_absen_outsource', [
	                'project_id'       => $row->project_id,
	                'bulan_penggajian' => $bulan,
	                'tahun_penggajian' => $tahun,
	                'tgl_start_absen'  => $period_start,
	                'tgl_end_absen'    => $period_end,
	                'created_at'       => date("Y-m-d H:i:s"),
	                'created_by'       => $_SESSION['worker']
	            ]);

	            $header_id = $this->db->insert_id();
	        } else {
	            $header_id = $header->id;
	        }

	        /* ---- hitung alfa ---- */

	        $ttl_ada_absen = 
	            $row->total_masuk +
	            $row->total_cuti +
	            $row->total_sakit;

	        $total_alfa = max(
	            0,
	            (int)$row->total_hari_kerja - (int)$ttl_ada_absen
	        );

	        /* ---- siapkan batch insert ---- */

	        $insert_batch[] = [
	            'summary_absen_outsource_id' => $header_id,
	            'emp_id'             => $row->emp_id,
	            'total_hari_kerja'   => $row->total_hari_kerja,
	            'total_masuk'        => $row->total_masuk,
	            'total_ijin'         => $row->total_cuti,
	            'total_cuti'         => $row->total_cuti,
	            'total_alfa'         => $total_alfa,
	            'total_lembur'       => $row->total_lembur,
	            'total_jam_kerja'    => $row->total_jam_kerja,
	            'total_jam_lembur'   => $row->total_jam_lembur,
	            'created_at'         => date("Y-m-d H:i:s"),
	            'created_by'         => $_SESSION['worker']
	        ];
	    }

	    /* ===============================
	       INSERT BATCH DETAIL
	    =============================== */

	    if (!empty($insert_batch)) {
	        $this->db->insert_batch(
	            'summary_absen_outsource_detail',
	            $insert_batch
	        );
	    }

	    return [
		    "status" => true,
		    "msg" => "Data berhasil disimpan"
		];
	}

	


	public function add_data_old($post) { 
		$getperiod_start 	= date_create($post['period_start']); 
		$getperiod_end 		= date_create($post['period_end']); 
		$period_start 		= date_format($getperiod_start,"Y-m-d");
		$period_end 		= date_format($getperiod_end,"Y-m-d");

  		if(!empty($post['penggajian_month']) && !empty($post['penggajian_year']) && !empty($period_start) && !empty($period_end)){ 
  			$where_date = " and (date_attendance between '".$period_start."' and '".$period_end."')";

  			
			if ($post['is_all_project'] == 'Karyawan') {
			    if (!empty($post['employeeIds']) && is_array($post['employeeIds'])) {
			        $this->db->where_in('id', $post['employeeIds']);
			    }
			}else if ($post['is_all_project'] == 'Sebagian') {
			    if (!empty($post['projectIds']) && is_array($post['projectIds'])) {
			        $this->db->where_in('project_id', $post['projectIds']);
			    }
			}

			$data_os = $this->db
			    ->select('id, total_hari_kerja, project_id')
			    ->from('employees')
			    ->where('emp_source', 'outsource')
			    ->where('status_id', 1)
			    ->get()
			    ->result();


  			if(!empty($data_os)){
  				foreach($data_os as $rowdata_os){
  					if(!empty($rowdata_os->project_id)){

  						$emp_id = $rowdata_os->id;
	  					$total_hari_kerja = (int)$rowdata_os->total_hari_kerja;

	  					
						$sql = 'select a.*, b.full_name, if(a.is_late = "Y","Late", "") as "is_late_desc", 
								(case when a.leave_type != "" then concat("(",c.name,")") 
									  when a.is_leaving_office_early = "Y" then "Leaving Office Early"
									  else "" end) as is_leaving_office_early_desc,
								d.name as branch_name, e.full_name as direct_name,
								(case when a.leave_absences_id is not null and a.leave_type != 5 and h.status_approval = 2 then "1" else "" end) as cuti,
								(case when a.leave_absences_id is null and a.date_attendance_in is not null then "1" else "" end) as masuk,
								(case when a.leave_absences_id is null and a.date_attendance_in is not null and a.work_location = "onsite" then "1" else "" end) as piket,
								(case when a.leave_absences_id is null and a.date_attendance_in is not null and a.work_location = "wfh" then "1" else "" end) as wfh,
								a.notes as keterangan
								,b.emp_code, f.name as dept_name, g.name as work_location_name,
								(case when a.leave_absences_id is null and a.date_attendance_in is not null and a.work_location = "wfo" then "1" else "" end) as wfo,
								(case when a.leave_absences_id is not null and leave_type = 5 and h.status_approval = 2 then "1" else "" end) as sakit,
								(case when a.is_late = "Y" then "1" else "" end) as late,
								(case when a.is_leaving_office_early = "Y" then "1" else "" end) as leaving_early,
							    i.num_of_hour as overtime_num_of_hour,
							    i.amount as overtime_amount,
							    (case when i.id is not null then "1" else "" end) as overtime
							from time_attendances a 
							left join employees b on b.id = a.employee_id
							left join master_leaves c on c.id = a.leave_type
							left join branches d on d.id = b.branch_id
							left join employees e on e.id = b.direct_id
							left join departments f on f.id = b.department_id
							left join master_work_location g on g.id = b.work_location
							left join leave_absences h on h.id = a.leave_absences_id
							left join overtimes i on i.employee_id = a.employee_id 
							and (a.date_attendance = DATE_FORMAT(i.datetime_start, "%Y-%m-%d"))
							and i.type = 1 and i.status_id = 2
							where a.employee_id = "'.$emp_id.'" '.$where_date.'
							ORDER BY id ASC';

		                $res = $this->db->query($sql);
		                $data_absensi = $res->result();


		                $ttl_cuti=0; $ttl_masuk=0; $ttl_piket=0; $ttl_wfh=0;
		                $ttl_wfo=0; $ttl_sakit=0; $ttl_working_hours=0; $ttl_late=0; $ttl_leaving_early=0;
		                $ttl_overtime_num_of_hour=0; $ttl_overtime_amount=0; $ttl_overtime=0;
		                
		                if(!empty($data_absensi)){ 
		                	foreach($data_absensi as $rowdata){
			                    $ttl_cuti += ($rowdata->cuti != '' ? $rowdata->cuti : 0);
			                    $ttl_masuk += ($rowdata->masuk != '' ? $rowdata->masuk : 0);
			                    $ttl_piket += ($rowdata->piket != '' ? $rowdata->piket : 0);
			                    $ttl_wfh   += ($rowdata->wfh != '' ? $rowdata->wfh : 0);
			                    $ttl_wfo   += ($rowdata->wfo != '' ? $rowdata->wfo : 0);
			                    $ttl_sakit += ($rowdata->sakit != '' ? $rowdata->sakit : 0);
			                    $ttl_working_hours += ($rowdata->num_of_working_hours != '' ? $rowdata->num_of_working_hours : 0);
			                    $ttl_late += ($rowdata->late != '' ? $rowdata->late : 0);
			                    $ttl_leaving_early += ($rowdata->leaving_early != '' ? $rowdata->leaving_early : 0);
			                    $ttl_overtime_num_of_hour += ($rowdata->overtime_num_of_hour != '' ? $rowdata->overtime_num_of_hour : 0);
			                    $ttl_overtime_amount += ($rowdata->overtime_amount != '' ? $rowdata->overtime_amount : 0);
			                    $ttl_overtime += ($rowdata->overtime != '' ? $rowdata->overtime : 0);
			                }
		                }
		                

		                $ttl_ada_absen = $ttl_masuk+$ttl_cuti+$ttl_sakit;
		                $ttl_alfa = $total_hari_kerja-$ttl_ada_absen;
		                $total_alfa = 0;
		                if($ttl_alfa > 0){
		                	$total_alfa = $ttl_alfa;
		                }


		                //save header
		                $data_summary = $this->db->query("select * from summary_absen_outsource where project_id = '".$rowdata_os->project_id."' and bulan_penggajian = '".$post['penggajian_month']."' and tahun_penggajian = '".$post['penggajian_year']."' ")->result();
	  					if(empty($data_summary)){ /// add header + add detail
	  						$data = [
	  							'project_id' 		=> $rowdata_os->project_id,
								'bulan_penggajian' 	=> trim($post['penggajian_month']),
								'tahun_penggajian' 	=> trim($post['penggajian_year']),
								'tgl_start_absen' 	=> $period_start,
								'tgl_end_absen' 	=> $period_end
							];
							$this->db->insert($this->table_name, $data);
							$lastId = $this->db->insert_id();


							$data_dtl = [
								'summary_absen_outsource_id' => $lastId,
								'emp_id' 			=> $emp_id,
								'total_hari_kerja'  => $total_hari_kerja,
								'total_masuk'  		=> $ttl_masuk,
								'total_ijin'  		=> $ttl_cuti,
								'total_cuti'  		=> $ttl_cuti,
								'total_alfa'  		=> $total_alfa,
								'total_lembur'  	=> $ttl_overtime,
								'total_jam_kerja'  	=> $ttl_working_hours,
								'total_jam_lembur'  => $ttl_overtime_num_of_hour,
								'created_at'		=> date("Y-m-d H:i:s"),
								'created_by' 		=> $_SESSION['worker']
							];
							$rs = $this->db->insert("summary_absen_outsource_detail", $data_dtl);

	  					}else{ /// add or edit detail

				            $data_summary_detail = $this->db->query("select a.* from summary_absen_outsource_detail a 
							left join summary_absen_outsource b on b.id = a.summary_absen_outsource_id
							where a.emp_id = '".$emp_id."' and b.bulan_penggajian = ".$post['penggajian_month']." and b.tahun_penggajian = '".$post['penggajian_year']."' ")->result();

				            if(empty($data_summary_detail)){ ///ADD 
				            	$data_dtl = [
									'summary_absen_outsource_id' => $data_summary[0]->id,
									'emp_id' 			=> $emp_id,
									'total_hari_kerja'  => $total_hari_kerja,
									'total_masuk'  		=> $ttl_masuk,
									'total_ijin'  		=> $ttl_cuti,
									'total_cuti'  		=> $ttl_cuti,
									'total_alfa'  		=> $total_alfa,
									'total_lembur'  	=> $ttl_overtime,
									'total_jam_kerja'  	=> $ttl_working_hours,
									'total_jam_lembur'  => $ttl_overtime_num_of_hour,
									'created_at'		=> date("Y-m-d H:i:s"),
									'created_by' 		=> $_SESSION['worker']
								];
								$rs = $this->db->insert("summary_absen_outsource_detail", $data_dtl);

				            }else{ /// UPDATE
				            	$data_dtl = [
									'summary_absen_outsource_id' => $data_summary[0]->id,
									'emp_id' 			=> $emp_id,
									'total_hari_kerja'  => $total_hari_kerja,
									'total_masuk'  		=> $ttl_masuk,
									'total_ijin'  		=> $ttl_cuti,
									'total_cuti'  		=> $ttl_cuti,
									'total_alfa'  		=> $total_alfa,
									'total_lembur'  	=> $ttl_overtime,
									'total_jam_kerja'  	=> $ttl_working_hours,
									'total_jam_lembur'  => $ttl_overtime_num_of_hour,
									'created_at'		=> date("Y-m-d H:i:s"),
									'created_by' 		=> $_SESSION['worker']
								];
								$rs = $this->db->update("summary_absen_outsource_detail", $data_dtl, "id = '".$data_summary_detail[0]->id."'");
				            }

	  					}
  					}
  				}
  				return $rs;
  			}

  		}else{
  			echo "Bulan Tahun Penggajian & Periode Absensi harus diisi"; 
  		}

		
	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){

			$getperiod_start 	= date_create($post['period_start']); 
			$getperiod_end 		= date_create($post['period_end']); 
			$period_start 		= date_format($getperiod_start,"Y-m-d");
			$period_end 		= date_format($getperiod_end,"Y-m-d");

	  		if(!empty($post['penggajian_month']) && !empty($post['penggajian_year']) && !empty($period_start) && !empty($period_end)){ 
	  			
	  			$data = [
					'bulan_penggajian' 	=> trim($post['penggajian_month']),
					'tahun_penggajian' 	=> trim($post['penggajian_year']),
					'tgl_start_absen' 	=> $period_start,
					'tgl_end_absen' 	=> $period_end
				];
				$rs = $this->db->update("summary_absen_outsource", $data, "id = '".$post['id']."'");


				if(isset($post['hdnempid'])){
					$item_num = count($post['hdnempid']); // cek sum
					$item_len_min = min(array_keys($post['hdnempid'])); // cek min key index
					$item_len = max(array_keys($post['hdnempid'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						$hdnid = trim($post['hdnid'][$i]);

						if(!empty($hdnid)){ //update
							if(isset($post['hdnempid'][$i])){
								$itemData = [
									'total_hari_kerja'	=> trim($post['ttl_hari_kerja'][$i]),
									'total_masuk' 		=> trim($post['ttl_masuk'][$i]),
									'total_ijin' 		=> trim($post['ttl_ijin'][$i]),
									'total_cuti'		=> trim($post['ttl_cuti'][$i]),
									'total_alfa' 		=> trim($post['ttl_alfa'][$i]),
									'total_lembur' 		=> trim($post['ttl_lembur'][$i]),
									'total_jam_kerja' 	=> trim($post['ttl_jam_kerja'][$i]),
									'total_jam_lembur' 	=> trim($post['ttl_jam_lembur'][$i])
								];

								$this->db->update("summary_absen_outsource_detail", $itemData, "id = '".$hdnid."'");
							}
						}else{ //insert
							if(isset($post['hdnempid'][$i])){
								$itemData = [
									'summary_absen_outsource_id'	=> $post['id'],
									'total_hari_kerja'	=> trim($post['ttl_hari_kerja'][$i]),
									'total_masuk' 		=> trim($post['ttl_masuk'][$i]),
									'total_ijin' 		=> trim($post['ttl_ijin'][$i]),
									'total_cuti'		=> trim($post['ttl_cuti'][$i]),
									'total_alfa' 		=> trim($post['ttl_alfa'][$i]),
									'total_lembur' 		=> trim($post['ttl_lembur'][$i]),
									'total_jam_kerja' 	=> trim($post['ttl_jam_kerja'][$i]),
									'total_jam_lembur' 	=> trim($post['ttl_jam_lembur'][$i])
								];

								$this->db->insert('summary_absen_outsource_detail', $itemData);
							}
						}
					}
				}

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
				    "msg" 	 => "Bulan Tahun Penggajian & Periode Absensi harus diisi"
				];
	  		}
		}else{
			return [
			    "status" => false,
			    "msg" 	 => "ID tidak ditemukan"
			];
		}
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.name_indo as month_name, c.project_name from summary_absen_outsource a 
					left join master_month b on b.id = a.bulan_penggajian
					left join project_outsource c on c.id = a.project_id
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
		$where_date = " where 1=1 ";

		if (
		    isset($_GET['fldatestart'], $_GET['fldateend']) &&
		    $_GET['fldatestart'] != '' &&
		    $_GET['fldateend'] != '' &&
		    $_GET['fldatestart'] != 0 &&
		    $_GET['fldateend'] != 0
		) {
		    $where_date = " WHERE (a.tgl_start <= '".$_GET['fldateend']."'
		                    AND a.tgl_end   >= '".$_GET['fldatestart']."') ";
		}

		$where_emp="";
		if(isset($_GET['flemployee']) && $_GET['flemployee'] != '' && $_GET['flemployee'] != 0){
			$where_emp = " and a.emp_id = '".$_GET['flemployee']."' ";
		}


		
		$sql = 'select a.*, b.full_name, c.name_indo as month_name from summary_absen_outsource a left join employees b on b.id = a.emp_id left join master_month c on c.id = a.bulan '.$where_date.$where_emp.'
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


	public function getNewAbsenOSRow($row,$id=0,$project,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getAbsenOSRows($id,$project,$view);
		} else { 
			$data = '';
			$no = $row+1;
			
			$data 	.= '<td>'.$this->return_build_txt('','ttl_hari_kerja','ttl_hari_kerja','ttl_hari_kerja','text-align: right;','data-id="'.$row.'" ').'<input type="hidden" id="hdnid" name="hdnid" value=""/></td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_masuk','ttl_masuk','ttl_masuk','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_ijin','ttl_ijin','ttl_ijin','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_cuti','ttl_cuti','ttl_cuti','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_alfa','ttl_alfa','ttl_alfa','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_lembur','ttl_lembur','ttl_lembur','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_jam_kerja','ttl_jam_kerja','ttl_jam_kerja','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_jam_lembur','ttl_jam_lembur','ttl_jam_lembur','text-align: right;','data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="btn btn-md btn-danger ibtnDel" onclick="del_fpp(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getAbsenOSRows($id,$project,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select 
								    a.id AS employee_id,
								    a.emp_code,
								    a.full_name,
								    a.project_id,
								    b.*
								FROM employees a
								LEFT JOIN summary_absen_outsource_detail b 
								    ON b.emp_id = a.id
								    AND b.summary_absen_outsource_id = ".$id."
								WHERE a.emp_source = 'outsource'
								  AND a.status_id = 1
								  AND a.project_id = '".$project."'
								ORDER BY a.full_name ASC
								")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$f->emp_code.'</td>';
					$dt .= '<td>'.$f->full_name.'<input type="hidden" id="hdnempid" name="hdnempid['.$row.']" value="'.$f->employee_id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_hari_kerja,'ttl_hari_kerja['.$row.']','','ttl_hari_kerja','text-align: right;','data-id="'.$row.'" ').'<input type="hidden" id="hdnid" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_masuk,'ttl_masuk['.$row.']','','ttl_masuk','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_ijin,'ttl_ijin['.$row.']','','ttl_ijin','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_cuti,'ttl_cuti['.$row.']','','ttl_cuti','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_alfa,'ttl_alfa['.$row.']','','ttl_alfa','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_jam_kerja,'ttl_jam_kerja['.$row.']','','ttl_jam_kerja','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_jam_lembur,'ttl_jam_lembur['.$row.']','','ttl_jam_lembur','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_lembur,'ttl_lembur['.$row.']','','ttl_lembur','text-align: right;','data-id="'.$row.'" ').'</td>';

					
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
					
					$dt .= '<td>'.$f->emp_code.'</td>';
					$dt .= '<td>'.$f->full_name.'</td>';
					$dt .= '<td>'.$f->total_hari_kerja.'</td>';
					$dt .= '<td>'.$f->total_masuk.'</td>';
					$dt .= '<td>'.$f->total_ijin.'</td>';
					$dt .= '<td>'.$f->total_cuti.'</td>';
					$dt .= '<td>'.$f->total_alfa.'</td>';
					$dt .= '<td>'.$f->total_jam_kerja.'</td>';
					$dt .= '<td>'.$f->total_jam_lembur.'</td>';
					$dt .= '<td>'.$f->total_lembur.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}



	public function getAbsenProject($project, $bln, $thn){ 

		$rs = $this->db->query("select a.*, b.project_id from summary_absen_outsource a 
								left join employees b on b.id = a.emp_id
								where a.bulan = '".$bln."' and a.tahun = '".$thn."' and b.project_id = '".$project."'")->result(); 

		

		return $rs;

	}


	/*public function getNewEditAbsenRow($row,$id=0,$project,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getEditAbsenRow($id,$project,$view);
		} else { 
			$data = '';
			$no = $row+1;

			$data 	.= '<td>No Data</td>';

			
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getEditAbsenRow($id,$project,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select a.id as employee_id, a.emp_code, a.full_name, a.project_id, b.* from         employees a left join summary_absen_outsource_detail b on b.emp_id = a.id
			where a.emp_source = 'outsource' and a.status_id = 1 and (b.summary_absen_outsource_id = ".$id." or a.project_id = ".$project.")")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$f->emp_code.'</td>';
					$dt .= '<td>'.$f->full_name.'<input type="hidden" id="hdnempid" name="hdnempid['.$row.']" value="'.$f->employee_id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_hari_kerja,'ttl_hari_kerja_edit['.$row.']','','ttl_hari_kerja_edit','text-align: right;','data-id="'.$row.'" ').'<input type="hidden" id="hdnid_edit" name="hdnid_edit['.$row.']" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_masuk,'ttl_masuk_edit['.$row.']','','ttl_masuk_edit','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_ijin,'ttl_ijin_edit['.$row.']','','ttl_ijin_edit','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_cuti,'ttl_cuti_edit['.$row.']','','ttl_cuti_edit','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_alfa,'ttl_alfa_edit['.$row.']','','ttl_alfa_edit','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_lembur,'ttl_lembur_edit['.$row.']','','ttl_lembur_edit','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_jam_kerja,'ttl_jam_kerja_edit['.$row.']','','ttl_jam_kerja_edit','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_jam_lembur,'ttl_jam_lembur_edit['.$row.']','','ttl_jam_lembur_edit','text-align: right;','data-id="'.$row.'" ').'</td>';

					
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
					
					$dt .= '<td>'.$f->emp_code.'</td>';
					$dt .= '<td>'.$f->full_name.'</td>';
					$dt .= '<td>'.$f->total_hari_kerja.'</td>';
					$dt .= '<td>'.$f->total_masuk.'</td>';
					$dt .= '<td>'.$f->total_ijin.'</td>';
					$dt .= '<td>'.$f->total_cuti.'</td>';
					$dt .= '<td>'.$f->total_alfa.'</td>';
					$dt .= '<td>'.$f->total_lembur.'</td>';
					$dt .= '<td>'.$f->total_jam_kerja.'</td>';
					$dt .= '<td>'.$f->total_jam_lembur.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}*/

}