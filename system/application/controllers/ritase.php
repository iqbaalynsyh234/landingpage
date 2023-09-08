<?php
include "base.php";

class Ritase extends Base {

	function Ritase()
	{
		parent::Base();
		$this->load->model("gpsmodel");
    $this->load->helper('common_helper');
		$this->load->helper('email');
		$this->load->library('email');
		$this->load->model("dashboardmodel");
		$this->load->helper('common');

		if (! isset($this->sess->user_type))
		{
			redirect(base_url());
		}

	}

	function index()
	{
		if (!isset($this->sess->user_company))
		{
			redirect(base_url());
		}

		$this->dbtransporter = $this->load->database("transporter",true);
		$this->dbtransporter->where("ritase_company", $this->sess->user_company);
		//$this->dbtransporter->where("ritase_status", 1);
		$q_ritase    = $this->dbtransporter->get("ritase",10, 0);
		$rows_ritase = $q_ritase->result();
		$total       = count($rows_ritase);
		$this->dbtransporter->where("ritase_company", $this->sess->user_company);
		//$this->dbtransporter->where("ritase_status", 1);
		$qtotal    = $this->dbtransporter->get("ritase");
		$rowstotal = $qtotal->result();
		$total     = count($rowstotal);

		$config['total_rows']   = $total;

		$this->params['data']    = $rows_ritase;
		$this->params['total']   = $total;

    // GET DATA UNTUK ADD RITASE
    $this->dbtransporter = $this->load->database("transporter",true);
		$this->dbtransporter->where("ritase_company", $this->sess->user_company);
		$this->dbtransporter->where("ritase_status", 1);
		$q_ritase = $this->dbtransporter->get("ritase");
		$rows_ritase = $q_ritase->result();

		if (count($rows_ritase) > 0)
		{
			foreach($rows_ritase as $row_ritase)
			{
				$ritase_geofence_name[] = $row_ritase->ritase_geofence_name;
			}
		}

		$this->db->order_by("geofence_name", "asc");
		$this->db->where("geofence_status", "1");
		$this->db->where("geofence_name !=", "");
		$this->db->where("geofence_user", $this->sess->user_id);
		if (count($rows_ritase) > 0)
		{
			$this->db->where_not_in("geofence_name", $ritase_geofence_name);
		}
		$q = $this->db->get("geofence");
		$rows = $q->result();

		$this->params['dataforritase']  = $rows;
    $this->params['rows_ritase']    = $rows_ritase;
		$this->params['code_view_menu'] = "configuration";

    $this->params["header"]         = $this->load->view('dashboard/header', $this->params, true);
		$this->params["sidebar"]        = $this->load->view('dashboard/sidebar', $this->params, true);
		$this->params["chatsidebar"]    = $this->load->view('dashboard/chatsidebar', $this->params, true);
		$this->params["content"]        = $this->load->view('dashboard/ritase/v_ritase', $this->params, true);
		$this->load->view("dashboard/template_dashboard_report", $this->params);
	}

	function add()
	{
		if (!isset($this->sess->user_company))
		{
			redirect(base_url());
		}

		$this->dbtransporter = $this->load->database("transporter",true);
		$this->dbtransporter->where("ritase_company", $this->sess->user_company);
		$this->dbtransporter->where("ritase_status", 1);
		$q_ritase = $this->dbtransporter->get("ritase");
		$rows_ritase = $q_ritase->result();

		if (count($rows_ritase) > 0)
		{
			foreach($rows_ritase as $row_ritase)
			{
				$ritase_geofence_name[] = $row_ritase->ritase_geofence_name;
			}
		}

		$this->db->order_by("geofence_name", "asc");
		$this->db->where("geofence_status", "1");
		$this->db->where("geofence_name !=", "");
		$this->db->where("geofence_user", $this->sess->user_id);
		if (count($rows_ritase) > 0)
		{
			$this->db->where_not_in("geofence_name", $ritase_geofence_name);
		}
		$q = $this->db->get("geofence");
		$rows = $q->result();

		$this->params['data'] = $rows;
		$this->params["content"] = $this->load->view('ritase/add', $this->params, true);
		$this->load->view("templatesess", $this->params);
	}

	function save()
	{
		$this->dbtransporter          = $this->load->database("transporter", true);

		$company                      = $this->sess->user_company;
		$name                         = isset($_POST['ritase_name']) ? trim($_POST['ritase_name']): "";
		unset($data);

		$data['ritase_company']       = $company;
		$data['ritase_geofence_name'] = $name;
		$data['ritase_status']        = 1;

		$this->dbtransporter->insert("ritase", $data);

		$callback['error']    = false;
		$callback['message']  = "Add Ritase Seccess";
		$callback['redirect'] = base_url()."ritase";

		echo json_encode($callback);
		return;
	}

	function info_delete()
	{
		$id = $this->input->post("id");
		if ($id)
		{
			$this->dbtransporter = $this->load->database("transporter", true);
			$this->dbtransporter->where("ritase_id", $id);
			$this->dbtransporter->limit(1);
			$q = $this->dbtransporter->get("ritase");
			$row = $q->row();

			$params["row"] = $row;
			$html = $this->load->view("ritase/info_delete", $params, true);
			$callback["error"] = false;
			$callback["html"] = $html;

			echo json_encode($callback);

		}
	}


	function remove()
	{
		$id = $this->input->post("id_ritase");
		if ($id)
		{
			$this->dbtransporter = $this->load->database("transporter", true);
			$this->dbtransporter->where("ritase_id", $id);
			$this->dbtransporter->delete("ritase");
			$this->dbtransporter->cache_delete_all();
			echo json_encode(array("msg" => "success", "code" => "200"));
		}
	}

	function menu_ritase_report()
	{
		if (! isset($this->sess->user_type))
		{
			redirect(base_url());
		}

        $this->db->order_by("vehicle_name", "asc");
        $this->db->order_by("vehicle_no", "asc");
        $this->db->where("vehicle_status <>", 3);

        if ($this->sess->user_type == 2)
        {
            $this->db->where("vehicle_user_id", $this->sess->user_id);
            $this->db->or_where("vehicle_company", $this->sess->user_company);
            $this->db->where("vehicle_active_date2 >=", date("Ymd"));
        }

		$q_vehicle = $this->db->get("vehicle");
		$row_vehicle = $q_vehicle->result();
        //print_r($row_vehicle);exit;

		$this->db->cache_delete_all();

		$this->dbtransporter = $this->load->database("transporter", true);

		$this->dbtransporter->order_by("ritase_geofence_name", "asc");
		$this->dbtransporter->where("ritase_company", $this->sess->user_company);
		$this->dbtransporter->where("ritase_status", "1");

		$q_ritase = $this->dbtransporter->get("ritase");
		$row_ritase = $q_ritase->result();

		$this->dbtransporter->cache_delete_all();

		$this->params["vehicle"] = $row_vehicle;
		$this->params["ritase"] = $row_ritase;
		$this->params["content"] = $this->load->view('ritase/mn_ritase_report', $this->params, true);
		$this->load->view("templatesess", $this->params);
	}

	function ritase_report()
	{
		$vehicle_device = $this->input->post("vehicle");

		$startdate = $this->input->post("date");
		$sdate = date("Y-m-d H:i:s", strtotime($startdate." "."00:00:00"));

		$enddate = $this->input->post("enddate");
		$edate = date("Y-m-d H:i:s", strtotime($enddate." "."23:59:59"));

		$ritase = $this->input->post("ritase");
		$exRitase = explode(",",$ritase);
		$ritase_id = $exRitase[0];
		$ritase_name = $exRitase[1];

		$this->db->order_by("geoalert_time", "asc");
		$this->db->where("geoalert_vehicle", $vehicle_device);
		$this->db->where("geoalert_time >=", $sdate);
        $this->db->where("geoalert_time <=", $edate);
		$this->db->join("geofence", "geofence_id = geoalert_geofence", "leftouter");
		$this->db->where("geofence_name", $ritase_name);
		$q = $this->db->get("geofence_alert");
		$rows = $q->result();

        //print_r($rows);exit;

		$this->db->cache_delete_all();

		for ($i=0;$i<count($rows);$i++)
		{
			$rows[$i]->geoalert_time_t = dbmaketime($rows[$i]->geoalert_time);
		}

		$params["data"]=$rows;
		$params["start_date"]=$startdate;
		$params["end_date"]=$enddate;

		$html = $this->load->view("ritase/ritase_report", $params, true);

		$callback["error"] = false;
		$callback["html"] = $html;

		echo json_encode($callback);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
