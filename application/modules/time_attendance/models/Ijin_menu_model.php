<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ijin_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "time_attendance/ijin_menu";
 	protected $table_name 				= _PREFIX_TABLE."leave_absences";
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
			'id',
			'dt.full_name',
			'dt.date_leave_start',
			'dt.date_leave_end',
			'dt.leave_name',
			'dt.reason',
			'dt.total_leave',
			'dt.status',
			'dt.direct_id'
		];
		
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1 && $getdata[0]->id_groups != 4){ //bukan super user && bukan HR admin
			$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name, c.name as leave_name,
					(case
						when a.status_approval = 1 then "Waiting Approval"
						when a.status_approval = 2 then "Approved"
						when a.status_approval = 3 then "Rejected"
						 end) as status, b.direct_id
					from leave_absences a left join employees b on b.id = a.employee_id
					left join master_leaves c on c.id = a.masterleave_id
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

		$getdirect = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$direct_karyawan_id = $getdirect[0]->id_karyawan;

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
			if($row->status == 'Waiting Approval' && $row->direct_id == $direct_karyawan_id){
				/*$reject = '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="reject('."'".$row->id."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" href="javascript:void(0);" onclick="approve('."'".$row->id."'".')" role="button"><i class="fa fa-check"></i></a>';*/

				$reject = '<a class="btn btn-xs btn-danger" style="background-color: #A01818;" href="javascript:void(0);" onclick="reject('."'".$row->id."'".')" role="button"><i class="fa fa-times"></i></a>';
				$approve = '<a class="btn btn-xs btn-warning" style="background-color: #2c9e1fff; border-color: #2c9e1fff;" href="javascript:void(0);" onclick="approve('."'".$row->id."'".')" role="button"><i class="fa fa-check"></i></a>';
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
				$row->date_leave_start,
				$row->date_leave_end,
				$row->leave_name,
				$row->reason,
				$row->total_leave,
				$row->status

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

	public function dayCount($from, $to) {
	    $first_date = strtotime($from);
	    $second_date = strtotime($to);
	    $days_diff = $second_date - $first_date;
	    return date('d',$days_diff);
	}

	public function add_data($post) { 

		$date_start 	= date_create($post['date_start']);
		$date_end 		= date_create($post['date_end']);
		$f_date_start 	= date_format($date_start,"Y-m-d");
		$f_date_end 	= date_format($date_end,"Y-m-d");


		if($post['employee'] != '' && $post['date_start'] != '' && $post['date_end'] != '' && $post['leave_type'] != ''){
			/*$cek_sisa_cuti 	= $this->get_data_sisa_cuti($post['employee'], $f_date_start, $f_date_end);
			$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti;*/

			$cek_sisa_cuti = $this->get_data_sisa_cuti($post['employee'], $f_date_start, $f_date_end);
			if (is_array($cek_sisa_cuti) && isset($cek_sisa_cuti[0]->ttl_sisa_cuti)) {
			    $sisa_cuti = $cek_sisa_cuti[0]->ttl_sisa_cuti;
			} else {
			    $sisa_cuti = 0; // default kalau datanya tidak ada
			}

			$diff_day		= $this->dayCount($f_date_start, $f_date_end);

			if($post['leave_type'] == '6'){ //Half day leave
				$diff_day = $diff_day*0.5;
			}
			if($post['leave_type'] == '5'){ //Sick Leave
				$diff_day = 0 ;
			}
			

			if($f_date_end >= $f_date_start){
				if($diff_day <= $sisa_cuti || $post['leave_type'] == '2'){ //unpaid leave gak ngecek sisa cuti
					//upload 
					$dataU = array();
					$dataU['status'] = FALSE; 
					$fieldname='attachment';
					if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
		            { 
		               
		                $config['upload_path']   = "./uploads/ijin";
		                $config['allowed_types'] = "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
		                $config['max_size']      = "0"; 
		                
		                $this->load->library('upload', $config); 
		                
		                if(!$this->upload->do_upload($fieldname)){ 
		                    $err_msg = $this->upload->display_errors(); 
		                    $dataU['error_warning'] = strip_tags($err_msg);              
		                    $dataU['status'] = FALSE;
		                } else { 
		                    $fileData = $this->upload->data();
		                    $dataU['upload_file'] = $fileData['file_name'];
		                    $dataU['status'] = TRUE;
		                }
		            }
		            $document = '';
					if($dataU['status']){ 
						$document = $dataU['upload_file'];
					} else if(isset($dataU['error_warning'])){ 
						//echo $dataU['error_warning']; exit;
						$document = 'ERROR : '.$dataU['error_warning'];
					}
		            //end upload

					if($post['leave_type'] == 5 && $document == ''){ //tipe sick harus ada document
						return null; // tipe sick harus ada document
					}else{
						$data = [
							'employee_id' 				=> trim($post['employee']),
							'date_leave_start' 			=> $f_date_start,
							'date_leave_end' 			=> $f_date_end,
							'masterleave_id' 			=> trim($post['leave_type']),
							'reason' 					=> trim($post['reason']),
							'total_leave' 				=> $diff_day,
							'status_approval' 			=> 1,
							'photo' 					=> $document,
							'created_at'				=> date("Y-m-d H:i:s")
						];
						$rs = $this->db->insert($this->table_name, $data);

						if($rs){
							return $rs;
						}else return null;


						//update sisa jatah cuti
						/*if($post['leave_type'] != '2'){ //unpaid leave gak update sisa cuti
							$jatahcuti = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$post['employee']."' and status = 1 order by period_start asc")->result(); 

							$is_update_jatah_selanjutnya=0;
							$sisa_cuti = $jatahcuti[0]->sisa_cuti-$diff_day;

							if($diff_day > $jatahcuti[0]->sisa_cuti){ 
								$is_update_jatah_selanjutnya=1;
								$sisa_cuti = 0;
								$diff_day2 = $diff_day-$jatahcuti[0]->sisa_cuti;
								$sisa_cuti2 = $jatahcuti[1]->sisa_cuti-$diff_day2;
								
							}
							
							$data2 = [
										'sisa_cuti' 	=> $sisa_cuti,
										'updated_date'	=> date("Y-m-d H:i:s")
									];
							$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[0]->id."'");


							if($is_update_jatah_selanjutnya == 1){ 
								$data3 = [
											'sisa_cuti' 	=> $sisa_cuti2,
											'updated_date'	=> date("Y-m-d H:i:s")
										];
								$this->db->update('total_cuti_karyawan', $data3, "id = '".$jatahcuti[1]->id."'");
							}

						}*/
					}
					
				}
				else return null;
			}else{
				echo "Date not match"; die();
			}
			
		}else{
			echo "Please fill Employee, Date Range & Leave Type"; die();
		}
		
	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){

			$date_start 	= date_create($post['date_start']);
			$date_end 		= date_create($post['date_end']);
			$f_date_start 	= date_format($date_start,"Y-m-d");
			$f_date_end 	= date_format($date_end,"Y-m-d");

			if($post['date_start'] != '' && $post['date_end'] != '' && $post['leave_type'] != ''){
				$getcurrLeave = $this->db->query("select * from leave_absences where id = '".$post['id']."' ")->result(); 

				if($getcurrLeave[0]->status_approval == 1){ //waiting approval
					// dipakai kalo ada update jatah cuti
					/*$getcurrTotalCuti =0;
					if($getcurrLeave[0]->masterleave_id != 2){ //data sebelumnya bukan unpaid leave, maka sisa cuti dibalikin
						$getcurrTotalCuti = $getcurrLeave[0]->total_leave;
					}

					$cek_sisa_cuti 	= $this->get_data_sisa_cuti($getcurrLeave[0]->employee_id, $f_date_start, $f_date_end); 
					$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti+$getcurrTotalCuti;

					$diff_day		= $this->dayCount($f_date_start, $f_date_end);

					if($post['leave_type'] == '6'){ //Half day leave
						$diff_day = $diff_day*0.5;
					}
					if($post['leave_type'] == '5'){ //Sick Leave
						$diff_day = 0 ;
					}*/
					// END dipakai kalo ada update jatah cuti


					$cek_sisa_cuti 	= $this->get_data_sisa_cuti($getcurrLeave[0]->employee_id, $f_date_start, $f_date_end);
					$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti;

					$diff_day		= $this->dayCount($f_date_start, $f_date_end);

					if($post['leave_type'] == '6'){ //Half day leave
						$diff_day = $diff_day*0.5;
					}
					if($post['leave_type'] == '5'){ //Sick Leave
						$diff_day = 0 ;
					}

					if($diff_day <= $sisa_cuti || $post['leave_type'] == '2'){ //unpaid leave gak ngecek sisa cuti
						$hdnattachment = $post['hdnattachment'];
						//upload 
						$dataU = array();
						$dataU['status'] = FALSE; 
						$fieldname='attachment';
						if(isset($_FILES[$fieldname]) && !empty($_FILES[$fieldname]['name']))
			            { 
			               
			                $config['upload_path']   = "./uploads/ijin";
			                $config['allowed_types'] = "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
			                $config['max_size']      = "0"; 
			                
			                $this->load->library('upload', $config); 
			                
			                if(!$this->upload->do_upload($fieldname)){ 
			                    $err_msg = $this->upload->display_errors(); 
			                    $dataU['error_warning'] = strip_tags($err_msg);              
			                    $dataU['status'] = FALSE;
			                } else { 
			                    $fileData = $this->upload->data();
			                    $dataU['upload_file'] = $fileData['file_name'];
			                    $dataU['status'] = TRUE;
			                }
			            }
			            $document = '';
						if($dataU['status']){ 
							$document = $dataU['upload_file'];
						} else if(isset($dataU['error_warning'])){ 
							//echo $dataU['error_warning']; exit;
							$document = 'ERROR : '.$dataU['error_warning'];
						}
		            	//end upload

		            	if($document == '' && $hdnattachment != ''){
		            		$document = $hdnattachment;
		            	}

		            	if($post['leave_type'] == 5 && $document == ''){ //tipe sick harus ada document
		            		return null; 
		            	}else{
		            		$data = [

								'date_leave_start' 			=> $f_date_start,
								'date_leave_end' 			=> $f_date_end,
								'masterleave_id' 			=> trim($post['leave_type']),
								'reason' 					=> trim($post['reason']),
								'total_leave' 				=> $diff_day,
								'photo' 					=> $document,
								'updated_at'				=> date("Y-m-d H:i:s")
								
							];

							$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

							if($rs){
								return $rs;
							}else return null;

							//update sisa jatah cuti
							/*if($rs){

								$update_jatah_cuti=1;
								if($getcurrLeave[0]->masterleave_id == 2 && $post['leave_type'] == 2){ //tidak ada perubahan jika data sebelumnya dan data skrg sama2 unpaid leave
									$update_jatah_cuti=0;
									return $rs; 
								}

								if($update_jatah_cuti == 1){

									if($post['leave_type'] == 2){
										$diff_day=0;
									}

									$jml_tambahan_cuti =  $getcurrTotalCuti-$diff_day;

									if($jml_tambahan_cuti != 0){
										$jatahcuti = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$getcurrLeave[0]->employee_id."' and status = 1 order by period_start asc")->result(); 

										if($jml_tambahan_cuti > 0){ // metode tambahin cuti
										
											$sisa_cuti_1 = $jatahcuti[0]->sisa_cuti+$jml_tambahan_cuti;

											$tambah_selanjutnya=0;
											if($sisa_cuti_1 > 12){
												$tambah_selanjutnya =1;
												$slot_tambah = 12- $jatahcuti[0]->sisa_cuti;
												$sisa_slot_tambah = $jml_tambahan_cuti-$slot_tambah;
												$sisa_cuti_1 =12;
											}
											$data2 = [
												'sisa_cuti' 	=> $sisa_cuti_1,
												'updated_date'	=> date("Y-m-d H:i:s")
											];
											$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[0]->id."'");

											if($tambah_selanjutnya == 1){
												$sisa_cuti_2 = $jatahcuti[1]->sisa_cuti+$sisa_slot_tambah;
												if($sisa_cuti_2 > 12){
													$sisa_cuti_2 = 12;
												}

												$data3 = [
													'sisa_cuti' 	=> $sisa_cuti_2,
													'updated_date'	=> date("Y-m-d H:i:s")
												];
												$this->db->update('total_cuti_karyawan', $data3, "id = '".$jatahcuti[1]->id."'");
											}

										}else{ //metode kurangi cuti

											$jml_kurang_cuti = $diff_day-$getcurrTotalCuti;
											$sisa_cuti_1 = $jatahcuti[0]->sisa_cuti-$jml_kurang_cuti;

											$kurang_selanjutnya=0;
											if($sisa_cuti_1 < 0){
												$kurang_selanjutnya = 1;

												if($jatahcuti[0]->sisa_cuti == 0){
													$slot_kurang =0;
												}else{
													$slot_kurang = $jml_kurang_cuti-$jatahcuti[0]->sisa_cuti;
												}
												
												$sisa_slot_kurang = $jml_kurang_cuti-$slot_kurang;
												$sisa_cuti_1 = 0;
											}
											$data2 = [
												'sisa_cuti' 	=> $sisa_cuti_1,
												'updated_date'	=> date("Y-m-d H:i:s")
											];
											$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[0]->id."'");

											if($kurang_selanjutnya == 1){
												$sisa_cuti_2 = $jatahcuti[1]->sisa_cuti-$sisa_slot_kurang;
												if($sisa_cuti_2 < 0){
													$sisa_cuti_2 = 0;
												}
												$data3 = [
													'sisa_cuti' 	=> $sisa_cuti_2,
													'updated_date'	=> date("Y-m-d H:i:s")
												];
												$this->db->update('total_cuti_karyawan', $data3, "id = '".$jatahcuti[1]->id."'");
											}

										}
									}
									
								}
								
								return  $rs;
							}else return null;*/
							// end update jatah cuti
		            	}

					}else return null; // cuti gak cukup
				}else{
					return null; //'cannot edit approved leave'
				}

			}
			else return null;

		} else return null;
	}  

	public function getRowData($id) { 
		$mTable = '(select a.*, b.full_name, c.name as leave_name
					from leave_absences a left join employees b on b.id = a.employee_id
					left join master_leaves c on c.id = a.masterleave_id

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
				'code' 	=> $v["B"],
				'name' 	=> $v["C"]
				
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
		if($getdata[0]->id_groups != 1 && $getdata[0]->id_groups != 4){ //bukan super user && bukan HR admin
			$whr=' where a.employee_id = "'.$karyawan_id.'" or b.direct_id = "'.$karyawan_id.'" ';
		}




		$sql = 'select a.*, b.full_name, c.name as leave_name,
					(case
						when a.status_approval = 1 then "Waiting Approval"
						when a.status_approval = 2 then "Approved"
						when a.status_approval = 3 then "Rejected"
						 end) as status, b.direct_id
					from leave_absences a left join employees b on b.id = a.employee_id
					left join master_leaves c on c.id = a.masterleave_id
					'.$whr.'
	   			ORDER BY id ASC
		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function get_data_sisa_cuti($empid, $startdate, $enddate){ 

		$cek_start_date = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$empid."' and status = 1 and ( ('".$startdate."' >= period_start and '".$startdate."' <= period_end) or ('".$startdate."' >= period_start and '".$startdate."' <= expired_date) )")->result(); 

		$cek_end_date = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$empid."' and status = 1 and ( ('".$enddate."' >= period_start and '".$enddate."' <= period_end) or ('".$enddate."' >= period_start and '".$enddate."' <= expired_date) )")->result(); 


		// cek apakah startdate & enddate masuk dalam periode available cuti
		if(!empty($cek_start_date) && !empty($cek_end_date)){
			$rs = $this->db->query("select sum(sisa_cuti) as ttl_sisa_cuti from total_cuti_karyawan where employee_id = '".$empid."' and status = 1")->result(); 

			return $rs;
		}else return 0;

	}

	public function get_data_sisa_cuti_byEmp($empid){ 

		$rs = $this->db->query("select sum(sisa_cuti) as ttl_sisa_cuti from total_cuti_karyawan where employee_id = '".$empid."' and status = 1")->result(); 

		return $rs;

	}


}
