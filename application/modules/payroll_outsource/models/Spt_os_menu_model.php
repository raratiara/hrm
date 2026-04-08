<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spt_os_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "payroll_outsource/spt_os_menu";
 	protected $table_name 				= _PREFIX_TABLE."forecasting_budget";
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
			'dt.bulan_penggajian_name',
			'dt.tahun_penggajian',
			'dt.jml_nominal_masuk',
			'dt.jml_nominal_lembur'
		];
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.name_indo as bulan_penggajian_name from forecasting_budget a left join master_month b on b.id = a.bulan_penggajian)dt';
		

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
			

			$jml_nominal_masuk_fmt  = number_format($row->jml_nominal_masuk, 2, ',', '.');
			$jml_nominal_lembur_fmt = number_format($row->jml_nominal_lembur, 2, ',', '.');

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					
					'.$delete.'
				</div>',
				$row->id,
				$row->bulan_penggajian_name,
				$row->tahun_penggajian,
				'Rp. '.$jml_nominal_masuk_fmt,
				'Rp. '.$jml_nominal_lembur_fmt
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
		
  		if(!empty($post['penggajian_month_fcast']) && !empty($post['penggajian_year_fcast']) && !empty($post['is_all_project_fcast'])){ 
  			

  			$data = [
				'bulan_penggajian' 	=> trim($post['penggajian_month_fcast']),
				'tahun_penggajian' 	=> trim($post['penggajian_year_fcast']),
				'jml_nominal_masuk' 	=> trim($post['hdnjml_nominal_masuk']),
				'jml_nominal_lembur' 	=> trim($post['hdnjml_nominal_lembur']),
				'created_at'		=> date("Y-m-d H:i:s"),
				'created_by'		=> $_SESSION['worker']
			];
			$rs = $this->db->insert($this->table_name, $data);
			$lastId = $this->db->insert_id();

			if($rs){
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
						if(isset($post['hdnempid'][$i])){
							$itemData = [
								'forecasting_budget_id'	=> $lastId,
								'employee_id' 			=> trim($post['hdnempid'][$i]),
								'ttl_masuk' 			=> trim($post['total_masuk'][$i]),
								'ttl_masuk_nominal' 	=> trim($post['hdntotal_masuk_nominal'][$i]),
								'ttl_lembur'			=> trim($post['total_jam_lembur'][$i]),
								'ttl_lembur_nominal' 	=> trim($post['hdntotal_lembur_nominal'][$i])
							];

							$this->db->insert('forecasting_budget_detail', $itemData);
						}
					}
				}
			}


			return $rs;
  		}else return null;

	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){ 
		
			if($post['info_type'] == 'Event'){
  				$color = 'today';
  			}else if($post['info_type'] == 'News'){
  				$color = 'yellow';
  			}else{
  				$color = 'grey'; //orange
  			}

  			$data = [
				'label1' 			=> trim($post['label1']),
				'label2' 			=> trim($post['label2']),
				'color'				=> $color,
				'title' 			=> trim($post['title']),
				'description' 		=> trim($post['description']),
				'type' 				=> trim($post['info_type']),
				'show_date_start' 	=> date("Y-m-d", strtotime($show_date_start)),
				'show_date_end' 	=> date("Y-m-d", strtotime($show_date_end)),
				'updated_at'		=> date("Y-m-d H:i:s")
			];

			return  $rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.name_indo as bulan_penggajian_name from forecasting_budget a left join master_month b on b.id = a.bulan_penggajian
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

		$sql = "select a.*, b.name_indo as bulan_penggajian_name from forecasting_budget a left join master_month b on b.id = a.bulan_penggajian
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getNewFcastRow($row,$id=0,$penggajian_month,$penggajian_year,$project='',$view=FALSE)
	{ 
		/*if($id > 0){ 
			$data = $this->genFcastRow($id,$period_start,$period_end,$view);
		} else { 
			$data = '';
			$no = $row+1;

			$data 	.= '<td>No Data</td>';

			
		}

		
		*/

		$data = $this->genFcastRow($id,$penggajian_month,$penggajian_year,$project,$view);

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function genFcastRow($id,$penggajian_month,$penggajian_year,$project='',$view,$print=FALSE){ 

		$dt = ''; 
		
		/*$rs = $this->db->query("select 
								    b.id AS emp_id,
								    b.emp_code,
								    b.full_name,
								    b.job_title_id, 

								    SUM(
								        CASE 
								            WHEN a.leave_absences_id IS NULL 
								             AND a.date_attendance_in IS NOT NULL 
								            THEN 1 ELSE 0 
								        END
								    ) AS ttl_masuk,

								    SUM(
								        CASE 
								            WHEN a.leave_absences_id IS NOT NULL 
								             AND a.leave_type != 5 
								             AND h.status_approval = 2 
								            THEN 1 ELSE 0 
								        END
								    ) AS ttl_cuti,

								    SUM(
								        CASE 
								            WHEN a.leave_absences_id IS NOT NULL 
								             AND a.leave_type = 5 
								             AND h.status_approval = 2 
								            THEN 1 ELSE 0 
								        END
								    ) AS ttl_sakit,

								    SUM(
								        CASE 
								            WHEN a.is_late = 'Y' THEN 1 ELSE 0 
								        END
								    ) AS ttl_late,

								    SUM(
								        CASE 
								            WHEN a.is_leaving_office_early = 'Y' THEN 1 ELSE 0 
								        END
								    ) AS ttl_leaving_early,

								    SUM(IFNULL(ot.num_of_hour,0)) AS ttl_overtime_hour,
								    SUM(IFNULL(ot.amount,0)) AS ttl_overtime_amount

								FROM employees b
								LEFT JOIN time_attendances a 
								       ON a.employee_id = b.id
								LEFT JOIN leave_absences h 
								       ON h.id = a.leave_absences_id
								LEFT JOIN (
								    SELECT 
								        employee_id,
								        DATE(datetime_start) AS ot_date,
								        SUM(num_of_hour) AS num_of_hour,
								        SUM(amount) AS amount
								    FROM overtimes
								    WHERE type = 1 AND status_id = 2
								    GROUP BY employee_id, DATE(datetime_start)
								) ot ON ot.employee_id = b.id 
								     AND ot.ot_date = a.date_attendance

								WHERE b.emp_source = 'outsource'
								  AND b.status_id = 1

								GROUP BY b.id
								ORDER BY b.full_name;
								")->result(); */



		if($id > 0){ 
			$rs = $this->db->query("select d.name_indo, a.tahun_penggajian, b.employee_id, c.emp_code, c.full_name, 		c.project_id,
					    c.job_title_id, 
					    c.total_hari_kerja, b.*, a.jml_nominal_masuk, a.jml_nominal_lembur, e.project_name
					from forecasting_budget a
					left join forecasting_budget_detail b on b.forecasting_budget_id = a. id
					left join employees c on c.id = b.employee_id
					left join master_month d on d.id = a.bulan_penggajian
					left join project_outsource e on e.id = c.project_id
					where a.id = ".$id." ")->result();

		}else{ //add

			$whr_project = '';
			if (!empty($project) && is_array($project)) {
			    /*$this->db->where_in('project_id', $post['projectIds']);*/
			    $project_ids = implode(',', array_map('intval', $project));
    			$whr_project = " AND (a.project_id IN ($project_ids)) ";
			}


			$rs = $this->db->query("select a.id as employee_id, a.emp_code, a.full_name, b.*, a.project_id, a.job_title_id, a.total_hari_kerja, e.project_name from employees a left join summary_absen_outsource b on b.emp_id = a.id and b.bulan = '".$penggajian_month."' and b.tahun = '".$penggajian_year."' left join project_outsource e on e.id = a.project_id where a.emp_source = 'outsource' ".$whr_project."	and a.status_id = 1 ")->result();
		}

		 


		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 

			$where_date='';
			$jml_nominal_masuk  = 0;
			$jml_nominal_lembur = 0;

			foreach ($rd as $f){

				$hdntotal_masuk_nominal=0; $hdntotal_lembur_nominal=0;
				$total_masuk_nominal=0; $total_lembur_nominal=0;

				if($id > 0){ 
					$jml_nominal_masuk  = $f->jml_nominal_masuk;
					$jml_nominal_lembur = $f->jml_nominal_lembur;
					$hdntotal_masuk_nominal=$f->ttl_masuk_nominal; 
					$hdntotal_lembur_nominal=$f->ttl_lembur_nominal;
					$total_masuk_nominal= number_format($f->ttl_masuk_nominal, 2, ',', '.');
					$total_lembur_nominal= number_format($f->ttl_lembur_nominal, 2, ',', '.');

				}else{

					$get_gaji_pokok = $this->db->query("select a.project_outsource_id, c.harga_satuan from project_outsource_boq a
					left join project_outsource_boq_detail b on b.boq_id = a.id
					left join master_boq_detail c on c.id = b.ms_boq_detail_id 
					where c.master_header_boq_id = 1 and c.parent_id = 1 and c.job_title_id = ".$f->job_title_id."
					and a.project_outsource_id = ".$f->project_id." ")->result(); 


					
					if(!empty($get_gaji_pokok)){
						if($get_gaji_pokok[0]->harga_satuan > 0 && $f->total_hari_kerja != '' && $f->total_hari_kerja > 0){
							$gaji_per_hari = $get_gaji_pokok[0]->harga_satuan/$f->total_hari_kerja;
							$gaji_bulanan = $get_gaji_pokok[0]->harga_satuan;

							if($f->total_masuk > 0){
								$hdntotal_masuk_nominal = round($f->total_masuk*$gaji_per_hari,2);
								$total_masuk_nominal = number_format($f->total_masuk * $gaji_per_hari, 2, ',', '.');

								$jml_nominal_masuk  += $hdntotal_masuk_nominal;
							}
							
							if($f->total_jam_lembur > 0){
								$hdntotal_lembur_nominal = round($f->total_jam_lembur * ($gaji_bulanan / 173),2);
								$total_lembur_nominal = number_format($f->total_jam_lembur * ($gaji_bulanan / 173), 2, ',', '.');

								$jml_nominal_lembur += $hdntotal_lembur_nominal;

							}
						}
					}
				}
				
				

				$no = $row+1;
				
				if(!$view){ 

					$dt .= '<tr>';
					$dt .= '<td>'.$no.'</td>';
					$dt .= '<td>'.$f->emp_code.'</td>';
					$dt .= '<td>'.$f->full_name.'<input type="hidden" id="hdnempid" name="hdnempid['.$row.']" value="'.$f->employee_id.'"/></td>';
					$dt .= '<td>'.$f->project_name.'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_masuk,'total_masuk['.$row.']','','total_masuk','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($total_masuk_nominal,'total_masuk_nominal['.$row.']','','total_masuk_nominal','text-align: right;','data-id="'.$row.'" readonly ').'<input type="hidden" id="hdntotal_masuk_nominal" name="hdntotal_masuk_nominal['.$row.']" value="'.$hdntotal_masuk_nominal.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_jam_lembur,'total_jam_lembur['.$row.']','','total_jam_lembur','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($total_lembur_nominal,'total_lembur_nominal['.$row.']','','total_lembur_nominal','text-align: right;','data-id="'.$row.'" readonly ').'<input type="hidden" id="hdntotal_lembur_nominal" name="hdntotal_lembur_nominal['.$row.']" value="'.$hdntotal_lembur_nominal.'"/></td>';

					
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
					$dt .= '<td>'.$f->emp_code.'</td>';
					$dt .= '<td>'.$f->full_name.'</td>';
					$dt .= '<td>'.$f->project_name.'</td>';
					$dt .= '<td>'.$f->ttl_masuk.'</td>';
					$dt .= '<td>'.$total_masuk_nominal.'</td>';
					$dt .= '<td>'.$f->ttl_lembur.'</td>';
					$dt .= '<td>'.$total_lembur_nominal.'</td>';
					
					$dt .= '</tr>';

					
				}

				$row++;
			}

			$jml_nominal_masuk_fmt  = number_format($jml_nominal_masuk, 2, ',', '.');
			$jml_nominal_lembur_fmt = number_format($jml_nominal_lembur, 2, ',', '.');

			$dt .= '<tr>';
			
			$dt .= '<td colspan="5" style="text-align:right; font-weight:bold">Jumlah Nominal Masuk </td>';
			$dt .= '<td style="font-weight:bold">'.$this->return_build_txt($jml_nominal_masuk_fmt,'total_masuk['.$row.']','','total_masuk','text-align: right;','data-id="'.$row.'" readonly ').'<input type="hidden" id="hdnjml_nominal_masuk" name="hdnjml_nominal_masuk" value="'.$jml_nominal_masuk.'"/></td>';
			$dt .= '<td style="text-align:right; font-weight:bold">Jumlah Nominal Lembur</td>';
			$dt .= '<td style="font-weight:bold">'.$this->return_build_txt($jml_nominal_lembur_fmt,'total_masuk_nominal['.$row.']','','total_masuk_nominal','text-align: right;','data-id="'.$row.'" readonly ').'<input type="hidden" id="hdnjml_nominal_lembur" name="hdnjml_nominal_lembur" value="'.$jml_nominal_lembur.'"/></td>';

			

			$dt .= '</tr>';


		}

		return [$dt,$row];
	}


}
