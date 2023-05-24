<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Data extends CI_Controller
{
	public function save()
	{
		if ($this->input->get('ketinggian') < 12) {
			$status = "AMAN";
		} else if ($this->input->get('ketinggian') >= 13 && $this->input->get('ketinggian') <= 17) {
			$status = "AWAS";
		} else {
			$status = "WASPADA";
		}


		$ketinggian = $this->input->get('ketinggian');
		$debit = $this->input->get('debit');


		$data = [
			'ketinggian' => $ketinggian,
			'debit'		 => $debit,
			'status' 	 => $status,
		];

		if ($data) {
			$this->db->insert('sensor', $data);
			echo 'data berhasil disimpan';
		} else {
			echo 'data gagal disimpan!';
		}
	}
}

/* End of file Data.php */
