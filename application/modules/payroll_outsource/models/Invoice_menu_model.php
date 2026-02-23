<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_menu_model extends MY_Model
{
	/* Module */
 	protected $folder_name				= "payroll_outsource/invoice_menu";
 	protected $table_name 				= _PREFIX_TABLE."project_invoice";
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
			'dt.invoice_no',
			'dt.invoice_date',
			'dt.po_number',
			'dt.periode_penggajian',
			'dt.project_id'
		];
		
		
		$karyawan_id = $_SESSION['worker'];

		$sIndexColumn = $this->primary_key;

		$where_project = "";
		if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
		$where_project = " and a.project_id = '".$_GET['flproject']."' ";
		}

		$sTable = '(select a.*, b.project_name, concat(d.name_indo," ",c.tahun_penggajian) as periode_penggajian
					from project_invoice a 
					left join project_outsource b on b.id = a.project_id
					left join payroll_slip c on c.id = a.payroll_slip_id
					left join master_month d on d.id = c.bulan_penggajian
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


			$print_invoice = '<a class="btn btn-default btn-xs" style="align:center" onclick="getInvoice('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Invoice
            </a>';

            $print_rincian_biaya = '<a class="btn btn-default btn-xs" style="align:center" onclick="getRincianBiaya('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Rincian Biaya
            </a>';

            $print_berita_acara = '<a class="btn btn-default btn-xs" style="align:center" onclick="getBeritaAcaraPekerjaan('."'".$row->id."'".')">
                <i class="fa fa-download"></i>
                Berita Acara
            </a>';
			


			array_push($output["aaData"],array(
				$delete_bulk,
				'<div class="action-buttons">
					'.$print_invoice.'
					'.$print_rincian_biaya.'
					'.$print_berita_acara.'
					'.$edit.'
					'.$delete.'
				</div>',
				$row->id,
				$row->project_name,
				$row->invoice_no,
				$row->invoice_date,
				$row->po_number,
				$row->periode_penggajian


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


	public function getNextNumber() { 
		
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		

		$cek = $this->db->query("select * from project_invoice where SUBSTRING(invoice_no, 9, 4) = '".$period."'");
		$rs_cek = $cek->result_array();

		if(empty($rs_cek)){
			$num = '00001';
		}else{
			$cek2 = $this->db->query("select max(invoice_no) as maxnum from project_invoice where SUBSTRING(invoice_no, 9, 4) = '".$period."'");
			$rs_cek2 = $cek2->result_array();
			$dt = $rs_cek2[0]['maxnum']; 
			$getnum = substr($dt,13); 
			$num = str_pad($getnum + 1, 5, 0, STR_PAD_LEFT);
			
		}

		return $num;
		
	} 


	public function add_data($post) { 

		$jatuh_tempo 		= trim($post['jatuh_tempo'] ?? '');

		$lettercode = ('INV-MAS'); // ca code
		$yearcode = date("y");
		$monthcode = date("m");
		$period = $yearcode.$monthcode; 
		

		////INV-MAS-2602-01946
		$runningnumber = $this->getNextNumber(); // next count number
		$nextnum 	= $lettercode.'-'.$period.'-'.$runningnumber;

		
  		$data_invoice = $this->db->query("select * from project_invoice where project_id = '".$post['project']."' and payroll_slip_id = '".$post['periode_gaji']."'  ")->result(); 

  		if(empty($data_invoice)){ 
  			$getCust = $this->db->query("select * from project_outsource where id = '".$post['project']."' ")->result(); 


  			$data = [
				'project_id' 		=> $post['project'],
				'invoice_no' 		=> $nextnum,
				'po_number' 		=> trim($post['no_po']),
				'jatuh_tempo' 		=> date("Y-m-d", strtotime($jatuh_tempo)),
				'terms' 			=> $getCust[0]->term_payment,
				'management_fee' 	=> $getCust[0]->management_fee,
				'payroll_slip_id' 	=> trim($post['periode_gaji']),
				'invoice_date'		=> date("Y-m-d H:i:s")
			];
			$rs = $this->db->insert($this->table_name, $data);
			$lastId = $this->db->insert_id();

			if($rs){
				$getBiaya = $this->db->query("select b.job_title_id, count(a.employee_id) as jumlah_personil, sum(a.gaji) as total_gaji, sum(a.tunjangan_jabatan) as total_tunjangan_jabatan,sum(a.tunjangan_transport) as total_tunjangan_transport, sum(a.tunjangan_konsumsi) as total_tunjangan_konsumsi
					,sum(a.tunjangan_komunikasi) as total_tunjangan_komunikasi, sum(a.bpjs_kesehatan) as total_bpjs_kesehatan
					,sum(a.bpjs_tk) as total_bpjs_tk, sum(a.seragam) as total_seragam, sum(a.pelatihan) as total_pelatihan
					,sum(a.lain_lain) as total_lain_lain, sum(a.hutang) as total_hutang, sum(a.sosial) as total_sosial
					,sum(a.payroll) as total_payroll, sum(a.pph_120) as total_pph_120, sum(a.gaji_bersih) as total_gaji_bersih
					,sum(a.total_masuk) as total_masuk
					from payroll_slip_detail a 
					left join employees b on b.id = a.employee_id
					where a.payroll_slip_id = ".$post['periode_gaji']."
					group by b.job_title_id")->result(); 

				if(!empty($getBiaya)){
					foreach($getBiaya as $row){
						$nominal_management_fee = ceil($row->total_gaji*($getCust[0]->management_fee/100));
						$jumlah_harga_jual = $row->total_gaji+$nominal_management_fee;
						$ppn_percen = 10;
						$ppn_nominal = ceil($jumlah_harga_jual*($ppn_percen/100));
						$jumlah_sesudah_pajak = $jumlah_harga_jual+$ppn_nominal;

						$data_dtl = [
							'project_invoice_id' 	=> $lastId,
							'job_title_id' 			=> $row->job_title_id,
							'jumlah_personil' 		=> $row->jumlah_personil,
							'jumlah_biaya' 			=> $row->total_gaji,
							'nominal_management_fee' => $nominal_management_fee,
							'jumlah_harga_jual' 	=> $jumlah_harga_jual,
							'ppn_percen'			=> $ppn_percen,
							'ppn_nominal'			=> $ppn_nominal,
							'jumlah_sesudah_pajak' 	=> $jumlah_sesudah_pajak,
							'total_gaji' 			=> $row->total_gaji,
							'total_tunjangan_jabatan' 		=> $row->total_tunjangan_jabatan,
							'total_tunjangan_transport' 	=> $row->total_tunjangan_transport,
							'total_tunjangan_konsumsi' 		=> $row->total_tunjangan_konsumsi,
							'total_tunjangan_komunikasi' 	=> $row->total_tunjangan_komunikasi,
							'total_bpjs_kesehatan' 	=> $row->total_bpjs_kesehatan,
							'total_bpjs_tk' 		=> $row->total_bpjs_tk,
							'total_seragam' 		=> $row->total_seragam,
							'total_pelatihan' 		=> $row->total_pelatihan,
							'total_lain_lain' 		=> $row->total_lain_lain,
							'total_hutang' 			=> $row->total_hutang,
							'total_sosial' 			=> $row->total_sosial,
							'total_payroll' 		=> $row->total_payroll,
							'total_pph_120' 		=> $row->total_pph_120,
							'total_gaji_bersih' 	=> $row->total_gaji_bersih,
							'total_masuk' 			=> $row->total_masuk
						];
						$this->db->insert("project_invoice_detail", $data_dtl);
					}
				}

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
			    "msg" 	 => "Data gagal disimpan. Data Invoice sudah ada"
			];
  		}

		
	}  

	public function edit_data($post) { 
		
	}  

	public function getRowData($id) { 
		$mTable = '(SELECT a.*, b.full_name as employee_name FROM time_attendances a left join employees b on b.id = a.employee_id
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
				'date_attendance' 	=> $v["B"],
				'employee_id' 		=> $v["C"]
				
			];

			$rs = $this->db->insert($this->table_name, $data);
			if (!$rs) $error .=",baris ". $v["A"];
		}

		return $error;
	}

	public function eksport_data()
	{ 
		
		$where_project = "";
		if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
			$where_project = " and a.project_id = '".$_GET['flproject']."' ";
		}
		
		$sql = 'select a.*, b.project_name, concat(d.name_indo," ",c.tahun_penggajian) as periode_penggajian
				from project_invoice a 
				left join project_outsource b on b.id = a.project_id
				left join payroll_slip c on c.id = a.payroll_slip_id
				left join master_month d on d.id = c.bulan_penggajian
				where 1=1 '.$where_project.'
		';

		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	}


	public function getDataPeriodeGaji($project){ 

		$rs = $this->db->query("select a.*, concat(b.name_indo,' ',a.tahun_penggajian) as periode_penggajian
								from payroll_slip a
								left join master_month b on b.id = a.bulan_penggajian
								where project_id = ".$project." ")->result(); 

		$data['msperiode'] = $rs;


		return $data;

	}


	public function getDataPayroll($payroll_id){ 

		$rs = $this->db->query("select * from payroll_slip
								where id = ".$payroll_id." ")->result(); 

		


		return $rs;

	}

	

}