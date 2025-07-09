<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Penagihan_model');
    }

    public function update_tagihan_bulanan()
    {
        $this->Penagihan_model->buat_tagihan_bulanan();
        echo "Tagihan bulanan berhasil diperbarui.";
    }
}
