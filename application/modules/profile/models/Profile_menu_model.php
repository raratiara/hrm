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


	public function calculateFatigue_old($sleepHours, $bpm, $spo2) { 
	    $score = 0;

	    // Sleep
	    if ($sleepHours < 5) $score += 3;      // sangat kurang tidur
	    elseif ($sleepHours < 7) $score += 2;  // kurang tidur
	    else $score += 1;                      // cukup tidur

	    // BPM
	    if ($bpm > 100) $score += 3;           // tinggi
	    elseif ($bpm > 90) $score += 2;        // agak tinggi
	    else $score += 1;                      // normal

	    // SpO2
	   	if ($spo2 < 90) $score += 3;           // sangat rendah
	    elseif ($spo2 < 95) $score += 2;       // agak rendah
	    else $score += 1;                      // normal



	    // Hitung persentase
	    $percentage = round((($score - 3) / (9 - 3)) * 100);

	    // Kategori Fatigue
	    if ($percentage <= 30) $category = "Low";
	    elseif ($percentage <= 65) $category = "Moderate";
	    else $category = "High";


	    // Return dalam bentuk array
	    return [
	        'score'      => $score,
	        'percentage' => $percentage,
	        'category'   => $category
	    ];
	}


	public function calculateFatigue($sleepHours=0, $bpm=0, $spo2=0) {
	    $score = 0;
	    $minScore = 0;
	    $maxScore = 0;

	    // Sleep
	    if ($sleepHours > 0) { // 0 = tidak ada data
	        $minScore += 1; $maxScore += 3;
	        if ($sleepHours < 5) $score += 3;
	        elseif ($sleepHours < 7) $score += 2;
	        else $score += 1;
	    }

	    // SpO2
	    if ($spo2 > 0) { // 0 = tidak ada data
	        $minScore += 1; $maxScore += 3;
	        if ($spo2 < 90) $score += 3;
	        elseif ($spo2 < 95) $score += 2;
	        else $score += 1;
	    }

	    // BPM
	    if ($bpm > 0) { // 0 = tidak ada data
	        $minScore += 1; $maxScore += 3;
	        if ($bpm > 100) $score += 3;
	        elseif ($bpm > 90) $score += 2;
	        else $score += 1;
	    }

	    // Kalau semua kosong (0)
	    if ($maxScore == 0) {
	        return [
	            "score" => 0,
	            "percentage" => 0,
	            "category" => "-"
	        ];
	    }

	    // Hitung persentase
	    $fatiguePercent = (($score - $minScore) / ($maxScore - $minScore)) * 100;

	    // Kategori
	    if ($fatiguePercent <= 30) $status = "Low";
	    elseif ($fatiguePercent <= 65) $status = "Moderate";
	    else $status = "High";

	    return [
	        "score" => $score,
	        "percentage" => round($fatiguePercent, 2),
	        "category" => $status
	    ];

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

		$listBday = $this->db->query("select a.full_name as name, a.emp_code, a.emp_photo, if(b.name = '' or  b.name is null,'-',b.name) as divname
					FROM employees a left join divisions b on b.id = a.division_id
					WHERE DATE_FORMAT(a.date_of_birth, '%m-%d') = '".date("m-d")."' and a.status_id = 1
					")->result(); 

		$listInfo = $this->db->query("select * from office_info
					where (show_date_start is not null and show_date_end is not null and ('".$datenow."' between show_date_start and show_date_end))
					or
					((show_date_start is not null) and (show_date_end is null) and ('".$datenow."' >= show_date_start))
					or 
					((show_date_end is not null) and (show_date_start is null) and ('".$datenow."' <= show_date_end))
					")->result(); 

		
		/*$data_tasklist = $this->db->query("
							select 
							    a.id,
							    a.employee_id,
							    a.task,
							    a.progress_percentage,
							    a.parent_id,
							    a.project_id,
							    CASE 
							        WHEN a.parent_id = 0 AND EXISTS (
							            SELECT 1 FROM tasklist x WHERE x.parent_id = a.id
							        ) THEN 'parent'
							        WHEN a.parent_id != 0 THEN 'child'
							        ELSE 'standalone'
							    END AS task_type,
							    CASE
							        WHEN a.id IS NULL AND a.project_id IS NOT NULL
							            THEN LPAD(a.project_id, 5, '0')
							        WHEN a.parent_id = 0 AND a.project_id IS NOT NULL
							            THEN CONCAT(LPAD(a.project_id, 5, '0'), '.', LPAD(a.id, 5, '0'))
							        WHEN a.parent_id = 0 AND a.project_id IS NULL
							            THEN CONCAT('99999.', LPAD(a.id, 5, '0'))
							        WHEN a.parent_id != 0
							            THEN (
							                SELECT CONCAT(
							                    LPAD(COALESCE(p.project_id, 99999), 5, '0'), '.', 
							                    LPAD(p.id, 5, '0'), '.', 
							                    LPAD(a.id, 5, '0')
							                )
							                FROM tasklist p
							                WHERE p.id = a.parent_id
							                LIMIT 1
							            )
							        ELSE CONCAT('99999.99999.', LPAD(a.id, 5, '0'))
							    END AS sort_key,
							    a.due_date,
							    a.status_id,
							    b.title AS project_name
							FROM tasklist a
							LEFT JOIN data_project b ON b.id = a.project_id
							WHERE
							    a.employee_id = '".$id."'
							    OR a.id IN (
							        SELECT DISTINCT parent_id
							        FROM tasklist
							        WHERE employee_id = '".$id."'
							        AND parent_id IS NOT NULL
							        AND parent_id != 0
							    )
							UNION ALL

							SELECT 
							    NULL AS id,
							    NULL AS employee_id,
							    b.title AS task,
							    NULL AS progress_percentage,
							    NULL AS parent_id,
							    b.id AS project_id,
							    'project' AS task_type,
							    LPAD(b.id, 5, '0') AS sort_key,
							    NULL AS due_date,
							    NULL AS status_id,
							    b.title AS project_name
							FROM data_project b
							WHERE b.id IN (
							    SELECT DISTINCT project_id
							    FROM tasklist
							    WHERE project_id IS NOT NULL AND project_id != 0
							)

							ORDER BY sort_key;
						")->result();*/


		/// SISA PLAFON REIMBURS
		$getplafon = $this->db->query("select a.id, a.grade_id,
						  MAX(CASE WHEN b.reimburs_type_id = 1 THEN b.nominal_plafon END) AS rawat_jalan,
						  MAX(CASE WHEN b.reimburs_type_id = 2 THEN b.nominal_plafon END) AS rawat_inap,
						  MAX(CASE WHEN b.reimburs_type_id = 3 THEN b.nominal_plafon END) AS kacamata,
						  MAX(CASE WHEN b.reimburs_type_id = 4 THEN b.nominal_plafon END) AS persalinan
						FROM employees a
						LEFT JOIN master_plafon b ON b.grade_id = a.grade_id
						LEFT JOIN master_reimburs_type c ON b.reimburs_type_id = c.id
						WHERE a.id = '".$id."'
						GROUP BY a.id, a.grade_id;
					")->result(); 
		
		$getpemakaian = $this->db->query("select b.name as type_name, sum(a.nominal_reimburse) as total_pemakaian from medicalreimbursements a left join master_reimburs_type b on b.id = a.reimburs_type_id where a.employee_id = '".$id."' and (DATE_FORMAT(a.date_reimbursment, '%Y')) = '".date("Y")."' group by a.reimburs_type_id, b.name ")->result(); 
		$pemakaian_rawatjalan=0; $pemakaian_rawatinap=0; $pemakaian_kacamata=0; $pemakaian_persalinan=0;
		if(!empty($getpemakaian)){
			if($getpemakaian[0]->type_name == 'Rawat Jalan'){
				$pemakaian_rawatjalan = $getpemakaian[0]->total_pemakaian;
			}else if($getpemakaian[0]->type_name == 'Rawat Inap'){
				$pemakaian_rawatinap = $getpemakaian[0]->total_pemakaian;
			}else if($getpemakaian[0]->type_name == 'Kacamata'){
				$pemakaian_kacamata = $getpemakaian[0]->total_pemakaian;
			}else if($getpemakaian[0]->type_name == 'Persalinan'){
				$pemakaian_persalinan = $getpemakaian[0]->total_pemakaian;
			}
		}

		$sisaplafon_rawatjalan = $getplafon[0]->rawat_jalan-$pemakaian_rawatjalan;
		if($sisaplafon_rawatjalan <= 0){
			$sisaplafon_rawatjalan=0;
		} 
		$sisaplafon_rawatinap = $getplafon[0]->rawat_inap-$pemakaian_rawatinap;
		if($sisaplafon_rawatinap <= 0){
			$sisaplafon_rawatinap=0;
		} 
		$sisaplafon_kacamata = $getplafon[0]->kacamata-$pemakaian_kacamata;
		if($sisaplafon_kacamata <= 0){
			$sisaplafon_kacamata=0;
		} 
		$sisaplafon_persalinan = $getplafon[0]->persalinan-$pemakaian_persalinan;
		if($sisaplafon_persalinan <= 0){
			$sisaplafon_persalinan=0;
		} 
		//$sisa_plafon_all = $sisaplafon_rawatjalan+$sisaplafon_rawatinap+$sisaplafon_kacamata+$sisaplafon_persalinan;
		$sisa_plafon_all = $sisaplafon_rawatjalan;
		$sisa_plafon_all = 'Rp ' . number_format($sisa_plafon_all, 0, ',', '.');


		

		$health_daily = $this->db->query("select * from health_daily where employee_id = '".$id."' order by date desc limit 1")->result(); 
		$sleep_hours ="0"; $lastLog=""; $sleep_percent="0";
		$bpm ="0"; $bpm_desc="-";
		$spo2 ="0"; $spo2_desc ="-";

		if (!empty($health_daily)) {
			$lastLog =  $health_daily[0]->date;

		    $sleep_minutes = $health_daily[0]->sleep_minutes;

		    // ubah ke jam
		    $sleep_hours = round($sleep_minutes / 60, 1); // 1 decimal

		    // hitung persentase dari 8 jam (480 menit)
		    $sleep_percent = min(100, round(($sleep_minutes / 480) * 100));

		    // kategori 
		    if ($sleep_hours >= 7 && $sleep_hours <= 9) {
		        $sleep_desc = "Optimal";
		    } else if ($sleep_hours >= 5) {
		        $sleep_desc = "Fair";
		    } else {
		        $sleep_desc = "Poor";
		    }


		    /// get data BPM
		    $health_raw_hr = $this->db->query("select * from health_raw_hr where employee_id = '".$id."' and  DATE_FORMAT(ts_utc, '%Y-%m-%d') = '".$lastLog."' order by ts_utc desc limit 1")->result(); 
			
			if(!empty($health_raw_hr)){
				$bpm = $health_raw_hr[0]->bpm;

				if ($bpm >= 60 && $bpm <= 100) {
				    $bpm_desc = "Normal";
				} else if ($bpm < 60) {
				    $bpm_desc = "Low";
				} else { // > 100
				    $bpm_desc = "High";
				}
			}



			/// get data sop2
			$health_raw_spo2 = $this->db->query("select * from health_raw_spo2 where employee_id = '".$id."' and  DATE_FORMAT(ts_utc, '%Y-%m-%d') = '".$lastLog."' order by ts_utc desc limit 1")->result(); 
			
			if(!empty($health_raw_spo2)){ 
				$spo2 = $health_raw_spo2[0]->pct;

				if ($spo2 >= 95) {
				    $spo2_desc = "Excellent";
				} else if ($spo2 >= 91) { 
				    $spo2_desc = "Good";
				} else { // berarti <91
				    $spo2_desc = "Not Good";
				}
			}
		}

		
		$result = $this->calculateFatigue($sleep_hours, $bpm, $spo2);
		$fatigue_percentage = $result['percentage'].'%';
		$fatigue_category = $result['category'];
		

		
		$dataX = array(
			'dtEmp' 					=> $rs,
			/*'ttl_leave' 				=> $ttl_leave,
			'ttl_workhours' 			=> $workhours,*/
			'ttl_tasklist_open' 		=> $ttl_tasklist_open,
			'ttl_tasklist_inprogress' 	=> $ttl_tasklist_inprogress,
			'ttl_tasklist_closed' 		=> $ttl_tasklist_closed,
			'ttl_sisa_cuti' 			=> $ttl_sisa_cuti,
			'birthdays' 				=> $listBday,
			'events' 					=> $listInfo,
			'sisaplafon_rawatjalan' 	=> 'Rp ' . number_format($sisaplafon_rawatjalan, 0, ',', '.'),
			'sisaplafon_rawatinap' 		=> 'Rp ' . number_format($sisaplafon_rawatinap, 0, ',', '.'),
			'sisaplafon_kacamata' 		=> 'Rp ' . number_format($sisaplafon_kacamata, 0, ',', '.'),
			'sisaplafon_persalinan' 	=> 'Rp ' . number_format($sisaplafon_persalinan, 0, ',', '.'),
			'sisa_plafon_all' 			=> $sisa_plafon_all,
			/*'taskList' 					=> $data_tasklist*/
			'spo2' 		=> $spo2,
			'spo2_desc' => $spo2_desc,
			'bpm' 		=> $bpm,
			'bpm_desc' 	=> $bpm_desc,
			'sleep_hours' 	=> $sleep_hours,
			'sleep_percent' => $sleep_percent,
			'fatigue_percentage' 	=> $fatigue_percentage,
			'fatigue_category' 		=> $fatigue_category,
			'lastLog' 	=> $lastLog
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
					$msStatus = $this->db->query("select * from master_tasklist_status ")->result(); 

					
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


	public function add_data($post) { 

		$date_attendance 	= $post['date_attendance']; 
		
		$is_late=''; 

		
		
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
							
							'date_attendance' 			=> $post['date_attendance'],
							'employee_id' 				=> trim($post['hdnempid']),
							'attendance_type' 			=> trim($post['emp_type']),
							'time_in' 					=> trim($post['time_in']),
							'time_out' 					=> trim($post['time_out']),
							'date_attendance_in' 		=> $f_datetime_in,
							'is_late'					=> $is_late,
							'created_at'				=> date("Y-m-d H:i:s"),
							'notes' 					=> trim($post['description']),
							'work_location' 			=> trim($post['location'])
						];
						$rs = $this->db->insert("time_attendances", $data);

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
							
							'date_attendance_out'		=> $f_datetime_out,
							'is_leaving_office_early'	=> $is_leaving_office_early,
							'num_of_working_hours'		=> $num_of_working_hours,
							'updated_at'				=> date("Y-m-d H:i:s"),
							'notes' 					=> trim($post['description']),
							'work_location' 			=> trim($post['location'])
						];

						$rs = $this->db->update("time_attendances", $data, [$this->primary_key => trim($post['id'])]);
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

	

}
