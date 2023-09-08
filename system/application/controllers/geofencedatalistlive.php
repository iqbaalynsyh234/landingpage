<?php
include "base.php";

class Geofencedatalistlive extends Base {

	function __construct()
	{
		parent::Base();
		$this->load->helper('common_helper');
		$this->load->helper('email');
		$this->load->library('email');
		$this->load->model("dashboardmodel");
		$this->load->helper('common');
	}
	
	function index()
	{
		$this->params['sortby'] = "group_name";
		$this->params['orderby'] = "asc";

		$this->params['title'] = "Geofence Data List (live)";

		$sortby = isset($_POST['sortby']) ? $_POST['sortby'] : "geofence_id";
		$orderby = isset($_POST['orderby']) ? $_POST['orderby'] : "desc";
		$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : "";
		$offset=0;
		$this->db = $this->load->database($this->sess->user_dblive, TRUE);
		$this->db->select("geofence_id,geofence_name,geofence_vehicle,geofence_created,geofence_type,geofence_user,geofence_speed");
		$this->db->order_by($sortby, $orderby);	
		$this->db->group_by("geofence_name");	
		$this->db->where("geofence_status", 1);
		$this->db->where("geofence_user", $this->sess->user_id);
		$q = $this->db->get("geofence");
		$rows = $q->result();
		$total = count($rows);
		
		
		//get data user
		$this->db = $this->load->database("default", TRUE);
		$this->db->select("user_id,user_name");
		$this->db->where("user_status", 1);
		$qusr = $this->db->get("user");
		$rows_user = $qusr->result();
		
		$this->load->library("pagination1");

		$config['uri_segment'] = 3;
		$config['total_rows'] = $total;
		$config['per_page'] = $this->config->item("limit_records");

		$this->pagination1->initialize($config);
		$this->params["offset"]         = $offset;
		$this->params["total"]          = $total;
		$this->params["data"]           = $rows;
		$this->params["ruser"]          = $rows_user;
		$this->params['code_view_menu'] = "configuration";


		$this->params["header"]      = $this->load->view('dashboard/header', $this->params, true);
		$this->params["sidebar"]     = $this->load->view('dashboard/sidebar', $this->params, true);
		$this->params["chatsidebar"] = $this->load->view('dashboard/chatsidebar', $this->params, true);
		$this->params["content"]     = $this->load->view('dashboard/geofencedatalistlive/v_geofencedata_list', $this->params, true);
		$this->load->view("dashboard/template_dashboard_report", $this->params);

		
	}
	
	function edit($id)
	{
		$id = $this->uri->segment(3);
		
		$this->dblive = $this->load->database($this->sess->user_dblive, TRUE);
		$this->dblive->select("geofence_name,geofence_id,geofence_created,geofence_speed");
		$this->dblive->limit(1);
		$this->dblive->where("geofence_id", $id);
		$qr = $this->dblive->get("geofence");
		$row = $qr->row();
		
		$this->params["row"] = $row;	

		$this->params['code_view_menu'] = "configuration";
		$this->params["header"]      = $this->load->view('dashboard/header', $this->params, true);
		$this->params["sidebar"]     = $this->load->view('dashboard/sidebar', $this->params, true);
		$this->params["chatsidebar"] = $this->load->view('dashboard/chatsidebar', $this->params, true);
		$this->params["content"]     = $this->load->view('dashboard/geofencedatalistlive/v_geofencedata_edit', $this->params, true);
		$this->load->view("dashboard/template_dashboard", $this->params);
	}

	function save()
	{
		if (! isset($this->sess->user_type))
		{
			redirect(base_url());
		}
		$usersite = $this->sess->user_company;
		$this->db = $this->load->database($this->sess->user_dblive, TRUE);
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$name = isset($_POST['name']) ? trim($_POST['name']) : "";
		$speed = isset($_POST['speed']) ? trim($_POST['speed']) : "";
		$error = "";
		
		if ($name == "")
		{
			$error .= "- Please fill Name ! \n";	
		}
		

		if ($error != "")
		{
			$callback['error'] = true;	
			$callback['message'] = $error;	
			
			echo json_encode($callback);			
			return;
		}

		unset($data);

		$data['geofence_name'] = $name;
		$data['geofence_speed'] = $speed;
		if ($id > 0)
		{
			$data['geofence_name'] = $name;
			$data['geofence_speed'] = $speed;
			
			$this->db->limit(1);
			$this->db->where("geofence_id",$id);
			$this->db->update("geofence",$data);
			
			$callback['error'] = false;
			$callback['message'] = "Edit Data Success";
			echo json_encode($callback);

			return;
		}

	}
	
	function delete_geofence($id){
		if (! isset($this->sess->user_type))
		{
			redirect(base_url());
		}
		$this->db = $this->load->database($this->sess->user_dblive, TRUE);
		$data["geofence_status"] = 2;
		$this->db->where("geofence_id", $id);
		if($this->db->update("geofence", $data)){
		
			$callback['message'] = "Data has been deleted";
			$callback['error'] = false;	
		}else{
			$callback['message'] = "Failed delete data";
			$callback['error'] = true;	
		}
		echo json_encode($callback);
	}
  


}
