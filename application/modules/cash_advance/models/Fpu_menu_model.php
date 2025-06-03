<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fpu_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "cash_advance/fpu_menu";
 	protected $table_name 				= _PREFIX_TABLE."fpu";
 	protected $primary_key 				= "id";

 	/* upload */
 	protected $attachment_folder	= "./uploads/fpu";
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
			'dt.fpu_number',
			'dt.request_date',
			'dt.prepared_by_name',
			'dt.requested_by_name',
			'dt.status_name'
		];
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name as prepared_by_name, c.full_name as requested_by_name
					, d.name as status_name    
					from fpu a left join employees b on b.id = a.prepared_by
					left join employees c on c.id = a.requested_by
					left join master_status_cashadvance d on d.id = a.status_id
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
			/*if($row->status_name == 'Waiting Approval' && $row->direct_id == $karyawan_id){
				$reject = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="reject('."'".$row->id."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" href="javascript:void(0);" onclick="approve('."'".$row->id."'".')" role="button"><i class="fa fa-check"></i></a>';
			}*/

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$reject.'
					'.$approve.'
				</div>',
				$row->id,
				$row->fpu_number,
				$row->request_date,
				$row->prepared_by_name,
				$row->requested_by_name,
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

	public function get_sisa_plafon($emp_id, $type_id){
		$getplafon = $this->db->query("select a.id, b.nominal_plafon, b.reimburs_type_id 
				from employees a left join master_plafon b on b.grade_id = a.grade_id and b.reimburs_type_id = '".$type_id."' where a.id = '".$emp_id."' ")->result(); 
		$plafon=0;
		if($getplafon != ''){
			$plafon = $getplafon[0]->nominal_plafon;
		}

		$getpemakaian = $this->db->query("select sum(nominal_reimburse) as total_pemakaian from medicalreimbursements where employee_id = '".$emp_id."' and reimburs_type_id = '".$type_id."' ")->result(); 
		$pemakaian=0;
		if($getpemakaian != ''){
			$pemakaian = $getpemakaian[0]->total_pemakaian;
		}

		$sisa = $plafon-$pemakaian;
		if($sisa <= 0){
			$sisa=0;
		} 




		return $sisa;
	}


	// Get next number 
	public function getNextNumber() { 
		
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		

		$cek = $this->db->query("select * from fpu where SUBSTRING(fpu_number, 4, 4) = '".$period."'");
		$rs_cek = $cek->result_array();

		if(empty($rs_cek)){
			$num = '0001';
		}else{
			$cek2 = $this->db->query("select max(fpu_number) as maxnum from fpu where SUBSTRING(fpu_number, 4, 4) = '".$period."'");
			$rs_cek2 = $cek2->result_array();
			$dt = $rs_cek2[0]['maxnum']; 
			$getnum = substr($dt,7); 
			$num = str_pad($getnum + 1, 4, 0, STR_PAD_LEFT);
			
		}

		return $num;
		
	} 


	public function add_data($post) { 

		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

		$lettercode = ('FPU'); // ca code
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		
		$runningnumber = $this->getNextNumber(); // next count number
		$nextnum 	= $lettercode.'/'.$period.'/'.$runningnumber;


		
  		if(!empty($post['requested_by'])){ 
  			$upload_doc = $this->upload_file('1', 'document', FALSE, '', TRUE);
			$document = '';
			if($upload_doc['status']){ 
				$document = $upload_doc['upload_file'];
			} else if(isset($upload_doc['error_warning'])){ 
				echo $upload_doc['error_warning']; exit;
			}

			$data = [
				'fpu_number' 	=> $nextnum,
				'request_date' 	=> trim($post['request_date']),
				'prepared_by' 	=> $karyawan_id,
				'requested_by'	=> trim($post['requested_by']),
				'total_biaya' 	=> trim($post['total_biaya']),
				'dokumen' 		=> $document,
				'status_id' 	=> 1 //waiting approval
			];
			$rs = $this->db->insert($this->table_name, $data);
			$lastId = $this->db->insert_id();

			if($rs){
				if(isset($post['name'])){
					$item_num = count($post['name']); // cek sum
					$item_len_min = min(array_keys($post['name'])); // cek min key index
					$item_len = max(array_keys($post['name'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						if(isset($post['name'][$i])){
							$itemData = [
								'fpu_id' 		=> $lastId,
								'name' 			=> trim($post['name'][$i]),
								'amount' 		=> trim($post['amount'][$i]),
								'ppn_pph' 		=> trim($post['ppn_pph'][$i]),
								'total_amount'	=> trim($post['total_amount'][$i]),
								'notes' 		=> trim($post['notes'][$i])
							];

							$this->db->insert('fpu_details', $itemData);
						}
					}
				}

				return $rs;
			}else return null;

  		}else return null;

	}  

	public function edit_data($post) { 
		$date 		= date_create($post['date']); 
		$f_date 	= date_format($date,"Y-m-d H:i:s");
		
		$sisa_plafon = $this->get_sisa_plafon($post['employee'], $post['type']);


		if(!empty($post['id'])){ 
			$getdata = $this->db->query("select * from medicalreimbursements where id = '".$post['id']."' ")->result(); 
			$curr_nominal_reimburs = $getdata[0]->nominal_reimburse;
			$sisa_plafon = $sisa_plafon+$curr_nominal_reimburs;

			if($post['nominal_reimburs'] <= $sisa_plafon){ //jika masih ada plafon
				$data = [
					'date_reimbursment' 	=> $f_date,
					'employee_id' 			=> trim($post['employee']),
					'reimburs_type_id' 		=> trim($post['type']),
					'reimburse_for'			=> trim($post['reimburs_for']),
					'atas_nama' 			=> trim($post['atas_nama']),
					'diagnosa' 				=> trim($post['diagnosa']),
					'nominal_billing' 		=> trim($post['nominal_billing']),
					'nominal_reimburse' 	=> trim($post['nominal_reimburs']),
					'updated_at'			=> date("Y-m-d H:i:s")
				];

				$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

				if($rs){
					if(isset($post['subtype'])){
						$item_num = count($post['subtype']); // cek sum
						$item_len_min = min(array_keys($post['subtype'])); // cek min key index
						$item_len = max(array_keys($post['subtype'])); // cek max key index
					} else {
						$item_num = 0;
					}

					if($item_num>0){
						for($i=$item_len_min;$i<=$item_len;$i++) 
						{
							$hdnid = trim($post['hdnid'][$i]);

							if(!empty($hdnid)){ //update

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

								if(isset($post['subtype'][$i])){
									$itemData = [
										'subtype_id' 		=> trim($post['subtype'][$i]),
										'document' 			=> $document,
										'biaya' 			=> trim($post['biaya'][$i]),
										'notes' 			=> trim($post['notes'][$i])
									];

									$this->db->update("reimbursement_detail", $itemData, "id = '".$hdnid."'");
								}

							}else{ //insert

								$upload_emp_photo = $this->upload_file('1', 'document'.$i.'', FALSE, '', TRUE, $i);
								$document = '';
								if($upload_emp_photo['status']){ 
									$document = $upload_emp_photo['upload_file'];
								} else if(isset($upload_emp_photo['error_warning'])){ 
									echo $upload_emp_photo['error_warning']; exit;
								}

								if(isset($post['subtype'][$i])){
									$itemData = [
										'reimbursement_id' 	=> $post['id'],
										'subtype_id' 		=> trim($post['subtype'][$i]),
										'document' 			=> $document,
										'biaya' 			=> trim($post['biaya'][$i]),
										'notes' 			=> trim($post['notes'][$i])
									];

									$this->db->insert('reimbursement_detail', $itemData);
								}

							}
							
						}
					}
				}

				return $rs;

			}else{
				return null;
			}

		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.full_name as prepared_by_name, c.full_name as requested_by_name
						, d.name as status_name    
						from fpu a left join employees b on b.id = a.prepared_by
						left join employees c on c.id = a.requested_by
						left join master_status_cashadvance d on d.id = a.status_id
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
				'date_reimbursment' 	=> $v["B"],
				'employee_id' 			=> $v["C"],
				'reimburse_for' 		=> $v["D"],
				'atas_nama' 			=> $v["E"],
				'diagnosa' 				=> $v["F"],
				'nominal_billing' 		=> $v["G"],
				'nominal_reimburse' 	=> $v["H"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{
		$sql = "select a.id, a.date_reimbursment, b.full_name as employee_name, c.name as reimburse_for_name, a.atas_nama, a.diagnosa, a.nominal_billing, a.nominal_reimburse
			from medicalreimbursements a left join employees b on b.id = a.employee_id
			left join master_reimbursfor_type c on c.id = a.reimburse_for order by a.id asc

		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getNewFpuRow($row,$id=0,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getFpuRows($id,$view);
		} else { 
			$data = '';
			$no = $row+1;
			
			$data 	.= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value=""/></td>';

			$data 	.= '<td>'.$this->return_build_txt('','name['.$row.']','','name','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','amount['.$row.']','','amount','text-align: right;','data-id="'.$row.'" onkeyup="set_total_amount(this)" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','ppn_pph['.$row.']','','ppn_pph','text-align: right;','data-id="'.$row.'" onkeyup="set_total_amount2(this)" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txt('','total_amount['.$row.']','','total_amount','text-align: right;','data-id="'.$row.'" ').'</td>';

			$data 	.= '<td>'.$this->return_build_txtarea('','notes['.$row.']','','notes','text-align: right;','data-id="'.$row.'" ').'</td>';

			$hdnid='';
			$data 	.= '<td><input type="button" class="ibtnDel btn btn-md btn-danger " onclick="del(\''.$row.'\',\''.$hdnid.'\')" value="Delete"></td>';
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getFpuRows($id,$view,$print=FALSE){ 

		$dt = ''; 
		
		$rs = $this->db->query("select * from fpu_details where fpu_id = '".$id."' ")->result(); 
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				
				if(!$view){ 

					$dt .= '<tr>';

					$dt .= '<td>'.$no.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($f->name,'name['.$row.']','','name','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->amount,'amount['.$row.']','','amount','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->ppn_pph,'ppn_pph['.$row.']','','ppn_pph','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->total_amount,'total_amount['.$row.']','','total_amount','text-align: right;','data-id="'.$row.'" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($f->notes,'notes['.$row.']','','notes','text-align: right;','data-id="'.$row.'" ').'</td>';

					
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
					$dt .= '<td>'.$f->name.'</td>';
					$dt .= '<td>'.$f->amount.'</td>';
					$dt .= '<td>'.$f->ppn_pph.'</td>';
					$dt .= '<td>'.$f->total_amount.'</td>';
					$dt .= '<td>'.$f->notes.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}



}
