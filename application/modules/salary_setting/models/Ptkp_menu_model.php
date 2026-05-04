<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ptkp_menu_model extends MY_Model
{
	protected $folder_name	= "salary_setting/ptkp_menu";
	protected $table_name 	= "tax_ptkp";
	protected $primary_key 	= "id";

	function __construct() { parent::__construct(); }

	public function get_marital_status_options()
	{
		$data = $this->db->order_by('id','ASC')->get('master_marital_status')->result();
		$options = '<select name="marital_status_id" class="form-control">';
		$options .= '<option value="">-- Select --</option>';
		foreach($data as $row){
			$options .= '<option value="'.$row->id.'">'.$row->name.'</option>';
		}
		$options .= '</select>';
		return $options;
	}

	public function get_list_data()
	{
		$aColumns = [NULL, NULL, 'dt.id', 'dt.status_code', 'dt.description', 'dt.amount', 'dt.effective_year', 'dt.marital_status_name'];

		$sIndexColumn = $this->primary_key;
		$sTable = '(SELECT tp.*, IFNULL(ms.name,"-") as marital_status_name FROM tax_ptkp tp LEFT JOIN master_marital_status ms ON ms.id = tp.marital_status_id ORDER BY tp.id ASC)dt';

		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
			$sLimit = "LIMIT ".($_GET['iDisplayStart']).", ".($_GET['iDisplayLength']);
		}

		$sOrder = "";
		if(isset($_GET['iSortCol_0'])) {
			$sOrder = "ORDER BY  ";
			for ($i=0 ; $i<intval($_GET['iSortingCols']) ; $i++){
				if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
					$srcCol = $aColumns[ intval($_GET['iSortCol_'.$i])];
					$findme = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sOrder .= trim($pieces[0])." ".($_GET['sSortDir_'.$i]) .", ";
					} else {
						$sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]." ".($_GET['sSortDir_'.$i]) .", ";
					}
				}
			}
			$sOrder = substr_replace($sOrder, "", -2);
			if($sOrder == "ORDER BY") $sOrder = "";
		}

		$sWhere = " WHERE 1 = 1 ";
		if(isset($_GET['sSearch']) && $_GET['sSearch'] != ""){
			$sWhere .= "AND (";
			foreach ($aColumns as $c) {
				if($c !== NULL){
					$srcCol = $c; $findme = ' as '; $pos = strpos($srcCol, $findme);
					if ($pos !== false) { $pieces = explode($findme, trim($srcCol)); $sWhere .= trim($pieces[0])." LIKE '%".($_GET['sSearch'])."%' OR "; }
					else { $sWhere .= $c." LIKE '%".($_GET['sSearch'])."%' OR "; }
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		$filtered_cols = array_filter($aColumns, [$this, 'is_not_null']);
		$sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $filtered_cols))." FROM $sTable $sWhere $sOrder $sLimit";
		$rResult = $this->db->query($sQuery)->result();

		$iFilteredTotal = $this->db->query("SELECT FOUND_ROWS() AS filter_total")->row()->filter_total;
		$iTotal = $this->db->query("SELECT COUNT(".$sIndexColumn.") AS total FROM $sTable")->row()->total;

		$output = array("sEcho" => intval($_GET['sEcho']), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());

		foreach($rResult as $row) {
			$detail = ""; $edit = ""; $delete_bulk = ""; $delete = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1") $detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #112D80; border-color: #112D80;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			if (_USER_ACCESS_LEVEL_UPDATE == "1") $edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			if (_USER_ACCESS_LEVEL_DELETE == "1") {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}
			array_push($output["aaData"], array(
				$delete_bulk,
				'<div class="action-buttons">'.$detail.' '.$edit.' '.$delete.'</div>',
				$row->id, $row->status_code, $row->description, number_format($row->amount, 0, ',', '.'), $row->effective_year, $row->marital_status_name
			));
		}
		echo json_encode($output);
	}

	public function is_not_null($val){ return !is_null($val); }

	public function delete($id= "") {
		if (isset($id) && $id <> "") {
			$this->db->trans_start();
			$this->db->where([$this->primary_key => $id])->delete($this->table_name);
			$this->db->trans_complete();
			return $this->db->trans_status();
		} else return null;
	}

	public function bulk($id= "") {
		if (is_array($id) && count($id)) {
			$err = '';
			foreach ($id as $pid) {
				$this->db->trans_start();
				$this->db->where([$this->primary_key => $pid])->delete($this->table_name);
				$this->db->trans_complete();
				if ($this->db->trans_status() == false) { if(!empty($err)) $err .= ", "; $err .= $pid; }
			}
			$data = array();
			if(empty($err)) $data['status'] = TRUE;
			else { $data['status'] = FALSE; $data['err'] = '<br/>ID : '.$err; }
			return $data;
		} else return null;
	}

	public function add_data($post) {
		if(!empty($post['status_code'])){
			$data = [
				'status_code' 		=> trim($post['status_code']),
				'description' 		=> trim($post['description'] ?? ''),
				'amount' 			=> str_replace(['.', ','], ['', '.'], trim($post['amount'] ?? '0')),
				'effective_year' 	=> trim($post['effective_year'] ?? ''),
				'marital_status_id' => trim($post['marital_status_id'] ?? ''),
				'created_at' 		=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert($this->table_name, $data);
			return $rs ? ["status" => true, "msg" => "Data berhasil disimpan"] : ["status" => false, "msg" => "Data gagal disimpan"];
		} else return ["status" => false, "msg" => "Status Code harus diisi"];
	}

	public function edit_data($post) {
		if(!empty($post['id'])){
			$data = [
				'status_code' 		=> trim($post['status_code']),
				'description' 		=> trim($post['description'] ?? ''),
				'amount' 			=> str_replace(['.', ','], ['', '.'], trim($post['amount'] ?? '0')),
				'effective_year' 	=> trim($post['effective_year'] ?? ''),
				'marital_status_id' => trim($post['marital_status_id'] ?? ''),
				'updated_at' 		=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
			return $rs ? ["status" => true, "msg" => "Data berhasil disimpan"] : ["status" => false, "msg" => "Data gagal disimpan"];
		} else return ["status" => false, "msg" => "ID tidak ditemukan"];
	}

	public function getRowData($id) {
		return $this->db->where(['tp.id' => $id])->from('tax_ptkp tp')->join('master_marital_status ms','ms.id = tp.marital_status_id','left')->select('tp.*, IFNULL(ms.name,"-") as marital_status_name')->get()->row();
	}

	public function import_data($list_data) {
		$error = '';
		foreach ($list_data as $k => $v) {
			$data = [
				'status_code' 		=> $v["B"],
				'description' 		=> $v["C"],
				'amount' 			=> $v["D"],
				'effective_year' 	=> $v["E"],
				'marital_status_id' => $v["F"]
			];
			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}
		return $error;
	}

	public function eksport_data() {
		$sql = "SELECT tp.*, IFNULL(ms.name,'-') as marital_status_name
			FROM tax_ptkp tp 
			LEFT JOIN master_marital_status ms ON ms.id = tp.marital_status_id 
			ORDER BY tp.id ASC";
		return $this->db->query($sql)->result_array();
	}
}
