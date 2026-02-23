<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lms_materi_menu_model extends MY_Model
{
	/* Module */
	protected $folder_name				= "training_development/lms_materi_menu";
	protected $table_name 				= _PREFIX_TABLE . "lms_materi";
	protected $primary_key 				= "id";


	/* upload */
	protected $attachment_folder	= "./uploads/lms_materi";
	protected $allow_type			= "pdf";
	protected $allow_size			= "0"; // 0 for limit by default php conf (in Kb)


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
			'dt.type_name',
			'dt.course_name',
			'dt.title',
			'dt.file_pdf',
			'dt.url_youtube',
			'dt.type',
			'dt.department_names'
		];

		/*$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr = ' where ao.created_by = "' . $karyawan_id . '" or ao.direct_id = "' . $karyawan_id . '" or ao.is_approver_view = 1  ';
		}*/


		$sIndexColumn = $this->primary_key;


		$sTable = '(select a.*, b.course_name, c.name as type_name,
						GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ", ") AS department_names
					from lms_materi a 
					left join lms_course b on b.id = a.lms_course_id
					left join master_lms_materi_type c on c.id = a.type
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


			if ($row->type == 1) { ///pdf
				$doc = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="downloadFile(' . "'" . $row->file_pdf . "'" . ')" role="button"><i class="fa fa-download"></i></a>';
			} else {
				/*$doc = $row->url_youtube;*/

				$doc = '<a href="' . $row->url_youtube . '" target="_blank">Link</a>';
			}


			array_push($output["aaData"], array(
				$delete_bulk,
				'<div class="action-buttons">
					' . $detail . '
					' . $edit . '
					' . $delete . '
				</div>',
				$row->id,
				$row->title,
				$row->type_name,
				$doc,
				$row->department_names,
				$row->course_name


			));
		}

		echo json_encode($output);
	}


	public function get_cards_list($page = 1, $length = 9, $search = '', $type = '')
	{
		$offset = ($page - 1) * $length;

		// Base query (PAKAI table_name biar prefix aman)
		$baseSql = '(select a.*, b.course_name, c.name as type_name,
            GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ", ") AS department_names
        from ' . $this->table_name . ' a
        left join lms_course b on b.id = a.lms_course_id
        left join master_lms_materi_type c on c.id = a.type
        left join departments d on FIND_IN_SET(d.id, a.department_ids)
        group by a.id
    ) dt';

		$where = " WHERE 1=1 ";

		if (!empty($type)) {
			$where .= " AND dt.type = " . $this->db->escape_str($type) . " ";
		}

		if (!empty($search)) {
			$s = $this->db->escape_like_str($search);
			$where .= " AND (
            dt.title LIKE '%{$s}%'
            OR dt.course_name LIKE '%{$s}%'
            OR dt.department_names LIKE '%{$s}%'
            OR dt.type_name LIKE '%{$s}%'
        ) ";
		}

		// total filtered
		$qTotal = $this->db->query("SELECT COUNT(dt.id) AS total FROM $baseSql $where")->row();
		$totalFiltered = (int)($qTotal ? $qTotal->total : 0);

		// rows
		$rows = $this->db->query("
        SELECT dt.id, dt.title, dt.type, dt.type_name, dt.course_name, dt.department_names, dt.file_pdf, dt.url_youtube
        FROM $baseSql
        $where
        ORDER BY dt.id DESC
        LIMIT {$offset}, {$length}
    ")->result_array();

		// stats (total/pdf/youtube) ikut filter search biar konsisten
		$stat = $this->db->query("
        SELECT 
          COUNT(dt.id) AS total,
          SUM(CASE WHEN dt.type = 1 THEN 1 ELSE 0 END) AS pdf,
          SUM(CASE WHEN dt.type <> 1 THEN 1 ELSE 0 END) AS youtube
        FROM $baseSql
        $where
    ")->row_array();

		$totalPage = ($length > 0) ? (int) ceil($totalFiltered / $length) : 1;

		return [
			'rows' => $rows,
			'pagination' => [
				'page' => $page,
				'length' => $length,
				'total_rows' => $totalFiltered,
				'total_pages' => $totalPage
			],
			'stats' => [
				'total' => (int)($stat['total'] ?? 0),
				'pdf' => (int)($stat['pdf'] ?? 0),
				'youtube' => (int)($stat['youtube'] ?? 0)
			]
		];
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


	// Upload file
	public function upload_file($id = "", $fieldname = "", $replace = FALSE, $oldfilename = "", $array = FALSE, $i = 0)
	{
		$data = array();
		$data['status'] = FALSE;
		if (!empty($id) && !empty($fieldname)) {
			// handling multiple upload (as array field)

			if ($array) {
				// Define new $_FILES array - $_FILES['file']
				$_FILES['file']['name'] = $_FILES[$fieldname]['name'];
				$_FILES['file']['type'] = $_FILES[$fieldname]['type'];
				$_FILES['file']['tmp_name'] = $_FILES[$fieldname]['tmp_name'];
				$_FILES['file']['error'] = $_FILES[$fieldname]['error'];
				$_FILES['file']['size'] = $_FILES[$fieldname]['size'];
				// override field

			}
			// handling regular upload (as one field)
			if (isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name'])) {
				/*$dir = $this->attachment_folder.'/'.$id;
				if(!is_dir($dir)) {
					mkdir($dir);
				}
				if($replace){
					$this->remove_file($id, $oldfilename);
				}*/
				$config['upload_path']   = $this->attachment_folder;
				$config['allowed_types'] = $this->allow_type;
				$config['max_size'] 	 = $this->allow_size;

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload($fieldname)) {
					$err_msg = $this->upload->display_errors();
					$data['error_warning'] = strip_tags($err_msg);
					$data['status'] = FALSE;
				} else {
					$fileData = $this->upload->data();
					$data['upload_file'] = $fileData['file_name'];
					$data['status'] = TRUE;
				}
			}
		}



		return $data;
	}


	public function add_data($post)
	{

		$department_ids = '';
		if (isset($post['departments']) && is_array($post['departments'])) {
			$department_ids = implode(',', $post['departments']);
		}


		if (trim($post['type']) == 1) { //PDF

			$upload_file = $this->upload_file('1', 'file_pdf', FALSE, '', TRUE, '');
			$file = '';
			if ($upload_file['status']) {
				$file = $upload_file['upload_file'];
			} else if (isset($upload_emp_photo['error_warning'])) {
			}

			$data = [
				'lms_course_id' => trim($post['course']),
				'type' 			=> trim($post['type']),
				'title'			=> trim($post['title_materi']),
				'department_ids' 	=> $department_ids,
				'file_pdf' 		=> $file
			];
		} else {
			$data = [
				'lms_course_id' => trim($post['course']),
				'type' 			=> trim($post['type']),
				'title'			=> trim($post['title_materi']),
				'department_ids' 	=> $department_ids,
				'url_youtube' 	=> trim($post['youtube_url'])
			];
		}


		$rs = $this->db->insert($this->table_name, $data);


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
				

	}  

	public function edit_data($post)
	{


		$department_ids = '';
		if (isset($post['departments']) && is_array($post['departments'])) {
			$department_ids = implode(',', $post['departments']);
		}



		if (!empty($post['id'])) {

			if (trim($post['type']) == 1) { //PDF
				$hdnfile = $post['hdnfile'];

				$upload_file = $this->upload_file('1', 'file_pdf', FALSE, '', TRUE, '');
				$file = '';
				if ($upload_file['status']) {
					$file = $upload_file['upload_file'];
				} else if (isset($upload_emp_photo['error_warning'])) {
				}
				if ($file == '' && $hdnfile != '') {
					$file = $hdnfile;
				}


				$data = [
					'lms_course_id' => trim($post['course']),
					'type' 			=> trim($post['type']),
					'title'			=> trim($post['title_materi']),
					'department_ids' 	=> $department_ids,
					'file_pdf' 		=> $file
				];
			} else {
				$data = [
					'lms_course_id' => trim($post['course']),
					'type' 			=> trim($post['type']),
					'title'			=> trim($post['title_materi']),
					'department_ids' 	=> $department_ids,
					'url_youtube' 	=> trim($post['youtube_url'])
				];
			}


			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);


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

		} else{
			return [
				    "status" => false,
				    "msg" 	 => "ID tidak ditemukan"
				];
		}
	}  

	public function getRowData($id)
	{
		$mTable = '(select a.*, b.course_name, c.name as type_name,
						GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ", ") AS department_names
					from lms_materi a 
					left join lms_course b on b.id = a.lms_course_id
					left join master_lms_materi_type c on c.id = a.type
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



		$sql = 'select a.*, b.course_name, c.name as type_name,
					GROUP_CONCAT(d.name ORDER BY d.name SEPARATOR ", ") AS department_names
				from lms_materi a 
				left join lms_course b on b.id = a.lms_course_id
				left join master_lms_materi_type c on c.id = a.type
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
