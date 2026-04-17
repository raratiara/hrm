<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meetings_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "emp_management/meetings_menu";
 	protected $table_name 				= _PREFIX_TABLE."meetings";
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
			'dt.meeting_name',
			'dt.meeting_date',
			'dt.meeting_times',
			'dt.room_name',
			'dt.status',
			'dt.booking_date',
			'dt.created_by_name',
			'dt.description',
			'dt.participants',
			'dt.participants_name',
			'dt.is_participant',
			'dt.created_by',
			'dt.meeting_type_name',
			'dt.platform_name',
			'dt.meeting_type_id'
		];
		
		
		$karyawan_id = $_SESSION['worker']; 
		$whr="";
		if($_SESSION['role'] != 1){ //bukan super user
			$whr=" and (ao.created_by = ".$karyawan_id." or ao.direct_id = ".$karyawan_id." or ao.is_participant = 1) ";
		}


		$sIndexColumn = $this->primary_key;
		$sTable = "(select ao.* from (SELECT 
					  a.*, 
					  b.room_name, 
					  c.direct_id, 
					  c.full_name AS created_by_name,
					  IF(
						a.type = 'custom',
						CONCAT(
						  DATE_FORMAT(a.start_time, '%l:%i %p'),
						  ' - ',
						  DATE_FORMAT(a.end_time, '%l:%i %p')
						),
						'Full Day'
					  ) AS meeting_times,
					  DATE_FORMAT(a.start_time, '%l:%i %p') AS start_time_display,
					  DATE_FORMAT(a.end_time, '%l:%i %p') AS end_time_display,
					  (
						SELECT GROUP_CONCAT(e.full_name SEPARATOR ', ')
						FROM employees e
						WHERE FIND_IN_SET(e.id, a.participants)
					  ) AS participants_name,
					  CASE 
						WHEN FIND_IN_SET(".$karyawan_id.", a.participants) > 0 THEN 1 
						ELSE 0 
						END AS is_participant
						,aa.name as meeting_type_name, d.name as platform_name
					FROM meetings a
					left join master_meeting_type aa on aa.id = a.meeting_type_id
					LEFT JOIN master_meeting_room b ON b.id = a.meeting_room_id
					LEFT JOIN employees c ON c.id = a.created_by
					left join master_meeting_platform d on d.id = a.platform_id) ao
					where 1=1 ".$whr."
             		 )dt";
		

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

			$cancel=""; $checkin=""; $checkout="";
			$dtCheckin = $this->db->query("select * from presensi_meeting where meetings_id = '".$row->id."' and employee_id = '".$karyawan_id."' ")->result();
			

			if($row->status == 'booked' || $row->status == 'check in'){
				$cancel = '<a class="btn btn-xs btn-danger" style="background-color: #ff7600;" href="javascript:void(0);" onclick="cancel('."'".$row->id."'".')" role="button">Cancel</a>';
				if(empty($dtCheckin)){
					$checkin = '<a class="btn btn-xs btn-success" style="background-color: #33b300;" href="javascript:void(0);" onclick="checkin('."'".$row->id."'".')" role="button">Check-IN</a>';
				}
			}

			if($row->status == 'check in' && $karyawan_id == $row->created_by){
				$checkout = '<a class="btn btn-xs btn-success" style="background-color: #33b300;" href="javascript:void(0);" onclick="checkout('."'".$row->id."'".')" role="button">Check-OUT</a>';
			}
			$print_absen="";
			if($karyawan_id == $row->created_by){
				$print_absen = '<a class="btn btn-xs btn-default" onclick="getReport_absen_meeting('."'".$row->id."'".')"  role="button"><i class="fa fa-download"></i> Absensi</a>';
			}
			

			if($karyawan_id != $row->created_by){
				$edit="";
				$delete="";
				$cancel="";
			}
			
			$room_platform="";
			if($row->meeting_type_id == 1){ ///offline
				$room_platform = $row->room_name;
			}else if($row->meeting_type_id == 2){ ///online
				$room_platform = $row->platform_name;
			}else if($row->meeting_type_id == 3){ ///hybrid
				$room_platform = $row->room_name.' & '.$row->platform_name;
			}
			

			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$detail.'
					'.$edit.'
					'.$delete.'
					'.$cancel.'
					'.$checkin.'
					'.$checkout.'
					'.$print_absen.'
				</div>',
				$row->id,
				$row->meeting_type_name,
				$row->meeting_name,
				$row->meeting_date,
				$row->meeting_times,
				$room_platform,
				$row->status,
				$row->booking_date,
				$row->created_by_name,
				$row->description,
				$row->participants_name


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


	public function generate_unique_meeting_code() { 
	    do {
	        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
	        $exists = $this->db->query("select id from meetings where code = '$code'")->num_rows();
	    } while ($exists > 0);

	    return $code;
	}	


	public function create_zoom_meeting($client_id, $client_secret, $account_id, $account_email, $meeting_name, $meeting_date, $start_time, $end_time)
	{
		// 1. Get Access Token
		$token_url = "https://zoom.us/oauth/token?grant_type=account_credentials&account_id=".$account_id;

		$auth = base64_encode($client_id . ':' . $client_secret);

		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => $token_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_HTTPHEADER => [
				"Authorization: Basic ".$auth
			]
		]);

		$token_response = curl_exec($ch);
		curl_close($ch);

		$token_data = json_decode($token_response, true);

		if (!isset($token_data['access_token'])) {
			return [
				"status" => false,
				"msg" => "Failed get token",
				"log" => $token_response
			];
		}

		$access_token = $token_data['access_token'];

		// 2. Format waktu
		$start_dt = date("Y-m-d\TH:i:s", strtotime($meeting_date . ' ' . $start_time));
		$end_dt   = date("Y-m-d H:i:s", strtotime($meeting_date . ' ' . $end_time));

		$duration = (strtotime($end_dt) - strtotime($meeting_date . ' ' . $start_time)) / 60;

		// 3. Payload meeting
		$payload = [
			"topic" => $meeting_name,
			"type" => 2,
			"start_time" => $start_dt,
			"timezone" => "Asia/Jakarta",
			"duration" => (int)$duration,
			"settings" => [
				"join_before_host" => true,
				"approval_type" => 2,
				"waiting_room" => false
			]
		];

		// 4. Create meeting
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => "https://api.zoom.us/v2/users/".$account_email."/meetings",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_HTTPHEADER => [
				"Authorization: Bearer ".$access_token,
				"Content-Type: application/json"
			],
			CURLOPT_POSTFIELDS => json_encode($payload)
		]);

		$response = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($response, true);

		if (isset($data['id'])) {
			return [
				"status" => true,
				"join_url" => $data['join_url'],
				"start_url" => $data['start_url'],
				"meeting_id" => $data['id'],
				"raw" => $data
			];
		} else {
			return [
				"status" => false,
				"msg" => "Zoom create failed",
				"log" => $response
			];
		}
	}
	
	public function add_data($post) { 

		$karyawan_id = $_SESSION['worker'];

		if($post['meeting_type'] == 1 && $post['meeting_room'] == ''){ ///offline
			return [
				"status" => false,
				"msg" 	 => "harap isi meeting room"
			];
		}else if($post['meeting_type'] == 2 && $post['meeting_platform'] == ''){ ///online
			return [
				"status" => false,
				"msg" 	 => "harap isi platform"
			];
		}else if($post['meeting_type'] == 3 && ($post['meeting_room'] == '' || $post['meeting_platform'] == '')){ ///hybrid
			return [
				"status" => false,
				"msg" 	 => "harap isi meeting room & platform"
			];
		}else{

			if($post['type'] == 'custom' && ($post['start_time'] == '' || $post['end_time'] == '')){
				return [
					"status" => false,
					"msg" 	 => "harap isi start time & end time"
				];
			}else{
				$get_meeting_date 		= trim($post['meeting_date'] ?? '');
				$meeting_date 	= date("Y-m-d", strtotime($get_meeting_date));
				if($meeting_date == '1970-01-01'){
					$meeting_date='';
				}


				// pastikan participants berupa string koma
				$participants = '';
				if (!empty($post['participants'])) {
					if (is_array($post['participants'])) {
						$participants = implode(',', $post['participants']); // ubah array jadi string
					} else {
						$participants = trim($post['participants']);
					}
				}

				$meeting_code = $this->generate_unique_meeting_code();

				$whr="";
				if(trim($post['type']) == 'custom'){
					$start_time = date("H:i:s", strtotime($post['start_time']));
					$end_time   = date("H:i:s", strtotime($post['end_time']));
					$whr = " and ((type = 'custom' and (start_time < '".$end_time."' AND end_time > '".$start_time."')) or type = 'full day')";
				}


				if($post['meeting_type'] == '1'){ /// offline
					$cekdata = $this->db->query("select * from meetings where meeting_date = '".$meeting_date."' and meeting_room_id = '".trim($post['meeting_room'])."' and (status = 'booked' and check_in_time is null and expired_time > '".date("H:i:s")."') ".$whr." ")->result();

					if(empty($cekdata)){
						if(trim($post['type']) == 'custom'){
							$expired_time = date("H:i:s", strtotime($post['start_time'] . ' +30 minutes'));

							$data = [
								'meeting_name' 		=> trim($post['meeting_name']),
								'meeting_date' 		=> $meeting_date,
								'type'				=> trim($post['type']),
								'start_time' 		=> $start_time,
								'end_time' 			=> $end_time,
								'meeting_room_id' 	=> trim($post['meeting_room']),
								'description' 		=> trim($post['description']),
								'participants' 		=> $participants,
								'created_by' 		=> $karyawan_id,
								'status' 			=> 'Booked',
								'booking_date' 		=> date("Y-m-d H:i:s"),
								'code' 				=> $meeting_code,
								'expired_time' 		=> $meeting_date.' '.$expired_time,
								'meeting_type_id' 	=> trim($post['meeting_type'])
							];

						}else{
							$expired_time = '09:30:00';
							$data = [
								'meeting_name' 		=> trim($post['meeting_name']),
								'meeting_date' 		=> $meeting_date,
								'type'				=> trim($post['type']),
								'meeting_room_id' 	=> trim($post['meeting_room']),
								'description' 		=> trim($post['description']),
								'participants' 		=> $participants,
								'created_by' 		=> $karyawan_id,
								'status' 			=> 'Booked',
								'booking_date' 		=> date("Y-m-d H:i:s"),
								'code' 				=> $meeting_code,
								'expired_time' 		=> $meeting_date.' '.$expired_time,
								'meeting_type_id' 	=> trim($post['meeting_type'])
							];
						}
						
						//return $rs = $this->db->insert($this->table_name, $data);
						$rs = $this->db->insert($this->table_name, $data);
						if($rs){
							$dataInput = array(
								'rs' 	=> $rs,
								'code' 	=> $meeting_code
							);

							return [
								"status" => true,
								"msg" 	 => "Data berhasil disimpan. Kode meeting = ".$meeting_code.""
							];
						}else{
							return [
								"status" => false,
								"msg" 	 => "Data gagal disimpan"
							];
						}


					}else{
						return [
							"status" => false,
							"msg" 	 => "tidak ada ruangan"
						];
					} 
				}
				else if($post['meeting_type'] == '2'){ /// online
					$cekaccount = $this->db->query("select * FROM zoom_account where is_active = 1 ORDER BY id")->result();

					if(!empty($cekaccount)){

						$start_time = date("H:i:s", strtotime($post['start_time']));
						$end_time   = date("H:i:s", strtotime($post['end_time']));

						// ✅ FIX overlap
						$cekdata = $this->db->query("select 1
							FROM meetings
							WHERE zoom_account_id = '".$cekaccount[0]->id."'
							AND meeting_type_id = 2
							AND platform_id = '1'
							AND status <> 'Cancelled'
							AND meeting_date = '".$meeting_date."'
							AND start_time < '".$end_time."'
							AND end_time > '".$start_time."'")->result();

						if(empty($cekdata)){

							// CREATE ZOOM
							$zoom = $this->create_zoom_meeting(
								$cekaccount[0]->client_id,
								$cekaccount[0]->client_secret,
								$cekaccount[0]->account_id,
								$cekaccount[0]->account_email,
								$post['meeting_name'],
								$meeting_date,
								$start_time,
								$end_time
							);

							if(empty($zoom) || !isset($zoom['status']) || !$zoom['status']){
								return [
									"status" => false,
									"msg" => "Gagal create Zoom",
									"log" => $zoom['log']
								];
							}

							$expired_time = date("H:i:s", strtotime($post['start_time'].' +30 minutes'));

							$data = [
								'meeting_name' => trim($post['meeting_name']),
								'meeting_date' => $meeting_date,
								'type' => trim($post['type']),
								'start_time' => $start_time,
								'end_time' => $end_time,
								'description' => trim($post['description']),
								'participants' => $participants,
								'created_by' => $karyawan_id,
								'status' => 'Booked',
								'booking_date' => date("Y-m-d H:i:s"),
								'code' => $meeting_code,
								'expired_time' => $meeting_date.' '.$expired_time,
								'meeting_type_id' => trim($post['meeting_type']),
								'platform_id' => trim($post['meeting_platform']),

								// SIMPAN ZOOM
								'meeting_link' => $zoom['join_url'],
								'zoom_start_url' => $zoom['start_url'],
								'zoom_meeting_id' => $zoom['meeting_id'],
								'zoom_account_id' => $cekaccount[0]->id
							];

							$rs = $this->db->insert($this->table_name, $data);

							return $rs
								? ["status"=>true,"msg"=>"Meeting + Zoom berhasil dibuat"]
								: ["status"=>false,"msg"=>"Gagal simpan data"];

						}else{
							return ["status"=>false,"msg"=>"Jadwal Zoom bentrok"];
						}

					}else{
						return ["status"=>false,"msg"=>"Tidak ada akun Zoom tersedia"];
					}
				}
				else if($post['meeting_type'] == '3'){ ///hybrid
					$start_time = date("H:i:s", strtotime($post['start_time']));
					$end_time   = date("H:i:s", strtotime($post['end_time']));

					//  cek ruangan
					$cekdata = $this->db->query("select * from meetings 
						where meeting_date = '".$meeting_date."' 
						and meeting_room_id = '".trim($post['meeting_room'])."' 
						and (status = 'booked' and check_in_time is null) ".$whr)->result();

					if(empty($cekdata)){

						$cekaccount = $this->db->query("select * FROM zoom_account where is_active = 1 ORDER BY id")->result();

						if(!empty($cekaccount)){

							// FIX overlap
							$cekdata2 = $this->db->query("select 1
								FROM meetings
								WHERE zoom_account_id = '".$cekaccount[0]->id."'
								AND meeting_type_id = 2
								AND platform_id = '1'
								AND status <> 'Cancelled'
								AND meeting_date = '".$meeting_date."'
								AND start_time < '".$end_time."'
								AND end_time > '".$start_time."'")->result();

							if(empty($cekdata2)){ // FIX (dibalik sebelumnya)

								// CREATE ZOOM
								$zoom = $this->create_zoom_meeting(
									$cekaccount[0]->client_id,
									$cekaccount[0]->client_secret,
									$cekaccount[0]->account_id,
									$cekaccount[0]->account_email,
									$post['meeting_name'],
									$meeting_date,
									$start_time,
									$end_time
								);

								if(!$zoom['status']){
									return [
										"status" => false,
										"msg" => "Gagal create Zoom",
										"log" => $zoom['log']
									];
								}

								$expired_time = date("H:i:s", strtotime($post['start_time'].' +30 minutes'));

								$data = [
									'meeting_name' => trim($post['meeting_name']),
									'meeting_date' => $meeting_date,
									'type' => trim($post['type']),
									'start_time' => $start_time,
									'end_time' => $end_time,
									'meeting_room_id' => trim($post['meeting_room']),
									'description' => trim($post['description']),
									'participants' => $participants,
									'created_by' => $karyawan_id,
									'status' => 'Booked',
									'booking_date' => date("Y-m-d H:i:s"),
									'code' => $meeting_code,
									'expired_time' => $meeting_date.' '.$expired_time,
									'meeting_type_id' => trim($post['meeting_type']),
									'platform_id' => trim($post['meeting_platform']),

									//  SIMPAN ZOOM
									'meeting_link' => $zoom['join_url'],
									'zoom_start_url' => $zoom['start_url'],
									'zoom_meeting_id' => $zoom['meeting_id'],
									'zoom_account_id' => $cekaccount[0]->id
								];

								$rs = $this->db->insert($this->table_name, $data);

								return $rs
									? ["status"=>true,"msg"=>"Hybrid meeting + Zoom berhasil"]
									: ["status"=>false,"msg"=>"Gagal simpan data"];

							}else{
								return ["status"=>false,"msg"=>"Jadwal Zoom bentrok"];
							}

						}else{
							return ["status"=>false,"msg"=>"Tidak ada akun Zoom"];
						}

					}else{
						return ["status"=>false,"msg"=>"Ruangan tidak tersedia"];
					}
				}
			}

		}
  			
	}  

	public function edit_data($post) {

		$karyawan_id = $_SESSION['worker'];

		if(empty($post['id'])){
			return [
			    "status" => false,
			    "msg" 	 => "ID tidak ditemukan"
			];
		}

		if($post['meeting_type'] == 1 && $post['meeting_room'] == ''){ ///offline
			return [
				"status" => false,
				"msg" 	 => "harap isi meeting room"
			];
		}else if($post['meeting_type'] == 2 && $post['meeting_platform'] == ''){ ///online
			return [
				"status" => false,
				"msg" 	 => "harap isi platform"
			];
		}else if($post['meeting_type'] == 3 && ($post['meeting_room'] == '' || $post['meeting_platform'] == '')){ ///hybrid
			return [
				"status" => false,
				"msg" 	 => "harap isi meeting room & platform"
			];
		}else{

			if($post['type'] == 'custom' && ($post['start_time'] == '' || $post['end_time'] == '')){
				return [
					"status" => false,
					"msg" 	 => "harap isi start time & end time"
				];
			}else{
				$get_meeting_date 	= trim($post['meeting_date'] ?? '');
				$meeting_date 		= date("Y-m-d", strtotime($get_meeting_date));
				if($meeting_date == '1970-01-01'){
					$meeting_date='';
				}

				// pastikan participants berupa string koma
				$participants = '';
				if (!empty($post['participants'])) {
					if (is_array($post['participants'])) {
						$participants = implode(',', $post['participants']); // ubah array jadi string
					} else {
						$participants = trim($post['participants']);
					}
				}

				$whr="";
				if(trim($post['type']) == 'custom'){
					$start_time = date("H:i:s", strtotime($post['start_time']));
					$end_time   = date("H:i:s", strtotime($post['end_time']));
					$whr = " and ((type = 'custom' and (start_time < '".$end_time."' AND end_time > '".$start_time."')) or type = 'full day')";
				
					$expired_time = date("H:i:s", strtotime($post['start_time'] . ' +30 minutes'));
				}else{
					$expired_time = '09:30:00';
				}

				if($post['meeting_type'] == '1'){ /// offline
					$cekdata = $this->db->query("select * from meetings where meeting_date = '".$meeting_date."' and meeting_room_id = '".trim($post['meeting_room'])."' and (status = 'booked' and check_in_time is null and expired_time > '".date("H:i:s")."') ".$whr." and id != ".$post['id']." ")->result();

					if(empty($cekdata)){
						$start_time=""; $end_time="";
						if(trim($post['type']) == 'custom'){
							$start_time = date("H:i:s", strtotime($post['start_time']));
							$end_time   = date("H:i:s", strtotime($post['end_time']));
						}

						$data = [
							'meeting_name' 		=> trim($post['meeting_name']),
							'meeting_date' 		=> $meeting_date,
							'type'				=> trim($post['type']),
							'start_time' 		=> $start_time,
							'end_time' 			=> $end_time,
							'meeting_room_id' 	=> trim($post['meeting_room']),
							'description' 		=> trim($post['description']),
							'participants' 		=> $participants,
							'meeting_type_id' 	=> trim($post['meeting_type']),
							'expired_time' 		=> $meeting_date.' '.$expired_time
						];

						$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

						return $rs
							? ["status" => true, "msg" => "Data berhasil disimpan"]
							: ["status" => false, "msg" => "Data gagal disimpan"];

					}else{
						return [
						    "status" => false,
						    "msg" 	 => "tidak ada ruangan"
						];
					}
				}
				else if($post['meeting_type'] == '2'){ /// online
					$start_time = date("H:i:s", strtotime($post['start_time']));
					$end_time   = date("H:i:s", strtotime($post['end_time']));

					$expired_time = date("H:i:s", strtotime($post['start_time'] . ' +30 minutes'));

					$cekaccount = $this->db->query("select * FROM zoom_account where is_active = 1 ORDER BY id")->result();

					if(!empty($cekaccount)){
						$cekdata = $this->db->query("select 1
							FROM meetings
							WHERE zoom_account_id = '".$cekaccount[0]->id."'
							AND meeting_type_id = 2
							AND platform_id = '1'
							AND status <> 'Cancelled'
							AND meeting_date = '".$meeting_date."'
							AND start_time < '".$end_time."'
							AND end_time > '".$start_time."'
							AND id != ".$post['id'])->result();

						if(empty($cekdata)){
							$data = [
								'meeting_name' 		=> trim($post['meeting_name']),
								'meeting_date' 		=> $meeting_date,
								'type'				=> trim($post['type']),
								'start_time' 		=> $start_time,
								'end_time' 			=> $end_time,
								'description' 		=> trim($post['description']),
								'participants' 		=> $participants,
								'meeting_type_id' 	=> trim($post['meeting_type']),
								'platform_id' 		=> trim($post['meeting_platform']),
								'expired_time' 		=> $meeting_date.' '.$expired_time
							];

							$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

							return $rs
								? ["status" => true, "msg" => "Data berhasil disimpan"]
								: ["status" => false, "msg" => "Data gagal disimpan"];

						}else{
							return ["status" => false, "msg" => "Jadwal Zoom bentrok"];
						}

					}else{
						return ["status" => false, "msg" => "Tidak ada akun Zoom tersedia"];
					}
				}
				else if($post['meeting_type'] == '3'){ ///hybrid
					$start_time = date("H:i:s", strtotime($post['start_time']));
					$end_time   = date("H:i:s", strtotime($post['end_time']));
					$expired_time = date("H:i:s", strtotime($post['start_time'] . ' +30 minutes'));

					// cek ruangan
					$cekdata = $this->db->query("select * from meetings
						where meeting_date = '".$meeting_date."'
						and meeting_room_id = '".trim($post['meeting_room'])."'
						and (status = 'booked' and check_in_time is null) ".$whr." and id != ".$post['id'])->result();

					if(empty($cekdata)){

						$cekaccount = $this->db->query("select * FROM zoom_account where is_active = 1 ORDER BY id")->result();

						if(!empty($cekaccount)){

							$cekdata2 = $this->db->query("select 1
								FROM meetings
								WHERE zoom_account_id = '".$cekaccount[0]->id."'
								AND meeting_type_id = 2
								AND platform_id = '1'
								AND status <> 'Cancelled'
								AND meeting_date = '".$meeting_date."'
								AND start_time < '".$end_time."'
								AND end_time > '".$start_time."'
								AND id != ".$post['id'])->result();

							if(empty($cekdata2)){
								$data = [
									'meeting_name' 		=> trim($post['meeting_name']),
									'meeting_date' 		=> $meeting_date,
									'type'				=> trim($post['type']),
									'start_time' 		=> $start_time,
									'end_time' 			=> $end_time,
									'meeting_room_id' 	=> trim($post['meeting_room']),
									'description' 		=> trim($post['description']),
									'participants' 		=> $participants,
									'meeting_type_id' 	=> trim($post['meeting_type']),
									'platform_id' 		=> trim($post['meeting_platform']),
									'expired_time' 		=> $meeting_date.' '.$expired_time
								];

								$rs = $this->db->update($this->table_name, $data, [$this->primary_key => trim($post['id'])]);

								return $rs
									? ["status" => true, "msg" => "Data berhasil disimpan"]
									: ["status" => false, "msg" => "Data gagal disimpan"];

							}else{
								return ["status" => false, "msg" => "Jadwal Zoom bentrok"];
							}

						}else{
							return ["status" => false, "msg" => "Tidak ada akun Zoom"];
						}

					}else{
						return ["status" => false, "msg" => "Ruangan tidak tersedia"];
					}
				}
			}

		}
	}


	public function getRowData($id) { 
		
		$karyawan_id = $_SESSION['worker'];


		$mTable = "(SELECT 
					  a.*, 
					  b.room_name, 
					  c.direct_id, 
					  c.full_name AS created_by_name,
					  IF(
					    a.type = 'custom',
					    CONCAT(
					      DATE_FORMAT(a.start_time, '%l:%i %p'),
					      ' - ',
					      DATE_FORMAT(a.end_time, '%l:%i %p')
					    ),
					    'Full Day'
					  ) AS meeting_times,
					  DATE_FORMAT(a.start_time, '%l:%i %p') AS start_time_display,
					  DATE_FORMAT(a.end_time, '%l:%i %p') AS end_time_display,
					  (
					    SELECT GROUP_CONCAT(e.full_name SEPARATOR ', ')
					    FROM employees e
					    WHERE FIND_IN_SET(e.id, a.participants)
					  ) AS participants_name,
					   d.name as meeting_type_name,
					   e.name as meeting_platform_name
					FROM meetings a
					LEFT JOIN master_meeting_room b ON b.id = a.meeting_room_id
					LEFT JOIN employees c ON c.id = a.created_by
					left join master_meeting_type d on d.id = a.meeting_type_id
					left join master_meeting_platform e on e.id = a.platform_id
				)dt";

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();

		$participants = explode(',', $rs->participants);
		
		
		
		$data = array(
			'rowdata' 		=> $rs,
			'participants' 	=> $participants
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
		$whr="";
		if($_SESSION['role'] != 1){ //bukan super user
			$whr=" and (ao.created_by = ".$karyawan_id." or ao.direct_id = ".$karyawan_id." or ao.is_participant = 1) ";
		}



		$sql = "select ao.* from (SELECT 
				  a.*, 
				  b.room_name, 
				  c.direct_id, 
				  c.full_name AS created_by_name,
				  IF(
					a.type = 'custom',
					CONCAT(
					  DATE_FORMAT(a.start_time, '%l:%i %p'),
					  ' - ',
					  DATE_FORMAT(a.end_time, '%l:%i %p')
					),
					'Full Day'
				  ) AS meeting_times,
				  DATE_FORMAT(a.start_time, '%l:%i %p') AS start_time_display,
				  DATE_FORMAT(a.end_time, '%l:%i %p') AS end_time_display,
				  (
					SELECT GROUP_CONCAT(e.full_name SEPARATOR ', ')
					FROM employees e
					WHERE FIND_IN_SET(e.id, a.participants)
				  ) AS participants_name,
				  CASE 
					WHEN FIND_IN_SET(".$karyawan_id.", a.participants) > 0 THEN 1 
					ELSE 0 
					END AS is_participant
				FROM meetings a
				LEFT JOIN master_meeting_room b ON b.id = a.meeting_room_id
				LEFT JOIN employees c ON c.id = a.created_by) ao
				where 1=1 ".$whr."
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


}