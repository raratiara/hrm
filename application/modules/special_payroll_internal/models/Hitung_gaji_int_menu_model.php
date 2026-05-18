<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_gaji_int_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "special_payroll_internal/hitung_gaji_int_menu";
 	protected $table_name 				= _PREFIX_TABLE."special_payroll_slip_internal";
 	protected $primary_key 				= "id";
	protected $approval_matrix_type_id 	= 20;
	protected $payment_history_table 	= _PREFIX_TABLE."special_payroll_paid_history_internal";

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
			'dt.month_name',
			'dt.tahun_penggajian',
			'dt.status_payroll',
			'dt.status',
			'dt.status_id',
			'dt.created_by',
			'dt.current_approval_level',
			'dt.is_approver',
			'dt.is_approver_view'
		];

		/*$where_project = "";
			if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
			$where_project = " and a.project_id = '".$_GET['flproject']."' ";
		}*/

		

		$sIndexColumn = $this->primary_key;
		


		$karyawan_id = $_SESSION['worker'];
		$whr = '';
		if($_SESSION['role'] != 1){
			$whr = ' and (ao.created_by = "'.$karyawan_id.'" or ao.direct_id = "'.$karyawan_id.'" or ao.is_approver_view = 1) ';
		}

		$sTable = '(select ao.* from (select a.*, b.name_indo as month_name,
					case when a.status_id = 0 then "Draft" when a.status_id = 2 then coalesce(c.name, "Menunggu Pembayaran") else coalesce(st.name, "Waiting Approval") end as status_payroll,
					creator.direct_id,
					max(ap.current_approval_level) as current_approval_level,
					GROUP_CONCAT(amp.employee_id) as all_employeeid_approver,
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
					from special_payroll_slip_internal a
					left join master_month b on b.id = a.bulan_penggajian
					left join master_payroll_status c on c.id = a.status
					left join master_status_cashadvance st on st.id = a.status_id
					left join employees creator on creator.id = a.created_by
					left join approval_path ap on ap.trx_id = a.id and ap.approval_matrix_type_id = '.$this->approval_matrix_type_id.'
					left join approval_matrix am on am.id = ap.approval_matrix_id
					left join approval_matrix_detail amd on amd.approval_matrix_id = am.id
					left join approval_matrix_role_pic amp on amp.approval_matrix_role_id = amd.role_id
					left join approval_matrix_detail cur_detail on cur_detail.approval_matrix_id = ap.approval_matrix_id and cur_detail.approval_level = ap.current_approval_level
					left join approval_matrix_role cur_role on cur_role.id = cur_detail.role_id
					where 1=1
					group by a.id
			)ao where 1=1 '.$whr.'
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
				
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #112D80; border-color: #112D80;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1" && $row->status != 2 && $row->status_id != 2)  {
				
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1" && $row->status != 2 && $row->status_id != 2)  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}

			
			$print_gaji="";
			if($row->status == 2){ //terbayar
				$print_gaji = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportGaji('."'".$row->id."'".')">
	                <i class="fa fa-download"></i>
	                Slip Gaji
	            </a>';
			}
			
			
            /*$print_lembur = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportLembur('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Lembur
            </a>';*/
            
            $print_absen = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportAbsen_gaji('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Rekap Absen
            </a>';
            $print_rekap_gaji = "";
            if($row->status == 2){ //terbayar
	            $print_rekap_gaji = '<a class="btn btn-default btn-xs" style="align:center" onclick="getRekapGaji('."'".$row->id."'".')">
	                <i class="fa fa-download"></i>
	                Rekap Gaji
	            </a>';
	        }


			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
				'.$print_gaji.'
				'.$print_absen.'
				'.$print_rekap_gaji.'
				'.$detail.'
				'.$edit.'
				'.$delete.'
				</div>',
				$row->id,
				$row->month_name,
				$row->tahun_penggajian,
				$row->status_payroll
				
			));


		}

		echo json_encode($output);
	}

	// filltering null value from array
	public function is_not_null($val){
		return !is_null($val);
	}		

	private function moneyVal($value)
	{
		$value = trim((string)$value);
		if(preg_match('/^\d+\.\d{1,2}$/', $value)) return (float)$value;
		return (float) str_replace(['.', ','], '', $value);
	}

	private function getPayrollApprovalTotal($payroll_slip_id)
	{
		$row = $this->db->query("select coalesce(sum(total_pendapatan), 0) as total_nominal
			from special_payroll_slip_detail_internal
			where payroll_slip_id = ?", [(int)$payroll_slip_id])->row();
		return $row ? (float)$row->total_nominal : 0;
	}

	private function createApprovalPath($trx_id, $amount)
	{
		$employee = $this->db->query("select work_location from employees where id = ?", [$_SESSION['worker']])->row();
		$work_location_id = $employee ? $employee->work_location : '';
		if($work_location_id == '') return false;

		$matrix = $this->findApprovalMatrix($work_location_id, $amount);
		if(!$matrix) return false;

		$path = $this->getApprovalPath($trx_id);
		if($path) {
			$this->resetApprovalPath($trx_id);
			return true;
		}

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

	private function deleteApprovalPath($trx_id)
	{
		$path = $this->getApprovalPath($trx_id);
		if(!$path) return false;

		$this->db->where('approval_path_id', $path->id)->delete('approval_path_detail');
		$this->db->where('id', $path->id)->delete('approval_path');

		return true;
	}

	private function ensurePaymentHistory($payroll_slip_id)
	{
		$existing = $this->db->where('payroll_slip_id', $payroll_slip_id)->get($this->payment_history_table)->row();
		if($existing) return true;

		return $this->db->insert($this->payment_history_table, [
			'payroll_slip_id' => $payroll_slip_id,
			'status' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'created_by' => $_SESSION['worker']
		]);
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
				'status' => 1,
				'approval_date' => $now
			], [$this->primary_key => $id]);
			$this->ensurePaymentHistory($id);
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
			'status' => null,
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
			'status' => null,
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

	public function delete($id= "") {
		if (isset($id) && $id <> "") {
			//$this->db->trans_off(); // Disable transaction
			$this->db->trans_start(); // set "True" for query will be rolled back
			$this->db->where([$this->primary_key => $id])->delete($this->table_name);
			$this->db->trans_complete();

			return $rs = $this->db->trans_status();
		} else return null;
	}  

	private function normalizeSalaryComponentName($name)
	{
		return strtolower(preg_replace('/[^a-z0-9]/i', '', (string)$name));
	}

	private function getBenefitDeductionComponentColumn()
	{
		if($this->db->field_exists('salary_components_id', 'employee_benefit_deduction')){
			return 'salary_components_id';
		}

		if($this->db->field_exists('salary_component_id', 'employee_benefit_deduction')){
			return 'salary_component_id';
		}

		if($this->db->field_exists('component_id', 'employee_benefit_deduction')){
			return 'component_id';
		}

		return 'salary_components_id';
	}

	private function getEmployeeBenefitDeductionAmounts($employee_ids)
	{
		$result = [];
		if(empty($employee_ids) || !$this->db->table_exists('employee_benefit_deduction')){
			return $result;
		}

		$employee_ids = array_values(array_unique(array_filter(array_map('intval', (array)$employee_ids))));
		if(empty($employee_ids)){
			return $result;
		}

		$componentColumn = $this->getBenefitDeductionComponentColumn();
		$amountColumn = $this->db->field_exists('amount', 'employee_benefit_deduction') ? 'amount' : '';
		if($amountColumn == ''){
			return $result;
		}

		// Fetch salary_components rows (where salary_bpjs_id is NULL)
		$rows = $this->db->query("select a.employee_id, a.".$amountColumn." as amount, b.code
					from employee_benefit_deduction a
					left join salary_components b on b.id = a.".$componentColumn."
					where a.employee_id in (".implode(',', $employee_ids).")
					AND (a.salary_bpjs_id IS NULL OR a.salary_bpjs_id = 0)")->result();

		foreach($rows as $row){
			if(empty($row->code)) continue;
			$result[$row->employee_id][$row->code] = $row->amount;
		}

		// Fetch BPJS rows (where salary_bpjs_id is set) - use salary_bpjs.code as key
		$bpjsRows = $this->db->query("select a.employee_id, a.".$amountColumn." as amount, b.code
					from employee_benefit_deduction a
					left join salary_bpjs b on b.id = a.salary_bpjs_id
					where a.employee_id in (".implode(',', $employee_ids).")
					AND a.salary_bpjs_id IS NOT NULL AND a.salary_bpjs_id > 0")->result();

		foreach($bpjsRows as $row){
			if(empty($row->code)) continue;
			$result[$row->employee_id][$row->code] = $row->amount;
		}

		return $result;
	}

	/**
	 * Ambil komponen salary yang punya calculate_percentage & calculate_from (non-fixed)
	 * Return: array [ code => ['percentage' => x, 'from' => 'gaji_bulanan'] ]
	 */
	private function getSalaryComponentsCalculation()
	{
		$result = [];
		$rows = $this->db->query("SELECT code, calculate_percentage, calculate_from 
			FROM salary_components 
			WHERE IFNULL(is_fixed,0) != 1 
			AND calculate_percentage IS NOT NULL AND calculate_percentage != '' 
			AND calculate_from IS NOT NULL AND calculate_from != ''")->result();

		foreach($rows as $row){
			$result[$row->code] = [
				'percentage' => (float)$row->calculate_percentage,
				'from' => $row->calculate_from
			];
		}
		return $result;
	}

	private function benefitValue($benefit, $field, $fallback = 0)
	{
		if(isset($benefit[$field]) && $benefit[$field] !== '' && $benefit[$field] != 0){
			return $benefit[$field];
		}

		return $fallback;
	}

	/**
	 * Ambil kode-kode BPJS dari tabel salary_bpjs, grouped by category
	 * Return: ['kesehatan' => ['bpjs_kesehatan'], 'ketenagakerjaan' => ['jht','jp','jkk','jkm']]
	 */
	private function getBpjsCodes()
	{
		static $cache = null;
		if($cache !== null) return $cache;

		$cache = ['kesehatan' => [], 'ketenagakerjaan' => []];
		if(!$this->db->table_exists('salary_bpjs')) return $cache;

		$rows = $this->db->query("SELECT code, category FROM salary_bpjs WHERE code IS NOT NULL AND code != ''")->result();
		foreach($rows as $row){
			$cat = strtolower(trim($row->category));
			if($cat == 'kesehatan'){
				$cache['kesehatan'][] = $row->code;
			} else {
				$cache['ketenagakerjaan'][] = $row->code;
			}
		}
		return $cache;
	}

	/**
	 * Hitung total BPJS Kesehatan dari benefit berdasarkan kode di salary_bpjs
	 */
	private function calcBpjsKesehatan($benefit)
	{
		$codes = $this->getBpjsCodes();
		$total = 0;
		foreach($codes['kesehatan'] as $code){
			$total += (float)$this->benefitValue($benefit, $code, 0);
		}
		return $total;
	}

	/**
	 * Hitung total BPJS Ketenagakerjaan dari benefit berdasarkan kode di salary_bpjs
	 */
	private function calcBpjsTk($benefit)
	{
		$codes = $this->getBpjsCodes();
		$total = 0;
		foreach($codes['ketenagakerjaan'] as $code){
			$total += (float)$this->benefitValue($benefit, $code, 0);
		}
		return $total;
	}

	/**
	 * Hitung detail per komponen BPJS TK (jht, jp, jkk, jkm)
	 * Return: ['bpjs_jht' => x, 'bpjs_jp' => x, 'bpjs_jkk' => x, 'bpjs_jkm' => x]
	 */
	private function calcBpjsTkDetail($benefit)
	{
		return [
			'bpjs_jht' => (float)$this->benefitValue($benefit, 'jht', 0),
			'bpjs_jp'  => (float)$this->benefitValue($benefit, 'jp', 0),
			'bpjs_jkk' => (float)$this->benefitValue($benefit, 'jkk', 0),
			'bpjs_jkm' => (float)$this->benefitValue($benefit, 'jkm', 0),
		];
	}

	/**
	 * Hitung PPh 21 bulanan menggunakan metode TER (Tarif Efektif Rata-rata)
	 * Alur: marital_status_id -> tax_ter_category_mapping (dapat category A/B/C)
	 *       -> tax_ter (cari rate berdasarkan category & range bruto)
	 *       -> PPh 21 = bruto * rate
	 */
	private function calcPph21Ter($bruto, $marital_status_id)
	{
		if($bruto <= 0 || empty($marital_status_id)) return 0;

		// Ambil category dari tax_ter_category_mapping berdasarkan marital_status_id
		$mapping = $this->db->query(
			"SELECT category FROM tax_ter_category_mapping WHERE marital_status_id = ? LIMIT 1",
			array((int)$marital_status_id)
		)->row();

		if(!$mapping || empty($mapping->category)) return 0;

		$category = $mapping->category;

		// Ambil rate dari tax_ter berdasarkan category dan range bruto
		$ter = $this->db->query(
			"SELECT rate FROM tax_ter WHERE category = ? AND min_bruto <= ? AND max_bruto > ? ORDER BY min_bruto DESC LIMIT 1",
			array($category, $bruto, $bruto)
		)->row();

		if(!$ter) return 0;

		$rate = (float)$ter->rate;
		$pph21 = ceil($bruto * $rate);

		return $pph21;
	}

	/**
	 * Hitung nilai komponen berdasarkan calculate_percentage & calculate_from
	 * Jika sudah ada amount di benefit deduction, pakai itu. Jika tidak, hitung dari percentage.
	 */
	private function calculateComponentValue($benefit, $code, $salaryCalc, $resolvedValues)
	{
		// Jika ada amount di benefit deduction, pakai itu
		if(isset($benefit[$code]) && $benefit[$code] !== '' && $benefit[$code] != 0){
			return (float)$benefit[$code];
		}

		// Jika ada rumus calculate di salary_components, hitung
		if(isset($salaryCalc[$code])){
			$percentage = $salaryCalc[$code]['percentage'];
			$fromCode = $salaryCalc[$code]['from'];
			$baseValue = isset($resolvedValues[$fromCode]) ? (float)$resolvedValues[$fromCode] : 0;
			if($baseValue > 0 && $percentage > 0){
				return ceil(($baseValue * $percentage) * 100) / 100;
			}
		}

		return 0;
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

	

	public function add_data($post)
	{
	    $this->load->helper('global');

	    if (empty($post['penggajian_month']) || empty($post['penggajian_year'])) {
	        return [
	            "status" => false,
	            "msg"    => "Bulan Tahun Penggajian harus diisi"
	        ];
	    }

	    $this->db->trans_start();

	    $bulan  = (int)$post['penggajian_month'];
	    $tahun  = trim($post['penggajian_year']);
	    $isSubmitFinal = isset($post['payroll_action']) && $post['payroll_action'] == 'submit_final';

	    // =========================
	    // Ambil kode bulan
	    // =========================
	    $codemonth = $this->db
	        ->select('code')
	        ->from('master_month')
	        ->where('id', $bulan)
	        ->get()
	        ->row();

	    if (!$codemonth) {
	        $this->db->trans_complete();
	        return [
	            "status" => false,
	            "msg"    => "Bulan Penggajian tidak valid"
	        ];
	    }

	    $periode_gaji = $tahun . '-' . $codemonth->code;

	    // =========================
	    // MAIN QUERY
	    // =========================
	    $this->db->select("
	        e.id as employee_id,
	        e.total_hari_kerja,
	        e.no_bpjs,
	        e.no_bpjs_ketenagakerjaan,
	        e.marital_status_id,

	        sd.total_masuk,
	        sd.total_ijin,
	        sd.total_cuti,
	        sd.total_alfa,
	        sd.total_lembur,
	        sd.total_jam_kerja,
	        sd.total_jam_lembur,

	        s.tgl_start_absen,
	        s.tgl_end_absen,

	        COALESCE(l.ttl_hutang,0) as hutang
	    ");

	    $this->db->from('employees e');
	    $this->db->join('special_summary_absen_internal_detail sd', 'sd.emp_id = e.id', 'left');
	    $this->db->join('special_summary_absen_internal s', 's.id = sd.summary_absen_internal_id', 'left');

	    $this->db->join("(SELECT b.id_employee,
	                        SUM(b.nominal_cicilan_per_bulan) as ttl_hutang
	                     FROM loan_detail a
	                     JOIN loan b ON b.id = a.loan_id
	                     WHERE DATE_FORMAT(a.tgl_jatuh_tempo,'%Y-%m') = '$periode_gaji'
	                     GROUP BY b.id_employee) l",
	                     "l.id_employee = e.id",
	                     "left");

	    $this->db->where('e.emp_source', 'internal');
	   	$this->db->where('e.is_special_payroll = 1', null, false);
	    $this->db->where('e.status_id', 1);
	    $this->db->where('s.bulan_penggajian', $bulan);
	    $this->db->where('s.tahun_penggajian', $tahun);

	    if ($post['is_all_employee'] == 'Karyawan' && !empty($post['employeeIds'])) {
	        $this->db->where_in('e.id', $post['employeeIds']);
	    }

	    $data = $this->db->get()->result();

	    if (empty($data)) {
	        $this->db->trans_complete();
	        return [
	            "status" => false,
	            "msg"    => "Data tidak ditemukan"
	        ];
	    }

	    $benefitAmounts = $this->getEmployeeBenefitDeductionAmounts(array_map(function ($row) {
	        return $row->employee_id;
	    }, $data));

	    $salaryCalc = $this->getSalaryComponentsCalculation();

	    // =========================
	    // BUAT 1 HEADER PAYROLL SAJA
	    // =========================
	    $header = $this->db->where([
	        'bulan_penggajian' => $bulan,
	        'tahun_penggajian' => $tahun
	    ])->get('special_payroll_slip_internal')->row();

	    if (!$header) {
	        $this->db->insert('special_payroll_slip_internal', [
	            'bulan_penggajian' => $bulan,
	            'tahun_penggajian' => $tahun,
	            'tgl_start_absen'  => $data[0]->tgl_start_absen,
	            'tgl_end_absen'    => $data[0]->tgl_end_absen,
	            'status' => null,
	            'status_id' => $isSubmitFinal ? 1 : 0,
	            'created_at' => date("Y-m-d H:i:s"),
	            'created_by' => $_SESSION['worker']
	        ]);
	        $payroll_slip_id = $this->db->insert_id();
	    } else {
	        $payroll_slip_id = $header->id;
	        $this->db->update('special_payroll_slip_internal', [
	        	'status' => null,
	        	'status_id' => $isSubmitFinal ? 1 : 0,
	        	'rfu_reason' => '',
	        	'reject_reason' => '',
	        	'updated_at' => date("Y-m-d H:i:s"),
	        	'updated_by' => $_SESSION['worker']
	        ], ['id' => $payroll_slip_id]);
	    }

	    $employeeIds = array_map(function ($row) {
	        return (int) $row->employee_id;
	    }, $data);

	    if (!empty($employeeIds)) {
	        $this->db
	            ->where('payroll_slip_id', $payroll_slip_id)
	            ->where_in('employee_id', $employeeIds)
	            ->delete('special_payroll_slip_detail_internal');
	    }

	    // =========================
	    // BUAT 1 HEADER BPJS SAJA
	    // =========================
	    $bpjs_header = $this->db->where([
	        'periode_gaji_bulan' => $bulan,
	        'periode_gaji_tahun' => $tahun
	    ])->get('special_history_bpjs_internal')->row();

	    if (!$bpjs_header) {
	        $this->db->insert("special_history_bpjs_internal", [
	            'periode_gaji_bulan' => $bulan,
	            'periode_gaji_tahun' => $tahun
	        ]);
	        $history_bpjs_id = $this->db->insert_id();
	    } else {
	        $history_bpjs_id = $bpjs_header->id;
	    }

	    $insertDetail = [];
	    $payrollComponents = $this->getPayrollComponentMap($bulan, $tahun);

	    foreach ($data as $row) {

	    	$benefit = isset($benefitAmounts[$row->employee_id]) ? $benefitAmounts[$row->employee_id] : [];

	        $gaji_bulanan = (float)$this->benefitValue($benefit, 'gaji_bulanan', 0);
	        $gaji_harian  = (float)$this->benefitValue($benefit, 'gaji_harian', 0);
	        if($gaji_harian == 0 && $gaji_bulanan > 0 && $row->total_hari_kerja > 0){
	            $gaji_harian = ceil($gaji_bulanan / $row->total_hari_kerja);
	        }
	        $tunjangan_jabatan = (float)$this->benefitValue($benefit, 'tunjangan_jabatan', 0);
	        $tunjangan_transport = (float)$this->benefitValue($benefit, 'tunjangan_transportasi', 0);
	        $tunjangan_konsumsi = (float)$this->benefitValue($benefit, 'tunjangan_konsumsi', 0);
	        $tunjangan_komunikasi = (float)$this->benefitValue($benefit, 'tunjangan_komunikasi', 0);

	        $total_tidak_masuk = (int)$row->total_hari_kerja - (int)$row->total_masuk;

	        $gaji = ceil($row->total_masuk * $gaji_harian);

	        $lembur_perjam  = ceil($gaji_bulanan / 173);
	        $lembur_total   = $row->total_lembur;

	        // Resolved values untuk basis perhitungan percentage
	        $resolvedValues = ['gaji_bulanan' => $gaji_bulanan, 'gaji_harian' => $gaji_harian];

	        // BPJS dari salary_bpjs (nama komponen dinamis dari tabel salary_bpjs)
	        $bpjs_kesehatan = $this->calcBpjsKesehatan($benefit);
	        $bpjs_tk_detail = $this->calcBpjsTkDetail($benefit);
	        $bpjs_tk        = array_sum($bpjs_tk_detail);
	        $seragam = (float)$this->benefitValue($benefit, 'seragam', 0);
	        $pelatihan = (float)$this->benefitValue($benefit, 'pelatihan', 0);
	        $lain_lain = (float)$this->benefitValue($benefit, 'lain_lain', 0);
	        $payroll = (float)$this->benefitValue($benefit, 'payroll', 0);

	        $sosial = (float)$this->benefitValue($benefit, 'sosial', 0);
	        $hutang = (float)$row->hutang;

	        $bonus = $payrollComponents['bonus']['has_data'] ? ($payrollComponents['bonus']['amounts'][(int)$row->employee_id] ?? 0) : 0;
	        $thr = $payrollComponents['thr']['has_data'] ? ($payrollComponents['thr']['amounts'][(int)$row->employee_id] ?? 0) : 0;

	        $total_pendapatan = ceil($gaji + $lembur_total + $tunjangan_jabatan + $tunjangan_transport + $tunjangan_konsumsi + $tunjangan_komunikasi + $bonus + $thr);

	        // Hitung PPh 21 bulanan dengan metode TER
	        $pph_21 = $this->calcPph21Ter($total_pendapatan, $row->marital_status_id);

	        $subtotal         = ceil($total_pendapatan - ($seragam + $pelatihan + $lain_lain + $hutang + $sosial));
	        $gaji_bersih      = ceil($subtotal - ($bpjs_kesehatan + $bpjs_tk + $payroll + $pph_21));

	        // =========================
	        // INSERT / UPDATE BPJS DETAIL
	        // =========================
	        $bpjs_detail = $this->db->where([
	            'history_bpjs_id' => $history_bpjs_id,
	            'employee_id'     => $row->employee_id
	        ])->get('special_history_bpjs_detail_internal')->row();

	        $data_bpjs_detail = [
	            'history_bpjs_id'       => $history_bpjs_id,
	            'employee_id'           => $row->employee_id,
	            'no_bpjs_kesehatan'     => $row->no_bpjs,
	            'no_bpjs_tk'            => $row->no_bpjs_ketenagakerjaan,
	            'nominal_bpjs_kesehatan'=> $bpjs_kesehatan,
	            'nominal_bpjs_tk'       => $bpjs_tk,
	            'tanggal_potong'        => date("Y-m-d H:i:s")
	        ];

	        if ($bpjs_detail) {
	            $this->db->update("special_history_bpjs_detail_internal", $data_bpjs_detail, ['id' => $bpjs_detail->id]);
	        } else {
	            $this->db->insert("special_history_bpjs_detail_internal", $data_bpjs_detail);
	        }

	        // =========================
	        // INSERT DETAIL PAYROLL
	        // =========================
	        $insertDetail[] = [
	            'payroll_slip_id'  => $payroll_slip_id,
	            'employee_id'      => $row->employee_id,
	            'total_hari_kerja' => $row->total_hari_kerja,
	            'total_masuk'      => $row->total_masuk,
	            'total_tidak_masuk'=> $total_tidak_masuk,
	            'total_ijin' 	   => $row->total_ijin,
	            'total_cuti' 	   => $row->total_cuti,
	            'total_alfa' 	   => $row->total_alfa,
	            'total_jam_kerja'  => $row->total_jam_kerja,
	            'total_jam_lembur' => $row->total_jam_lembur,
	            'created_at'       => date("Y-m-d H:i:s"),
	            'created_by'       => $_SESSION['worker'],
	            'gaji_bulanan'     => $gaji_bulanan,
	            'gaji_harian'      => $gaji_harian,
	            'gaji'             => $gaji,
	            'tunjangan_jabatan' => $tunjangan_jabatan,
	            'tunjangan_transport' => $tunjangan_transport,
	            'tunjangan_konsumsi' => $tunjangan_konsumsi,
	            'tunjangan_komunikasi' => $tunjangan_komunikasi,
	            'lembur_perjam'    => $lembur_perjam,
	            'total_nominal_lembur' => $lembur_total,
	            'bonus'            => $bonus,
	            'thr'              => $thr,
	            'total_pendapatan' => $total_pendapatan,
	            'sosial'           => $sosial,
	            'bpjs_kesehatan'   => $bpjs_kesehatan,
	            'bpjs_tk'          => $bpjs_tk,
	            'bpjs_jht'         => $bpjs_tk_detail['bpjs_jht'],
	            'bpjs_jp'          => $bpjs_tk_detail['bpjs_jp'],
	            'bpjs_jkk'         => $bpjs_tk_detail['bpjs_jkk'],
	            'bpjs_jkm'         => $bpjs_tk_detail['bpjs_jkm'],
	            'seragam'          => $seragam,
	            'pelatihan'        => $pelatihan,
	            'lain_lain'        => $lain_lain,
	            'hutang'           => $hutang,
	            'payroll'          => $payroll,
	            'pph_21'           => $pph_21,
	            'subtotal'         => $subtotal,
	            'gaji_bersih'      => $gaji_bersih
	        ];
	    }

	    if (!empty($insertDetail)) {
	        $this->db->insert_batch('special_payroll_slip_detail_internal', $insertDetail);
	    }

	    if($isSubmitFinal) {
	    	$this->createApprovalPath($payroll_slip_id, $this->getPayrollApprovalTotal($payroll_slip_id));
	    } else {
	    	$this->deleteApprovalPath($payroll_slip_id);
	    }

	    $this->db->trans_complete();

	    return [
	        "status" => $this->db->trans_status(),
	        "msg"    => $this->db->trans_status() ? "Data berhasil disimpan" : "Data gagal disimpan"
	    ];
	}



	
	public function edit_data($post)
	{
	    if (empty($post['id'])) {
	        return [
	            "status" => false,
	            "msg"    => "ID tidak ditemukan"
	        ];
	    }

	    if(isset($post['action_type']) && $post['action_type'] == 'approval') {
	    	return $this->approve($post['id']);
	    }
	    $isSubmitFinal = isset($post['payroll_action']) && $post['payroll_action'] == 'submit_final';

	    $this->db->trans_start();

	    $getperiod_start = date_create($post['period_start']);
	    $getperiod_end   = date_create($post['period_end']);

	    $period_start = date_format($getperiod_start, "Y-m-d");
	    $period_end   = date_format($getperiod_end, "Y-m-d");

	    if (
	        empty($post['penggajian_month']) ||
	        empty($post['penggajian_year']) ||
	        empty($period_start) ||
	        empty($period_end)
	    ) {
	        return [
	            "status" => false,
	            "msg"    => "Bulan Tahun Penggajian & Periode Absensi harus diisi"
	        ];
	    }

	    $bulan = trim($post['penggajian_month']);
	    $tahun = trim($post['penggajian_year']);

	    // =========================
	    // UPDATE HEADER PAYROLL
	    // =========================
	    $dataHeader = [
	        'bulan_penggajian' => $bulan,
	        'tahun_penggajian' => $tahun,
	        'tgl_start_absen'  => $period_start,
	        'tgl_end_absen'    => $period_end,
	        'status'           => null,
	        'status_id'        => $isSubmitFinal ? 1 : 0,
	        'rfu_reason'       => '',
	        'reject_reason'    => '',
	        'updated_at'       => date("Y-m-d H:i:s"),
	        'updated_by'       => $_SESSION['worker']
	    ];

	    $this->db->update("special_payroll_slip_internal", $dataHeader, ['id' => $post['id']]);

	    // =========================
	    // CEK / BUAT HEADER BPJS (NO PROJECT)
	    // =========================
	    $bpjs_header = $this->db->where([
	        'periode_gaji_bulan' => $bulan,
	        'periode_gaji_tahun' => $tahun
	    ])->get('special_history_bpjs_internal')->row();

	    if (!$bpjs_header) {
	        $this->db->insert("special_history_bpjs_internal", [
	            'periode_gaji_bulan' => $bulan,
	            'periode_gaji_tahun' => $tahun
	        ]);
	        $history_bpjs_id = $this->db->insert_id();
	    } else {
	        $history_bpjs_id = $bpjs_header->id;
	    }

	    // =========================
	    // LOOP DETAIL
	    // =========================
	    if (isset($post['hdnempid_gaji'])) {

	        $item_len_min = min(array_keys($post['hdnempid_gaji']));
	        $item_len     = max(array_keys($post['hdnempid_gaji']));

	        for ($i = $item_len_min; $i <= $item_len; $i++) {

	            $hdnid       = trim($post['hdnid_gaji'][$i] ?? '');
	            $employee_id = trim($post['hdnempid_gaji'][$i] ?? '');

	            if (empty($employee_id)) continue;

	            $itemData = [
	                'tunjangan_jabatan'     => trim($post['tunj_jabatan_gaji'][$i] ?? 0),
	                'tunjangan_transport'   => trim($post['tunj_transport_gaji'][$i] ?? 0),
	                'tunjangan_konsumsi'    => trim($post['tunj_konsumsi_gaji'][$i] ?? 0),
	                'tunjangan_komunikasi'  => trim($post['tunj_komunikasi_gaji'][$i] ?? 0),
	                'seragam'               => trim($post['seragam_gaji'][$i] ?? 0),
	                'pelatihan'             => trim($post['pelatihan_gaji'][$i] ?? 0),
	                'lain_lain'             => trim($post['lainlain_gaji'][$i] ?? 0),
	                'payroll'               => trim($post['payroll_gaji'][$i] ?? 0),
	                'pph_21'                => trim($post['pph21_gaji'][$i] ?? 0),
	                'total_jam_kerja'       => trim($post['jml_jam_kerja_gaji'][$i] ?? 0),
	                'total_masuk'           => trim($post['jml_hadir_gaji'][$i] ?? 0),
	                'total_tidak_masuk'     => trim($post['jml_tdkhadir_gaji'][$i] ?? 0),
	                'gaji_bulanan'          => trim($post['gaji_bulanan_gaji'][$i] ?? 0),
	                'gaji_harian'           => trim($post['gaji_harian_gaji'][$i] ?? 0),
	                'gaji'                  => trim($post['gaji_gaji'][$i] ?? 0),
	                'lembur_perjam'         => trim($post['lembur_perjam_gaji'][$i] ?? 0),
	                'total_nominal_lembur'  => trim($post['total_nominal_lembur_gaji'][$i] ?? 0),
	                'total_jam_lembur'      => trim($post['jam_lembur_gaji'][$i] ?? 0),
	                'bonus'                 => trim($post['bonus_gaji'][$i] ?? 0),
	                'thr'                   => trim($post['thr_gaji'][$i] ?? 0),
	                'total_pendapatan'      => trim($post['ttl_pendapatan_gaji'][$i] ?? 0),
	                'bpjs_kesehatan'        => trim($post['bpjs_kes_gaji'][$i] ?? 0),
	                'bpjs_tk'               => trim($post['bpjs_tk_gaji'][$i] ?? 0),
	                'bpjs_jht'              => trim($post['bpjs_jht_gaji'][$i] ?? 0),
	                'bpjs_jp'               => trim($post['bpjs_jp_gaji'][$i] ?? 0),
	                'bpjs_jkk'              => trim($post['bpjs_jkk_gaji'][$i] ?? 0),
	                'bpjs_jkm'              => trim($post['bpjs_jkm_gaji'][$i] ?? 0),
	                'hutang'                => trim($post['hutang_gaji'][$i] ?? 0),
	                'sosial'                => trim($post['sosial_gaji'][$i] ?? 0),
	                'subtotal'              => trim($post['subtotal_gaji'][$i] ?? 0),
	                'gaji_bersih'           => trim($post['gaji_bersih_gaji'][$i] ?? 0)
	            ];

	            if (!empty($hdnid)) {
	                $this->db->update("special_payroll_slip_detail_internal", $itemData, ['id' => $hdnid]);
	            } else {
	                $itemData['payroll_slip_id'] = $post['id'];
	                $itemData['employee_id']     = $employee_id;
	                $this->db->insert('special_payroll_slip_detail_internal', $itemData);
	            }

	            // =========================
	            // UPDATE / INSERT BPJS DETAIL
	            // =========================
	            $bpjs_detail = $this->db->where([
	                'history_bpjs_id' => $history_bpjs_id,
	                'employee_id'     => $employee_id
	            ])->get('special_history_bpjs_detail_internal')->row();

	            $data_bpjs_detail = [
	                'history_bpjs_id'        => $history_bpjs_id,
	                'employee_id'            => $employee_id,
	                'nominal_bpjs_kesehatan' => $itemData['bpjs_kesehatan'],
	                'nominal_bpjs_tk'        => $itemData['bpjs_tk'],
	                'tanggal_potong'         => date("Y-m-d H:i:s")
	            ];

	            if ($bpjs_detail) {
	                $this->db->update("special_history_bpjs_detail_internal", $data_bpjs_detail, ['id' => $bpjs_detail->id]);
	            } else {
	                $this->db->insert("special_history_bpjs_detail_internal", $data_bpjs_detail);
	            }
	        }
	    }

	    if($isSubmitFinal) {
	    	$this->createApprovalPath($post['id'], $this->getPayrollApprovalTotal($post['id']));
	    } else {
	    	$this->deleteApprovalPath($post['id']);
	    }

	    $this->db->trans_complete();

	    return [
	        "status" => $this->db->trans_status(),
	        "msg"    => $this->db->trans_status() ? "Data berhasil disimpan" : "Data gagal disimpan"
	    ];
	} 


	public function getRowData($id) { 

		$karyawan_id = $_SESSION['worker'];
		$mTable = "(select a.*, b.name_indo as month_name,
					case when a.status_id = 0 then 'Draft' when a.status_id = 2 then coalesce(ps.name, 'Menunggu Pembayaran') else coalesce(st.name, 'Waiting Approval') end as status_name,
					max(ap.current_approval_level) as current_approval_level,
					CASE
						WHEN FIND_IN_SET(
							".$karyawan_id.",
							(
								SELECT GROUP_CONCAT(employee_id)
								FROM approval_matrix_role_pic
								WHERE approval_matrix_role_id = max(cur_detail.role_id)
							)
						) > 0 THEN 1
						WHEN max(cur_role.role_name) = 'Direct' AND max(creator.direct_id) = ".$karyawan_id." THEN 1
						ELSE 0
					END as is_approver
					from special_payroll_slip_internal a
					left join master_month b on b.id = a.bulan_penggajian
					left join master_status_cashadvance st on st.id = a.status_id
					left join master_payroll_status ps on ps.id = a.status
					left join employees creator on creator.id = a.created_by
					left join approval_path ap on ap.trx_id = a.id and ap.approval_matrix_type_id = ".$this->approval_matrix_type_id."
					left join approval_matrix_detail cur_detail on cur_detail.approval_matrix_id = ap.approval_matrix_id and cur_detail.approval_level = ap.current_approval_level
					left join approval_matrix_role cur_role on cur_role.id = cur_detail.role_id
					group by a.id
			)dt";

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		
		
		return $rs;
	} 

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'employee_id' 			=> $v["B"],
				'task' 					=> $v["C"],
				'progress_percentage' 	=> $v["D"],
				'parent_id' 			=> $v["E"],
				'due_date' 				=> $v["F"],
				'status_id' 			=> $v["G"],
				'solve_date' 			=> $v["H"]
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{

		$sql = "select a.*, b.name_indo as month_name
					from special_payroll_slip_internal a 
					left join master_month b on b.id = a.bulan_penggajian
					where 1=1 
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getSummaryAbsen($bln, $thn){ 

		$rs = $this->db->query("select * from  special_summary_absen_internal where bulan_penggajian = ".$bln." and tahun_penggajian = '".$thn."' limit 1")->result(); 

		

		return $rs;

	}

	public function getGaji($bln, $thn){ 

		$rs = $this->db->query("select a.* from special_payroll_slip_internal a
				where a.bulan_penggajian = ".$bln." and a.tahun_penggajian = '".$thn."' limit 1")->result(); 

		

		return $rs;

	}

	private function getPayrollComponentMap($bulan, $tahun)
	{
		$components = [
			'bonus' => ['has_data' => false, 'amounts' => []],
			'thr' => ['has_data' => false, 'amounts' => []]
		];

		$bonusHeader = $this->db->query("
			select id
			from special_bonus_internal
			where periode_bulan = ? and periode_tahun = ? and status_id = 2
			order by id desc
			limit 1
		", [(int)$bulan, (string)$tahun])->row();

		if ($bonusHeader) {
			$components['bonus']['has_data'] = true;
			$rows = $this->db->where('special_bonus_internal_id', $bonusHeader->id)->get('special_bonus_internal_detail')->result();
			foreach ($rows as $row) {
				$components['bonus']['amounts'][(int)$row->employee_id] = (float)$row->bonus_amount;
			}
		}

		$thrHeader = $this->db->query("
			select id
			from special_thr_internal
			where periode_bulan = ? and periode_tahun = ? and status_id = 2
			order by id desc
			limit 1
		", [(int)$bulan, (string)$tahun])->row();

		if ($thrHeader) {
			$components['thr']['has_data'] = true;
			$rows = $this->db->where('special_thr_internal_id', $thrHeader->id)->get('special_thr_internal_detail')->result();
			foreach ($rows as $row) {
				$components['thr']['amounts'][(int)$row->employee_id] = (float)$row->thr_amount;
			}
		}

		return $components;
	}



	public function getNewGajiRow($row,$id=0,$bln,$thn,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getGajiRows($id,$bln,$thn,$view);
		} else { 
			$data = '';
			$no = $row+1;

			$data 	.= '<td>No Data</td>';

			
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getGajiRows($id,$bln,$thn,$view,$print=FALSE){ 

		$dt = ''; 
		

		$rs = $this->db->query("select a.*, b.full_name, b.emp_code, b.id as employee_id, b.total_hari_kerja, b.marital_status_id, c.bulan_penggajian, c.tahun_penggajian
			from special_summary_absen_internal_detail a left join employees b on b.id = a.emp_id
			left join special_summary_absen_internal c on c.id = a.summary_absen_internal_id
			where b.emp_source = 'internal' and b.is_special_payroll = 1 and c.bulan_penggajian = ".$bln." and c.tahun_penggajian = '".$thn."' 
			order by b.full_name asc

		")->result();

		
		$rd = $rs;
		$benefitAmounts = $this->getEmployeeBenefitDeductionAmounts(array_map(function ($row) {
			return $row->employee_id;
		}, $rd));
		$salaryCalc = $this->getSalaryComponentsCalculation();
		$payrollComponents = $this->getPayrollComponentMap($bln, $thn);

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				$benefit = isset($benefitAmounts[$f->employee_id]) ? $benefitAmounts[$f->employee_id] : [];

				$dataSlip = $this->db->query("select a.id as employee_id, a.emp_code, a.full_name, b.*, c.id as payroll_id, c.status as status_payroll
				from employees a left join special_payroll_slip_detail_internal b on b.employee_id = a.id 
				left join special_payroll_slip_internal c on c.id = b.payroll_slip_id
				where a.emp_source = 'internal' and a.is_special_payroll = 1 and a.id = '".$f->emp_id."' and a.status_id = 1 
				and c.bulan_penggajian = ".$bln." and c.tahun_penggajian = '".$thn."' ")->result(); 

				$gaji_bulanan = (float)$this->benefitValue($benefit, 'gaji_bulanan', 0);
				$gaji_harian_benefit = (float)$this->benefitValue($benefit, 'gaji_harian', 0);
				if($gaji_harian_benefit == 0 && $gaji_bulanan > 0 && $f->total_hari_kerja > 0){
					$gaji_harian_benefit = ceil($gaji_bulanan / $f->total_hari_kerja);
				}

				if(!empty($dataSlip)){ /// ambil data slip
					$status_payroll = $dataSlip[0]->status_payroll;
					
					///informasi detail bpjs - dari salary_bpjs via benefit deduction
					$bpjs_tk_detail_view = $this->calcBpjsTkDetail($benefit);
					$bpjs_jht = $bpjs_tk_detail_view['bpjs_jht'];
					$bpjs_jp  = $bpjs_tk_detail_view['bpjs_jp'];
					$bpjs_jkk = $bpjs_tk_detail_view['bpjs_jkk'];
					$bpjs_jkm = $bpjs_tk_detail_view['bpjs_jkm'];


					$id = $dataSlip[0]->id;
					$payroll_id = $dataSlip[0]->payroll_id;
					$emp_code = $dataSlip[0]->emp_code;
					$full_name = $dataSlip[0]->full_name;
					$employee_id = $dataSlip[0]->employee_id;
					$total_jam_kerja = $dataSlip[0]->total_jam_kerja;
					$total_masuk = $dataSlip[0]->total_masuk;
					$total_tidak_masuk = $dataSlip[0]->total_tidak_masuk;
					$gaji_bulanan = $dataSlip[0]->gaji_bulanan;
					$gaji_harian = $dataSlip[0]->gaji_harian;
					$gaji = $dataSlip[0]->gaji;
					$tunjangan_jabatan = $dataSlip[0]->tunjangan_jabatan;
					$tunjangan_transport = $dataSlip[0]->tunjangan_transport;
					$tunjangan_konsumsi = $dataSlip[0]->tunjangan_konsumsi;
					$tunjangan_komunikasi = $dataSlip[0]->tunjangan_komunikasi;
					$lembur_perjam = $dataSlip[0]->lembur_perjam;
					$total_nominal_lembur = $dataSlip[0]->total_nominal_lembur;
					$total_jam_lembur = $dataSlip[0]->total_jam_lembur;
					$savedBonus = isset($dataSlip[0]->bonus) ? (float)$dataSlip[0]->bonus : 0;
					$savedThr = isset($dataSlip[0]->thr) ? (float)$dataSlip[0]->thr : 0;
					$bonus = $payrollComponents['bonus']['has_data'] ? ($payrollComponents['bonus']['amounts'][(int)$employee_id] ?? 0) : $savedBonus;
					$thr = $payrollComponents['thr']['has_data'] ? ($payrollComponents['thr']['amounts'][(int)$employee_id] ?? 0) : $savedThr;
					$total_pendapatan = $dataSlip[0]->total_pendapatan;
					$bpjs_kesehatan = $dataSlip[0]->bpjs_kesehatan;
					$bpjs_tk = $dataSlip[0]->bpjs_tk;
					/*$absen = $dataSlip[0]->absen;*/
					$seragam = $dataSlip[0]->seragam;
					$pelatihan = $dataSlip[0]->pelatihan;
					$lain_lain = $dataSlip[0]->lain_lain;
					$hutang = $dataSlip[0]->hutang;
					$sosial = $dataSlip[0]->sosial;
					$payroll = $dataSlip[0]->payroll;
					$pph_21 = isset($dataSlip[0]->pph_21) ? $dataSlip[0]->pph_21 : 0;
					$subtotal = $dataSlip[0]->subtotal;
					$gaji_bersih = $dataSlip[0]->gaji_bersih;
					$total_pendapatan = ceil(($gaji + $total_nominal_lembur + $tunjangan_jabatan + $tunjangan_transport + $tunjangan_konsumsi + $tunjangan_komunikasi + $bonus + $thr) * 100) / 100;
					$pph_21 = $this->calcPph21Ter($total_pendapatan, $f->marital_status_id);
					$subtotal = ceil(($total_pendapatan - ($seragam+$pelatihan+$lain_lain+$hutang+$sosial)) * 100) / 100;
					$gaji_bersih = ceil(($subtotal - ($bpjs_kesehatan+$bpjs_tk+$payroll+$pph_21)) * 100) / 100;

				}else{  /// ambil data dr summary absen
					$status_payroll="";
					
					$total_tidak_masuk = (int)$f->total_hari_kerja - (int)$f->total_masuk;

					$gaji = ceil(((int)$f->total_masuk * (float)$gaji_harian_benefit) * 100) / 100;
					
					$lembur_perjam = ($gaji_bulanan > 0) ? ceil(($gaji_bulanan / 173) * 100) / 100 : 0;
					
					$resolvedValues = ['gaji_bulanan' => $gaji_bulanan, 'gaji_harian' => $gaji_harian_benefit];
					// BPJS dari salary_bpjs (nama komponen dinamis dari tabel salary_bpjs)
					$bpjs_kesehatan = $this->calcBpjsKesehatan($benefit);
					$bpjs_tk_detail = $this->calcBpjsTkDetail($benefit);
					$bpjs_tk        = array_sum($bpjs_tk_detail);
					$tunjangan_jabatan = (float)$this->benefitValue($benefit, 'tunjangan_jabatan', 0);
					$tunjangan_transport = (float)$this->benefitValue($benefit, 'tunjangan_transportasi', 0);
					$tunjangan_konsumsi = (float)$this->benefitValue($benefit, 'tunjangan_konsumsi', 0);
					$tunjangan_komunikasi = (float)$this->benefitValue($benefit, 'tunjangan_komunikasi', 0);
					$seragam = (float)$this->benefitValue($benefit, 'seragam', 0);
					$pelatihan = (float)$this->benefitValue($benefit, 'pelatihan', 0);
					$lain_lain = (float)$this->benefitValue($benefit, 'lain_lain', 0);
					$payroll = (float)$this->benefitValue($benefit, 'payroll', 0);
					
					$sosial = (float)$this->benefitValue($benefit, 'sosial', 0);
					//ambil pinjaman yg masih berjalan
					$data_pinjaman = $this->db->query("select sum(nominal_cicilan_per_bulan) as ttt_hutang from loan where id_employee = '".$f->emp_id."' and status_id = 5")->result();
					$hutang=0;
					if(!empty($data_pinjaman)){
						$hutang = $data_pinjaman[0]->ttt_hutang;
					}

					/// ttl pendapatan - potongan tdk wajib
					//$subtotal = ceil(($gaji - ($potongan_absen+$hutang+$sosial)) * 100) / 100;
					$total_nominal_lembur = ceil((int)$lembur_perjam*(int)$f->total_jam_lembur);
					$bonus = $payrollComponents['bonus']['has_data'] ? ($payrollComponents['bonus']['amounts'][(int)$f->employee_id] ?? 0) : 0;
					$thr = $payrollComponents['thr']['has_data'] ? ($payrollComponents['thr']['amounts'][(int)$f->employee_id] ?? 0) : 0;
					$total_pendapatan = ceil(($gaji + $total_nominal_lembur + $tunjangan_jabatan + $tunjangan_transport + $tunjangan_konsumsi + $tunjangan_komunikasi + $bonus + $thr) * 100) / 100;

					// Hitung PPh 21 bulanan dengan metode TER
					$pph_21 = $this->calcPph21Ter($total_pendapatan, $f->marital_status_id);

					$subtotal = ceil(($total_pendapatan - ($seragam+$pelatihan+$lain_lain+$hutang+$sosial)) * 100) / 100;

					/// subtotal - potongan wajib
					$gaji_bersih = ceil(($subtotal - ($bpjs_kesehatan+$bpjs_tk+$payroll+$pph_21)) * 100) / 100;

					///informasi detail bpjs - dari salary_bpjs via benefit deduction
					$bpjs_jht = $bpjs_tk_detail['bpjs_jht'];
					$bpjs_jp  = $bpjs_tk_detail['bpjs_jp'];
					$bpjs_jkk = $bpjs_tk_detail['bpjs_jkk'];
					$bpjs_jkm = $bpjs_tk_detail['bpjs_jkm'];

		             
					$id = "";
					$payroll_id = "";
					$emp_code = $f->emp_code;
					$full_name = $f->full_name;
					$employee_id = $f->employee_id;
					$total_jam_kerja = $f->total_jam_kerja;
					$total_masuk = $f->total_masuk;
					$total_tidak_masuk = $total_tidak_masuk;
					$gaji_bulanan = $gaji_bulanan;
					$gaji_harian = $gaji_harian_benefit;
					$gaji = $gaji;
					$tunjangan_jabatan = $tunjangan_jabatan;
					$tunjangan_transport = $tunjangan_transport;
					$tunjangan_konsumsi = $tunjangan_konsumsi;
					$tunjangan_komunikasi = $tunjangan_komunikasi;
					$lembur_perjam = $lembur_perjam;
					$total_jam_lembur = $f->total_jam_lembur;
					$total_nominal_lembur = $total_nominal_lembur;
					$bonus = $bonus;
					$thr = $thr;
					$total_pendapatan = $total_pendapatan;
					$bpjs_kesehatan = $bpjs_kesehatan;
					$bpjs_tk = $bpjs_tk;
					/*$absen = $potongan_absen;*/
					$seragam = $seragam;
					$pelatihan = $pelatihan;
					$lain_lain = $lain_lain;
					$hutang = $hutang;
					$sosial = $sosial;
					$payroll = $payroll;
					$pph_21 = $pph_21;
					$subtotal = $subtotal;
					$gaji_bersih = $gaji_bersih;
				}

				
				if(!$view){ 
					

					$dt .= '<tr>';

					
					$dt .= '<td>'.$emp_code.'</td>';
					$dt .= '<td>'.$full_name.'<input type="hidden" id="hdnempid_gaji" name="hdnempid_gaji['.$row.']" value="'.$employee_id.'"/><input type="hidden" name="marital_status_gaji['.$row.']" value="'.$f->marital_status_id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($total_jam_kerja,'jml_jam_kerja_gaji['.$row.']','','jml_jam_kerja_gaji','text-align: right;','data-id="'.$row.'" readonly ').'<input type="hidden" id="hdnid_gaji" name="hdnid_gaji['.$row.']" value="'.$id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($total_masuk,'jml_hadir_gaji['.$row.']','','jml_hadir_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($total_tidak_masuk,'jml_tdkhadir_gaji['.$row.']','','jml_tdkhadir_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($gaji_bulanan,'gaji_bulanan_gaji['.$row.']','','gaji_bulanan_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($gaji_harian,'gaji_harian_gaji['.$row.']','','gaji_harian_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($gaji,'gaji_gaji['.$row.']','','gaji_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($tunjangan_jabatan,'tunj_jabatan_gaji['.$row.']','','tunj_jabatan_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setTotalPendapatan(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($tunjangan_transport,'tunj_transport_gaji['.$row.']','','tunj_transport_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setTotalPendapatan(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($tunjangan_konsumsi,'tunj_konsumsi_gaji['.$row.']','','tunj_konsumsi_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setTotalPendapatan(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($tunjangan_komunikasi,'tunj_komunikasi_gaji['.$row.']','','tunj_komunikasi_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setTotalPendapatan(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($lembur_perjam,'lembur_perjam_gaji['.$row.']','','lembur_perjam_gaji','text-align: right;','data-id="'.$row.'" readonly').'</td>';

					$dt .= '<td>'.$this->return_build_txt($total_jam_lembur,'jam_lembur_gaji['.$row.']','','jam_lembur_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($total_nominal_lembur,'total_nominal_lembur_gaji['.$row.']','','total_nominal_lembur_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td class="gaji-bonus-col" data-has-component="'.($payrollComponents['bonus']['has_data'] ? 1 : 0).'">'.$this->return_build_txt($bonus,'bonus_gaji['.$row.']','','bonus_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td class="gaji-thr-col" data-has-component="'.($payrollComponents['thr']['has_data'] ? 1 : 0).'">'.$this->return_build_txt($thr,'thr_gaji['.$row.']','','thr_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($total_pendapatan,'ttl_pendapatan_gaji['.$row.']','','ttl_pendapatan_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($bpjs_kesehatan,'bpjs_kes_gaji['.$row.']','','bpjs_kes_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($bpjs_tk,'bpjs_tk_gaji['.$row.']','','bpjs_tk_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					
					$dt .= '<td>'.$this->return_build_txt($bpjs_jht,'bpjs_jht_gaji['.$row.']','','bpjs_jht_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($bpjs_jp,'bpjs_jp_gaji['.$row.']','','bpjs_jp_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($bpjs_jkk,'bpjs_jkk_gaji['.$row.']','','bpjs_jkk_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($bpjs_jkm,'bpjs_jkm_gaji['.$row.']','','bpjs_jkm_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';


					/*$dt .= '<td>'.$this->return_build_txt($absen,'absen_gaji['.$row.']','','absen_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';*/

					$dt .= '<td>'.$this->return_build_txt($seragam,'seragam_gaji['.$row.']','','seragam_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setSubTotal(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($pelatihan,'pelatihan_gaji['.$row.']','','pelatihan_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setSubTotal(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($lain_lain,'lainlain_gaji['.$row.']','','lainlain_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setSubTotal(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($hutang,'hutang_gaji['.$row.']','','hutang_gaji','text-align: right;','data-id="'.$row.'" readonly').'</td>';

					$dt .= '<td>'.$this->return_build_txt($sosial,'sosial_gaji['.$row.']','','sosial_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($payroll,'payroll_gaji['.$row.']','','payroll_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setGajiBersih(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($pph_21,'pph21_gaji['.$row.']','','pph21_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($subtotal,'subtotal_gaji['.$row.']','','subtotal_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($gaji_bersih,'gaji_bersih_gaji['.$row.']','','gaji_bersih_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					
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

					$print_gaji ="";
					if($status_payroll == 2){
						$print_gaji = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportGaji_perEmployee('."'".$payroll_id."'".','."'".$employee_id."'".')"> <i class="fa fa-download"></i> Gaji</a>';

						$dt .= '<td style="text-align:center !important">'.$print_gaji.'</td>';
					}else{
						$dt .= '<td style="text-align:center !important">-</td>';
					}
					
					
					$dt .= '<td>'.$emp_code.'</td>';
					$dt .= '<td>'.$full_name.'</td>';
					$dt .= '<td>'.$total_jam_kerja.'</td>';
					$dt .= '<td>'.$total_masuk.'</td>';
					$dt .= '<td>'.$total_tidak_masuk.'</td>';
					$dt .= '<td>'.$gaji_bulanan.'</td>';
					$dt .= '<td>'.$gaji_harian.'</td>';
					$dt .= '<td>'.$gaji.'</td>';
					$dt .= '<td>'.$tunjangan_jabatan.'</td>';
					$dt .= '<td>'.$tunjangan_transport.'</td>';
					$dt .= '<td>'.$tunjangan_konsumsi.'</td>';
					$dt .= '<td>'.$tunjangan_komunikasi.'</td>';
					$dt .= '<td>'.$lembur_perjam.'</td>';
					$dt .= '<td>'.$total_jam_lembur.'</td>';
					$dt .= '<td>'.$total_nominal_lembur.'</td>';
					$dt .= '<td class="gaji-bonus-col" data-has-component="'.($payrollComponents['bonus']['has_data'] ? 1 : 0).'">'.$bonus.'</td>';
					$dt .= '<td class="gaji-thr-col" data-has-component="'.($payrollComponents['thr']['has_data'] ? 1 : 0).'">'.$thr.'</td>';
					$dt .= '<td>'.$total_pendapatan.'</td>';
					$dt .= '<td>'.$bpjs_kesehatan.'</td>';
					$dt .= '<td>'.$bpjs_tk.'</td>';

					$dt .= '<td>'.$bpjs_jht.'</td>';
					$dt .= '<td>'.$bpjs_jp.'</td>';
					$dt .= '<td>'.$bpjs_jkk.'</td>';
					$dt .= '<td>'.$bpjs_jkm.'</td>';

					// $dt .= '<td>'.$absen.'</td>';
					$dt .= '<td>'.$seragam.'</td>';
					$dt .= '<td>'.$pelatihan.'</td>';
					$dt .= '<td>'.$lain_lain.'</td>';
					$dt .= '<td>'.$hutang.'</td>';
					$dt .= '<td>'.$sosial.'</td>';
					$dt .= '<td>'.$payroll.'</td>';
					$dt .= '<td>'.$pph_21.'</td>';
					$dt .= '<td>'.$subtotal.'</td>';
					$dt .= '<td>'.$gaji_bersih.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


}
