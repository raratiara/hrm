<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApprovalNotifMobile {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->model('notification/Notification_model', 'notif');
    }


    public function send_notif($type, $approver_empid)
    {
        if ($approver_empid != '' && $type != '') {

            $ids = explode(',', $approver_empid);

            $this->CI->db->select('a.id as employee_id, a.full_name, c.fcm_token, b.user_id');
            $this->CI->db->from('employees a');
            $this->CI->db->join('user b','b.id_karyawan = a.id','left');
            $this->CI->db->join('user_devices c','c.user_id = b.user_id','left');
            $this->CI->db->where_in('a.id',$ids);
            $this->CI->db->where('a.status_id',1);
            $this->CI->db->where('c.fcm_token IS NOT NULL');
            $this->CI->db->where('c.fcm_token !=','');

            $users = $this->CI->db->get()->result();

            if (!empty($users)) {
                $title = "-- Approval";
                $app = "--";
                foreach ($users as $u) {

                    if ($type == 'leave_absences') {
                        $title = "Leave Absences Approval";
                        $app = "Leave Absences";

                    }else if($type == 'overtimes'){
                        $title = "Overtime Approval";
                        $app = "Overtime";
                    }else if($type == 'cash_advance'){
                        $title = "Cash Advance Approval";
                        $app = "Cash Advance";
                    }else if($type == 'settlement'){
                        $title = "Settlement Approval";
                        $app = "Settlement";
                    }else if($type == 'reimbursement'){
                        $title = "Reimbursement Approval";
                        $app = "Reimbursement";
                    }else if($type == 'loan'){
                        $title = "Loan Approval";
                        $app = "Loan";
                    }else if($type == 'business_trip'){
                        $title = "Business Trip Approval";
                        $app = "Business Trip";
                    }else if($type == 'training'){
                        $title = "Training Approval";
                        $app = "Training";
                    }else if($type == 'request_recruitment'){
                        $title = "Request Recruitment Approval";
                        $app = "Request Recruitment";
                    }

                    $body = "Hi ".$u->full_name.", you have a pending ".$app." request awaiting your approval.";
                    $message = "Hi ".$u->full_name.", you have a pending ".$app." request awaiting your approval. Please review it in the system.";

                    $this->CI->notif->sendNotification(
                        $u->fcm_token,
                        $title,
                        $body,
                        [
                            'type' => $type,
                            'user_id' => (string) $u->user_id,
                            'message' => $message
                        ]
                    );

                }
            }
        }
    }


    

}
