<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_gaji_os_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "payroll_outsource/hitung_gaji_os_menu";
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
			'dt.periode_bulan_name',
			'dt.periode_tahun',
			'dt.full_name',
			'dt.project_name'
		];

		$where_employee = "";
		if(isset($_GET['flemployee']) && $_GET['flemployee'] != '' && $_GET['flemployee'] != 0){
		$where_employee = " and a.employee_id = '".$_GET['flemployee']."' ";
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
					
					'.$edit.'
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
		$mTable = '(select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
				from payroll_slip a 
				left join employees b on b.id = a.employee_id 
				left join master_month c on c.id = a.periode_bulan
				left join project_outsource d on d.id = b.project_id
				left join master_job_title_os e on e.id = b.job_title_id
				left join data_customer f on f.id = d.customer_id
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


	public function getNewAbsenOSRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getAbsenOSRows($id,$view);
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
	public function getAbsenOSRows($id,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select * from summary_absen_outsource where id = '".$id."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$this->return_build_txt($f->total_hari_kerja,'ttl_hari_kerja','ttl_hari_kerja','ttl_hari_kerja','text-align: right;','data-id="'.$row.'" readonly ').'<input type="hidden" id="hdnid" name="hdnid" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_masuk,'ttl_masuk','ttl_masuk','ttl_masuk','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_ijin,'ttl_ijin','ttl_ijin','ttl_ijin','text-align: right;','data-id="'.$row.'" readonly').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_cuti,'ttl_cuti','ttl_cuti','ttl_cuti','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_alfa,'ttl_alfa','ttl_alfa','ttl_alfa','text-align: right;','data-id="'.$row.'" readonly  ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_lembur,'ttl_lembur','ttl_lembur','ttl_lembur','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					
					
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
					
					$dt .= '<td>'.$f->total_hari_kerja.'</td>';
					$dt .= '<td>'.$f->total_masuk.'</td>';
					$dt .= '<td>'.$f->total_ijin.'</td>';
					$dt .= '<td>'.$f->total_cuti.'</td>';
					$dt .= '<td>'.$f->total_alfa.'</td>';
					$dt .= '<td>'.$f->total_lembur.'</td>';
					
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}

}
