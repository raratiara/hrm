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



    public function login()
    {

		$valid_elem = ['username', 'password'];
		$params = elements($valid_elem, $this->mParams);

		if(!values_satisfied($params)){
			$username 	= $params['username'];
			$password   = $params['password'];
			
			
			$cek_login = $this->api->cek_login($username);	 
			
			if(password_verify($password, isset($cek_login['password'])?$cek_login['password']:''))
			{
				$data = array(
					"id" 	=> $cek_login['id'],
					"name" 	=> $cek_login['name'],
					"email" => $cek_login['email']
				);
	 
				$token = $this->genJWTdata($data);	 
				$response = [
					'status' 	=> 200,
					'message' 	=> 'Success',
					"token" 	=> $token[0],
					"expire" 	=> $token[1],
					"email" 	=> $cek_login['email'] 
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
    	$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
		$base_url = $protocol . $_SERVER['HTTP_HOST'] . '/';


    	$this->verify_token();


		$valid_elem = ['url'];
		$params = elements($valid_elem, $this->mParams);
		if(!values_satisfied($params)){
			$url 	= $params['url'];
			
			$cek_data = $this->api->cek_company($url);	 

			if($cek_data['id'] != '')
			{
				$urllogo = $base_url.'_hrm/uploads/logo/'.$cek_data['logo'];
				$data = array(
					"nama_perusahaan" => $cek_data['name'],
					"logo_perusahaan" => $urllogo  
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
		
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Allow-Methods: POST');
		$this->output->set_header('Access-Control-Max-Age: 3600');
		$this->output->set_header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->render_json($response, $response['status']);
    }


    public function absen()
    {
    	$this->verify_token();


		$valid_elem = ['employee','tipe','datetime_attendance'];
		$params = elements($valid_elem, $this->mParams);

		if(!values_satisfied($params)){
			$employee 	= $params['employee'];
			$tipe 		= $params['tipe'];
			$datetime 	= $params['datetime_attendance'];

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
						$num_of_working_hours 	= abs($timestamp2 - $timestamp1)/(60); //menit

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


		$valid_elem = ['employee','leave_type','date_start', 'date_end', 'reason'];
		$params = elements($valid_elem, $this->mParams);

		if(!values_satisfied($params)){
			$employee 	= $params['employee'];
			$leave_type = $params['leave_type'];
			$date_start = $params['date_start'];
			$date_end 	= $params['date_end'];
			$reason 	= $params['reason'];
			
			
			$cek_emp = $this->api->cek_employee($employee);	

			if($cek_emp['id'] != '')
			{
				//soon akan cek sisa cuti

				//$cek_leave = $this->db->query("select * from leave_absences where id = '".$employee."' ")->result();

				$data = [
					'employee_id' 				=> $employee,
					'date_leave_start' 			=> $date_start,
					'date_leave_end' 			=> $date_end,
					'masterleave_id' 			=> $leave_type,
					'reason' 					=> $reason,
					'created_at'				=> date("Y-m-d H:i:s")
				];

				$rs = $this->db->insert("leave_absences", $data);

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


}
