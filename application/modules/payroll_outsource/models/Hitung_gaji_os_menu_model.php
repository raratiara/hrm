<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_gaji_os_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "payroll_outsource/hitung_gaji_os_menu";
 	protected $table_name 				= _PREFIX_TABLE."payroll_slip";
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
			'dt.project_name',
			'dt.month_name',
			'dt.tahun_penggajian',
			'dt.status',
			'dt.project_id'
		];

		$where_project = "";
			if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
			$where_project = " and a.project_id = '".$_GET['flproject']."' ";
		}

		

		$sIndexColumn = $this->primary_key;
		/*$sTable = '(select a.*, b.full_name, c.name_indo as periode_bulan_name, b.emp_code, d.project_name, e.name as job_title_name, f.tanggal_pembayaran_lembur
				from payroll_slip a 
				left join employees b on b.id = a.employee_id 
				left join master_month c on c.id = a.periode_bulan
				left join project_outsource d on d.id = b.project_id
				left join master_job_title_os e on e.id = b.job_title_id
				left join data_customer f on f.id = d.customer_id
				where 1=1 '.$where_project.'
			)dt';*/


		$sTable = '(select a.*, b.name_indo as month_name, c.project_name 
					from payroll_slip a 
					left join master_month b on b.id = a.bulan_penggajian
					left join project_outsource c on c.id = a.project_id
					where 1=1 '.$where_project.'
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

			
			
			$print_gaji = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportGaji('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Slip Gaji
            </a>';
            $print_lembur = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportLembur('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Lembur
            </a>';
            $print_absen = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportAbsenOS_gaji('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Rekap Absen
            </a>';
            $print_rekap_gaji = '<a class="btn btn-default btn-xs" style="align:center" onclick="getRekapGajiOS('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Rekap Gaji
            </a>';


			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
				'.$print_gaji.'
				'.$print_lembur.'
				'.$print_absen.'
				'.$print_rekap_gaji.'
				'.$detail.'
				'.$edit.'
				'.$delete.'
				</div>',
				$row->id,
				$row->project_name,
				$row->month_name,
				$row->tahun_penggajian,
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

	/*public function ceil_2($number){
	    return ceil($number * 100) / 100;
	}*/



	public function add_data($post)
	{

		$this->load->helper('global');

	    if (empty($post['penggajian_month']) || empty($post['penggajian_year'])) {
	        
	        return [
			    "status" => false,
			    "msg" 	 => "Bulan Tahun Penggajian harus diisi"
			];
	    }

	    $this->db->trans_start();

	    $bulan  = (int)$post['penggajian_month'];
	    $tahun  = trim($post['penggajian_year']);

	    // =========================
	    // Ambil kode bulan
	    // =========================
	    $codemonth = $this->db
	        ->select('code')
	        ->from('master_month')
	        ->where('id', $bulan)
	        ->get()
	        ->row();

	    $periode_gaji = $tahun . '-' . $codemonth->code;

	    // =========================
	    // MAIN QUERY (1 QUERY SAJA)
	    // =========================
	    $this->db->select("
	        e.id as employee_id,
	        e.project_id,
	        e.total_hari_kerja,
	        e.gaji_bulanan,
	        e.gaji_harian,
	        e.no_bpjs,
	        e.no_bpjs_ketenagakerjaan,

	        sd.total_masuk,
	        sd.total_ijin,
	        sd.total_cuti,
	        sd.total_alfa,
	        sd.total_lembur,
	        sd.total_jam_kerja,
	        sd.total_jam_lembur,

	        s.tgl_start_absen,
	        s.tgl_end_absen,
	        t.sistem_lembur,
	        t.nominal_lembur,

	        COALESCE(l.ttl_hutang,0) as hutang
	    ");

	    $this->db->from('employees e');
	    $this->db->join('summary_absen_outsource_detail sd', 'sd.emp_id = e.id', 'left');
	    $this->db->join('summary_absen_outsource s', 's.id = sd.summary_absen_outsource_id', 'left');

	    $this->db->join("(SELECT b.id_employee,
	                        SUM(b.nominal_cicilan_per_bulan) as ttl_hutang
	                     FROM loan_detail a
	                     JOIN loan b ON b.id = a.loan_id
	                     WHERE DATE_FORMAT(a.tgl_jatuh_tempo,'%Y-%m') = '$periode_gaji'
	                     GROUP BY b.id_employee) l",
	                     "l.id_employee = e.id",
	                     "left");

	    $this->db->join('data_customer t', 't.id = e.cust_id', 'left');

	    $this->db->where('e.emp_source', 'outsource');
	    $this->db->where('e.status_id', 1);
	    $this->db->where('s.bulan_penggajian', $bulan);
	    $this->db->where('s.tahun_penggajian', $tahun);

	    if ($post['is_all_project'] == 'Karyawan' && !empty($post['employeeIds'])) {
	        $this->db->where_in('e.id', $post['employeeIds']);
	    }

	    if ($post['is_all_project'] == 'Sebagian' && !empty($post['projectIds'])) {
	        $this->db->where_in('e.project_id', $post['projectIds']);
	    }

	    $data = $this->db->get()->result();

	    if (empty($data)) {
	        $this->db->trans_complete();
	        
	        return [
			    "status" => false,
			    "msg" 	 => "Data gagal disimpan"
			];
	    }

	    // =========================
	    // Cache Payroll Header Per Project
	    // =========================
	    $projectHeader = [];
	    $insertDetail  = [];
	    $bpjsHeaderCache = [];


	    foreach ($data as $row) {

	        // =========================
	        // Buat header payroll jika belum ada
	        // =========================
	        if (!isset($projectHeader[$row->project_id])) {

	            $header = $this->db->where([
	                'project_id'       => $row->project_id,
	                'bulan_penggajian' => $bulan,
	                'tahun_penggajian' => $tahun
	            ])->get('payroll_slip')->row();

	            if (!$header) {
	                $this->db->insert('payroll_slip', [
	                    'project_id'       => $row->project_id,
	                    'bulan_penggajian' => $bulan,
	                    'tahun_penggajian' => $tahun,
	                    'tgl_start_absen'  => $row->tgl_start_absen,
	                    'tgl_end_absen'    => $row->tgl_end_absen
	                ]);
	                $projectHeader[$row->project_id] = $this->db->insert_id();
	            } else {
	                $projectHeader[$row->project_id] = $header->id;
	            }
	        }

	        // =========================
	        // HITUNG GAJI
	        // =========================
	        $gaji_bulanan = (float)$row->gaji_bulanan;
	        $gaji_harian  = (float)$row->gaji_harian;

	        $total_tidak_masuk =
	            (int)$row->total_ijin +
	            (int)$row->total_cuti +
	            (int)$row->total_alfa;

	        $gaji = ceil($row->total_masuk * $gaji_harian);

	        
	        if($row->sistem_lembur == 'tidak_sistem_lembur'){
	        	$lembur_perjam  = $row->nominal_lembur ?? 0;
	        }else{
	        	$lembur_perjam  = ceil($gaji_bulanan / 173);
	        }


	        //$lembur_total   = ceil($lembur_perjam * $row->total_jam_lembur);
	        $lembur_total = $row->total_lembur;

	        $bpjs_kesehatan = ceil($gaji_bulanan * 0.04);
	        $bpjs_tk        = ceil($gaji_bulanan * 0.0624);

	        $hari_kerja = (int)$row->total_hari_kerja;

	        /*$potongan_absen = $hari_kerja > 0
	            ? ceil($total_tidak_masuk * ($gaji_bulanan / $hari_kerja))
	            : 0;*/

	        $sosial = 5000;
	        $hutang = (float)$row->hutang;

	        //$total_pendapatan = ceil($gaji + $lembur_total);
	        $total_pendapatan = $gaji;

	        $subtotal    = ceil($total_pendapatan - ($hutang + $sosial));
	        $gaji_bersih = ceil($subtotal - ($bpjs_kesehatan + $bpjs_tk));

	        

	        // =========================
			// INSERT / UPDATE HISTORY BPJS
			// =========================

			if (!isset($bpjsHeaderCache[$row->project_id])) {

			    $bpjs_header = $this->db->where([
			        'project_id'         => $row->project_id,
			        'periode_gaji_bulan' => $bulan,
			        'periode_gaji_tahun' => $tahun
			    ])->get('history_bpjs')->row();

			    if (!$bpjs_header) {

			        $this->db->insert("history_bpjs", [
			            'project_id'         => $row->project_id,
			            'periode_gaji_bulan' => $bulan,
			            'periode_gaji_tahun' => $tahun
			        ]);

			        $bpjsHeaderCache[$row->project_id] = $this->db->insert_id();

			    } else {
			        $bpjsHeaderCache[$row->project_id] = $bpjs_header->id;
			    }
			}

			$history_bpjs_id = $bpjsHeaderCache[$row->project_id];


			// cek detail bpjs employee
			$bpjs_detail = $this->db->where([
			    'history_bpjs_id' => $history_bpjs_id,
			    'employee_id'     => $row->employee_id
			])->get('history_bpjs_detail')->row();

			$data_bpjs_detail = [
			    'history_bpjs_id'       => $history_bpjs_id,
			    'employee_id'           => $row->employee_id,
			    'no_bpjs_kesehatan'     => $row->no_bpjs,
			    'no_bpjs_tk'            => $row->no_bpjs_ketenagakerjaan,
			    'nominal_bpjs_kesehatan'=> $bpjs_kesehatan,
			    'nominal_bpjs_tk'       => $bpjs_tk,
			    'tanggal_potong'        => date("Y-m-d H:i:s")
			];

			if ($bpjs_detail) {
			    $this->db->update(
			        "history_bpjs_detail",
			        $data_bpjs_detail,
			        ['id' => $bpjs_detail->id]
			    );
			} else {
			    $this->db->insert("history_bpjs_detail", $data_bpjs_detail);
			}


			// =========================
	        // Simpan ke batch insert
	        // =========================

	        $insertDetail[] = [
	            'payroll_slip_id'  => $projectHeader[$row->project_id],
	            'employee_id'      => $row->employee_id,
	            'total_hari_kerja' => $row->total_hari_kerja,
	            'total_masuk'      => $row->total_masuk,
	            'total_tidak_masuk'=> $total_tidak_masuk,
	            'total_jam_kerja'  => $row->total_jam_kerja,
	            'total_jam_lembur' => $row->total_jam_lembur,
	            'created_at'       => date("Y-m-d H:i:s"),
	            'created_by'       => $_SESSION['worker'],
	            'gaji_bulanan'     => $gaji_bulanan,
	            'gaji_harian'      => $gaji_harian,
	            'gaji'             => $gaji,
	            'lembur_perjam'    => $lembur_perjam,
	            'total_nominal_lembur' => $lembur_total,
	            'total_pendapatan' => $total_pendapatan,
	            'sosial'           => $sosial,
	            'bpjs_kesehatan'   => $bpjs_kesehatan,
	            'bpjs_tk'          => $bpjs_tk,
	            'hutang'           => $hutang,
	            'subtotal'         => $subtotal,
	            'gaji_bersih'      => $gaji_bersih
	        ];
	    }

	    // =========================
	    // INSERT BATCH (SUPER CEPAT)
	    // =========================
	    if (!empty($insertDetail)) {
	        $this->db->insert_batch('payroll_slip_detail', $insertDetail);
	    }

	    $this->db->trans_complete();

	    

	    if($this->db->trans_status()){
			return [
			    "status" => true,
			    "msg" => "Data berhasil disimpan"
			];
		}else{
			return [
			    "status" => false,
			    "msg" 	 => "Data gagal disimpan"
			];
		}
	}



	public function add_data_old($post) { 
		

  		if(!empty($post['penggajian_month']) && !empty($post['penggajian_year']) ){ 

  			$codemonth = $this->db->query("select * from master_month where id = '".$post['penggajian_month']."' ")->result();
			$periode_gaji = $post['penggajian_year'].'-'.$codemonth[0]->code; //2026-03
  			
			if ($post['is_all_project'] == 'Karyawan') {
			    if (!empty($post['employeeIds']) && is_array($post['employeeIds'])) {
			        $this->db->where_in('id', $post['employeeIds']);
			    }
			}else if ($post['is_all_project'] == 'Sebagian') {
			    if (!empty($post['projectIds']) && is_array($post['projectIds'])) {
			        $this->db->where_in('project_id', $post['projectIds']);
			    }
			}

			$data_os = $this->db
			    ->select('id, total_hari_kerja, gaji_bulanan, gaji_harian, no_bpjs, no_bpjs_ketenagakerjaan, project_id')
			    ->from('employees')
			    ->where('emp_source', 'outsource')
			    ->where('status_id', 1)
			    ->get()
			    ->result();


  			if(!empty($data_os)){
  				foreach($data_os as $rowdata_os){
  					$emp_id = $rowdata_os->id;
  					
  					$data_summary = $this->db->query("select a.*, b.project_id, b.full_name, b.gaji_bulanan, b.gaji_harian, b.emp_code, b.id as employee_id, c.bulan_penggajian, c.tahun_penggajian, c.project_id, c.tgl_start_absen, c.tgl_end_absen, d.sistem_lembur, d.nominal_lembur
						from summary_absen_outsource_detail a left join employees b on b.id = a.emp_id
						left join summary_absen_outsource c on c.id = a.summary_absen_outsource_id
						left join data_customer d on d.id = b.cust_id
						where c.bulan_penggajian = ".$post['penggajian_month']." and c.tahun_penggajian = '".$post['penggajian_year']."' and c.project_id = '".$rowdata_os->project_id."' and a.emp_id = '".$emp_id."'
						order by b.full_name asc")->result();

  					if(!empty($data_summary)){
  						$gaji_bulanan = (int)$rowdata_os->gaji_bulanan;

  						$total_tidak_masuk = ((int)$data_summary[0]->total_ijin ?? 0) +
									     ((int)$data_summary[0]->total_cuti ?? 0) +
									     ((int)$data_summary[0]->total_alfa ?? 0);
  						$gaji = ceil(($data_summary[0]->total_masuk * (int)$rowdata_os->gaji_harian) * 100) / 100;

  						if($data_summary[0]->sistem_lembur == 'tidak_sistem_lembur'){
  							$lembur_perjam 	= $data_summary[0]->nominal_lembur ?? 0;
  						}else{
  							$lembur_perjam 	= ceil(($gaji_bulanan / 173) * 100) / 100;
  						}
  						

  						$total_nominal_lembur = ceil($lembur_perjam*$data_summary[0]->total_jam_lembur);
  						$bpjs_kesehatan = ceil(($gaji_bulanan * 0.04) * 100) / 100; /// 4% dr GP
  						$bpjs_tk = ceil(($gaji_bulanan * 0.0624) * 100) / 100; /// 6.24% dr GP

  						$hari_kerja = (int) ($rowdata_os->total_hari_kerja ?? 0);
						/*if ($hari_kerja > 0) {
						    $potongan_absen = ceil(
						        ($total_tidak_masuk * ($gaji_bulanan / $hari_kerja)) * 100
						    ) / 100;
						} else {
						    $potongan_absen = 0;
						}*/


  						$sosial = '5000';

  						
  						//ambil pinjaman yg masih berjalan
  						/*$data_pinjaman = $this->db->query("select sum(nominal_cicilan_per_bulan) as ttl_hutang from loan where id_employee = '".$emp_id."' and status_id = 5")->result();*/
  						$data_pinjaman = $this->db->query("select sum(nominal_cicilan_per_bulan) as ttl_hutang from loan_detail a left join loan b on b.id = a.loan_id
							where b.id_employee = '".$emp_id."' and DATE_FORMAT(a.tgl_jatuh_tempo, '%Y-%m') = '".$periode_gaji."'")->result();

  						$hutang=0;
  						if(!empty($data_pinjaman)){
  							$hutang = $data_pinjaman[0]->ttl_hutang;
  						}

  						/// ttl pendapatan - potongan tdk wajib
  						$subtotal = ceil(($gaji - ($hutang+$sosial)) * 100) / 100; 

  						/// subtotal - potongan wajib
  						$gaji_bersih = ceil(($subtotal - ($bpjs_kesehatan+$bpjs_tk)) * 100) / 100;



  						$data_payslip = $this->db->query("select * from payroll_slip where project_id = '".$rowdata_os->project_id."' and bulan_penggajian = '".$post['penggajian_month']."' and tahun_penggajian = '".$post['penggajian_year']."' ")->result();
  						if(empty($data_payslip)){ /// add header + add detail
  							$data = [
	  							'project_id' 		=> $rowdata_os->project_id,
								'bulan_penggajian' 	=> trim($post['penggajian_month']),
								'tahun_penggajian' 	=> trim($post['penggajian_year']),
								'tgl_start_absen' 	=> $data_summary[0]->tgl_start_absen,
								'tgl_end_absen' 	=> $data_summary[0]->tgl_end_absen
							];
							$this->db->insert($this->table_name, $data);
							$lastId = $this->db->insert_id();

							$data_dtl = [
								'payroll_slip_id' 	=> $lastId,
								'employee_id' 		=> $emp_id,
								'total_hari_kerja'  => $data_summary[0]->total_hari_kerja,
								'total_masuk'  		=> $data_summary[0]->total_masuk,
								'total_tidak_masuk' => $total_tidak_masuk,
								'total_lembur'  	=> $data_summary[0]->total_lembur,
								'total_jam_kerja'  	=> $data_summary[0]->total_jam_kerja,
								'total_jam_lembur'  => $data_summary[0]->total_jam_lembur,
								'created_at'		=> date("Y-m-d H:i:s"),
								'created_by' 		=> $_SESSION['worker'],
								'gaji_bulanan'  	=> $gaji_bulanan,
								'gaji_harian' 		=> $rowdata_os->gaji_harian,
								'gaji' 				=> $gaji,
								'lembur_perjam' 	=> $lembur_perjam,
								'total_nominal_lembur' 	=> $total_nominal_lembur,
								'total_pendapatan' 	=> $gaji,
								'sosial' 			=> $sosial,
								'bpjs_kesehatan' 	=> $bpjs_kesehatan,
								'bpjs_tk' 			=> $bpjs_tk,
								'hutang' 			=> $hutang,
								'subtotal' 			=> $subtotal,
								'gaji_bersih' 		=> $gaji_bersih
							];
							$rs = $this->db->insert("payroll_slip_detail", $data_dtl);
  						}else{
  							$data_payslip_detail = $this->db->query("select a.* from payroll_slip_detail a left join payroll_slip b on b.id = a.payroll_slip_id where a.employee_id = '".$emp_id."' and b.bulan_penggajian = ".$post['penggajian_month']." and b.tahun_penggajian = '".$post['penggajian_year']."' ")->result();

  							if(empty($data_payslip_detail)){ ///ADD 
  								$data_dtl = [
									'payroll_slip_id' 	=> $data_payslip[0]->id,
									'employee_id' 		=> $emp_id,
									'total_hari_kerja'  => $data_summary[0]->total_hari_kerja,
									'total_masuk'  		=> $data_summary[0]->total_masuk,
									'total_tidak_masuk' => $total_tidak_masuk,
									'total_lembur'  	=> $data_summary[0]->total_lembur,
									'total_jam_kerja'  	=> $data_summary[0]->total_jam_kerja,
									'total_jam_lembur'  => $data_summary[0]->total_jam_lembur,
									'created_at'		=> date("Y-m-d H:i:s"),
									'created_by' 		=> $_SESSION['worker'],
									'gaji_bulanan'  	=> $gaji_bulanan,
									'gaji_harian' 		=> $rowdata_os->gaji_harian,
									'gaji' 				=> $gaji,
									'lembur_perjam' 	=> $lembur_perjam,
									'total_nominal_lembur' => $total_nominal_lembur,
									'total_pendapatan' 	=> $gaji,
									'sosial' 			=> $sosial,
									'bpjs_kesehatan' 	=> $bpjs_kesehatan,
									'bpjs_tk' 			=> $bpjs_tk,
									'hutang' 			=> $hutang,
									'subtotal' 			=> $subtotal,
									'gaji_bersih' 		=> $gaji_bersih
								];
								$rs = $this->db->insert("payroll_slip_detail", $data_dtl);
  							}else{ ///UPDATE
  								$data_dtl = [
									'payroll_slip_id' 	=> $data_payslip[0]->id,
									'employee_id' 		=> $emp_id,
									'total_hari_kerja'  => $data_summary[0]->total_hari_kerja,
									'total_masuk'  		=> $data_summary[0]->total_masuk,
									'total_tidak_masuk' => $total_tidak_masuk,
									'total_lembur'  	=> $data_summary[0]->total_lembur,
									'total_jam_kerja'  	=> $data_summary[0]->total_jam_kerja,
									'total_jam_lembur'  => $data_summary[0]->total_jam_lembur,
									'created_at'		=> date("Y-m-d H:i:s"),
									'created_by' 		=> $_SESSION['worker'],
									'gaji_bulanan'  	=> $gaji_bulanan,
									'gaji_harian' 		=> $rowdata_os->gaji_harian,
									'gaji' 				=> $gaji,
									'lembur_perjam' 	=> $lembur_perjam,
									'total_nominal_lembur' => $total_nominal_lembur,
									'total_pendapatan' 	=> $gaji,
									'sosial' 			=> $sosial,
									'bpjs_kesehatan' 	=> $bpjs_kesehatan,
									'bpjs_tk' 			=> $bpjs_tk,
									'hutang' 			=> $hutang,
									'subtotal' 			=> $subtotal,
									'gaji_bersih' 		=> $gaji_bersih
								];
								$rs = $this->db->update("payroll_slip_detail", $data_dtl, "id = '".$data_payslip_detail[0]->id."'");
  							}
  						}

		                

						if($rs){
							$bpjs_history = $data_payslip_detail = $this->db->query("select * from history_bpjs where project_id = ".$rowdata_os->project_id." and periode_gaji_bulan = ".trim($post['penggajian_month'])." and periode_gaji_tahun = '".trim($post['penggajian_year'])."' ")->result();
							if(empty($bpjs_history)){
								$data_bpjs = [
		  							'project_id' 			=> $rowdata_os->project_id,
									'periode_gaji_bulan' 	=> trim($post['penggajian_month']),
									'periode_gaji_tahun' 	=> trim($post['penggajian_year'])
								];
								$this->db->insert("history_bpjs", $data_bpjs);
								$lastIdBpjs = $this->db->insert_id();

								$log_bpjs = [
									'history_bpjs_id'	=> $lastIdBpjs,
									'employee_id' 		=> $emp_id,
									'no_bpjs_kesehatan' => $rowdata_os->no_bpjs,
									'no_bpjs_tk'  		=> $rowdata_os->no_bpjs_ketenagakerjaan,
									'nominal_bpjs_kesehatan'  	=> $bpjs_kesehatan,
									'nominal_bpjs_tk'  	=> $bpjs_tk,
									'tanggal_potong'  	=> date("Y-m-d H:i:s")
								];
								$this->db->insert("history_bpjs_detail", $log_bpjs);

							}else{

								$bpjs_history_detail = $this->db->query("select * from history_bpjs_detail where employee_id = '".$emp_id."' and history_bpjs_id = '".$bpjs_history[0]->id."' ")->result();
								if(!empty($bpjs_history_detail)){
									$log_bpjs = [
										'no_bpjs_kesehatan' => $rowdata_os->no_bpjs,
										'no_bpjs_tk'  		=> $rowdata_os->no_bpjs_ketenagakerjaan,
										'nominal_bpjs_kesehatan'  	=> $bpjs_kesehatan,
										'nominal_bpjs_tk'  	=> $bpjs_tk,
										'tanggal_potong'  	=> date("Y-m-d H:i:s")
									];
									 $this->db->update("history_bpjs_detail", $log_bpjs, "id = '".$bpjs_history_detail[0]->id."'");
								}else{
									$log_bpjs = [
										'history_bpjs_id'	=> $bpjs_history[0]->id,
										'employee_id' 		=> $emp_id,
										'no_bpjs_kesehatan' => $rowdata_os->no_bpjs,
										'no_bpjs_tk'  		=> $rowdata_os->no_bpjs_ketenagakerjaan,
										'nominal_bpjs_kesehatan'  	=> $bpjs_kesehatan,
										'nominal_bpjs_tk'  	=> $bpjs_tk,
										'tanggal_potong'  	=> date("Y-m-d H:i:s")
									];
									$this->db->insert("history_bpjs_detail", $log_bpjs);
								}

							}

							
						}

  					}

  				}
  				return $rs;
  			}

  		}else{
  			echo "Bulan Tahun Penggajian & Periode Absensi harus diisi"; 
  		}

		
	}  

	public function edit_data($post) { 

		if(!empty($post['id'])){
			$this->db->trans_start();

			$getperiod_start 	= date_create($post['period_start']); 
			$getperiod_end 		= date_create($post['period_end']); 
			$period_start 		= date_format($getperiod_start,"Y-m-d");
			$period_end 		= date_format($getperiod_end,"Y-m-d");

	  		if(!empty($post['penggajian_month']) && !empty($post['penggajian_year']) && !empty($period_start) && !empty($period_end)){ 
	  			
	  			$data = [
					'bulan_penggajian' 	=> trim($post['penggajian_month']),
					'tahun_penggajian' 	=> trim($post['penggajian_year']),
					'tgl_start_absen' 	=> $period_start,
					'tgl_end_absen' 	=> $period_end
				];
				$rs = $this->db->update("payroll_slip", $data, "id = '".$post['id']."'");

				// ambil project_id sekali saja
				$slip = $this->db->where('id', $post['id'])
				                 ->get('payroll_slip')
				                 ->row();

				$project_id = $slip->project_id;
				$bulan = trim($post['penggajian_month']);
				$tahun = trim($post['penggajian_year']);

				$bpjsHeaderCache = [];


				if(isset($post['hdnempid_gaji'])){
					$item_num = count($post['hdnempid_gaji']); // cek sum
					$item_len_min = min(array_keys($post['hdnempid_gaji'])); // cek min key index
					$item_len = max(array_keys($post['hdnempid_gaji'])); // cek max key index
				} else {
					$item_num = 0;
				}

				if($item_num>0){
					for($i=$item_len_min;$i<=$item_len;$i++) 
					{
						$hdnid = trim($post['hdnid_gaji'][$i]);

						if(!empty($hdnid)){ //update
							if(isset($post['hdnempid_gaji'][$i])){
								$itemData = [
									'tunjangan_jabatan'		=> trim($post['tunj_jabatan_gaji'][$i]),
									'tunjangan_transport' 	=> trim($post['tunj_transport_gaji'][$i]),
									'tunjangan_konsumsi' 	=> trim($post['tunj_konsumsi_gaji'][$i]),
									'tunjangan_komunikasi'	=> trim($post['tunj_komunikasi_gaji'][$i]),
									'seragam' 				=> trim($post['seragam_gaji'][$i]),
									'pelatihan' 			=> trim($post['pelatihan_gaji'][$i]),
									'lain_lain' 			=> trim($post['lainlain_gaji'][$i]),
									'payroll' 				=> trim($post['payroll_gaji'][$i]),
									'pph_120' 				=> trim($post['pph120_gaji'][$i]),
									'total_jam_kerja' 		=> trim($post['jml_jam_kerja_gaji'][$i]),
									'total_masuk' 			=> trim($post['jml_hadir_gaji'][$i]),
									'total_tidak_masuk' 	=> trim($post['jml_tdkhadir_gaji'][$i]),
									'gaji_bulanan' 			=> trim($post['gaji_bulanan_gaji'][$i]),
									'gaji_harian' 			=> trim($post['gaji_harian_gaji'][$i]),
									'gaji' 					=> trim($post['gaji_gaji'][$i]),
									'lembur_perjam' 		=> trim($post['lembur_perjam_gaji'][$i]),
									'total_nominal_lembur' 	=> trim($post['total_nominal_lembur_gaji'][$i]),
									'total_jam_lembur' 		=> trim($post['jam_lembur_gaji'][$i]),
									'total_pendapatan' 		=> trim($post['ttl_pendapatan_gaji'][$i]),
									'bpjs_kesehatan' 		=> trim($post['bpjs_kes_gaji'][$i]),
									'bpjs_tk' 				=> trim($post['bpjs_tk_gaji'][$i]),
									/*'absen' 				=> trim($post['absen_gaji'][$i]),*/
									'hutang' 				=> trim($post['hutang_gaji'][$i]),
									'sosial' 				=> trim($post['sosial_gaji'][$i]),
									'subtotal' 				=> trim($post['subtotal_gaji'][$i]),
									'gaji_bersih' 			=> trim($post['gaji_bersih_gaji'][$i])
									
								];

								$this->db->update("payroll_slip_detail", $itemData, "id = '".$hdnid."'");
							}
						}else{ //insert
							if(isset($post['hdnempid_gaji'][$i])){
								$itemData = [
									'payroll_slip_id'		=> $post['id'],
									'tunjangan_jabatan'		=> trim($post['tunj_jabatan_gaji'][$i]),
									'tunjangan_transport' 	=> trim($post['tunj_transport_gaji'][$i]),
									'tunjangan_konsumsi' 	=> trim($post['tunj_konsumsi_gaji'][$i]),
									'tunjangan_komunikasi'	=> trim($post['tunj_komunikasi_gaji'][$i]),
									'seragam' 				=> trim($post['seragam_gaji'][$i]),
									'pelatihan' 			=> trim($post['pelatihan_gaji'][$i]),
									'lain_lain' 			=> trim($post['lainlain_gaji'][$i]),
									'payroll' 				=> trim($post['payroll_gaji'][$i]),
									'pph_120' 				=> trim($post['pph120_gaji'][$i]),
									'total_jam_kerja' 		=> trim($post['jml_jam_kerja_gaji'][$i]),
									'total_masuk' 			=> trim($post['jml_hadir_gaji'][$i]),
									'total_tidak_masuk' 	=> trim($post['jml_tdkhadir_gaji'][$i]),
									'gaji_bulanan' 			=> trim($post['gaji_bulanan_gaji'][$i]),
									'gaji_harian' 			=> trim($post['gaji_harian_gaji'][$i]),
									'gaji' 					=> trim($post['gaji_gaji'][$i]),
									'lembur_perjam' 		=> trim($post['lembur_perjam_gaji'][$i]),
									'total_nominal_lembur' 	=> trim($post['total_nominal_lembur_gaji'][$i]),
									'total_jam_lembur' 		=> trim($post['jam_lembur_gaji'][$i]),
									'total_pendapatan' 		=> trim($post['ttl_pendapatan_gaji'][$i]),
									'bpjs_kesehatan' 		=> trim($post['bpjs_kes_gaji'][$i]),
									'bpjs_tk' 				=> trim($post['bpjs_tk_gaji'][$i]),
									/*'absen' 				=> trim($post['absen_gaji'][$i]),*/
									'hutang' 				=> trim($post['hutang_gaji'][$i]),
									'sosial' 				=> trim($post['sosial_gaji'][$i]),
									'subtotal' 				=> trim($post['subtotal_gaji'][$i]),
									'gaji_bersih' 			=> trim($post['gaji_bersih_gaji'][$i])
								];

								$this->db->insert('payroll_slip_detail', $itemData);
							}
						}


						$employee_id = trim($post['hdnempid_gaji'][$i]);

						// =======================
						// CEK / BUAT HEADER BPJS
						// =======================

						if (!isset($bpjsHeaderCache[$project_id])) {

						    $bpjs_header = $this->db->where([
						        'project_id'         => $project_id,
						        'periode_gaji_bulan' => $bulan,
						        'periode_gaji_tahun' => $tahun
						    ])->get('history_bpjs')->row();

						    if (!$bpjs_header) {

						        $this->db->insert("history_bpjs", [
						            'project_id'         => $project_id,
						            'periode_gaji_bulan' => $bulan,
						            'periode_gaji_tahun' => $tahun
						        ]);

						        $bpjsHeaderCache[$project_id] = $this->db->insert_id();

						    } else {
						        $bpjsHeaderCache[$project_id] = $bpjs_header->id;
						    }
						}

						$history_bpjs_id = $bpjsHeaderCache[$project_id];


						// =======================
						// UPDATE / INSERT DETAIL
						// =======================

						$bpjs_kesehatan = trim($post['bpjs_kes_gaji'][$i]);
						$bpjs_tk        = trim($post['bpjs_tk_gaji'][$i]);

						$bpjs_detail = $this->db->where([
						    'history_bpjs_id' => $history_bpjs_id,
						    'employee_id'     => $employee_id
						])->get('history_bpjs_detail')->row();

						$data_bpjs_detail = [
						    'history_bpjs_id'        => $history_bpjs_id,
						    'employee_id'            => $employee_id,
						    'nominal_bpjs_kesehatan' => $bpjs_kesehatan,
						    'nominal_bpjs_tk'        => $bpjs_tk,
						    'tanggal_potong'         => date("Y-m-d H:i:s")
						];

						if ($bpjs_detail) {
						    $this->db->update(
						        "history_bpjs_detail",
						        $data_bpjs_detail,
						        ['id' => $bpjs_detail->id]
						    );
						} else {
						    $this->db->insert("history_bpjs_detail", $data_bpjs_detail);
						}

					}
				}
				$this->db->trans_complete();
				if($rs){
					return [
					    "status" => true,
					    "msg" => "Data berhasil disimpan"
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
				    "msg" 	 => "Bulan Tahun Penggajian & Periode Absensi harus diisi"
				];
	  		}
		}else{
			return [
			    "status" => false,
			    "msg" 	 => "ID tidak ditemukan"
			];
		}
	} 


	public function getRowData($id) { 

		
		$mTable = "(select a.*, b.name_indo as month_name, c.project_name 
					from payroll_slip a 
					left join master_month b on b.id = a.bulan_penggajian
					left join project_outsource c on c.id = a.project_id
					
			)dt";

		$rs = $this->db->where([$this->primary_key => $id])->get($mTable)->row();
		
		
		
		return $rs;
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

		$sql = "select a.*, b.name_indo as month_name, c.project_name 
					from payroll_slip a 
					left join master_month b on b.id = a.bulan_penggajian
					left join project_outsource c on c.id = a.project_id
					where 1=1 
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getSummaryAbsen($bln, $thn){ 

		$rs = $this->db->query("select * from  summary_absen_outsource where bulan_penggajian = ".$bln." and tahun_penggajian = '".$thn."' limit 1")->result(); 

		

		return $rs;

	}

	public function getGaji($project, $bln, $thn){ 

		$rs = $this->db->query("select a.* from  payroll_slip a 
				left join employees b on b.id = a.employee_id
				where a.periode_bulan = ".$bln." and a.periode_tahun = '".$thn."' and b.project_id = ".$project." limit 1")->result(); 

		

		return $rs;

	}



	public function getNewGajiOSRow($row,$id=0,$project,$bln,$thn,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getGajiOSRows($id,$project,$bln,$thn,$view);
		} else { 
			$data = '';
			$no = $row+1;

			$data 	.= '<td>No Data</td>';

			
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getGajiOSRows($id,$project,$bln,$thn,$view,$print=FALSE){ 

		$dt = ''; 
		

		$rs = $this->db->query("select a.*, b.project_id, b.full_name, b.gaji_bulanan, b.gaji_harian, b.emp_code, b.id as employee_id, c.bulan_penggajian, c.tahun_penggajian, c.project_id, d.sistem_lembur, d.nominal_lembur, b.tipe_penggajian
			from summary_absen_outsource_detail a left join employees b on b.id = a.emp_id
			left join summary_absen_outsource c on c.id = a.summary_absen_outsource_id
			left join data_customer d on d.id = b.cust_id
			where c.bulan_penggajian = ".$bln." and c.tahun_penggajian = '".$thn."' and c.project_id = ".$project."
			order by b.full_name asc

		")->result();

		
		$rd = $rs;

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;

				$dataSlip = $this->db->query("select a.id as employee_id, a.emp_code, a.full_name, b.*, c.id as payroll_id
				from employees a left join payroll_slip_detail b on b.employee_id = a.id 
				left join payroll_slip c on c.id = b.payroll_slip_id
				where a.emp_source = 'outsource' and a.id = '".$f->emp_id."' and a.status_id = 1 
				and c.bulan_penggajian = ".$bln." and c.tahun_penggajian = '".$thn."' ")->result(); 

				$gaji_bulanan = (int)$f->gaji_bulanan;

				if(!empty($dataSlip)){ /// ambil data slip
					
					///informasi detail bpjs
					$tp_jkk = ceil(($gaji_bulanan * 0.0024) * 100) / 100; /// 0.24% dr GP
					$tp_jkm = ceil(($gaji_bulanan * 0.003) * 100) / 100; /// 0.3% dr GP
					$tp_jht  =  ceil(($gaji_bulanan * 0.0375) * 100) / 100; /// 3.75% dr GP
					$tp_jp  = ceil(($gaji_bulanan * 0.02) * 100) / 100; /// 2% dr GP
					$pgk_jht  = ceil(($gaji_bulanan * 0.02) * 100) / 100; /// 2% dr GP
					$pgk_jp   = ceil(($gaji_bulanan * 0.01) * 100) / 100; /// 1% dr GP
					$tp_jkes  = ceil(($gaji_bulanan * 0.02) * 100) / 100; /// 2% dr GP
					$pgk_jkes   = ceil(($gaji_bulanan * 0.01) * 100) / 100; /// 1% dr GP


					$id = $dataSlip[0]->id;
					$payroll_id = $dataSlip[0]->payroll_id;
					$emp_code = $dataSlip[0]->emp_code;
					$full_name = $dataSlip[0]->full_name;
					$employee_id = $dataSlip[0]->employee_id;
					$total_jam_kerja = $dataSlip[0]->total_jam_kerja;
					$total_masuk = $dataSlip[0]->total_masuk;
					$total_tidak_masuk = $dataSlip[0]->total_tidak_masuk;
					$gaji_bulanan = $dataSlip[0]->gaji_bulanan;
					$gaji_harian = $dataSlip[0]->gaji_harian;
					$gaji = $dataSlip[0]->gaji;
					$tunjangan_jabatan = $dataSlip[0]->tunjangan_jabatan;
					$tunjangan_transport = $dataSlip[0]->tunjangan_transport;
					$tunjangan_konsumsi = $dataSlip[0]->tunjangan_konsumsi;
					$tunjangan_komunikasi = $dataSlip[0]->tunjangan_komunikasi;
					$lembur_perjam = $dataSlip[0]->lembur_perjam;
					$total_nominal_lembur = $dataSlip[0]->total_nominal_lembur;
					$total_jam_lembur = $dataSlip[0]->total_jam_lembur;
					$total_pendapatan = $dataSlip[0]->total_pendapatan;
					$bpjs_kesehatan = $dataSlip[0]->bpjs_kesehatan;
					$bpjs_tk = $dataSlip[0]->bpjs_tk;
					/*$absen = $dataSlip[0]->absen;*/
					$seragam = $dataSlip[0]->seragam;
					$pelatihan = $dataSlip[0]->pelatihan;
					$lain_lain = $dataSlip[0]->lain_lain;
					$hutang = $dataSlip[0]->hutang;
					$sosial = $dataSlip[0]->sosial;
					$payroll = $dataSlip[0]->payroll;
					$pph_120 = $dataSlip[0]->pph_120;
					$subtotal = $dataSlip[0]->subtotal;
					$gaji_bersih = $dataSlip[0]->gaji_bersih;

				}else{ /// ambil data dr summary absen
					
					$total_tidak_masuk = ((int)$f->total_ijin ?? 0) +
									     ((int)$f->total_cuti ?? 0) +
									     ((int)$f->total_alfa ?? 0);

					$gaji = ceil(($f->total_masuk * (int)$f->gaji_harian) * 100) / 100;
					
					if($f->sistem_lembur == 'tidak_sistem_lembur'){
						$lembur_perjam = $f->nominal_lembur ?? 0;
					}else{
						$lembur_perjam = ($gaji_bulanan > 0) ? ceil(($gaji_bulanan / 173) * 100) / 100 : 0;
					}
					

					$bpjs_kesehatan = ceil(($gaji_bulanan * 0.04) * 100) / 100; /// 4% dr GP
					$bpjs_tk = ceil(($gaji_bulanan * 0.0624) * 100) / 100; /// 6.24% dr GP
					
					/*$hari_kerja = (int) ($f->total_hari_kerja ?? 0);

					if ($hari_kerja > 0) {
					    $potongan_absen = ceil(
					        ($total_tidak_masuk * ($gaji_bulanan / $hari_kerja)) * 100
					    ) / 100;
					} else {
					    $potongan_absen = 0;
					}*/



					$sosial = '5000';
					//ambil pinjaman yg masih berjalan
					$data_pinjaman = $this->db->query("select sum(nominal_cicilan_per_bulan) as ttt_hutang from loan where id_employee = '".$f->emp_id."' and status_id = 5")->result();
					$hutang=0;
					if(!empty($data_pinjaman)){
						$hutang = $data_pinjaman[0]->ttt_hutang;
					}

					/// ttl pendapatan - potongan tdk wajib
					//$subtotal = ceil(($gaji - ($potongan_absen+$hutang+$sosial)) * 100) / 100;
					$subtotal = ceil(($gaji - ($hutang+$sosial)) * 100) / 100;

					/// subtotal - potongan wajib
					$gaji_bersih = ceil(($subtotal - ($bpjs_kesehatan+$bpjs_tk)) * 100) / 100;

					///informasi detail bpjs
					$tp_jkk = ceil(($gaji_bulanan * 0.0024) * 100) / 100; /// 0.24% dr GP
					$tp_jkm = ceil(($gaji_bulanan * 0.003) * 100) / 100; /// 0.3% dr GP
					$tp_jht  =  ceil(($gaji_bulanan * 0.0375) * 100) / 100; /// 3.75% dr GP
					$tp_jp  = ceil(($gaji_bulanan * 0.02) * 100) / 100; /// 2% dr GP
					$pgk_jht  = ceil(($gaji_bulanan * 0.02) * 100) / 100; /// 2% dr GP
					$pgk_jp   = ceil(($gaji_bulanan * 0.01) * 100) / 100; /// 1% dr GP
					$tp_jkes  = ceil(($gaji_bulanan * 0.02) * 100) / 100; /// 2% dr GP
					$pgk_jkes   = ceil(($gaji_bulanan * 0.01) * 100) / 100; /// 1% dr GP

		             
					$id = "";
					$payroll_id = "";
					$emp_code = $f->emp_code;
					$full_name = $f->full_name;
					$employee_id = $f->employee_id;
					$total_jam_kerja = $f->total_jam_kerja;
					$total_masuk = $f->total_masuk;
					$total_tidak_masuk = $total_tidak_masuk;
					$gaji_harian = $f->gaji_harian;
					$gaji = $gaji;
					$tunjangan_jabatan = "";
					$tunjangan_transport = "";
					$tunjangan_konsumsi = "";
					$tunjangan_komunikasi = "";
					$lembur_perjam = $lembur_perjam;
					$total_jam_lembur = $f->total_jam_lembur;
					$total_nominal_lembur = ceil($lembur_perjam*$total_jam_lembur);
					$total_pendapatan = $gaji;
					$bpjs_kesehatan = $bpjs_kesehatan;
					$bpjs_tk = $bpjs_tk;
					/*$absen = $potongan_absen;*/
					$seragam = "";
					$pelatihan = "";
					$lain_lain = "";
					$hutang = $hutang;
					$sosial = $sosial;
					$payroll = "";
					$pph_120 = "";
					$subtotal = $subtotal;
					$gaji_bersih = $gaji_bersih;
				}

				
				if(!$view){ 
					

					$dt .= '<tr>';

					
					$dt .= '<td>'.$emp_code.'</td>';
					$dt .= '<td>'.$full_name.'<input type="hidden" id="hdnempid_gaji" name="hdnempid_gaji['.$row.']" value="'.$employee_id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($total_jam_kerja,'jml_jam_kerja_gaji['.$row.']','','jml_jam_kerja_gaji','text-align: right;','data-id="'.$row.'" readonly ').'<input type="hidden" id="hdnid_gaji" name="hdnid_gaji['.$row.']" value="'.$id.'"/></td>';

					$dt .= '<td>'.$this->return_build_txt($total_masuk,'jml_hadir_gaji['.$row.']','','jml_hadir_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($total_tidak_masuk,'jml_tdkhadir_gaji['.$row.']','','jml_tdkhadir_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($gaji_bulanan,'gaji_bulanan_gaji['.$row.']','','gaji_bulanan_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($gaji_harian,'gaji_harian_gaji['.$row.']','','gaji_harian_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($gaji,'gaji_gaji['.$row.']','','gaji_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($tunjangan_jabatan,'tunj_jabatan_gaji['.$row.']','','tunj_jabatan_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setTotalPendapatan(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($tunjangan_transport,'tunj_transport_gaji['.$row.']','','tunj_transport_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setTotalPendapatan(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($tunjangan_konsumsi,'tunj_konsumsi_gaji['.$row.']','','tunj_konsumsi_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setTotalPendapatan(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($tunjangan_komunikasi,'tunj_komunikasi_gaji['.$row.']','','tunj_komunikasi_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setTotalPendapatan(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($lembur_perjam,'lembur_perjam_gaji['.$row.']','','lembur_perjam_gaji','text-align: right;','data-id="'.$row.'" readonly').'</td>';

					$dt .= '<td>'.$this->return_build_txt($total_jam_lembur,'jam_lembur_gaji['.$row.']','','jam_lembur_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($total_nominal_lembur,'total_nominal_lembur_gaji['.$row.']','','total_nominal_lembur_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($total_pendapatan,'ttl_pendapatan_gaji['.$row.']','','ttl_pendapatan_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($bpjs_kesehatan,'bpjs_kes_gaji['.$row.']','','bpjs_kes_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($bpjs_tk,'bpjs_tk_gaji['.$row.']','','bpjs_tk_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					
					$dt .= '<td>'.$this->return_build_txt($tp_jkk,'tp_jkk['.$row.']','','tp_jkk','text-align: right;','data-id="'.$row.'" readonly ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($tp_jkm,'tp_jkm['.$row.']','','tp_jkm','text-align: right;','data-id="'.$row.'" readonly ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($tp_jht,'tp_jht['.$row.']','','tp_jht','text-align: right;','data-id="'.$row.'" readonly ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($tp_jp ,'tp_jp['.$row.']','','tp_jp','text-align: right;','data-id="'.$row.'" readonly ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($pgk_jht,'pgk_jht['.$row.']','','pgk_jht','text-align: right;','data-id="'.$row.'" readonly ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($pgk_jp,'pgk_jp['.$row.']','','pgk_jp','text-align: right;','data-id="'.$row.'" readonly ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($tp_jkes,'tp_jkes['.$row.']','','tp_jkes','text-align: right;','data-id="'.$row.'" readonly ').'</td>';
					$dt .= '<td>'.$this->return_build_txt($pgk_jkes,'pgk_jkes['.$row.']','','pgk_jkes','text-align: right;','data-id="'.$row.'" readonly ').'</td>';


					/*$dt .= '<td>'.$this->return_build_txt($absen,'absen_gaji['.$row.']','','absen_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';*/

					$dt .= '<td>'.$this->return_build_txt($seragam,'seragam_gaji['.$row.']','','seragam_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setSubTotal(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($pelatihan,'pelatihan_gaji['.$row.']','','pelatihan_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setSubTotal(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($lain_lain,'lainlain_gaji['.$row.']','','lainlain_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setSubTotal(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($hutang,'hutang_gaji['.$row.']','','hutang_gaji','text-align: right;','data-id="'.$row.'" readonly').'</td>';

					$dt .= '<td>'.$this->return_build_txt($sosial,'sosial_gaji['.$row.']','','sosial_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($payroll,'payroll_gaji['.$row.']','','payroll_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setGajiBersih(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($pph_120,'pph120_gaji['.$row.']','','pph120_gaji','text-align: right;','data-id="'.$row.'" onkeyup="setGajiBersih(this)" ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($subtotal,'subtotal_gaji['.$row.']','','subtotal_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					$dt .= '<td>'.$this->return_build_txt($gaji_bersih,'gaji_bersih_gaji['.$row.']','','gaji_bersih_gaji','text-align: right;','data-id="'.$row.'" readonly ').'</td>';

					
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
					$print_gaji = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportGaji_perEmployee('."'".$payroll_id."'".','."'".$employee_id."'".')"> <i class="fa fa-download"></i> Gaji</a>';
					$dt .= '<td style="text-align:center !important">'.$print_gaji.'</td>';
					$dt .= '<td>'.$emp_code.'</td>';
					$dt .= '<td>'.$full_name.'</td>';
					$dt .= '<td>'.$total_jam_kerja.'</td>';
					$dt .= '<td>'.$total_masuk.'</td>';
					$dt .= '<td>'.$total_tidak_masuk.'</td>';
					$dt .= '<td>'.$gaji_bulanan.'</td>';
					$dt .= '<td>'.$gaji_harian.'</td>';
					$dt .= '<td>'.$gaji.'</td>';
					$dt .= '<td>'.$tunjangan_jabatan.'</td>';
					$dt .= '<td>'.$tunjangan_transport.'</td>';
					$dt .= '<td>'.$tunjangan_konsumsi.'</td>';
					$dt .= '<td>'.$tunjangan_komunikasi.'</td>';
					$dt .= '<td>'.$lembur_perjam.'</td>';
					$dt .= '<td>'.$total_jam_lembur.'</td>';
					$dt .= '<td>'.$total_nominal_lembur.'</td>';
					$dt .= '<td>'.$total_pendapatan.'</td>';
					$dt .= '<td>'.$bpjs_kesehatan.'</td>';
					$dt .= '<td>'.$bpjs_tk.'</td>';

					$dt .= '<td>'.$tp_jkk.'</td>';
					$dt .= '<td>'.$tp_jkm.'</td>';
					$dt .= '<td>'.$tp_jht.'</td>';
					$dt .= '<td>'.$tp_jp.'</td>';
					$dt .= '<td>'.$pgk_jht.'</td>';
					$dt .= '<td>'.$pgk_jp.'</td>';
					$dt .= '<td>'.$tp_jkes.'</td>';
					$dt .= '<td>'.$pgk_jkes.'</td>';

					// $dt .= '<td>'.$absen.'</td>';
					$dt .= '<td>'.$seragam.'</td>';
					$dt .= '<td>'.$pelatihan.'</td>';
					$dt .= '<td>'.$lain_lain.'</td>';
					$dt .= '<td>'.$hutang.'</td>';
					$dt .= '<td>'.$sosial.'</td>';
					$dt .= '<td>'.$payroll.'</td>';
					$dt .= '<td>'.$pph_120.'</td>';
					$dt .= '<td>'.$subtotal.'</td>';
					$dt .= '<td>'.$gaji_bersih.'</td>';
					$dt .= '</tr>';

					
				}

				$row++;
			}
		}

		return [$dt,$row];
	}


}
