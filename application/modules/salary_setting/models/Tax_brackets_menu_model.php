<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tax_brackets_menu_model extends MY_Model
{
	protected $folder_name	= "salary_setting/tax_brackets_menu";
	protected $table_name 	= "tax_brackets";
	protected $primary_key 	= "id";

	function __construct() { parent::__construct(); }

	public function get_list_data()
	{
		$aColumns = [NULL, NULL, 'dt.id', 'dt.min_income', 'dt.max_income', 'dt.rate', 'dt.effective_year'];

		$sIndexColumn = $this->primary_key;
		$sTable = '(SELECT * FROM tax_brackets ORDER BY min_income ASC)dt';

		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1')
			$sLimit = "LIMIT ".($_GET['iDisplayStart']).", ".($_GET['iDisplayLength']);

		$sOrder = "";
		if(isset($_GET['iSortCol_0'])) {
			$sOrder = "ORDER BY  ";
			for ($i=0 ; $i<intval($_GET['iSortingCols']) ; $i++){
				if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
					$srcCol = $aColumns[ intval($_GET['iSortCol_'.$i])];
					$findme = ' as '; $pos = strpos($srcCol, $findme);
					if ($pos !== false) { $pieces = explode($findme, trim($srcCol)); $sOrder .= trim($pieces[0])." ".($_GET['sSortDir_'.$i]) .", "; }
					else { $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]." ".($_GET['sSortDir_'.$i]) .", "; }
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
			$sWhere = substr_replace($sWhere, "", -3); $sWhere .= ')';
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
				$delete_bulk, '<div class="action-buttons">'.$detail.' '.$edit.' '.$delete.'</div>',
				$row->id, number_format($row->min_income, 0, ',', '.'), number_format($row->max_income, 0, ',', '.'), $row->rate, $row->effective_year
			));
		}
		echo json_encode($output);
	}

	public function is_not_null($val){ return !is_null($val); }

	public function delete($id= "") {
		if (isset($id) && $id <> "") { $this->db->trans_start(); $this->db->where([$this->primary_key => $id])->delete($this->table_name); $this->db->trans_complete(); return $this->db->trans_status(); }
		else return null;
	}

	public function bulk($id= "") {
		if (is_array($id) && count($id)) {
			$err = '';
			foreach ($id as $pid) { $this->db->trans_start(); $this->db->where([$this->primary_key => $pid])->delete($this->table_name); $this->db->trans_complete(); if ($this->db->trans_status() == false) { if(!empty($err)) $err .= ", "; $err .= $pid; } }
			$data = array();
			if(empty($err)) $data['status'] = TRUE; else { $data['status'] = FALSE; $data['err'] = '<br/>ID : '.$err; }
			return $data;
		} else return null;
	}

	public function add_data($post) {
		if(!empty($post['min_income']) || $post['min_income'] === '0'){
			$data = [
				'min_income' => str_replace(['.', ','], ['', '.'], trim($post['min_income'])),
				'max_income' => str_replace(['.', ','], ['', '.'], trim($post['max_income'] ?? '0')),
				'rate' => trim($post['rate'] ?? '0'),
				'effective_year' => trim($post['effective_year'] ?? ''),
				'created_at' => date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert($this->table_name, $data);
			return $rs ? ["status" => true, "msg" => "Data berhasil disimpan"] : ["status" => false, "msg" => "Data gagal disimpan"];
		} else return ["status" => false, "msg" => "Min Income harus diisi"];
	}

	public function edit_data($post) {
		if(!empty($post['id'])){
			$data = [
				'min_income' => str_replace(['.', ','], ['', '.'], trim($post['min_income'])),
				'max_income' => str_replace(['.', ','], ['', '.'], trim($post['max_income'] ?? '0')),
				'rate' => trim($post['rate'] ?? '0'),
				'effective_year' => trim($post['effective_year'] ?? ''),
				'updated_at' => date("Y-m-d H:i:s")
			];
			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
			return $rs ? ["status" => true, "msg" => "Data berhasil disimpan"] : ["status" => false, "msg" => "Data gagal disimpan"];
		} else return ["status" => false, "msg" => "ID tidak ditemukan"];
	}

	public function getRowData($id) { return $this->db->where([$this->primary_key => $id])->get('(SELECT * FROM tax_brackets)dt')->row(); }

	public function import_data($list_data) {
		$error = '';
		foreach ($list_data as $k => $v) {
			$data = ['min_income' => $v["B"], 'max_income' => $v["C"], 'rate' => $v["D"], 'effective_year' => $v["E"], 'created_at' => date("Y-m-d H:i:s")];
			if (!$this->db->insert($this->table_name, $data)) $error .=",baris ". $v["A"];
		}
		return $error;
	}

	public function eksport_data() {
		return $this->db->query("SELECT * FROM tax_brackets ORDER BY min_income ASC")->result_array();
	}
}
