<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Performance_plan_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "performance_management/performance_plan_menu";
 	protected $table_name 				= _PREFIX_TABLE."performance_plan";
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
			'dt.full_name',
			'dt.year',
			'dt.status_name',
			'dt.direct_id',
			'dt.rfu_reason',
			'dt.employee_id'
		];
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name, b.direct_id,
					(case 
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "RFU"
					else ""
					 end) as status_name
					from performance_plan a left join employees b on b.id = a.employee_id)dt';

		

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


		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

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
			if (_USER_ACCESS_LEVEL_UPDATE == "1" && (($row->status_name == 'RFU' && $karyawan_id == $row->employee_id ) || ($row->status_name == 'Waiting Approval' && $row->direct_id == $karyawan_id) ) )  {
				$edit = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				$delete = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}

			/*$reject=""; */
			/*$approve=""; $rfu="";
			if($row->status_name == 'Waiting Approval' && $row->direct_id == $karyawan_id){*/
				/*$reject = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="reject('."'".$row->id."'".')" role="button"><i class="fa fa-times"></i></a>';*/
				/*$rfu = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="rfu('."'".$row->id."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" href="javascript:void(0);" onclick="approve('."'".$row->id."'".')" role="button"><i class="fa fa-check"></i></a>';
			}*/

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
				</div>',
				$row->id,
				$row->full_name,
				$row->year,
				$row->status_name,
				$row->rfu_reason

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
		
  		if(!empty($post['employee']) && !empty($post['year'])){ 

  			$data_plan = $this->db->query("select * from performance_plan where employee_id = '".$post['employee']."' and year = '".$post['year']."'")->result(); 

  			if(empty($data_plan)){ 
  				$data = [
					'employee_id' 	=> trim($post['employee']),
					'year' 			=> trim($post['year']),
					'status_id' 	=> 1, //waiting approval
					'created_at'	=> date("Y-m-d H:i:s")
					
				];
				$rs = $this->db->insert($this->table_name, $data);
				$lastId = $this->db->insert_id();

				if($rs){ 
					if(isset($post['hardskill'])){
						$item_num = count($post['hardskill']); // cek sum
						$item_len_min = min(array_keys($post['hardskill'])); // cek min key index
						$item_len = max(array_keys($post['hardskill'])); // cek max key index
					} else {
						$item_num = 0;
					}

					if($item_num>0){
						for($i=$item_len_min;$i<=$item_len;$i++) 
						{
						
							if(isset($post['hardskill'][$i])){
								$itemData = [
									'performance_plan_id' 	=> $lastId,
									'hardskill' 			=> trim($post['hardskill'][$i]),
									'notes' 				=> trim($post['notes'][$i]),
									'weight' 				=> trim($post['weight'][$i])
								];

								$this->db->insert('performance_plan_hardskill', $itemData);
							}
						}
					}

					return $rs;
				}else{
					return null;
				}
  			}else return null;

  		}else return null;

	}  

	public function edit_data($post) { 
		
		if(!empty($post['id'])){ 
			$rowdata = $this->db->query("select * from performance_plan where id = '".$post['id']."' ")->result(); 
			$next_status='';
			if($rowdata[0]->status_id == 3){ // rfu
				$next_status = 1; //waiting approval direct
			}else if($rowdata[0]->status_id == 1){
				$next_status = 2; //approved
			}


			$data = [
				'employee_id' 	=> trim($post['employee']),
				'year' 			=> trim($post['year']),
				'updated_at'	=> date("Y-m-d H:i:s"),
				'status_id' 	=> $next_status,
				'rfu_reason' 	=> ''
				
			];
			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

			if($rs){ 
				if($next_status != 2){
					if(isset($post['hardskill'])){
						$item_num = count($post['hardskill']); // cek sum
						$item_len_min = min(array_keys($post['hardskill'])); // cek min key index
						$item_len = max(array_keys($post['hardskill'])); // cek max key index
					} else {
						$item_num = 0;
					}

					if($item_num>0){
						for($i=$item_len_min;$i<=$item_len;$i++) 
						{
							$hdnid = $post['hdnid'][$i];
							if(isset($post['hardskill'][$i])){
								if($hdnid != ''){ //update
									$itemData = [
										'hardskill' 		=> trim($post['hardskill'][$i]),
										'notes' 			=> trim($post['notes'][$i]),
										'weight' 			=> trim($post['weight'][$i])
									];
									$this->db->update('performance_plan_hardskill', $itemData, "id = '".$hdnid."'");
								}else{ //insert
									$itemData = [
										'performance_plan_id' 	=> $post['id'],
										'hardskill' 			=> trim($post['hardskill'][$i]),
										'notes' 				=> trim($post['notes'][$i]),
										'weight' 				=> trim($post['weight'][$i])
									];

									$this->db->insert('performance_plan_hardskill', $itemData);
								}
							}
						}
					}
				}

				if($next_status == 2){ //approved
					//ADD DATA KE APPRAISAL
					$data_plan = $this->db->query("select * from performance_plan where id = '".$post['id']."'")->result();

					$data2 = [
						'employee_id' 	=> $data_plan[0]->employee_id,
						'year' 			=> $data_plan[0]->year,
						'status_id' 	=> 0, //draft
						'created_at'	=> date("Y-m-d H:i:s")
						
					];
					$rs_appraisal = $this->db->insert('performance_appraisal', $data2);
					$lastId = $this->db->insert_id();

					$data_plan_hardskill = $this->db->query("select * from performance_plan_hardskill where performance_plan_id = '".$post['id']."'")->result();

					foreach($data_plan_hardskill as $row_plan){
						$itemData2 = [
							'performance_appraisal_id' 	=> $lastId,
							'hardskill' 				=> $row_plan->hardskill,
							'notes' 					=> $row_plan->notes,
							'weight' 					=> $row_plan->weight
						];

						$this->db->insert('performance_appraisal_hardskill', $itemData2);
					}
				}
				
				return $rs; 
			}else return null;

		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.full_name, b.direct_id,
					(case 
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "RFU"
					else ""
					 end) as status_name
					from performance_plan a left join employees b on b.id = a.employee_id
					)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		

		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

		$isdirect = 0;
		if($rs->direct_id == $karyawan_id){
			$isdirect = 1;
		}
		
		$data = array(
			'rowdata' 	=> $rs,
			'isdirect' 	=> $isdirect
		);
		
		return $data;
		
	} 

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'employee_id' 	=> $v["B"],
				'year' 			=> $v["C"],
				'status_id' 	=> 1,
				'created_at' 	=> date("Y-m-d H:i:s")
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$sql = 'select a.id, b.full_name, a.year, 
				(case 
				when a.status_id = 1 then "Waiting Approval"
				when a.status_id = 2 then "Approved"
				when a.status_id = 3 then "RFU"
				else ""
				 end) as status_name
				from performance_plan a left join employees b on b.id = a.employee_id
				order by a.id asc

		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getNewHardskillRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getHardskillRows($id,$view);
		} else {  
			$data = '';
			$no = $row+1;
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value=""/></td>';
			
			$data 	.= '<td>'.$this->return_build_txt('','hardskill['.$row.']','','hardskill','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txtarea('','notes['.$row.']','','notes','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','weight['.$row.']','','weight','text-align: right;','data-id="'.$row.'" ').'</td>';
			
			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getHardskillRows($id,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select * from performance_plan_hardskill where performance_plan_id = '".$id."' ")->result(); 
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

				if(!$view){ 
					
					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';
					
					$dt .= '<td>'.$this->return_build_txt($f->hardskill,'hardskill['.$row.']','','hardskill','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txtarea($f->notes,'notes['.$row.']','','notes','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->weight,'weight['.$row.']','','weight','text-align: right;','data-id="'.$row.'" ').'</td>';
					
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
					$dt .= '<td>'.$f->hardskill.'</td>';
					$dt .= '<td>'.$f->notes.'</td>';
					$dt .= '<td>'.$f->weight.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


	



}
