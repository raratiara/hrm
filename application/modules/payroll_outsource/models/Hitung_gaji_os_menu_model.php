<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_gaji_os_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "payroll_outsource/hitung_gaji_os_menu";
 	protected $table_name 				= _PREFIX_TABLE."payroll_slip";
 	protected $primary_key 				= "id";
	protected $approval_matrix_type_id 	= 19;
	protected $payment_history_table 	= _PREFIX_TABLE."payroll_paid_history";

	function __construct()
	{
		parent::__construct();
		$this->ensurePph21AdjustmentPayrollColumns();
	}

	private function ensurePph21AdjustmentPayrollColumns()
	{
		if(!$this->db->field_exists('pph21_adjustment', 'payroll_slip_detail')){
			$this->db->query("ALTER TABLE `payroll_slip_detail` ADD COLUMN `pph21_adjustment` DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER `pph_21`");
		}

		if(!$this->db->field_exists('pph21_adjustment_keterangan', 'payroll_slip_detail')){
			$this->db->query("ALTER TABLE `payroll_slip_detail` ADD COLUMN `pph21_adjustment_keterangan` VARCHAR(50) NULL AFTER `pph21_adjustment`");
		}
	}

	private function getPph21AdjustmentMap($bulan, $tahun, $employeeIds = [])
	{
		if (!$this->db->table_exists('spt_pph21_adjustment_os')) return [];
		if (empty($employeeIds)) return [];

		$rows = $this->db
			->select('employee_id, type, amount')
			->where('proses_ke_bulan_penggajian', (int)$bulan)
			->where('proses_ke_tahun_penggajian', trim($tahun))
			->where_in('status', ['processed', 'process', 'proces'])
			->where_in('employee_id', array_map('intval', $employeeIds))
			->get('spt_pph21_adjustment_os')
			->result();

		$map = [];
		foreach ($rows as $row) {
			$type = strtolower(trim((string)$row->type));
			$amount = abs((float)$row->amount);
			$isRefund = $type == 'refund';
			$employeeId = (int)$row->employee_id;
			$keterangan = $isRefund ? 'Refund' : 'Kurang Bayar';
			if (!isset($map[$employeeId])) {
				$map[$employeeId] = ['amount' => 0, 'keterangan' => ''];
			}
			$map[$employeeId]['amount'] += $isRefund ? $amount : ($amount * -1);
			if (strpos($map[$employeeId]['keterangan'], $keterangan) === false) {
				$map[$employeeId]['keterangan'] .= ($map[$employeeId]['keterangan'] == '' ? '' : ', ').$keterangan;
			}
		}
		return $map;
	}

	// fix
	public function get_list_data()
	{
		$aColumns = [
			NULL,
			NULL,
			'dt.id',
			'dt.project_name',
			'dt.month_name',
			'dt.tahun_penggajian',
			'dt.status',
			'dt.project_id',
			'dt.bulan_penggajian',
			'dt.payroll_status',
			'dt.status_id',
			'dt.created_by',
			'dt.current_approval_level',
			'dt.is_approver',
			'dt.is_approver_view'
		];

		$where_project = "";
			if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
			$where_project = " and a.project_id = '".$_GET['flproject']."' ";
		}

		

		$sIndexColumn = $this->primary_key;
		/*$sTable = '(select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
				from payroll_slip a 
				left join employees b on b.id = a.employee_id 
				left join master_month c on c.id = a.periode_bulan
				left join project_outsource d on d.id = b.project_id
				left join master_job_title_os e on e.id = b.job_title_id
				left join data_customer f on f.id = d.customer_id
				where 1=1 '.$where_project.'
			)dt';*/


		$karyawan_id = $_SESSION['worker'];
		$whr_approval = '';
		if($_SESSION['role'] != 1){
			$whr_approval = ' and (ao.created_by = "'.$karyawan_id.'" or ao.direct_id = "'.$karyawan_id.'" or ao.is_approver_view = 1) ';
		}

		$sTable = '(select ao.* from (select a.*, b.name_indo as month_name, c.project_name,
					case when a.status_id = 0 then "Draft" when a.status_id = 2 then coalesce(d.name, "Menunggu Pembayaran") else coalesce(st.name, "Waiting Approval") end as payroll_status,
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
					from payroll_slip a 
					left join master_month b on b.id = a.bulan_penggajian
					left join project_outsource c on c.id = a.project_id
					left join master_payroll_status d on d.id = a.status
					left join master_status_cashadvance st on st.id = a.status_id
					left join employees creator on creator.id = a.created_by
					left join approval_path ap on ap.trx_id = a.id and ap.approval_matrix_type_id = '.$this->approval_matrix_type_id.'
					left join approval_matrix am on am.id = ap.approval_matrix_id
					left join approval_matrix_detail amd on amd.approval_matrix_id = am.id
					left join approval_matrix_role_pic amp on amp.approval_matrix_role_id = amd.role_id
					left join approval_matrix_detail cur_detail on cur_detail.approval_matrix_id = ap.approval_matrix_id and cur_detail.approval_level = ap.current_approval_level
					left join approval_matrix_role cur_role on cur_role.id = cur_detail.role_id
					where 1=1 '.$where_project.'
					group by a.id
			)ao where 1=1 '.$whr_approval.'
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
			/*$cek_history_bpjs = $this->db->query("select * from history_bpjs where project_id = ".$row->project_id." and periode_gaji_bulan = ".$row->bulan_penggajian." and periode_gaji_tahun = '".$row->tahun_penggajian."' ")->result();*/

			$pembayaran_gaji = $this->db->query("select a.id from payroll_paid_history a left join payroll_slip b on b.id = a.payroll_slip_id where b.bulan_penggajian = ".$row->bulan_penggajian." and b.tahun_penggajian = '".$row->tahun_penggajian."' ")->result();

			$pembayaran_lembur = $this->db->query("select a.id from overtime_paid_history a left join payroll_slip b on b.id = a.payroll_slip_id where b.bulan_penggajian = ".$row->bulan_penggajian." and b.tahun_penggajian = '".$row->tahun_penggajian."' ")->result();
			$isEdit = 1; $isDelete = 1;
			if(!empty($pembayaran_gaji) || !empty($pembayaran_lembur)){
				$isEdit = 0; $isDelete = 0;
			}



			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #112D80; border-color: #112D80;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1" && $isEdit == 1 && $row->status_id != 2)  {
				
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1" && $isDelete == 1 && $row->status_id != 2)  {
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
			
			
            $print_lembur = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportLembur('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Lembur
            </a>';
            $print_absen = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportAbsenOS_gaji('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Rekap Absen
            </a>';
            $print_rekap_gaji = "";
            if($row->status == 2){ //terbayar
	            $print_rekap_gaji = '<a class="btn btn-default btn-xs" style="align:center" onclick="getRekapGajiOS('."'".$row->id."'".')">
	                <i class="fa fa-download"></i>
	                Rekap Gaji
	            </a>';
	        }


			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
				'.$print_gaji.'
				'.$print_lembur.'
				'.$print_absen.'
				'.$print_rekap_gaji.'
				'.$detail.'
				'.$edit.'
				'.$delete.'
				</div>',
				$row->id,
				$row->project_name,
				$row->month_name,
				$row->tahun_penggajian,
				$row->payroll_status
				
			));


		}

		echo json_encode($output);
	}

	// filltering null value from array
	public function is_not_null($val){
		return !is_null($val);
	}		

	private function getPayrollApprovalTotal($payroll_slip_id)
	{
		$row = $this->db->query("select coalesce(sum(total_pendapatan), 0) as total_nominal
			from payroll_slip_detail
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

	private function getBenefitDeductionOsComponentColumn()
	{
		if($this->db->field_exists('salary_components_id', 'employee_benefit_deduction_os')){
			return 'salary_components_id';
		}
		if($this->db->field_exists('salary_component_id', 'employee_benefit_deduction_os')){
			return 'salary_component_id';
		}
		if($this->db->field_exists('component_id', 'employee_benefit_deduction_os')){
			return 'component_id';
		}
		return 'salary_components_id';
	}

	private function getEmployeeBenefitDeductionOsAmounts($employee_ids)
	{
		$result = [];
		if(empty($employee_ids) || !$this->db->table_exists('employee_benefit_deduction_os') || !$this->db->table_exists('salary_components_os')){
			return $result;
		}

		$employee_ids = array_values(array_unique(array_filter(array_map('intval', (array)$employee_ids))));
		if(empty($employee_ids)){
			return $result;
		}

		$componentColumn = $this->getBenefitDeductionOsComponentColumn();

		// Fetch salary_components_os rows (where salary_bpjs_id is NULL)
		$rows = $this->db->query("select a.employee_id, a.amount, b.code
					from employee_benefit_deduction_os a
					left join salary_components_os b on b.id = a.".$componentColumn."
					where a.employee_id in (".implode(',', $employee_ids).")
					AND (a.salary_bpjs_id IS NULL OR a.salary_bpjs_id = 0)")->result();

		foreach($rows as $row){
			if(empty($row->code)) continue;
			$result[$row->employee_id][$row->code] = $row->amount;
		}

		// Fetch BPJS rows (where salary_bpjs_id is set) - use salary_bpjs.code as key
		$bpjsRows = $this->db->query("select a.employee_id, a.amount, b.code
					from employee_benefit_deduction_os a
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
	 * Ambil komponen salary_os yang punya calculate_percentage & calculate_from (non-fixed)
	 */
	private function getSalaryComponentsOsCalculation()
	{
		$result = [];
		$rows = $this->db->query("SELECT code, calculate_percentage, calculate_from 
			FROM salary_components_os 
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

	private function benefitOsValue($benefit, $field, $fallback = 0)
	{
		if(isset($benefit[$field]) && $benefit[$field] !== '' && $benefit[$field] != 0){
			return $benefit[$field];
		}
		return $fallback;
	}

	/**
	 * Ambil kode-kode BPJS dari tabel salary_bpjs, grouped by category
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

	private function calcBpjsKesehatan($benefit)
	{
		$codes = $this->getBpjsCodes();
		$total = 0;
		foreach($codes['kesehatan'] as $code){
			$total += (float)$this->benefitOsValue($benefit, $code, 0);
		}
		return $total;
	}

	private function calcBpjsTk($benefit)
	{
		$codes = $this->getBpjsCodes();
		$total = 0;
		foreach($codes['ketenagakerjaan'] as $code){
			$total += (float)$this->benefitOsValue($benefit, $code, 0);
		}
		return $total;
	}

	/**
	 * Hitung detail BPJS TK per komponen (JHT, JP, JKK, JKM)
	 * Return: ['bpjs_jht' => x, 'bpjs_jp' => x, 'bpjs_jkk' => x, 'bpjs_jkm' => x]
	 */
	private function calcBpjsTkDetail($benefit)
	{
		return [
			'bpjs_jht' => (float)$this->benefitOsValue($benefit, 'jht', 0),
			'bpjs_jp'  => (float)$this->benefitOsValue($benefit, 'jp', 0),
			'bpjs_jkk' => (float)$this->benefitOsValue($benefit, 'jk', 0),
			'bpjs_jkm' => (float)$this->benefitOsValue($benefit, 'jkm', 0),
		];
	}

	/**
	 * Hitung nilai komponen berdasarkan calculate_percentage & calculate_from
	 */
	private function calculateComponentValue($benefit, $code, $salaryCalc, $resolvedValues)
	{
		if(isset($benefit[$code]) && $benefit[$code] !== '' && $benefit[$code] != 0){
			return (float)$benefit[$code];
		}

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

	/*public function ceil_2($number){
	    return ceil($number * 100) / 100;
	}*/


	public function getRateTer($total_pendapatan, $emp_id){
		$pph_21 =0; $ter_rate=0;

		$emp = $this->db->query("select marital_status_id from employees where emp_source = 'outsource' and id = ".$emp_id." ")->result(); 
		if(!empty($emp)){
			$getCat = $this->db->query("select category from tax_ter_category_mapping where marital_status_id = ".$emp[0]->marital_status_id." ")->result(); 
			if(!empty($getCat)){
				$getTer = $this->db->query("select rate from tax_ter where category = '".$getCat[0]->category."' and (".$total_pendapatan." between min_bruto and max_bruto) order by id desc limit 1 ")->result();
				if(!empty($getTer)){
			        $ter_rate = $getTer[0]->rate;
			        $pph_21 = ceil($total_pendapatan*$ter_rate);
				}
			}
		}


		return [
		    'ter_rate' => $ter_rate,
		    'pph_21'   => $pph_21
		]; 

	}



	public function add_data($post)
	{

		$this->load->helper('global');

	    if (empty($post['penggajian_month']) || empty($post['penggajian_year'])) {
	        
	        return [
			    "status" => false,
			    "msg" 	 => "Bulan Tahun Penggajian harus diisi"
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
			    "msg" 	 => "Bulan Penggajian tidak valid"
			];
	    }

	    $periode_gaji = $tahun . '-' . $codemonth->code;

	    // =========================
	    // MAIN QUERY (1 QUERY SAJA)
	    // =========================
	    $this->db->select("
	        e.id as employee_id,
	        e.project_id,
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
	        t.sistem_lembur,
	        t.nominal_lembur,
	        t.rumus_lembur,

	        COALESCE(l.ttl_hutang,0) as hutang
	    ");

	    $this->db->from('employees e');
	    $this->db->join('summary_absen_outsource_detail sd', 'sd.emp_id = e.id', 'left');
	    $this->db->join('summary_absen_outsource s', 's.id = sd.summary_absen_outsource_id', 'left');

	    $this->db->join("(SELECT b.id_employee,
	                        SUM(b.nominal_cicilan_per_bulan) as ttl_hutang
	                     FROM loan_detail a
	                     JOIN loan b ON b.id = a.loan_id
	                     WHERE DATE_FORMAT(a.tgl_jatuh_tempo,'%Y-%m') = '$periode_gaji'
	                     GROUP BY b.id_employee) l",
	                     "l.id_employee = e.id",
	                     "left");

	    $this->db->join('data_customer t', 't.id = e.cust_id', 'left');

	    $this->db->where('e.emp_source', 'outsource');
	    $this->db->where('IFNULL(e.is_special_payroll,0) != 1', null, false);
	    $this->db->where('e.status_id', 1);
	    $this->db->where('s.bulan_penggajian', $bulan);
	    $this->db->where('s.tahun_penggajian', $tahun);

	    if ($post['is_all_project'] == 'Karyawan' && !empty($post['employeeIds'])) {
	        $this->db->where_in('e.id', $post['employeeIds']);
	    }

	    if ($post['is_all_project'] == 'Sebagian' && !empty($post['projectIds'])) {
	        $this->db->where_in('e.project_id', $post['projectIds']);
	    }

	    $data = $this->db->get()->result();

	    if (empty($data)) {
	        $this->db->trans_complete();
	        
	        return [
			    "status" => false,
			    "msg" 	 => "Data gagal disimpan"
			];
	    }

	    // =========================
	    // Cache Payroll Header Per Project
	    // =========================
	    $projectHeader = [];
	    $insertDetail  = [];
	    $bpjsHeaderCache = [];
	    $detailEmployeeIdsByPayroll = [];
	    $benefitAmounts = $this->getEmployeeBenefitDeductionOsAmounts(array_map(function($row) {
	    	return $row->employee_id;
	    }, $data));
	    $salaryCalc = $this->getSalaryComponentsOsCalculation();
	    $pph21AdjustmentMap = $this->getPph21AdjustmentMap($bulan, $tahun, array_map(function($row) {
	    	return $row->employee_id;
	    }, $data));


	    foreach ($data as $row) {

	        // =========================
	        // Buat header payroll jika belum ada
	        // =========================
	        if (!isset($projectHeader[$row->project_id])) {

	            $header = $this->db->where([
	                'project_id'       => $row->project_id,
	                'bulan_penggajian' => $bulan,
	                'tahun_penggajian' => $tahun
	            ])->get('payroll_slip')->row();

	            if (!$header) {
	                $this->db->insert('payroll_slip', [
	                    'project_id'       => $row->project_id,
	                    'bulan_penggajian' => $bulan,
	                    'tahun_penggajian' => $tahun,
	                    'tgl_start_absen'  => $row->tgl_start_absen,
	                    'tgl_end_absen'    => $row->tgl_end_absen,
	                    'status'           => null,
	                    'status_id'        => $isSubmitFinal ? 1 : 0,
	                    'created_at'       => date("Y-m-d H:i:s"),
	                    'created_by'       => $_SESSION['worker']
	                ]);
	                $projectHeader[$row->project_id] = $this->db->insert_id();
	            } else {
	                $projectHeader[$row->project_id] = $header->id;
	                $this->db->update('payroll_slip', [
	                	'status' => null,
	                	'status_id' => $isSubmitFinal ? 1 : 0,
	                	'rfu_reason' => '',
	                	'reject_reason' => '',
	                	'updated_at' => date("Y-m-d H:i:s"),
	                	'updated_by' => $_SESSION['worker']
	                ], ['id' => $header->id]);
	            }
	        }

	        // =========================
	        // HITUNG GAJI
	        // =========================
	        $benefit = isset($benefitAmounts[$row->employee_id]) ? $benefitAmounts[$row->employee_id] : [];
	        $gaji_bulanan = (float)$this->benefitOsValue($benefit, 'gaji_bulanan', 0);
	        $gaji_harian  = (float)$this->benefitOsValue($benefit, 'gaji_harian', 0);
	        if($gaji_harian == 0 && $gaji_bulanan > 0 && $row->total_hari_kerja > 0){
	            $gaji_harian = ceil($gaji_bulanan / $row->total_hari_kerja);
	        }
	        $tunjangan_jabatan = (float)$this->benefitOsValue($benefit, 'tunjangan_jabatan', 0);
	        $tunjangan_transport = (float)$this->benefitOsValue($benefit, 'tunjangan_transportasi', 0);
	        $tunjangan_konsumsi = (float)$this->benefitOsValue($benefit, 'tunjangan_konsumsi', 0);
	        $tunjangan_komunikasi = (float)$this->benefitOsValue($benefit, 'tunjangan_komunikasi', 0);

	        $total_tidak_masuk = (int)$row->total_hari_kerja - (int)$row->total_masuk;

	        $gaji = ceil($row->total_masuk * $gaji_harian);

	        
	        if($row->sistem_lembur == 'tidak_sistem_lembur'){
	        	$lembur_perjam  = $row->nominal_lembur ?? 0;
	        }else{
	        	$lembur_perjam  = ceil($gaji_bulanan / 173);
	        
	        	if($row->rumus_lembur == 'gapok/26/7'){
					$lembur_perjam = ceil($gaji_bulanan / 26 / 7);
				}else if($row->rumus_lembur == 'gapok/20/12'){
					$lembur_perjam = ceil($gaji_bulanan / 20 / 12);
				}
	        }


	        //$lembur_total   = ceil($lembur_perjam * $row->total_jam_lembur);
	        $lembur_total = $row->total_lembur;

	        // Resolved values untuk basis perhitungan percentage
	        $resolvedValues = ['gaji_bulanan' => $gaji_bulanan, 'gaji_harian' => $gaji_harian];

	        // BPJS dari salary_bpjs (nama komponen dinamis dari tabel salary_bpjs)
	        $bpjs_kesehatan = $this->calcBpjsKesehatan($benefit);
	        $bpjs_tk_detail = $this->calcBpjsTkDetail($benefit);
	        $bpjs_tk        = array_sum($bpjs_tk_detail);
	        $seragam = (float)$this->benefitOsValue($benefit, 'seragam', 0);
	        $pelatihan = (float)$this->benefitOsValue($benefit, 'pelatihan', 0);
	        $lain_lain = (float)$this->benefitOsValue($benefit, 'lain_lain', 0);
	        $payroll = (float)$this->benefitOsValue($benefit, 'payroll', 0);
	        $pph_120 = (float)$this->benefitOsValue($benefit, 'pph_120', 0);

	        $hari_kerja = (int)$row->total_hari_kerja;

	        /*$potongan_absen = $hari_kerja > 0
	            ? ceil($total_tidak_masuk * ($gaji_bulanan / $hari_kerja))
	            : 0;*/

	        $sosial = (float)$this->benefitOsValue($benefit, 'sosial', 0);
	        $hutang = (float)$row->hutang;

	        if (!isset($componentCache[$row->project_id])) {
	        	$componentCache[$row->project_id] = $this->getPayrollComponentMap($row->project_id, $bulan, $tahun);
	        }
	        $payrollComponents = $componentCache[$row->project_id];
	        $bonus = $payrollComponents['bonus']['has_data'] ? ($payrollComponents['bonus']['amounts'][(int)$row->employee_id] ?? 0) : 0;
	        $thr = $payrollComponents['thr']['has_data'] ? ($payrollComponents['thr']['amounts'][(int)$row->employee_id] ?? 0) : 0;

	        //$total_pendapatan = ceil($gaji + $lembur_total);
	        $total_pendapatan = $gaji + $tunjangan_jabatan + $tunjangan_transport + $tunjangan_konsumsi + $tunjangan_komunikasi + $bonus + $thr;

	        $getTer = $this->getRateTer($total_pendapatan, $row->employee_id);
			$ter_rate = $getTer['ter_rate'];
			$pph_21   = $getTer['pph_21'];
			$pph21_adjustment = $pph21AdjustmentMap[(int)$row->employee_id]['amount'] ?? 0;
			$pph21_adjustment_keterangan = $pph21AdjustmentMap[(int)$row->employee_id]['keterangan'] ?? '';

	        $subtotal    = ceil($total_pendapatan - ($seragam + $pelatihan + $lain_lain + $hutang + $sosial));
	        $gaji_bersih = ceil($subtotal - ($bpjs_kesehatan + $bpjs_tk + $payroll + $pph_120 + $pph_21) + $pph21_adjustment);


	        // =========================
			// INSERT / UPDATE HISTORY BPJS
			// =========================

			if (!isset($bpjsHeaderCache[$row->project_id])) {

			    $bpjs_header = $this->db->where([
			        'project_id'         => $row->project_id,
			        'periode_gaji_bulan' => $bulan,
			        'periode_gaji_tahun' => $tahun
			    ])->get('history_bpjs')->row();

			    if (!$bpjs_header) {

			        $this->db->insert("history_bpjs", [
			            'project_id'         => $row->project_id,
			            'periode_gaji_bulan' => $bulan,
			            'periode_gaji_tahun' => $tahun
			        ]);

			        $bpjsHeaderCache[$row->project_id] = $this->db->insert_id();

			    } else {
			        $bpjsHeaderCache[$row->project_id] = $bpjs_header->id;
			    }
			}

			$history_bpjs_id = $bpjsHeaderCache[$row->project_id];


			// cek detail bpjs employee
			$bpjs_detail = $this->db->where([
			    'history_bpjs_id' => $history_bpjs_id,
			    'employee_id'     => $row->employee_id
			])->get('history_bpjs_detail')->row();

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
			    $this->db->update(
			        "history_bpjs_detail",
			        $data_bpjs_detail,
			        ['id' => $bpjs_detail->id]
			    );
			} else {
			    $this->db->insert("history_bpjs_detail", $data_bpjs_detail);
			}


			// =========================
	        // Simpan ke batch insert
	        // =========================

	        $insertDetail[] = [
	            'payroll_slip_id'  => $projectHeader[$row->project_id],
	            'employee_id'      => $row->employee_id,
	            'total_hari_kerja' => $row->total_hari_kerja,
	            'total_masuk'      => $row->total_masuk,
	            'total_tidak_masuk'=> $total_tidak_masuk,
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
	            'pph_120'          => $pph_120,
	            'subtotal'         => $subtotal,
	            'gaji_bersih'      => $gaji_bersih,
	            'pph_21'      	   => $pph_21,
	            'pph_21_rate' 	   => $ter_rate,
	            'pph21_adjustment' => $pph21_adjustment,
	            'pph21_adjustment_keterangan' => $pph21_adjustment_keterangan
	        ];

	        $detailEmployeeIdsByPayroll[$projectHeader[$row->project_id]][] = (int) $row->employee_id;
	    }

	    // =========================
	    // INSERT BATCH (SUPER CEPAT)
	    // =========================
	    foreach ($detailEmployeeIdsByPayroll as $payrollSlipId => $employeeIds) {
	        $employeeIds = array_values(array_unique($employeeIds));
	        if (!empty($employeeIds)) {
	            $this->db
	                ->where('payroll_slip_id', $payrollSlipId)
	                ->where_in('employee_id', $employeeIds)
	                ->delete('payroll_slip_detail');
	        }
	    }

	    if (!empty($insertDetail)) {
	        $this->db->insert_batch('payroll_slip_detail', $insertDetail);
	    }

	    foreach (array_unique(array_values($projectHeader)) as $payrollSlipId) {
	    	if($isSubmitFinal) {
	    		$this->createApprovalPath($payrollSlipId, $this->getPayrollApprovalTotal($payrollSlipId));
	    	} else {
	    		$this->deleteApprovalPath($payrollSlipId);
	    	}
	    }

	    $this->db->trans_complete();

	    

	    if($this->db->trans_status()){
			return [
			    "status" => true,
			    "msg" => "Data berhasil disimpan"
			];
		}else{
			return [
			    "status" => false,
			    "msg" 	 => "Data gagal disimpan"
			];
		}
	}



	public function add_data_old($post) { 
		

  		if(!empty($post['penggajian_month']) && !empty($post['penggajian_year']) ){ 

  			$codemonth = $this->db->query("select * from master_month where id = '".$post['penggajian_month']."' ")->result();
			$periode_gaji = $post['penggajian_year'].'-'.$codemonth[0]->code; //2026-03
  			
			if ($post['is_all_project'] == 'Karyawan') {
			    if (!empty($post['employeeIds']) && is_array($post['employeeIds'])) {
			        $this->db->where_in('id', $post['employeeIds']);
			    }
			}else if ($post['is_all_project'] == 'Sebagian') {
			    if (!empty($post['projectIds']) && is_array($post['projectIds'])) {
			        $this->db->where_in('project_id', $post['projectIds']);
			    }
			}

			$data_os = $this->db
			    ->select('id, total_hari_kerja, no_bpjs, no_bpjs_ketenagakerjaan, project_id')
			    ->from('employees')
			    ->where('emp_source', 'outsource')
			    ->where('status_id', 1)
			    ->get()
			    ->result();


  			if(!empty($data_os)){
  				$benefitAmounts = $this->getEmployeeBenefitDeductionOsAmounts(array_map(function($row) {
  					return $row->id;
  				}, $data_os));
  				$salaryCalc = $this->getSalaryComponentsOsCalculation();

  				foreach($data_os as $rowdata_os){
  					$emp_id = $rowdata_os->id;
  					
  					$data_summary = $this->db->query("select a.*, b.project_id, b.full_name, b.emp_code, b.id as employee_id, b.total_hari_kerja, c.bulan_penggajian, c.tahun_penggajian, c.project_id, c.tgl_start_absen, c.tgl_end_absen, d.sistem_lembur, d.nominal_lembur, d.rumus_lembur
						from summary_absen_outsource_detail a left join employees b on b.id = a.emp_id
						left join summary_absen_outsource c on c.id = a.summary_absen_outsource_id
						left join data_customer d on d.id = b.cust_id
						where c.bulan_penggajian = ".$post['penggajian_month']." and c.tahun_penggajian = '".$post['penggajian_year']."' and c.project_id = '".$rowdata_os->project_id."' and a.emp_id = '".$emp_id."'
						order by b.full_name asc")->result();

  					if(!empty($data_summary)){
						$benefit_emp = isset($benefitAmounts[$emp_id]) ? $benefitAmounts[$emp_id] : [];
  						$gaji_bulanan = (float)$this->benefitOsValue($benefit_emp, 'gaji_bulanan', 0);
  						$gaji_harian_val = (float)$this->benefitOsValue($benefit_emp, 'gaji_harian', 0);
  						if($gaji_harian_val == 0 && $gaji_bulanan > 0 && $rowdata_os->total_hari_kerja > 0){
  							$gaji_harian_val = ceil($gaji_bulanan / $rowdata_os->total_hari_kerja);
  						}

  						$total_tidak_masuk = ((int)$data_summary[0]->total_ijin ?? 0) +
								     ((int)$data_summary[0]->total_cuti ?? 0) +
								     ((int)$data_summary[0]->total_alfa ?? 0);
  						$gaji = ceil(($data_summary[0]->total_masuk * $gaji_harian_val) * 100) / 100;
  						if($data_summary[0]->sistem_lembur == 'tidak_sistem_lembur'){
  							$lembur_perjam 	= $data_summary[0]->nominal_lembur ?? 0;
  						}else{
  							$lembur_perjam 	= ceil(($gaji_bulanan / 173) * 100) / 100;
  							if($data_summary[0]->rumus_lembur == 'gapok/26/7'){
								$lembur_perjam = ceil($gaji_bulanan / 26 / 7);
							}else if($data_summary[0]->rumus_lembur == 'gapok/20/12'){
								$lembur_perjam = ceil($gaji_bulanan / 20 / 12);
							}
  						}
  						

  						$total_nominal_lembur = ceil($lembur_perjam*$data_summary[0]->total_jam_lembur);
  						$resolvedValues = ['gaji_bulanan' => $gaji_bulanan, 'gaji_harian' => $gaji_harian_val];
  						// BPJS dari salary_bpjs (nama komponen dinamis dari tabel salary_bpjs)
  						$bpjs_kesehatan = $this->calcBpjsKesehatan($benefit_emp);
  						$bpjs_tk_detail = $this->calcBpjsTkDetail($benefit_emp);
  						$bpjs_tk = array_sum($bpjs_tk_detail);

  						$hari_kerja = (int) ($rowdata_os->total_hari_kerja ?? 0);
						/*if ($hari_kerja > 0) {
						    $potongan_absen = ceil(
						        ($total_tidak_masuk * ($gaji_bulanan / $hari_kerja)) * 100
						    ) / 100;
						} else {
						    $potongan_absen = 0;
						}*/


  						$sosial = (float)$this->benefitOsValue($benefit_emp, 'sosial', 0);

  						
  						//ambil pinjaman yg masih berjalan
  						/*$data_pinjaman = $this->db->query("select sum(nominal_cicilan_per_bulan) as ttl_hutang from loan where id_employee = '".$emp_id."' and status_id = 5")->result();*/
  						$data_pinjaman = $this->db->query("select sum(nominal_cicilan_per_bulan) as ttl_hutang from loan_detail a left join loan b on b.id = a.loan_id
							where b.id_employee = '".$emp_id."' and DATE_FORMAT(a.tgl_jatuh_tempo, '%Y-%m') = '".$periode_gaji."'")->result();

  						$hutang=0;
  						if(!empty($data_pinjaman)){
  							$hutang = $data_pinjaman[0]->ttl_hutang;
  						}

  						/// ttl pendapatan - potongan tdk wajib
  						$subtotal = ceil(($gaji - ($hutang+$sosial)) * 100) / 100; 

  						/// subtotal - potongan wajib
  						$gaji_bersih = ceil(($subtotal - ($bpjs_kesehatan+$bpjs_tk)) * 100) / 100;



  						$data_payslip = $this->db->query("select * from payroll_slip where project_id = '".$rowdata_os->project_id."' and bulan_penggajian = '".$post['penggajian_month']."' and tahun_penggajian = '".$post['penggajian_year']."' ")->result();
  						if(empty($data_payslip)){ /// add header + add detail
  							$data = [
	  							'project_id' 		=> $rowdata_os->project_id,
								'bulan_penggajian' 	=> trim($post['penggajian_month']),
								'tahun_penggajian' 	=> trim($post['penggajian_year']),
								'tgl_start_absen' 	=> $data_summary[0]->tgl_start_absen,
								'tgl_end_absen' 	=> $data_summary[0]->tgl_end_absen
							];
							$this->db->insert($this->table_name, $data);
							$lastId = $this->db->insert_id();

							$data_dtl = [
								'payroll_slip_id' 	=> $lastId,
								'employee_id' 		=> $emp_id,
								'total_hari_kerja'  => $data_summary[0]->total_hari_kerja,
								'total_masuk'  		=> $data_summary[0]->total_masuk,
								'total_tidak_masuk' => $total_tidak_masuk,
								'total_lembur'  	=> $data_summary[0]->total_lembur,
								'total_jam_kerja'  	=> $data_summary[0]->total_jam_kerja,
								'total_jam_lembur'  => $data_summary[0]->total_jam_lembur,
								'created_at'		=> date("Y-m-d H:i:s"),
								'created_by' 		=> $_SESSION['worker'],
								'gaji_bulanan'  	=> $gaji_bulanan,
								'gaji_harian' 		=> $gaji_harian_val,
								'gaji' 				=> $gaji,
								'lembur_perjam' 	=> $lembur_perjam,
								'total_nominal_lembur' 	=> $total_nominal_lembur,
								'total_pendapatan' 	=> $gaji,
								'sosial' 			=> $sosial,
								'bpjs_kesehatan' 	=> $bpjs_kesehatan,
								'bpjs_tk' 			=> $bpjs_tk,
								'bpjs_jht' 			=> $bpjs_tk_detail['bpjs_jht'],
								'bpjs_jp' 			=> $bpjs_tk_detail['bpjs_jp'],
								'bpjs_jkk' 			=> $bpjs_tk_detail['bpjs_jkk'],
								'bpjs_jkm' 			=> $bpjs_tk_detail['bpjs_jkm'],
								'hutang' 			=> $hutang,
								'subtotal' 			=> $subtotal,
								'gaji_bersih' 		=> $gaji_bersih
							];
							$rs = $this->db->insert("payroll_slip_detail", $data_dtl);
  						}else{
  							$data_payslip_detail = $this->db->query("select a.* from payroll_slip_detail a left join payroll_slip b on b.id = a.payroll_slip_id where a.employee_id = '".$emp_id."' and b.bulan_penggajian = ".$post['penggajian_month']." and b.tahun_penggajian = '".$post['penggajian_year']."' ")->result();

  							if(empty($data_payslip_detail)){ ///ADD 
  								$data_dtl = [
									'payroll_slip_id' 	=> $data_payslip[0]->id,
									'employee_id' 		=> $emp_id,
									'total_hari_kerja'  => $data_summary[0]->total_hari_kerja,
									'total_masuk'  		=> $data_summary[0]->total_masuk,
									'total_tidak_masuk' => $total_tidak_masuk,
									'total_lembur'  	=> $data_summary[0]->total_lembur,
									'total_jam_kerja'  	=> $data_summary[0]->total_jam_kerja,
									'total_jam_lembur'  => $data_summary[0]->total_jam_lembur,
									'created_at'		=> date("Y-m-d H:i:s"),
									'created_by' 		=> $_SESSION['worker'],
									'gaji_bulanan'  	=> $gaji_bulanan,
									'gaji_harian' 		=> $gaji_harian_val,
									'gaji' 				=> $gaji,
									'lembur_perjam' 	=> $lembur_perjam,
									'total_nominal_lembur' => $total_nominal_lembur,
									'total_pendapatan' 	=> $gaji,
									'sosial' 			=> $sosial,
									'bpjs_kesehatan' 	=> $bpjs_kesehatan,
									'bpjs_tk' 			=> $bpjs_tk,
									'bpjs_jht' 			=> $bpjs_tk_detail['bpjs_jht'],
									'bpjs_jp' 			=> $bpjs_tk_detail['bpjs_jp'],
									'bpjs_jkk' 			=> $bpjs_tk_detail['bpjs_jkk'],
									'bpjs_jkm' 			=> $bpjs_tk_detail['bpjs_jkm'],
									'hutang' 			=> $hutang,
									'subtotal' 			=> $subtotal,
									'gaji_bersih' 		=> $gaji_bersih
								];
								$rs = $this->db->insert("payroll_slip_detail", $data_dtl);
  							}else{ ///UPDATE
  								$data_dtl = [
									'payroll_slip_id' 	=> $data_payslip[0]->id,
									'employee_id' 		=> $emp_id,
									'total_hari_kerja'  => $data_summary[0]->total_hari_kerja,
									'total_masuk'  		=> $data_summary[0]->total_masuk,
									'total_tidak_masuk' => $total_tidak_masuk,
									'total_lembur'  	=> $data_summary[0]->total_lembur,
									'total_jam_kerja'  	=> $data_summary[0]->total_jam_kerja,
									'total_jam_lembur'  => $data_summary[0]->total_jam_lembur,
									'created_at'		=> date("Y-m-d H:i:s"),
									'created_by' 		=> $_SESSION['worker'],
									'gaji_bulanan'  	=> $gaji_bulanan,
									'gaji_harian' 		=> $gaji_harian_val,
									'gaji' 				=> $gaji,
									'lembur_perjam' 	=> $lembur_perjam,
									'total_nominal_lembur' => $total_nominal_lembur,
									'total_pendapatan' 	=> $gaji,
									'sosial' 			=> $sosial,
									'bpjs_kesehatan' 	=> $bpjs_kesehatan,
									'bpjs_tk' 			=> $bpjs_tk,
									'bpjs_jht' 			=> $bpjs_tk_detail['bpjs_jht'],
									'bpjs_jp' 			=> $bpjs_tk_detail['bpjs_jp'],
									'bpjs_jkk' 			=> $bpjs_tk_detail['bpjs_jkk'],
									'bpjs_jkm' 			=> $bpjs_tk_detail['bpjs_jkm'],
									'hutang' 			=> $hutang,
									'subtotal' 			=> $subtotal,
									'gaji_bersih' 		=> $gaji_bersih
								];
								$rs = $this->db->update("payroll_slip_detail", $data_dtl, "id = '".$data_payslip_detail[0]->id."'");
  							}
  						}

		                

						if($rs){
							$bpjs_history = $data_payslip_detail = $this->db->query("select * from history_bpjs where project_id = ".$rowdata_os->project_id." and periode_gaji_bulan = ".trim($post['penggajian_month'])." and periode_gaji_tahun = '".trim($post['penggajian_year'])."' ")->result();
							if(empty($bpjs_history)){
								$data_bpjs = [
		  							'project_id' 			=> $rowdata_os->project_id,
									'periode_gaji_bulan' 	=> trim($post['penggajian_month']),
									'periode_gaji_tahun' 	=> trim($post['penggajian_year'])
								];
								$this->db->insert("history_bpjs", $data_bpjs);
								$lastIdBpjs = $this->db->insert_id();

								$log_bpjs = [
									'history_bpjs_id'	=> $lastIdBpjs,
									'employee_id' 		=> $emp_id,
									'no_bpjs_kesehatan' => $rowdata_os->no_bpjs,
									'no_bpjs_tk'  		=> $rowdata_os->no_bpjs_ketenagakerjaan,
									'nominal_bpjs_kesehatan'  	=> $bpjs_kesehatan,
									'nominal_bpjs_tk'  	=> $bpjs_tk,
									'tanggal_potong'  	=> date("Y-m-d H:i:s")
								];
								$this->db->insert("history_bpjs_detail", $log_bpjs);

							}else{

								$bpjs_history_detail = $this->db->query("select * from history_bpjs_detail where employee_id = '".$emp_id."' and history_bpjs_id = '".$bpjs_history[0]->id."' ")->result();
								if(!empty($bpjs_history_detail)){
									$log_bpjs = [
										'no_bpjs_kesehatan' => $rowdata_os->no_bpjs,
										'no_bpjs_tk'  		=> $rowdata_os->no_bpjs_ketenagakerjaan,
										'nominal_bpjs_kesehatan'  	=> $bpjs_kesehatan,
										'nominal_bpjs_tk'  	=> $bpjs_tk,
										'tanggal_potong'  	=> date("Y-m-d H:i:s")
									];
									 $this->db->update("history_bpjs_detail", $log_bpjs, "id = '".$bpjs_history_detail[0]->id."'");
								}else{
									$log_bpjs = [
										'history_bpjs_id'	=> $bpjs_history[0]->id,
										'employee_id' 		=> $emp_id,
										'no_bpjs_kesehatan' => $rowdata_os->no_bpjs,
										'no_bpjs_tk'  		=> $rowdata_os->no_bpjs_ketenagakerjaan,
										'nominal_bpjs_kesehatan'  	=> $bpjs_kesehatan,
										'nominal_bpjs_tk'  	=> $bpjs_tk,
										'tanggal_potong'  	=> date("Y-m-d H:i:s")
									];
									$this->db->insert("history_bpjs_detail", $log_bpjs);
								}

							}

							
						}

  					}

  				}
  				return $rs;
  			}

  		}else{
  			echo "Bulan Tahun Penggajian & Periode Absensi harus diisi"; 
  		}

		
	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){
			if(isset($post['action_type']) && $post['action_type'] == 'approval') {
				return $this->approve($post['id']);
			}
			$isSubmitFinal = isset($post['payroll_action']) && $post['payroll_action'] == 'submit_final';

			$this->db->trans_start();

			$getperiod_start 	= date_create($post['period_start']); 
			$getperiod_end 		= date_create($post['period_end']); 
			$period_start 		= date_format($getperiod_start,"Y-m-d");
			$period_end 		= date_format($getperiod_end,"Y-m-d");

	  		if(!empty($post['penggajian_month']) && !empty($post['penggajian_year']) && !empty($period_start) && !empty($period_end)){ 
	  			
	  			$data = [
					'bulan_penggajian' 	=> trim($post['penggajian_month']),
					'tahun_penggajian' 	=> trim($post['penggajian_year']),
					'tgl_start_absen' 	=> $period_start,
					'tgl_end_absen' 	=> $period_end,
					'status' 			=> null,
					'status_id' 		=> $isSubmitFinal ? 1 : 0,
					'rfu_reason' 		=> '',
					'reject_reason' 	=> '',
					'updated_at' 		=> date("Y-m-d H:i:s"),
					'updated_by' 		=> $_SESSION['worker']
				];
				$rs = $this->db->update("payroll_slip", $data, "id = '".$post['id']."'");

				// ambil project_id sekali saja
				$slip = $this->db->where('id', $post['id'])
				                 ->get('payroll_slip')
				                 ->row();

				$project_id = $slip->project_id;
				$bulan = trim($post['penggajian_month']);
				$tahun = trim($post['penggajian_year']);

				$bpjsHeaderCache = [];
				$employeeIdsPost = isset($post['hdnempid_gaji']) ? array_filter(array_map('intval', $post['hdnempid_gaji'])) : [];
				$pph21AdjustmentMap = $this->getPph21AdjustmentMap($bulan, $tahun, $employeeIdsPost);


				if(isset($post['hdnempid_gaji'])){
					$item_num = count($post['hdnempid_gaji']); // cek sum
					$item_len_min = min(array_keys($post['hdnempid_gaji'])); // cek min key index
					$item_len = max(array_keys($post['hdnempid_gaji'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						$hdnid = trim($post['hdnid_gaji'][$i]);
						$employee_id_post = trim($post['hdnempid_gaji'][$i] ?? '');
						$pph21Adjustment = $pph21AdjustmentMap[(int)$employee_id_post]['amount'] ?? 0;
						$pph21AdjustmentKeterangan = $pph21AdjustmentMap[(int)$employee_id_post]['keterangan'] ?? '';
						$subtotalPost = trim($post['subtotal_gaji'][$i] ?? 0);
						$bpjsKesehatanPost = trim($post['bpjs_kes_gaji'][$i] ?? 0);
						$bpjsTkPost = trim($post['bpjs_tk_gaji'][$i] ?? 0);
						$payrollPost = trim($post['payroll_gaji'][$i] ?? 0);
						$pph120Post = trim($post['pph120_gaji'][$i] ?? 0);
						$pph21Post = trim($post['pph21_gaji'][$i] ?? 0);
						$gajiBersihPost = ceil(((float)$subtotalPost - ((float)$bpjsKesehatanPost + (float)$bpjsTkPost + (float)$payrollPost + (float)$pph120Post + (float)$pph21Post) + (float)$pph21Adjustment) * 100) / 100;

						if(!empty($hdnid)){ //update
							if(isset($post['hdnempid_gaji'][$i])){
								$itemData = [
									'tunjangan_jabatan'		=> trim($post['tunj_jabatan_gaji'][$i]),
									'tunjangan_transport' 	=> trim($post['tunj_transport_gaji'][$i]),
									'tunjangan_konsumsi' 	=> trim($post['tunj_konsumsi_gaji'][$i]),
									'tunjangan_komunikasi'	=> trim($post['tunj_komunikasi_gaji'][$i]),
									'seragam' 				=> trim($post['seragam_gaji'][$i]),
									'pelatihan' 			=> trim($post['pelatihan_gaji'][$i]),
									'lain_lain' 			=> trim($post['lainlain_gaji'][$i]),
									'payroll' 				=> $payrollPost,
									'pph_120' 				=> $pph120Post,
									'total_jam_kerja' 		=> trim($post['jml_jam_kerja_gaji'][$i]),
									'total_masuk' 			=> trim($post['jml_hadir_gaji'][$i]),
									'total_tidak_masuk' 	=> trim($post['jml_tdkhadir_gaji'][$i]),
									'gaji_bulanan' 			=> trim($post['gaji_bulanan_gaji'][$i]),
									'gaji_harian' 			=> trim($post['gaji_harian_gaji'][$i]),
									'gaji' 					=> trim($post['gaji_gaji'][$i]),
									'lembur_perjam' 		=> trim($post['lembur_perjam_gaji'][$i]),
									'total_nominal_lembur' 	=> trim($post['total_nominal_lembur_gaji'][$i]),
									'total_jam_lembur' 		=> trim($post['jam_lembur_gaji'][$i]),
									'bonus' 				=> trim($post['bonus_gaji'][$i] ?? 0),
									'thr' 					=> trim($post['thr_gaji'][$i] ?? 0),
									'total_pendapatan' 		=> trim($post['ttl_pendapatan_gaji'][$i]),
									'bpjs_kesehatan' 		=> $bpjsKesehatanPost,
									'bpjs_tk' 				=> $bpjsTkPost,
									'bpjs_jht' 				=> trim($post['bpjs_jht_gaji'][$i] ?? 0),
									'bpjs_jp' 				=> trim($post['bpjs_jp_gaji'][$i] ?? 0),
									'bpjs_jkk' 				=> trim($post['bpjs_jkk_gaji'][$i] ?? 0),
									'bpjs_jkm' 				=> trim($post['bpjs_jkm_gaji'][$i] ?? 0),
									/*'absen' 				=> trim($post['absen_gaji'][$i]),*/
									'hutang' 				=> trim($post['hutang_gaji'][$i]),
									'sosial' 				=> trim($post['sosial_gaji'][$i]),
									'subtotal' 				=> $subtotalPost,
									'gaji_bersih' 			=> $gajiBersihPost,
									'pph_21' 				=> $pph21Post,
									'pph21_adjustment' 		=> $pph21Adjustment,
									'pph21_adjustment_keterangan' => $pph21AdjustmentKeterangan
									
								];

								$this->db->update("payroll_slip_detail", $itemData, "id = '".$hdnid."'");
							}
						}else{ //insert
							if(isset($post['hdnempid_gaji'][$i])){
								$itemData = [
									'payroll_slip_id'		=> $post['id'],
									'tunjangan_jabatan'		=> trim($post['tunj_jabatan_gaji'][$i]),
									'tunjangan_transport' 	=> trim($post['tunj_transport_gaji'][$i]),
									'tunjangan_konsumsi' 	=> trim($post['tunj_konsumsi_gaji'][$i]),
									'tunjangan_komunikasi'	=> trim($post['tunj_komunikasi_gaji'][$i]),
									'seragam' 				=> trim($post['seragam_gaji'][$i]),
									'pelatihan' 			=> trim($post['pelatihan_gaji'][$i]),
									'lain_lain' 			=> trim($post['lainlain_gaji'][$i]),
									'payroll' 				=> $payrollPost,
									'pph_120' 				=> $pph120Post,
									'total_jam_kerja' 		=> trim($post['jml_jam_kerja_gaji'][$i]),
									'total_masuk' 			=> trim($post['jml_hadir_gaji'][$i]),
									'total_tidak_masuk' 	=> trim($post['jml_tdkhadir_gaji'][$i]),
									'gaji_bulanan' 			=> trim($post['gaji_bulanan_gaji'][$i]),
									'gaji_harian' 			=> trim($post['gaji_harian_gaji'][$i]),
									'gaji' 					=> trim($post['gaji_gaji'][$i]),
									'lembur_perjam' 		=> trim($post['lembur_perjam_gaji'][$i]),
									'total_nominal_lembur' 	=> trim($post['total_nominal_lembur_gaji'][$i]),
									'total_jam_lembur' 		=> trim($post['jam_lembur_gaji'][$i]),
									'bonus' 				=> trim($post['bonus_gaji'][$i] ?? 0),
									'thr' 					=> trim($post['thr_gaji'][$i] ?? 0),
									'total_pendapatan' 		=> trim($post['ttl_pendapatan_gaji'][$i]),
									'bpjs_kesehatan' 		=> $bpjsKesehatanPost,
									'bpjs_tk' 				=> $bpjsTkPost,
									'bpjs_jht' 				=> trim($post['bpjs_jht_gaji'][$i] ?? 0),
									'bpjs_jp' 				=> trim($post['bpjs_jp_gaji'][$i] ?? 0),
									'bpjs_jkk' 				=> trim($post['bpjs_jkk_gaji'][$i] ?? 0),
									'bpjs_jkm' 				=> trim($post['bpjs_jkm_gaji'][$i] ?? 0),
									/*'absen' 				=> trim($post['absen_gaji'][$i]),*/
									'hutang' 				=> trim($post['hutang_gaji'][$i]),
									'sosial' 				=> trim($post['sosial_gaji'][$i]),
									'subtotal' 				=> $subtotalPost,
									'gaji_bersih' 			=> $gajiBersihPost,
									'pph_21' 				=> $pph21Post,
									'pph21_adjustment' 		=> $pph21Adjustment,
									'pph21_adjustment_keterangan' => $pph21AdjustmentKeterangan
								];

								$this->db->insert('payroll_slip_detail', $itemData);
							}
						}


						$employee_id = trim($post['hdnempid_gaji'][$i]);

						// =======================
						// CEK / BUAT HEADER BPJS
						// =======================

						if (!isset($bpjsHeaderCache[$project_id])) {

						    $bpjs_header = $this->db->where([
						        'project_id'         => $project_id,
						        'periode_gaji_bulan' => $bulan,
						        'periode_gaji_tahun' => $tahun
						    ])->get('history_bpjs')->row();

						    if (!$bpjs_header) {

						        $this->db->insert("history_bpjs", [
						            'project_id'         => $project_id,
						            'periode_gaji_bulan' => $bulan,
						            'periode_gaji_tahun' => $tahun
						        ]);

						        $bpjsHeaderCache[$project_id] = $this->db->insert_id();

						    } else {
						        $bpjsHeaderCache[$project_id] = $bpjs_header->id;
						    }
						}

						$history_bpjs_id = $bpjsHeaderCache[$project_id];


						// =======================
						// UPDATE / INSERT DETAIL
						// =======================

						$bpjs_kesehatan = trim($post['bpjs_kes_gaji'][$i]);
						$bpjs_tk        = trim($post['bpjs_tk_gaji'][$i]);

						$bpjs_detail = $this->db->where([
						    'history_bpjs_id' => $history_bpjs_id,
						    'employee_id'     => $employee_id
						])->get('history_bpjs_detail')->row();

						$data_bpjs_detail = [
						    'history_bpjs_id'        => $history_bpjs_id,
						    'employee_id'            => $employee_id,
						    'nominal_bpjs_kesehatan' => $bpjs_kesehatan,
						    'nominal_bpjs_tk'        => $bpjs_tk,
						    'tanggal_potong'         => date("Y-m-d H:i:s")
						];

						if ($bpjs_detail) {
						    $this->db->update(
						        "history_bpjs_detail",
						        $data_bpjs_detail,
						        ['id' => $bpjs_detail->id]
						    );
						} else {
						    $this->db->insert("history_bpjs_detail", $data_bpjs_detail);
						}

					}
				}
				if($isSubmitFinal) {
					$this->createApprovalPath($post['id'], $this->getPayrollApprovalTotal($post['id']));
				} else {
					$this->deleteApprovalPath($post['id']);
				}
				$this->db->trans_complete();
				if($rs){
					return [
					    "status" => true,
					    "msg" => "Data berhasil disimpan"
					];
				}else{
					return [
					    "status" => false,
					    "msg" 	 => "Data gagal disimpan"
					];
				}

	  		}else{
	  			 
	  			return [
				    "status" => false,
				    "msg" 	 => "Bulan Tahun Penggajian & Periode Absensi harus diisi"
				];
	  		}
		}else{
			return [
			    "status" => false,
			    "msg" 	 => "ID tidak ditemukan"
			];
		}
	} 


	public function getRowData($id) { 

		$karyawan_id = $_SESSION['worker'];
		$mTable = "(select a.*, b.name_indo as month_name, c.project_name,
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
					from payroll_slip a 
					left join master_month b on b.id = a.bulan_penggajian
					left join project_outsource c on c.id = a.project_id
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

		$sql = "select a.*, b.name_indo as month_name, c.project_name 
					from payroll_slip a 
					left join master_month b on b.id = a.bulan_penggajian
					left join project_outsource c on c.id = a.project_id
					where 1=1 
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getSummaryAbsen($bln, $thn){ 

		$rs = $this->db->query("select * from  summary_absen_outsource where bulan_penggajian = ".$bln." and tahun_penggajian = '".$thn."' limit 1")->result(); 

		

		return $rs;

	}

	public function getGaji($project, $bln, $thn){ 

		$rs = $this->db->query("select a.* from payroll_slip a
				where a.bulan_penggajian = ".$bln." and a.tahun_penggajian = '".$thn."' and a.project_id = ".$project." limit 1")->result(); 

		

		return $rs;

	}

	private function getPayrollComponentMap($project, $bulan, $tahun)
	{
		$components = [
			'bonus' => ['has_data' => false, 'amounts' => []],
			'thr' => ['has_data' => false, 'amounts' => []]
		];

		$bonusHeader = $this->db->query("
			select id
			from bonus_os
			where project_id = ? and periode_bulan = ? and periode_tahun = ? and status_id = 2
			order by id desc
			limit 1
		", [(int)$project, (int)$bulan, (string)$tahun])->row();

		if ($bonusHeader) {
			$components['bonus']['has_data'] = true;
			$rows = $this->db->where('bonus_os_id', $bonusHeader->id)->get('bonus_os_detail')->result();
			foreach ($rows as $row) {
				$components['bonus']['amounts'][(int)$row->employee_id] = (float)$row->bonus_amount;
			}
		}

		$thrHeader = $this->db->query("
			select id
			from thr_os
			where project_id = ? and periode_bulan = ? and periode_tahun = ? and status_id = 2
			order by id desc
			limit 1
		", [(int)$project, (int)$bulan, (string)$tahun])->row();

		if ($thrHeader) {
			$components['thr']['has_data'] = true;
			$rows = $this->db->where('thr_os_id', $thrHeader->id)->get('thr_os_detail')->result();
			foreach ($rows as $row) {
				$components['thr']['amounts'][(int)$row->employee_id] = (float)$row->thr_amount;
			}
		}

		return $components;
	}



	public function getNewGajiOSRow($row,$id=0,$project,$bln,$thn,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getGajiOSRows($id,$project,$bln,$thn,$view);
		} else { 
			$data = '';
			$no = $row+1;

			$data 	.= '<td>No Data</td>';

			
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getGajiOSRows($id,$project,$bln,$thn,$view,$print=FALSE){ 

		$dt = ''; 
		

		$rs = $this->db->query("select a.*, b.project_id, b.full_name, b.emp_code, b.id as employee_id, b.total_hari_kerja, c.bulan_penggajian, c.tahun_penggajian, c.project_id, d.sistem_lembur, d.nominal_lembur, d.rumus_lembur, b.marital_status_id
			from summary_absen_outsource_detail a left join employees b on b.id = a.emp_id
			left join summary_absen_outsource c on c.id = a.summary_absen_outsource_id
			left join data_customer d on d.id = b.cust_id
			where b.emp_source = 'outsource' and IFNULL(b.is_special_payroll,0) != 1 and b.status_id = 1
			and c.bulan_penggajian = ".$bln." and c.tahun_penggajian = '".$thn."' and c.project_id = ".$project."
			order by b.full_name asc

		")->result();

		
		$rd = $rs;
		$benefitAmounts = $this->getEmployeeBenefitDeductionOsAmounts(array_map(function($row) {
			return $row->employee_id;
		}, $rd));
		$salaryCalc = $this->getSalaryComponentsOsCalculation();
		$payrollComponents = $this->getPayrollComponentMap($project, $bln, $thn);
		$pph21AdjustmentMap = $this->getPph21AdjustmentMap($bln, $thn, array_map(function($row) {
			return $row->employee_id;
		}, $rd));

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				$benefit = isset($benefitAmounts[$f->employee_id]) ? $benefitAmounts[$f->employee_id] : [];

				$dataSlip = $this->db->query("select a.id as employee_id, a.emp_code, a.full_name, b.*, c.id as payroll_id, c.status as status_payroll
				from employees a left join payroll_slip_detail b on b.employee_id = a.id 
				left join payroll_slip c on c.id = b.payroll_slip_id
				where a.emp_source = 'outsource' and IFNULL(a.is_special_payroll,0) != 1 and a.id = '".$f->emp_id."' and a.status_id = 1 
				and c.bulan_penggajian = ".$bln." and c.tahun_penggajian = '".$thn."' and c.project_id = ".$project." ")->result(); 

				$gaji_bulanan = (float)$this->benefitOsValue($benefit, 'gaji_bulanan', 0);
				$gaji_harian_benefit = (float)$this->benefitOsValue($benefit, 'gaji_harian', 0);
				if($gaji_harian_benefit == 0 && $gaji_bulanan > 0 && $f->total_hari_kerja > 0){
					$gaji_harian_benefit = ceil($gaji_bulanan / $f->total_hari_kerja);
				}
				$ter_rate=0;

				if(!empty($dataSlip)){ /// ambil data slip
					$status_payroll = $dataSlip[0]->status_payroll;
					
					///informasi detail bpjs - dari salary_bpjs via benefit deduction
					$bpjs_jht = $dataSlip[0]->bpjs_jht;
					$bpjs_jp  = $dataSlip[0]->bpjs_jp;
					$bpjs_jkk = $dataSlip[0]->bpjs_jkk;
					$bpjs_jkm = $dataSlip[0]->bpjs_jkm;


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
					$pph_120 = $dataSlip[0]->pph_120;
					$pph_21 = $dataSlip[0]->pph_21;
					$pph_21_rate = $dataSlip[0]->pph_21_rate;
					$pph21_adjustment = $pph21AdjustmentMap[(int)$employee_id]['amount'] ?? 0;
					$pph21_adjustment_keterangan = $pph21AdjustmentMap[(int)$employee_id]['keterangan'] ?? '';
					$subtotal = $dataSlip[0]->subtotal;
					$gaji_bersih = $dataSlip[0]->gaji_bersih;
					$ter_rate= $dataSlip[0]->pph_21_rate;
					$total_pendapatan = ceil(($gaji + $tunjangan_jabatan + $tunjangan_transport + $tunjangan_konsumsi + $tunjangan_komunikasi + $bonus + $thr) * 100) / 100;
					$getTer = $this->getRateTer($total_pendapatan, $f->employee_id);
					$ter_rate = $getTer['ter_rate'];
					$pph_21 = $getTer['pph_21'];
					$subtotal = ceil(($total_pendapatan - ($seragam+$pelatihan+$lain_lain+$hutang+$sosial)) * 100) / 100;
					$gaji_bersih = ceil(($subtotal - ($bpjs_kesehatan+$bpjs_tk+$payroll+$pph_120+$pph_21) + $pph21_adjustment) * 100) / 100;

				}else{ /// ambil data dr summary absen
					$status_payroll="";
					
					$total_tidak_masuk = (int)$f->total_hari_kerja - (int)$f->total_masuk;

					$gaji = ceil(($f->total_masuk * (float)$gaji_harian_benefit) * 100) / 100;
					$tunjangan_jabatan = (float)$this->benefitOsValue($benefit, 'tunjangan_jabatan', 0);
					$tunjangan_transport = (float)$this->benefitOsValue($benefit, 'tunjangan_transportasi', 0);
					$tunjangan_konsumsi = (float)$this->benefitOsValue($benefit, 'tunjangan_konsumsi', 0);
					$tunjangan_komunikasi = (float)$this->benefitOsValue($benefit, 'tunjangan_komunikasi', 0);

					
					if($f->sistem_lembur == 'tidak_sistem_lembur'){
						$lembur_perjam = $f->nominal_lembur ?? 0;
					}else{
						$lembur_perjam = ($gaji_bulanan > 0) ? ceil(($gaji_bulanan / 173) * 100) / 100 : 0;
						if($f->rumus_lembur == 'gapok/26/7'){
							$lembur_perjam = ceil($gaji_bulanan / 26 / 7);
						}else if($f->rumus_lembur == 'gapok/20/12'){
							$lembur_perjam = ceil($gaji_bulanan / 20 / 12);
						}
					}
					

					$resolvedValues = ['gaji_bulanan' => $gaji_bulanan, 'gaji_harian' => $gaji_harian_benefit];
					// BPJS dari salary_bpjs (nama komponen dinamis dari tabel salary_bpjs)
					$bpjs_kesehatan = $this->calcBpjsKesehatan($benefit);
					$bpjs_tk_detail = $this->calcBpjsTkDetail($benefit);
					$bpjs_tk        = array_sum($bpjs_tk_detail);
					$seragam = (float)$this->benefitOsValue($benefit, 'seragam', 0);
					$pelatihan = (float)$this->benefitOsValue($benefit, 'pelatihan', 0);
					$lain_lain = (float)$this->benefitOsValue($benefit, 'lain_lain', 0);
					$payroll = (float)$this->benefitOsValue($benefit, 'payroll', 0);
					$pph_120 = (float)$this->benefitOsValue($benefit, 'pph_120', 0);
					
					/*$hari_kerja = (int) ($f->total_hari_kerja ?? 0);

					if ($hari_kerja > 0) {
					    $potongan_absen = ceil(
					        ($total_tidak_masuk * ($gaji_bulanan / $hari_kerja)) * 100
					    ) / 100;
					} else {
					    $potongan_absen = 0;
					}*/



					$sosial = (float)$this->benefitOsValue($benefit, 'sosial', 0);
					//ambil pinjaman yg masih berjalan
					$data_pinjaman = $this->db->query("select sum(nominal_cicilan_per_bulan) as ttt_hutang from loan where id_employee = '".$f->emp_id."' and status_id = 5")->result();
					$hutang=0;
					if(!empty($data_pinjaman)){
						$hutang = $data_pinjaman[0]->ttt_hutang;
					}

					/// ttl pendapatan - potongan tdk wajib
					//$subtotal = ceil(($gaji - ($potongan_absen+$hutang+$sosial)) * 100) / 100;
					$total_nominal_lembur = ceil($lembur_perjam*$f->total_jam_lembur);
					$bonus = $payrollComponents['bonus']['has_data'] ? ($payrollComponents['bonus']['amounts'][(int)$f->employee_id] ?? 0) : 0;
					$thr = $payrollComponents['thr']['has_data'] ? ($payrollComponents['thr']['amounts'][(int)$f->employee_id] ?? 0) : 0;
					$total_pendapatan = ceil(($gaji + $tunjangan_jabatan + $tunjangan_transport + $tunjangan_konsumsi + $tunjangan_komunikasi + $bonus + $thr) * 100) / 100;
					$getTer = $this->getRateTer($total_pendapatan, $f->employee_id);
					$ter_rate = $getTer['ter_rate'];
					$pph_21 = $getTer['pph_21'];
					$pph21_adjustment = $pph21AdjustmentMap[(int)$f->employee_id]['amount'] ?? 0;
					$pph21_adjustment_keterangan = $pph21AdjustmentMap[(int)$f->employee_id]['keterangan'] ?? '';
					$subtotal = ceil(($total_pendapatan - ($seragam+$pelatihan+$lain_lain+$hutang+$sosial)) * 100) / 100;

					/// subtotal - potongan wajib
					$gaji_bersih = ceil(($subtotal - ($bpjs_kesehatan+$bpjs_tk+$payroll+$pph_120+$pph_21) + $pph21_adjustment) * 100) / 100;

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
					$pph_120 = $pph_120;
					$subtotal = $subtotal;
					$gaji_bersih = $gaji_bersih;
				}

				
				if(!$view){ 
					

					$dt .= '<tr>';

					
					$dt .= '<td>'.$emp_code.'</td>';
					$dt .= '<td>'.$full_name.'<input type="hidden" id="hdnempid_gaji" name="hdnempid_gaji['.$row.']" value="'.$employee_id.'"/></td>';

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

					$dt .= '<td>'.$this->return_build_txt($pph_120,'pph120_gaji['.$row.']','','pph120_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setGajiBersih(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($pph_21,'pph21_gaji['.$row.']','','pph21_gaji','text-align: right;','data-id="'.$row.'" readonly ').'<input type="hidden" id="pph21_rate" name="pph21_rate['.$row.']" value="'.$ter_rate.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($pph21_adjustment,'pph21_adjustment_gaji['.$row.']','','pph21_adjustment_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($pph21_adjustment_keterangan,'pph21_adjustment_keterangan_gaji['.$row.']','','pph21_adjustment_keterangan_gaji','','data-id="'.$row.'" readonly ').'</td>';

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
					$dt .= '<td>'.$pph_120.'</td>';
					$dt .= '<td>'.$pph_21.'</td>';
					$dt .= '<td>'.$pph21_adjustment.'</td>';
					$dt .= '<td>'.$pph21_adjustment_keterangan.'</td>';
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
