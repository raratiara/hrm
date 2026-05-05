<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bpjs_configuration_menu_model extends MY_Model
{
	protected $folder_name				= "salary_setting/bpjs_configuration_menu";
	protected $table_name 				= "salary_bpjs";
	protected $primary_key 				= "id";

	function __construct()
	{
		parent::__construct();
	}

	public function get_list_data()
	{
		$aColumns = [
			NULL,
			NULL,
			'dt.id',
			'dt.bpjs_type',
			'dt.employee_percentage',
			'dt.employer_percentage',
			'dt.salary_cap',
			'dt.tax_ded'
		];

		$sIndexColumn = $this->primary_key;
		$sTable = '(SELECT * FROM salary_bpjs ORDER BY id ASC)dt';

		/* Paging */
		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
			$sLimit = "LIMIT ".($_GET['iDisplayStart']).", ".($_GET['iDisplayLength']);
		}

		/* Ordering */
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

		/* Filtering */
		$sWhere = " WHERE 1 = 1 ";
		if(isset($_GET['sSearch']) && $_GET['sSearch'] != ""){
			$sWhere .= "AND (";
			foreach ($aColumns as $c) {
				if($c !== NULL){
					$srcCol = $c;
					$findme = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0])." LIKE '%".($_GET['sSearch'])."%' OR ";
					} else {
						$sWhere .= $c." LIKE '%".($_GET['sSearch'])."%' OR ";
					}
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
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

		$sQuery = "SELECT FOUND_ROWS() AS filter_total";
		$aResultFilterTotal = $this->db->query($sQuery)->row();
		$iFilteredTotal = $aResultFilterTotal->filter_total;

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
			if (_USER_ACCESS_LEVEL_DETAIL == "1") {
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #112D80; border-color: #112D80;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1") {
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1") {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">'.$detail.' '.$edit.' '.$delete.'</div>',
				$row->id,
				$row->bpjs_type,
				$row->employee_percentage,
				$row->employer_percentage,
				$row->salary_cap ? number_format((float)$row->salary_cap, 0, ',', '.') : '-',
				$row->tax_ded ? $row->tax_ded : '-'
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
				if ($this->db->trans_status() == false) {
					if(!empty($err)) $err .= ", ";
					$err .= $pid;
				}
			}
			$data = array();
			if(empty($err)){ $data['status'] = TRUE; }
			else { $data['status'] = FALSE; $data['err'] = '<br/>ID : '.$err; }
			return $data;
		} else return null;
	}

	public function add_data($post) {
		if(!empty($post['bpjs_type'])){
			$data = [
				'bpjs_type' 			=> trim($post['bpjs_type']),
				'employee_percentage' 	=> trim($post['employee_percentage'] ?? '0'),
				'employer_percentage' 	=> trim($post['employer_percentage'] ?? '0'),
				'salary_cap' 			=> trim($post['salary_cap'] ?? ''),
				'tax_ded' 				=> trim($post['tax_ded'] ?? ''),
				'category' 				=> trim($post['category'] ?? '')
			];
			$rs = $this->db->insert($this->table_name, $data);
			if($rs) return ["status" => true, "msg" => "Data berhasil disimpan"];
			else return ["status" => false, "msg" => "Data gagal disimpan"];
		} else {
			return ["status" => false, "msg" => "BPJS Type harus diisi"];
		}
	}

	public function edit_data($post) {
		if(!empty($post['id'])){
			$data = [
				'bpjs_type' 			=> trim($post['bpjs_type']),
				'employee_percentage' 	=> trim($post['employee_percentage'] ?? '0'),
				'employer_percentage' 	=> trim($post['employer_percentage'] ?? '0'),
				'salary_cap' 			=> trim($post['salary_cap'] ?? ''),
				'tax_ded' 				=> trim($post['tax_ded'] ?? ''),
				'category' 				=> trim($post['category'] ?? '')
			];
			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
			if($rs) return ["status" => true, "msg" => "Data berhasil disimpan"];
			else return ["status" => false, "msg" => "Data gagal disimpan"];
		} else {
			return ["status" => false, "msg" => "ID tidak ditemukan"];
		}
	}

	public function getRowData($id) {
		return $this->db->where([$this->primary_key => $id])->get('salary_bpjs')->row();
	}

	public function import_data($list_data)
	{
		$error = '';
		foreach ($list_data as $k => $v) {
			$data = [
				'name' 			=> $v["B"],
				'employer_rate' => $v["C"],
				'employee_rate' => $v["D"],
				'max_salary' 	=> $v["E"],
				'is_active' 	=> $v["F"]
			];
			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}
		return $error;
	}

	public function eksport_data()
	{
		$sql = "SELECT * FROM salary_bpjs ORDER BY id ASC";
		return $this->db->query($sql)->result_array();
	}
}
