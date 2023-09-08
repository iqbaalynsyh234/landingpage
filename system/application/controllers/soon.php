<?php
include "base.php";

class Soon extends Base {

	function Soon()
	{
		parent::Base();

		$this->load->helper("common");
		$this->load->model("smsmodel");
	}

	
	function index()
	{
		if ($this->sess)
		{
			redirect(base_url()."trackers/");
		}

		if ($this->config->item("login"))
		{
			$servername = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "abditrack.com";
			$urls = parse_url($this->config->item("login"));
			if ($urls['host'] != $servername)
			{
				redirect($this->config->item("login"));
			}
		}

		if($this->config->item("iscustompage"))
		{
			$params['content'] = $this->load->view("globalpage/member/comingsoon", false, true);
		}
		else
		{
			$params['content'] = $this->load->view("member/comingsoon", false, true);
		}
		$this->load->view('template', $params);
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
