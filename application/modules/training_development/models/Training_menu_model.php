<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Training_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "training_development/training_menu";
 	protected $table_name 				= _PREFIX_TABLE."employee_training";
 	protected $primary_key 				= "id";

 	
 	/* upload */
 	/*protected $attachment_folder	= "./uploads/employee";*/
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
			'dt.full_name',
			'dt.training_name',
			'dt.training_date',
			'dt.location',
			'dt.trainer',
			'dt.notes',
			'dt.status_name',
			'dt.direct_id',
			'dt.file_sertifikat',
			'dt.emp_code'
		];
		
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name, b.direct_id, b.emp_code,
					(case
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					 end) as status_name
					from employee_training a left join employees b on b.id = a.employee_id
					'.$whr.'
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
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #343851; border-color: #343851;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
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

			$reject=""; 
			$approve="";
			if($row->status_name == 'Waiting Approval' && $row->direct_id == $karyawan_id){
				$reject = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="reject('."'".$row->id."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" style="background-color: #2c9e1fff; border-color: #2c9e1fff;" href="javascript:void(0);" onclick="approve('."'".$row->id."'".')" role="button"><i class="fa fa-check"></i></a>';
			}

			$file_sertifikat ="";
			if($row->file_sertifikat != ''){
				$file_sertifikat = '<a href="'.base_url().'uploads/'.$row->emp_code.'/'.$row->file_sertifikat.'" target="_blank">View</a>';
			}
			

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$reject.'
					'.$approve.'
				</div>',
				$row->id,
				$row->full_name,
				$row->training_name,
				$row->training_date,
				$row->location,
				$row->trainer,
				$row->notes,
				$row->status_name,
				$file_sertifikat

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

		$training_date 		= date_create($post['training_date']); 
		$f_training_date	= date_format($training_date,"Y-m-d H:i:s");
		
		
		
  		if(!empty($post['employee'])){ 
  			$dataemp = $this->db->query("select * from employees where id = '".$post['employee']."'")->result();

  			$upload_dir = './uploads/'.$dataemp[0]->emp_code.'/'; // nama folder
			// Cek apakah folder sudah ada
			if (!is_dir($upload_dir)) {
			    // Jika belum ada, buat folder
			    mkdir($upload_dir, 0755, true); // 0755 = permission, true = recursive
			}


			$upload_file_sertifikat = $this->upload_file($upload_dir, '1', 'doc_sertifikat', FALSE, '', TRUE, '');
			$file_sertifikat = '';
			if($upload_file_sertifikat['status']){
				$file_sertifikat = $upload_file_sertifikat['upload_file'];
			} else if(isset($upload_file_sertifikat['error_warning'])){
				echo $upload_file_sertifikat['error_warning']; exit;
			}



  			$data = [
				'employee_id' 			=> trim($post['employee']),
				'training_name' 		=> trim($post['training_name']),
				'training_date'			=> $f_training_date,
				'location' 				=> trim($post['location']),
				'trainer' 				=> trim($post['trainer']),
				'notes' 				=> trim($post['notes']),
				'status_id' 			=> 1, //waiting approval
				'created_at'			=> date("Y-m-d H:i:s"),
				'file_sertifikat' 		=> $file_sertifikat
				
			];
			$rs = $this->db->insert($this->table_name, $data);

  		}else return null;

	}  

	public function edit_data($post) { 
		$training_date 		= date_create($post['training_date']); 
		$f_training_date	= date_format($training_date,"Y-m-d H:i:s");


		if(!empty($post['id'])){ 

			$dataemp = $this->db->query("select * from employees where id = '".$post['employee']."'")->result();

  			$upload_dir = './uploads/'.$dataemp[0]->emp_code.'/'; // nama folder
			// Cek apakah folder sudah ada
			if (!is_dir($upload_dir)) {
			    // Jika belum ada, buat folder
			    mkdir($upload_dir, 0755, true); // 0755 = permission, true = recursive
			}

			$hdndoc_sertifikat 		= trim($post['hdndoc_sertifikat']);
			$upload_file_sertifikat = $this->upload_file($upload_dir, '1', 'doc_sertifikat', FALSE, '', TRUE, '');
			$file_sertifikat = '';
			if($upload_file_sertifikat['status']){
				$file_sertifikat = $upload_file_sertifikat['upload_file'];
			} else if(isset($upload_file_sertifikat['error_warning'])){
				echo $upload_file_sertifikat['error_warning']; exit;
			}

			if($file_sertifikat == '' && $hdndoc_sertifikat != ''){
				$file_sertifikat = $hdndoc_sertifikat;
			}




			$data = [
				'employee_id' 			=> trim($post['employee']),
				'training_name' 		=> trim($post['training_name']),
				'training_date'			=> $f_training_date,
				'location' 				=> trim($post['location']),
				'trainer' 				=> trim($post['trainer']),
				'notes' 				=> trim($post['notes']),
				'updated_at'			=> date("Y-m-d H:i:s"),
				'file_sertifikat' 		=> $file_sertifikat
				
			];
			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);


			return $rs;

		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.full_name, b.emp_code,
					(case
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					 end) as status_name
					from employee_training a left join employees b on b.id = a.employee_id
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
				'employee_id' 	=> $v["B"],
				'training_name' => $v["C"],
				'training_date' => $v["D"],
				'location' 		=> $v["E"],
				'trainer' 		=> $v["F"],
				'notes' 		=> $v["G"],
				'status_id' 	=> $v["H"],
				'created_at' 	=> date("Y-m-d H:i:s")
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}


		
		$sql = 'select a.*, b.full_name, b.direct_id, b.emp_code,
				(case
				when a.status_id = 1 then "Waiting Approval"
				when a.status_id = 2 then "Approved"
				when a.status_id = 3 then "Rejected"
				else ""
				 end) as status_name
				from employee_training a left join employees b on b.id = a.employee_id
				'.$whr.'
				order by a.id asc

		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}



}