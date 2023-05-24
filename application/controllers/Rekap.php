<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('data_login'))) {
            $this->session->set_flashdata('flash-error', 'Anda Belum Login');
            redirect('auth', 'refresh');
        }

        $this->load->model('M_Admin');

        $this->db->where('id', $this->session->userdata('id'));
        $this->dt_admin = $this->db->get('admin')->row();
    }

    public function index()
    {

        $data = [
            'title' => 'Rekap',
            'page'  => 'backend/rekap',
            'rekap' => $this->M_Admin->rekap(),
        ];

        $this->load->view('backend/index', $data);
    }
}

/* End of file Rekap.php */
