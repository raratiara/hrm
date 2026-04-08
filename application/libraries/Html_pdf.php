<?php defined('BASEPATH') or exit('No direct script access allowed');

$autoload = APPPATH . '../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
} else {
    show_error('vendor/autoload.php tidak ditemukan');
}

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

    public function load_view_landscape($view, $data = array())
    {
        $html = $this->ci()->load->view($view, $data, true);
        $this->loadHtml($html);
        $this->setPaper('A4', 'landscape');
    }

    public function set_password($password = null)
    {
        $this->password = $password;
    }

    public function render_pdf()
    {
        $this->render();

        if (!empty($this->password)) {
            $canvas = $this->getCanvas();
            $canvas->get_cpdf()->setEncryption(
                $this->password,
                null,
                ['print']
            );
        }
    }

    public function stream_pdf($attachment = false)
    {
        // bersihin output buffer biar PDF tidak corrupt
        if (ob_get_length()) {
            ob_end_clean();
        }

        $this->stream($this->filename, ['Attachment' => $attachment]);
    }


    public function render_to_string($view, $data = [])
    {
        // BUAT INSTANCE BARU (INI KUNCI UTAMA)
        $dompdf = new Dompdf();

        $html = $this->ci()->load->view($view, $data, true);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output(); // PDF binary
    }


    public function render_to_string_portrait($view, $data = [])
    {
        // BUAT INSTANCE BARU (INI KUNCI UTAMA)
        $dompdf = new Dompdf();

        $html = $this->ci()->load->view($view, $data, true);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output(); // PDF binary
    }



}
