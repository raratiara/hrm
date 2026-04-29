<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Benefit_deduction_menu_model extends MY_Model
{
	protected $folder_name	= "emp_management/benefit_deduction_menu";
	protected $table_name 	= _PREFIX_TABLE."employee_benefit_deduction";
	protected $primary_key 	= "id";

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
			'dt.emp_code',
			'dt.full_name',
			'dt.job_title_name',
			'dt.department_name',
			'dt.status_name'
		];

		$karyawan_id = $_SESSION['worker'];
		$whr = '';
		if($_SESSION['role'] != 1 && $_SESSION['role'] != 4){
			$whr = ' and (a.id = "'.$karyawan_id.'" or a.direct_id = "'.$karyawan_id.'") ';
		}

		$sIndexColumn = "id";
		$sTable = '(select a.id, a.emp_code, a.full_name, b.name as job_title_name, c.name as department_name,
					if(a.status_id = 1, "Active", "Not Active") as status_name
					from employees a
					left join master_job_title b on b.id = a.job_title_id
					left join departments c on c.id = a.department_id
					where a.emp_source = "internal" and a.status_id = 1 '.$whr.'
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
					$findme = ' as ';
					$pos = strpos($srcCol, $findme);
					if($pos !== false){
						$pieces = explode($findme, trim($srcCol));
						$sOrder .= trim($pieces[0])." ".($_GET['sSortDir_'.$i]).", ";
					}else{
						$sOrder .= $srcCol." ".($_GET['sSortDir_'.$i]).", ";
					}
				}
			}

			$sOrder = substr_replace($sOrder, "", -2);
			if($sOrder == "ORDER BY"){
				$sOrder = "";
			}
		}

		$sWhere = " WHERE 1 = 1 ";
		if(isset($_GET['sSearch']) && $_GET['sSearch'] != ""){
			$sWhere .= "AND (";
			foreach($aColumns as $c){
				if($c !== NULL){
					$srcCol = $c;
					$findme = ' as ';
					$pos = strpos($srcCol, $findme);
					if($pos !== false){
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0])." LIKE '%".($_GET['sSearch'])."%' OR ";
					}else{
						$sWhere .= $c." LIKE '%".($_GET['sSearch'])."%' OR ";
					}
				}
			}

			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		for($i=0 ; $i<count($aColumns) ; $i++) {
			if(isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && isset($_GET['sSearch_'.$i]) && $_GET['sSearch_'.$i] != ''){
				$sWhere .= " AND ";
				$srcCol = $aColumns[$i];
				$findme = ' as ';
				$pos = strpos($srcCol, $findme);
				if($pos !== false){
					$pieces = explode($findme, trim($srcCol));
					$sWhere .= trim($pieces[0])." LIKE '%".($_GET['sSearch_'.$i])."%' ";
				}else{
					$sWhere .= $srcCol." LIKE '%".($_GET['sSearch_'.$i])."%' ";
				}
			}
		}

		$filtered_cols = array_filter($aColumns, [$this, 'is_not_null']);
		$sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $filtered_cols))."
					FROM $sTable
					$sWhere
					$sOrder
					$sLimit";
		$rResult = $this->db->query($sQuery)->result();

		$aResultFilterTotal = $this->db->query("SELECT FOUND_ROWS() AS filter_total")->row();
		$iFilteredTotal = $aResultFilterTotal->filter_total;
		$aResultTotal = $this->db->query("SELECT COUNT(".$sIndexColumn.") AS total FROM $sTable")->row();
		$iTotal = $aResultTotal->total;

		$output = [
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => []
		];

		foreach($rResult as $row){
			$edit = "";
			if(_USER_ACCESS_LEVEL_UPDATE == "1"){
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}

			if(_USER_ACCESS_LEVEL_DELETE == "1"){
				array_push($output["aaData"], [
					'',
					'<div class="action-buttons">'.$edit.'</div>',
					$row->id,
					$row->emp_code,
					$row->full_name,
					$row->job_title_name,
					$row->department_name,
					$row->status_name
				]);
			}else{
				array_push($output["aaData"], [
					'<div class="action-buttons">'.$edit.'</div>',
					$row->id,
					$row->emp_code,
					$row->full_name,
					$row->job_title_name,
					$row->department_name,
					$row->status_name
				]);
			}
		}

		echo json_encode($output);
	}

	public function is_not_null($val)
	{
		return !is_null($val);
	}

	public function getRowData($id)
	{
		$employee = $this->db->query("select a.id, a.emp_code, a.full_name, b.name as job_title_name, c.name as department_name
					from employees a
					left join master_job_title b on b.id = a.job_title_id
					left join departments c on c.id = a.department_id
					where a.emp_source = 'internal' and a.id = '".$id."'")->row();

		if(empty($employee)){
			return false;
		}

		$components = $this->db->query("select id, name, type, order_num
					from salary_components
					where is_active = 1
					order by case when lower(type) = 'earning' then 0 else 1 end asc, order_num asc, name asc")->result();

		$saved = $this->getSavedComponents($id);
		foreach($components as $component){
			$component->amount = isset($saved[$component->id]) ? $saved[$component->id] : '';
		}

		return [
			'employee' => $employee,
			'components' => $components
		];
	}

	private function getSavedComponents($employee_id)
	{
		$result = [];
		if(!$this->db->table_exists('employee_benefit_deduction')){
			return $result;
		}

		$componentColumn = $this->getComponentColumn();
		$amountColumn = $this->getAmountColumn();
		$rows = $this->db->query("select ".$componentColumn." as salary_component_id, ".$amountColumn." as amount
					from employee_benefit_deduction
					where employee_id = '".$employee_id."'")->result();

		foreach($rows as $row){
			$result[$row->salary_component_id] = $row->amount;
		}

		return $result;
	}

	private function getComponentColumn()
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

		return 'salary_component_id';
	}

	private function getAmountColumn()
	{
		if($this->db->field_exists('amount', 'employee_benefit_deduction')){
			return 'amount';
		}

		if($this->db->field_exists('nominal', 'employee_benefit_deduction')){
			return 'nominal';
		}

		if($this->db->field_exists('value', 'employee_benefit_deduction')){
			return 'value';
		}

		return 'amount';
	}

	public function add_data($post)
	{
		return $this->edit_data($post);
	}

	public function edit_data($post)
	{
		if(empty($post['id'])){
			return false;
		}

		if(!$this->db->table_exists('employee_benefit_deduction')){
			return false;
		}

		$employee_id = trim($post['id']);
		$componentColumn = $this->getComponentColumn();
		$amountColumn = $this->getAmountColumn();
		$components = isset($post['component_id']) ? $post['component_id'] : [];
		$amounts = isset($post['amount']) ? $post['amount'] : [];

		$this->db->trans_start();

		foreach($components as $row => $component_id){
			$component_id = trim($component_id);
			$amount = isset($amounts[$row]) ? trim(str_replace(',', '', $amounts[$row])) : '';

			$existing = $this->db->query("select id from employee_benefit_deduction
						where employee_id = '".$employee_id."'
						and ".$componentColumn." = '".$component_id."'
						limit 1")->result();

			$data = [
				'employee_id' => $employee_id,
				$componentColumn => $component_id,
				$amountColumn => $amount
			];

			if(!empty($existing)){
				if($this->db->field_exists('updated_at', 'employee_benefit_deduction')){
					$data['updated_at'] = date("Y-m-d H:i:s");
				}
				$this->db->update('employee_benefit_deduction', $data, ['id' => $existing[0]->id]);
			}else{
				if($amount === ''){
					continue;
				}

				if($this->db->field_exists('created_at', 'employee_benefit_deduction')){
					$data['created_at'] = date("Y-m-d H:i:s");
				}
				$this->db->insert('employee_benefit_deduction', $data);
			}
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function delete($id = "")
	{
		return null;
	}
}
