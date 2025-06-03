<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_shift_schedule_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "time_attendance/group_shift_schedule_menu";
 	protected $table_name 				= _PREFIX_TABLE."group_shift_schedule";
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
			'dt.periode',
			'dt.group_name'
		];
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.name as group_name from group_shift_schedule a 
						left join master_group_shift b on b.id = a.master_group_shift_id
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
			if($row->status_name == 'Waiting Approval' && $row->direct_id == $direct_karyawan_id){
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
				$row->periode,
				$row->group_name

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

		$period = $post['period'];
		$group 	= $post['group'];
		

		if($period != '' && $group != ''){
			$exist_data = $this->db->query("select * from group_shift_schedule where periode = '".$period."' and master_group_shift_id = '".$group."'")->result(); 

			if(empty($exist_data)){
				$data = [
					'periode' 				=> $period,
					'master_group_shift_id'	=> $group,
					'created_at'			=> date("Y-m-d H:i:s")
				];
				$rs = $this->db->insert($this->table_name, $data);
				$lastId = $this->db->insert_id();


				if($rs){
					if(isset($post['shift'])){
						$item_num = count($post['shift']); // cek sum
						$item_len_min = min(array_keys($post['shift'])); // cek min key index
						$item_len = max(array_keys($post['shift'])); // cek max key index
					} else {
						$item_num = 0;
					}

					if($item_num>0){

						for($i=$item_len_min;$i<=$item_len;$i++) 
						{
							$tgl=$i+1;
							$field = sprintf("%02d", $tgl);
							if(isset($post['shift'][$i])){
								$itemData = [
									'`'.$field.'`' 	=> trim($post['shift'][$i])
								];
								$this->db->update($this->table_name, $itemData, "id = '".$lastId."'");
							}
						}
					}


					//insert employee list
					if(isset($post['employee'])){
						$item_num2 = count($post['employee']); // cek sum
						$item_len_min2 = min(array_keys($post['employee'])); // cek min key index
						$item_len2 = max(array_keys($post['employee'])); // cek max key index
					} else {
						$item_num2 = 0;
					}

					if($item_num2>0){
						for($j=$item_len_min2;$j<=$item_len2;$j++) 
						{
							
							if(isset($post['employee'][$j])){
								$itemData2 = [
									'group_shift_schedule_id' 	=> $lastId,
									'employee_id' 				=> trim($post['employee'][$j]),
									'created_at' 				=> date("Y-m-d H:i:s")
								];

								$this->db->insert('shift_schedule', $itemData2);
							}
						}
					}
					//end insert employee list



					return $rs;

				}else return null;
			}else return null;
		}else return null;
		
	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){

			$period = $post['period'];
			$group 	= $post['group'];


			if($period != '' && $group != ''){
			
				$data = [
					'periode' 				=> $period,
					'master_group_shift_id'	=> $group,
					'updated_at'			=> date("Y-m-d H:i:s")
				];
				$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

				if(isset($post['shift'])){
					$item_num = count($post['shift']); // cek sum
					$item_len_min = min(array_keys($post['shift'])); // cek min key index
					$item_len = max(array_keys($post['shift'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){

					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						$tgl=$i+1;
						$field = sprintf("%02d", $tgl);
						if(isset($post['shift'][$i])){
							$itemData = [
								'`'.$field.'`' 	=> trim($post['shift'][$i])
							];
							$this->db->update($this->table_name, $itemData, "id = '".$post['id']."'");
						}
					}
				}


				//insert employee list
				if(isset($post['employee'])){
					$item_num2 = count($post['employee']); // cek sum
					$item_len_min2 = min(array_keys($post['employee'])); // cek min key index
					$item_len2 = max(array_keys($post['employee'])); // cek max key index
				} else {
					$item_num2 = 0;
				}

				if($item_num2>0){
					for($j=$item_len_min2;$j<=$item_len2;$j++) 
					{
						$hdnid = trim($post['hdnid'][$j]);
						if(!empty($hdnid)){ //update
							if(isset($post['employee'][$j])){
								$itemData2 = [
									'employee_id' 				=> trim($post['employee'][$j]),
									'updated_at' 				=> date("Y-m-d H:i:s")
								];

								$this->db->update('shift_schedule', $itemData2, "id = '".$hdnid."'");
							}
						}else{ //insert
							if(isset($post['employee'][$j])){
								$itemData2 = [
									'group_shift_schedule_id' 	=> $post['id'],
									'employee_id' 				=> trim($post['employee'][$j]),
									'created_at' 				=> date("Y-m-d H:i:s")
								];

								$this->db->insert('shift_schedule', $itemData2);
							}
						}
						
					}
				}
				//end insert employee list


				return $rs;

			}else return null;
				

		} else return null;
	}  

	public function getRowData($id) { 
		/*$mTable = '(select a.*, b.name as group_name from group_shift_schedule a 
						left join master_group_shift b on b.id = a.master_group_shift_id

			)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();*/

		$rs = $this->db->query("select a.*, b.name as group_name from group_shift_schedule a 
						left join master_group_shift b on b.id = a.master_group_shift_id where a.id = '".$id."' ")->result(); 
		

		$shiftData = [];
		for($i=0; $i<31; $i++){
			$tgl=$i+1;
			$field = sprintf("%02d", $tgl);

			$shiftData[$field] = $rs[0]->$field;

		}
		

		
		$dataX = array(
			'id' 					=> $rs[0]->id,
			'periode' 				=> $rs[0]->periode,
			'master_group_shift_id'	=> $rs[0]->master_group_shift_id,
			'group_name' 			=> $rs[0]->group_name,
			'data_shift' 			=> $shiftData

		);
		
		
		return $dataX;
	} 

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'periode' 	=> $v["B"],
				'master_group_shift_id'	=> $v["C"],
				'`01`' 		=> $v["D"],
				'`02`' 		=> $v["E"],
				'`03`' 		=> $v["F"],
				'`04`' 		=> $v["G"],
				'`05`' 		=> $v["H"],
				'`06`' 		=> $v["I"],
				'`07`' 		=> $v["J"],
				'`08`' 		=> $v["K"],
				'`09`' 		=> $v["L"],
				'`10`' 		=> $v["M"],
				'`11`' 		=> $v["N"],
				'`12`' 		=> $v["O"],
				'`13`' 		=> $v["P"],
				'`14`' 		=> $v["Q"],
				'`15`' 		=> $v["R"],
				'`16`' 		=> $v["S"],
				'`17`' 		=> $v["T"],
				'`18`' 		=> $v["U"],
				'`19`' 		=> $v["V"],
				'`20`' 		=> $v["W"],
				'`21`' 		=> $v["X"],
				'`22`' 		=> $v["Y"],
				'`23`' 		=> $v["Z"],
				'`24`' 		=> $v["AA"],
				'`25`' 		=> $v["AB"],
				'`26`' 		=> $v["AC"],
				'`27`' 		=> $v["AD"],
				'`28`' 		=> $v["AE"],
				'`29`' 		=> $v["AF"],
				'`30`' 		=> $v["AG"],
				'`31`' 		=> $v["AH"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$sql = 'select a.*, b.name as group_name from group_shift_schedule a 
				left join master_group_shift b on b.id = a.master_group_shift_id
				order by a.id asc
		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getNewEmplistRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getEmplistRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			$msEmp = $this->db->query("select * from employees")->result(); 
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value=""/></td>';
			$data 	.= '<td>'.$this->return_build_chosenme($msEmp,'','','','employee['.$row.']','employee','employee','','id','full_name','','','',' data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getEmplistRows($id,$view,$print=FALSE){ 
		
		$dt = ''; 
		
		$rs = $this->db->query("select a.*, b.full_name from shift_schedule a 
								left join employees b on b.id = a.employee_id
								where a.group_shift_schedule_id = '".$id."' ")->result(); 

		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			/*if($view){
				$arrSat = json_decode(json_encode($msObat), true);
				$arrS = [];
				foreach($arrSat as $ai){
					$arrS[$ai['id']] = $ai;
				}
			}*/
			foreach ($rd as $f){
				$no = $row+1;
				$msEmp = $this->db->query("select * from employees")->result(); 

				if(!$view){ 
					
					$dt .= '<tr>';
					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';
					$dt .= '<td>'.$this->return_build_chosenme($msEmp,'',isset($f->employee_id)?$f->employee_id:1,'','employee['.$row.']','employee','employee','','id','full_name','','','',' data-id="'.$row.'" ').'</td>';
					$dt .= '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete" onclick="del(\''.$row.'\',\''.$f->id.'\')"></td>';
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
					$dt .= '<td>'.$f->full_name.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}




}
