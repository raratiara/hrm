<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_detail_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "dashboard/dashboard_detail_menu";
 	protected $table_name 				= _PREFIX_TABLE."cctv";
 	protected $primary_key 				= "id";

	function __construct()
	{
		parent::__construct();
	}

	// fix
	public function get_list_data($id)
	{ 

		$aColumns = [
			'dt.date',
			'dt.order_no',
			'dt.order_name',
			'dt.floating_crane_name',
			'dt.mother_vessel_name',
			'dt.activity_name',
			'dt.datetime_start',
			'dt.datetime_end',
			'dt.total_time',
			'dt.degree',
			'dt.degree_2',
			'dt.pic',
			'dt.status_name'
		];
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.date, b.order_no, b.order_name, b.floating_crane_id, b.mother_vessel_id, 				b.pic, b.order_status, c.activity_name, d.name as floating_crane_name
					, e.name as mother_vessel_name, f.name as status_name
					from job_order_detail a left join job_order b on b.id = a.job_order_id
					left join activity c on c.id = a.activity_id
					left join floating_crane d on d.id = b.floating_crane_id
					left join mother_vessel e on e.id = b.mother_vessel_id
					left join status f on f.id = b.order_status
					where b.floating_crane_id = '.$id.' and b.is_active = 1)dt';
		

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

			array_push($output["aaData"],array(
				$row->date,
				$row->order_no,
				$row->order_name,
				$row->floating_crane_name,
				$row->mother_vessel_name,
				$row->activity_name,
				$row->datetime_start,
				$row->datetime_end,
				$row->total_time,
				$row->degree,
				$row->degree_2,
				$row->pic,
				$row->status_name

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
		$data = [
			'floating_crane_id'	=> trim($post['floating_crane']),
			'code' 				=> trim($post['code']),
			'name' 				=> trim($post['name']),
			'position' 			=> trim($post['posisi']),
			'ip_cctv' 			=> trim($post['ip_cctv']),
			'ip_server' 		=> trim($post['ip_server']),
			'rtsp' 				=> trim($post['rtsp']),
			'embed' 			=> trim($post['embed']),
			'type_streaming' 	=> trim($post['type_streaming']),
			'thumnail' 			=> trim($post['thumbnail']),
			'is_active' 		=> trim($post['is_active']),
			'latitude' 			=> trim($post['latitude']),
			'longitude' 		=> trim($post['longitude'])
		];

		return $rs = $this->db->insert($this->table_name, $data);
	}  

	public function edit_data($post) { 
		if(!empty($post['id'])){
			$data = [
				'floating_crane_id'	=> trim($post['floating_crane']),
				'code' 				=> trim($post['code']),
				'name' 				=> trim($post['name']),
				'position' 			=> trim($post['posisi']),
				'ip_cctv' 			=> trim($post['ip_cctv']),
				'ip_server' 		=> trim($post['ip_server']),
				'rtsp' 				=> trim($post['rtsp']),
				'embed' 			=> trim($post['embed']),
				'type_streaming' 	=> trim($post['type_streaming']),
				'thumnail' 			=> trim($post['thumbnail']),
				'is_active' 		=> trim($post['is_active']),
				'latitude' 			=> trim($post['latitude']),
				'longitude' 		=> trim($post['longitude'])
			];

			return  $rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.name as floating_crane_name, if(a.is_active=1,"Yes","No") as is_active_desc from cctv a left join floating_crane b on b.id = a.floating_crane_id)dt';

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
				'floating_crane_id'	=> $v["B"],
				'code' 				=> $v["C"],
				'name' 				=> $v["D"],
				'position' 			=> $v["E"],
				'ip_cctv' 			=> $v["F"],
				'ip_server' 		=> $v["G"],
				'rtsp' 				=> $v["H"],
				'embed' 			=> $v["I"],
				'type_streaming' 	=> $v["J"],
				'thumnail' 			=> $v["K"],
				'is_active' 		=> $v["L"],
				'latitude' 			=> $v["M"],
				'longitude' 		=> $v["N"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$id=1;



		$sql = "select dt.date,
			dt.order_no,
			dt.order_name,
			dt.floating_crane_name,
			dt.mother_vessel_name,
			dt.activity_name,
			dt.datetime_start,
			dt.datetime_end,
			dt.total_time,
			dt.degree,
			dt.degree_2,
			dt.pic,
			dt.status_name from (select a.*, b.date, b.order_no, b.order_name, b.floating_crane_id, b.mother_vessel_id, 				b.pic, b.order_status, c.activity_name, d.name as floating_crane_name
					, e.name as mother_vessel_name, f.name as status_name
					from job_order_detail a left join job_order b on b.id = a.job_order_id
					left join activity c on c.id = a.activity_id
					left join floating_crane d on d.id = b.floating_crane_id
					left join mother_vessel e on e.id = b.mother_vessel_id
					left join status f on f.id = b.order_status
					where b.floating_crane_id = '".$id."' and b.is_active = 1)dt
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}

	public function getTblCctv($cctv) { 
		
		$rs = $this->db->query("select a.*, b.name as floating_crane_name from cctv a left join floating_crane b on b.id = a.floating_crane_id where a.floating_crane_id = '".$cctv."' ")->result(); 
		$rd = $rs;


		if(!empty($rd)){ 

			
		
			foreach ($rd as $row){


				$thead .= '<th scope="col">'.$row->name.'</th>';
				$tbody .= '<td><iframe width="420" height="345" src="'.$row->embed.'">
					</iframe></td> ';

			}

			$dt .= '<div class="row ca">
                        <div class="col-md-12">
							<div class="portlet box green">
								<div class="portlet-title">
									<div class="caption"><i class="fa fa-cubes"></i>'.$row->floating_crane_name.' </div>
									<div class="tools">
								
									</div>
								</div>
								<div class="portlet-body">
									<div class="table-scrollable tablesaw-cont">
									<table class="table table-striped table-bordered table-hover reim-list tablesaw tablesaw-stack" id="tblJadwal" data-tablesaw-mode="stack">
										<thead>
											<tr>
											'.$thead.'
											</tr>
										</thead>
										<tbody>
											<tr>
											'.$tbody.'
											</tr>
										</tbody>
										<tfoot>
										</tfoot>
									</table>
									</div>
								</div>
							</div>
						</div>
					</div>';
		}


		return $dt;
	} 

	public function getJob($idfc, $start_date, $end_date){ 

		if($start_date != '' && $end_date != ''){
			$whr_date = " and date >= '".$start_date."' and date <= '".$end_date."' ";
		}else{ //default sebulan terakhir
			$today = date("Y-m-d");
			$start = date('Y-m-d',strtotime($today. ' - 1 months'));
			$end = date("Y-m-d");
			
			$whr_date = " and date >= '".$start."' and date <= '".$end."' ";
		}

		
		$sql = "select a.date, b.name as floating_crane_name, a.order_name, a.date_time_total
				FROM job_order a INNER JOIN floating_crane b ON a.floating_crane_id = b.id 
				where b.id = '".$idfc."' 
				".$whr_date."
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;

	}

	public function getActivity($jobId){ 

		$rs = $this->db->query("select a.*, b.activity_name, c.order_name
				from job_order_summary a left join activity b on b.id = a.activity_id
				left join job_order c on c.id = a.job_order_id
				where c.order_name = '".$jobId."' ")->result(); 


		return $rs;

	}

	public function getdetailwaktuAct($activity, $job){ 

		$rs = $this->db->query("select a.*, b.activity_name, c.order_name 
				from job_order_detail a left join activity b on b.id = a.activity_id 
				left join job_order c on c.id = a.job_order_id
				where c.order_name = '".$job."' and b.activity_name = '".$activity."'")->result(); 


		return $rs;

	}


	public function get_list_data_waktu($job, $activity)
	{ 

		$aColumns = [
			'dt.datetime_start',
			'dt.datetime_end',
			'dt.total_time',
			'dt.degree',
			'dt.degree_2'
		];
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.activity_name, c.order_name 
					from job_order_detail a left join activity b on b.id = a.activity_id 
					left join job_order c on c.id = a.job_order_id
					where c.order_name = "'.$job.'" and b.activity_name = "'.$activity.'")dt';
		

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

			array_push($output["aaData"],array(
				$row->datetime_start,
				$row->datetime_end,
				$row->total_time,
				$row->degree,
				$row->degree_2

			));
		}

		echo json_encode($output);
	}


}
