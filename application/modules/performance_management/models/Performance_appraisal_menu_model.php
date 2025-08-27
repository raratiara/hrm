<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Performance_appraisal_menu_model extends MY_Model
{
	/* Module */
	protected $folder_name = "performance_management/performance_appraisal_menu";
	protected $table_name = _PREFIX_TABLE . "performance_appraisal";
	protected $primary_key = "id";




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
			'dt.full_name',
			'dt.year',
			'dt.status_name',
			'dt.direct_id',
			'dt.employee_id',
			'dt.rfu_reason',
			'dt.score'
		];

		$getdata = $this->db->query("select * from user where user_id = '" . $_SESSION['id'] . "'")->result();
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr = '';
		if ($getdata[0]->id_groups != 1) { //bukan super user
			$whr = ' where a.employee_id = "' . $karyawan_id . '" or b.direct_id = "' . $karyawan_id . '" ';
		}


		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name, b.direct_id,
					(case 
					when a.status_id = 0 then "Draft"
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "RFU"
					else ""
					 end) as status_name
					from performance_appraisal a left join employees b on b.id = a.employee_id
					' . $whr . '
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
					$findme = ' as ';
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
					$findme = ' as ';
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
				$findme = '|';
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
					$findme = ' as ';
					$pos = strpos($srcCol, $findme);
					if ($pos !== false) {
						$pieces = explode($findme, trim($srcCol));
						$sWhere .= trim($pieces[0]) . " IN (" . $srcKey . ") ";
					} else {
						$sWhere .= $aColumns[$i] . " IN (" . $srcKey . ") ";
					}
				} else {
					$srcCol = $aColumns[$i];
					$findme = ' as ';
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
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #343851; border-color: #343851;" href="javascript:void(0);" onclick="detail(' . "'" . $row->id . "'" . ')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1" && ((($row->status_name == 'Draft' || $row->status_name == 'RFU') && $karyawan_id == $row->employee_id) || ($row->status_name == 'Waiting Approval' && $row->direct_id == $karyawan_id))) {
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit(' . "'" . $row->id . "'" . ')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1") {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="' . $row->id . '">';
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting(' . "'" . $row->id . "'" . ')" role="button"><i class="fa fa-trash"></i></a>';
			}

			/*$reject=""; 
			$approve="";
			if($row->status_name == 'Waiting Approval' && $row->direct_id == $karyawan_id){
				$reject = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="reject('."'".$row->id."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" href="javascript:void(0);" onclick="approve('."'".$row->id."'".')" role="button"><i class="fa fa-check"></i></a>';
			}*/

			array_push($output["aaData"], array(
				$delete_bulk,
				'<div class="action-buttons">
					' . $detail . '
					' . $edit . '
					' . $delete . '
				</div>',
				$row->id,
				$row->full_name,
				$row->year,
				$row->status_name,
				$row->score,
				$row->rfu_reason

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
		} else
			return null;
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
					if (!empty($err))
						$err .= ", ";
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
		} else
			return null;
	}


	public function add_data($post)
	{

		if (!empty($post['employee'])) {

			$data_appraisal = $this->db->query("select * from performance_appraisal where employee_id = '" . $post['employee'] . "' and year = '" . $post['year'] . "'")->result();

			if (empty($data_appraisal)) {
				$data = [
					'employee_id' => trim($post['employee']),
					'year' => trim($post['year']),
					'status_id' => 1, //waiting approval
					'created_at' => date("Y-m-d H:i:s")

				];
				$rs = $this->db->insert($this->table_name, $data);
				$lastId = $this->db->insert_id();

				if ($rs) {
					if (isset($post['hardskill'])) {
						$item_num = count($post['hardskill']); // cek sum
						$item_len_min = min(array_keys($post['hardskill'])); // cek min key index
						$item_len = max(array_keys($post['hardskill'])); // cek max key index
					} else {
						$item_num = 0;
					}

					if ($item_num > 0) {
						for ($i = $item_len_min; $i <= $item_len; $i++) {

							if (isset($post['hardskill'][$i])) {
								$itemData = [
									'performance_appraisal_id' => $lastId,
									'hardskill' => trim($post['hardskill'][$i]),
									'notes' => trim($post['notes'][$i]),
									'score_emp' => trim($post['score_emp'][$i]),
									'score_direct' => trim($post['score_direct'][$i]),
									'final_score' => trim($post['final_score'][$i])
								];

								$this->db->insert('performance_appraisal_hardskill', $itemData);
							}
						}
					}


					//softskill
					if (isset($post['hdnid_mastersoftskill'])) {
						$item_num2 = count($post['hdnid_mastersoftskill']); // cek sum
						$item_len_min2 = min(array_keys($post['hdnid_mastersoftskill'])); // cek min key index
						$item_len2 = max(array_keys($post['hdnid_mastersoftskill'])); // cek max key index
					} else {
						$item_num2 = 0;
					}

					if ($item_num2 > 0) {
						for ($i = $item_len_min2; $i <= $item_len2; $i++) {

							if (isset($post['hdnid_mastersoftskill'][$i])) {
								$itemData2 = [
									'performance_appraisal_id' => $lastId,
									'softskill_id' => trim($post['hdnid_mastersoftskill'][$i]),
									'notes' => trim($post['notes_softskill'][$i]),
									'score_emp' => trim($post['score_emp_softskill'][$i]),
									'score_direct' => trim($post['score_direct_softskill'][$i]),
									'final_score' => trim($post['final_score_softskill'][$i])
								];

								$this->db->insert('performance_appraisal_softskill', $itemData2);
							}
						}
					}
					return $rs;
				}
			} else
				return null;

		} else
			return null;

	}

	public function edit_data($post)
	{

		if (!empty($post['id'])) {
			$rowdata = $this->db->query("select * from performance_appraisal where id = '" . $post['id'] . "' ")->result();
			$next_status = '';
			if ($rowdata[0]->status_id == 0 || $rowdata[0]->status_id == 3) { //draft atau rfu
				$next_status = 1; //waiting approval direct
			} else if ($rowdata[0]->status_id == 1) {
				$ttl_score = ($post['hdnttl_final_score']+$post['hdnttl_final_score_softskill'])/2; 
				$getScore = $this->db->query("select * from master_kpi_score where '".$ttl_score."' >= start_val and '".$ttl_score."' <= end_val ")->result();
				if(!empty($getScore)){
					$score = $getScore[0]->name;
				}else{
					$score = '-';
				}
				
				$next_status = 2; //approved
			}

			if($next_status == 2){
				$data = [
					'employee_id' => trim($post['employee']),
					'year' => trim($post['year']),
					'updated_at' => date("Y-m-d H:i:s"),
					'total_final_score' => $post['hdnttl_final_score'],
					'total_final_score_softskill' => $post['hdnttl_final_score_softskill'],
					'status_id' => $next_status,
					'rfu_reason' => '',
					'score_val' => $ttl_score,
					'score' => $score
				];
			}else{
				$data = [
					'employee_id' => trim($post['employee']),
					'year' => trim($post['year']),
					'updated_at' => date("Y-m-d H:i:s"),
					'total_final_score' => $post['hdnttl_final_score'],
					'total_final_score_softskill' => $post['hdnttl_final_score_softskill'],
					'status_id' => $next_status,
					'rfu_reason' => ''

				];
			}
			
			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

			if ($rs) {
				if (isset($post['hardskill'])) {
					$item_num = count($post['hardskill']); // cek sum
					$item_len_min = min(array_keys($post['hardskill'])); // cek min key index
					$item_len = max(array_keys($post['hardskill'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if ($item_num > 0) {
					for ($i = $item_len_min; $i <= $item_len; $i++) {
						$hdnid = $post['hdnid'][$i];
						if (isset($post['hardskill'][$i])) {
							if ($hdnid != '') { //update
								$itemData = [
									'hardskill' => trim($post['hardskill'][$i]),
									'notes' => trim($post['notes'][$i]),
									'score_emp' => trim($post['score_emp'][$i]),
									'score_direct' => trim($post['score_direct'][$i]),
									'weight' => trim($post['weight'][$i]),
									'final_score' => trim($post['final_score'][$i])
								];
								$this->db->update('performance_appraisal_hardskill', $itemData, "id = '" . $hdnid . "'");
							} else { //insert
								$itemData = [
									'performance_appraisal_id' => $post['id'],
									'hardskill' => trim($post['hardskill'][$i]),
									'notes' => trim($post['notes'][$i]),
									'score_emp' => trim($post['score_emp'][$i]),
									'score_direct' => trim($post['score_direct'][$i]),
									'weight' => trim($post['weight'][$i]),
									'final_score' => trim($post['final_score'][$i])
								];

								$this->db->insert('performance_appraisal_hardskill', $itemData);
							}
						}
					}
				}


				//softskill
				if (isset($post['hdnid_mastersoftskill'])) {
					$item_num2 = count($post['hdnid_mastersoftskill']); // cek sum
					$item_len_min2 = min(array_keys($post['hdnid_mastersoftskill'])); // cek min key index
					$item_len2 = max(array_keys($post['hdnid_mastersoftskill'])); // cek max key index
				} else {
					$item_num2 = 0;
				}

				if ($item_num2 > 0) {
					for ($i = $item_len_min2; $i <= $item_len2; $i++) {
						$hdnid_softskill = $post['hdnid_softskill'][$i];
						if (isset($post['hdnid_mastersoftskill'][$i])) {
							if ($hdnid_softskill != '') { //update
								$itemData2 = [
									'softskill_id' => trim($post['hdnid_mastersoftskill'][$i]),
									'notes' => trim($post['notes_softskill'][$i]),
									'score_emp' => trim($post['score_emp_softskill'][$i]),
									'score_direct' => trim($post['score_direct_softskill'][$i]),
									'final_score' => trim($post['final_score_softskill'][$i])
								];

								$this->db->update('performance_appraisal_softskill', $itemData2, "id = '" . $hdnid_softskill . "'");
							} else { //insert
								$itemData2 = [
									'performance_appraisal_id' => $post['id'],
									'softskill_id' => trim($post['hdnid_mastersoftskill'][$i]),
									'notes' => trim($post['notes_softskill'][$i]),
									'score_emp' => trim($post['score_emp_softskill'][$i]),
									'score_direct' => trim($post['score_direct_softskill'][$i]),
									'final_score' => trim($post['final_score_softskill'][$i])
								];

								$this->db->insert('performance_appraisal_softskill', $itemData2);
							}
						}
					}
				}


				return $rs;
			} else
				return null;

		} else
			return null;
	}

	public function getRowData($id)
	{
		$mTable = '(select a.*, b.full_name, b.direct_id,
					(case 
					when a.status_id = 0 then "Draft"
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "RFU"
					else ""
					 end) as status_name
					from performance_appraisal a left join employees b on b.id = a.employee_id
					)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();

		$getdata = $this->db->query("select * from user where user_id = '" . $_SESSION['id'] . "'")->result();
		$karyawan_id = $getdata[0]->id_karyawan;

		$absensi = $this->db->query("select * from time_attendances where employee_id = '" . $rs->employee_id . "' and year(date_attendance) = '" . $rs->year . "' and leave_absences_id is null ")->result();
		$ttl_kehadiran = 0;
		if (!empty($absensi)) {
			$ttl_kehadiran = count($absensi);
		}

		$ijin = $this->db->query("select a.*, b.status_approval from time_attendances a 
					left join leave_absences b on b.id = a.leave_absences_id where a.employee_id = '" . $rs->employee_id . "' and year(a.date_attendance) = '" . $rs->year . "' and a.leave_absences_id is not null and b.status_approval = 2 ")->result();
		$ttl_ijin = 0;
		if (!empty($ijin)) {
			$ttl_ijin = count($ijin);
		}

		$telat = $this->db->query("select * from time_attendances where employee_id = '" . $rs->employee_id . "' and year(date_attendance) = '" . $rs->year . "' and leave_absences_id is null and is_late = 'Y' ")->result();
		$ttl_telat = 0;
		if (!empty($telat)) {
			$ttl_telat = count($telat);
		}



		$isdirect = 0;
		if ($rs->direct_id == $karyawan_id) {
			$isdirect = 1;
		}

		$data = array(
			'rowdata' => $rs,
			'isdirect' => $isdirect,
			'ttl_kehadiran' => $ttl_kehadiran,
			'ttl_ijin' => $ttl_ijin,
			'ttl_telat' => $ttl_telat
		);

		return $data;
	}

	public function import_data($list_data)
	{
		$i = 0;

		foreach ($list_data as $k => $v) {
			$i += 1;

			$data = [
				'employee_id' => $v["B"],
				'year' => $v["C"],
				'status_id' => 1,
				'created_at' => date("Y-m-d H:i:s")

			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs)
				$error .= ",baris " . $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$getdata = $this->db->query("select * from user where user_id = '" . $_SESSION['id'] . "'")->result();
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr = '';
		if ($getdata[0]->id_groups != 1) { //bukan super user
			$whr = ' where a.employee_id = "' . $karyawan_id . '" or b.direct_id = "' . $karyawan_id . '" ';
		}



		$sql = 'select a.*, b.full_name, b.direct_id,
				(case 
				when a.status_id = 0 then "Draft"
				when a.status_id = 1 then "Waiting Approval"
				when a.status_id = 2 then "Approved"
				when a.status_id = 3 then "RFU"
				else ""
				 end) as status_name
				from performance_appraisal a left join employees b on b.id = a.employee_id
				' . $whr . '
				order by a.id asc

		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getNewHardskillRow($row, $id = 0, $view = FALSE)
	{
		if ($id > 0) {
			$data = $this->getHardskillRows($id, $view);
		} else {
			$data = '';
			$no = $row + 1;

			$data .= '<td>' . $no . '<input type="hidden" id="hdnid' . $row . '" name="hdnid[' . $row . ']" value=""/></td>';

			$data .= '<td>' . $this->return_build_txt('', 'hardskill[' . $row . ']', '', 'hardskill', 'text-align: right;', 'data-id="' . $row . '" ') . '</td>';
			$data .= '<td>' . $this->return_build_txtarea('', 'notes[' . $row . ']', '', 'notes', 'text-align: right;', 'data-id="' . $row . '" ') . '</td>';
			$data .= '<td>' . $this->return_build_txt('', 'weight[' . $row . ']', '', 'weight', 'text-align: right;', 'data-id="' . $row . '" onkeyup="set_weight(this)" ') . '</td>';
			$data .= '<td>' . $this->return_build_txt('', 'score_emp[' . $row . ']', '', 'score_emp', 'text-align: right;', 'data-id="' . $row . '" onkeyup="set_score_emp(this)" ') . '</td>';
			$data .= '<td>' . $this->return_build_txt('', 'score_direct[' . $row . ']', '', 'score_direct', 'text-align: right;', 'data-id="' . $row . '" onkeyup="set_score_direct(this)" readonly ') . '</td>';
			$data .= '<td>' . $this->return_build_txt('', 'final_score[' . $row . ']', '', 'final_score', 'text-align: right;', 'data-id="' . $row . '" ') . '</td>';

			$hdnid = '';
			$data .= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del(\'' . $row . '\',\'' . $hdnid . '\')" value="Delete"></td>';
		}

		return $data;
	}

	// Generate expenses item rows for edit & view
	public function getHardskillRows($id, $view, $print = FALSE)
	{

		$getdata = $this->db->query("select * from user where user_id = '" . $_SESSION['id'] . "'")->result();
		$karyawan_id = $getdata[0]->id_karyawan;

		$dt = '';

		$rs = $this->db->query("select * from performance_appraisal_hardskill where performance_appraisal_id = '" . $id . "' ")->result();
		$rd = $rs;

		$row = 0;
		if (!empty($rd)) {
			$status = $this->db->query("select a.*, b.direct_id from performance_appraisal a left join employees b on b.id = a.employee_id where a.id = '" . $id . "' ")->result();
			$isdirect = 0;
			$isemp = 0;
			if ($status[0]->direct_id == $karyawan_id) {
				$isdirect = 1;
			}
			if ($status[0]->employee_id == $karyawan_id) {
				$isemp = 1;
			}


			$readonly_direct = 'readonly';
			$readonly_emp = 'readonly';
			if ($status[0]->status_id == 1 && $isdirect == 1) { //waiting approval direct
				$readonly_direct = '';
			}
			if ($isemp == 1 && ($status[0]->status_id == '0' || $status[0]->status_id == '3')) { //draft atau rfu
				$readonly_emp = '';
			}


			$rs_num = count($rd);

			/*if($view){
				$arrSat = json_decode(json_encode($msObat), true);
				$arrS = [];
				foreach($arrSat as $ai){
					$arrS[$ai['id']] = $ai;
				}
			}*/
			foreach ($rd as $f) {
				$no = $row + 1;

				if (!$view) {

					$dt .= '<tr>';

					$dt .= '<td>' . $no . '<input type="hidden" id="hdnid' . $row . '" name="hdnid[' . $row . ']" value="' . $f->id . '"/></td>';

					$dt .= '<td>' . $this->return_build_txt($f->hardskill, 'hardskill[' . $row . ']', '', 'hardskill', 'text-align: right;', 'data-id="' . $row . '" ') . '</td>';
					$dt .= '<td>' . $this->return_build_txtarea($f->notes, 'notes[' . $row . ']', '', 'notes', 'text-align: right;', 'data-id="' . $row . '" ') . '</td>';
					$dt .= '<td>' . $this->return_build_txt($f->weight, 'weight[' . $row . ']', '', 'weight', 'text-align: right;', 'data-id="' . $row . '" onkeyup="set_weight(this)" ') . '</td>';
					$dt .= '<td>' . $this->return_build_txt($f->score_emp, 'score_emp[' . $row . ']', '', 'score_emp', 'text-align: right;', 'data-id="' . $row . '" onkeyup="set_score_emp(this)" ' . $readonly_emp . ' ') . '</td>';
					$dt .= '<td>' . $this->return_build_txt($f->score_direct, 'score_direct[' . $row . ']', '', 'score_direct', 'text-align: right;', 'data-id="' . $row . '" onkeyup="set_score_direct(this)" ' . $readonly_direct . ' ') . '</td>';
					$dt .= '<td>' . $this->return_build_txt($f->final_score, 'final_score[' . $row . ']', '', 'final_score', 'text-align: right;', 'data-id="' . $row . '" readonly') . '</td>';

					$dt .= '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete" onclick="del(\'' . $row . '\',\'' . $f->id . '\')"></td>';
					$dt .= '</tr>';
				} else {

					if ($print) {
						if ($row == ($rs_num - 1)) {
							$dt .= '<tr class="item last">';
						} else {
							$dt .= '<tr class="item">';
						}
					} else {
						$dt .= '<tr>';
					}

					$dt .= '<td>' . $no . '</td>';
					$dt .= '<td>' . $f->hardskill . '</td>';
					$dt .= '<td>' . $f->notes . '</td>';
					$dt .= '<td>' . $f->weight . '</td>';
					$dt .= '<td>' . $f->score_emp . '</td>';
					$dt .= '<td>' . $f->score_direct . '</td>';
					$dt .= '<td>' . $f->final_score . '</td>';
					$dt .= '</tr>';


				}

				$row++;
			}

		}

		return [$dt, $row];
	}


	public function getDataSoftskill($employee, $id, $save_method)
	{

		$getdata = $this->db->query("select * from user where user_id = '" . $_SESSION['id'] . "'")->result();
		$karyawan_id = $getdata[0]->id_karyawan;

		if ($save_method == 'detail') { //VIEW
			$datasoftskill = $this->db->query("select a.*, b.name, b.weight_percentage from performance_appraisal_softskill a left join master_softskill b on b.id = a.softskill_id where a.performance_appraisal_id = '" . $id . "' ")->result();

			$dt = '';
			$ttl = 0;
			if (!empty($datasoftskill)) {
				$row = 0;

				foreach ($datasoftskill as $f) {
					$no = $row + 1;

					$dt .= '<tr>';

					$dt .= '<td>' . $no . '</td>';
					$dt .= '<td>' . $f->name . '</td>';
					$dt .= '<td>' . $f->weight_percentage . '</td>';
					$dt .= '<td>' . $f->score_emp . '</td>';
					$dt .= '<td>' . $f->score_direct . '</td>';
					$dt .= '<td>' . $f->notes . '</td>';
					$dt .= '<td>' . $f->final_score . '</td>';

					$dt .= '</tr>';

					$ttl += $f->final_score;
					$row++;
				}
			}

			$tblsoftskill = '<div class="row">
			    <div class="col-md-12">
					<div class="portlet box grey">
						<div class="portlet-title">
							<div class="caption">Softskill </div>
							<div class="tools">
								
							</div>
						</div> 
						<div class="portlet-body"> 
							<span style="color:red">
							Keterangan Nilai:
							1 = Sangat Kurang, 2 = Kurang, 3 = Cukup, 4 = Baik, 5 = Sangat Baik
							</span>
							<div class="table-scrollable tablesaw-cont">
							
							<table class="table table-striped table-bordered table-hover tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailSoftskill">
							
								<thead>
									<tr>
										<th scope="col">No</th>
										<th scope="col">Name</th>
										<th scope="col">Weight (%)</th>
										<th scope="col" style="width:7%">Score (1-5)</th>
										<th scope="col" style="width:7%">Score by Direct (1-5)</th>
										<th scope="col">Notes by Direct</th>
										<th scope="col">Final Score</th>
									</tr>
								</thead>
								<tbody>
									' . $dt . '
								</tbody>
								<tfoot>
								</tfoot>
							</table>

							<table class="table table-striped table-bordered table-hover tablesaw tablesaw-stack" data-tablesaw-mode="stack" >
							
								<thead>
									
								</thead>
								<tbody>
									<tr>
										<td style="width:896px; text-align: right;"><b>Total Final Score</b></td>
										<td><b><span id="total_final_score_softskill">' . $ttl . '</span></b></td>
									</tr>
								</tbody>
								<tfoot>
								</tfoot>
							</table>

							</div>
						</div>
					</div>
				</div>
			</div>';


			$data['tblsoftskill'] = $tblsoftskill;


			return $data;

		} else { // ADD OR UPDATE
			$emp = $this->db->query("select * from employees where id = '" . $employee . "' ")->result();

			if ($emp[0]->grade_id != '') {

				$datasoftskill = $this->db->query("select a.*, b.name, b.weight_percentage from performance_appraisal_softskill a left join master_softskill b on b.id = a.softskill_id where a.performance_appraisal_id = '" . $id . "' ")->result();

				$status = $this->db->query("select a.*, b.direct_id from performance_appraisal a left join employees b on b.id = a.employee_id where a.id = '" . $id . "' ")->result();
				$isdirect = 0;
				$isemp = 0;
				if ($status[0]->direct_id == $karyawan_id) {
					$isdirect = 1;
				}
				if ($status[0]->employee_id == $karyawan_id) {
					$isemp = 1;
				}


				$readonly_direct = 'readonly';
				$readonly_emp = 'readonly';
				if ($status[0]->status_id == 1 && $isdirect == 1) { //waiting approval direct
					$readonly_direct = '';
				}
				if ($isemp == 1 && ($status[0]->status_id == '0' || $status[0]->status_id == '3')) { //draft atau rfu
					$readonly_emp = '';
				}




				if (!empty($datasoftskill)) {

					$dt = '';
					$row = 0;
					$ttl = 0;

					foreach ($datasoftskill as $f) {
						$ttl += $f->final_score;
						$no = $row + 1;

						$dt .= '<tr>';

						$dt .= '<td>' . $no . '</td>';
						$dt .= '<td>' . $f->name . '</td>';
						$dt .= '<td>' . $f->weight_percentage . '</td>';

						$dt .= '<td>' . $this->return_build_txt($f->score_emp, 'score_emp_softskill[' . $row . ']', '', 'score_emp_softskill', 'text-align: right;', 'data-id="' . $row . '" onkeyup="set_score_emp_softskill(this)" ' . $readonly_emp . ' ') . '<input type="hidden" id="hdnid_softskill' . $row . '" name="hdnid_softskill[' . $row . ']" value="' . $f->id . '"/><input type="hidden" id="hdnid_mastersoftskill' . $row . '" name="hdnid_mastersoftskill[' . $row . ']" value="' . $f->softskill_id . '"/><input type="hidden" id="weight_softskill' . $row . '" name="weight_softskill[' . $row . ']" value="' . $f->weight_percentage . '"/></td>';

						$dt .= '<td>' . $this->return_build_txt($f->score_direct, 'score_direct_softskill[' . $row . ']', '', 'score_direct_softskill', 'text-align: right;', 'data-id="' . $row . '" onkeyup="set_score_direct_softskill(this)" ' . $readonly_direct . ' ') . '</td>';

						$dt .= '<td>' . $this->return_build_txtarea($f->notes, 'notes_softskill[' . $row . ']', '', 'notes_softskill', 'text-align: right;', 'data-id="' . $row . '" ' . $readonly_direct . ' ') . '</td>';
						$dt .= '<td>' . $this->return_build_txt($f->final_score, 'final_score_softskill[' . $row . ']', '', 'final_score_softskill', 'text-align: right;', 'data-id="' . $row . '" readonly ') . '</td>';

						$dt .= '</tr>';


						$row++;
					}

				} else {
					$rs = $this->db->query("select * from master_softskill where grade_id = '" . $emp[0]->grade_id . "' order by weight_percentage desc ")->result();

					$dt = '';
					$row = 0;
					$ttl = 0;
					if (!empty($rs)) {
						foreach ($rs as $f) {
							/*$ttl += $f->final_score;*/
							$no = $row + 1;

							$dt .= '<tr>';

							$dt .= '<td>' . $no . '</td>';
							$dt .= '<td>' . $f->name . '</td>';
							$dt .= '<td>' . $f->weight_percentage . '</td>';

							$dt .= '<td>' . $this->return_build_txt('', 'score_emp_softskill[' . $row . ']', '', 'score_emp_softskill', 'text-align: right;', 'data-id="' . $row . '" onkeyup="set_score_emp_softskill(this)" ' . $readonly_emp . ' ') . '<input type="hidden" id="hdnid_softskill' . $row . '" name="hdnid_softskill[' . $row . ']" value=""/><input type="hidden" id="hdnid_mastersoftskill' . $row . '" name="hdnid_mastersoftskill[' . $row . ']" value="' . $f->id . '"/><input type="hidden" id="weight_softskill' . $row . '" name="weight_softskill[' . $row . ']" value="' . $f->weight_percentage . '"/></td>';

							$dt .= '<td>' . $this->return_build_txt('', 'score_direct_softskill[' . $row . ']', '', 'score_direct_softskill', 'text-align: right;', 'data-id="' . $row . '" onkeyup="set_score_direct_softskill(this)" ' . $readonly_direct . ' ') . '</td>';

							$dt .= '<td>' . $this->return_build_txtarea('', 'notes_softskill[' . $row . ']', '', 'notes_softskill', 'text-align: right;', 'data-id="' . $row . '" ' . $readonly_direct . ' ') . '</td>';

							$dt .= '<td>' . $this->return_build_txt('', 'final_score_softskill[' . $row . ']', '', 'final_score_softskill', 'text-align: right;', 'data-id="' . $row . '" readonly ') . '</td>';

							$dt .= '</tr>';


							$row++;
						}
					}
				}



				$tblsoftskill = '<div class="row">
				    <div class="col-md-12">
						<div class="portlet box grey">
							<div class="portlet-title">
								<div class="caption">Softskill </div>
								<div class="tools">
									
								</div>
							</div> 
							<div class="portlet-body"> 
								<span style="color:red">
								Keterangan Nilai:
								1 = Sangat Kurang, 2 = Kurang, 3 = Cukup, 4 = Baik, 5 = Sangat Baik
								</span>
								<div class="table-scrollable tablesaw-cont">
								
								<table class="table table-striped table-bordered table-hover tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailSoftskill">
								
									<thead>
										<tr>
											<th scope="col">No</th>
											<th scope="col">Name</th>
											<th scope="col">Weight (%)</th>
											<th scope="col" style="width:7%">Score (1-5)</th>
											<th scope="col" style="width:7%">Score by Direct (1-5)</th>
											<th scope="col">Notes by Direct</th>
											<th scope="col">Final Score</th>
											<th scope="col"></th>
										</tr>
									</thead>
									<tbody>
										' . $dt . '
									</tbody>
									<tfoot>
									</tfoot>
								</table>

								<table class="table table-striped table-bordered table-hover tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailSoftskill">
								
									<thead>
										
									</thead>
									<tbody>
										<tr>
											<td style="width:758px; text-align: right;"><b>Total Final Score</b></td>
											<td><b><span id="total_final_score_softskill">' . $ttl . '</span></b>
											<input type="hidden" name="hdnttl_final_score_softskill" id="hdnttl_final_score_softskill" value="' . $ttl . '">
											</td>
										</tr>
									</tbody>
									<tfoot>
									</tfoot>
								</table>

								</div>
							</div>
						</div>
					</div>
				</div>';




				$data['tblsoftskill'] = $tblsoftskill;


				return $data;

			} else
				return null;
		}


	}



}