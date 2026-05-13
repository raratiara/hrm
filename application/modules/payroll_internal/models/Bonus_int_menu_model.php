<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus_int_menu_model extends MY_Model
{
	protected $folder_name = "payroll_internal/bonus_int_menu";
	protected $table_name = _PREFIX_TABLE."bonus_internal";
	protected $detail_table_name = _PREFIX_TABLE."bonus_internal_detail";
	protected $detail_foreign_key = "bonus_internal_id";
	protected $amount_field = "bonus_amount";
	protected $total_header_field = "total_bonus";
	protected $primary_key = "id";
	protected $employee_source = "internal";

	function __construct()
	{
		parent::__construct();
	}

	private function moneyVal($value)
	{
		$value = trim((string)$value);
		if(preg_match('/^\d+\.\d{1,2}$/', $value)) {
			return (float) $value;
		}

		return (float) str_replace(['.', ','], '', $value);
	}

	private function getTotalNominalFromPost($post)
	{
		$total = 0;
		if(empty($post['nominal_amount']) || !is_array($post['nominal_amount'])) return $total;

		foreach($post['nominal_amount'] as $value) {
			$total += $this->moneyVal($value);
		}

		return $total;
	}

	public function get_list_data()
	{
		$aColumns = [
			NULL,
			NULL,
			'dt.id',
			'dt.month_name',
			'dt.periode_tahun',
			'dt.periode',
			'dt.total_nominal'
		];

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.name_indo as month_name,
					concat(b.name_indo, " ", a.periode_tahun) as periode,
					coalesce(sum(d.'.$this->amount_field.'), 0) as total_nominal
					from '.$this->table_name.' a
					left join master_month b on b.id = a.periode_bulan
					left join '.$this->detail_table_name.' d on d.'.$this->detail_foreign_key.' = a.id
					group by a.id
			)dt';

		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
			$sLimit = "LIMIT ".($_GET['iDisplayStart']).", ".($_GET['iDisplayLength']);
		}

		$sOrder = "";
		if(isset($_GET['iSortCol_0'])) {
			$sOrder = "ORDER BY  ";
			for ($i=0 ; $i<intval($_GET['iSortingCols']) ; $i++){
				if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
					$srcCol = $aColumns[intval($_GET['iSortCol_'.$i])];
					if(strpos($srcCol, ' as ') !== false) {
						$pieces = explode(' as ', trim($srcCol));
						$sOrder .= trim($pieces[0])." ".$_GET['sSortDir_'.$i].", ";
					} else {
						$sOrder .= $srcCol." ".$_GET['sSortDir_'.$i].", ";
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
					$sWhere .= $c." LIKE '%".$this->db->escape_like_str($_GET['sSearch'])."%' OR ";
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		$filtered_cols = array_filter($aColumns, [$this, 'is_not_null']);
		$sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $filtered_cols))."
			FROM $sTable $sWhere $sOrder $sLimit";
		$rResult = $this->db->query($sQuery)->result();

		$iFilteredTotal = $this->db->query("SELECT FOUND_ROWS() AS filter_total")->row()->filter_total;
		$iTotal = $this->db->query("SELECT COUNT(".$sIndexColumn.") AS total FROM $sTable")->row()->total;

		$output = [
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => []
		];

		foreach($rResult as $row) {
			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1") {
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color:#112D80;border-color:#112D80;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}

			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1") {
				$edit = '<a class="btn btn-xs btn-primary" style="background-color:#FFA500;border-color:#FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}

			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1") {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				$delete = '<a class="btn btn-xs btn-danger" style="background-color:#A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}

			$output["aaData"][] = [
				$delete_bulk,
				'<div class="action-buttons">'.$detail.$edit.$delete.'</div>',
				$row->id,
				$row->periode,
				number_format((float)$row->total_nominal, 0, ',', '.')
			];
		}

		echo json_encode($output);
	}

	public function is_not_null($val)
	{
		return !is_null($val);
	}

	public function delete($id = "")
	{
		if (empty($id)) return null;

		$this->db->trans_start();
		$this->db->where($this->detail_foreign_key, $id)->delete($this->detail_table_name);
		$this->db->where([$this->primary_key => $id])->delete($this->table_name);
		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function bulk($id = "")
	{
		if (!is_array($id) || !count($id)) return null;

		$err = '';
		foreach ($id as $pid) {
			$deleted = $this->delete($pid);
			if ($deleted == false) {
				if(!empty($err)) $err .= ", ";
				$err .= $pid;
			}
		}

		return empty($err) ? ['status' => TRUE] : ['status' => FALSE, 'err' => '<br/>ID : '.$err];
	}

	private function validateHeader($post)
	{
		if(empty($post['periode_bulan']) || empty($post['periode_tahun'])){
			return false;
		}

		return preg_match('/^\d{4}$/', trim($post['periode_tahun'])) === 1;
	}

	private function saveDetails($headerId, $post)
	{
		$this->db->where($this->detail_foreign_key, $headerId)->delete($this->detail_table_name);

		if(empty($post['hdnempid']) || !is_array($post['hdnempid'])) return true;

		$items = [];
		foreach($post['hdnempid'] as $row => $employeeId) {
			$nominal = isset($post['nominal_amount'][$row]) ? $this->moneyVal($post['nominal_amount'][$row]) : 0;
			$items[] = [
				$this->detail_foreign_key => $headerId,
				'employee_id' => trim($employeeId),
				$this->amount_field => $nominal,
				'note' => isset($post['detail_note'][$row]) ? trim($post['detail_note'][$row]) : ''
			];
		}

		if(!empty($items)) {
			$this->db->insert_batch($this->detail_table_name, $items);
		}

		return true;
	}

	public function add_data($post)
	{
		if(!$this->validateHeader($post)) {
			return [
				'status' => false,
				'msg' => 'Periode bulan dan tahun wajib diisi dengan benar'
			];
		}

		$exists = $this->db->where([
			'periode_bulan' => trim($post['periode_bulan']),
			'periode_tahun' => trim($post['periode_tahun'])
		])->get($this->table_name)->row();

		if($exists) {
			return [
				'status' => false,
				'msg' => 'Data periode ini sudah ada. Silakan edit data yang sudah tersimpan.'
			];
		}

		$this->db->trans_start();
		$data = [
			'periode_bulan' => trim($post['periode_bulan']),
			'periode_tahun' => trim($post['periode_tahun']),
			'notes' => trim($post['notes']),
			'created_at' => date('Y-m-d H:i:s'),
			'created_by' => $_SESSION['worker']
		];
		if($this->db->field_exists($this->total_header_field, $this->table_name)) {
			$data[$this->total_header_field] = $this->getTotalNominalFromPost($post);
		}

		$this->db->insert($this->table_name, $data);
		$headerId = $this->db->insert_id();
		$this->saveDetails($headerId, $post);
		$this->db->trans_complete();

		return [
			'status' => $this->db->trans_status(),
			'msg' => $this->db->trans_status() ? 'Data berhasil disimpan' : 'Data gagal disimpan',
			'id' => $headerId
		];
	}

	public function edit_data($post)
	{
		if(empty($post['id']) || !$this->validateHeader($post)) return false;

		$this->db->trans_start();
		$data = [
			'periode_bulan' => trim($post['periode_bulan']),
			'periode_tahun' => trim($post['periode_tahun']),
			'notes' => trim($post['notes']),
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $_SESSION['worker']
		];
		if($this->db->field_exists($this->total_header_field, $this->table_name)) {
			$data[$this->total_header_field] = $this->getTotalNominalFromPost($post);
		}

		$this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
		$this->saveDetails(trim($post['id']), $post);
		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function getRowData($id)
	{
		$mTable = '(select a.*, b.name_indo as month_name,
					concat(b.name_indo, " ", a.periode_tahun) as periode
					from '.$this->table_name.' a
					left join master_month b on b.id = a.periode_bulan
			)dt';

		return $this->db->where($this->primary_key, $id)->get($mTable)->row();
	}

	public function import_data($list_data)
	{
		return '';
	}

	public function eksport_data()
	{
		$sql = "select a.*, b.name_indo as month_name,
				coalesce(sum(d.".$this->amount_field."), 0) as total_nominal
				from ".$this->table_name." a
				left join master_month b on b.id = a.periode_bulan
				left join ".$this->detail_table_name." d on d.".$this->detail_foreign_key." = a.id
				group by a.id
				order by a.periode_tahun desc, a.periode_bulan desc";

		return $this->db->query($sql)->result_array();
	}

	public function getNewBonusThrRows($id = 0, $view = FALSE)
	{
		if($id > 0) return $this->getBonusThrRows($id, $view);

		return $this->getEmployeeRows();
	}

	private function getEmployeeRows()
	{
		$rows = $this->db->query("select id as employee_id, emp_code, full_name
				from employees
				where emp_source = ?
				and status_id = 1
				and IFNULL(is_special_payroll,0) != 1
				order by full_name asc", [$this->employee_source])->result();

		return $this->buildRows($rows, FALSE);
	}

	private function getBonusThrRows($id, $view)
	{
		$rows = $this->db->query("select a.*, b.emp_code, b.full_name, b.id as employee_id
				from ".$this->detail_table_name." a
				left join ".$this->table_name." c on c.id = a.".$this->detail_foreign_key."
				left join employees b on b.id = a.employee_id
				where a.".$this->detail_foreign_key." = ?
				order by b.full_name asc", [$id])->result();

		return $this->buildRows($rows, $view);
	}

	private function buildRows($rows, $view)
	{
		$dt = '';
		$row = 0;

		if(empty($rows)) {
			return ['<tr><td colspan="4" class="center">Data karyawan tidak ditemukan</td></tr>', 0];
		}

		foreach($rows as $f) {
			$nominal = isset($f->{$this->amount_field}) ? $f->{$this->amount_field} : 0;
			$note = isset($f->note) ? $f->note : '';

			$dt .= '<tr>';
			$dt .= '<td>'.$f->emp_code.'</td>';
			$dt .= '<td>'.$f->full_name.'<input type="hidden" name="hdnempid['.$row.']" value="'.$f->employee_id.'"/></td>';

			if($view) {
				$dt .= '<td class="right">'.number_format((float)$nominal, 0, ',', '.').'</td>';
				$dt .= '<td>'.htmlspecialchars($note).'</td>';
			} else {
				$dt .= '<td>'.$this->return_build_txt(number_format((float)$nominal, 0, ',', '.'), 'nominal_amount['.$row.']', '', 'nominal_amount', 'text-align:right;', 'data-id="'.$row.'" onkeyup="setNominalTotal()"').'</td>';
				$dt .= '<td>'.$this->return_build_txt($note, 'detail_note['.$row.']', '', 'detail_note', '', 'data-id="'.$row.'"').'</td>';
			}

			$dt .= '</tr>';
			$row++;
		}

		return [$dt, $row];
	}
}
