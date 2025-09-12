<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dash_bustrip_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "dash_bustrip_menu"; // identify menu
 	const  LABELMASTER				= "Menu Dashboard Business Trip";
 	const  LABELFOLDER				= "dashboard"; // module folder
 	const  LABELPATH				= "dash_bustrip_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "dashboard"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Dashboard"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Floating Crane","CCTV Code","CCTV Name"];
	
	/* Export */
	public $colnames 				= ["Date","Order No","Order Name","Floating Crane","Mother Vessel","Activity","Datetime Start","Datetime End","Total Time","Degree","Degree 2","PIC","Status"];
	public $colfields 				= ["date","order_no","order_name","floating_crane_name","mother_vessel_name","activity_name","datetime_start","datetime_end","total_time","degree","degree_2","pic","status_name"];




	/* Form Field Asset */
	public function form_field_asset()
	{
		$field = [];


		$msemp 				= $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		$field['selemp'] 	= $this->self_model->return_build_select2me($msemp,'','','','fldashemp','fldashemp','','','id','full_name',' ','','','',3,'-');

		$msdiv 				= $this->db->query("select * from divisions order by name asc")->result(); 
		$field['seldiv'] 	= $this->self_model->return_build_select2me($msdiv,'','','','fldiv','fldiv','','','id','name',' ','','','',1,'-');

		$field['master_emp'] = $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		
		return $field;
	}

	//========================== Considering Already Fixed =======================//
 	/* Construct */
	public function __construct() {
        parent::__construct(); 
        
        
		# akses level
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]); 
		/*define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',$akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);*/
    }

	/* Module */
 	public $folder_name				= self::LABELFOLDER."/".self::LABELPATH; // module path
 	public $module_name				= self::LABELMODULE;
 	public $model_name				= self::LABELPATH."_model";

	/* Navigation */
 	public $parent_menu				= self::LABELFOLDER;
 	public $subparent_menu			= self::LABELNAVSEG1;
 	public $subparentitem_menu		= self::LABELNAVSEG2;
 	public $sub_menu 				= self::LABELMODULE;

	/* Label */
 	public $label_parent_modul		= self::LABELFOLDER;
 	public $label_subparent_modul	= self::LABELSUBPARENTSEG1;
 	public $label_subparentitem_modul	= self::LABELSUBPARENTSEG2;
 	public $label_modul				= self::LABELMASTER;
 	public $label_list_data			= "Daftar Data ".self::LABELMASTER;
 	public $label_add_data			= "Tambah Data ".self::LABELMASTER;
 	public $label_update_data		= "Edit Data ".self::LABELMASTER;
 	public $label_sukses_disimpan 	= "Data berhasil disimpan";
 	public $label_gagal_disimpan 	= "Data gagal disimpan";
 	public $label_delete_data		= "Hapus Data ".self::LABELMASTER;
 	public $label_sukses_dihapus 	= "Data berhasil dihapus";
 	public $label_gagal_dihapus 	= "Data gagal dihapus";
 	public $label_detail_data		= "Datail Data ".self::LABELMASTER;
 	public $label_import_data		= "Import Data ".self::LABELMASTER;
 	public $label_sukses_diimport 	= "Data berhasil diimport";
 	public $label_gagal_diimport 	= "Import data di baris : ";
 	public $label_export_data		= "Export";
 	public $label_gagal_eksekusi 	= "Eksekusi gagal karena ketiadaan data";

	//============================== Additional Method ==============================//


 	public function get_data_total(){
 		$post = $this->input->post(null, true);
		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv = " and bb.division_id = '".$fldiv."'"; 
		}

		
		$ttl_trip = $this->db->query("select count(a.id) as ttl from business_trip a left join employees bb on bb.id = a.employee_id where 1=1 $whereDiv ")->result(); 
		$ttl_budget = $this->db->query("select sum(a.amount) as ttl from business_trip_detail a left join business_trip b on b.id = a.business_trip_id left join employees bb on bb.id = b.employee_id where 1=1 
			$whereDiv ")->result(); 
		$getstatus = $this->db->query("select SUM(CASE WHEN a.status_id = 1 THEN 1 ELSE 0 END) AS 	total_waitingapproval,
				SUM(CASE WHEN a.status_id = 2 THEN 1 ELSE 0 END) AS total_approved,
				SUM(CASE WHEN a.status_id = 3 THEN 1 ELSE 0 END) AS total_rejected
				from business_trip a 
				left join employees bb on bb.id = a.employee_id
				where 1=1 $whereDiv ")->result(); 
		$getrata2hari = $this->db->query("select CEIL(AVG(DATEDIFF(a.end_date, a.start_date) + 1)) AS rata_rata_hari
					FROM business_trip a left join employees bb on bb.id = a.employee_id WHERE (a.start_date IS NOT NULL AND a.end_date IS NOT NULL) $whereDiv ")->result();

		$total_trip=0;
		if(!empty($ttl_trip)){
			if(!empty($ttl_trip[0]->ttl)){
				$total_trip = $ttl_trip[0]->ttl;
			}
		}

		$total_budget=0;
		if(!empty($ttl_budget)){
			if(!empty($ttl_budget[0]->ttl)){
				$total_budget = $ttl_budget[0]->ttl;
			}
		}

		$avgDays=0;
		if(!empty($getrata2hari)){
			if(!empty($getrata2hari[0]->rata_rata_hari)){
				$avgDays = $getrata2hari[0]->rata_rata_hari;
			}
		}


		$total_waitingapproval=0; $total_approved=0; $total_rejected=0;
		if(!empty($getstatus)){
			if(!empty($getstatus[0]->total_waitingapproval)){
				$total_waitingapproval = $getstatus[0]->total_waitingapproval;
			}
			if(!empty($getstatus[0]->total_approved)){
				$total_approved = $getstatus[0]->total_approved;
			}
			if(!empty($getstatus[0]->total_rejected)){
				$total_rejected = $getstatus[0]->total_rejected;
			}
		}
		
		

		$rs = array(
			'ttl_trip' 				=> $total_trip,
			'ttl_budget'			=> $total_budget,
			'total_waitingapproval' => $total_waitingapproval,
			'total_approved' 		=> $total_approved,
			'total_rejected'		=> $total_rejected,
			'avgDays' 				=> $avgDays
		);


		
		echo json_encode($rs);
 	}


 	public function get_data_bustripbyDiv(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and bb.division_id = '".$fldiv."'";
		}


    	$rs = $this->db->query("select 
									c.id AS division_id,
									c.name AS division_name,
									COUNT(a.id) AS total
								FROM divisions c
								LEFT JOIN employees bb ON bb.division_id = c.id $whereDiv
								LEFT JOIN business_trip a ON a.employee_id = bb.id
								GROUP BY c.id, c.name
								ORDER BY c.name
								")->result(); 

		$division_name=[]; $total=[]; 
		foreach($rs as $row){
			$division_name[] 	= $row->division_name;
			$total[] 			= $row->total;
			
		}


		$data = array(
			'division_name' => $division_name,
			'total'			=> $total
		);


		echo json_encode($data);


 	}


 	public function get_data_costbyType(){
 		$post  = $this->input->post(null, true);
	    $fldiv = !empty($post['fldiv']) ? $post['fldiv'] : null;

	    $rs = $this->db->query("
	        select 
	            c.id AS type_id,
	            c.name AS type_name,
	            COALESCE(SUM(
	                CASE 
	                    WHEN (? IS NULL OR e.id = ?) 
	                    THEN a.amount ELSE 0 
	                END
	            ), 0) AS total
	        FROM master_bustrip_type c
	        LEFT JOIN business_trip_detail a 
	            ON a.bustrip_type_id = c.id
	        LEFT JOIN business_trip b 
	            ON b.id = a.business_trip_id
	        LEFT JOIN employees d 
	            ON d.id = b.employee_id 
	        LEFT JOIN divisions e 
	            ON e.id = d.division_id 
	        GROUP BY c.id, c.name
	        ORDER BY c.name
	    ", [$fldiv, $fldiv])->result();

	    
		$type_name=[]; $total=[]; 
		foreach($rs as $row){
			$type_name[] 	= $row->type_name;
			$total[] 		= $row->total;
			
		}


		$data = array(
			'type_name' => $type_name,
			'total'			=> $total
		);


		echo json_encode($data);

 	}


 	public function get_data_monthlyTripSummary()
	{
		$post  = $this->input->post(null, true);
	    $fldiv = isset($post['fldiv']) ? trim($post['fldiv']) : "";

	    $whereDiv="";
	    if(!empty($fldiv)){
	    	$whereDiv = " and c.division_id = '".$fldiv."'";
	    }


	    $sql = "select 
				    m.bulan,
				    CONCAT(YEAR(CURDATE()), '-', LPAD(m.bulan, 2, '0')) AS nama_bulan,
				    COALESCE(COUNT(DISTINCT t.id), 0) AS jumlah_trip,
				    COALESCE(SUM(t.amount), 0) AS total_cost
				FROM (
				    SELECT 1 AS bulan UNION ALL
				    SELECT 2 UNION ALL
				    SELECT 3 UNION ALL
				    SELECT 4 UNION ALL
				    SELECT 5 UNION ALL
				    SELECT 6 UNION ALL
				    SELECT 7 UNION ALL
				    SELECT 8 UNION ALL
				    SELECT 9 UNION ALL
				    SELECT 10 UNION ALL
				    SELECT 11 UNION ALL
				    SELECT 12
				) m
				LEFT JOIN (
				    SELECT 
				        a.id,
				        MONTH(a.created_date) AS bulan,
				        b.amount
				    FROM business_trip a
				    LEFT JOIN business_trip_detail b ON b.business_trip_id = a.id
				    left join employees c on c.id = a.employee_id
				    WHERE (YEAR(a.created_date) = YEAR(CURDATE())) $whereDiv
				) t ON t.bulan = m.bulan
				GROUP BY m.bulan, nama_bulan
				ORDER BY m.bulan
	    ";


	    $rs = $this->db->query($sql)->result();


	    $periode 		= [];
	    $jumlah_trip 	= [];
	    $total_cost 	= [];

	    if(!empty($rs)){
	    	foreach ($rs as $row) {
		        $periode[]		= $row->nama_bulan;
		        $jumlah_trip[]  = $row->jumlah_trip;
		        $total_cost[]   = $row->total_cost;
		    }
	    }
	    

	    $data = array(
	        'periode'		=> $periode,
	        'jumlah_trip'   => $jumlah_trip,
	        'total_cost'    => $total_cost
	    );

	    echo json_encode($data);
	}


}
