<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lembur_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "time_attendance/lembur_menu";
 	protected $table_name 				= _PREFIX_TABLE."overtimes";
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
			'dt.date_overtime',
			'dt.full_name',
			'dt.datetime_start',
			'dt.datetime_end',
			'dt.num_of_hour',
			'dt.amount',
			'dt.reason',
			'dt.status_name',
			'dt.direct_id'
		];
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name,  b.direct_id,
					(case 
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					end) as status_name 
					from overtimes a left join employees b on b.id = a.employee_id)dt';
		

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

		$getdirect = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$direct_karyawan_id = $getdirect[0]->id_karyawan;

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

			$reject=""; 
			$approve="";
			if($row->status == 'Waiting Approval' && $row->direct_id == $direct_karyawan_id){
				$reject = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="reject('."'".$row->id."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" href="javascript:void(0);" onclick="approve('."'".$row->id."'".')" role="button"><i class="fa fa-check"></i></a>';
			}
			

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$reject.'
					'.$approve.'
				</div>',
				$row->id,
				$row->date_overtime,
				$row->full_name,
				$row->datetime_start,
				$row->datetime_end,
				$row->num_of_hour,
				$row->amount,
				$row->reason,
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

	public function dayCount($from, $to) {
	    $first_date = strtotime($from);
	    $second_date = strtotime($to);
	    $days_diff = $second_date - $first_date;
	    return date('d',$days_diff);
	}

	public function add_data($post) { 

		$date_overtime 		= date_create($post['date']);
		$datetime_start 	= date_create($post['datetime_start']);
		$datetime_end 		= date_create($post['datetime_end']);
		$f_datetime_start 	= date_format($datetime_start,"Y-m-d H:i:s");
		$f_datetime_end 	= date_format($datetime_end,"Y-m-d H:i:s");
		$f_date_overtime 	= date_format($date_overtime,"Y-m-d");

		$timestamp1 = strtotime($f_datetime_start); 
		$timestamp2 = strtotime($f_datetime_end);

		
		$num_of_hour= abs($timestamp2 - $timestamp1); //jam
		$biaya='50000';
		$amount = $num_of_hour*$biaya;


		if($post['employee'] != '' && $post['datetime_start'] != '' && $post['datetime_end'] != ''){
			
			$data = [
				'date_overtime' 			=> $f_date_overtime,
				'employee_id' 				=> trim($post['employee']),
				'datetime_start' 			=> $f_datetime_start,
				'datetime_end' 				=> $f_datetime_end,
				'num_of_hour' 				=> $num_of_hour,
				'amount' 					=> $amount,
				'reason' 					=> trim($post['reason']),
				'status_id' 				=> 1,
				'created_at'				=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert($this->table_name, $data);

		}else return null;
		
	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){

			$date_overtime 		= date_create($post['date']);
			$datetime_start 	= date_create($post['datetime_start']);
			$datetime_end 		= date_create($post['datetime_end']);
			$f_datetime_start 	= date_format($datetime_start,"Y-m-d H:i:s");
			$f_datetime_end 	= date_format($datetime_end,"Y-m-d H:i:s");
			$f_date_overtime 	= date_format($date_overtime,"Y-m-d");

			$timestamp1 = strtotime($f_datetime_start); 
			$timestamp2 = strtotime($f_datetime_end);

			
			$num_of_hour= abs($timestamp2 - $timestamp1); //jam
			$biaya='50000';
			$amount = $num_of_hour*$biaya;


			if($post['employee'] != '' && $post['datetime_start'] != '' && $post['datetime_end'] != ''){
			
				$data = [
					'date_overtime' 			=> $f_date_overtime,
					'employee_id' 				=> trim($post['employee']),
					'datetime_start' 			=> $f_datetime_start,
					'datetime_end' 				=> $f_datetime_end,
					'num_of_hour' 				=> $num_of_hour,
					'amount' 					=> $amount,
					'reason' 					=> trim($post['reason']),
					'updated_at'				=> date("Y-m-d H:i:s")
				];
				$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

			}else return null;
				

		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.full_name, b.direct_id,
					(case 
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					end) as status_name 
					from overtimes a left join employees b on b.id = a.employee_id

			)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		
		
		return $rs;
	} 

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$timestamp1 = strtotime($v["D"]); //Y-m-d H:i:s
			$timestamp2 = strtotime($v["E"]); //Y-m-d H:i:s

			
			$num_of_hour= abs($timestamp2 - $timestamp1); //jam
			$biaya='50000';
			$amount = $num_of_hour*$biaya;

			$data = [
				'date_overtime' 	=> $v["B"],
				'employee_id' 		=> $v["C"],
				'datetime_start' 	=> $v["D"],
				'datetime_end' 		=> $v["E"],
				'num_of_hour' 		=> $num_of_hour,
				'amount' 			=> $amount,
				'reason' 			=> $v["F"],
				'created_at' 		=> $v["G"],
				'status_id' 		=> $v["H"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$sql = 'select a.id, a.date_overtime, b.full_name, a.datetime_start, a.datetime_end, a.num_of_hour, a.amount,a.reason, 
				(case 
				when a.status_id = 1 then "Waiting Approval"
				when a.status_id = 2 then "Approved"
				when a.status_id = 3 then "Rejected"
				else ""
				end) as status_name 
				from overtimes a left join employees b on b.id = a.employee_id
				order by a.id asc
		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}




}
