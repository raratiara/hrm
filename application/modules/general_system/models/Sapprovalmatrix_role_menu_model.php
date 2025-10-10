<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sapprovalmatrix_role_menu_model extends MY_Model
{
	/* Module */
	protected $folder_name 	= "general_system/sapprovalmatrix_role_menu";
	protected $table_name 	= _PREFIX_TABLE . "approval_matrix_role";
	protected $primary_key 	= "id";

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
			'dt.role_name',
			'dt.work_location_name',
			'dt.description'
		];

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.name as work_location_name from approval_matrix_role a
					left join master_work_location b on b.id = a.work_location_id
				)dt';


		/* Paging */
		$sLimit = "";
		if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
			$sLimit = "LIMIT " . ($_GET['iDisplayStart']) . ", " .
				($_GET['iDisplayLength']);
		}

		/* Ordering */
		$sOrder = "";
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = "ORDER BY  ";
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					$srcCol = $aColumns[intval($_GET['iSortCol_' . $i])];
					$findme = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sOrder .= trim($pieces[0]) . "
						" . ($_GET['sSortDir_' . $i]) . ", ";
					} else {
						$sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
						" . ($_GET['sSortDir_' . $i]) . ", ";
					}
				}
			}

			$sOrder = substr_replace($sOrder, "", -2);
			if ($sOrder == "ORDER BY") {
				$sOrder = "";
			}
		}

		/* Filtering */
		$sWhere = " WHERE 1 = 1 ";
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
			$sWhere .= "AND (";
			foreach ($aColumns as $c) {
				if ($c !== NULL) {
					$srcCol = $c;
					$findme = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0]) . " LIKE '%" . ($_GET['sSearch']) . "%' OR ";
					} else {
						$sWhere .= $c . " LIKE '%" . ($_GET['sSearch']) . "%' OR ";
					}
				}
			}

			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		for ($i = 0; $i < count($aColumns); $i++) {
			if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && isset($_GET['sSearch_' . $i]) && $_GET['sSearch_' . $i] != '') {
				if ($sWhere == "") {
					$sWhere = "WHERE ";
				} else {
					$sWhere .= " AND ";
				}
				$srcString = $_GET['sSearch_' . $i];
				$findme = '|';
				$pos = strpos($srcString, $findme);
				if ($pos !== false) {
					$srcKey = "";
					$pieces = explode($findme, trim($srcString));
					foreach ($pieces as $value) {
						if (!empty($srcKey)) {
							$srcKey .= ",";
						}
						$srcKey .= "'" . $value . "'";
					}

					$srcCol = $aColumns[$i];
					$findme = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0]) . " IN (" . $srcKey . ") ";
					} else {
						$sWhere .= $aColumns[$i] . " IN (" . $srcKey . ") ";
					}
				} else {
					$srcCol = $aColumns[$i];
					$findme = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0]) . " LIKE '%" . ($srcString) . "%' ";
					} else {
						$sWhere .= $aColumns[$i] . " LIKE '%" . ($srcString) . "%' ";
					}
				}
			}
		}

		/* Get data to display */
		$filtered_cols = array_filter($aColumns, [$this, 'is_not_null']); // Filtering NULL value
		$sQuery = "
		SELECT  SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $filtered_cols)) . "
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
			SELECT COUNT(" . $sIndexColumn . ") AS total
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

		foreach ($rResult as $row) {
			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1") {
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #343851; border-color: #343851;" href="javascript:void(0);" onclick="detail(' . "'" . $row->id . "'" . ')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1") {
				if($row->role_name == 'Direct' || $row->role_name == 'Indirect'){
					$edit = "";
				}else{
					$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit(' . "'" . $row->id . "'" . ')" role="button"><i class="fa fa-pencil"></i></a>';
				}
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1") {
				if($row->role_name == 'Direct' || $row->role_name == 'Indirect'){
					$delete_bulk = "";
					$delete = "";
				}else{
					$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="' . $row->id . '">';
					$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting(' . "'" . $row->id . "'" . ')" role="button"><i class="fa fa-trash"></i></a>';
				}
				
			}

			array_push($output["aaData"], array(
				$delete_bulk,
				'<div class="action-buttons">
					' . $detail . '
					' . $edit . '
					' . $delete . '
				</div>',
				$row->id,
				$row->role_name,
				$row->work_location_name,
				$row->description

			));
		}

		echo json_encode($output);
	}

	// filltering null value from array
	public function is_not_null($val)
	{
		return !is_null($val);
	}

	public function delete($id = "")
	{
		if (isset($id) && $id <> "") {
			//$this->db->trans_off(); // Disable transaction
			$this->db->trans_start(); // set "True" for query will be rolled back
			$this->db->where([$this->primary_key => $id])->delete($this->table_akses_name);
			$this->db->where([$this->primary_key => $id])->delete($this->table_akses_role_name);
			$this->db->where([$this->primary_key => $id])->delete($this->table_name);
			$this->db->trans_complete();

			return $rs = $this->db->trans_status();
		} else
			return null;
	}

	// delete multi items action
	public function bulk($id = "")
	{
		if (is_array($id) && count($id)) {
			$err = '';
			foreach ($id as $pid) {
				//$this->db->trans_off(); // Disable transaction
				$this->db->trans_start(); // set "True" for query will be rolled back
				$this->db->where([$this->primary_key => $pid])->delete($this->table_akses_name);
				$this->db->where([$this->primary_key => $pid])->delete($this->table_akses_role_name);
				$this->db->where([$this->primary_key => $pid])->delete($this->table_name);
				$this->db->trans_complete();
				$deleted = $this->db->trans_status();
				if ($deleted == false) {
					if (!empty($err))
						$err .= ", ";
					$err .= $pid;
				}
			}

			$data = array();
			if (empty($err)) {
				$data['status'] = TRUE;
			} else {
				$data['status'] = FALSE;
				$data['err'] = '<br/>ID : ' . $err;
			}

			return $data;
		} else
			return null;
	}

	public function add_data($post)
	{ 
		$cekdata = $this->db->query("select * from approval_matrix_role where role_name = '".trim($post['role_name'])."' and work_location_id = '".trim($post['location'])."' ")->result(); 
		if(empty($cekdata)){
				$data = [
				'role_name' 		=> trim($post['role_name']),
				'work_location_id' 	=> trim($post['location']),
				'description' 		=> trim($post['description']),
				'created_date' 		=> date("Y-m-d H:i:s"),
				'created_by' 		=> $_SESSION["username"]
			];
			
			$rs = $this->db->insert($this->table_name, $data);
			$lastId = $this->db->insert_id();

			if($rs){
				if(isset($post['pic_role'])){
					$item_num = count($post['pic_role']); // cek sum
					$item_len_min = min(array_keys($post['pic_role'])); // cek min key index
					$item_len = max(array_keys($post['pic_role'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						if(isset($post['pic_role'][$i])){
							$itemData = [
								'approval_matrix_role_id'	=> $lastId,
								'employee_id' 				=> trim($post['pic_role'][$i])
							];

							$this->db->insert('approval_matrix_role_pic', $itemData);
						}
					}
				}

				return $rs;
			}else return null;
		}else{
			echo 'Gagal submit. Role name dengan lokasi tersebut sudah ada'; 
		}

	}

	public function edit_data($post)
	{
		if (!empty($post['id'])) { 
			$data = [
				'role_name' 		=> trim($post['role_name']),
				'work_location_id' 	=> trim($post['location']),
				'description' 		=> trim($post['description']),
				'updated_date' 		=> date("Y-m-d H:i:s"),
				'updated_by' 		=> $_SESSION["username"]
			];

			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

			if($rs){
				if(isset($post['pic_role'])){
					$item_num = count($post['pic_role']); // cek sum
					$item_len_min = min(array_keys($post['pic_role'])); // cek min key index
					$item_len = max(array_keys($post['pic_role'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						$hdnid = trim($post['hdnid'][$i]);

						if(!empty($hdnid)){ //update
							if(isset($post['pic_role'][$i])){
								$itemData = [
									'employee_id'	=> trim($post['pic_role'][$i])
								];

								$this->db->update("approval_matrix_role_pic", $itemData, "id = '".$hdnid."'");
							}
						}else{ //insert
							if(isset($post['pic_role'][$i])){
								$itemData = [
									'approval_matrix_role_id'	=> $post['id'],
									'employee_id' 				=> trim($post['pic_role'][$i]),
								];

								$this->db->insert('approval_matrix_role_pic', $itemData);
							}
						}
					}
				}

				return $rs;

			}else return null;


			return $rs;
		} else{ 
			return null;
		}
	}

	public function getRowData($id)
	{
		$mTable = '(select a.*, b.name as work_location_name from approval_matrix_role a
					left join master_work_location b on b.id = a.work_location_id
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
				'title' => $v["B"],
				'link_type' => $v["C"],
				'module_name' => $v["D"],
				'url' => $v["E"],
				'parent_id' => $v["F"],
				'is_parent' => $v["G"],
				'show_menu' => $v["H"],
				'um_class' => $v["I"],
				'um_order' => $v["J"],
				'insert_by' => $_SESSION["username"]
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs)
				$error .= ",baris " . $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$sql = "select a.*, b.name as work_location_name from approval_matrix_role a
					left join master_work_location b on b.id = a.work_location_id
					order by a.id asc
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getNewPicRoleRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getPicRoleRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			$msPic = $this->db->query("select id,full_name from employees where status_id = 1 order by full_name asc")->result(); 
			
			$data 	.= '<td>'.$no.'</td>';

			$data 	.= '<td>'.$this->return_build_chosenme($msPic,'','','','pic_role['.$row.']','pic_role','pic_role','','id','full_name','','','',' data-id="'.$row.'" ').'</td>';

			$hdnid='';
			/*$data 	.= '<td><input type="button" class="btn btn-md btn-danger ibtnDel" onclick="del(\''.$row.'\',\''.$hdnid.'\')" value="Delete"><input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value=""/></td>';*/

			$data 	.= '<td><input type="button" class="btn btn-md btn-danger ibtnDel" onclick="del(this,\''.$hdnid.'\')" value="Delete"><input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value=""/></td>';
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getPicRoleRows($id,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select a.*, b.full_name from approval_matrix_role_pic a 
								left join employees b on b.id = a.employee_id
								where a.approval_matrix_role_id = '".$id."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				$msPic = $this->db->query("select id,full_name from employees where status_id = 1 order by full_name asc")->result();
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'</td>';

					$dt .= '<td>'.$this->return_build_chosenme($msPic,'',isset($f->employee_id)?$f->employee_id:1,'','pic_role['.$row.']','pic_role','pic_role','','id','full_name','','','',' data-id="'.$row.'" ').'</td>';

					/*$dt .= '<td><input type="button" class="btn btn-md btn-danger ibtnDel" id="btndel" value="Delete" onclick="del(\''.$row.'\',\''.$f->id.'\')"><input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';*/

					$dt .= '<td><input type="button" class="btn btn-md btn-danger ibtnDel" id="btndel" value="Delete" onclick="del(this,\''.$f->id.'\')"><input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';

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


	public function getDataRole($location){ 

		$rs = $this->db->query("select distinct(role_name),id from approval_matrix_role where work_location_id = '".$location."' ")->result(); 

		$data['msrole'] = $rs;


		return $data;

	}


}