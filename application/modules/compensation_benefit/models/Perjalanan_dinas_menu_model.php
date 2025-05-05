<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perjalanan_dinas_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "compensation_benefit/perjalanan_dinas_menu";
 	protected $table_name 				= _PREFIX_TABLE."business_trip";
 	protected $primary_key 				= "id";

 


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
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name, b.direct_id,
					(case 
					when a.status_id = 1 then "Waiting Approval"
					when a.status_id = 2 then "Approved"
					when a.status_id = 3 then "Rejected"
					else ""
					end) as status_name 
					from business_trip a left join employees b on b.id = a.employee_id)dt';

		

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


		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;

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


	public function add_data($post) { 

		$date 		= date_create($post['date']); 
		$f_date 	= date_format($date,"Y-m-d H:i:s");

		$sisa_plafon = $this->get_sisa_plafon($post['employee'], $post['type']);

		
  		if(!empty($post['date']) && !empty($post['employee'])){ 
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
					'created_at'			=> date("Y-m-d H:i:s"),
					'status_id' 			=> 1 //waiting approval
				];
				$rs = $this->db->insert($this->table_name, $data);
				$lastId = $this->db->insert_id();

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
							$upload_emp_photo = $this->upload_file('1', 'document'.$i.'', FALSE, '', TRUE, $i);
							$document = '';
							if($upload_emp_photo['status']){ 
								$document = $upload_emp_photo['upload_file'];
							} else if(isset($upload_emp_photo['error_warning'])){ 
								echo $upload_emp_photo['error_warning']; exit;
							}

							if(isset($post['subtype'][$i])){
								$itemData = [
									'reimbursement_id' 	=> $lastId,
									'subtype_id' 		=> trim($post['subtype'][$i]),
									'document' 			=> $document,
									'biaya' 			=> trim($post['biaya'][$i]),
									'qty' 				=> trim($post['qty'][$i]),
									'notes' 			=> trim($post['notes'][$i])
								];

								$this->db->insert('reimbursement_detail', $itemData);
							}
						}
					}
				}

				return $rs;

  			}else{
  				return null;
  			}

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
										'qty' 				=> trim($post['qty'][$i]),
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
										'qty' 				=> trim($post['qty'][$i]),
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
		$mTable = '(select a.*, b.full_name as employee_name, c.name as reimburse_for_name,
						d.name as reimburs_type_name
						from medicalreimbursements a left join employees b on b.id = a.employee_id
						left join master_reimbursfor_type c on c.id = a.reimburse_for 
					    left join master_reimburs_type d on d.id = a.reimburs_type_id
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



}
