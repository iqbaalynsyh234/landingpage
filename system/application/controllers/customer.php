<?php
include "base.php";

class Customer extends Base {

	function __construct()
	{
		parent::Base();
		$this->load->helper('common_helper');
		$this->load->helper('email');
		$this->load->library('email');
		$this->load->model("dashboardmodel");
    $this->load->model("m_custtms");
		$this->load->helper('common');
	}


  function index(){
		if (!isset($this->sess->user_type))
		{
			redirect(base_url());
		}

    $getcusttms = $this->m_custtms->getcusttms();

		// echo "<pre>";
		// var_dump($getcusttms);die();
		// echo "<pre>";

    $this->params["custtms"]        = $getcusttms;
		$this->params['url_code_view']  = "1";
		$this->params['code_view_menu'] = "monitor";
		$this->params['maps_code']      = "morehundred";

		$this->params["header"]         = $this->load->view('dashboard/header', $this->params, true);
		$this->params["sidebar"]        = $this->load->view('dashboard/sidebar', $this->params, true);
		$this->params["chatsidebar"]    = $this->load->view('dashboard/chatsidebar', $this->params, true);
		$this->params["content"]        = $this->load->view('dashboard/customertms/v_custtms', $this->params, true);
		$this->load->view("dashboard/template_dashboard_report", $this->params);
  }

  function savecusttms(){
		// FOR ADD CUSTOMER
		$joborder_customer 				 = $this->input->post('joborder_customer');
		$joborder_customername     = $this->input->post('joborder_customername');
			if ($joborder_customername == "") {
				 echo json_encode(array("code" => "100", "msg" => "Please fiil Customer Name Field"));
				 return;
			}
		$joborder_geofencename     = $this->input->post('joborder_geofencename');
		$joborder_customercode     = $this->input->post('joborder_customercode');
		$joborder_customercodearea = $this->input->post('joborder_customercodearea');
		$joborder_customerwhatsapp = $this->input->post('joborder_customerwhatsapp');
		$latitude                  = $this->input->post('latitude');
		$longitude                 = $this->input->post('longitude');
		$customer_coordinate 			 = $latitude.','.$longitude;
		$addressfix                = $this->input->post('addressfix');

		if ($this->sess->user_level == 1) {
			$userparentfix = $this->sess->user_id;
		}else {
			$userparentfix = $this->sess->user_parent;
		}

    $dataaddcustomer = array(
      "customer_code"                 => $joborder_customercode,
      "customer_code_area"            => $joborder_customercodearea,
      "customer_name"                 => $joborder_customername,
      "customer_coordinate"           => $latitude.','.$longitude,
      "customer_address"              => $addressfix,
      "customer_geofence_name"        => $joborder_geofencename,
			"customer_whatsapp"			        => $joborder_customerwhatsapp,
      "customer_createdby_id"         => $this->sess->user_id,
      "customer_createdby_company"    => $this->sess->user_company,
			"customer_createdby_subcompany" => $this->sess->user_subcompany,
			"customer_parent"  							=> $userparentfix,
      "customer_created_date"         => date("Y-m-d H:i:s")
    );


		// echo "<pre>";
		// var_dump($dataaddcustomer);die();
		// echo "<pre>";

    $this->m_custtms->insertdata("joborder_customer", $dataaddcustomer);

		echo json_encode(array("code" => "200"));
	}

	function edit($idcustomer){
		$getcustomer                    = $this->m_custtms->getthiscustomer($idcustomer);
		$this->params['code_view_menu'] = "configuration";
		$this->params['custdata']       = $getcustomer;
		$this->params['latlng']         = explode(",", $getcustomer[0]['customer_coordinate']);

		// echo "<pre>";
		// var_dump($getcustomer);die();
		// echo "<pre>";

		$this->params["header"]         = $this->load->view('dashboard/header', $this->params, true);
		$this->params["sidebar"]        = $this->load->view('dashboard/sidebar', $this->params, true);
		$this->params["chatsidebar"]    = $this->load->view('dashboard/chatsidebar', $this->params, true);
		$this->params["content"]        = $this->load->view('dashboard/customertms/v_custtmsedit', $this->params, true);
		$this->load->view("dashboard/template_dashboard_report", $this->params);
	}

  function checkthisgeofence(){
		$latlng = explode(",", $_POST['latlng']);

		$thisgeofence = $this->getGeofence_location($latlng[1], "E", $latlng[0], "S", $this->sess->user_id);
		echo json_encode($thisgeofence);
		// echo "<pre>";
		// var_dump($thisgeofence);die();
		// echo "<pre>";
	}

  function getGeofence_location($longitude, $ew, $latitude, $ns, $vehicle_user) {
		$lng = $longitude;
		$lat = $latitude;
		// getLongitude($longitude, $ew); getLatitude($latitude, $ns);
				$geo_name = "''";

		$sql = sprintf("
					SELECT 	*
					FROM 	%sgeofence
					WHERE 	TRUE
														AND (geofence_name <> %s)
							AND CONTAINS(geofence_polygon, GEOMFROMTEXT('POINT(%s %s)'))
														AND (geofence_user = '%s' )
														AND (geofence_status = 1)
							ORDER BY (geofence_id = 'desc')
					LIMIT 1 OFFSET 0", $this->db->dbprefix, $geo_name, $lng, $lat, $vehicle_user);
				// print_r($sql);exit();
		$q = $this->db->query($sql);
		// echo "<pre>";
		// var_dump($q->result_array());die();
		// echo "<pre>";

		if ($q->num_rows() > 0)
		{
			$row  = $q->row();
			$data = $row->geofence_name;
			return $data;
		}else{
				return false;
		}
	}

	function updatecusttms(){
		$custid                    = $_POST['custid'];
		$joborder_customername     = $_POST['joborder_customername'];
		$joborder_customercode     = $_POST['joborder_customercode'];
		$joborder_customercodearea = $_POST['joborder_customercodearea'];
		$joborder_customerwhatsapp = $_POST['joborder_customerwhatsapp'];
		$joborder_geofencename     = $_POST['joborder_geofencename'];
		$latitude                  = $_POST['latitude'];
		$longitude                 = $_POST['longitude'];
		$addressfix                = $_POST['addressfix'];



		$data = array(
			"customer_code"          => $joborder_customercode,
			"customer_code_area"     => $joborder_customercodearea,
			"customer_name"          => $joborder_customername,
			"customer_whatsapp"			 => $joborder_customerwhatsapp,
			"customer_geofence_name" => $joborder_geofencename,
			"customer_coordinate"    => $latitude.','.$longitude,
			"customer_address"       => $addressfix
		);

		// echo "<pre>";
		// var_dump($data);die();
		// echo "<pre>";

		$update = $this->m_custtms->updatedata("joborder_customer", $custid, $data);
			if ($update) {
				echo json_encode(array("code" => 200, "msg" => "Customer succesfully updated"));
			}else {
				echo json_encode(array("code" => 400, "msg" => "Failed update customer"));
			}
	}

	function delete(){
		$custid = $_POST['custid'];
		$data = array(
			"customer_flag" => 0
		);
		$delete = $this->m_custtms->deletedata("joborder_customer", $custid, $data);
			if ($delete) {
				echo json_encode(array("code" => 200, "msg" => "Customer succesfully deleted"));
			}else {
				echo json_encode(array("code" => 400, "msg" => "Failed delete customer"));
			}
	}


}
?>
