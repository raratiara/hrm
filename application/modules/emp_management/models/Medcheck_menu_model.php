<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medcheck_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "emp_management/medcheck_menu";
 	protected $table_name 				= _PREFIX_TABLE."medical_check";
 	protected $primary_key 				= "id";


 	protected $allow_type			= "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
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
			'dt.employee_name',
			'dt.file',
			'dt.created_at',
			'dt.status',
			'dt.approval_date',
			'dt.direct_id',
			'dt.emp_code'
		];
		
		
		$karyawan_id = $_SESSION['worker']; 
		$whr='';
		if($_SESSION['role'] == 1){ //bukan super user
			$whr=' and (a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'") ';
		}


		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name as employee_name, b.direct_id, b.emp_code
					from medical_check a left join employees b on b.id = a.employee_id 
					where 1=1 '.$whr.' order by a.id desc
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
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}
			

			//$url_file 	= _URL.'/uploads/employee/'.$emp_code.'/medcheck/'.$row->file;

			$url_file = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="downloadFile('."'".$row->emp_code."'".','."'".$row->file."'".')" role="button"><i class="fa fa-download"></i></a>';

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$delete.'
				</div>',
				$row->id,
				$row->employee_name,
				$url_file,
				$row->created_at,
				$row->status,
				$row->approval_date


			));
		}

		echo json_encode($output);
	}

	// filltering null value from array
	public function is_not_null($val){
		return !is_null($val);
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


	// Upload file
	public function upload_file($attachment_folder, $id = "", $fieldname= "", $replace=FALSE, $oldfilename= "", $array=FALSE, $i=0) { 
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
				/*$config['upload_path']   = $this->attachment_folder;*/
				$config['upload_path']   = $attachment_folder;
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


	public function add_data($post) { 

		$employee = trim($post['employee']); 
		
		
  		if(!empty($post['employee'])){ 

  			$dataEmp = $this->db->query("select * from employees where id = '".$employee."'")->result(); 
  			$empcode = "";
  			if(!empty($dataEmp)){
  				$empcode = $dataEmp[0]->emp_code;
  			}

  			if(!empty($empcode)){ 
  				$upload_dir = './uploads/employee/'.$empcode.'/medcheck/'; // nama folder
				// Cek apakah folder sudah ada
				if (!is_dir($upload_dir)) {
				    // Jika belum ada, buat folder
				    mkdir($upload_dir, 0755, true); // 0755 = permission, true = recursive
				}


				$upload_emp_photo = $this->upload_file($upload_dir, '1', 'file', FALSE, '', TRUE, '');
				$file_medcek = '';
				if($upload_emp_photo['status']){
					$file_medcek = $upload_emp_photo['upload_file'];
				} else if(isset($upload_emp_photo['error_warning'])){
					echo $upload_emp_photo['error_warning']; exit;
				}


	  			$data = [
					'employee_id' 	=> $employee,
					'file' 			=> $file_medcek,
					'created_at'	=> date("Y-m-d H:i:s")
				];
				return $rs = $this->db->insert($this->table_name, $data);
  			}
  			else return null;
  			
  		}else return null;

	}  

	public function edit_data($post) { 
		
		$karyawan_id = $_SESSION['worker'];


		$employee = trim($post['employee']); 


		if(!empty($post['id'])){  
			$dtmedcek = $this->db->query("select * from medical_check where id = '".$post['id']."'")->result();

			$dtemp = $this->db->query("select direct_id, emp_code from employees where id = '".$dtmedcek[0]->employee_id."'")->result();
		
			$direct_id =""; $empcode = "";
			if(!empty($dtemp)){ 
				if($dtemp[0]->direct_id != ''){ 
					$direct_id = $dtemp[0]->direct_id;
				}
				if($dtemp[0]->emp_code != ''){
					$empcode = $dtemp[0]->emp_code;
				}
			}

			$hdnfile 	= trim($post['hdnfile']);
			$upload_dir = './uploads/employee/'.$empcode.'/medcheck/'; // nama folder

			$upload_emp_photo = $this->upload_file($upload_dir, '1', 'file', FALSE, '', TRUE, '');
				$file_medcek = '';
			if($upload_emp_photo['status']){
				$file_medcek = $upload_emp_photo['upload_file'];
			} else if(isset($upload_emp_photo['error_warning'])){
				echo $upload_emp_photo['error_warning']; exit;
			}

			if($file_medcek == '' && $hdnfile != ''){
				$file_medcek = $hdnfile;
			}


			if($direct_id == $karyawan_id){ //approval
				
				$data = [
					'status' 		=> trim($post['status']),
					'approval_date'	=> date("Y-m-d H:i:s")
				];
			}else{ 
				$data = [
					'employee_id' 	=> trim($post['employee']),
					'file' 			=> $file_medcek,
					'updated_at'	=> date("Y-m-d H:i:s")
				];
			}

		

			return  $rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);
		} else return null;
	}


	public function getRowData($id) { 
		
		$karyawan_id = $_SESSION['worker'];


		$mTable = '(select a.*, b.full_name as employee_name, b.direct_id, b.emp_code
					from medical_check a left join employees b on b.id = a.employee_id 
					
			)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		$isdirect = 0;
		if ($rs->direct_id == $karyawan_id) {
			$isdirect = 1;
		}
		
		$data = array(
			'rowdata' 	=> $rs,
			'isdirect' 	=> $isdirect
		);
		
		return $data;
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
		
		$karyawan_id = $_SESSION['worker'];
		$whr='';
		if($_SESSION['role'] != 1){ //bukan super user
			$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}



		$sql = "select b.full_name as employee_name, a.task, c.task as parent_name, d.name as status_name, a.progress_percentage, a.due_date, a.solve_date, e.title as project_name   
			from tasklist a left join employees b on b.id = a.employee_id
			left join tasklist c on c.id = a.parent_id
			left join master_tasklist_status d on d.id = a.status_id 
			left join data_project e on e.id = a.project_id
			".$whr."
			order by a.id asc
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


}