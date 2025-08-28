<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dash_cashadvance_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "dash_cashadvance_menu"; // identify menu
 	const  LABELMASTER				= "Menu Dashboard Cash Advance";
 	const  LABELFOLDER				= "dashboard"; // module folder
 	const  LABELPATH				= "dash_cashadvance_menu"; // controller file (lowercase)
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
			$whereDiv=" where b.division_id = '".$fldiv."'";
		}


		$ttl_ca = $this->db->query("select count(a.id) as ttl from cash_advance a left join employees b on b.id = a.requested_by left join divisions c on c.id = b.division_id ".$whereDiv." ")->result(); 

		$ttl_amountca = $this->db->query("select sum(a.total_cost) as ttl from cash_advance a left join employees b on b.id = a.requested_by left join divisions c on c.id = b.division_id ".$whereDiv." ")->result(); 

		$ttl_pertype = $this->db->query("select SUM(CASE WHEN ca_type = 1 THEN 1 ELSE 0 END) AS total_fpu, 
										SUM(CASE WHEN ca_type = 2 THEN 1 ELSE 0 END) AS total_fpp
										from cash_advance a left join employees b on b.id = a.requested_by
										left join divisions c on c.id = b.division_id
										".$whereDiv." ")->result(); 

		$ttl_sett = $this->db->query("select count(a.id) AS total_settlement
								from settlement a left join employees b on b.id = a.requested_by
								left join divisions c on c.id = b.division_id ".$whereDiv." ")->result(); 

		$total_ttl_amountca = $ttl_amountca[0]->ttl;
		if(empty($ttl_amountca[0]->ttl)){
			$total_ttl_amountca = 0;
		}

		$total_fpu = $ttl_pertype[0]->total_fpu;
		if(empty($ttl_pertype[0]->total_fpu)){
			$total_fpu = 0;
		}
		$total_fpp = $ttl_pertype[0]->total_fpp;
		if(empty($ttl_pertype[0]->total_fpp)){
			$total_fpp = 0;
		}
		$total_settlement = $ttl_sett[0]->total_settlement;
		if(empty($ttl_sett[0]->total_settlement)){
			$total_settlement = 0;
		}
		
		


		$rs = array(
			'ttl_ca' 			=> $ttl_ca[0]->ttl,
			'ttl_amount_ca'		=> $total_ttl_amountca,
			'total_fpu'			=> $total_fpu,
			'total_fpp'			=> $total_fpp,
			'total_settlement'	=> $total_settlement
		);


		
		echo json_encode($rs);
 	}


 	public function get_data_fppType(){
	    $post  = $this->input->post(null, true);
	    $fldiv = $post['fldiv'];

	    $where = "";
	    $params = [];

	    if (!empty($fldiv)) {
	        $where .= " WHERE e.id = ? ";
	        $params[] = $fldiv;
	    }

	    $sql = "
	        select base.fpp_type, COUNT(a.id) AS total
	        FROM (
	            SELECT 'Personal' AS fpp_type
	            UNION ALL
	            SELECT 'Company' AS fpp_type
	        ) base
	        LEFT JOIN cash_advance a 
	            ON a.fpp_type = base.fpp_type 
	            AND a.ca_type = 2
	        LEFT JOIN employees d 
	            ON d.id = a.requested_by
	        LEFT JOIN divisions e 
	            ON e.id = d.division_id
	        $where
	        GROUP BY base.fpp_type
	        ORDER BY FIELD(base.fpp_type, 'Personal', 'Company')
	    ";

	    $data_emp = $this->db->query($sql, $params)->result();

	    $pie_data = [];
	    foreach ($data_emp as $row) {
	        $pie_data[] = [
	            'label' => $row->fpp_type,
	            'value' => (int) $row->total
	        ];
	    }

	    echo json_encode($pie_data);
	}



 	public function get_data_fppType_old(){
	    $post  = $this->input->post(null, true);
	    $fldiv = $post['fldiv'];


	    $data_emp = $this->db->query("
								    select base.fpp_type, COUNT(a.id) AS total
								    FROM (
								        SELECT 'Personal' AS fpp_type
								        UNION ALL
								        SELECT 'Company' AS fpp_type
								    ) base
								    LEFT JOIN cash_advance a 
								        ON a.fpp_type = base.fpp_type 
								        AND a.ca_type = 2
								    LEFT JOIN employees d 
								        ON d.id = a.requested_by
								    LEFT JOIN divisions e 
								        ON e.id = d.division_id 
								        AND (? IS NULL OR ? = '' OR e.id = ?)
								    GROUP BY base.fpp_type
								    ORDER BY FIELD(base.fpp_type, 'Personal', 'Company')
								", [$fldiv, $fldiv, $fldiv])->result();


	    // Ubah hasil query ke format pie chart
	    $pie_data = [];
	    foreach ($data_emp as $row) {
	        $pie_data[] = [
	            'label' => $row->fpp_type,
	            'value' => (int) $row->total
	        ];
	    }

	    echo json_encode($pie_data);
	}




 	public function get_data_monthlyCASummary(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		// buat where dinamis
	    $whereDiv = "";
	    if (!empty($fldiv)) { 
	        $whereDiv = " WHERE a.division_id = '".$this->db->escape_str($fldiv)."' ";
	    }

		
		/*$rs = $this->db->query("select
									aa.request_date, a.division_id, b.name as division_name,
									SUM(CASE WHEN aa.status_id = '1' THEN 1 ELSE 0 END) AS total_waitingapproval,
									SUM(CASE WHEN aa.status_id = '2' THEN 1 ELSE 0 END) AS total_approve,
									SUM(CASE WHEN aa.status_id = '3' THEN 1 ELSE 0 END) AS total_reject,
								    SUM(CASE WHEN aa.status_id = '4' THEN 1 ELSE 0 END) AS total_rfu,
									COUNT(*) AS total_ca
								FROM
								cash_advance aa 
									left join employees a on a.id = aa.requested_by
									left join divisions b on b.id = a.division_id
									$whereDiv
								GROUP BY
									DATE_FORMAT(aa.request_date, '%Y-%m')
								ORDER BY request_date ASC")->result();*/

		$rs = $this->db->query("select
									DATE_FORMAT(aa.request_date, '%Y-%m') AS periode,
									MAX(a.division_id) AS division_id,
									MAX(b.name) AS division_name,
									SUM(CASE WHEN aa.status_id = '1' THEN 1 ELSE 0 END) AS total_waitingapproval,
									SUM(CASE WHEN aa.status_id = '2' THEN 1 ELSE 0 END) AS total_approve,
									SUM(CASE WHEN aa.status_id = '3' THEN 1 ELSE 0 END) AS total_reject,
									SUM(CASE WHEN aa.status_id = '4' THEN 1 ELSE 0 END) AS total_rfu,
									COUNT(*) AS total_ca
								FROM cash_advance aa
								LEFT JOIN employees a ON a.id = aa.requested_by
								LEFT JOIN divisions b ON b.id = a.division_id 
								$whereDiv
								GROUP BY DATE_FORMAT(aa.request_date, '%Y-%m')
								ORDER BY periode ASC;
								")->result(); 

		$request_date=[]; $total_waitingapproval=[]; $total_approve=[]; $total_reject=[]; $total_rfu=[];
		foreach($rs as $row){
			$request_date[] 			= $row->periode;
			$total_waitingapproval[] 	= $row->total_waitingapproval;
			$total_approve[]			= $row->total_approve;
			$total_reject[]				= $row->total_reject;
			$total_rfu[]				= $row->total_rfu;
		}


		$data = array(
			'request_date' 	=> $request_date,
			'total_waitingapproval' => $total_waitingapproval,
			'total_approve' 		=> $total_approve,
			'total_reject' 			=> $total_reject,
			'total_rfu' 			=> $total_rfu
		);


		echo json_encode($data);
 	}


 	public function get_data_byDiv(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and c.id = '".$fldiv."'";
		}


    	$rs = $this->db->query("select 
									c.id AS division_id,
									c.name AS division_name,
									COUNT(a.id) AS total
								FROM divisions c
								LEFT JOIN employees b ON b.division_id = c.id
								LEFT JOIN cash_advance a ON a.requested_by = b.id
								where 1=1 ".$whereDiv."
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



 	public function get_data_projectSummary(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		$whereDiv="";
		if(!empty($fldiv)){ 
			$whereDiv=" and b.division_id = '".$fldiv."'";
		}


    	$rs = $this->db->query("select 
								    c.id AS division_id,
								    c.name AS division_name,
								    SUM(CASE WHEN a.status_id = 1 THEN 1 ELSE 0 END) AS total_open,
								    SUM(CASE WHEN a.status_id = 2 THEN 1 ELSE 0 END) AS total_inprogress,
								    SUM(CASE WHEN a.status_id = 3 THEN 1 ELSE 0 END) AS total_closed
								FROM divisions c
								LEFT JOIN employees b ON b.division_id = c.id
								LEFT JOIN tasklist a ON a.employee_id = b.id and a.status_id != '' ".$whereDiv."
								GROUP BY c.id, c.name

						 ")->result(); 

		$division_name=[]; $total_open=[]; $total_inprogress=[]; $total_closed=[]; 
		foreach($rs as $row){
			$division_name[] 		= $row->division_name;
			$total_open[] 			= $row->total_open;
			$total_inprogress[] 	= $row->total_inprogress;
			$total_closed[] 		= $row->total_closed;
			
		}


		$data = array(
			'division_name' 	=> $division_name,
			'total_open'		=> $total_open,
			'total_inprogress' 	=> $total_inprogress,
			'total_closed' 		=> $total_closed
		);


		echo json_encode($data);

 	}


 	public function get_data_bySubtype(){
 		$post = $this->input->post(null, true);
 		$fldiv 	= $post['fldiv'];


		if (!empty($fldiv)) {
		    // ada filter divisi
		    $sql = "
		        select 
		            c.id AS subtypeid,
		            c.fulltipe AS subtypename,
		            COUNT(
		                CASE WHEN b.division_id = '".$fldiv."' THEN a.id END
		            ) AS total,
		            '".$fldiv."' AS division_id
		        FROM (
		            SELECT aa.id, CONCAT(bb.name,' - ',aa.name) AS fulltipe 
		            FROM master_reimburs_subtype aa 
		            LEFT JOIN master_reimburs_type bb ON bb.id = aa.reimburs_type_id
		        ) c
		        LEFT JOIN reimbursement_detail ab 
		            ON ab.subtype_id = c.id
		        LEFT JOIN medicalreimbursements a 
		            ON a.id = ab.reimbursement_id
		        LEFT JOIN employees b 
		            ON b.id = a.employee_id
		        GROUP BY c.id, c.fulltipe
		        ORDER BY c.fulltipe
		    ";
		} else {
		    // tidak ada filter divisi → hitung semua
		    $sql = "
		        select 
		            c.id AS subtypeid,
		            c.fulltipe AS subtypename,
		            COUNT(a.id) AS total,
		            NULL AS division_id
		        FROM (
		            SELECT aa.id, CONCAT(bb.name,' - ',aa.name) AS fulltipe 
		            FROM master_reimburs_subtype aa 
		            LEFT JOIN master_reimburs_type bb ON bb.id = aa.reimburs_type_id
		        ) c
		        LEFT JOIN reimbursement_detail ab 
		            ON ab.subtype_id = c.id
		        LEFT JOIN medicalreimbursements a 
		            ON a.id = ab.reimbursement_id
		        LEFT JOIN employees b 
		            ON b.id = a.employee_id
		        GROUP BY c.id, c.fulltipe
		        ORDER BY c.fulltipe
		    ";
		}

		$result = $this->db->query($sql)->result_array();

		/*$subtypename=[]; $total=[]; 
		foreach($result as $row){
			$subtypename[] 	= $row->subtypename;
			$total[] 		= $row->total;
			
		}*/
		$subtypename=[]; 
		$total=[]; 
		foreach($result as $row){
		    $subtypename[] = $row['subtypename'];
		    $total[]       = $row['total'];
		}


		$data = array(
			'subtypename' 	=> $subtypename,
			'total'			=> $total
		);


		echo json_encode($data);


 	}

 	public function get_data_monthlyCAAmount()
	{
	    $post  = $this->input->post(null, true);
	    $fldiv = isset($post['fldiv']) ? trim($post['fldiv']) : "";

	    if (empty($fldiv)) { // all div
	        $sql = "
	            select 
	                CONCAT(YEAR(CURDATE()), '-', LPAD(b.bln, 2, '0')) AS tahun_bulan,
	                COALESCE(SUM(CASE WHEN m.ca_type = 1 THEN m.total_cost ELSE 0 END), 0) AS nominal_raw_fpu,
	                FORMAT(COALESCE(SUM(CASE WHEN m.ca_type = 1 THEN m.total_cost ELSE 0 END), 0), 2) AS nominal_ca_fpu,
	                COALESCE(SUM(CASE WHEN m.ca_type = 2 THEN m.total_cost ELSE 0 END), 0) AS nominal_raw_fpp,
	                FORMAT(COALESCE(SUM(CASE WHEN m.ca_type = 2 THEN m.total_cost ELSE 0 END), 0), 2) AS nominal_ca_fpp
	            FROM (
	                SELECT 1 AS bln UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
	                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8
	                UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12
	            ) b
	            LEFT JOIN cash_advance m 
	                ON MONTH(m.request_date) = b.bln 
	               AND YEAR(m.request_date) = YEAR(CURDATE())
	            LEFT JOIN employees n 
	                ON n.id = m.requested_by
	            GROUP BY b.bln
	            ORDER BY b.bln
	        ";
	    } else { // with filter
	        $sql = "
	            SELECT 
	                CONCAT(YEAR(CURDATE()), '-', LPAD(b.bln, 2, '0')) AS tahun_bulan,
	                COALESCE(SUM(CASE WHEN n.division_id = '".$fldiv."' AND m.ca_type = 1 THEN m.total_cost ELSE 0 END), 0) AS nominal_raw_fpu,
	                FORMAT(COALESCE(SUM(CASE WHEN n.division_id = '".$fldiv."' AND m.ca_type = 1 THEN m.total_cost ELSE 0 END), 0), 2) AS nominal_ca_fpu,
	                COALESCE(SUM(CASE WHEN n.division_id = '".$fldiv."' AND m.ca_type = 2 THEN m.total_cost ELSE 0 END), 0) AS nominal_raw_fpp,
	                FORMAT(COALESCE(SUM(CASE WHEN n.division_id = '".$fldiv."' AND m.ca_type = 2 THEN m.total_cost ELSE 0 END), 0), 2) AS nominal_ca_fpp
	            FROM (
	                SELECT 1 AS bln UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
	                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8
	                UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12
	            ) b
	            LEFT JOIN cash_advance m 
	                ON MONTH(m.request_date) = b.bln 
	               AND YEAR(m.request_date) = YEAR(CURDATE())
	            LEFT JOIN employees n 
	                ON n.id = m.requested_by
	            GROUP BY b.bln
	            ORDER BY b.bln
	        ";
	    }

	    $rs = $this->db->query($sql)->result();

	    $periode = [];
	    $nominal_raw_fpu = [];
	    $nominal_ca_fpu  = [];
	    $nominal_raw_fpp = [];
	    $nominal_ca_fpp  = [];

	    foreach ($rs as $row) {
	        $periode[]         = $row->tahun_bulan;
	        $nominal_raw_fpu[] = $row->nominal_raw_fpu;
	        $nominal_ca_fpu[]  = $row->nominal_ca_fpu;
	        $nominal_raw_fpp[] = $row->nominal_raw_fpp;
	        $nominal_ca_fpp[]  = $row->nominal_ca_fpp;
	    }

	    $data = array(
	        'periode'          => $periode,
	        'nominal_raw_fpu'  => $nominal_raw_fpu,
	        'nominal_ca_fpu'   => $nominal_ca_fpu,
	        'nominal_raw_fpp'  => $nominal_raw_fpp,
	        'nominal_ca_fpp'   => $nominal_ca_fpp,
	    );

	    echo json_encode($data);
	}


	public function get_data_outstandingTrend()
	{
		$post  = $this->input->post(null, true);
	    $fldiv = isset($post['fldiv']) ? trim($post['fldiv']) : "";

	    $whereDiv="";
	    if(!empty($fldiv)){
	    	$whereDiv = " and b.division_id = '".$fldiv."'";
	    }


	    /*$sql = "
	        select 
	            CONCAT(YEAR(ca.request_date), '-', LPAD(MONTH(ca.request_date), 2, '0')) AS tahun_bulan,
	            COALESCE(SUM(ca.total_cost), 0) AS total_pengajuan,
	            COALESCE(SUM(s.total_cost), 0) AS total_pemakaian,
	            (COALESCE(SUM(ca.total_cost), 0) - COALESCE(SUM(s.total_cost), 0)) AS outstanding,
	            COUNT(DISTINCT CASE WHEN s.id IS NULL THEN ca.id END) AS jumlah_belum_settlement
	        FROM cash_advance ca
	        LEFT JOIN settlement s ON s.cash_advance_id = ca.id
	        left join employees b on b.id = ca.requested_by
	        WHERE (YEAR(ca.request_date) = YEAR(CURDATE())) $whereDiv
	        GROUP BY YEAR(ca.request_date), MONTH(ca.request_date) 
	        ORDER BY tahun_bulan
	    ";*/

	    $sql = "
	        select 
				DATE_FORMAT(ca.request_date, '%Y-%m') AS tahun_bulan,
				COALESCE(SUM(ca.total_cost), 0) AS total_pengajuan,
				COALESCE(SUM(s.total_cost), 0) AS total_pemakaian,
				(COALESCE(SUM(ca.total_cost), 0) - COALESCE(SUM(s.total_cost), 0)) AS outstanding,
				COUNT(DISTINCT CASE WHEN s.id IS NULL THEN ca.id END) AS jumlah_belum_settlement
			FROM cash_advance ca
			LEFT JOIN settlement s ON s.cash_advance_id = ca.id
			LEFT JOIN employees b ON b.id = ca.requested_by
			WHERE YEAR(ca.request_date) = YEAR(CURDATE()) $whereDiv
			GROUP BY DATE_FORMAT(ca.request_date, '%Y-%m')
			ORDER BY tahun_bulan;

	    ";


	    $rs = $this->db->query($sql)->result();


	    $periode = [];
	    $pengajuan = [];
	    $pemakaian = [];
	    $outstanding = [];
	    $belum_settlement = [];

	    if(!empty($rs)){
	    	foreach ($rs as $row) {
		        $periode[]          = $row->tahun_bulan;
		        $pengajuan[]        = $row->total_pengajuan;
		        $pemakaian[]        = $row->total_pemakaian;
		        $outstanding[]      = $row->outstanding;
		        $belum_settlement[] = $row->jumlah_belum_settlement;
		    }
	    }
	    

	    $data = array(
	        'periode'           => $periode,
	        'pengajuan'         => $pengajuan,
	        'pemakaian'         => $pemakaian,
	        'outstanding'       => $outstanding,
	        'belum_settlement'  => $belum_settlement
	    );

	    echo json_encode($data);
	}




 


 	

}
