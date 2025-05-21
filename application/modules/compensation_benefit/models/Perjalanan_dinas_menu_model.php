<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perjalanan_dinas_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "compensation_benefit/perjalanan_dinas_menu";
 	protected $table_name 				= _PREFIX_TABLE."business_trip";
 	protected $primary_key 				= "id";


 	/* upload */
 	protected $attachment_folder	= "./uploads/bustrip";
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
			'dt.destination',
			'dt.start_date',
			'dt.end_date',
			'dt.reason',
			'dt.status_name',
			'dt.direct_id'
		];
		
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;


		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name, b.direct_id,
					(case 
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					end) as status_name 
					from business_trip a left join employees b on b.id = a.employee_id
					where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'"
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
				$detail = '<a class="btn btn-xs btn-success detail-btn" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				$edit = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
			}
			$delete_bulk = "";
			$delete = "";
			if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				$delete = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}

			$reject=""; 
			$approve="";
			if($row->status_name == 'Waiting Approval' && $row->direct_id == $karyawan_id){
				$reject = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="reject('."'".$row->id."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" href="javascript:void(0);" onclick="approve('."'".$row->id."'".')" role="button"><i class="fa fa-check"></i></a>';
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
				$row->destination,
				$row->start_date,
				$row->end_date,
				$row->reason,
				$row->status_name

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


	public function add_data($post) { 

		$start_date 	= date_create($post['start_date']); 
		$f_start_date 	= date_format($start_date,"Y-m-d H:i:s");
		$end_date 		= date_create($post['end_date']); 
		$f_end_date 	= date_format($end_date,"Y-m-d H:i:s");

		
		
  		if(!empty($post['employee'])){ 
  			$data = [
				'employee_id' 			=> trim($post['employee']),
				'destination' 			=> trim($post['destination']),
				'start_date'			=> $f_start_date,
				'end_date' 				=> $f_end_date,
				'reason' 				=> trim($post['reason']),
				'status_id' 			=> 1, //waiting approval
				'created_date'			=> date("Y-m-d H:i:s")
				
			];
			$rs = $this->db->insert($this->table_name, $data);
			$lastId = $this->db->insert_id();

			if($rs){
				if(isset($post['type'])){
					$item_num = count($post['type']); // cek sum
					$item_len_min = min(array_keys($post['type'])); // cek min key index
					$item_len = max(array_keys($post['type'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						$upload_doc = $this->upload_file('1', 'document'.$i.'', FALSE, '', TRUE, $i);
						$document = '';
						if($upload_doc['status']){ 
							$document = $upload_doc['upload_file'];
						} else if(isset($upload_doc['error_warning'])){ 
							echo $upload_doc['error_warning']; exit;
						}

						if(isset($post['type'][$i])){
							$itemData = [
								'business_trip_id' 	=> $lastId,
								'bustrip_type_id' 	=> trim($post['type'][$i]),
								'document' 			=> $document,
								'amount' 			=> trim($post['amount'][$i]),
								'description' 		=> trim($post['description'][$i])
							];

							$this->db->insert('business_trip_detail', $itemData);
						}
					}
				}

				return $rs;
			}else return null;


  		}else return null;

	}  

	public function edit_data($post) { 
		$start_date 	= date_create($post['start_date']); 
		$f_start_date 	= date_format($start_date,"Y-m-d H:i:s");
		$end_date 		= date_create($post['end_date']); 
		$f_end_date 	= date_format($end_date,"Y-m-d H:i:s");


		if(!empty($post['id'])){ 
			$data = [
				'employee_id' 			=> trim($post['employee']),
				'destination' 			=> trim($post['destination']),
				'start_date'			=> $f_start_date,
				'end_date' 				=> $f_end_date,
				'reason' 				=> trim($post['reason']),
				'updated_date'			=> date("Y-m-d H:i:s")
				
			];
			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

			if($rs){ 
				if(isset($post['type'])){
					$item_num = count($post['type']); // cek sum
					$item_len_min = min(array_keys($post['type'])); // cek min key index
					$item_len = max(array_keys($post['type'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						$hdnid = trim($post['hdnid'][$i]);

						if(!empty($hdnid)){ //update

							$hdndocument = trim($post['hdndocument'.$i]);
							$upload_doc = $this->upload_file('1', 'document'.$i.'', FALSE, '', TRUE, $i);
							$document = '';
							if($upload_doc['status']){ 
								$document = $upload_doc['upload_file'];
							} else if(isset($upload_doc['error_warning'])){ 
								echo $upload_doc['error_warning']; exit;
							}

							if($document == '' && $hdndocument != ''){
								$document = $hdndocument;
							}

							if(isset($post['type'][$i])){
								$itemData = [
									'bustrip_type_id' 	=> trim($post['type'][$i]),
									'document' 			=> $document,
									'amount' 			=> trim($post['amount'][$i]),
									'description' 		=> trim($post['description'][$i])
								];

								$this->db->update("business_trip_detail", $itemData, "id = '".$hdnid."'");
							}

						}else{ //insert

							$upload_doc = $this->upload_file('1', 'document'.$i.'', FALSE, '', TRUE, $i);
							$document = '';
							if($upload_doc['status']){ 
								$document = $upload_doc['upload_file'];
							} else if(isset($upload_doc['error_warning'])){ 
								echo $upload_doc['error_warning']; exit;
							}

							if(isset($post['type'][$i])){ 
								$itemData = [
									'business_trip_id' 	=> $post['id'],
									'bustrip_type_id' 	=> trim($post['type'][$i]),
									'document' 			=> $document,
									'amount' 			=> trim($post['amount'][$i]),
									'description' 		=> trim($post['description'][$i])
								];

								$this->db->insert('business_trip_detail', $itemData);
							}

						}
						
					}
				}


				return $rs;
			}else return null;

		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.full_name, b.direct_id,
					(case 
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					end) as status_name 
					from business_trip a left join employees b on b.id = a.employee_id
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
				'destination' 	=> $v["C"],
				'start_date' 	=> $v["D"],
				'end_date' 		=> $v["E"],
				'reason' 		=> $v["F"],
				'status_id' 	=> $v["G"],
				'created_date' 	=> date("Y-m-d H:i:s")
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$sql = "select a.id, b.full_name, a.destination, a.start_date, a.end_date, a.reason,
				(case 
				when a.status_id = 1 then 'Waiting Approval'
				when a.status_id = 2 then 'Approved'
				when a.status_id = 3 then 'Rejected'
				else ''
				end) as status_name 
				from business_trip a left join employees b on b.id = a.employee_id
				order by a.id asc

		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getNewBustripRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getBustripRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			$msBustripType = $this->db->query("select * from master_bustrip_type")->result(); 
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value=""/></td>';
			$data 	.= '<td>'.$this->return_build_chosenme($msBustripType,'','','','type['.$row.']','type','type','','id','name','','','',' data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','amount['.$row.']','','amount','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_fileinput('document'.$row.'','','','document','text-align: right;','data-id="'.$row.'" ').'</td>';
			$data 	.= '<td>'.$this->return_build_txt('','description['.$row.']','','description','text-align: right;','data-id="'.$row.'" ').'</td>';
			

			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getBustripRows($id,$view,$print=FALSE){ 
		$uri = $_SERVER['REQUEST_URI'];
	 	$xpl = explode("/",$uri);
	 	$url = $_SERVER['SERVER_NAME'].'/'.$xpl[1].'/uploads/bustrip';


		$dt = ''; 
		
		$rs = $this->db->query("select a.*, b.name as type_name from business_trip_detail a 
								left join master_bustrip_type b on b.id = a.bustrip_type_id where a.business_trip_id = '".$id."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			/*if($view){
				$arrSat = json_decode(json_encode($msObat), true);
				$arrS = [];
				foreach($arrSat as $ai){
					$arrS[$ai['id']] = $ai;
				}
			}*/
			foreach ($rd as $f){
				$no = $row+1;
				$msBustripType = $this->db->query("select * from master_bustrip_type")->result();

				if(!$view){ 
					$viewdoc = '';
					if($f->document != ''){
						$viewdoc = '<a href="'.base_url().'uploads/bustrip/'.$f->document.'" target="_blank">View</a>';
					}

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';
					$dt .= '<td>'.$this->return_build_chosenme($msBustripType,'',isset($f->bustrip_type_id)?$f->bustrip_type_id:1,'','type['.$row.']','type','type','','id','name','','','',' data-id="'.$row.'" ').'</td>';
				
					$dt .= '<td>'.$this->return_build_fileinput('document'.$row.'','','','document','text-align: right;','data-id="'.$row.'" ').$viewdoc.' <input type="hidden" id="hdndocument'.$row.'" name="hdndocument'.$row.'" value="'.$f->document.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->description,'description['.$row.']','','description','text-align: right;','data-id="'.$row.'" ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($f->amount,'amount['.$row.']','','amount','text-align: right;','data-id="'.$row.'" ').'</td>';
					
					$dt .= '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete" onclick="del(\''.$row.'\',\''.$f->id.'\')"></td>';
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
					
					$dt .= '<td>'.$no.'</td>';
					$dt .= '<td>'.$f->type_name.'</td>';
					$dt .= '<td>'.$f->amount.'</td>';
					$dt .= '<td><a href="'.base_url().'uploads/bustrip/'.$f->document.'" target="_blank">View</a></td>';
					$dt .= '<td>'.$f->description.'</td>';
					
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}



}
