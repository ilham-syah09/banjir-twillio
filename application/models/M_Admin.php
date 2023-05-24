<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_Admin extends CI_Model
{

	public function getCountAdmin()
	{
		return $this->db->get('admin')->num_rows();
	}

	public function getCountRekap($where = null)
	{
		if ($where) {
			$this->db->where($where);
		}

		return $this->db->get('resi')->num_rows();
	}

	public function rekap()
	{
		return $this->db->get('sensor')->result();
	}

	public function realtimeSampai()
	{
		$this->db->where('status', 1);
		return $this->db->get('resi')->num_rows();
	}

	public function realtimeBelumSampai()
	{
		$this->db->where('status', 0);
		return $this->db->get('resi')->num_rows();
	}
}

/* End of file M_Admin.php */
