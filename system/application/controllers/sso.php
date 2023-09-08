<?php
include "base.php";

class Sso extends Base {

	function Sso()
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
			$params['content'] = $this->load->view("globalpage/member/login_sso", false, true);
		}
		else
		{
			$params['content'] = $this->load->view("member/login_sso", false, true);
		}
		$this->load->view('template', $params);
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
