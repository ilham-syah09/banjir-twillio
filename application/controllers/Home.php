<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function index()
    {
        $data = [
            'title' => 'Home',
        ];

        $this->load->view('frontend/index', $data);
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
}

/* End of file Frontend.php */
