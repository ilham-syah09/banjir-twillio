<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
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
        $query = $this->db->get('admin')->result();

        $data = [
            'title'     => 'Profile',
            'page'      => 'backend/profile',
            'profile'     => $query,
        ];

        $this->load->view('backend/index', $data);
    }

    public function hapusprofile($id)
    {
        $this->db->delete('profile', ['id' => $id]);
        $this->session->set_flashdata('flash-sukses', 'Sukses hapus data');
        redirect('dashboard/profile');
    }

    public function addprofile()
    {
        $data = [
            'username'      => htmlspecialchars($this->input->post('username')),
            'nama'          => htmlspecialchars($this->input->post('nama')),
            'password'      => password_hash($this->input->post('passowrd'), PASSWORD_BCRYPT)
        ];

        $this->db->insert('profile', $data);
        $this->session->set_flashdata('flash-sukses', 'Sukses tambah data');
        redirect('dashboard/profile');
    }

    public function editprofile()
    {
        $id = $this->input->post('id');

        $upload_foto = $_FILES['foto']['name'];

        if ($upload_foto) {
            $config['upload_path']          = 'upload/';
            $config['allowed_types']        = 'png|jpg|jpeg';
            $config['max_size']             = 5000; // 2 mb
            $config['remove_spaces']        = TRUE;
            $config['encrypt_name']         = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('foto')) {
                $this->session->set_flashdata('flash-error', $this->upload->display_errors());
                redirect('profile', 'refresh');
            } else {
                $upload_data = $this->upload->data();

                $this->db->where('id', $id);
                $profile = $this->db->get('admin')->row();

                if ($this->input->post('password')) {
                    $data = [
                        'username'      => htmlspecialchars($this->input->post('username')),
                        'nama'          => htmlspecialchars($this->input->post('nama')),
                        'password'      => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                        "foto"          => $upload_data['file_name']

                    ];
                } else {
                    $data = [
                        'username'      => htmlspecialchars($this->input->post('username')),
                        'nama'          => htmlspecialchars($this->input->post('nama')),
                        "foto"          => $upload_data['file_name']
                    ];
                }

                if ($profile->foto != "default.jpg") {
                    unlink(FCPATH . 'upload/' . $profile->foto);
                }
            }
        } else {
            if ($this->input->post('password')) {
                $data = [
                    'username'      => htmlspecialchars($this->input->post('username')),
                    'nama'          => htmlspecialchars($this->input->post('nama')),
                    'password'      => password_hash($this->input->post('password'), PASSWORD_BCRYPT)

                ];
            } else {
                $data = [
                    'username'      => htmlspecialchars($this->input->post('username')),
                    'nama'          => htmlspecialchars($this->input->post('nama'))

                ];
            }
        }

        $this->db->where('id', $id);
        $this->db->update('admin', $data);

        $this->session->set_flashdata('flash-sukses', 'Sukses edit data');

        redirect('profile');
    }
}

/* End of file profile.php */
