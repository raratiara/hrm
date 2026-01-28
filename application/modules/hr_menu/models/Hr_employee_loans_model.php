<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hr_employee_loans_model extends MY_Model
{
	/* Module */
 	protected $folder_name			= "hr_menu/hr_employee_loans";
 	protected $table_name 			= _PREFIX_TABLE."loan"; 
 	protected $table_karyawan 		= _PREFIX_TABLE."employees"; 
 	protected $primary_key 			= "id"; 

 	
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
			'dt.nominal_pinjaman', 
			'dt.tenor', 
			'dt.sisa_tenor', 
			'dt.bunga_per_bulan', 
			'dt.nominal_cicilan_per_bulan',  
			'dt.date_start_cicilan' ,
			'dt.direct_id',
			'dt.id_employee',
			'dt.status_name',
			'dt.current_approval_level',
			'dt.is_approver',
			'dt.current_role_id',
			'dt.current_role_name',
			'dt.is_approver_view'
		];


		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1 && $getdata[0]->id_groups != 4){ //bukan super user && bukan HR admin
			$whr = ' where ao.id_employee = "' . $karyawan_id . '" or ao.direct_id = "' . $karyawan_id . '" or ao.is_approver_view = 1  ';
		}

		/*$sIndexColumn = 'a.'.$this->primary_key;
		$sTable = ' '.$this->table_name.' a
					LEFT JOIN '.$this->table_karyawan.' b ON b.id=a.id_employee  
					'.$whr.'
					';*/

		$sIndexColumn = $this->primary_key;
		/*$sTable = '(SELECT ao.* 
					FROM (
					    select a.id, a.nominal_pinjaman, a.tenor, a.sisa_tenor, a.bunga_per_bulan, a.nominal_cicilan_per_bulan, a.id_employee, b.full_name, IF(a.date_start_cicilan IS NULL,"",a.date_start_cicilan) as date_start_cicilan,
							b.direct_id,
							(case 
								when a.status_id = 1 then "Waiting Approval"
								when a.status_id = 2 then "Approved"
								when a.status_id = 3 then "Rejected"
								else ""
							end) as status_name 
							from loan a 
							left join employees b on b.id = a.id_employee
					) ao
					'.$whr.'
				)dt';*/


		$sTable = '(SELECT ao.* 
					FROM (
					    select a.id, a.nominal_pinjaman, a.tenor, a.sisa_tenor, a.bunga_per_bulan, a.nominal_cicilan_per_bulan, a.id_employee, b.full_name, IF(a.date_start_cicilan IS NULL,"",a.date_start_cicilan) as date_start_cicilan,
							b.direct_id,
							aa.name as status_name,
							max(d2.current_approval_level) AS current_approval_level,
							max(h.role_id) AS current_role_id,
							max(i.role_name) AS current_role_name,
							GROUP_CONCAT(g.employee_id) AS all_employeeid_approver,
							max(
								IF(
									i.role_name = "Direct",
									b.direct_id,
									(
										SELECT GROUP_CONCAT(employee_id) 
										FROM approval_matrix_role_pic 
										WHERE approval_matrix_role_id = h.role_id
									)
								)
							) AS current_employeeid_approver,
							CASE 
								WHEN FIND_IN_SET('.$karyawan_id.', GROUP_CONCAT(g.employee_id)) > 0 THEN 1 
								ELSE 0 
							END AS is_approver_view,
							CASE 
								WHEN FIND_IN_SET(
									'.$karyawan_id.', 
									(
										SELECT GROUP_CONCAT(employee_id) 
										FROM approval_matrix_role_pic 
										WHERE approval_matrix_role_id = max(h.role_id)
									)
								) > 0 THEN 1
								WHEN max(i.role_name) = "Direct" AND max(b.direct_id) = '.$karyawan_id.' THEN 1  
								ELSE 0 
							END AS is_approver 
						from loan a 
						left join employees b on b.id = a.id_employee
						left join master_status_loan aa on aa.id = a.status_id
						LEFT JOIN approval_path d2 ON d2.trx_id = a.id AND d2.approval_matrix_type_id = 9
						LEFT JOIN approval_matrix bb ON bb.id = d2.approval_matrix_id
						LEFT JOIN approval_matrix_detail cc ON cc.approval_matrix_id = bb.id
						LEFT JOIN approval_matrix_role dd ON dd.id = cc.role_id
						LEFT JOIN approval_path_detail ee ON ee.approval_path_id = d2.id AND ee.approval_level = cc.approval_level
						LEFT JOIN approval_matrix_role_pic g ON g.approval_matrix_role_id = cc.role_id
						LEFT JOIN approval_matrix_detail h ON h.approval_matrix_id = d2.approval_matrix_id AND h.approval_level = d2.current_approval_level
						LEFT JOIN approval_matrix_role i ON i.id = h.role_id
						GROUP BY a.id
						) ao
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
			$is_approver = 0;
			if($row->is_approver == 1){
				$is_approver = 1;
			}



			$detail = "";
			if (_USER_ACCESS_LEVEL_DETAIL == "1")  {
				$detail = '<a class="btn btn-xs btn-success detail-btn" style="background-color: #112D80; border-color: #112D80;" href="javascript:void(0);" onclick="detail('."'".$row->id."'".')" role="button"><i class="fa fa-search-plus"></i></a>';
			}
			$edit = "";
			if (_USER_ACCESS_LEVEL_UPDATE == "1")  {
				if($row->status_name == 'Pinjaman Berjalan' && ($getdata[0]->id_groups == 1 || $getdata[0]->id_groups == 4)){ //superadmin / HR
					$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
				}
				/*if($row->status_name == 'Waiting Approval' && $row->employee_id == $karyawan_id){
					$edit = '<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('."'".$row->id."'".')" role="button"><i class="fa fa-pencil"></i></a>';
				}*/
			}
			$delete_bulk = "";
			$delete = "";
			/*if (_USER_ACCESS_LEVEL_DELETE == "1")  {
				$delete_bulk = '<input name="ids[]" type="checkbox" class="data-check" value="'.$row->id.'">';
				
				$delete = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="deleting('."'".$row->id."'".')" role="button"><i class="fa fa-trash"></i></a>';
			}*/


			$reject = "";
			$approve = "";
			if ($row->status_name == 'Waiting Approval' && $is_approver == 1) {
				$reject = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="reject('."'".$row->id."'".','."'".$row->current_approval_level."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" style="background-color: #2c9e1fff; border-color: #2c9e1fff;" href="javascript:void(0);" onclick="approve('."'".$row->id."'".','."'".$row->current_approval_level."'".')" role="button"><i class="fa fa-check"></i></a>';
			}


			if ($row->status_name == 'Menunggu Pencairan') {
				$update_pencairan = '<a class="btn btn-xs btn-warning" style="background-color: #FFA500;" href="javascript:void(0);" onclick="upd_pencairan('."'".$row->id."'".')" role="button">Update Pencairan</a>';
			}
			


			
			$nominal_pinjaman = number_format($row->nominal_pinjaman, 0, ',', '.');
			$nominal_cicilan_per_bulan = number_format($row->nominal_cicilan_per_bulan, 0, ',', '.');

			array_push($output["aaData"],array(
				
				'<div class="action-buttons">
					'.$detail.'
					'.$reject.'
					'.$approve.'
					'.$edit.'
					'.$update_pencairan.'
				</div>',
				$row->id,
				$row->full_name,
				$nominal_pinjaman, 
				$row->tenor, 
				$row->sisa_tenor,
				$row->bunga_per_bulan,
				$nominal_cicilan_per_bulan,
				$row->date_start_cicilan,
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


	public function getApprovalMatrix($work_location_id, $approval_type_id, $leave_type_id='', $amount='', $trx_id){

		if($work_location_id != '' && $approval_type_id != ''){
			if($approval_type_id == 9){ ///Loan
				if($amount == ''){
					$amount=0;
				}
				
				$getmatrix = $this->db->query("select * from approval_matrix where approval_type_id = '".$approval_type_id."' and work_location_id = '".$work_location_id."' and (
						(".$amount." >= min and ".$amount." <= max and min != '' and max != '') or
						(".$amount." >= min and min != '' and max = '') or
						(".$amount." <= max and max != '' and min = '')
					)  ")->result(); 

				if(empty($getmatrix)){
					$getmatrix = $this->db->query("select * from approval_matrix where approval_type_id = '".$approval_type_id."' and work_location_id = '".$work_location_id."' and ((min is null or min = '') and (max is null or max = ''))   ")->result(); 
				}

				
				if(!empty($getmatrix)){
					$approvalMatrixId = $getmatrix[0]->id;
					if($approvalMatrixId != ''){
						$dataApproval = [
							'approval_matrix_type_id' 	=> $approval_type_id, 
							'trx_id' 					=> $trx_id,
							'approval_matrix_id' 		=> $approvalMatrixId,
							'current_approval_level' 	=> 1
						];
						$rs = $this->db->insert("approval_path", $dataApproval);
						$approval_path_id = $this->db->insert_id();
						if($rs){
							$dataApprovalDetail = [
								'approval_path_id' 	=> $approval_path_id, 
								'approval_level' 	=> 1
							];
							$this->db->insert("approval_path_detail", $dataApprovalDetail);
						}
					}
				}
			}
		}

	}

  
	

	public function edit_data($post) {   
		if(!empty($post['id'])){  
			$data = [ 
				'sisa_tenor' 	=> trim($post['sisa_tenor'] ?? ''),
				'status_id' 	=> trim($post['status'] ?? ''),   
				'update_date'	=> date("Y-m-d H:i:s")
			];

			$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]); 


			/// update detail pembbayaran
			if(isset($post['hdnid'])){
				$item_num = count($post['hdnid']); // cek sum
				$item_len_min = min(array_keys($post['hdnid'])); // cek min key index
				$item_len = max(array_keys($post['hdnid'])); // cek max key index
			} else {
				$item_num = 0;
			}

			if($item_num>0){
				for($i=$item_len_min;$i<=$item_len;$i++) 
				{
					$hdnid = trim($post['hdnid'][$i]);

					if(!empty($hdnid)){ //update

						if(isset($post['hdnid'][$i])){
							$itemData = [
								'status' 	=> trim($post['status_bayar'][$i]),
								'tgl_bayar' => trim($post['tgl_bayar'][$i])
							];

							$this->db->update("loan_detail", $itemData, "id = '".$hdnid."'");
						}

					}
					
				}
			}



			return $rs;

		} else return null;
	}  

	public function getRowData($id) {

		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1 && $getdata[0]->id_groups != 4){ //bukan super user && bukan HR admin
			$whr = ' where ao.id_employee = "' . $karyawan_id . '" or ao.direct_id = "' . $karyawan_id . '" or ao.is_approver_view = 1  ';
		}

		/*$rs = $this->db->select('*')->where([$this->primary_key => $id])->get($this->table_name)->row();
		
		if(!empty($rs->id_employee)){
			$rd = $this->db->select('full_name')->where([$this->primary_key => $rs->id_employee])->get($this->table_karyawan)->row();
			$rs = (object) array_merge((array) $rs, (array) $rd);
		} else {
			$rs = (object) array_merge((array) $rs, ['full_name'=>'']);
		}*/

		$mTable = '(SELECT ao.* 
					FROM (
					    select a.id, a.nominal_pinjaman, a.tenor, a.sisa_tenor, a.bunga_per_bulan, a.nominal_cicilan_per_bulan, a.id_employee, b.full_name, IF(a.date_start_cicilan IS NULL,"",a.date_start_cicilan) as date_start_cicilan,
					    	a.date_pengajuan, a.date_persetujuan, a.date_pencairan,
							b.direct_id, a.reject_reason,
							aa.name as status_name, a.status_id,
							max(d2.current_approval_level) AS current_approval_level,
							max(h.role_id) AS current_role_id,
							max(i.role_name) AS current_role_name,
							GROUP_CONCAT(g.employee_id) AS all_employeeid_approver,
							max(
								IF(
									i.role_name = "Direct",
									b.direct_id,
									(
										SELECT GROUP_CONCAT(employee_id) 
										FROM approval_matrix_role_pic 
										WHERE approval_matrix_role_id = h.role_id
									)
								)
							) AS current_employeeid_approver,
							CASE 
								WHEN FIND_IN_SET('.$karyawan_id.', GROUP_CONCAT(g.employee_id)) > 0 THEN 1 
								ELSE 0 
							END AS is_approver_view,
							CASE 
								WHEN FIND_IN_SET(
									'.$karyawan_id.', 
									(
										SELECT GROUP_CONCAT(employee_id) 
										FROM approval_matrix_role_pic 
										WHERE approval_matrix_role_id = max(h.role_id)
									)
								) > 0 THEN 1
								WHEN max(i.role_name) = "Direct" AND max(b.direct_id) = '.$karyawan_id.' THEN 1  
								ELSE 0 
							END AS is_approver 
						from loan a 
						left join employees b on b.id = a.id_employee
						left join master_status_loan aa on aa.id = a.status_id
						LEFT JOIN approval_path d2 ON d2.trx_id = a.id AND d2.approval_matrix_type_id = 9
						LEFT JOIN approval_matrix bb ON bb.id = d2.approval_matrix_id
						LEFT JOIN approval_matrix_detail cc ON cc.approval_matrix_id = bb.id
						LEFT JOIN approval_matrix_role dd ON dd.id = cc.role_id
						LEFT JOIN approval_path_detail ee ON ee.approval_path_id = d2.id AND ee.approval_level = cc.approval_level
						LEFT JOIN approval_matrix_role_pic g ON g.approval_matrix_role_id = cc.role_id
						LEFT JOIN approval_matrix_detail h ON h.approval_matrix_id = d2.approval_matrix_id AND h.approval_level = d2.current_approval_level
						LEFT JOIN approval_matrix_role i ON i.id = h.role_id
						GROUP BY a.id
						) ao
						'.$whr.'
					)dt';

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();


			
		unset($rs->date_insert);
		unset($rs->insert_by);
		unset($rs->date_update);
		unset($rs->update_by); 
		
		return $rs;
	} 


	public function getNewExpensesRow($row,$id=0,$view=FALSE)
	{ 
		$data = $this->getExpensesRows($id,$view);


		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getExpensesRows($id,$view,$print=FALSE){ 
		
		$dt = ''; 
		
		$rs = $this->db->query("select * from loan_detail where loan_id = '".$id."' ")->result(); 
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

			$msStatusPembayaran = [
			    (object)[
			        'id'   => 'Belum',
			        'name' => 'Belum'
			    ],
			    (object)[
			        'id'   => 'Lunas',
			        'name' => 'Lunas'
			    ]
			];

			foreach ($rd as $f){
				$no = $row+1;
				
				if(!$view){ 
					
					$dt .= '<tr>';

					$dt .= '<td style="text-align: center">'.$f->cicilan_ke.'<input type="hidden" id="hdnid'.$row.'" name="hdnid['.$row.']" value="'.$f->id.'"/></td>';

					$dt .= '<td>'.$f->tgl_jatuh_tempo.'</td>';
				
					$dt .= '<td>'.$this->return_build_chosenme($msStatusPembayaran,'',isset($f->status)?$f->status:1,'','status_bayar['.$row.']','status_bayar','status_bayar','','id','name','','','',' data-id="'.$row.'" ').'</td>';

					/*$dt .= '<td>'.$this->return_build_txt($f->tgl_bayar,'tgl_bayar['.$row.']','','tgl_bayar','text-align: right;','data-id="'.$row.'" ').'</td>';*/

					$dt .= '<td><input type="date" class="form-control" name="tgl_bayar[' . $row . ']" value="'.$f->tgl_bayar.'" data-id="' . $row . '" /></td>';
					
					
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

					$tgl_bayar = $f->tgl_bayar;
					if($f->tgl_bayar == '0000-00-00'){
						$tgl_bayar = '';
					}
					
					$dt .= '<td>'.$f->cicilan_ke.'</td>';
					$dt .= '<td>'.$f->tgl_jatuh_tempo.'</td>';
					$dt .= '<td>'.$f->status.'</td>';
					$dt .= '<td>'.$tgl_bayar.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


	/*
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
		*/ 
}