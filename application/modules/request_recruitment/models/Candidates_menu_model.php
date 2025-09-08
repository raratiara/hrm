<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Candidates_menu_model extends MY_Model
{
	/* Module */
	protected $folder_name = "request_recruitment/candidates_menu";
	protected $table_name = _PREFIX_TABLE . "candidates";
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
				$row->id,
				$row->full_name,
				$row->position_name,
				$row->email,
				$row->phone,
				$cv,
				$row->status_name

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
		$getdata = $this->db->query("select * from user where user_id = '" . $_SESSION['id'] . "'")->result();
		$karyawan_id = $getdata[0]->id_karyawan;

		if (!empty($post['year'])) {
			$data = [
				'year' => trim($post['year']),
				'section_id' => trim($post['section']),
				'job_level_id' => trim($post['joblevel']),
				'mpp' => trim($post['headcount']),
				/*'completed' 	=> '',*/
				'notes' => trim($post['notes']),
				'created_at' => date("Y-m-d H:i:s"),
				'created_by' => $karyawan_id
			];
			$rs = $this->db->insert($this->table_name, $data);

			return $rs;

		} else
			return null;


	}

	public function edit_data($post)
	{
		$getdata = $this->db->query("select * from user where user_id = '" . $_SESSION['id'] . "'")->result();
		$karyawan_id = $getdata[0]->id_karyawan;


		if (!empty($post['id'])) {

			$data = [
				'year' => trim($post['year']),
				'section_id' => trim($post['section']),
				'job_level_id' => trim($post['joblevel']),
				'mpp' => trim($post['headcount']),
				/*'completed' 	=> '',*/
				'notes' => trim($post['notes']),
				'updated_at' => date("Y-m-d H:i:s"),
				'updated_by' => $karyawan_id
			];

			return $rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
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


		$sql = 'select a.*, b.name as section_name, c.name as level_name, a.headcount_id as id 
					from mpp a left join sections b on b.id = a.section_id
					left join master_job_level c on c.id = a.job_level_id
	   			ORDER BY a.headcount_id ASC
		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getDataStep($id, $save_method)
	{

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

			$datastep = $this->db->query("select a.*, b.name as step_name from candidates_step a 
								left join master_step_recruitment b on b.id = a.step_recruitment_id
								where a.candidates_id = '" . $id . "' ")->result();


			if (!empty($datastep)) {

				$dt = '';
				$row = 0;
				
				$msStatus = $this->db->query("select * from master_status_candidates where id in ('2','5')")->result(); 
				foreach ($datastep as $f) {
					$viewdoc = '';
					if($f->doc != ''){
						$viewdoc = '<a href="'.base_url().'uploads/candidates/'.$f->doc.'" target="_blank">View</a>';
					}
					if($f->step_recruitment_id == 4){ //psikotes
						$docc = '<td>'.$this->return_build_fileinput('doc'.$row.'','','','doc','text-align: right;','data-id="'.$row.'" ').$viewdoc.' <input type="hidden" id="hdndoc'.$row.'" name="hdndoc'.$row.'" value="'.$f->doc.'"/></td>';
					}else{
						$docc='<td></td>';
					}
					
					$no = $row + 1;

					$dt .= '<tr>';

					$dt .= '<td>' . $no . '</td>';
					$dt .= '<td>' . $f->step_name . '</td>';

					$dt .= '<td>' . $this->return_build_txt($f->date, 'date[' . $row . ']', '', 'date', 'text-align: right;', 'data-id="' . $row . '" ') . '</td>';

					/*$dt .= '<td>'.$this->return_build_fileinput('doc'.$row.'','','','doc','text-align: right;','data-id="'.$row.'" ').$viewdoc.' <input type="hidden" id="hdndoc'.$row.'" name="hdndoc'.$row.'" value="'.$f->doc.'"/></td>';*/

					$dt .= $docc;

					$dt .= '<td>' . $this->return_build_txtarea($f->notes, 'notes[' . $row . ']', '', 'notes', 'text-align: right;', 'data-id="' . $row . '"  ') . '</td>';

					$dt .= '<td>'.$this->return_build_chosenme($msStatus,'',isset($f->status_id)?$f->status_id:1,'','status['.$row.']','status','status','','id','name','','','',' data-id="'.$row.'" ').'</td>';
					

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
							$docc = '<td>'.$this->return_build_fileinput('doc'.$row.'','','','doc','text-align: right;','data-id="'.$row.'" ').' <input type="hidden" id="hdndoc'.$row.'" name="hdndoc'.$row.'" value=""/></td>';
						}else{
							$docc='<td></td>';
						}
						
						$no = $row + 1;

						$dt .= '<tr>';

						$dt .= '<td>' . $no . '</td>';
						$dt .= '<td>' . $f->name . '</td>';
						

						$dt .= '<td>' . $this->return_build_txt('', 'date[' . $row . ']', '', 'date', 'text-align: right;', 'data-id="' . $row . '" ') . '</td>';

						/*$dt .= '<td>'.$this->return_build_fileinput('doc'.$row.'','','','doc','text-align: right;','data-id="'.$row.'" ').' <input type="hidden" id="hdndoc'.$row.'" name="hdndoc'.$row.'" value=""/></td>';*/
						$dt .= $docc;

						$dt .= '<td>' . $this->return_build_txtarea('', 'notes[' . $row . ']', '', 'notes', 'text-align: right;', 'data-id="' . $row . '"  ') . '</td>';

						$dt .= '<td>'.$this->return_build_chosenme($msStatus,'','','','status['.$row.']','status','status','','id','name','','','',' data-id="'.$row.'" ').'</td>';

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