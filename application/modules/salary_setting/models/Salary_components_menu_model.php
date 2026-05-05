<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_components_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "salary_setting/salary_components_menu";
 	protected $table_name 				= "salary_components";
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
			'dt.name',
			'dt.type',
			'dt.default_amount',
			'dt.is_fixed_name',
			'dt.is_active_name',
			'dt.order_num'
		];
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(SELECT *, 
			IF(is_fixed=1,"Yes","No") as is_fixed_name,
			IF(is_active=1,"Active","Not Active") as is_active_name
			FROM salary_components ORDER BY order_num ASC)dt';
		

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
		$filtered_cols = array_filter($aColumns, [$this, 'is_not_null']);
		$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $filtered_cols))."
		FROM $sTable
		$sWhere
		$sOrder
		$sLimit
		";
		$rResult = $this->db->query($sQuery)->result();

		/* Data set length after filtering */
		$sQuery = "SELECT FOUND_ROWS() AS filter_total";
		$aResultFilterTotal = $this->db->query($sQuery)->row();
		$iFilteredTotal = $aResultFilterTotal->filter_total;

		/* Total data set length */
		$sQuery = "SELECT COUNT(".$sIndexColumn.") AS total FROM $sTable";
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

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">'.$detail.' '.$edit.' '.$delete.'</div>',
				$row->id,
				$row->name,
				'<span class="label label-'.($row->type=='earning'?'success':'danger').'">'.$row->type.'</span>',
				number_format($row->default_amount, 0, ',', '.'),
				$row->is_fixed_name,
				$row->is_active_name,
				$row->order_num
			));
		}

		echo json_encode($output);
	}

	public function is_not_null($val){
		return !is_null($val);
	}		

	public function delete($id= "") {
		if (isset($id) && $id <> "") {
			$this->db->trans_start();
			$this->db->where([$this->primary_key => $id])->delete($this->table_name);
			$this->db->trans_complete();
			return $rs = $this->db->trans_status();
		} else return null;
	}  

	public function bulk($id= "") {
		if (is_array($id) && count($id)) {
			$err = '';
			foreach ($id as $pid) {
				$this->db->trans_start();
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
  		if(!empty($post['name'])){ 
  			// Generate code from name
  			$code = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', trim($post['name'])));
  			$code = trim($code, '_');

  			$data = [
				'name' 			=> trim($post['name']),
				'code'			=> $code,
				'type' 			=> trim($post['type']),
				'default_amount'=> str_replace(['.', ','], ['', '.'], trim($post['default_amount'] ?? '0')),
				'is_fixed' 		=> trim($post['is_fixed'] ?? '1'),
				'is_active' 	=> trim($post['is_active'] ?? '1'),
				'order_num' 	=> trim($post['sort_order'] ?? '0'),
				'description' 	=> trim($post['description'] ?? ''),
				'calculate_percentage' => trim($post['calculate_percentage'] ?? ''),
				'calculate_from' => trim($post['calculate_from'] ?? ''),
				'created_at'	=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert($this->table_name, $data);
			if($rs){
				return ["status" => true, "msg" => "Data berhasil disimpan"];
			}else{
				return ["status" => false, "msg" => "Data gagal disimpan"];
			}
  		}else{
  			return ["status" => false, "msg" => "Name harus diisi"];
  		}
	}  

	public function edit_data($post) { 
		if(!empty($post['id'])){ 
  			$data = [
				'name' 			=> trim($post['name']),
				'type' 			=> trim($post['type']),
				'default_amount'=> str_replace(['.', ','], ['', '.'], trim($post['default_amount'] ?? '0')),
				'is_fixed' 		=> trim($post['is_fixed'] ?? '1'),
				'is_active' 	=> trim($post['is_active'] ?? '1'),
				'order_num' 	=> trim($post['sort_order'] ?? '0'),
				'description' 	=> trim($post['description'] ?? ''),
				'calculate_percentage' => trim($post['calculate_percentage'] ?? ''),
				'calculate_from' => trim($post['calculate_from'] ?? ''),
				'updated_at'	=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
			if($rs){
				return ["status" => true, "msg" => "Data berhasil disimpan"];
			}else{
				return ["status" => false, "msg" => "Data gagal disimpan"];
			}
		} else{
			return ["status" => false, "msg" => "ID tidak ditemukan"];
		}
	}  

	public function getRowData($id) { 
		$mTable = '(SELECT * FROM salary_components)dt';
		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		return $rs;
	}



	public function import_data($list_data)
	{
		$error = '';
		$i = 0;
		foreach ($list_data as $k => $v) {
			$i += 1;
			$data = [
				'name' 		=> $v["B"],
				'type' 		=> $v["C"],
				'default_amount' => $v["D"],
				'is_fixed' 	=> $v["E"],
				'is_active' => $v["F"],
				'order_num'=> $v["G"]
			];
			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}
		return $error;
	}

	public function eksport_data()
	{
		$sql = "SELECT *, 
			IF(is_fixed=1,'Yes','No') as is_fixed_name,
			IF(is_active=1,'Active','Not Active') as is_active_name
			FROM salary_components ORDER BY order_num ASC";
		$res = $this->db->query($sql);
		return $res->result_array();
	}

}
