<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends API_Controller
{
	/* Module */
 	//private $model_name				= "api_model";

   	public function __construct()
	{
      	parent::__construct();

		//$this->load->model($this->model_name);
   	}

    public function index()
    {
        $response = [
            'message' => 'Access denied',
            "error" => 'Not allowed root access.'
            ];

		$this->render_json($response, 400);
		exit;
	}

	
	
	// register basic example
    public function register()
    {
		$valid_elem = ['first_name', 'last_name', 'email', 'password'];
		$params = elements($valid_elem, $this->mParams);
		if(!values_satisfied($params)){
			$firstname  = $params['first_name'];
			$lastname   = $params['last_name'];
			$email      = $params['email'];
			$password   = $params['password'];
		 
			$password_hash = password_hash($password, PASSWORD_BCRYPT);
		 
			$dataRegister = [
				'first_name' => $firstname,
				'last_name' => $lastname,
				'email' => $email,
				'password' => $password_hash
			];
		 
			$register = $this->api->register($dataRegister);
		 
			if($register == true){
				$response = [
					'status' => 201, // Created
					'message' => 'Registration Successful'
				];
			} else {
				$response = [
					'status' => 503, // Service Unavailable
					'message' => 'Registration Fail'
				];
			}
		} else {
			$response = [
				'status' => 400, // Bad Request
				'message' => 'Failed',
				'error' => 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }



    public function login_old()
    {
    	$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$username	= $_REQUEST['username'];
    	$password 	= $_REQUEST['password'];


		if($username != '' && $password != ''){
			
			$cek_login = $this->api->cek_login($username);	
			
			if(password_verify($password, isset($cek_login['password'])?$cek_login['password']:''))
			{ 
				$data = array(
					"id" 			=> $cek_login['id'],
					"name" 			=> $cek_login['name'],
					"email" 		=> $cek_login['email'],
					"employee_id" 	=> $cek_login['id_karyawan']
				);
	 
				$token = $this->genJWTdata($data);	 
				$response = [
					'status' 		=> 200,
					'message' 		=> 'Success',
					"token" 		=> $token[0],
					"expire" 		=> $token[1],
					"email" 		=> $cek_login['email'],
					"employee_id" 	=> $cek_login['id_karyawan'] 
				];
			} else { 
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Access credentials not match'
				];
			}

		} else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }

    public function login()
    {
    	$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$username	= $_REQUEST['username'];
    	$password 	= $_REQUEST['password'];


		if($username != '' && $password != ''){
			
			$cek_login = $this->api->cek_login($username, $password);	
			
			if($cek_login != '')
			{ 
				$data = array(
					"id" 			=> $cek_login->id,
					"name" 			=> $cek_login->name,
					"email" 		=> $cek_login->email,
					"employee_id" 	=> $cek_login->id_karyawan
				);
	 
				$token = $this->genJWTdata($data);	 
				$response = [
					'status' 		=> 200,
					'message' 		=> 'Success',
					"token" 		=> $token[0],
					"expire" 		=> $token[1],
					"email" 		=> $cek_login->email,
					"employee_id" 	=> $cek_login->id_karyawan 
				];
			} else { 
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Access credentials not match'
				];
			}

		} else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }


    public function sync()
    {
    	/*$bearer_token = 'jk43242kdnsd';

    	$headers = getallheaders();
    	if (substr($headers['Authorization'], 0, 7) !== 'Bearer ') {
		    echo json_encode(["error" => "Bearer keyword is missing"]);
		    exit;
		}else{
			$token = trim(substr($headers['Authorization'], 7));

			if($token != $bearer_token){
				echo json_encode(["error" => "Token not valid"]);
		    	exit;
			}

		}*/


    	$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
		$base_url = $protocol . $_SERVER['HTTP_HOST'] . '/';


    	$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$url	= $_REQUEST['url'];
    	$username	= $_REQUEST['username'];
    	$password 	= $_REQUEST['password'];


		$cek_login = $this->api->cek_login($username, $password);
		if($cek_login != '')
		{ 
			if($url != ''){
			
				$cek_data = $this->api->cek_company($url);	
				$getversion = $this->db->query("select * from version order by id desc limit 1")->result(); 
				$version 	= $getversion[0]->version;

				if($cek_data['id'] != '')
				{
					//in LOCAL => //$urllogo = $base_url.'_hrm/uploads/logo/'.$cek_data['logo'];
					$urllogo = $base_url.'uploads/logo/'.$cek_data['logo'];
					$data = array(
						"nama_perusahaan" => $cek_data['name'],
						"logo_perusahaan" => $urllogo,
						"version" => $version  
					);
		 
					$response = [
						'status' 	=> 200,
						'message' 	=> 'Success',
						"data" 		=> $data
					];
				} else {
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Company not found'
					];
				}
				
			} else {
				$response = [
					'status' 	=> 400, // Bad Request
					'message'	=>'Failed',
					'error'	 	=> 'Require not satisfied'
				];
			}
		}else{
			$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Access credentials not match'
				];
		}

		
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }


    public function absen()
    {
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee	= $_REQUEST['employee'];
    	$tipe = $_REQUEST['tipe'];
    	$datetime	= $_REQUEST['datetime_attendance'];

		if($employee != '' && $tipe != '' && $datetime != ''){
			/*$date 	= date_format($datetime,"Y-m-d");
			$time 		= date_format($datetime,"H:i:s");*/

			$exp 			= explode(" ",$datetime);
			$date 			= $exp[0];
			$time 			= $exp[1];
			$timestamp_time = strtotime($time); 

			$cek_emp = $this->api->cek_employee($employee);	

			if($cek_emp['shift_type'] != '')
			{
				if($cek_emp['shift_type'] == 'Reguler'){
					$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 
					$attendance_type 	= $dt[0]->name;
					$time_in 			= $dt[0]->time_in;
					$time_out 			= $dt[0]->time_out;
					$post_timein 		= strtotime($time_in);
					$post_timeout 		= strtotime($time_out);
				}

				$is_late=''; $is_leaving_office_early = '';
				if($timestamp_time > $post_timein){
					$is_late='Y';
				}
				if($timestamp_time < $post_timeout){
					$is_leaving_office_early = 'Y';
				}

				$cek_data = $this->db->query("select * from time_attendances where employee_id = '".$employee."' and date_attendance = '".$date."' ")->result();

				if($cek_data[0]->id != ''){ //update
					if($tipe == 'checkin'){
						$data = [
							'attendance_type' 			=> $attendance_type,
							'time_in' 					=> $time_in,
							'time_out' 					=> $time_out,
							'date_attendance_in' 		=> $datetime,
							'is_late'					=> $is_late,
							'updated_at'				=> date("Y-m-d H:i:s")
						];
						$rs = $this->db->update("time_attendances", $data, "id='".$cek_data[0]->id."'");

						if($rs){
							$response = [
								'status' 	=> 200,
								'message' 	=> 'Success'
							];
						}else{
							$response = [
								'status' 	=> 401,
								'message' 	=> 'Failed',
								'error' 	=> 'Error update checkin'
							];
						}

					}else{ //checkout
						$f_datetime_in 			= $cek_data[0]->date_attendance_in;
						$f_datetime_out 		= $datetime;
						$timestamp1 			= strtotime($f_datetime_in); 
						$timestamp2 			= strtotime($f_datetime_out);
						$num_of_working_hours 	= abs($timestamp2 - $timestamp1)/(60)/(60); //jam

						$data = [
							'attendance_type' 			=> $attendance_type,
							'time_in' 					=> $time_in,
							'time_out' 					=> $time_out,
							'date_attendance_out' 		=> $datetime,
							'is_leaving_office_early'	=> $is_leaving_office_early,
							'num_of_working_hours'		=> $num_of_working_hours,
							'updated_at'				=> date("Y-m-d H:i:s")
						];
						$rs = $this->db->update("time_attendances", $data, "id='".$cek_data[0]->id."'");

						if($rs){
							$response = [
								'status' 	=> 200,
								'message' 	=> 'Success b'
							];
						}else{
							$response = [
								'status' 	=> 401,
								'message' 	=> 'Failed',
								'error' 	=> 'Error update checkout'
							];
						}
					}

				}else{ //insert
					if($tipe == 'checkin'){
						$data = [
							'date_attendance' 			=> $date,
							'employee_id' 				=> $employee,
							'attendance_type' 			=> $attendance_type,
							'time_in' 					=> $time_in,
							'time_out' 					=> $time_out,
							'date_attendance_in' 		=> $datetime,
							'is_late'					=> $is_late,
							'created_at'				=> date("Y-m-d H:i:s")
						];

						$rs = $this->db->insert("time_attendances", $data);

						if($rs){
							$response = [
								'status' 	=> 200,
								'message' 	=> 'Success'
							];
						}else{
							$response = [
								'status' 	=> 401,
								'message' 	=> 'Failed',
								'error' 	=> 'Error submit checkin'
							];
						}

					}else{ //checkout
						$response = [
							'status' 	=> 401,
							'message' 	=> 'Failed',
							'error' 	=> 'Please CheckIn first'
						];
					}
				}

			} else {
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Employee not found'
				];
			}
			
		} else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }


    public function ijin()
    {
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee	= $_REQUEST['employee'];
    	$leave_type = $_REQUEST['leave_type'];
    	$date_start	= $_REQUEST['date_start'];
    	$date_end 	= $_REQUEST['date_end'];
    	$reason		= $_REQUEST['reason'];
    	$method_type	= $_REQUEST['method_type']; //insert or update
    	$id = $_REQUEST['id'];
    	//$method_type = 'update';

		if($employee != '' && $leave_type != '' && $date_start != '' && $date_end != '' ){
			
			$cek_emp = $this->api->cek_employee($employee);	

			if($cek_emp['id'] != '')
			{
				if($method_type == 'insert'){
					$rs = $this->insert_ijin($employee, $leave_type, $date_start, $date_end, $reason);
				}else if($method_type == 'update'){
					$rs = $this->update_ijin($employee, $leave_type, $date_start, $date_end, $reason, $id);
				}
				

				if($rs){
					$response = [
						'status' 	=> 200,
						'message' 	=> 'Success'
					];
				}else{
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Error submit leave'
					];
				}
	
			} else {
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Employee not found'
				];
			}
			
		} else {
			$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Require not satisfied'
			];
		}
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }

    public function insert_ijin($employee, $leave_type, $date_start, $date_end, $reason){

    	if($employee != '' && $date_start != '' && $date_end != '' && $leave_type != ''){ 
			$cek_sisa_cuti 	= $this->api->get_data_sisa_cuti($employee, $date_start, $date_end);
			$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti;

			$diff_day		= $this->api->dayCount($date_start, $date_end);

			if($leave_type == '6'){ //Half day leave
				$diff_day = $diff_day*0.5;
			}
			if($leave_type == '5'){ //Sick Leave
				$diff_day = 0 ;
			}
			

			if($diff_day <= $sisa_cuti || $leave_type == '2'){ //unpaid leave gak ngecek sisa cuti
				$data = [
					'employee_id' 				=> $employee,
					'date_leave_start' 			=> $date_start,
					'date_leave_end' 			=> $date_end,
					'masterleave_id' 			=> $leave_type,
					'reason' 					=> $reason,
					'total_leave' 				=> $diff_day,
					'status_approval' 			=> 1, //waiting approval
					'created_at'				=> date("Y-m-d H:i:s")
				];
				$rs = $this->db->insert("leave_absences", $data);


				//update sisa jatah cuti
				if($leave_type != '2'){ //unpaid leave gak update sisa cuti
					$jatahcuti = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$employee."' and status = 1 order by period_start asc")->result(); 

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

				}

				return $rs;
			}
			else return null;
			
		}else return null;


    }


    public function update_ijin($employee, $leave_type, $date_start, $date_end, $reason, $id){

    	if(!empty($id)){

			if($date_start != '' && $date_end != '' && $leave_type != ''){ 
				$getcurrLeave = $this->db->query("select * from leave_absences where id = '".$id."' ")->result(); 
				$getcurrTotalCuti =0;
				if($getcurrLeave[0]->masterleave_id != 2){ //data sebelumnya bukan unpaid leave, maka sisa cuti dibalikin
					$getcurrTotalCuti = $getcurrLeave[0]->total_leave;
				}

				$cek_sisa_cuti 	= $this->api->get_data_sisa_cuti($employee, $date_start, $date_end); 
				$sisa_cuti 		= $cek_sisa_cuti[0]->ttl_sisa_cuti+$getcurrTotalCuti;

				$diff_day		= $this->api->dayCount($date_start, $date_end);

				if($leave_type == '6'){ //Half day leave
					$diff_day = $diff_day*0.5;
				}
				if($leave_type == '5'){ //Sick Leave
					$diff_day = 0 ;
				}

				if($diff_day <= $sisa_cuti || $leave_type == '2'){ //unpaid leave gak ngecek sisa cuti
					
					$data = [

						'date_leave_start' 			=> $date_start,
						'date_leave_end' 			=> $date_end,
						'masterleave_id' 			=> $leave_type,
						'reason' 					=> $reason,
						'total_leave' 				=> $diff_day,
						'updated_at'				=> date("Y-m-d H:i:s")
						
					];

					$rs = $this->db->update("leave_absences", $data, "id = '".	$id."'");

					//update sisa jatah cuti
					if($rs){

						$update_jatah_cuti=1;
						if($getcurrLeave[0]->masterleave_id == 2 && $leave_type == 2){ //tidak ada perubahan jika data sebelumnya dan data skrg sama2 unpaid leave
							$update_jatah_cuti=0;
							return $rs; 
						}

						if($update_jatah_cuti == 1){

							if($leave_type == 2){
								$diff_day=0;
							}

							$jml_tambahan_cuti =  $getcurrTotalCuti-$diff_day;

							if($jml_tambahan_cuti != 0){
								$jatahcuti = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$employee."' and status = 1 order by period_start asc")->result(); 

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
					}else return null;

				}else return null; // cuti gak cukup
			}
			else return null;

		} else return null;

    }


    public function get_data_ijin()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee	= $_REQUEST['employee'];

    	$where=''; 
    	if($employee != ''){
    		$where = " where a.employee_id = '".$employee."' ";
    	}

    	$dataijin = $this->db->query("select a.id, b.full_name, a.date_leave_start, a.date_leave_end, c.name as 			leave_name, a.reason, a.total_leave, 
						(case
						when a.status_approval = 1 then 'Waiting Approval'
						when a.status_approval = 2 then 'Approved'
						when a.status_approval = 3 then 'Rejected'
						 end) as status
					from leave_absences a left join employees b on b.id = a.employee_id
					left join master_leaves c on c.id = a.masterleave_id
                    ".$where." ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $dataijin
		];

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }

    public function get_data_absen()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee	= $_REQUEST['employee'];

    	$where=''; 
    	if($employee != ''){
    		$where = " where a.employee_id = '".$employee."' ";
    	}

    	$dataabsen = $this->db->query("select a.id, a.date_attendance, b.full_name, a.date_attendance_in, a.date_attendance_out, a.num_of_working_hours, if(a.is_late = 'Y','Late', '') as 'is_late_desc', 
			if(a.is_leaving_office_early = 'Y','Leaving Office Early','') as 'is_leaving_office_early_desc' 
			from time_attendances a left join employees b on b.id = a.employee_id
                    ".$where." ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $dataabsen
		];

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function get_data_tasklist()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee	= $_REQUEST['employee'];

    	$where=''; 
    	if($employee != ''){
    		$where = " where a.employee_id = '".$employee."' ";
    	}

    	$datatasklist = $this->db->query("select a.id, a.employee_id, b.full_name as employee_name, a.task, c.task as parent_name, d.name as status_name, a.progress_percentage, a.due_date, a.status_id, a.parent_id
					from tasklist a left join employees b on b.id = a.employee_id
					left join tasklist c on c.id = a.parent_id
					left join master_tasklist_status d on d.id = a.status_id
                    ".$where." ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $datatasklist
		];

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function get_master_task()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	/*$employee	= $_REQUEST['employee'];

    	$where=''; 
    	if($employee != ''){
    		$where = " where a.employee_id = '".$employee."' ";
    	}*/

    	$datamaster = $this->db->query("select * from tasklist where parent_id = 0 ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $datamaster
		];

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function get_data_employee()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$employee	= $_REQUEST['employee'];

    	$where=''; 
    	if($employee != ''){
    		$where = " where a.id = '".$employee."' ";
    	}

    	$dataemp = $this->db->query("select a.id, a.full_name, b.name as division_name, a.shift_type, c.time_in, c.time_out 
			,(select sum(total_leave) from leave_absences where employee_id = a.id) as ttl_ijin
			,(select count(id) from time_attendances where employee_id = a.id and leave_type is null) as ttl_hadir
			from employees a
			left join divisions b on b.id = a.division_id
			left join master_shift_time c on c.shift_type = a.shift_type
                    ".$where." ")->result();  

    	$response = [
    		'status' 	=> 200,
			'message' 	=> 'Success',
			'data' 		=> $dataemp
		];

		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }


    public function save_tasklist()
    { 
    	$this->verify_token();


		$jsonData = file_get_contents('php://input');
    	$data = json_decode($jsonData, true);
    	$_REQUEST = $data;

    	$type			= $_REQUEST['type']; // insert or update
    	$id 			= $_REQUEST['id'];
    	$employee		= $_REQUEST['employee_id'];
    	$task			= $_REQUEST['task'];
    	$progress		= $_REQUEST['progress'];
    	$task_parent	= $_REQUEST['task_parent_id'];
    	$due_date		= $_REQUEST['due_date'];
    	$status 		= $_REQUEST['status_id'];


    	if($type == 'insert'){

    		$data = [
				'employee_id' 			=> $employee,
				'task' 					=> $task,
				'progress_percentage'	=> $progress,
				'parent_id' 			=> $task_parent,
				'due_date' 				=> $due_date,
				'status_id' 			=> $status,
				'created_at'			=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert("tasklist", $data);

			if($rs){
				$response = [
		    		'status' 	=> 200,
					'message' 	=> 'Success'
				];
			}else{
				$response = [
					'status' 	=> 401,
					'message' 	=> 'Failed',
					'error' 	=> 'Error submit'
				];
			}

    	}else if($type == 'update'){
    		if($id != ''){
    			$data = [
					'employee_id' 			=> $employee,
					'task' 					=> $task,
					'progress_percentage'	=> $progress,
					'parent_id' 			=> $task_parent,
					'due_date' 				=> $due_date,
					'status_id' 			=> $status,
					'updated_at'			=> date("Y-m-d H:i:s")
				];
				$rs = $this->db->update("tasklist", $data, "id = '".$id."'");
				if($rs){
					$response = [
			    		'status' 	=> 200,
						'message' 	=> 'Success'
					];
				}else{
					$response = [
						'status' 	=> 401,
						'message' 	=> 'Failed',
						'error' 	=> 'Error submit'
					];
				}

    		}else{
    			$response = [
					'status' 	=> 400, // Bad Request
					'message' 	=>'Failed',
					'error' 	=> 'ID not found'
				];
    		}
    	}else{
    		$response = [
				'status' 	=> 400, // Bad Request
				'message' 	=>'Failed',
				'error' 	=> 'Type not found'
			];
    	}



		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
		
    }

}
