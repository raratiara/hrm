<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApprovalEmailService {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->library('emailing');
    }

    public function sendApproval($menu, $trx_id, $approval_path_id)
    {
        if($menu == 'cash_advance'){
            $data = $this->CI->db->query("
                        select a.*, b.role_id, c.role_name, d.ca_number as doc_num, d.requested_by, d.ca_type
                        FROM approval_path a
                        LEFT JOIN approval_matrix_detail b 
                            ON b.approval_matrix_id = a.approval_matrix_id
                            AND b.approval_level = a.current_approval_level
                        LEFT JOIN approval_matrix_role c ON c.id = b.role_id
                        LEFT JOIN cash_advance d ON d.id = a.trx_id
                        WHERE a.id = ?
                    ", [$approval_path_id])->row();

            
            if (!$data) {
                return false;
            }

            $link = ($data->ca_type == 1)
                ? _URL . 'cash_advance/fpu_menu'
                : _URL . 'cash_advance/fpp_menu';

            $subject    = 'Pending Approval - Cash Advance';
            $app        = 'Cash Advance';

        }else if($menu == 'settlement'){
            $data = $this->CI->db->query("
                        select a.*, b.role_id, c.role_name, d.settlement_number as doc_num, d.requested_by
                        FROM approval_path a
                        LEFT JOIN approval_matrix_detail b 
                            ON b.approval_matrix_id = a.approval_matrix_id
                            AND b.approval_level = a.current_approval_level
                        LEFT JOIN approval_matrix_role c ON c.id = b.role_id
                        LEFT JOIN settlement d ON d.id = a.trx_id
                        WHERE a.id = ?
                    ", [$approval_path_id])->row();

            
            if (!$data) {
                return false;
            }

            $link = _URL . 'cash_advance/settlement_menu';

            $subject    = 'Pending Approval - Settlement';
            $app        = 'Settlement';

        }else if($menu == 'leave_absences'){
            $data = $this->CI->db->query("
                        select a.*, b.role_id, c.role_name, '' as doc_num, d.employee_id as requested_by
                        FROM approval_path a
                        LEFT JOIN approval_matrix_detail b 
                            ON b.approval_matrix_id = a.approval_matrix_id
                            AND b.approval_level = a.current_approval_level
                        LEFT JOIN approval_matrix_role c ON c.id = b.role_id
                        LEFT JOIN leave_absences d ON d.id = a.trx_id
                        WHERE a.id = ?
                    ", [$approval_path_id])->row();

            
            if (!$data) {
                return false;
            }

            $link = _URL . 'time_attendance/ijin_menu';

            $subject    = 'Pending Approval - Leave Absences';
            $app        = 'Leave Absences';

        }else if($menu == 'overtimes'){
            $data = $this->CI->db->query("
                        select a.*, b.role_id, c.role_name, '' as doc_num, d.employee_id as requested_by
                        FROM approval_path a
                        LEFT JOIN approval_matrix_detail b 
                            ON b.approval_matrix_id = a.approval_matrix_id
                            AND b.approval_level = a.current_approval_level
                        LEFT JOIN approval_matrix_role c ON c.id = b.role_id
                        LEFT JOIN overtimes d ON d.id = a.trx_id
                        WHERE a.id = ?
                    ", [$approval_path_id])->row();

            
            if (!$data) {
                return false;
            }

            $link = _URL . 'time_attendance/lembur_menu';

            $subject    = 'Pending Approval - Overtimes';
            $app        = 'Overtimes';

        }else if($menu == 'reimbursement'){
            $data = $this->CI->db->query("
                        select a.*, b.role_id, c.role_name, d.reimburs_no as doc_num, d.employee_id as requested_by
                        FROM approval_path a
                        LEFT JOIN approval_matrix_detail b 
                            ON b.approval_matrix_id = a.approval_matrix_id
                            AND b.approval_level = a.current_approval_level
                        LEFT JOIN approval_matrix_role c ON c.id = b.role_id
                        LEFT JOIN medicalreimbursements d ON d.id = a.trx_id
                        WHERE a.id = ?
                    ", [$approval_path_id])->row();

            
            if (!$data) {
                return false;
            }

            $link = _URL . 'compensation_benefit/reimbursement_menu';

            $subject    = 'Pending Approval - Reimbursement';
            $app        = 'Reimbursement';

        }else if($menu == 'loan'){
            $data = $this->CI->db->query("
                        select a.*, b.role_id, c.role_name, d.loan_no as doc_num, d.id_employee as requested_by
                        FROM approval_path a
                        LEFT JOIN approval_matrix_detail b 
                            ON b.approval_matrix_id = a.approval_matrix_id
                            AND b.approval_level = a.current_approval_level
                        LEFT JOIN approval_matrix_role c ON c.id = b.role_id
                        LEFT JOIN loan d ON d.id = a.trx_id
                        WHERE a.id = ?
                    ", [$approval_path_id])->row();

            
            if (!$data) {
                return false;
            }

            $link = _URL . 'compensation_benefit/loan';

            $subject    = 'Pending Approval - Loan';
            $app        = 'Loan';

        }else if($menu == 'business_trip'){
            $data = $this->CI->db->query("
                        select a.*, b.role_id, c.role_name, d.bustrip_no as doc_num, d.employee_id as requested_by
                        FROM approval_path a
                        LEFT JOIN approval_matrix_detail b 
                            ON b.approval_matrix_id = a.approval_matrix_id
                            AND b.approval_level = a.current_approval_level
                        LEFT JOIN approval_matrix_role c ON c.id = b.role_id
                        LEFT JOIN business_trip d ON d.id = a.trx_id
                        WHERE a.id = ?
                    ", [$approval_path_id])->row();

            
            if (!$data) {
                return false;
            }

            $link = _URL . 'compensation_benefit/perjalanan_dinas_menu';

            $subject    = 'Pending Approval - Business Trip';
            $app        = 'Business Trip';

        }else if($menu == 'training'){
            $data = $this->CI->db->query("
                        select a.*, b.role_id, c.role_name, '' as doc_num, d.employee_id as requested_by
                        FROM approval_path a
                        LEFT JOIN approval_matrix_detail b 
                            ON b.approval_matrix_id = a.approval_matrix_id
                            AND b.approval_level = a.current_approval_level
                        LEFT JOIN approval_matrix_role c ON c.id = b.role_id
                        LEFT JOIN employee_training d ON d.id = a.trx_id
                        WHERE a.id = ?
                    ", [$approval_path_id])->row();

            
            if (!$data) {
                return false;
            }

            $link = _URL . 'training_development/training_menu';

            $subject    = 'Pending Approval - Training';
            $app        = 'Training';

        }else if($menu == 'request_recruitment'){
            $data = $this->CI->db->query("
                        select a.*, b.role_id, c.role_name, d.request_number as doc_num, d.requested_by
                        FROM approval_path a
                        LEFT JOIN approval_matrix_detail b 
                            ON b.approval_matrix_id = a.approval_matrix_id
                            AND b.approval_level = a.current_approval_level
                        LEFT JOIN approval_matrix_role c ON c.id = b.role_id
                        LEFT JOIN request_recruitment d ON d.id = a.trx_id
                        WHERE a.id = ?
                    ", [$approval_path_id])->row();

            
            if (!$data) {
                return false;
            }

            $link = _URL . 'request_recruitment/request_recruitment_menu';

            $subject    = 'Pending Approval - Request Recruitment';
            $app        = 'Request Recruitment';
        }


        
        


        $doc_num = $data->doc_num;

        // ===============================
        // GET APPROVER EMAIL
        // ===============================
        if ($data->role_name === 'Direct') {

            $approver = $this->CI->db->query("
                select 
                    c.full_name AS approver_name,
                    c.personal_email AS emails
                FROM employees b
                LEFT JOIN employees c ON c.id = b.direct_id
                WHERE b.id = ?
            ", [$data->requested_by])->row();

        } else {

            $approver = $this->CI->db->query("
                select 
                    GROUP_CONCAT(b.personal_email) AS emails,
                    c.role_name AS approver_name
                FROM approval_matrix_role_pic a
                LEFT JOIN employees b ON b.id = a.employee_id
                LEFT JOIN approval_matrix_role c ON c.id = a.approval_matrix_role_id
                WHERE a.approval_matrix_role_id = ?
            ", [$data->role_id])->row();
        }

        if (!$approver || empty($approver->emails)) {
            return false;
        }

        
        // ===============================
        // SEND EMAIL
        // ===============================
        $mail = [
            'subject'   => $subject,
            'to_name'   => $approver->approver_name,
            'to_email'  => 'tiarasanir@gmail.com',///$approver->emails,
            'template'  => 'approval'
        ];

        $emailData = [
            'approver_name' => $approver->approver_name,
            'app'           => $app,
            'doc_num'       => $doc_num,
            'link'          => $link
        ];

        return $this->CI->emailing->send($mail, $emailData);
    }
}
