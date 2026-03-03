<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lms_course_menu_model extends MY_Model
{
	/* Module */
	protected $folder_name				= "training_development/lms_course_menu";
	protected $table_name 				= _PREFIX_TABLE . "lms_course";
	protected $primary_key 				= "id";


	/* upload */
	/*protected $attachment_folder	= "./uploads/employee";*/
	protected $allow_type			= "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
	protected $allow_size			= "0"; // 0 for limit by default php conf (in Kb)


	function __construct()
	{
		parent::__construct();
	}


	public function get_cards_list($search = '', $category = '', $limit = 9, $offset = 0)
	{
		$baseSql = '(select 
        a.*,
        if(a.is_active =1,"Active","Not Active") as is_active_desc,
        GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ", ") AS department_names
      FROM lms_course a
      LEFT JOIN departments d ON FIND_IN_SET(d.id, a.department_ids)
      GROUP BY a.id
    ) dt';

		// WHERE
		$where = " WHERE 1=1 ";
		$params = [];

		if (!empty($search)) {
			$where .= " AND (dt.course_name LIKE ? OR dt.department_names LIKE ? OR dt.description LIKE ? OR dt.category LIKE ?) ";
			$like = "%" . $search . "%";
			$params = array_merge($params, [$like, $like, $like, $like]);
		}

		if (!empty($category)) {
			$where .= " AND dt.category = ? ";
			$params[] = $category;
		}

		// totals (filtered)
		$filteredTotal = $this->db->query("SELECT COUNT(dt.id) AS total FROM $baseSql $where", $params)->row()->total;

		// total (all)
		$totalAll = $this->db->query("SELECT COUNT(dt.id) AS total FROM $baseSql WHERE 1=1")->row()->total;

		// stats (all)
		$active = $this->db->query("SELECT COUNT(dt.id) AS total FROM $baseSql WHERE dt.is_active = 1")->row()->total;
		$inactive = $this->db->query("SELECT COUNT(dt.id) AS total FROM $baseSql WHERE dt.is_active = 0")->row()->total;

		// data rows
		$sql = "SELECT dt.id, dt.course_name, dt.category, dt.department_names, dt.description, dt.is_active, dt.is_active_desc
            FROM $baseSql
            $where
            ORDER BY dt.id DESC
            LIMIT $limit OFFSET $offset";

		$rows = $this->db->query($sql, $params)->result_array();

		return [
			"total" => (int)$totalAll,
			"active" => (int)$active,
			"inactive" => (int)$inactive,
			"filtered_total" => (int)$filteredTotal,
			"records" => $rows
		];
	}


	// fix
	public function get_list_data()
	{
		$aColumns = [
			NULL,
			NULL,
			'dt.id',
			'dt.course_name',
			'dt.description',
			'dt.category',
			'dt.is_active_desc',
			'dt.department_names'
		];

		/*$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr = ' where ao.created_by = "' . $karyawan_id . '" or ao.direct_id = "' . $karyawan_id . '" or ao.is_approver_view = 1  ';
		}*/


		$sIndexColumn = $this->primary_key;


		$sTable = '(select 
				    a.*, if(a.is_active =1,"Active","Not Active") as is_active_desc,
				    GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ", ") AS department_names
					FROM lms_course a
					LEFT JOIN departments d 
					    ON FIND_IN_SET(d.id, a.department_ids)
					GROUP BY a.id
					)dt';



		/* Paging */
		$sLimit = "";
		if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
			$sLimit = "LIMIT " . ($_GET['iDisplayStart']) . ", " .
				($_GET['iDisplayLength']);
		}

		/* Ordering */
		$sOrder = "";
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = "ORDER BY  ";
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					$srcCol = $aColumns[intval($_GET['iSortCol_' . $i])];
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sOrder .= trim($pieces[0]) . "
						" . ($_GET['sSortDir_' . $i]) . ", ";
					} else {
						$sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
						" . ($_GET['sSortDir_' . $i]) . ", ";
					}
				}
			}

			$sOrder = substr_replace($sOrder, "", -2);
			if ($sOrder == "ORDER BY") {
				$sOrder = "";
			}
		}

		/* Filtering */
		$sWhere = " WHERE 1 = 1 ";
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
			$sWhere .= "AND (";
			foreach ($aColumns as $c) {
				if ($c !== NULL) {
					$srcCol = $c;
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0]) . " LIKE '%" . ($_GET['sSearch']) . "%' OR ";
					} else {
						$sWhere .= $c . " LIKE '%" . ($_GET['sSearch']) . "%' OR ";
					}
				}
			}

			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		for ($i = 0; $i < count($aColumns); $i++) {
			if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && isset($_GET['sSearch_' . $i]) && $_GET['sSearch_' . $i] != '') {
				if ($sWhere == "") {
					$sWhere = "WHERE ";
				} else {
					$sWhere .= " AND ";
				}
				$srcString = $_GET['sSearch_' . $i];
				$findme   = '|';
				$pos = strpos($srcString, $findme);
				if ($pos !== false) {
					$srcKey = "";
					$pieces = explode($findme, trim($srcString));
					foreach ($pieces as $value) {
						if (!empty($srcKey)) {
							$srcKey .= ",";
						}
						$srcKey .= "'" . $value . "'";
					}

					$srcCol = $aColumns[$i];
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0]) . " IN (" . $srcKey . ") ";
					} else {
						$sWhere .= $aColumns[$i] . " IN (" . $srcKey . ") ";
					}
				} else {
					$srcCol = $aColumns[$i];
					$findme   = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0]) . " LIKE '%" . ($srcString) . "%' ";
					} else {
						$sWhere .= $aColumns[$i] . " LIKE '%" . ($srcString) . "%' ";
					}
				}
			}
		}



		/* Get data to display */
		$filtered_cols = array_filter($aColumns, [$this, 'is_not_null']); // Filtering NULL value
		$sQuery = "
		SELECT  SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $filtered_cols)) . "
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
			SELECT COUNT(" . $sIndexColumn . ") AS total
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

		foreach ($rResult as $row) {


			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1") {
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #112D80; border-color: #112D80;" href="javascript:void(0);" onclick="detail(' . "'" . $row->id . "'" . ')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1") {
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit(' . "'" . $row->id . "'" . ')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1") {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="' . $row->id . '">';
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting(' . "'" . $row->id . "'" . ')" role="button"><i class="fa fa-trash"></i></a>';
			}



			array_push($output["aaData"], array(
				$delete_bulk,
				'<div class="action-buttons">
					' . $detail . '
					' . $edit . '
					' . $delete . '
				</div>',
				$row->id,
				$row->course_name,
				$row->category,
				$row->department_names,
				$row->description,
				$row->is_active_desc


			));
		}

		echo json_encode($output);
	}

	// filltering null value from array
	public function is_not_null($val)
	{
		return !is_null($val);
	}

	public function delete($id = "")
	{
		if (isset($id) && $id <> "") {
			//$this->db->trans_off(); // Disable transaction
			$this->db->trans_start(); // set "True" for query will be rolled back
			$this->db->where([$this->primary_key => $id])->delete($this->table_name);
			$this->db->trans_complete();

			return $rs = $this->db->trans_status();
		} else return null;
	}

	// delete multi items action
	public function bulk($id = "")
	{
		if (is_array($id) && count($id)) {
			$err = '';
			foreach ($id as $pid) {
				//$this->db->trans_off(); // Disable transaction
				$this->db->trans_start(); // set "True" for query will be rolled back
				$this->db->where([$this->primary_key => $pid])->delete($this->table_name);
				$this->db->trans_complete();
				$deleted = $this->db->trans_status();
				if ($deleted == false) {
					if (!empty($err)) $err .= ", ";
					$err .= $pid;
				}
			}

			$data = array();
			if (empty($err)) {
				$data['status'] = TRUE;
			} else {
				$data['status'] = FALSE;
				$data['err'] = '<br/>ID : ' . $err;
			}

			return $data;
		} else return null;
	}




	public function add_data($post)
	{
		if (!empty($post['course_name'])) {

			$department_ids = [];
			if (isset($post['departments']) && is_array($post['departments'])) {
				$department_ids = $post['departments'];
			}


			$data = [
				'course_name'     => trim($post['course_name']),
				'description'     => trim($post['desc']),
				'category'        => trim($post['category']),
				'department_ids'  => implode(',', $department_ids),
				'is_active'       => trim($post['is_active'])
			];

			$rs = $this->db->insert($this->table_name, $data);
			$lastId = $this->db->insert_id();

			if ($rs && !empty($department_ids)) {


				$employees = $this->db
					->select('id')
					->from('employees')
					->where_in('department_id', $department_ids)
					->get()
					->result();

				if (!empty($employees)) {
					foreach ($employees as $emp) {

						$exists = $this->db
							->where('course_id', $lastId)
							->where('employee_id', $emp->id)
							->get('lms_course_progress')
							->num_rows();


						if ($exists == 0) {
							$dataprogress = [
								'course_id'   => $lastId,
								'employee_id' => $emp->id
							];
							$this->db->insert('lms_course_progress', $dataprogress);
						}
					}
				}
			}

			return [
			    "status" => true,
			    "msg"    => "Data berhasil disimpan"
			];
		}else{
			return [
			    "status" => false,
			    "msg"    => "Data gagal disimpan"
			];
		}

	}


	public function edit_data($post)
	{

		if (!empty($post['id'])) {

			$department_ids = '';
			if (isset($post['departments']) && is_array($post['departments'])) {
				$department_ids = implode(',', $post['departments']);
			}


			$data = [
				'course_name' 		=> trim($post['course_name']),
				'description' 		=> trim($post['desc']),
				'category'			=> trim($post['category']),
				'department_ids' 	=> $department_ids,
				'is_active' 		=> trim($post['is_active'])

			];

			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);



			if($rs){
				return [
				    "status" => true,
				    "msg"    => "Data berhasil disimpan"
				];
			}else{
				return [
				    "status" => false,
				    "msg"    => "Data gagal disimpan"
				];
			}
		} else{
			return [
			    "status" => false,
			    "msg"    => "Data gagal disimpan"
			];
		}
	}


	public function getRowData($id)
	{
		$mTable = '(select 
				    a.*, if(a.is_active =1,"Active","Not Active") as is_active_desc,
				    GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ", ") AS department_names
					FROM lms_course a
					LEFT JOIN departments d 
					    ON FIND_IN_SET(d.id, a.department_ids)
					GROUP BY a.id
					)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();



		return $rs;
	}

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'course_name' 		=> $v["B"],
				'category' 			=> $v["C"],
				'department_ids' 	=> $v["D"],
				'description' 		=> $v["E"],
				'is_active' 		=> $v["F"]

			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .= ",baris " . $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		/*$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr=' where a.created_by = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}*/



		$sql = 'select 
				    a.*, if(a.is_active =1,"Active","Not Active") as is_active_desc,
				    GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ", ") AS department_names
				FROM lms_course a
				LEFT JOIN departments d 
				    ON FIND_IN_SET(d.id, a.department_ids)
				GROUP BY a.id
				order by a.id asc

		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}
}
