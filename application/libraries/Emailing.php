<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emailing {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('email');
    }

    /**
     * Send email using template
     *
     * @param array $mail
     * @param array $data
     * @return bool
     */
    public function send($mail = [], $data = [])
    {
        // default config
        $mail = array_merge([
            'subject'     => '',
            'preheader'   => '',
            'from_name'   => _MAIL_SYSTEM_NAME ?? 'GDI Support System',
            'from_email'  => _MAIL_SYSTEM_EMAIL ?? 'noreply-billing@huma.net.id',
            'to_name'     => '',
            'to_email'    => '',
            'cc'          => '',
            'bcc'         => 'tiarasanir@gmail.com',
            'template'    => '',
            'attach'      => null, /// _URL.'uploads/user_manual_billing.docx',
        ], $mail);

        // inject default template data
        $data = array_merge([
            'preheader'      => $mail['preheader'],
            'corp'           => _COMPANY_NAME,
            'account_title'  => _ACCOUNT_TITLE,
            'link_site'      => _URL,
            'link_logo'      => _URL . 'public/assets/images/logo/gerbangdata.PNG'
        ], $data);

        // load email template
        $message = $this->CI->load->view(
            _TEMPLATE_EMAIL . $mail['template'],
            $data,
            TRUE
        );

        // set email
        $this->CI->email->from($mail['from_email'], $mail['from_name']);
        $this->CI->email->to($mail['to_email'], $mail['to_name']);
        $this->CI->email->cc($mail['cc']);
        $this->CI->email->bcc($mail['bcc']);
        $this->CI->email->subject($mail['subject']);
        $this->CI->email->message($message);

        // attachment (optional)
        if (!empty($mail['attach'])) {
            $this->CI->email->attach($mail['attach']);
        }

        $phpWarnings = [];
        ob_start();
        set_error_handler(function ($severity, $message, $file, $line) use (&$phpWarnings) {
            $phpWarnings[] = $message.' in '.$file.':'.$line;
            return true;
        });

        try {
            $isSent = $this->CI->email->send();
        } finally {
            restore_error_handler();
            $bufferedOutput = ob_get_clean();
        }

        // send
        if ($isSent) {
            return true;
        }

        if (!empty($phpWarnings)) {
            log_message('error', implode("\n", $phpWarnings));
        }
        if (!empty($bufferedOutput)) {
            log_message('error', $bufferedOutput);
        }
        log_message('error', $this->CI->email->print_debugger());
        return false;
    }
}
