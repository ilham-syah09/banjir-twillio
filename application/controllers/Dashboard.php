<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
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
            'title' => 'Dashboard',
            'page'  => 'backend/dashboard',
        ];

        $this->load->view('backend/index', $data);
    }

    public function get_realtime()
    {

        $this->db->order_by('id', 'desc');
        $query = $this->db->get('sensor', 10)->row();

        echo json_encode([
            'data'  => $query,
        ]);
    }

    public function get_grafik()
    {

        $this->db->order_by('date', 'desc');

        $query = $this->db->get('sensor', 10)->result();

        echo json_encode(array_reverse($query));
    }


    public function editAbout()
    {
        $upload_image = $_FILES['image']['name'];

        if ($upload_image) {
            $config['upload_path']      = 'upload';
            $config['allowed_types']    = 'jpg|jpeg|png';
            $config['max_size']         = 3000;
            $config['remove_spaces']    = TRUE;
            $config['encrypt_name']     = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('image')) {
                $this->session->set_flashdata('flash-error', $this->upload->display_errors());
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            } else {
                $upload_data = $this->upload->data();
                $data = [
                    'image'     => $upload_data['file_name'],
                    'title'     => $this->input->post('title'),
                    'deskripsi' => $this->input->post('deskripsi'),
                    'author'     => $this->input->post('author'),
                ];

                $query = $this->db->get('tb_about', 1)->row();


                $this->db->where('id', 1);
                $update = $this->db->update('tb_about', $data);
                if ($update) {
                    if ($query->image != null) {
                        unlink(FCPATH . 'upload/' . $query->image);
                    }
                    $this->session->set_flashdata('flash-sukses', 'About Updated');
                    redirect('dashboard/about', 'refresh');
                } else {
                    $this->session->set_flashdata('flash-error', 'Update about failed');
                    redirect('dashboard/about', 'refresh');
                }
            }
        } else {
            $data = [
                'title'     => $this->input->post('title'),
                'deskripsi' => $this->input->post('deskripsi'),
                'author'    => $this->input->post('author'),
            ];

            $this->db->where('id', 1);
            $update = $this->db->update('tb_about', $data);
            if ($update) {
                $this->session->set_flashdata('flash-sukses', 'About Updated');
                redirect('dashboard/about', 'refresh');
            } else {
                $this->session->set_flashdata('flash-error', 'Update about failed');
                redirect('dashboard/about', 'refresh');
            }
        }
    }
}
