<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use Dompdf\Dompdf;

class Html_pdf extends Dompdf
{
    public $filename = 'document.pdf';
    protected $password = null;

    public function __construct()
    {
        parent::__construct();
    }

    protected function ci()
    {
        return get_instance();
    }

    public function load_view($view, $data = array())
    {
        $html = $this->ci()->load->view($view, $data, true);
        $this->loadHtml($html);
        $this->setPaper('A4', 'portrait');
    }

    public function set_password($password = null)
    {
        $this->password = $password;
    }

    public function render_pdf()
    {
        $this->render();

        // 🔐 SET PASSWORD KHUSUS JIKA ADA
        if (!empty($this->password)) {
            $canvas = $this->getCanvas();
            $canvas->get_cpdf()->setEncryption(
                $this->password, // user password
                null,             // owner password
                ['print']         // permission
            );
        }
    }

    public function save($path)
    {
        file_put_contents($path, $this->output());
    }

    public function stream_pdf($attachment = false)
    {
        $this->stream($this->filename, ['Attachment' => $attachment]);
    }
}
