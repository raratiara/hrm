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
			'dt.reason'
		];
		
		

		$sIndexColumn = $this->primary_key;
		$sTable = '(select a.*, b.full_name, c.name as leave_name
					from leave_absences a left join employees b on b.id = a.employee_id
					left join master_leaves c on c.id = a.masterleave_id)dt';
		

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

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$delete.'
				</div>',
				$row->id,
				$row->full_name,
				$row->date_leave_start,
				$row->date_leave_end,
				$row->leave_name,
				$row->reason

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
			$cek_sisa_cuti 	= $this->get_data_sisa_cuti($post['employee'], $f_date_start, $f_date_end);
			$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti;

			$diff_day		= $this->dayCount($f_date_start, $f_date_end);

			if($post['leave_type'] == '6'){ //Half day leave
				$diff_day = $diff_day*0.5;
			}
			if($post['leave_type'] == '5'){ //Sick Leave
				$diff_day = 0 ;
			}
			


			if($diff_day <= $sisa_cuti || $post['leave_type'] == '2'){ //unpaid leave gak ngecek sisa cuti
				$data = [
					'employee_id' 				=> trim($post['employee']),
					'date_leave_start' 			=> $f_date_start,
					'date_leave_end' 			=> $f_date_end,
					'masterleave_id' 			=> trim($post['leave_type']),
					'reason' 					=> trim($post['reason']),
					'total_leave' 				=> $diff_day,
					'created_at'				=> date("Y-m-d H:i:s")
				];
				$rs = $this->db->insert($this->table_name, $data);


				//update sisa jatah cuti
				if($post['leave_type'] != '2'){ //unpaid leave gak update sisa cuti
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
						$data2 = [
									'sisa_cuti' 	=> $sisa_cuti2,
									'updated_date'	=> date("Y-m-d H:i:s")
								];
						$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[1]->id."'");
					}

				}

				return $rs;
			}
			else return null;
			
		}else return null;
		
	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){

			$date_start 	= date_create($post['date_start']);
			$date_end 		= date_create($post['date_end']);
			$f_date_start 	= date_format($date_start,"Y-m-d");
			$f_date_end 	= date_format($date_end,"Y-m-d");

			if($post['date_start'] != '' && $post['date_end'] != '' && $post['leave_type'] != ''){
				$getcurrLeave = $this->db->query("select * from leave_absences where id = '".$post['id']."' ")->result(); 
				$getcurrTotalCuti =0;
				if($getcurrLeave[0]->masterleave_id != 2){ //bukan unpaid leave, maka sisa cuti dibalikin
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
				}

				if($diff_day <= $sisa_cuti || $post['leave_type'] == '2'){ //unpaid leave gak ngecek sisa cuti
					
					$data = [

						'date_leave_start' 			=> $f_date_start,
						'date_leave_end' 			=> $f_date_end,
						'masterleave_id' 			=> trim($post['leave_type']),
						'reason' 					=> trim($post['reason']),
						'total_leave' 				=> $diff_day,
						'updated_at'				=> date("Y-m-d H:i:s")
						
					];

					$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

					//update sisa jatah cuti
					if($rs){

						$update_jatah_cuti=1;
						if($getcurrLeave[0]->masterleave_id == 2 && $post['leave_type'] == 2){ //tidak ada perubahan jika data sebelumnya dan data skrg sama2 unpaid leave
							$update_jatah_cuti=0;
							return $rs; 
						}

						if($update_jatah_cuti == 1){
							if($getcurrLeave[0]->masterleave_id == 2){ //kalau data sebelumnya adalah unpaid leave
								
								$jatahcuti = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$getcurrLeave[0]->employee_id."' and status = 1 order by period_start asc")->result(); 

								//$is_update_jatah_selanjutnya=0;
								$sisa_cuti = $sisa_cuti-$diff_day;

								/*if($diff_day > $jatahcuti[0]->sisa_cuti){ 
									$is_update_jatah_selanjutnya=1;
									$sisa_cuti = 0;
									$diff_day2 = $diff_day-$jatahcuti[0]->sisa_cuti;
									$sisa_cuti2 = $jatahcuti[1]->sisa_cuti-$diff_day2;
									
								}*/
								
								$data2 = [
											'sisa_cuti' 	=> $sisa_cuti,
											'updated_date'	=> date("Y-m-d H:i:s")
										];
								$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[0]->id."'");

								/*if($is_update_jatah_selanjutnya == 1){ 
									$data3 = [
												'sisa_cuti' 	=> $sisa_cuti2,
												'updated_date'	=> date("Y-m-d H:i:s")
											];
									$this->db->update('total_cuti_karyawan', $data3, "id = '".$jatahcuti[1]->id."'");
								}*/
							}else if($post['leave_type'] == 2){ //kalau data skrg adalah unpaid leave
								
								$is_update_jatah_selanjutnya=0;

								if($sisa_cuti > 12){
									$is_update_jatah_selanjutnya=1;
									$tambahjatah = $sisa_cuti-12;
									$sisa_cuti=12;
								}
								$data2 = [
											'sisa_cuti' 	=> $sisa_cuti,
											'updated_date'	=> date("Y-m-d H:i:s")
										];
								$this->db->update('total_cuti_karyawan', $data2, "id = '".$jatahcuti[0]->id."'");

								if($is_update_jatah_selanjutnya == 1){
									$nextjatah = $jatahcuti[1]->sisa_cuti+$tambahjatah;
									
									$sisa_cuti2 = $nextjatah;
									if($nextjatah > 12){
										$sisa_cuti2 = 12;
									}
									$data3 = [
												'sisa_cuti' 	=> $sisa_cuti2,
												'updated_date'	=> date("Y-m-d H:i:s")
											];
									$this->db->update('total_cuti_karyawan', $data3, "id = '".$jatahcuti[1]->id."'");
								}
							}else if($getcurrLeave[0]->masterleave_id != 2 && $post['leave_type'] != 2){

								$jatahcuti = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$getcurrLeave[0]->employee_id."' and status = 1 order by period_start asc")->result(); 

								$is_update_jatah_selanjutnya=0; echo $sisa_cuti; die();
								$sisa_cuti = $sisa_cuti-$diff_day;
echo 'sip'; die();
								if($diff_day > $jatahcuti[0]->sisa_cuti){ echo 'haha'; die();
									$is_update_jatah_selanjutnya=1;
									$sisa_cuti = 0;
									$diff_day2 = $diff_day-$jatahcuti[0]->sisa_cuti;
									$sisa_cuti2 = $jatahcuti[1]->sisa_cuti-$diff_day2;
									
								}else{echo $sisa_cuti; die();}
								
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

							}
							else return null;
						}
						
						return  $rs;
					}else return null;

				}else return null;
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
		$sql = "select id, code, name from mother_vessel
	   		ORDER BY id ASC
		";

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
