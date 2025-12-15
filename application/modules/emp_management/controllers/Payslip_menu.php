<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payslip_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "payslip_menu"; // identify menu
 	const  LABELMASTER				= "Menu Slip Gaji Karyawan";
 	const  LABELFOLDER				= "emp_management"; // module folder
 	const  LABELPATH				= "payslip_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "emp_management"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Employee Name","Period","File"];

	
	/* Export */
	public $colnames 				= ["ID","Employee Name","Period","File"];
	public $colfields 				= ["id","employee_name","task","parent_name"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		$whr='';
		if($getdata[0]->id_groups != 1){ //bukan super user
			$whr=' and id = "'.$karyawan_id.'" or direct_id = "'.$karyawan_id.'" ';
		}



		$field = [];
		
		$field['txtfile'] 		= $this->self_model->return_build_fileinput('file','file');
		
		$msemp 					= $this->db->query("select * from employees where status_id = 1 ".$whr." order by full_name asc")->result(); 
		$field['selemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','',3,'-');

		
		$msstatus = [
		    (object)[
		        'id'    => 'OK',
		        'title' => 'OK'
		    ],
		    (object)[
		        'id'    => 'NOT OK',
		        'title' => 'NOT OK'
		    ]
		];
		$field['selstatus'] 	= $this->self_model->return_build_select2me($msstatus,'','','','status','status','','','id','title',' ','','','',1,'-');



		
		return $field;
	}

	//========================== Considering Already Fixed =======================//
 	/* Construct */
	public function __construct() {
        parent::__construct();
		# akses level
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',$akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);
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


 	public function getDataEmp(){
		$post = $this->input->post(null, true);
		$empid = $post['empid'];

		$rs =  $this->self_model->getDataEmployee($empid);
		

		echo json_encode($rs);
	}



	public function downloadFile(){ 

		$filename 	= $_GET['file']; 
		$empcode 	= $_GET['empcode'];

		if($empcode != ''){
			// Set the full file path
			$filePath = "./uploads/employee/".$empcode."/payslip/" . basename($filename);


			if (file_exists($filePath)) {
			    header('Content-Description: File Transfer');
			    header('Content-Type: application/octet-stream');
			    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
			    header('Content-Length: ' . filesize($filePath));
			    readfile($filePath);
			    exit;
			} else {
			    http_response_code(404);
			    echo "File not found.";
			}
		}else{
			http_response_code(404);
			echo "Employee not found.";
		}
		

 	}



 	public function generate_payslip_pdf()
	{ 
		$payroll_slip_id = 1;
	    // ===============================
	    // 1. Ambil data slip gaji
	    // ===============================
	    $slip = $this->db->query("select a.*, b.full_name, c.period_name, b.emp_code
	    						, d.name as dept_name, b.date_of_birth, c.year as year_period, c.month as month_period 
								from payroll_slip a 
								left join employees b on b.id = a.employee_id 
								left join payroll_periods c on c.id = a.payroll_periods_id
								left join departments d on d.id = b.department_id
								where a.id = ".$payroll_slip_id."")->result(); 

	    if (!$slip) {
	        show_error('Payslip not found');
	    }

	    // ===============================
	    // 2. Generate password PDF
	    // Format: DDMMYYYY (tanggal lahir)
	    // ===============================
	    $pdfPassword = date('dmY', strtotime($slip[0]->date_of_birth));

	    // ===============================
	    // 3. Ambil detail komponen gaji
	    // ===============================
	    $details = $this->db->query("select b.name as component_name, b.type as component_type, b.order_num, a.amount 						  from payroll_details a 
									left join salary_components b on b.id = a.salary_component_id
									where a.payroll_slip_id = ".$payroll_slip_id."
									order by b.order_num asc")->result(); 

	    // ===============================
	    // 4. Generate HTML (Slip Gaji)
	    // ===============================
	    $html = '
	    <h2 style="text-align:center;">PAYSLIP</h2>
	    <hr>
	    <table width="100%" cellpadding="4">
	        <tr>
	            <td>Employee Name</td>
	            <td>: '.$slip[0]->full_name.'</td>
	            <td>Period</td>
	            <td>: '.$slip[0]->period_name.'</td>
	        </tr>
	        <tr>
	            <td>Employee ID</td>
	            <td>: '.$slip[0]->emp_code.'</td>
	            <td>Payslip Number</td>
	            <td>: '.$slip[0]->payslip_number.'</td>
	        </tr>
	        <tr>
	            <td>Department</td>
	            <td>: '.$slip[0]->dept_name.'</td>
	            <td>Print Date</td>
	            <td>: '.date('d M Y', strtotime($slip[0]->payslip_print_date)).'</td>
	        </tr>
	    </table>

	    <br>

	    <table width="100%" border="1" cellpadding="6" cellspacing="0">
	        <tr style="background:#f0f0f0;">
	            <th align="left">Description</th>
	            <th align="right">Amount</th>
	        </tr>';

	    $totalEarning   = 0;
	    $totalDeduction = 0;

	    foreach ($details as $row) {
	        $html .= '
	        <tr>
	            <td>'.$row->component_name.'</td>
	            <td align="right">'.number_format($row->amount, 2).'</td>
	        </tr>';

	        if ($row->component_type === 'earning') {
	            $totalEarning += $row->amount;
	        } else {
	            $totalDeduction += $row->amount;
	        }
	    }

	    $html .= '
	        <tr>
	            <td><strong>Total Earnings</strong></td>
	            <td align="right"><strong>'.number_format($totalEarning, 2).'</strong></td>
	        </tr>
	        <tr>
	            <td><strong>Total Deductions</strong></td>
	            <td align="right"><strong>'.number_format($totalDeduction, 2).'</strong></td>
	        </tr>
	        <tr style="background:#e8e8e8;">
	            <td><strong>Net Pay</strong></td>
	            <td align="right"><strong>'.number_format($slip[0]->take_home_pay, 2).'</strong></td>
	        </tr>
	    </table>

	    <br>
	    <small>This payslip is generated automatically by the system.</small>
	    ';

	    // ===============================
	    // 5. Setup mPDF + Password
	    // ===============================
	    // $mpdf = new \Mpdf\Mpdf([
	    //     'format' => 'A4',
	    //     'margin_top' => 10,
	    //     'margin_bottom' => 10,
	    // ]);
	    

	    // // Set password PDF
	    // $mpdf->SetProtection(
	    //     ['print'],      // permission
	    //     $pdfPassword,   // user password
	    //     null            // owner password
	    // );

	    // $mpdf->WriteHTML($html);


	    // Load library
		$this->load->library('html_pdf');

		// ===============================
		// 1. Tentukan folder & filename DULU
		// ===============================
		$dir = FCPATH . 'uploads/employee/' . $slip[0]->emp_code . '/payslip/';
		if (!is_dir($dir)) {
		    mkdir($dir, 0775, true);
		}

		$ym_period = $slip[0]->year_period.$slip[0]->month_period;
		// contoh: payslip_GDI15010001_202511.pdf
		$fileName = 'payslip_' . $slip[0]->emp_code . '_' .$ym_period. '.pdf';
		$filePath = $dir . $fileName;

		// ===============================
		// 2. Setup PDF
		// ===============================
		$this->html_pdf->filename = $fileName;

		$data = [
		    'slip'    => $slip[0],
		    'details' => $details
		];


		$this->html_pdf->load_view('pdf/payslip', $data);

		// set password KHUSUS payslip
		$this->html_pdf->set_password($pdfPassword);

		// ===============================
		// 3. Render & Save PDF
		// ===============================
		$this->html_pdf->render_pdf();
		$this->html_pdf->save($filePath);




	    // ===============================
	    // 7. Simpan path PDF ke database
	    // ===============================
	   

	    $dataupd = [
			'payslip_pdf_path' 		=> 'uploads/employee/'.$slip[0]->emp_code.'/payslip/' . $fileName,
			'payslip_print_date'	=> date("Y-m-d H:i:s")
		];
		$this->db->update('payroll_slip', $dataupd, "id = '".$payroll_slip_id."'");

	    return true;
	}




}
