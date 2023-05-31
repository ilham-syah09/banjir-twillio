<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once 'twilio-php-app/vendor/autoload.php';

use Twilio\Rest\Client;

class Data extends CI_Controller
{
	public function save()
	{
		if ($this->input->get('ketinggian') < 12) {
			$status = "AMAN";
		} else if ($this->input->get('ketinggian') >= 12 && $this->input->get('ketinggian') <= 17) {
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

		$setting = $this->db->get('setting')->row();

		if ($data) {
			$this->db->insert('sensor', $data);

			if ($setting->status != $status) {
				$this->_sendMsg($ketinggian, $status);

				$this->db->where('id', 1);
				$this->db->update('setting', [
					'status' => $status
				]);
			}

			echo 'Data berhasil disimpan#' . $setting->relay . '#ok';
		} else {
			echo 'Data gagal disimpan!#' . $setting->relay . '#ok';
		}
	}

	private function _sendMsg($a, $b)
	{
		$sid    = "AC46a1d0816ba31d762db50ae4c65ee3bb";
		$token  = "8dd903331a722915ca3e26993b3dbb5a";
		$twilio = new Client($sid, $token);

		$to = "whatsapp:+62895386907272"; // silahkan diganti
		$from = "whatsapp:+14155238886"; // jangan diganti
		$body = 'Ketinggian Air : ' . $a . ' cm, Status : ' . $b;

		$twilio->messages->create(
			$to,
			array(
				"from" => $from,
				"body" => $body
			)
		);
	}
}

/* End of file Data.php */
