<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_gaji_int_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "payroll_internal/hitung_gaji_int_menu";
 	protected $table_name 				= _PREFIX_TABLE."payroll_slip_internal";
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
			'dt.month_name',
			'dt.tahun_penggajian',
			'dt.status_payroll',
			'dt.status'
		];

		/*$where_project = "";
			if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
			$where_project = " and a.project_id = '".$_GET['flproject']."' ";
		}*/

		

		$sIndexColumn = $this->primary_key;
		


		$sTable = '(select a.*, b.name_indo as month_name, c.name as status_payroll
					from payroll_slip_internal a 
					left join master_month b on b.id = a.bulan_penggajian
					left join master_payroll_status c on c.id = a.status 
					where 1=1 
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

			
			$print_gaji="";
			if($row->status == 2){ //terbayar
				$print_gaji = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportGaji('."'".$row->id."'".')">
	                <i class="fa fa-download"></i>
	                Slip Gaji
	            </a>';
			}
			
			
            /*$print_lembur = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportLembur('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Lembur
            </a>';*/
            
            $print_absen = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportAbsen_gaji('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Rekap Absen
            </a>';
            $print_rekap_gaji = "";
            if($row->status == 2){ //terbayar
	            $print_rekap_gaji = '<a class="btn btn-default btn-xs" style="align:center" onclick="getRekapGaji('."'".$row->id."'".')">
	                <i class="fa fa-download"></i>
	                Rekap Gaji
	            </a>';
	        }


			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
				'.$print_gaji.'
				'.$print_absen.'
				'.$print_rekap_gaji.'
				'.$detail.'
				'.$edit.'
				'.$delete.'
				</div>',
				$row->id,
				$row->month_name,
				$row->tahun_penggajian,
				$row->status_payroll
				
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

	private function normalizeSalaryComponentName($name)
	{
		return strtolower(preg_replace('/[^a-z0-9]/i', '', (string)$name));
	}

	private function getBenefitDeductionComponentColumn()
	{
		if($this->db->field_exists('salary_components_id', 'employee_benefit_deduction')){
			return 'salary_components_id';
		}

		if($this->db->field_exists('salary_component_id', 'employee_benefit_deduction')){
			return 'salary_component_id';
		}

		if($this->db->field_exists('component_id', 'employee_benefit_deduction')){
			return 'component_id';
		}

		return 'salary_components_id';
	}

	private function getEmployeeBenefitDeductionAmounts($employee_ids)
	{
		$result = [];
		if(empty($employee_ids) || !$this->db->table_exists('employee_benefit_deduction')){
			return $result;
		}

		$employee_ids = array_values(array_unique(array_filter(array_map('intval', (array)$employee_ids))));
		if(empty($employee_ids)){
			return $result;
		}

		$componentColumn = $this->getBenefitDeductionComponentColumn();
		$amountColumn = $this->db->field_exists('amount', 'employee_benefit_deduction') ? 'amount' : '';
		if($amountColumn == ''){
			return $result;
		}

		$fieldMap = [
			'gajibulanan' => 'gaji_bulanan',
			'gajiharian' => 'gaji_harian',
			'tunjjabatan' => 'tunjangan_jabatan',
			'tunjtransport' => 'tunjangan_transport',
			'tunjtransportasi' => 'tunjangan_transport',
			'tunjkonsumsi' => 'tunjangan_konsumsi',
			'tunjkomunikasi' => 'tunjangan_komunikasi',
			'bpjskesehatan' => 'bpjs_kesehatan',
			'bpjstk' => 'bpjs_tk',
			'seragam' => 'seragam',
			'pelatihan' => 'pelatihan',
			'lainlain' => 'lain_lain',
			'sosial' => 'sosial',
			'payroll' => 'payroll',
			'pph120' => 'pph_120'
		];

		$rows = $this->db->query("select a.employee_id, a.".$amountColumn." as amount, b.name
					from employee_benefit_deduction a
					left join salary_components b on b.id = a.".$componentColumn."
					where a.employee_id in (".implode(',', $employee_ids).")")->result();

		foreach($rows as $row){
			$key = $this->normalizeSalaryComponentName($row->name);
			if(!isset($fieldMap[$key])){
				continue;
			}

			$result[$row->employee_id][$fieldMap[$key]] = $row->amount;
		}

		return $result;
	}

	private function benefitValue($benefit, $field, $fallback = 0)
	{
		if(isset($benefit[$field]) && $benefit[$field] !== ''){
			return $benefit[$field];
		}

		return $fallback;
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

	

	public function add_data($post)
	{
	    $this->load->helper('global');

	    if (empty($post['penggajian_month']) || empty($post['penggajian_year'])) {
	        return [
	            "status" => false,
	            "msg"    => "Bulan Tahun Penggajian harus diisi"
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

	    if (!$codemonth) {
	        $this->db->trans_complete();
	        return [
	            "status" => false,
	            "msg"    => "Bulan Penggajian tidak valid"
	        ];
	    }

	    $periode_gaji = $tahun . '-' . $codemonth->code;

	    // =========================
	    // MAIN QUERY
	    // =========================
	    $this->db->select("
	        e.id as employee_id,
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

	        COALESCE(l.ttl_hutang,0) as hutang
	    ");

	    $this->db->from('employees e');
	    $this->db->join('summary_absen_internal_detail sd', 'sd.emp_id = e.id', 'left');
	    $this->db->join('summary_absen_internal s', 's.id = sd.summary_absen_internal_id', 'left');

	    $this->db->join("(SELECT b.id_employee,
	                        SUM(b.nominal_cicilan_per_bulan) as ttl_hutang
	                     FROM loan_detail a
	                     JOIN loan b ON b.id = a.loan_id
	                     WHERE DATE_FORMAT(a.tgl_jatuh_tempo,'%Y-%m') = '$periode_gaji'
	                     GROUP BY b.id_employee) l",
	                     "l.id_employee = e.id",
	                     "left");

	    $this->db->where('e.emp_source', 'internal');
	   	$this->db->where('IFNULL(e.is_special_payroll,0) != 1', null, false);
	    $this->db->where('e.status_id', 1);
	    $this->db->where('s.bulan_penggajian', $bulan);
	    $this->db->where('s.tahun_penggajian', $tahun);

	    if ($post['is_all_employee'] == 'Karyawan' && !empty($post['employeeIds'])) {
	        $this->db->where_in('e.id', $post['employeeIds']);
	    }

	    $data = $this->db->get()->result();

	    if (empty($data)) {
	        $this->db->trans_complete();
	        return [
	            "status" => false,
	            "msg"    => "Data tidak ditemukan"
	        ];
	    }

	    $benefitAmounts = $this->getEmployeeBenefitDeductionAmounts(array_map(function ($row) {
	        return $row->employee_id;
	    }, $data));

	    // =========================
	    // BUAT 1 HEADER PAYROLL SAJA
	    // =========================
	    $header = $this->db->where([
	        'bulan_penggajian' => $bulan,
	        'tahun_penggajian' => $tahun
	    ])->get('payroll_slip_internal')->row();

	    if (!$header) {
	        $this->db->insert('payroll_slip_internal', [
	            'bulan_penggajian' => $bulan,
	            'tahun_penggajian' => $tahun,
	            'tgl_start_absen'  => $data[0]->tgl_start_absen,
	            'tgl_end_absen'    => $data[0]->tgl_end_absen,
	            'status' => 1 ///Menunggu Pembayaran
	        ]);
	        $payroll_slip_id = $this->db->insert_id();
	    } else {
	        $payroll_slip_id = $header->id;
	    }

	    $employeeIds = array_map(function ($row) {
	        return (int) $row->employee_id;
	    }, $data);

	    if (!empty($employeeIds)) {
	        $this->db
	            ->where('payroll_slip_id', $payroll_slip_id)
	            ->where_in('employee_id', $employeeIds)
	            ->delete('payroll_slip_detail_internal');
	    }

	    // =========================
	    // BUAT 1 HEADER BPJS SAJA
	    // =========================
	    $bpjs_header = $this->db->where([
	        'periode_gaji_bulan' => $bulan,
	        'periode_gaji_tahun' => $tahun
	    ])->get('history_bpjs_internal')->row();

	    if (!$bpjs_header) {
	        $this->db->insert("history_bpjs_internal", [
	            'periode_gaji_bulan' => $bulan,
	            'periode_gaji_tahun' => $tahun
	        ]);
	        $history_bpjs_id = $this->db->insert_id();
	    } else {
	        $history_bpjs_id = $bpjs_header->id;
	    }

	    $insertDetail = [];

	    foreach ($data as $row) {

	    	$benefit = isset($benefitAmounts[$row->employee_id]) ? $benefitAmounts[$row->employee_id] : [];

	        $gaji_bulanan = (float)$this->benefitValue($benefit, 'gaji_bulanan', $row->gaji_bulanan);
	        $gaji_harian  = (float)$this->benefitValue($benefit, 'gaji_harian', $row->gaji_harian);
	        $tunjangan_jabatan = (float)$this->benefitValue($benefit, 'tunjangan_jabatan', 0);
	        $tunjangan_transport = (float)$this->benefitValue($benefit, 'tunjangan_transport', 0);
	        $tunjangan_konsumsi = (float)$this->benefitValue($benefit, 'tunjangan_konsumsi', 0);
	        $tunjangan_komunikasi = (float)$this->benefitValue($benefit, 'tunjangan_komunikasi', 0);

	        $total_tidak_masuk =
	            (int)$row->total_ijin +
	            (int)$row->total_cuti +
	            (int)$row->total_alfa;

	        $gaji = ceil($row->total_masuk * $gaji_harian);

	        $lembur_perjam  = ceil($gaji_bulanan / 173);
	        $lembur_total   = $row->total_lembur;

	        $bpjs_kesehatan = (float)$this->benefitValue($benefit, 'bpjs_kesehatan', ceil($gaji_bulanan * 0.04));
	        $bpjs_tk        = (float)$this->benefitValue($benefit, 'bpjs_tk', ceil($gaji_bulanan * 0.0624));
	        $seragam = (float)$this->benefitValue($benefit, 'seragam', 0);
	        $pelatihan = (float)$this->benefitValue($benefit, 'pelatihan', 0);
	        $lain_lain = (float)$this->benefitValue($benefit, 'lain_lain', 0);
	        $payroll = (float)$this->benefitValue($benefit, 'payroll', 0);
	        $pph_120 = (float)$this->benefitValue($benefit, 'pph_120', 0);

	        $sosial = (float)$this->benefitValue($benefit, 'sosial', 5000);
	        $hutang = (float)$row->hutang;

	        $total_pendapatan = ceil($gaji + $lembur_total + $tunjangan_jabatan + $tunjangan_transport + $tunjangan_konsumsi + $tunjangan_komunikasi);
	        $subtotal         = ceil($total_pendapatan - ($seragam + $pelatihan + $lain_lain + $hutang + $sosial));
	        $gaji_bersih      = ceil($subtotal - ($bpjs_kesehatan + $bpjs_tk + $payroll + $pph_120));

	        // =========================
	        // INSERT / UPDATE BPJS DETAIL
	        // =========================
	        $bpjs_detail = $this->db->where([
	            'history_bpjs_id' => $history_bpjs_id,
	            'employee_id'     => $row->employee_id
	        ])->get('history_bpjs_detail_internal')->row();

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
	            $this->db->update("history_bpjs_detail_internal", $data_bpjs_detail, ['id' => $bpjs_detail->id]);
	        } else {
	            $this->db->insert("history_bpjs_detail_internal", $data_bpjs_detail);
	        }

	        // =========================
	        // INSERT DETAIL PAYROLL
	        // =========================
	        $insertDetail[] = [
	            'payroll_slip_id'  => $payroll_slip_id,
	            'employee_id'      => $row->employee_id,
	            'total_hari_kerja' => $row->total_hari_kerja,
	            'total_masuk'      => $row->total_masuk,
	            'total_tidak_masuk'=> $total_tidak_masuk,
	            'total_ijin' 	   => $row->total_ijin,
	            'total_cuti' 	   => $row->total_cuti,
	            'total_alfa' 	   => $row->total_alfa,
	            'total_jam_kerja'  => $row->total_jam_kerja,
	            'total_jam_lembur' => $row->total_jam_lembur,
	            'created_at'       => date("Y-m-d H:i:s"),
	            'created_by'       => $_SESSION['worker'],
	            'gaji_bulanan'     => $gaji_bulanan,
	            'gaji_harian'      => $gaji_harian,
	            'gaji'             => $gaji,
	            'tunjangan_jabatan' => $tunjangan_jabatan,
	            'tunjangan_transport' => $tunjangan_transport,
	            'tunjangan_konsumsi' => $tunjangan_konsumsi,
	            'tunjangan_komunikasi' => $tunjangan_komunikasi,
	            'lembur_perjam'    => $lembur_perjam,
	            'total_nominal_lembur' => $lembur_total,
	            'total_pendapatan' => $total_pendapatan,
	            'sosial'           => $sosial,
	            'bpjs_kesehatan'   => $bpjs_kesehatan,
	            'bpjs_tk'          => $bpjs_tk,
	            'seragam'          => $seragam,
	            'pelatihan'        => $pelatihan,
	            'lain_lain'        => $lain_lain,
	            'hutang'           => $hutang,
	            'payroll'          => $payroll,
	            'pph_120'          => $pph_120,
	            'subtotal'         => $subtotal,
	            'gaji_bersih'      => $gaji_bersih
	        ];
	    }

	    if (!empty($insertDetail)) {
	        $this->db->insert_batch('payroll_slip_detail_internal', $insertDetail);
	    }

	    $this->db->trans_complete();

	    return [
	        "status" => $this->db->trans_status(),
	        "msg"    => $this->db->trans_status() ? "Data berhasil disimpan" : "Data gagal disimpan"
	    ];
	}



	
	public function edit_data($post)
	{
	    if (empty($post['id'])) {
	        return [
	            "status" => false,
	            "msg"    => "ID tidak ditemukan"
	        ];
	    }

	    $this->db->trans_start();

	    $getperiod_start = date_create($post['period_start']);
	    $getperiod_end   = date_create($post['period_end']);

	    $period_start = date_format($getperiod_start, "Y-m-d");
	    $period_end   = date_format($getperiod_end, "Y-m-d");

	    if (
	        empty($post['penggajian_month']) ||
	        empty($post['penggajian_year']) ||
	        empty($period_start) ||
	        empty($period_end)
	    ) {
	        return [
	            "status" => false,
	            "msg"    => "Bulan Tahun Penggajian & Periode Absensi harus diisi"
	        ];
	    }

	    $bulan = trim($post['penggajian_month']);
	    $tahun = trim($post['penggajian_year']);

	    // =========================
	    // UPDATE HEADER PAYROLL
	    // =========================
	    $dataHeader = [
	        'bulan_penggajian' => $bulan,
	        'tahun_penggajian' => $tahun,
	        'tgl_start_absen'  => $period_start,
	        'tgl_end_absen'    => $period_end
	    ];

	    $this->db->update("payroll_slip_internal", $dataHeader, ['id' => $post['id']]);

	    // =========================
	    // CEK / BUAT HEADER BPJS (NO PROJECT)
	    // =========================
	    $bpjs_header = $this->db->where([
	        'periode_gaji_bulan' => $bulan,
	        'periode_gaji_tahun' => $tahun
	    ])->get('history_bpjs_internal')->row();

	    if (!$bpjs_header) {
	        $this->db->insert("history_bpjs_internal", [
	            'periode_gaji_bulan' => $bulan,
	            'periode_gaji_tahun' => $tahun
	        ]);
	        $history_bpjs_id = $this->db->insert_id();
	    } else {
	        $history_bpjs_id = $bpjs_header->id;
	    }

	    // =========================
	    // LOOP DETAIL
	    // =========================
	    if (isset($post['hdnempid_gaji'])) {

	        $item_len_min = min(array_keys($post['hdnempid_gaji']));
	        $item_len     = max(array_keys($post['hdnempid_gaji']));

	        for ($i = $item_len_min; $i <= $item_len; $i++) {

	            $hdnid       = trim($post['hdnid_gaji'][$i] ?? '');
	            $employee_id = trim($post['hdnempid_gaji'][$i] ?? '');

	            if (empty($employee_id)) continue;

	            $itemData = [
	                'tunjangan_jabatan'     => trim($post['tunj_jabatan_gaji'][$i] ?? 0),
	                'tunjangan_transport'   => trim($post['tunj_transport_gaji'][$i] ?? 0),
	                'tunjangan_konsumsi'    => trim($post['tunj_konsumsi_gaji'][$i] ?? 0),
	                'tunjangan_komunikasi'  => trim($post['tunj_komunikasi_gaji'][$i] ?? 0),
	                'seragam'               => trim($post['seragam_gaji'][$i] ?? 0),
	                'pelatihan'             => trim($post['pelatihan_gaji'][$i] ?? 0),
	                'lain_lain'             => trim($post['lainlain_gaji'][$i] ?? 0),
	                'payroll'               => trim($post['payroll_gaji'][$i] ?? 0),
	                'pph_120'               => trim($post['pph120_gaji'][$i] ?? 0),
	                'total_jam_kerja'       => trim($post['jml_jam_kerja_gaji'][$i] ?? 0),
	                'total_masuk'           => trim($post['jml_hadir_gaji'][$i] ?? 0),
	                'total_tidak_masuk'     => trim($post['jml_tdkhadir_gaji'][$i] ?? 0),
	                'gaji_bulanan'          => trim($post['gaji_bulanan_gaji'][$i] ?? 0),
	                'gaji_harian'           => trim($post['gaji_harian_gaji'][$i] ?? 0),
	                'gaji'                  => trim($post['gaji_gaji'][$i] ?? 0),
	                'lembur_perjam'         => trim($post['lembur_perjam_gaji'][$i] ?? 0),
	                'total_nominal_lembur'  => trim($post['total_nominal_lembur_gaji'][$i] ?? 0),
	                'total_jam_lembur'      => trim($post['jam_lembur_gaji'][$i] ?? 0),
	                'total_pendapatan'      => trim($post['ttl_pendapatan_gaji'][$i] ?? 0),
	                'bpjs_kesehatan'        => trim($post['bpjs_kes_gaji'][$i] ?? 0),
	                'bpjs_tk'               => trim($post['bpjs_tk_gaji'][$i] ?? 0),
	                'hutang'                => trim($post['hutang_gaji'][$i] ?? 0),
	                'sosial'                => trim($post['sosial_gaji'][$i] ?? 0),
	                'subtotal'              => trim($post['subtotal_gaji'][$i] ?? 0),
	                'gaji_bersih'           => trim($post['gaji_bersih_gaji'][$i] ?? 0)
	            ];

	            if (!empty($hdnid)) {
	                $this->db->update("payroll_slip_detail_internal", $itemData, ['id' => $hdnid]);
	            } else {
	                $itemData['payroll_slip_id'] = $post['id'];
	                $itemData['employee_id']     = $employee_id;
	                $this->db->insert('payroll_slip_detail_internal', $itemData);
	            }

	            // =========================
	            // UPDATE / INSERT BPJS DETAIL
	            // =========================
	            $bpjs_detail = $this->db->where([
	                'history_bpjs_id' => $history_bpjs_id,
	                'employee_id'     => $employee_id
	            ])->get('history_bpjs_detail_internal')->row();

	            $data_bpjs_detail = [
	                'history_bpjs_id'        => $history_bpjs_id,
	                'employee_id'            => $employee_id,
	                'nominal_bpjs_kesehatan' => $itemData['bpjs_kesehatan'],
	                'nominal_bpjs_tk'        => $itemData['bpjs_tk'],
	                'tanggal_potong'         => date("Y-m-d H:i:s")
	            ];

	            if ($bpjs_detail) {
	                $this->db->update("history_bpjs_detail_internal", $data_bpjs_detail, ['id' => $bpjs_detail->id]);
	            } else {
	                $this->db->insert("history_bpjs_detail_internal", $data_bpjs_detail);
	            }
	        }
	    }

	    $this->db->trans_complete();

	    return [
	        "status" => $this->db->trans_status(),
	        "msg"    => $this->db->trans_status() ? "Data berhasil disimpan" : "Data gagal disimpan"
	    ];
	} 


	public function getRowData($id) { 

		
		$mTable = "(select a.*, b.name_indo as month_name
					from payroll_slip_internal a 
					left join master_month b on b.id = a.bulan_penggajian
					
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

		$sql = "select a.*, b.name_indo as month_name
					from payroll_slip_internal a 
					left join master_month b on b.id = a.bulan_penggajian
					where 1=1 
		";

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getSummaryAbsen($bln, $thn){ 

		$rs = $this->db->query("select * from  summary_absen_internal where bulan_penggajian = ".$bln." and tahun_penggajian = '".$thn."' limit 1")->result(); 

		

		return $rs;

	}

	public function getGaji($bln, $thn){ 

		$rs = $this->db->query("select a.* from payroll_slip_internal a
				where a.bulan_penggajian = ".$bln." and a.tahun_penggajian = '".$thn."' limit 1")->result(); 

		

		return $rs;

	}



	public function getNewGajiRow($row,$id=0,$bln,$thn,$view=FALSE)
	{ 
		if($id > 0){ 
			$data = $this->getGajiRows($id,$bln,$thn,$view);
		} else { 
			$data = '';
			$no = $row+1;

			$data 	.= '<td>No Data</td>';

			
		}

		return $data;
	} 
	
	// Generate expenses item rows for edit & view
	public function getGajiRows($id,$bln,$thn,$view,$print=FALSE){ 

		$dt = ''; 
		

		$rs = $this->db->query("select a.*, b.full_name, b.gaji_bulanan, b.gaji_harian, b.emp_code, b.id as employee_id, c.bulan_penggajian, c.tahun_penggajian
			from summary_absen_internal_detail a left join employees b on b.id = a.emp_id
			left join summary_absen_internal c on c.id = a.summary_absen_internal_id
			where b.emp_source = 'internal' and IFNULL(b.is_special_payroll,0) != 1 and c.bulan_penggajian = ".$bln." and c.tahun_penggajian = '".$thn."' 
			order by b.full_name asc

		")->result();

		
		$rd = $rs;
		$benefitAmounts = $this->getEmployeeBenefitDeductionAmounts(array_map(function ($row) {
			return $row->employee_id;
		}, $rd));

		$row = 0; 
		if(!empty($rd)){ 
			$rs_num = count($rd); 
			
			foreach ($rd as $f){
				$no = $row+1;
				$benefit = isset($benefitAmounts[$f->employee_id]) ? $benefitAmounts[$f->employee_id] : [];

				$dataSlip = $this->db->query("select a.id as employee_id, a.emp_code, a.full_name, b.*, c.id as payroll_id, c.status as status_payroll
				from employees a left join payroll_slip_detail_internal b on b.employee_id = a.id 
				left join payroll_slip_internal c on c.id = b.payroll_slip_id
				where a.emp_source = 'internal' and IFNULL(a.is_special_payroll,0) != 1 and a.id = '".$f->emp_id."' and a.status_id = 1 
				and c.bulan_penggajian = ".$bln." and c.tahun_penggajian = '".$thn."' ")->result(); 

				$gaji_bulanan = (float)$this->benefitValue($benefit, 'gaji_bulanan', $f->gaji_bulanan);
				$gaji_harian_benefit = (float)$this->benefitValue($benefit, 'gaji_harian', $f->gaji_harian);

				if(!empty($dataSlip)){ /// ambil data slip
					$status_payroll = $dataSlip[0]->status_payroll;
					
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

				}else{  /// ambil data dr summary absen
					$status_payroll="";
					
					$total_tidak_masuk = ((int)$f->total_ijin ?? 0) +
									     ((int)$f->total_cuti ?? 0) +
									     ((int)$f->total_alfa ?? 0);

					$gaji = ceil(((int)$f->total_masuk * (float)$gaji_harian_benefit) * 100) / 100;
					
					$lembur_perjam = ($gaji_bulanan > 0) ? ceil(($gaji_bulanan / 173) * 100) / 100 : 0;
					
					$bpjs_kesehatan = (float)$this->benefitValue($benefit, 'bpjs_kesehatan', ceil(($gaji_bulanan * 0.04) * 100) / 100); /// 4% dr GP
					$bpjs_tk = (float)$this->benefitValue($benefit, 'bpjs_tk', ceil(($gaji_bulanan * 0.0624) * 100) / 100); /// 6.24% dr GP
					$tunjangan_jabatan = (float)$this->benefitValue($benefit, 'tunjangan_jabatan', 0);
					$tunjangan_transport = (float)$this->benefitValue($benefit, 'tunjangan_transport', 0);
					$tunjangan_konsumsi = (float)$this->benefitValue($benefit, 'tunjangan_konsumsi', 0);
					$tunjangan_komunikasi = (float)$this->benefitValue($benefit, 'tunjangan_komunikasi', 0);
					$seragam = (float)$this->benefitValue($benefit, 'seragam', 0);
					$pelatihan = (float)$this->benefitValue($benefit, 'pelatihan', 0);
					$lain_lain = (float)$this->benefitValue($benefit, 'lain_lain', 0);
					$payroll = (float)$this->benefitValue($benefit, 'payroll', 0);
					$pph_120 = (float)$this->benefitValue($benefit, 'pph_120', 0);
					
					

					$sosial = (float)$this->benefitValue($benefit, 'sosial', 5000);
					//ambil pinjaman yg masih berjalan
					$data_pinjaman = $this->db->query("select sum(nominal_cicilan_per_bulan) as ttt_hutang from loan where id_employee = '".$f->emp_id."' and status_id = 5")->result();
					$hutang=0;
					if(!empty($data_pinjaman)){
						$hutang = $data_pinjaman[0]->ttt_hutang;
					}

					/// ttl pendapatan - potongan tdk wajib
					//$subtotal = ceil(($gaji - ($potongan_absen+$hutang+$sosial)) * 100) / 100;
					$total_nominal_lembur = ceil((int)$lembur_perjam*(int)$f->total_jam_lembur);
					$total_pendapatan = ceil(($gaji + $total_nominal_lembur + $tunjangan_jabatan + $tunjangan_transport + $tunjangan_konsumsi + $tunjangan_komunikasi) * 100) / 100;
					$subtotal = ceil(($total_pendapatan - ($seragam+$pelatihan+$lain_lain+$hutang+$sosial)) * 100) / 100;

					/// subtotal - potongan wajib
					$gaji_bersih = ceil(($subtotal - ($bpjs_kesehatan+$bpjs_tk+$payroll+$pph_120)) * 100) / 100;

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
					$gaji_bulanan = $gaji_bulanan;
					$gaji_harian = $gaji_harian_benefit;
					$gaji = $gaji;
					$tunjangan_jabatan = $tunjangan_jabatan;
					$tunjangan_transport = $tunjangan_transport;
					$tunjangan_konsumsi = $tunjangan_konsumsi;
					$tunjangan_komunikasi = $tunjangan_komunikasi;
					$lembur_perjam = $lembur_perjam;
					$total_jam_lembur = $f->total_jam_lembur;
					$total_nominal_lembur = $total_nominal_lembur;
					$total_pendapatan = $total_pendapatan;
					$bpjs_kesehatan = $bpjs_kesehatan;
					$bpjs_tk = $bpjs_tk;
					/*$absen = $potongan_absen;*/
					$seragam = $seragam;
					$pelatihan = $pelatihan;
					$lain_lain = $lain_lain;
					$hutang = $hutang;
					$sosial = $sosial;
					$payroll = $payroll;
					$pph_120 = $pph_120;
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

					$print_gaji ="";
					if($status_payroll == 2){
						$print_gaji = '<a class="btn btn-default btn-xs" style="align:center" onclick="getReportGaji_perEmployee('."'".$payroll_id."'".','."'".$employee_id."'".')"> <i class="fa fa-download"></i> Gaji</a>';

						$dt .= '<td style="text-align:center !important">'.$print_gaji.'</td>';
					}else{
						$dt .= '<td style="text-align:center !important">-</td>';
					}
					
					
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
