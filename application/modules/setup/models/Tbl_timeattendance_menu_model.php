<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tbl_timeattendance_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "setup/tbl_timeattendance_menu";
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
			'dt.id',
			'dt.date_attendance',
			'dt.employee_id',
			'dt.attendance_type',
			'dt.time_in',
			'dt.time_out',
			'dt.date_attendance_in',
			'dt.date_attendance_out',
			'dt.is_late',
			'dt.is_leaving_office_early',
			'dt.num_of_working_hours',
			'dt.created_at',
			'dt.updated_at',
			'dt.leave_type',
			'dt.notes',
			'dt.photo'
		];
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select * from time_attendances)dt';
		

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
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #343851; border-color: #343851; href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500; href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
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
					'.$edit.'
					'.$delete.'
				</div>',
				$row->id,
				$row->date_attendance,
				$row->employee_id,
				$row->attendance_type,
				$row->time_in,
				$row->time_out,
				$row->date_attendance_in,
				$row->date_attendance_out,
				$row->is_late,
				$row->is_leaving_office_early,
				$row->num_of_working_hours,
				$row->created_at,
				$row->updated_at,
				$row->leave_type,
				$row->notes,
				$row->photo


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

		$pwd 		= trim($post['pwd']);
		$password 	= md5($pwd);

		$username = $post['username'];
		if(empty($username)){ 
			$name = $post['hdnname'];
			$username = strtolower($name);

			if ($username == trim($username) && strpos($username, ' ') !== false) {
			    $username = str_replace(" ","_",$username);
			}
		}
		
  		if(!empty($post['name'])){ 
  			$data_user = $this->db->query("select * from user where id_karyawan = '".$post['name']."' ")->result(); 
  			if(empty($data_user)){
  				$data_username = $this->db->query("select * from user where username = '".$username."' ")->result();
  				if(empty($data_username)){
  					$data = [
						'name' 			=> trim($post['hdnname']),
						'email' 		=> trim($post['email']),
						'username'		=> $username,
						'passwd' 		=> $password,
						'id_karyawan'	=> trim($post['name']),
						'id_groups' 	=> 3, //user
						'base_menu'		=> 'role',
						//'id_branch'		=> '',
						'isaktif' 		=> 2,
						'date_insert' 	=> date("Y-m-d H:i:s")
					];
					return $rs = $this->db->insert($this->table_name, $data);

					echo 'dd'; die();
  				}else echo 'cc'; die();//return null;
  				
  			}else echo 'bb'; die();//return null;
  			
  		}else echo 'aa'; die();//return null;

	}  

	public function edit_data($post) { 
		$pwd 		= trim($post['pwd']);
		$password 	= md5($pwd);
		
		
		if(!empty($post['id'])){ 
			
			$olddata = $this->db->query("select * from user where user_id = '".$post['id']."' ")->result();
			if($olddata[0]->id_karyawan == $post['name']){
				$data_username = $this->db->query("select * from user where username = '".$post['username']."' ")->result();
				if( empty($data_username) || (!empty($data_username) && $data_username[0]->id_karyawan == $post['name']) ){
					if(!empty($pwd)){
						$data = [
							/*'name' 			=> trim($post['hdnname']),
							'email' 		=> trim($post['email']),*/
							'username'		=> trim($post['username']),
							'passwd' 		=> $password,
							/*'id_karyawan'	=> trim($post['name']),*/
							/*'id_groups' 	=> 3, //user
							'base_menu'		=> 'role',*/
							//'id_branch'		=> '',
							'isaktif' 		=> trim($post['status']),
							'date_update' 	=> date("Y-m-d H:i:s")
						];
					}else{
						$data = [
							/*'name' 			=> trim($post['hdnname']),
							'email' 		=> trim($post['email']),*/
							'username'		=> trim($post['username']),
							/*'id_karyawan'	=> trim($post['name']),*/
							/*'id_groups' 	=> 3, //user
							'base_menu'		=> 'role',*/
							//'id_branch'		=> '',
							'isaktif' 		=> trim($post['status']),
							'date_update' 	=> date("Y-m-d H:i:s")
						];
					}
				

					return  $rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
				}else return null;
				
			}else return null;

		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.full_name, b.personal_email, (case when a.isaktif = 2 then "Active" when a.isaktif = 0 then "Not Active" else "" end) as statusname from user a left join employees b on b.id = a.id_karyawan)dt';

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
				'status_id' 			=> $v["G"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$sql = "select b.full_name as employee_name, a.task, c.task as parent_name, d.name as status_name, a.progress_percentage, a.due_date   
			from tasklist a left join employees b on b.id = a.employee_id
			left join tasklist c on c.id = a.parent_id
			left join master_tasklist_status d on d.id = a.status_id order by a.id asc
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}

	public function getDataEmployee($empid){ 

		$rs = $this->db->query("select * from employees where id = '".$empid."' ")->result(); 

		$dataX = array(
			'email' 	=> $rs[0]->personal_email,
			'full_name' => $rs[0]->full_name
		);


		return $dataX;

	}


}
