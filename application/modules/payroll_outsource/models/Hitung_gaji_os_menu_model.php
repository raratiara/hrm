<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_gaji_os_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "payroll_outsource/hitung_gaji_os_menu";
 	protected $table_name 				= _PREFIX_TABLE."payroll_slip";
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
			'dt.periode_bulan_name',
			'dt.periode_tahun',
			'dt.full_name',
			'dt.project_name'
		];

		$where_employee = "";
		if(isset($_GET['flemployee_gaji']) && $_GET['flemployee_gaji'] != '' && $_GET['flemployee_gaji'] != 0){
		$where_employee = " and a.employee_id = '".$_GET['flemployee_gaji']."' ";
		}
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
				from payroll_slip a 
				left join employees b on b.id = a.employee_id 
				left join master_month c on c.id = a.periode_bulan
				left join project_outsource d on d.id = b.project_id
				left join master_job_title_os e on e.id = b.job_title_id
				left join data_customer f on f.id = d.customer_id
				where 1=1 '.$where_employee.'
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
				
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #343851; border-color: #343851;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
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
			

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					
					'.$detail.'
					'.$delete.'
				</div>',
				$row->id,
				$row->periode_bulan_name,
				$row->periode_tahun,
				$row->full_name,
				$row->project_name
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
		

  		if(!empty($post['penggajian_month']) && !empty($post['penggajian_year']) ){ 
  			
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
			    ->select('id, total_hari_kerja')
			    ->from('employees')
			    ->where('emp_source', 'outsource')
			    ->where('status_id', 1)
			    ->get()
			    ->result();


  			if(!empty($data_os)){
  				foreach($data_os as $rowdata_os){
  					$emp_id = $rowdata_os->id;
  					
  					$data_summary = $this->db->query("select * from summary_absen_outsource where bulan = ".$post['penggajian_month']." and tahun = '".$post['penggajian_year']."' and emp_id = ".$emp_id."")->result();
  					if(!empty($data_summary)){
  						$total_tidak_masuk = $data_summary[0]->total_ijin+$data_summary[0]->total_cuti+$data_summary[0]->total_alfa;
		                $data = [
							'periode_bulan' 	=> trim($post['penggajian_month']),
							'periode_tahun' 	=> trim($post['penggajian_year']),
							'tgl_start_absensi' => trim($post['period_start']),
							'tgl_end_absensi' 	=> trim($post['period_end']),
							'employee_id' 		=> $emp_id,
							'total_hari_kerja'  => $data_summary[0]->total_hari_kerja,
							'total_masuk'  		=> $data_summary[0]->total_masuk,
							'total_tidak_masuk' => $total_tidak_masuk,
							'total_lembur'  	=> $data_summary[0]->total_lembur,
							'total_jam_kerja'  	=> $data_summary[0]->total_jam_kerja,
							'total_jam_lembur'  => $data_summary[0]->total_jam_lembur,
							'created_at'		=> date("Y-m-d H:i:s"),
							'created_by' 		=> $_SESSION['worker'],
							'gaji_bulanan'  	=> '',
							'gaji_harian' 		=> '',
							'gaji' 				=> '',
							'lembur_perjam' 	=> '',
							'ot' 				=> '',
							'total_pendapatan' 	=> '',
							'sosial' 			=> ''
						];
						$rs = $this->db->insert($this->table_name, $data);
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
	  			$where_date = " and (date_attendance between '".$period_start."' and '".$period_end."')";

	  			$dataEmp = $this->db->query("select emp_id from summary_absen_outsource where id = ".$post['id']."")->result();

				$data_os = $this->db
				    ->select('id, total_hari_kerja')
				    ->from('employees')
				    ->where('emp_source', 'outsource')
				    ->where('status_id', 1)
				    ->where('id', $dataEmp[0]->emp_id)
				    ->get()
				    ->result();


	  			if(!empty($data_os)){ 
	  				foreach($data_os as $rowdata_os){
	  					$emp_id = $rowdata_os->id;
	  					$total_hari_kerja = $rowdata_os->total_hari_kerja;

	  					
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


		                $data = [
							'bulan' 			=> trim($post['penggajian_month']),
							'tahun' 			=> trim($post['penggajian_year']),
							'tgl_start' 		=> $period_start,
							'tgl_end' 			=> $period_end,
							/*'emp_id' 			=> $emp_id,*/
							'total_hari_kerja'  => $total_hari_kerja,
							'total_masuk'  		=> $ttl_masuk,
							'total_ijin'  		=> $ttl_cuti,
							'total_cuti'  		=> $ttl_cuti,
							'total_alfa'  		=> $total_alfa,
							'total_lembur'  	=> $ttl_overtime,
							'total_jam_kerja'  	=> $ttl_working_hours,
							'total_jam_lembur'  => $ttl_overtime_num_of_hour,
							'updated_at'		=> date("Y-m-d H:i:s"),
							'updated_by' 		=> $_SESSION['worker']
						];
						$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
	  					

	  				}
	  				return $rs;
	  			}

	  		}else{
	  			echo "Bulan Tahun Penggajian & Periode Absensi harus diisi"; 
	  		}
		}else return null;
	} 


	public function getRowData($id) { 

		$where_employee = "";
		if(isset($_GET['flemployee_gaji']) && $_GET['flemployee_gaji'] != '' && $_GET['flemployee_gaji'] != 0){
		$where_employee = " and a.employee_id = '".$_GET['flemployee_gaji']."' ";
		}


		$mTable = "(select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
				from payroll_slip a 
				left join employees b on b.id = a.employee_id 
				left join master_month c on c.id = a.periode_bulan
				left join project_outsource d on d.id = b.project_id
				left join master_job_title_os e on e.id = b.job_title_id
				left join data_customer f on f.id = d.customer_id
				where 1=1 ".$where_employee."
			)dt";

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		
		
		return $rs;
	} 

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'employee_id' 			=> $v["B"],
				'task' 					=> $v["C"],
				'progress_percentage' 	=> $v["D"],
				'parent_id' 			=> $v["E"],
				'due_date' 				=> $v["F"],
				'status_id' 			=> $v["G"],
				'solve_date' 			=> $v["H"]
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{

		$sql = "select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
				from payroll_slip a 
				left join employees b on b.id = a.employee_id 
				left join master_month c on c.id = a.periode_bulan
				left join project_outsource d on d.id = b.project_id
				left join master_job_title_os e on e.id = b.job_title_id
				left join data_customer f on f.id = d.customer_id
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getNewGajiOSRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getGajiOSRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			
			$data 	.= '<td>'.$this->return_build_txt('','ttl_hari_kerja','ttl_hari_kerja','ttl_hari_kerja','text-align: right;','data-id="'.$row.'" ').'<input type="hidden" id="hdnid" name="hdnid" value=""/></td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_masuk','ttl_masuk','ttl_masuk','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_ijin','ttl_ijin','ttl_ijin','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_cuti','ttl_cuti','ttl_cuti','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_alfa','ttl_alfa','ttl_alfa','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ttl_lembur','ttl_lembur','ttl_lembur','text-align: right;','data-id="'.$row.'" ').'</td>';

			

			$hdnid='';
			$data 	.= '<td><input type="button" class="btn btn-md btn-danger ibtnDel" onclick="del_fpp(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getGajiOSRows($id,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
			from payroll_slip a 
			left join employees b on b.id = a.employee_id 
			left join master_month c on c.id = a.periode_bulan
			left join project_outsource d on d.id = b.project_id
			left join master_job_title_os e on e.id = b.job_title_id
			left join data_customer f on f.id = d.customer_id
			where a.id = '".$id."' ")->result(); 

		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$f->emp_code.'</td>';
					$dt .= '<td>'.$f->full_name.'<input type="hidden" id="hdnempid_gaji" name="hdnempid_gaji['.$row.']" value="'.$f->employee_id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_jam_kerja,'jml_jam_kerja_gaji['.$row.']','','jml_jam_kerja_gaji','text-align: right;','data-id="'.$row.'" ').'<input type="hidden" id="hdnid_gaji" name="hdnid_gaji['.$row.']" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_masuk,'jml_hadir_gaji['.$row.']','','jml_hadir_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_tidak_masuk,'jml_tdkhadir_gaji['.$row.']','','jml_tdkhadir_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->gaji_bulanan,'gaji_bulanan_gaji['.$row.']','','gaji_bulanan_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->gaji_harian,'gaji_harian_gaji['.$row.']','','gaji_harian_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->gaji,'gaji_gaji['.$row.']','','gaji_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->tunjangan_jabatan,'tunj_jabatan_gaji['.$row.']','','tunj_jabatan_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->tunjangan_transport,'tunj_transport_gaji['.$row.']','','tunj_transport_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->tunjangan_konsumsi,'tunj_konsumsi_gaji['.$row.']','','tunj_konsumsi_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->tunjangan_komunikasi,'tunj_komunikasi_gaji['.$row.']','','tunj_komunikasi_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->lembur_perjam,'lembur_perjam_gaji['.$row.']','','lembur_perjam_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->ot,'ot_gaji['.$row.']','','ot_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_jam_lembur,'jam_lembur_gaji['.$row.']','','jam_lembur_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_pendapatan,'ttl_pendapatan_gaji['.$row.']','','ttl_pendapatan_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->bpjs_kesehatan,'bpjs_kes_gaji['.$row.']','','bpjs_kes_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->bpjs_tk,'bpjs_tk_gaji['.$row.']','','bpjs_tk_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->absen,'absen_gaji['.$row.']','','absen_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->seragam,'seragam_gaji['.$row.']','','seragam_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->pelatihan,'pelatihan_gaji['.$row.']','','pelatihan_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->lain_lain,'lainlain_gaji['.$row.']','','lainlain_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->hutang,'hutang_gaji['.$row.']','','hutang_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->sosial,'sosial_gaji['.$row.']','','sosial_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->payroll,'payroll_gaji['.$row.']','','payroll_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->pph_120,'pph120_gaji['.$row.']','','pph120_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->subtotal,'subtotal_gaji['.$row.']','','subtotal_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->gaji_bersih,'gaji_bersih_gaji['.$row.']','','gaji_bersih_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					
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
					
					$dt .= '<td>'.$f->total_jam_kerja.'</td>';
					$dt .= '<td>'.$f->total_masuk.'</td>';
					$dt .= '<td>'.$f->total_tidak_masuk.'</td>';
					$dt .= '<td>'.$f->gaji_bulanan.'</td>';
					$dt .= '<td>'.$f->gaji_harian.'</td>';
					$dt .= '<td>'.$f->gaji.'</td>';
					$dt .= '<td>'.$f->tunjangan_jabatan.'</td>';
					$dt .= '<td>'.$f->tunjangan_transport.'</td>';
					$dt .= '<td>'.$f->tunjangan_konsumsi.'</td>';
					$dt .= '<td>'.$f->tunjangan_komunikasi.'</td>';
					$dt .= '<td>'.$f->lembur_perjam.'</td>';
					$dt .= '<td>'.$f->ot.'</td>';
					$dt .= '<td>'.$f->total_jam_lembur.'</td>';
					$dt .= '<td>'.$f->total_pendapatan.'</td>';
					$dt .= '<td>'.$f->bpjs_kesehatan.'</td>';
					$dt .= '<td>'.$f->bpjs_tk.'</td>';
					$dt .= '<td>'.$f->total_hari_kerja.'</td>';
					$dt .= '<td>'.$f->absen.'</td>';
					$dt .= '<td>'.$f->seragam.'</td>';
					$dt .= '<td>'.$f->pelatihan.'</td>';
					$dt .= '<td>'.$f->lain_lain.'</td>';
					$dt .= '<td>'.$f->hutang.'</td>';
					$dt .= '<td>'.$f->sosial.'</td>';
					$dt .= '<td>'.$f->payroll.'</td>';
					$dt .= '<td>'.$f->pph_120.'</td>';
					$dt .= '<td>'.$f->subtotal.'</td>';
					$dt .= '<td>'.$f->gaji_bersih.'</td>';
					
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


	public function getSummaryAbsen($bln, $thn){ 

		$rs = $this->db->query("select * from  summary_absen_outsource where bulan = ".$bln." and tahun = '".$thn."' limit 1")->result(); 

		

		return $rs;

	}

	public function getGaji($bln, $thn){ 

		$rs = $this->db->query("select * from  payroll_slip where periode_bulan = ".$bln." and periode_tahun = '".$thn."' limit 1")->result(); 

		

		return $rs;

	}



	public function getNewEditAbsenRow($row,$id=0,$bln,$thn,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getEditAbsenRow($id,$bln,$thn,$view);
		} else { 
			$data = '';
			$no = $row+1;

			$data 	.= '<td>No Data</td>';

			
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getEditAbsenRow($id,$bln,$thn,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select a.id as employee_id, a.emp_code, a.full_name, b.* from employees a 
						left join payroll_slip b on b.employee_id = a.id 
						where a.emp_source = 'outsource' and a.project_id = '".$id."'
						and a.status_id = 1 and b.periode_bulan = '".$bln."' and b.periode_tahun = '".$thn."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$f->emp_code.'</td>';
					$dt .= '<td>'.$f->full_name.'<input type="hidden" id="hdnempid_gaji" name="hdnempid_gaji['.$row.']" value="'.$f->employee_id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_jam_kerja,'jml_jam_kerja_edit_gaji['.$row.']','','jml_jam_kerja_edit_gaji','text-align: right;','data-id="'.$row.'" ').'<input type="hidden" id="hdnid_edit_gaji" name="hdnid_edit_gaji['.$row.']" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_masuk,'jml_hadir_edit_gaji['.$row.']','','jml_hadir_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_tidak_masuk,'jml_tdkhadir_edit_gaji['.$row.']','','jml_tdkhadir_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->gaji_bulanan,'gaji_bulanan_edit_gaji['.$row.']','','gaji_bulanan_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->gaji_harian,'gaji_harian_edit_gaji['.$row.']','','gaji_harian_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->gaji,'gaji_edit_gaji['.$row.']','','gaji_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->tunjangan_jabatan,'tunj_jabatan_edit_gaji['.$row.']','','tunj_jabatan_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->tunjangan_transport,'tunj_transport_edit_gaji['.$row.']','','tunj_transport_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->tunjangan_konsumsi,'tunj_konsumsi_edit_gaji['.$row.']','','tunj_konsumsi_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->tunjangan_komunikasi,'tunj_komunikasi_edit_gaji['.$row.']','','tunj_komunikasi_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->lembur_perjam,'lembur_perjam_edit_gaji['.$row.']','','lembur_perjam_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->ot,'ot_edit_gaji['.$row.']','','ot_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_jam_lembur,'jam_lembur_edit_gaji['.$row.']','','jam_lembur_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_pendapatan,'ttl_pendapatan_edit_gaji['.$row.']','','ttl_pendapatan_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->bpjs_kesehatan,'bpjs_kes_edit_gaji['.$row.']','','bpjs_kes_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->bpjs_tk,'bpjs_tk_edit_gaji['.$row.']','','bpjs_tk_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->absen,'absen_edit_gaji['.$row.']','','absen_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->seragam,'seragam_edit_gaji['.$row.']','','seragam_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->pelatihan,'pelatihan_edit_gaji['.$row.']','','pelatihan_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->lain_lain,'lainlain_edit_gaji['.$row.']','','lainlain_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->hutang,'hutang_edit_gaji['.$row.']','','hutang_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->sosial,'sosial_edit_gaji['.$row.']','','sosial_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->payroll,'payroll_edit_gaji['.$row.']','','payroll_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->pph_120,'pph120_edit_gaji['.$row.']','','pph120_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->subtotal,'subtotal_edit_gaji['.$row.']','','subtotal_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->gaji_bersih,'gaji_bersih_edit_gaji['.$row.']','','gaji_bersih_edit_gaji','text-align: right;','data-id="'.$row.'" ').'</td>';

					
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
					
					$dt .= '<td>'.$f->total_jam_kerja.'</td>';
					$dt .= '<td>'.$f->total_masuk.'</td>';
					$dt .= '<td>'.$f->total_tidak_masuk.'</td>';
					$dt .= '<td>'.$f->gaji_bulanan.'</td>';
					$dt .= '<td>'.$f->gaji_harian.'</td>';
					$dt .= '<td>'.$f->gaji.'</td>';
					$dt .= '<td>'.$f->tunjangan_jabatan.'</td>';
					$dt .= '<td>'.$f->tunjangan_transport.'</td>';
					$dt .= '<td>'.$f->tunjangan_konsumsi.'</td>';
					$dt .= '<td>'.$f->tunjangan_komunikasi.'</td>';
					$dt .= '<td>'.$f->lembur_perjam.'</td>';
					$dt .= '<td>'.$f->ot.'</td>';
					$dt .= '<td>'.$f->total_jam_lembur.'</td>';
					$dt .= '<td>'.$f->total_pendapatan.'</td>';
					$dt .= '<td>'.$f->bpjs_kesehatan.'</td>';
					$dt .= '<td>'.$f->bpjs_tk.'</td>';
					$dt .= '<td>'.$f->total_hari_kerja.'</td>';
					$dt .= '<td>'.$f->absen.'</td>';
					$dt .= '<td>'.$f->seragam.'</td>';
					$dt .= '<td>'.$f->pelatihan.'</td>';
					$dt .= '<td>'.$f->lain_lain.'</td>';
					$dt .= '<td>'.$f->hutang.'</td>';
					$dt .= '<td>'.$f->sosial.'</td>';
					$dt .= '<td>'.$f->payroll.'</td>';
					$dt .= '<td>'.$f->pph_120.'</td>';
					$dt .= '<td>'.$f->subtotal.'</td>';
					$dt .= '<td>'.$f->gaji_bersih.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


}
