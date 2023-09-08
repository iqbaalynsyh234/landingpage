<?php
include "base.php";

class Abcargointegration extends Base {

	function Abcargointegration()
	{
		parent::Base();
		$this->load->model("gpsmodel");
    $this->load->model("m_integrationmodul");

		if (! isset($this->sess->user_type))
		{
			redirect(base_url());
		}

		if (! $this->sess->user_type)
		{
			redirect(base_url());
		}
	}


  function index(){
    $datavehicle   = $this->m_integrationmodul->allvehicle();
    $dataintegrasi = $this->m_integrationmodul->abcargodataintegrasi();
    $total         = sizeof($datavehicle);
    // echo "<pre>";
    // var_dump($dataintegrasi);die();
    // echo "<pre>";
    $config['uri_segment'] = 5;
    $config['base_url']    = base_url()."integration";
    $config['total_rows']  = $total;
    $config['per_page']    = $this->config->item("limit_records");

    $this->pagination->initialize($config);

    $this->params['title']         = "Integration Modul";
    $this->params["paging"]        = $this->pagination->create_links();
    $this->params["offset"]        = 0;
    $this->params["total"]         = $total;
    $this->params["datavehicle"]   = $datavehicle;
    $this->params["dataintegrasi"] = $dataintegrasi;
    // $this->params["contentpoi"] = $this->load->view('poi/tblpoi', $this->params, true);
    $this->params["content"]       = $this->load->view('integrationmodul/v_home_abcargointegration', $this->params, true);
    $this->load->view("templatesess", $this->params);
  }

  function submitintegration(){
  	$integration_vehicle   = explode("|", $_POST['integration_vehicle']);
    $integrationShipmentNo = $_POST['integrationShipmentNo'];

    $data = array(
      "integration_vehicle_device" => $integration_vehicle[0],
			"integration_vehicle_no"     => $integration_vehicle[1],
      "integration_shipment_no"    => $integrationShipmentNo,
      "integration_owner_id"       => $this->sess->user_id,
      "integration_submit"         => date("Y-m-d H:i:s")
    );

    // echo "<pre>";
    // var_dump($integration_vehicle);die();
    // echo "<pre>";

    $insert = $this->m_integrationmodul->submitabcargo("integration_modul", $data);

    if ($insert) {
      echo json_encode(array("code" => "200", "msg" => "success"));
    }else {
      echo json_encode(array("code" => "400", "msg" => "failed"));
    }
  }

  function changestatus(){
    $integration_id = $_POST['integration_id'];
    $status = $_POST['status'];

    $data = array(
      "integration_status"         => $status
    );

    $update = $this->m_integrationmodul->updateUmum("transporter", "integration_modul", "integration_id", $integration_id, $data);
    if ($update) {
      echo json_encode(array("code" => "200", "msg" => "success"));
    }else {
      echo json_encode(array("code" => "400", "msg" => "failed"));
    }
  }














}
