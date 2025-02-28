<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_order_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "job_order/job_order_detail_menu";
 	protected $table_name 				= _PREFIX_TABLE."job_order_detail";
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
			'dt.date',
			'dt.order_no',
			'dt.order_name'
		];
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.name as floating_crane_name, c.name as mother_vessel_name, d.name as status_name, if(a.is_active = 1, "Yes", "No") as is_active_desc
			from job_order a
			left join floating_crane b on b.id = a.floating_crane_id
			left join mother_vessel c on c.id = a.mother_vessel_id
			left join status d on d.id = a.order_status
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
				$row->date,
				$row->order_no,
				$row->order_name

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

	// Get next number 
	public function getNextNumber() { 
		
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		

		$cek = $this->db->query("select * from job_order where SUBSTRING(order_no, 4, 4) = '".$period."'");
		$rs_cek = $cek->result_array();

		if(empty($rs_cek)){
			$num = '0001';
		}else{
			$cek2 = $this->db->query("select max(order_no) as maxnum from job_order where SUBSTRING(order_no, 4, 4) = '".$period."'");
			$rs_cek2 = $cek2->result_array();
			$dt = $rs_cek2[0]['maxnum']; 
			$getnum = substr($dt,7); 
			$num = str_pad($getnum + 1, 4, 0, STR_PAD_LEFT);
			
		}

		return $num;
		
	} 

	public function add_data($post) { 
		// BOF auto numbering 
		$lettercode = ('ORD'); // ca code
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		
		
		$runningnumber = $this->getNextNumber(); // next count number
		
		$nextnum 	= $lettercode.$period.$runningnumber;


		$date=date_create($post['date_pekerjaan']);
		$datetime_start = date_create($post['date_time_start']); 
		$datetime_end = date_create($post['date_time_end']); 

		$f_datetime_start = date_format($datetime_start,"Y-m-d H:i:s");
		$f_datetime_end = date_format($datetime_end,"Y-m-d H:i:s");


		$timestamp1 = strtotime($f_datetime_start); 
		$timestamp2 = strtotime($f_datetime_end);

  		$diff = abs($timestamp2 - $timestamp1)/(60); //menit
		


		$data = [
			'date'	=> date_format($date,"Y-m-d"),
			'order_no' 				=> $nextnum,
			'order_name' 				=> trim($post['order_name']),
			'floating_crane_id' 			=> trim($post['floating_crane']),
			'mother_vessel_id' 			=> trim($post['mother_vessel']),
			'pic' 		=> trim($post['pic']),
			'datetime_start' 		=> $f_datetime_start,
			'datetime_end' 			=> $f_datetime_end,
			'date_time_total' 	=> $diff,
			'order_status' => trim($post['status']),
			'is_active' => trim($post['is_active'])
		];

		return $rs = $this->db->insert($this->table_name, $data);
	}  

	public function edit_data($post) { 

		$date=date_create($post['date_pekerjaan']);
		$datetime_start = date_create($post['date_time_start']); 
		$datetime_end = date_create($post['date_time_end']); 

		$f_datetime_start = date_format($datetime_start,"Y-m-d H:i:s");
		$f_datetime_end = date_format($datetime_end,"Y-m-d H:i:s");


		$timestamp1 = strtotime($f_datetime_start); 
		$timestamp2 = strtotime($f_datetime_end);

  		$diff = abs($timestamp2 - $timestamp1)/(60); //menit

		if(!empty($post['id'])){
			$data = [
				'date'				=> date_format($date,"Y-m-d"),
				'order_name' 		=> trim($post['order_name']),
				'floating_crane_id' => trim($post['floating_crane']),
				'mother_vessel_id' 	=> trim($post['mother_vessel']),
				'pic' 				=> trim($post['pic']),
				'datetime_start' 	=> $f_datetime_start,
				'datetime_end' 		=> $f_datetime_end,
				'date_time_total' 	=> $diff,
				'order_status' 		=> trim($post['status']),
				'is_active' 		=> trim($post['is_active'])
			];

			return  $rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.name as floating_crane_name, c.name as mother_vessel_name, d.name as status_name, if(a.is_active = 1, "Yes", "No") as is_active_desc
			from job_order a
			left join floating_crane b on b.id = a.floating_crane_id
			left join mother_vessel c on c.id = a.mother_vessel_id
			left join status d on d.id = a.order_status
			)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		/*if(!empty($rs->provinsi_id)){
			$ri = $this->db->select('name as parent_title')->where([$this->primary_key => $rs->provinsi_id])->get($this->table_name)->row();
			$rs = (object) array_merge((array) $rs, (array) $ri);
		} else {
			$rs = (object) array_merge((array) $rs, ['parent_title'=>'-']);
		}*/
		
		return $rs;
	} 

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'date'	=> $v["B"],
				'order_no' 				=> $v["C"],
				'order_name' 				=> $v["D"],
				'floating_crane_id' 			=> $v["E"],
				'mother_vessel_id' 			=> $v["F"],
				'pic' 		=> $v["G"],
				'datetime_start' 				=> $v["H"],
				'datetime_end' 			=> $v["I"],
				'order_status' 	=> $v["J"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$sql = "select a.*, b.name as floating_crane_name, c.name as mother_vessel_name, d.name as status_name
				from job_order a
				left join floating_crane b on b.id = a.floating_crane_id
				left join mother_vessel c on c.id = a.mother_vessel_id
				left join status d on d.id = a.order_status
				order by a.id asc
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}

}
