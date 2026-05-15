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
	protected $approval_matrix_type_id = 14;

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
			'dt.total_nominal',
			'dt.status_name',
			'dt.status_id',
			'dt.created_by',
			'dt.current_approval_level',
			'dt.is_approver',
			'dt.is_approver_view'
		];

		$karyawan_id = $_SESSION['worker'];
		$whr = '';
		if($_SESSION['role'] != 1){
			$whr = ' and (ao.created_by = "'.$karyawan_id.'" or ao.direct_id = "'.$karyawan_id.'" or ao.is_approver_view = 1) ';
		}

		$sIndexColumn = $this->primary_key;
		$sTable = '(select ao.* from (select a.*, b.name_indo as month_name,
					concat(b.name_indo, " ", a.periode_tahun) as periode,
					coalesce(a.'.$this->total_header_field.', 0) as total_nominal,
					coalesce(st.name, "Waiting Approval") as status_name,
					creator.direct_id,
					max(ap.current_approval_level) as current_approval_level,
					GROUP_CONCAT(amp.employee_id) as all_employeeid_approver,
					max(
						IF(
							cur_role.role_name = "Direct",
							creator.direct_id,
							(
								SELECT GROUP_CONCAT(employee_id)
								FROM approval_matrix_role_pic
								WHERE approval_matrix_role_id = cur_detail.role_id
							)
						)
					) as current_employeeid_approver,
					CASE WHEN FIND_IN_SET('.$karyawan_id.', GROUP_CONCAT(amp.employee_id)) > 0 THEN 1 ELSE 0 END as is_approver_view,
					CASE
						WHEN FIND_IN_SET(
							'.$karyawan_id.',
							(
								SELECT GROUP_CONCAT(employee_id)
								FROM approval_matrix_role_pic
								WHERE approval_matrix_role_id = max(cur_detail.role_id)
							)
						) > 0 THEN 1
						WHEN max(cur_role.role_name) = "Direct" AND max(creator.direct_id) = '.$karyawan_id.' THEN 1
						ELSE 0
					END as is_approver
					from '.$this->table_name.' a
					left join master_month b on b.id = a.periode_bulan
					left join master_status_cashadvance st on st.id = a.status_id
					left join employees creator on creator.id = a.created_by
					left join approval_path ap on ap.trx_id = a.id and ap.approval_matrix_type_id = '.$this->approval_matrix_type_id.'
					left join approval_matrix am on am.id = ap.approval_matrix_id
					left join approval_matrix_detail amd on amd.approval_matrix_id = am.id
					left join approval_matrix_role_pic amp on amp.approval_matrix_role_id = amd.role_id
					left join approval_matrix_detail cur_detail on cur_detail.approval_matrix_id = ap.approval_matrix_id and cur_detail.approval_level = ap.current_approval_level
					left join approval_matrix_role cur_role on cur_role.id = cur_detail.role_id
					group by a.id
			)ao where 1=1 '.$whr.'
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
				if($row->status_id != 2) {
					$edit = '<a class="btn btn-xs btn-primary" style="background-color:#FFA500;border-color:#FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
				}
			}

			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1") {
				if($row->status_id != 2) {
					$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
					$delete = '<a class="btn btn-xs btn-danger" style="background-color:#A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
				}
			}

			$output["aaData"][] = [
				$delete_bulk,
				'<div class="action-buttons">'.$detail.$edit.$delete.'</div>',
				$row->id,
				$row->periode,
				number_format((float)$row->total_nominal, 0, ',', '.'),
				$row->status_name
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
		if($this->db->field_exists('status_id', $this->table_name)) {
			$data['status_id'] = 1;
		}
		if($this->db->field_exists($this->total_header_field, $this->table_name)) {
			$data[$this->total_header_field] = $this->getTotalNominalFromPost($post);
		}

		$this->db->insert($this->table_name, $data);
		$headerId = $this->db->insert_id();
		$this->saveDetails($headerId, $post);
		$this->createApprovalPath($headerId, $this->getTotalNominalFromPost($post));
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
		if(isset($post['action_type']) && $post['action_type'] == 'approval') {
			$approval = $this->approve($post['id']);
			return isset($approval['status']) ? $approval['status'] : false;
		}

		$this->db->trans_start();
		$current = $this->db->where($this->primary_key, trim($post['id']))->get($this->table_name)->row();
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
		if($current && isset($current->status_id) && $current->status_id == 4) {
			$data['status_id'] = 1;
			$data['rfu_reason'] = '';
			$this->resetApprovalPath(trim($post['id']));
		}

		$this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
		$this->saveDetails(trim($post['id']), $post);
		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function getRowData($id)
	{
		$karyawan_id = $_SESSION['worker'];
		$mTable = '(select a.*, b.name_indo as month_name,
					concat(b.name_indo, " ", a.periode_tahun) as periode,
					coalesce(st.name, "Waiting Approval") as status_name,
					creator.direct_id,
					max(ap.current_approval_level) as current_approval_level,
					CASE
						WHEN FIND_IN_SET(
							'.$karyawan_id.',
							(
								SELECT GROUP_CONCAT(employee_id)
								FROM approval_matrix_role_pic
								WHERE approval_matrix_role_id = max(cur_detail.role_id)
							)
						) > 0 THEN 1
						WHEN max(cur_role.role_name) = "Direct" AND max(creator.direct_id) = '.$karyawan_id.' THEN 1
						ELSE 0
					END as is_approver
					from '.$this->table_name.' a
					left join master_month b on b.id = a.periode_bulan
					left join master_status_cashadvance st on st.id = a.status_id
					left join employees creator on creator.id = a.created_by
					left join approval_path ap on ap.trx_id = a.id and ap.approval_matrix_type_id = '.$this->approval_matrix_type_id.'
					left join approval_matrix_detail cur_detail on cur_detail.approval_matrix_id = ap.approval_matrix_id and cur_detail.approval_level = ap.current_approval_level
					left join approval_matrix_role cur_role on cur_role.id = cur_detail.role_id
					group by a.id
			)dt';

		return $this->db->where($this->primary_key, $id)->get($mTable)->row();
	}

	private function createApprovalPath($trx_id, $amount)
	{
		$employee = $this->db->query("select work_location from employees where id = ?", [$_SESSION['worker']])->row();
		$work_location_id = $employee ? $employee->work_location : '';
		if($work_location_id == '') return false;

		$matrix = $this->findApprovalMatrix($work_location_id, $amount);
		if(!$matrix) return false;

		$this->db->insert('approval_path', [
			'approval_matrix_type_id' => $this->approval_matrix_type_id,
			'trx_id' => $trx_id,
			'approval_matrix_id' => $matrix->id,
			'current_approval_level' => 1
		]);
		$approval_path_id = $this->db->insert_id();
		$this->db->insert('approval_path_detail', [
			'approval_path_id' => $approval_path_id,
			'approval_level' => 1
		]);

		return true;
	}

	private function findApprovalMatrix($work_location_id, $amount)
	{
		$amount = (float) $amount;
		$matrix = $this->db->query("select * from approval_matrix where approval_type_id = ? and work_location_id = ? and (
				(? >= min and ? <= max and min != '' and max != '') or
				(? >= min and min != '' and (max = '' or max is null)) or
				(? <= max and max != '' and (min = '' or min is null))
			) limit 1", [$this->approval_matrix_type_id, $work_location_id, $amount, $amount, $amount, $amount])->row();
		if($matrix) return $matrix;

		return $this->db->query("select * from approval_matrix where approval_type_id = ? and work_location_id = ? and ((min is null or min = '') and (max is null or max = '')) limit 1", [$this->approval_matrix_type_id, $work_location_id])->row();
	}

	private function getApprovalPath($trx_id)
	{
		return $this->db->where([
			'approval_matrix_type_id' => $this->approval_matrix_type_id,
			'trx_id' => $trx_id
		])->get('approval_path')->row();
	}

	private function getCurrApproval($trx_id, $approval_level)
	{
		return $this->db->query("select b.* from approval_path a
			left join approval_path_detail b on b.approval_path_id = a.id and b.approval_level = ?
			where a.approval_matrix_type_id = ? and a.trx_id = ?", [$approval_level, $this->approval_matrix_type_id, $trx_id])->row();
	}

	private function getMaxApproval($approval_matrix_id)
	{
		$row = $this->db->query("select max(approval_level) as approval_level from approval_matrix_detail where approval_matrix_id = ?", [$approval_matrix_id])->row();
		return $row ? (int)$row->approval_level : 0;
	}

	private function resetApprovalPath($trx_id)
	{
		$path = $this->getApprovalPath($trx_id);
		if(!$path) return false;

		$this->db->update('approval_path', ['current_approval_level' => 1], ['id' => $path->id]);
		$this->db->where('approval_path_id', $path->id)->where('approval_level !=', 1)->delete('approval_path_detail');
		$this->db->update('approval_path_detail', [
			'status' => '',
			'approval_by' => '',
			'approval_date' => ''
		], ['approval_path_id' => $path->id, 'approval_level' => 1]);

		return true;
	}

	public function approve($id)
	{
		$path = $this->getApprovalPath($id);
		if(!$path) return ['status' => false, 'msg' => 'Approval path tidak ditemukan'];

		$approval_level = (int)$path->current_approval_level;
		$current = $this->getCurrApproval($id, $approval_level);
		if(!$current) return ['status' => false, 'msg' => 'Approver tidak ditemukan'];

		$maxApproval = $this->getMaxApproval($path->approval_matrix_id);
		$now = date('Y-m-d H:i:s');
		$worker = $_SESSION['worker'];

		$this->db->trans_start();
		$this->db->update('approval_path_detail', [
			'status' => 'Approved',
			'approval_by' => $worker,
			'approval_date' => $now
		], ['id' => $current->id]);

		if($approval_level >= $maxApproval) {
			$this->db->update($this->table_name, [
				'status_id' => 2,
				'approval_date' => $now
			], [$this->primary_key => $id]);
		} else {
			$nextLevel = $approval_level + 1;
			$this->db->update('approval_path', ['current_approval_level' => $nextLevel], ['id' => $path->id]);
			$this->db->insert('approval_path_detail', [
				'approval_path_id' => $path->id,
				'approval_level' => $nextLevel
			]);
		}
		$this->db->trans_complete();

		return [
			'status' => $this->db->trans_status(),
			'msg' => $this->db->trans_status() ? 'Data berhasil disetujui' : 'Data gagal disetujui'
		];
	}

	public function rfu($id, $reason, $approval_level)
	{
		$current = $this->getCurrApproval($id, $approval_level);
		$this->db->trans_start();
		$this->db->update($this->table_name, [
			'status_id' => 4,
			'rfu_reason' => $reason,
			'approval_date' => date('Y-m-d H:i:s')
		], [$this->primary_key => $id]);
		if($current) {
			$this->db->update('approval_path_detail', [
				'status' => 'Request for Update',
				'approval_by' => $_SESSION['worker'],
				'approval_date' => date('Y-m-d H:i:s')
			], ['id' => $current->id]);
		}
		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function reject($id, $reason, $approval_level)
	{
		$current = $this->getCurrApproval($id, $approval_level);
		$this->db->trans_start();
		$this->db->update($this->table_name, [
			'status_id' => 3,
			'reject_reason' => $reason,
			'approval_date' => date('Y-m-d H:i:s')
		], [$this->primary_key => $id]);
		if($current) {
			$this->db->update('approval_path_detail', [
				'status' => 'Rejected',
				'approval_by' => $_SESSION['worker'],
				'approval_date' => date('Y-m-d H:i:s')
			], ['id' => $current->id]);
		}
		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function getApprovalLogRows($id)
	{
		return $this->db->query("select a.*, c.approval_level, d.role_name, e.id as id_detail,
				(case when e.id != '' and (e.status = '' or e.status is null) and a.current_approval_level = c.approval_level then 'Waiting Approval'
					when e.id != '' and e.status != '' then e.status
					else ''
				end) as status_name,
				IF(e.id != '' and e.status != '', (select full_name from employees where id = e.approval_by), d.role_name) as approver_name,
				e.approval_date
			from approval_path a
			left join approval_matrix b on b.id = a.approval_matrix_id
			left join approval_matrix_detail c on c.approval_matrix_id = b.id
			left join approval_matrix_role d on d.id = c.role_id
			left join approval_path_detail e on e.approval_path_id = a.id and e.approval_level = c.approval_level
			where a.approval_matrix_type_id = ? and a.trx_id = ?
			order by c.approval_level asc", [$this->approval_matrix_type_id, $id])->result();
	}

	public function import_data($list_data)
	{
		return '';
	}

	public function eksport_data()
	{
		$sql = "select a.*, b.name_indo as month_name,
				coalesce(a.".$this->total_header_field.", 0) as total_nominal
				from ".$this->table_name." a
				left join master_month b on b.id = a.periode_bulan
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
