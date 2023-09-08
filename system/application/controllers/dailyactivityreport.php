<?php
include "base.php";

  class Dailyactivityreport extends Base {
  var $otherdb;

  function Dailyactivityreport()
  {
    parent::Base();
    $this->load->model("gpsmodel");
    $this->load->model("vehiclemodel");
    $this->load->model("configmodel");
    $this->load->model("dashboardmodel");
    $this->load->helper('common_helper');
    $this->load->model("m_dailyactivity");
  }

  function index(){
    if (! isset($this->sess->user_type))
		{
			redirect(base_url());
		}

    $this->db->select("vehicle.*, user_name");
    $this->db->order_by("vehicle_no", "asc");
    $this->db->where("vehicle_status <>", 3);
    $this->db->where("vehicle_type <>", "TJAM");

    if ($this->sess->user_type == 2)
    {
      $this->db->where("vehicle_user_id", $this->sess->user_id);
      $this->db->or_where("vehicle_company", $this->sess->user_company);
      $this->db->where("vehicle_active_date2 >=", date("Ymd"));
    }
    else
    if ($this->sess->user_type == 3)
    {
      $this->db->where("user_agent", $this->sess->user_agent);
    }
    //tambahan, user group yg open playback report
    if ($this->sess->user_group <> 0)
    {
      $this->db->where("vehicle_group", $this->sess->user_group);
    }

    $this->db->join("user", "vehicle_user_id = user_id", "left outer");
    $q = $this->db->get("vehicle");

    if ($q->num_rows() == 0)
    {
      redirect(base_url());
    }

    $rows                           = $q->result();
    $rows_company                   = $this->get_company_bylevel();

    // echo "<pre>";
    // var_dump($this->sess->user_id);die();
    // // var_dump($this->config->item("user_view_customreport"));die();
    // echo "<pre>";

    $this->params["vehicles"]       = $rows;
    $this->params["rcompany"]       = $rows_company;
    $this->params['code_view_menu'] = "report";

    $this->params["header"] = $this->load->view('dashboard/header', $this->params, true);
    $this->params["sidebar"] = $this->load->view('dashboard/sidebar', $this->params, true);
    $this->params["content"] = $this->load->view('dashboard/report/v_dailyactivity', $this->params, true);
    $this->load->view("dashboard/template_dashboard_report", $this->params);
  }

  function searchactivity(){
    ini_set('display_errors', 1);
    if (! isset($this->sess->user_type))
    {
      redirect(base_url());
    }

    $vehicle         = $this->input->post('vehicle');
    $startdatepost   = $this->input->post('startdate');
    $shour           = $this->input->post('shour').":00";
    $enddatepost     = $this->input->post('enddate');
    $ehour           = $this->input->post('ehour').":59";
    $parameter       = $this->input->post('stopduration');
    $parametersecond = $parameter * 60;


    $startdate = $startdatepost." ".$shour;
    $enddate   = $enddatepost." ".$ehour;

    // echo "<pre>";
    // var_dump($this->sess->user_id);die();
    // echo "<pre>";

    // $vehicle         = "58042081136707@GT08S";
    // $parameter       = 15;
    // $parametersecond = $parameter * 60;
    // $startdate       = date("2021-03-23 00:00:00");
    // $enddate         = date("2021-03-23 23:59:59");

    if ($startdate == "") {
      $startdate    = date("Y-m-d 00:00:00", strtotime("yesterday"));
      $datefilename = date("Ymd", strtotime("yesterday"));
      $month        = date("F", strtotime("yesterday"));
      $year         = date("Y", strtotime("yesterday"));
    }

    if ($startdate != "")
    {
      $datefilename = date("Ymd", strtotime($startdate));
      $startdate    = date("Y-m-d 00:00:00", strtotime($startdate));
      $month        = date("F", strtotime($startdate));
      $year         = date("Y", strtotime($startdate));
    }

    if ($enddate != "")
    {
      $enddate = date("Y-m-d 23:59:59", strtotime($enddate));
    }

    if ($enddate == "") {
      $enddate = date("Y-m-d 23:59:59", strtotime("yesterday"));
    }

    $report              = "operasional_";
    $reportinsert        = "dailyactivity_";
    $reportdailyactivity = "dailyactivityreport_";

    switch ($month)
    {
      case "January":
            $dbtable  = $report."januari_".$year;
            $dbtable2 = $reportinsert."januari_".$year;
            $dbtable3 = $reportdailyactivity."januari_".$year;
      break;
      case "February":
            $dbtable  = $report."februari_".$year;
            $dbtable2 = $reportinsert."februari_".$year;
            $dbtable3 = $reportdailyactivity."februari_".$year;
      break;
      case "March":
            $dbtable = $report."maret_".$year;
            $dbtable2 = $reportinsert."maret_".$year;
            $dbtable3 = $reportdailyactivity."maret_".$year;
      break;
      case "April":
            $dbtable = $report."april_".$year;
            $dbtable2 = $reportinsert."april_".$year;
            $dbtable3 = $reportdailyactivity."april_".$year;
      break;
      case "May":
            $dbtable = $report."mei_".$year;
            $dbtable2 = $reportinsert."mei_".$year;
            $dbtable3 = $reportdailyactivity."mei_".$year;
      break;
      case "June":
            $dbtable = $report."juni_".$year;
            $dbtable2 = $reportinsert."juni_".$year;
            $dbtable3 = $reportdailyactivity."juni_".$year;
      break;
      case "July":
            $dbtable = $report."juli_".$year;
            $dbtable2 = $reportinsert."juli_".$year;
            $dbtable3 = $reportdailyactivity."juli_".$year;
      break;
      case "August":
            $dbtable = $report."agustus_".$year;
            $dbtable2 = $reportinsert."agustus_".$year;
            $dbtable3 = $reportdailyactivity."agustus_".$year;
      break;
      case "September":
            $dbtable = $report."september_".$year;
            $dbtable2 = $reportinsert."september_".$year;
            $dbtable3 = $reportdailyactivity."september_".$year;
      break;
      case "October":
            $dbtable = $report."oktober_".$year;
            $dbtable2 = $reportinsert."oktober_".$year;
            $dbtable3 = $reportdailyactivity."oktober_".$year;
      break;
      case "November":
            $dbtable = $report."november_".$year;
            $dbtable2 = $reportinsert."november_".$year;
            $dbtable3 = $reportdailyactivity."november_".$year;
      break;
      case "December":
            $dbtable = $report."desember_".$year;
            $dbtable2 = $reportinsert."desember_".$year;
            $dbtable3 = $reportdailyactivity."desember_".$year;
      break;
    }

    // GET REPORTDAILY BY DATE & VEHICLEDEVICE
    $datafix  = array();
    $sumArray = array();
    $vehiclerow  = $this->m_dailyactivity->getvehicledata($vehicle);
    $reportdaily = $this->m_dailyactivity->getreport($dbtable3, $vehicle, $startdate, $enddate);
      if (sizeof($reportdaily) > 0) {
        for ($i=0; $i < sizeof($reportdaily); $i++) {
          $starttimefix      = $reportdaily[$i]['dailyactivity_from_origin'];
          $endtimefix        = $reportdaily[$i]['dailyactivity_backto_origin'];
          $dataperjalanan   = $this->m_dailyactivity->getdataperjalanan($dbtable2, $vehicle, $starttimefix, $endtimefix, $parametersecond);
            if (sizeof($dataperjalanan) > 0) {
                $totalwaktu_drop_barang = floor($dataperjalanan[0]['totalwaktudropbarang']/60);
                $total_waktu_tempuh     = floor($reportdaily[$i]['dailyactivity_waktu_trip'] - $totalwaktu_drop_barang);
                $totalkmtempuh          = $this->m_dailyactivity->getkmtempuhtotal($dbtable, $vehicle, $reportdaily[$i]['dailyactivity_from_origin'], $reportdaily[$i]['dailyactivity_backto_origin']);
              // $totalwaktu_drop_barang = $parameter * sizeof($dataperjalanan);
              array_push($datafix, array(
                "periode"                => date("d-m-Y", strtotime($startdatepost)).' s/d '.date("d-m-Y", strtotime($enddatepost)),
                "vehicle_device"         => $reportdaily[$i]['dailyactivity_vehicle_device'],
                "vehicle_no"             => $vehiclerow[0]['vehicle_no'],
                "vehicle_name"           => $reportdaily[$i]['dailyactivity_vehicle_name'],
                "from_origin"            => $reportdaily[$i]['dailyactivity_from_origin'],
                "backto_origin"          => $reportdaily[$i]['dailyactivity_backto_origin'],
                "waktu_trip"             => $reportdaily[$i]['dailyactivity_waktu_trip'],
                "jumlah_drop_barang"     => $dataperjalanan[0]['jumlahdropbarang'],
                "totalwaktu_drop_barang" => $totalwaktu_drop_barang,
                "totalwaktu_tempuh"      => $total_waktu_tempuh,
                "km_tempuh"              => number_format($totalkmtempuh[0]['totalkmtempuh'], 2),
                "km_perjam"              => round(number_format($totalkmtempuh[0]['totalkmtempuh'], 2) / ($total_waktu_tempuh/60)),
              ));
            }
        }
      }
      // $dbtable2.$vehicle.$starttime.$endtime
    // echo "<pre>";
    // var_dump($vehiclerow);die();
    // echo "<pre>";
    $params['data']    = $datafix;
		$html              = $this->load->view("dashboard/report/v_dailyactivity_result", $params, true);
		$callback['error'] = false;
		$callback['html']  = $html;
		echo json_encode($callback);
  }

  function get_company_bylevel(){
		if (! isset($this->sess->user_type))
		{
			redirect(base_url());
		}
		$this->db->order_by("company_name","asc");
		/*if($this->sess->user_level == "1"){
			$this->db->where("company_created_by", $this->sess->user_id);
		}*/
		$this->db->where("company_created_by", $this->sess->user_id);
		$this->db->where("company_flag", 0);
		$qd = $this->db->get("company");
		$rd = $qd->result();

		return $rd;
	}

}
