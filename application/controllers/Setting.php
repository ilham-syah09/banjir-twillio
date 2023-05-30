<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends CI_Controller
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
        $this->db->order_by('id', 'desc');
        $setting = $this->db->get('setting', 1)->row();

        $data = [
            'title' => 'Setting',
            'page'  => 'backend/setting',
            'setting'    => $setting
        ];

        $this->load->view('backend/index', $data);
    }

    public function changeSelenoid()
    {
        $data = [
            'relay'    => $this->input->post('relay')
        ];

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('setting', $data);
        $this->session->set_flashdata('flash-sukses', 'berhasil update setting');
        redirect('setting');
    }
}
