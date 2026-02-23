<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_project_model extends MY_Model
{
	/* Module */
 	protected $folder_name			= "project/data_project";
 	protected $table_name 			= _PREFIX_TABLE."data_project";
 	protected $table_customer 		= _PREFIX_TABLE."data_customer"; 
 	protected $table_karyawan 		= _PREFIX_TABLE."employees";
 	protected $table_status 		= _PREFIX_TABLE."option_project_status";
 	protected $primary_key 			= "id"; 

 	
 	/* upload */
 	/*protected $attachment_folder	= "./uploads/employee";*/
	protected $allow_type			= "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
	protected $allow_size			= "0"; // 0 for limit by default php conf (in Kb)


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
			'a.id',
			'a.project',
			'a.title',
			'b.code as ccustomer', 
			'IF(a.type="1","External","Internal") as tipe',
			'IF(a.date_plan_start IS NULL,"",a.date_plan_start) as dpstart',
			'IF(a.date_plan_finish IS NULL,"",a.date_plan_finish) as dpfinish',
			'IF(a.date_actual_start IS NULL,"",a.date_actual_start) as dastart',
			'IF(a.date_actual_finish IS NULL,"",a.date_actual_finish) as dafinish',
			'd.description as status'
		];

		$sIndexColumn = 'a.'.$this->primary_key;
		$sTable = ' '.$this->table_name.' a
					LEFT JOIN '.$this->table_customer.' b ON b.id=a.id_customer 
					LEFT JOIN '.$this->table_status.' d ON d.id=a.id_status
					';
			 

		

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
					'.$edit.'
					'.$delete.' 
				</div>',
				$row->id,
				$row->project,
				$row->title,
				$row->ccustomer, 
				$row->tipe,
				$row->dpstart,
				$row->dpfinish,
				$row->dastart,
				$row->dafinish,
				$row->status 
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
		$id_customer = (isset($post['id_customer']) && !empty($post['id_customer']))? trim($post['id_customer']):0; 
		$id_pic = (isset($post['id_pic']) && !empty($post['id_pic']))? trim($post['id_pic']):0;
		$id_pm = (isset($post['id_pm']) && !empty($post['id_pm']))? trim($post['id_pm']):0; 
		$type = (isset($post['type']) && !empty($post['type']))? trim($post['type']):0;
		$id_status = (isset($post['id_status']) && !empty($post['id_status']))? trim($post['id_status']):NULL;
		$data = [
			'project' 				=> trim($post['project']),
			'title' 				=> trim($post['title']),
			'id_customer' 			=> $id_customer,
			'id_spk' 				=> trim($post['id_spk']),
			'nilai_project' 		=> trim($post['nilai_project']),
			'estimasi_cost' 		=> trim($post['estimasi_cost']), 
			'id_pic' 				=> $id_pic,
			'id_pm' 				=> $id_pm, 
			'type' 					=> $type,
			'date_plan_start' 		=> date("Y-m-d", strtotime(trim($post['date_plan_start']))),   
			'date_plan_finish' 		=> date("Y-m-d", strtotime(trim($post['date_plan_finish']))),  
			'date_actual_start' 	=> date("Y-m-d", strtotime(trim($post['date_actual_start']))),  
			'date_actual_finish'	=> date("Y-m-d", strtotime(trim($post['date_actual_finish']))),   
			'id_status' 			=> $id_status,
			'insert_by'				=> $_SESSION["username"]
		];

		$rs = $this->db->insert($this->table_name, $data);
		if($rs){
			return [
			    "status" => true,
			    "msg"    => "Data berhasil disimpan"
			];
		}else{
			return [
			    "status" => false,
			    "msg"    => "Data gagal disimpan"
			];
		}


	}  

	public function edit_data($post) {   
		if(!empty($post['id'])){ 
			$id_customer = (isset($post['id_customer']) && !empty($post['id_customer']))? trim($post['id_customer']):0; 
			$id_pic = (isset($post['id_pic']) && !empty($post['id_pic']))? trim($post['id_pic']):0;
			$id_pm = (isset($post['id_pm']) && !empty($post['id_pm']))? trim($post['id_pm']):0; 
			$type = (isset($post['type']) && !empty($post['type']))? trim($post['type']):0;
			$id_status = (isset($post['id_status']) && !empty($post['id_status']))? trim($post['id_status']):NULL;
			$data = [
				'project' 				=> trim($post['project']),
				'title' 				=> trim($post['title']),
				'id_customer' 			=> $id_customer,
				'id_spk' 				=> trim($post['id_spk']),
				'nilai_project' 		=> trim($post['nilai_project']),
				'estimasi_cost' 		=> trim($post['estimasi_cost']), 
				'id_pic' 				=> $id_pic,
				'id_pm' 				=> $id_pm, 
				'type' 					=> $type,
				'date_plan_start' 		=> date("Y-m-d", strtotime(trim($post['date_plan_start']))),   
				'date_plan_finish' 		=> date("Y-m-d", strtotime(trim($post['date_plan_finish']))),  
				'date_actual_start' 	=> date("Y-m-d", strtotime(trim($post['date_actual_start']))),  
				'date_actual_finish'	=> date("Y-m-d", strtotime(trim($post['date_actual_finish']))),   
				'id_status' 			=> $id_status,
				'update_by'				=> $_SESSION["username"]
			];

			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]); 

			if($rs){
				return [
				    "status" => true,
				    "msg"    => "Data berhasil disimpan"
				];
			}else{
				return [
				    "status" => false,
				    "msg"    => "Data gagal disimpan"
				];
			}

		} else{
			return [
			    "status" => false,
			    "msg"    => "Data not found"
			];
		}
	}  

	public function getRowData($id) { 
		$rs = $this->db->select('*,title as ttitle, IF(date_plan_start IS NULL,"",date_plan_start) as dpstart, IF(date_plan_finish IS NULL,"",date_plan_finish) as dpfinish, IF(date_actual_start IS NULL,"",date_actual_start) as dastart, IF(date_actual_finish IS NULL,"",date_actual_finish) as dafinish')->where([$this->primary_key => $id])->get($this->table_name)->row();
		
		if(!empty($rs->id_customer)){
			$rd = $this->db->select('code as ccustomer')->where([$this->primary_key => $rs->id_customer])->get($this->table_customer)->row();
			$rs = (object) array_merge((array) $rs, (array) $rd);
		} else {
			$rs = (object) array_merge((array) $rs, ['ccustomer'=>'']);
		}
	 	
		if(!empty($rs->id_pic)){
			$rd = $this->db->select('full_name as pic')->where([$this->primary_key => $rs->id_pic])->get($this->table_karyawan)->row();
			
			$rs = (object) array_merge((array) $rs, (array) $rd);
		} else {
			$rs = (object) array_merge((array) $rs, ['pic'=>'']);
		} 
	
		if(!empty($rs->id_pm)){
			$rd = $this->db->select('full_name as pm')->where([$this->primary_key => $rs->id_pm])->get($this->table_karyawan)->row();
			$rs = (object) array_merge((array) $rs, (array) $rd);
		} else {
			$rs = (object) array_merge((array) $rs, ['pm'=>'']);
		} 
		 
		if(!empty($rs->id_status)){
			$rd = $this->db->select('description as status')->where([$this->primary_key => $rs->id_status])->get($this->table_status)->row();
			$rs = (object) array_merge((array) $rs, (array) $rd);
		} else {
			$rs = (object) array_merge((array) $rs, ['status'=>'']);
		} 
			
		unset($rs->date_insert);
		unset($rs->insert_by);
		unset($rs->date_update);
		unset($rs->update_by);
		unset($rs->id_gm);
		unset($rs->id_adm);
		unset($rs->deskripsi);
		
		return $rs;
	} 

	/*
	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'employee_id' 	=> $v["B"],
				'training_name' => $v["C"],
				'training_date' => $v["D"],
				'location' 		=> $v["E"],
				'trainer' 		=> $v["F"],
				'notes' 		=> $v["G"],
				'status_id' 	=> $v["H"],
				'created_at' 	=> date("Y-m-d H:i:s")
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}


		
		$sql = 'select a.*, b.full_name, b.direct_id, b.emp_code,
				(case
				when a.status_id = 1 then "Waiting Approval"
				when a.status_id = 2 then "Approved"
				when a.status_id = 3 then "Rejected"
				else ""
				 end) as status_name
				from employee_training a left join employees b on b.id = a.employee_id
				'.$whr.'
				order by a.id asc

		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}
		*/ 
}