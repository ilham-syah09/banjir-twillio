<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_Login extends CI_Model
{
	function cek_login($u, $p)
	{
		$this->db->where('username', $u);
		$data = $this->db->get('admin')->row();

		if ($data) {
			if (password_verify($p, $data->password)) {
				$login		=	array(
					'is_logged_in'	=> 	true,
					'id'			=>	$data->id
				);
				if ($login) {
					$this->session->set_userdata('data_login', $login);
					$this->session->set_userdata($login);
					return 'Valid';
				}
			} else {
				return 'Password Salah';
			}
		} else {
			return 'Username tidak terdaftar';
		}
	}
}
/* End of file M_Login.php */
/* Location: ./application/models/M_Login.php */