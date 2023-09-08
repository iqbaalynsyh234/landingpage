<?php
include "base.php";
setlocale(LC_ALL, 'IND');

class Joborder extends Base {

	function Joborder()
	{
		parent::Base();
		$this->load->model("gpsmodel");
		$this->load->model("vehiclemodel");
		$this->load->model("configmodel");
		$this->load->model("historymodel");
		$this->load->model("dashboardmodel");
		$this->load->model("m_joborder");
	}

  // function index(){
  //   echo "<pre>";
  //   var_dump("Joborder is under construction. Please press back button!");die();
  //   echo "<pre>";
  // }

  function index(){
    $userid         = $this->sess->user_id;
		$companyid      = $this->sess->user_company; //1867;  //1867;
		$userlevel      = $this->sess->user_level; //3; //2;
		$usersubcompany = $this->sess->user_subcompany; //33;
    $datacustomer   = $this->m_joborder->getcustomer();
    $datavehicle    = $this->m_joborder->getvehicle("vehicle", $userid);
    $alldatajob     = $this->m_joborder->alldatajob();

		// GET DATA PICKUP
		$datapickup = array();
		if ($userlevel == 1) {
			$dataforpickup = $this->m_joborder->getbranchoffice("branch", $userid);
		}elseif ($userlevel == 2) {
			$dataforpickup = $this->m_joborder->getsubbranchofficebycompanyid("subcompany", $companyid);
		}else {
			$dataforpickup = $this->m_joborder->getsubbranchoffice("subcompany", $usersubcompany);
			$datavehicle    = $this->m_joborder->getvehiclebysubcompanyid("vehicle", $usersubcompany);
		}

		// echo "<pre>";
		// var_dump($datacustomer);die();
		// echo "<pre>";

		$dataforloopinterval = array();
			for ($i=0; $i < sizeof($alldatajob); $i++) {
				array_push($dataforloopinterval, array(
					"vehicle_device" => $alldatajob[$i]['order_vehicle_device']
				));
			}

			// COMPANY
			$company                  = $this->dashboardmodel->getcompany_byowner();
			$datavehicleandcompany    = array();
			$datavehicleandcompanyfix = array();

				for ($d=0; $d < sizeof($company); $d++) {
					$vehicledata[$d]   = $this->dashboardmodel->getvehicle_bycompany_master($company[$d]->company_id);
					// $vehiclestatus[$d] = $this->dashboardmodel->getjson_status2($vehicledata[1][0]->vehicle_device);
					$totaldata         = $this->dashboardmodel->gettotalengine($company[$d]->company_id);
					$totalengine       = explode("|", $totaldata);
						array_push($datavehicleandcompany, array(
							"company_id"   => $company[$d]->company_id,
							"company_name" => $company[$d]->company_name,
							"totalmobil"   => $totalengine[2],
							"vehicle"      => $vehicledata[$d]
						));
				}

    // echo "<pre>";
    // var_dump($dataforpickup);die();
    // echo "<pre>";
		$this->params['company']                   = $company;
		$this->params['companyid']                 = $companyid;
		$this->params['url_code_view']             = "1";
		$this->params['code_view_menu']            = "monitor";
		$this->params['maps_code']                 = "morehundred";
		$this->params['datacustomer'] 				     = $datacustomer;
		$this->params['datavehicle'] 				       = $datavehicle;
  	$this->params['datajob'] 				 		       = $alldatajob;
		$this->params['dataforpickup'] 				 		 = $dataforpickup;
    $this->params["header"]                    = $this->load->view('dashboard/header', $this->params, true);
    $this->params["sidebar"]                   = $this->load->view('dashboard/sidebar', $this->params, true);
    $this->params["content"]                   = $this->load->view('dashboard/joborder/v_joborder', $this->params, true);
    $this->load->view("dashboard/template_dashboard_report", $this->params);
  }

	function savetojoborder(){
		$user_parent 				     = $this->sess->user_parent;
		$user_id 				         = $this->sess->user_id;
		$userlevel 				       = $this->sess->user_level;
		$user_company 				   = $this->sess->user_company;
		$user_subcompany 				 = $this->sess->user_subcompany;

		if ($userlevel == 1) {
			$order_parentid = $user_id;
		}elseif ($userlevel == 2) {
			$order_parentid = $user_parent;
		}else {
			$order_parentid = $user_parent;
		}

		// $userlevel 				       = 3;
		// $user_company 				   = 1867;
		// $user_subcompany 				 = 35;

		$vehicle                 = isset($_POST['vehicle']) ? $_POST['vehicle'] : "";
		$orderdate               = date("Y-m-d", strtotime($this->input->post('orderdate')));
		$ordertime               = $this->input->post('ordertime');
		$orderdatetime           = $orderdate.' '.$ordertime.':00';
		$memoremark              = $this->input->post('memoremark');
		$jobordercustomer        = $this->input->post('joborder_customer');
		$memonourut				       = $this->input->post('memonourut');

		$branchofficefix     = "";
		$subbranchofficefix  = "";
		$branchofficepost    = isset($_POST['branchoffice']) ? $_POST['branchoffice'] : "0";
		$subbranchofficepost = isset($_POST['subbranchoffice']) ? $_POST['subbranchoffice'] : "0";

		if (isset($branchofficepost)) {
			$branchofficefix = $branchofficepost;
		}else {
			$branchofficefix = "0";
		}

		if (isset($subbranchofficepost)) {
			if ($subbranchofficepost == "") {
				$subbranchofficefix = "0";
			}else {
				$subbranchofficefix = $subbranchofficepost;
			}
		}else {
			$subbranchofficefix = "0";
		}

		// echo "<pre>";
		// var_dump($branchofficefix.'-'.$subbranchofficefix);die();
		// echo "<pre>";

		if ($userlevel == 1) {
			$branchofficefix    = $branchofficefix;
			$subbranchofficefix = $subbranchofficefix;
			// echo "<pre>";
			// var_dump("user level 1 : ".$branchofficefix.'-'.$subbranchofficefix.'-'.$vehicle);die();
			// echo "<pre>";
		}elseif ($userlevel == 2) {
			$branchofficefix    = $user_company;
			$subbranchofficefix = $subbranchofficefix;
			// echo "<pre>";
			// var_dump("user level 2 : ".$branchofficefix.'-'.$subbranchofficefix.'-'.$vehicle);die();
			// echo "<pre>";
		}else {
			$branchofficefix    = $user_company;
			$subbranchofficefix = $user_subcompany;
			// echo "<pre>";
			// var_dump("user level 3 : ".$user_company.'-'.$user_subcompany.'-'.$vehicle);die();
			// echo "<pre>";
		}

		$addnewcustomernya    = $this->input->post('addnewcustomernya');
		if ($addnewcustomernya == 1) {
			$jobordercustomername     = $this->input->post('joborder_customername');
			$jobordergeofencename     = $this->input->post('joborder_geofencename');
			$jobordercustomercode     = $this->input->post('joborder_customercode');
			$jobordercustomercodearea = $this->input->post('joborder_customercodearea');
			$latitude                 = $this->input->post('latitude');
			$longitude                = $this->input->post('longitude');
			$coordinate               = $latitude.','.$longitude;
			$addressfix               = $this->input->post('addressfix');
			$customer_whatsapp        = $this->input->post('joborder_customerwhatsapp');
		}else {
			$customerdetail           = $this->m_joborder->checkcustomer("joborder_customer", $jobordercustomer);

			$jobordercustomercode     = $customerdetail[0]['customer_code'];
			$jobordercustomercodearea = $customerdetail[0]['customer_code_area'];
			$jobordercustomername     = $customerdetail[0]['customer_name'];
			$jobordergeofencename     = $customerdetail[0]['customer_geofence_name'];
			$coordinate               = $customerdetail[0]['customer_coordinate'];
			$addressfix               = $customerdetail[0]['customer_address'];
			$customer_whatsapp        = $customerdetail[0]['customer_whatsapp'];
		}

		if ($jobordercustomername == "") {
			 echo json_encode(array("code" => "100", "msg" => "Please fiil Customer Name Field"));
			 return;
		}

		$vehicledetail = $this->m_joborder->checkvehicle("vehicle", $vehicle);

		$uniquetime    = time();
		$randomunique  = rand(100000,1000000);
		$sharelink     = $this->createlinkonjoborder($uniquetime, $randomunique);

		$data = array(
			"order_randomid_sharelink" 		    => $randomunique,
			"order_parentid" 		              => $order_parentid,
			"order_user_company"              => $branchofficefix,
			"order_user_subcompany"           => $subbranchofficefix,
			"order_vehicle_id"                => $vehicledetail[0]['vehicle_id'],
			"order_vehicle_device"            => $vehicledetail[0]['vehicle_device'],
			"order_vehicle_no"                => $vehicledetail[0]['vehicle_no'],
			"order_vehicle_name"              => $vehicledetail[0]['vehicle_name'],
			"order_vehicle_company"           => $vehicledetail[0]['vehicle_company'],
			"order_jobordercust_code"         => $jobordercustomercode,
			"order_jobordercust_codearea"     => $jobordercustomercodearea,
			"order_jobordercust_name"         => $jobordercustomername,
			"order_jobordercust_coordinate"   => $coordinate,
			"order_jobordercust_address"      => $addressfix,
			"order_jobordercust_geofencename" => $jobordergeofencename,
			"order_jobordercust_whatsapp"		  => $customer_whatsapp,
			"order_remark"	                  => $memoremark,
			"order_nourut"	                  => $memonourut,
			"order_sharelink"                 => base_url()."share/directlink/".$sharelink,
			"order_datetime"                  => $orderdatetime,
			"order_submit"                    => date("Y-m-d H:i:s")
		);

		// echo "<pre>";
		// var_dump($data);die();
		// echo "<pre>";

		$insert = $this->m_joborder->insertdata("joborder", $data);
			if ($insert) {
				echo json_encode(array("code" => "200"));
			}else {
				echo json_encode(array("code" => "400"));
			}
	}

	function changethisstatus(){
		$orderid      = $_POST['id'];
		$data = array(
			"order_status"             => 2,
			"order_completed_datetime" => date("Y-m-d H:i:s"),
			"order_manual_status"      => "MANUAL"
		);
		$changestatus = $this->m_joborder->changestatusnow("joborder", $orderid, $data);

		// echo "<pre>";
		// var_dump($changestatus);die();
		// echo "<pre>";

			if ($changestatus) {
				echo json_encode(array("code" => 200));
			}else {
				echo json_encode(array("code" => 400));
			}
	}

	function cancelthisjoborder(){
		$orderid      = $_POST['id'];
		$data = array(
			"order_flag"             => 1,
		);
		$cancelnow = $this->m_joborder->canceljobordernow("joborder", $orderid, $data);

		// echo "<pre>";
		// var_dump($changestatus);die();
		// echo "<pre>";

			if ($cancelnow) {
				echo json_encode(array("code" => 200));
			}else {
				echo json_encode(array("code" => 400));
			}
	}

	function linkformat(){
		$orderid = $_POST['id'];
		$unique  = $_POST['unique'];

		$pro_text = substr($unique, 0, 3);
		$epi_text = substr($unique, 3, 20);

		// echo "<pre>";
		// var_dump($unique.'-'.$pro_text.'-'.$epi_text);die();
		// echo "<pre>";

		echo json_encode(array("unique" => $orderid.'989812'.$pro_text.$epi_text));
	}

	function internalsharelink(){
		$vehicleid            = $_POST['id'];
		$startdatetime        = date("d-m-Y", strtotime($_POST['startdatetime']));
		$startdatetimeexplode = explode("-", $startdatetime);
		$date                 = $startdatetimeexplode[0];
		$month                = $startdatetimeexplode[1];
		$year                 = $startdatetimeexplode[2];
		$startdatetimefix     = $year."-".$month."-".$date." 00:00:00";
		$enddatetimefix       = $year."-".$month."-".$date." 23:59:59";
		$unique               = $_POST['unique'];

		$joborderbynopol   = $this->m_joborder->getdatabynopol($vehicleid, $startdatetimefix, $enddatetimefix);

		$pro_text             = substr($unique, 0, 3);
		$epi_text             = substr($unique, 3, 20);

		// $vehicleid.'-'.$unique.'-'.$pro_text.'-'.$epi_text
		// $startdatetimefix.'||'.$enddatetimefix

		// echo "<pre>";
		// var_dump($joborderbynopol);die();
		// echo "<pre>";

		echo json_encode(array("unique" => $vehicleid.'989812'.$year.'1010'.$month.'3113'.$date));
	}

	function createlinkonjoborder($randid, $uniqid){

		$pro_text = substr($uniqid, 0, 3);
		$epi_text = substr($uniqid, 3, 10);

		// echo "<pre>";
		// var_dump($randid.'989812'.$pro_text.$epi_text);die();
		// echo "<pre>";
		$result = $randid.'989812'.$pro_text.$epi_text;
		return $result;
	}

	function subbranchofficebybranchid(){
		$branchofficeid = $_POST['branchofficeid'];
		$datavehicle    = $this->m_joborder->vehiclebybranchoffice("vehicle", $branchofficeid);
		$dataforpickup  = $this->m_joborder->getsbubranchofficebyid("subcompany", $branchofficeid);
		$datadriver		  = $this->m_joborder->getdriver("driver");

		// echo "<pre>";
		// var_dump($datadriver);die();
		// echo "<pre>";

		echo json_encode(array("pickup" => $dataforpickup, "vehicle" => $datavehicle, "driver" => $datadriver));
	}

	function vehiclebysubbranchofficeid(){
		$subbranchoffice = $_POST['subbranchoffice'];
		$datavehicle    = $this->m_joborder->vehiclebysubbranchoffice("vehicle", $subbranchoffice);
		$datadriver		  = $this->m_joborder->getdriver("driver");

		// echo "<pre>";
		// var_dump($datavehicle);die();
		// echo "<pre>";

		echo json_encode(array("vehicle" => $datavehicle, "driver" => $datadriver));
	}

}
