


<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fmb140 extends API_Controller
{ 

   	public function __construct()
	{
      	parent::__construct();
 
   	}

    public function index()
    {  
		$jsonData = file_get_contents('php://input');
    	$json_decode = json_decode($jsonData, true); 

		if ($jsonData <> "") {
			$data = [
				'json' 			=> $jsonData,
				'json_decode' 	=> $json_decode  
			];
			$rs = $this->db->insert("tracker", $data);
		}
		
    }  

}
