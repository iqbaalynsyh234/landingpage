<?php
include "base.php";

class Devicealert extends Base {
	var $period1;
	var $period2;
	var $tblhist;
	var $tblinfohist;
	var $otherdb;

	function Devicealert()
	{
		parent::Base();
		$this->load->model("vehiclemodel");
		$this->load->model("configmodel");
		$this->load->model("historymodel");
		$this->load->model("dashboardmodel");
		$this->load->model("m_poipoolmaster");
		$this->load->model("gpsmodel");
	}

  function index(){
    redirect(base_url());
  }

  function getallalert(){
    $getdatanya = $this->dashboardmodel->get_devicealert();

		$datafixbgt = array();
		for ($i=0; $i < sizeof($getdatanya); $i++) {
			array_push($datafixbgt, array(
			 "vehicle_no"             => $getdatanya[$i]['vehicle_no'],
			 "vehicle_name"           => $getdatanya[$i]['vehicle_name'],
			 "vehicle_alert"          => $getdatanya[$i]['vehicle_alert'],
			 "vehicle_alert_datetime" => date("d-m-Y H:i:s", strtotime($getdatanya[$i]['vehicle_alert_datetime']) + 420*60)
			));
		}

    // echo "<pre>";
    // var_dump($getdatanya);die();
    // echo "<pre>";

    if (sizeof($getdatanya) > 0) {
      echo json_encode(array("code" => "200", "data" => $datafixbgt));
    }else {
      echo json_encode(array("code" => "400", "data" => "empty"));
    }
  }

  function listalert(){
    $this->params['title']     = "";

    $getdatanya = $this->dashboardmodel->get_devicealert();


		$datafixbgt = array();
		for ($i=0; $i < sizeof($getdatanya); $i++) {
			$lastinfofix = $this->gpsmodel->GeoReverse($getdatanya[$i]['vehicle_lat'], $getdatanya[$i]['vehicle_lng']);
			array_push($datafixbgt, array(
			 "vehicle_no"             => $getdatanya[$i]['vehicle_no'],
			 "vehicle_name"           => $getdatanya[$i]['vehicle_name'],
			 "vehicle_alert"          => $getdatanya[$i]['vehicle_alert'],
			 "vehicle_device"         => $getdatanya[$i]['vehicle_device'],
			 "vehicle_lat"            => $getdatanya[$i]['vehicle_lat'],
			 "vehicle_lng"            => $getdatanya[$i]['vehicle_lng'],
			 "address"                => $lastinfofix->display_name,
			 "gps_alert"              => $getdatanya[$i]['gps_alert'],
			 "gps_status"             => $getdatanya[$i]['gps_status'],
			 "gps_speed"              => $getdatanya[$i]['gps_speed'],
			 "vehicle_alert_datetime" => date("d-m-Y H:i:s", strtotime($getdatanya[$i]['vehicle_alert_datetime']) + 420*60)
			));
		}

		// echo "<pre>";
		// var_dump($datafixbgt);die();
		// echo "<pre>";

    $this->params['devicealert']     = $datafixbgt;
		$this->params['code_view_menu']  = "report";

		$this->params["header"]      = $this->load->view('dashboard/header', $this->params, true);
		$this->params["sidebar"]     = $this->load->view('dashboard/sidebar', $this->params, true);
		$this->params["chatsidebar"] = $this->load->view('dashboard/chatsidebar', $this->params, true);
		$this->params["content"]     = $this->load->view('dashboard/trackers/list_alert', $this->params, true);
		$this->load->view("dashboard/template_dashboard_report", $this->params);
  }

	function listdevicealert(){
    $this->params['title']        = "";

		$rows                           = $this->dashboardmodel->getvehicle_report();
		$this->params["vehicle"]       = $rows;
		$this->params['code_view_menu'] = "report";

		// echo "<pre>";
		// var_dump($this->params['vehicle']);die();
		// echo "<pre>";

		$this->params["header"]         = $this->load->view('dashboard/header', $this->params, true);
		$this->params["sidebar"]        = $this->load->view('dashboard/sidebar', $this->params, true);
		$this->params["chatsidebar"]    = $this->load->view('dashboard/chatsidebar', $this->params, true);
		$this->params["content"]        = $this->load->view('dashboard/trackers/list_device_alert', $this->params, true);
		$this->load->view("dashboard/template_dashboard_report", $this->params);
  }

  function clearnotif(){
    $data = array(
      "gps_view" => 1
    );
    $changeviewedalert = $this->dashboardmodel->update_data("webtracking_gps_alert", $data);
    if ($changeviewedalert) {
      echo json_encode(array("code" => 200));
    }else {
      echo json_encode(array("code" => 400));
    }
  }

	function searchreport(){
		$vehicle      = $this->input->post('vehicle');
		$shour        = str_replace(" ", "", $this->input->post('shour'));
		$ehour        = str_replace(" ", "", $this->input->post('ehour'));
		$sdate        = $this->input->post('sdate')." ".$shour.":00";
		$enddate      = $this->input->post('enddate')." ".$ehour.":00";
		$sdatefix     = date("Y-m-d H:i:s", strtotime($sdate) - 420*60);
		$enddatefix   = date("Y-m-d H:i:s", strtotime($enddate) - 420*60);
	  $devicealert  = $this->dashboardmodel->searchforreport("webtracking_gps_alert", $vehicle, $sdatefix, $enddatefix);
		// echo "<pre>";
		// var_dump($devicealert);die();
		// echo "<pre>";
		$datafixbgt   = array();
			if (sizeof($devicealert) > 0) {
				for ($i=0; $i < sizeof($devicealert); $i++) {
					if ($devicealert[$i]['vehicle_device'] == $vehicle) {
						$lastinfofix = $this->gpsmodel->GeoReverse($devicealert[$i]['vehicle_lat'], $devicealert[$i]['vehicle_lng']);
						array_push($datafixbgt, array(
						 "vehicle_no"              => $devicealert[$i]['vehicle_no'],
	 					 "vehicle_name"            => $devicealert[$i]['vehicle_name'],
	 					 "vehicle_device"          => $devicealert[$i]['vehicle_device'],
						 "vehicle_lat"             => $devicealert[$i]['vehicle_lat'],
						 "vehicle_lng"             => $devicealert[$i]['vehicle_lng'],
						 "address"             	 	 => $lastinfofix->display_name,
	 					 "gps_alert"               => $devicealert[$i]['gps_alert'],
						 "gps_status"              => $devicealert[$i]['gps_status'],
						 "gps_speed"               => $devicealert[$i]['gps_speed'],
	 					 "vehicle_alert_datetime"  => date("d-m-Y H:i:s", strtotime($devicealert[$i]['vehicle_alert_datetime']) + 420*60)
						));
					}else {
						$lastinfofix = $this->gpsmodel->GeoReverse($devicealert[$i]['vehicle_lat'], $devicealert[$i]['vehicle_lng']);
						if ($vehicle == "ALL") {
							array_push($datafixbgt, array(
							 "vehicle_no"             => $devicealert[$i]['vehicle_no'],
		 					 "vehicle_name"           => $devicealert[$i]['vehicle_name'],
		 					 "vehicle_device"         => $devicealert[$i]['vehicle_device'],
							 "vehicle_lat"            => $devicealert[$i]['vehicle_lat'],
							 "vehicle_lng"            => $devicealert[$i]['vehicle_lng'],
							 "address"             	 	=> $lastinfofix->display_name,
		 					 "gps_alert"              => $devicealert[$i]['gps_alert'],
							 "gps_status"             => $devicealert[$i]['gps_status'],
							 "gps_speed"              => $devicealert[$i]['gps_speed'],
		 					 "vehicle_alert_datetime" => date("d-m-Y H:i:s", strtotime($devicealert[$i]['vehicle_alert_datetime']) + 420*60)
							));
						}
					}
				}
			}
		// echo "<pre>";
		// var_dump($datafixbgt);die();
		// echo "<pre>";
		$this->params['devicealert']   = $datafixbgt;
		$html                          = $this->load->view('dashboard/trackers/list_devicealert_result', $this->params, true);
		$callback['html']              = $html;
		echo json_encode($callback);
	}

	function get_gpsalert($device,$host,$type){
		$this->dbalert = $this->load->database($this->sess->user_dblive, true);

		$table_alert = "webtracking_gps_alert";
		$this->dbalert->where("gps_name", $device);
		$this->dbalert->where("gps_host", $host);
		$this->dbalert->where("gps_notif", 0);
		$this->dbalert->where("gps_view", 0);
		$qalert      = $this->dbalert->get($table_alert);
		$rowsalert   = $qalert->result_array();

		return $rowsalert;
	}

}
