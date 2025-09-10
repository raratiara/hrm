<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Candidates_menu_model extends MY_Model
{
	/* Module */
	protected $folder_name = "request_recruitment/candidates_menu";
	protected $table_name = _PREFIX_TABLE . "candidates";
	protected $primary_key = "id";


	/* upload */
 	protected $attachment_folder	= "./uploads/candidates";
	protected $allow_type			= "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt|apk";
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
			'dt.candidate_code',
			'dt.full_name',
			'dt.position_name',
			'dt.email',
			'dt.phone',
			'dt.cv',
			'dt.status_name'
		];

		/*$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;*/

		$sIndexColumn = $this->primary_key;



		$sTable = '(select a.*, b.subject as position_name, c.name as status_name 
						from candidates a left join request_recruitment b on b.id = a.request_recruitment_id
						left join master_status_candidates c on c.id = a.status_id)dt';


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

		$no=1;
		foreach ($rResult as $row) {
			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1") {
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #343851; border-color: #343851;" href="javascript:void(0);" onclick="detail(' . "'" . $row->id . "'" . ')" role="button"><i class="fa fa-search-plus"></i></a>';
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

			$cv = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="downloadFile('."'".$row->cv."'".')" role="button"><i class="fa fa-download"></i></a>';
			array_push($output["aaData"], array(
				$delete_bulk,
				'<div class="action-buttons">
					' . $detail . '
					' . $edit . '
					' . $delete . '
				</div>',
				$no,
				$row->candidate_code,
				$row->full_name,
				$row->position_name,
				$row->email,
				$row->phone,
				$cv,
				$row->status_name

			));

			$no++;
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


	// Upload file
	public function upload_file($id = "", $fieldname= "", $replace=FALSE, $oldfilename= "", $array=FALSE, $i=0) { 
		$data = array();
		$data['status'] = FALSE; 
		if(!empty($id) && !empty($fieldname)){ 
			// handling multiple upload (as array field)

			if($array){ 
				// Define new $_FILES array - $_FILES['file']
				$_FILES['file']['name'] = $_FILES[$fieldname]['name'];
				$_FILES['file']['type'] = $_FILES[$fieldname]['type'];
				$_FILES['file']['tmp_name'] = $_FILES[$fieldname]['tmp_name'];
				$_FILES['file']['error'] = $_FILES[$fieldname]['error'];
				$_FILES['file']['size'] = $_FILES[$fieldname]['size']; 
				// override field
				//$fieldname = 'document';

			} 
			// handling regular upload (as one field)
			if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
			{ 
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
				
				if(!$this->upload->do_upload($fieldname)){ 
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


	// public function add_data($post)
	// {
	// 	$getdata = $this->db->query("select * from user where user_id = '" . $_SESSION['id'] . "'")->result();
	// 	$karyawan_id = $getdata[0]->id_karyawan;

	// 	if (!empty($post['year'])) {
	// 		$data = [
	// 			'year' => trim($post['year']),
	// 			'section_id' => trim($post['section']),
	// 			'job_level_id' => trim($post['joblevel']),
	// 			'mpp' => trim($post['headcount']),
	// 			/*'completed' 	=> '',*/
	// 			'notes' => trim($post['notes']),
	// 			'created_at' => date("Y-m-d H:i:s"),
	// 			'created_by' => $karyawan_id
	// 		];
	// 		$rs = $this->db->insert($this->table_name, $data);

	// 		return $rs;

	// 	} else
	// 		return null;


	// }

	public function edit_data($post)
	{
		$join_date = trim($post['join_date']);
		$contract_sign_date = trim($post['contract_sign_date']);
		
		if (!empty($post['id'])) {

			$data = [
				'status_id' 	=> trim($post['status']),
				'join_date' 	=> date('Y-m-d', strtotime($join_date)),
				'contract_sign_date' => date('Y-m-d', strtotime($contract_sign_date)),
				'updated_date' 	=> date("Y-m-d H:i:s")
			];

			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);


			//step
			if (isset($post['step_id'])) {
				$item_num2 = count($post['step_id']); // cek sum
				$item_len_min2 = min(array_keys($post['step_id'])); // cek min key index
				$item_len2 = max(array_keys($post['step_id'])); // cek max key index
			} else {
				$item_num2 = 0;
			}

			if ($item_num2 > 0) {
				for ($i = $item_len_min2; $i <= $item_len2; $i++) {
					$candidates_step_id = $post['candidates_step_id'][$i];
					if (isset($post['step_id'][$i])) {

						/// add file
						$hdndocument = trim($post['hdndocument'.$i]);
						$document = '';
						$upload_emp_photo = $this->upload_file('1', 'document'.$i.'', FALSE, '', TRUE, $i);
						if($upload_emp_photo['status']){ 
							$document = $upload_emp_photo['upload_file'];
						} else if(isset($upload_emp_photo['error_warning'])){ 
							echo $upload_emp_photo['error_warning']; exit;
						}

						if($document == '' && $hdndocument != ''){
							$document = $hdndocument;
						}
						/// end add file

						if ($candidates_step_id != '') { //update
							$itemData2 = [
								'date' 		=> trim($post['date'][$i]),
								'doc' 		=> $document,
								'notes' 	=> trim($post['notes'][$i]),
								'status_id' => trim($post['status_step'][$i])
							];

							$this->db->update('candidates_step', $itemData2, "id = '" . $candidates_step_id . "'");
						} else { //insert
							$itemData2 = [
								'candidates_id' => $post['id'],
								'date' 		=> trim($post['date'][$i]),
								'doc' 		=> $document,
								'notes' 	=> trim($post['notes'][$i]),
								'status_id' => trim($post['status_step'][$i])
							];

							$this->db->insert('candidates_step', $itemData2);
						}
					}
				}
			}

			return $rs;

		} else
			return null;
	}

	public function getRowData($id)
	{
		$mTable = '(select a.*, b.subject as position_name, c.name as status_name 
						from candidates a left join request_recruitment b on b.id = a.request_recruitment_id
						left join master_status_candidates c on c.id = a.status_id
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
				'year' => $v["B"],
				'section_id' => $v["C"],
				'job_level_id' => $v["D"],
				'mpp' => $v["E"],
				'notes' => $v["F"]

			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs)
				$error .= ",baris " . $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{


		$sql = 'select a.*, b.subject as position_name, c.name as status_name 
						from candidates a left join request_recruitment b on b.id = a.request_recruitment_id
						left join master_status_candidates c on c.id = a.status_id
	   			ORDER BY a.id ASC
		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getDataStep($id, $save_method)
	{

		if ($save_method == 'detail') { //VIEW
			$datastep = $this->db->query("select a.*, b.name as step_name, b.id as step_id, c.name as status_name
								from candidates_step a 
								left join master_step_recruitment b on b.id = a.step_recruitment_id
								left join master_status_candidates c on c.id = a.status_id
								where a.candidates_id = '" . $id . "' ")->result();

			$dt = '';
			if (!empty($datastep)) {
				$row = 0;
				$no = 1;
				foreach ($datastep as $f) {
					$viewdoc = '';
					if($f->doc != ''){
						$viewdoc = '<a href="'.base_url().'uploads/candidates/'.$f->doc.'" target="_blank">View</a>';
					}
					$date = $f->date;
					if($f->date == '0000-00-00'){
						$date = '';
					}
				
					$dt .= '<tr>';

					$dt .= '<td>' . $no . '</td>';
					$dt .= '<td>' . $f->step_name . '</td>';
					$dt .= '<td>' . $date . '</td>';
					$dt .= '<td>' . $viewdoc . '</td>';
					$dt .= '<td>' . $f->notes . '</td>';
					$dt .= '<td>' . $f->status_name . '</td>';

					$dt .= '</tr>';

				
					$row++; $no++;
				}
			}

			$tblstep = '<div class="row">
			    <div class="col-md-12">
					<div class="portlet box grey">
						<div class="portlet-title">
							<div class="caption">Step Detail </div>
							<div class="tools">
								
							</div>
						</div> 
						<div class="portlet-body"> 
							
							<div class="table-scrollable tablesaw-cont">
							
							<table class="table table-striped table-bordered table-hover tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailStep">
							
								<thead>
									<tr>
										<th scope="col">No</th>
										<th scope="col">Step</th>
										<th scope="col">Date</th>
										<th scope="col" style="width:12%">Document</th>
										<th scope="col">Notes</th>
										<th scope="col">Status</th>
									</tr>
								</thead>
								<tbody>
									' . $dt . '
								</tbody>
								<tfoot>
								</tfoot>
							</table>

							
							</div>
						</div>
					</div>
				</div>
			</div>';


			$data['tblstep'] = $tblstep;


			return $data;

		} else { // ADD OR UPDATE

			$datastep = $this->db->query("select a.*, b.name as step_name, b.id as step_id from candidates_step a 
								left join master_step_recruitment b on b.id = a.step_recruitment_id
								where a.candidates_id = '" . $id . "' ")->result();


			if (!empty($datastep)) {

				$dt = '';
				$row = 0;
				
				$msStatus = $this->db->query("select * from master_status_candidates where id in ('2','5')")->result(); 
				foreach ($datastep as $f) {
					
					if($f->step_recruitment_id == 4){ //psikotes
						$docc = '<td>'.$this->return_build_fileinput('document'.$row.'','','','document','text-align: right;','data-id="'.$row.'" ').$viewdoc.' <input type="hidden" id="hdndocument'.$row.'" name="hdndocument'.$row.'" value="'.$f->doc.'"/></td>';
						
					}else{
						$docc='<td></td>';
					}
					
					$no = $row + 1;

					$dt .= '<tr>';

					$dt .= '<td>' . $no . '<input type="hidden" name="candidates_step_id[' . $row . ']" value="'.$f->id.'"/><input type="hidden" name="step_id[' . $row . ']" value="'.$f->step_id.'"/></td>';
					$dt .= '<td>' . $f->step_name . '</td>';

					/*$dt .= '<td>' . $this->return_build_txt($f->date, 'date[' . $row . ']', 'date', 'date', 'text-align: right;', 'data-id="' . $row . '" ') . '</td>';*/

					$dt .= '<td><input type="date" class="form-control" name="date[' . $row . ']" value="'.$f->date.'" data-id="' . $row . '" /></td>';


					/*$dt .= '<td>'.$this->return_build_fileinput('doc'.$row.'','','','doc','text-align: right;','data-id="'.$row.'" ').$viewdoc.' <input type="hidden" id="hdndoc'.$row.'" name="hdndoc'.$row.'" value="'.$f->doc.'"/></td>';*/

					$dt .= $docc;

					$dt .= '<td>' . $this->return_build_txtarea($f->notes, 'notes[' . $row . ']', '', 'notes', 'text-align: right;', 'data-id="' . $row . '"  ') . '</td>';

					$dt .= '<td>'.$this->return_build_chosenme($msStatus,'',isset($f->status_id)?$f->status_id:1,'','status_step['.$row.']','status_step','status_step','','id','name','','','',' data-id="'.$row.'" ').'</td>';
					

					$dt .= '</tr>';


					$row++;
				}

			} else {
				$rs = $this->db->query("select * from master_step_recruitment order by order_no asc ")->result();

				$dt = '';
				$row = 0;
				$ttl = 0;
				if (!empty($rs)) {
					foreach ($rs as $f) {
						$msStatus = $this->db->query("select * from master_status_candidates where id in ('2','5')")->result(); 
						if($f->id == 4){ //psikotes
							$docc = '<td>'.$this->return_build_fileinput('document'.$row.'','','','document','text-align: right;','data-id="'.$row.'" ').' <input type="hidden" id="hdndocument'.$row.'" name="hdndocument'.$row.'" value=""/></td>';
						}else{
							$docc='<td></td>';
						}
						
						$no = $row + 1;

						$dt .= '<tr>';

						$dt .= '<td>' . $no . '<input type="hidden" name="candidates_step_id[' . $row . ']" /><input type="hidden" name="step_id[' . $row . ']" value="'.$f->id.'"/></td>';
						$dt .= '<td>' . $f->name . '</td>';
						

						/*$dt .= '<td>' . $this->return_build_txt('', 'date[' . $row . ']', '', 'date', 'text-align: right;', 'data-id="' . $row . '" ') . '</td>';*/
						$dt .= '<td><input type="date" class="form-control" name="date[' . $row . ']" value="" data-id="' . $row . '"></></td>';

						/*$dt .= '<td>'.$this->return_build_fileinput('doc'.$row.'','','','doc','text-align: right;','data-id="'.$row.'" ').' <input type="hidden" id="hdndoc'.$row.'" name="hdndoc'.$row.'" value=""/></td>';*/
						$dt .= $docc;

						$dt .= '<td>' . $this->return_build_txtarea('', 'notes[' . $row . ']', '', 'notes', 'text-align: right;', 'data-id="' . $row . '"  ') . '</td>';

						$dt .= '<td>'.$this->return_build_chosenme($msStatus,'','','','status_step['.$row.']','status_step','status_step','','id','name','','','',' data-id="'.$row.'" ').'</td>';

						$dt .= '</tr>';


						$row++;
					}
				}
			}



			$tblstep = '<div class="row">
			    <div class="col-md-12">
					<div class="portlet box grey">
						<div class="portlet-title">
							<div class="caption">Step Detail </div>
							<div class="tools">
								
							</div>
						</div> 
						<div class="portlet-body"> 
							
							<div class="table-scrollable tablesaw-cont">
							
							<table class="table table-striped table-bordered table-hover tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailStep">
							
								<thead>
									<tr>
										<th scope="col">No</th>
										<th scope="col">Step</th>
										<th scope="col">Date</th>
										<th scope="col" style="width:13%">Document</th>
										<th scope="col">Notes</th>
										<th scope="col">Status</th>
									</tr>
								</thead>
								<tbody>
									' . $dt . '
								</tbody>
								<tfoot>
								</tfoot>
							</table>

							<table class="table table-striped table-bordered table-hover tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailStep">
							
								<thead>
									
								</thead>
								<tbody>
									
								</tbody>
								<tfoot>
								</tfoot>
							</table>

							</div>
						</div>
					</div>
				</div>
			</div>';




			$data['tblstep'] = $tblstep;


			return $data;

			
		}


	}



}