<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pph21_adjustment_menu_model extends MY_Model
{
	protected $folder_name = "payroll_internal/pph21_adjustment_menu";
	protected $table_name = "spt_pph21_adjustment";
	protected $primary_key = "id";

	function __construct()
	{
		parent::__construct();
		$this->ensureAdjustmentTable();
	}

	private function ensureAdjustmentTable()
	{
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `spt_pph21_adjustment` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`spt_pph21_id` INT(11) NOT NULL,
				`spt_pph21_detail_id` INT(11) NOT NULL,
				`employee_id` INT(11) NOT NULL,
				`tahun_pajak` VARCHAR(10) NOT NULL,
				`type` VARCHAR(20) NOT NULL,
				`amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
				`kurang_lebih_bayar` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
				`status` VARCHAR(20) NOT NULL DEFAULT 'pending',
				`proses_ke_bulan_penggajian` INT(2) NOT NULL DEFAULT 1,
				`proses_ke_tahun_penggajian` VARCHAR(10) NOT NULL,
				`created_at` DATETIME DEFAULT NULL,
				`created_by` INT(11) DEFAULT NULL,
				`updated_at` DATETIME DEFAULT NULL,
				`updated_by` INT(11) DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `uniq_spt_adjustment_detail` (`spt_pph21_detail_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
		");
		$this->db->query("ALTER TABLE `spt_pph21_adjustment` ADD COLUMN IF NOT EXISTS `spt_pph21_id` INT(11) NULL AFTER `id`, ADD COLUMN IF NOT EXISTS `spt_pph21_detail_id` INT(11) NULL AFTER `spt_pph21_id`, ADD COLUMN IF NOT EXISTS `kurang_lebih_bayar` DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER `amount`, ADD COLUMN IF NOT EXISTS `created_at` DATETIME DEFAULT NULL, ADD COLUMN IF NOT EXISTS `created_by` INT(11) DEFAULT NULL, ADD COLUMN IF NOT EXISTS `updated_at` DATETIME DEFAULT NULL, ADD COLUMN IF NOT EXISTS `updated_by` INT(11) DEFAULT NULL");
		$this->db->query("ALTER TABLE `spt_pph21_adjustment` CHANGE COLUMN IF EXISTS `tax_year` `tahun_pajak` VARCHAR(10) NULL");
		$this->db->query("ALTER TABLE `spt_pph21_adjustment` DROP COLUMN IF EXISTS `source_type`");
		$this->db->query("DROP INDEX IF EXISTS `uniq_spt_adjustment_source_detail` ON `spt_pph21_adjustment`");
		$this->db->query("CREATE UNIQUE INDEX IF NOT EXISTS `uniq_spt_adjustment_detail` ON `spt_pph21_adjustment` (`spt_pph21_detail_id`)");
	}

	public function get_list_data()
	{
		$aColumns = [
			NULL,
			NULL,
			'dt.id',
			'dt.tahun_pajak',
			'dt.emp_code',
			'dt.full_name',
			'dt.type',
			'dt.amount',
			'dt.status',
			'dt.periode_proses'
		];

		$sIndexColumn = $this->primary_key;
		$sTable = "(select a.*, e.emp_code, e.full_name, m.name_indo as month_name,
					concat(m.name_indo, ' ', a.proses_ke_tahun_penggajian) as periode_proses
				from spt_pph21_adjustment a
				left join employees e on e.id = a.employee_id
				left join master_month m on m.id = a.proses_ke_bulan_penggajian)dt";

		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
			$sLimit = "LIMIT ".($_GET['iDisplayStart']).", ".$_GET['iDisplayLength'];
		}

		$sOrder = "";
		if(isset($_GET['iSortCol_0'])) {
			$sOrder = "ORDER BY ";
			for ($i=0 ; $i<intval($_GET['iSortingCols']) ; $i++){
				if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
					$sOrder .= $aColumns[intval($_GET['iSortCol_'.$i])]." ".$_GET['sSortDir_'.$i].", ";
				}
			}
			$sOrder = substr_replace($sOrder, "", -2);
			if($sOrder == "ORDER BY") $sOrder = "";
		}

		$sWhere = " WHERE 1 = 1 ";
		if(isset($_GET['sSearch']) && $_GET['sSearch'] != ""){
			$sWhere .= "AND (";
			foreach ($aColumns as $c) {
				if($c !== NULL) $sWhere .= $c." LIKE '%".$_GET['sSearch']."%' OR ";
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		$filtered_cols = array_filter($aColumns, [$this, 'is_not_null']);
		$sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $filtered_cols))." FROM $sTable $sWhere $sOrder $sLimit";
		$rResult = $this->db->query($sQuery)->result();
		$iFilteredTotal = $this->db->query("SELECT FOUND_ROWS() AS filter_total")->row()->filter_total;
		$iTotal = $this->db->query("SELECT COUNT(".$sIndexColumn.") AS total FROM $sTable")->row()->total;

		$output = ["sEcho" => intval($_GET['sEcho']), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => []];

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

			$output["aaData"][] = [
				$delete_bulk,
				'<div class="action-buttons">'.$detail.' '.$edit.' '.$delete.'</div>',
				$row->id,
				$row->tahun_pajak,
				$row->emp_code,
				$row->full_name,
				$row->type == 'refund' ? 'Refund' : 'Kurang Bayar',
				number_format($row->amount, 0, ',', '.'),
				ucwords($row->status),
				$row->periode_proses
			];
		}

		echo json_encode($output);
	}

	public function is_not_null($val){ return !is_null($val); }

	public function add_data($post)
	{
		return ["status" => false, "msg" => "Adjustment dibuat otomatis saat SPT difinalkan"];
	}

	public function edit_data($post)
	{
		if(empty($post['id'])){
			return ["status" => false, "msg" => "ID tidak ditemukan"];
		}

		$item = [
			'proses_ke_bulan_penggajian' => (int)$post['proses_ke_bulan_penggajian'],
			'proses_ke_tahun_penggajian' => trim($post['proses_ke_tahun_penggajian']),
			'status' => trim($post['status']),
			'updated_at' => date("Y-m-d H:i:s"),
			'updated_by' => $_SESSION['worker']
		];

		$rs = $this->db->update($this->table_name, $item, [$this->primary_key => trim($post['id'])]);
		return $rs ? ["status" => true, "msg" => "Data berhasil disimpan"] : ["status" => false, "msg" => "Data gagal disimpan"];
	}

	public function delete($id= "")
	{
		if ($id == "") return null;
		$this->db->trans_start();
		$this->db->where([$this->primary_key => $id])->delete($this->table_name);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function bulk($id= "")
	{
		if (!is_array($id) || !count($id)) return null;
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
		return empty($err) ? ['status' => TRUE] : ['status' => FALSE, 'err' => '<br/>ID : '.$err];
	}

	public function getRowData($id)
	{
		$mTable = "(select a.*, e.emp_code, e.full_name, m.name_indo as month_name,
					concat(m.name_indo, ' ', a.proses_ke_tahun_penggajian) as periode_proses
				from spt_pph21_adjustment a
				left join employees e on e.id = a.employee_id
				left join master_month m on m.id = a.proses_ke_bulan_penggajian)dt";

		return $this->db->where([$this->primary_key => $id])->get($mTable)->row();
	}

	public function import_data($list_data){ return ''; }

	public function eksport_data()
	{
		return $this->db->query("select * from spt_pph21_adjustment order by id asc")->result_array();
	}
}
