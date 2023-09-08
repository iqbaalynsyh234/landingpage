<?php
include "base.php";

class Share extends Base {
	var $period1;
	var $period2;
	var $tblhist;
	var $tblinfohist;
	var $otherdb;

	function Share()
	{
		parent::Base();
    // DASHBOARD START
    $this->load->helper('common_helper');
		$this->load->helper('email');
		$this->load->library('email');
		$this->load->model("dashboardmodel");
		$this->load->helper('common');
    // DASHBOARD END
		$this->load->model("gpsmodel");
		$this->load->model("vehiclemodel");
		$this->load->model("configmodel");
		$this->load->model("historymodel");
		$this->load->model("m_projectschedule");
    $this->load->model("smsmodel");
    $this->load->model("m_poipoolmaster");
		$this->load->model("m_joborder");
	}

  function locationreal($imei){
    print_r("share link UNDER CONSTRUCTION");exit();
  }

	function realtime($orderid){
    // $imei = "002100000816";
		$urlexplode = explode("989812", $orderid);
		$joborderdata = $this->m_joborder->checkinjoborder($urlexplode[0]);
		// echo "<pre>";
		// var_dump($urlexplode);die();
		// echo "<pre>";
			if (isset($joborderdata[0])) {
				if ($joborderdata[0]['order_status'] == 2) {
					$data['msg']     = "Pengiriman sudah selesai. Terima kasih sudah mempercayai kami.";
					$data['codemsg'] = "1";
					$data['code']    = "Completed";
					$this->load->view("dashboard/joborder/expiredlinkview", $data);
				}else {
					//GET DATA FROM DB
					$this->db     = $this->load->database("default", true);
					$this->db->select("*");
					// $this->db->where("vehicle_user_id", $user_id_fix);
					// $this->db->where("vehicle_status <>", 3);
			    $this->db->where("vehicle_device ", $joborderdata[0]['order_vehicle_device']);
					$q       = $this->db->get("vehicle");
					$result  = $q->result_array();

			    $currpposition = $this->lastinfo($result[0]['vehicle_device']);

			    $datafix = array();
			    for ($i=0; $i < sizeof($currpposition); $i++) {

			      array_push($datafix, array(
			        "vehicle_no"             => $currpposition->vehicle_no,
			        "vehicle_name"           => $currpposition->vehicle_name,
							"gps_latitude_real_fmt"  => $currpposition->gps->gps_latitude_real_fmt,
							"gps_longitude_real_fmt" => $currpposition->gps->gps_longitude_real_fmt,
							"gps_speed_fmt"          => $currpposition->gps->gps_speed_fmt,
							"display_name"           => $currpposition->gps->georeverse->display_name,
							"customername"           => $joborderdata[0]['order_jobordercust_name'],
							"customercoordinate"     => $joborderdata[0]['order_jobordercust_coordinate']
			      ));
			    }

			    // echo "<pre>";
			    // var_dump($datafix);die();
			    // echo "<pre>";

					$data['datafix'] = $datafix;
					$this->load->view("dashboard/joborder/sharelinkview", $data);
				}
			}else {
				$data['msg']     = "URL yang anda masukan salah. Silahkan hubungi Administrator kami.";
				$data['codemsg'] = "2";
				$data['code']    = "Wrong URL";
				$this->load->view("dashboard/joborder/expiredlinkview", $data);
			}
  }

	function directlink($unique){
    // $imei = "002100000816";
		$urlexplode = explode("989812", $unique);
		$joborderdata = $this->m_joborder->checkinjoborder2($urlexplode[1]);
		// echo "<pre>";
		// var_dump($joborderdata);die();
		// echo "<pre>";
			if (isset($joborderdata[0])) {
				if ($joborderdata[0]['order_status'] == 2) {
					$data['msg']     = "Pengiriman sudah selesai. Terima kasih sudah mempercayai kami.";
					$data['codemsg'] = "1";
					$data['code']    = "Completed";
					$this->load->view("dashboard/joborder/expiredlinkview", $data);
				}else {
					//GET DATA FROM DB
					$this->db     = $this->load->database("default", true);
					$this->db->select("*");
					// $this->db->where("vehicle_user_id", $user_id_fix);
					// $this->db->where("vehicle_status <>", 3);
			    $this->db->where("vehicle_device ", $joborderdata[0]['order_vehicle_device']);
					$q       = $this->db->get("vehicle");
					$result  = $q->result_array();

			    $currpposition = $this->lastinfo($result[0]['vehicle_device']);

			    $datafix = array();
			    for ($i=0; $i < sizeof($currpposition); $i++) {

			      array_push($datafix, array(
			        "vehicle_no"             => $currpposition->vehicle_no,
			        "vehicle_name"           => $currpposition->vehicle_name,
							"gps_latitude_real_fmt"  => $currpposition->gps->gps_latitude_real_fmt,
							"gps_longitude_real_fmt" => $currpposition->gps->gps_longitude_real_fmt,
							"gps_speed_fmt"          => $currpposition->gps->gps_speed_fmt,
							"display_name"           => $currpposition->gps->georeverse->display_name,
							"customername"           => $joborderdata[0]['order_jobordercust_name'],
							"customercoordinate"     => $joborderdata[0]['order_jobordercust_coordinate'],
							"nourut"           			 => $joborderdata[0]['order_nourut'],
							"sharelink"        			 => $joborderdata[0]['order_sharelink']
			      ));
			    }

			    // echo "<pre>";
			    // var_dump($datafix);die();
			    // echo "<pre>";

					$data['datafix'] = $datafix;
					$this->load->view("dashboard/joborder/sharelinkview", $data);
				}
			}else {
				$data['msg']     = "URL yang anda masukan salah. Silahkan hubungi Administrator kami.";
				$data['codemsg'] = "2";
				$data['code']    = "Wrong URL";
				$this->load->view("dashboard/joborder/expiredlinkview", $data);
			}
  }

	function bynopol($unique){
		// $imei = "002100000816";
		$vehicleid         = explode("989812", $unique);
		$vehicleidfix      = $vehicleid[0];
		$year              = explode("1010", $unique);
		$year2             = explode("989812", $year[0]);
		$yearfix           = $year2[1];
		$monthanddate      = explode("3113", $unique);
		$monthanddate2     = explode("1010", $monthanddate[0]);
		$monthfix 				 = $monthanddate2[1];
		$datefix           = $monthanddate[1];
		$startdatetime     = $yearfix.'-'.$monthfix.'-'.$datefix." 00:00:00";
		$enddatetime       = $yearfix.'-'.$monthfix.'-'.$datefix." 23:59:59";

		$joborderdata 		 = $this->m_joborder->checkinjoborder3($vehicleid[0], $startdatetime, $enddatetime);

		$datacustomer = array();
		for ($i=0; $i < sizeof($joborderdata); $i++) {
			$coord = explode(",", $joborderdata[$i]['order_jobordercust_coordinate']);
			array_push($datacustomer, array(
				"customername"     => $joborderdata[$i]['order_jobordercust_name'],
				"customercoordlat" => $coord[0],
				"customercoordlng" => $coord[1],
				"status"           => $joborderdata[$i]['order_status'],
				"nourut"           => $joborderdata[$i]['order_nourut']
			));
		}

		$this->db     = $this->load->database("default", true);
		$this->db->select("*");
		// $this->db->where("vehicle_user_id", $user_id_fix);
		// $this->db->where("vehicle_status <>", 3);
		$this->db->where("vehicle_device", $joborderdata[0]['order_vehicle_device']);
		$q       = $this->db->get("vehicle");
		$result  = $q->result_array();

		$currpposition       = $this->lastinfo($result[0]['vehicle_device']);
		$datacurrentposition = array();
		for ($j=0; $j < sizeof($currpposition); $j++) {
			array_push($datacurrentposition, array(
				"vehicle_no"             => $currpposition->vehicle_no,
				"vehicle_name"           => $currpposition->vehicle_name,
				"gps_latitude_real_fmt"  => $currpposition->gps->gps_latitude_real_fmt,
				"gps_longitude_real_fmt" => $currpposition->gps->gps_longitude_real_fmt,
				"gps_speed_fmt"          => $currpposition->gps->gps_speed_fmt,
				"display_name"           => $currpposition->gps->georeverse->display_name
			));
		}

		// echo "<pre>";
		// var_dump($datacurrentposition);die();
		// echo "<pre>";

		$data['datacustomer']        = $datacustomer;
		$data['datacurrentposition'] = $datacurrentposition;
		$this->load->view("dashboard/joborder/sharelinkviewinternal", $data);
	}

  function location($imei){
    // $imei = "002100000816";

		$user_id_fix     = 981;
		//GET DATA FROM DB
		$this->db     = $this->load->database("default", true);
		$this->db->select("*");
		$this->db->where("vehicle_user_id", $user_id_fix);
		// $this->db->where("vehicle_status <>", 3);
    $this->db->where("vehicle_device like '%$imei%'");
		$q       = $this->db->get("vehicle");
		$result  = $q->result_array();

    $currpposition = $this->lastinfo($result[0]['vehicle_device']);

    $datafix = array();
    for ($i=0; $i < sizeof($currpposition); $i++) {

      array_push($datafix, array(
        "vehicle_no"         => $currpposition->vehicle_no,
        "vehicle_name"       => $currpposition->vehicle_name,
				"gps_latitude_real_fmt"  => $currpposition->gps->gps_latitude_real_fmt,
				"gps_longitude_real_fmt" => $currpposition->gps->gps_longitude_real_fmt,
				"gps_speed_fmt"          => $currpposition->gps->gps_speed_fmt,
				"display_name"           => $currpposition->gps->georeverse->display_name,
      ));
    }

    // echo "<pre>";
    // var_dump($datafix);die();
    // echo "<pre>";

    $data['datafix'] = $datafix;

    $this->load->view("kpa/sharelinkview", $data);

  }

  function lastinfo($devicefix)
	{
		$device        = $devicefix;
		$user_id_fix   = 981;

		if (strpos($device, '@') !== false) {
			$device   = $device;
		}else {
			$insearch      = isset($_POST['insearch']) ? $_POST['insearch']: "";

			if ($insearch == 1) {
				$mastervehicle = $this->m_poipoolmaster->searchmasterdata("webtracking_vehicle", $insearch);
				$device = $mastervehicle[0]['vehicle_device'];
			}else {
				$this->db->where("vehicle_status <>", 3);
				$this->db->where("vehicle_device", $device);
				$query  = $this->db->get("vehicle");
				$result = $query->result_array();
				$device = $result[0]['vehicle_device'];
			}
		}
		$lasttime = isset($_POST['lasttime']) ? $_POST['lasttime']: 0;
		// print_r($mastervehicle);exit();

		$usertype = 2;
		// if ($usertype == 2)
		// {
		// 	$vehicleids = $this->vehiclemodel->getVehicleIds();
		// }

		$this->db->where("vehicle_status <>", 3);
		$this->db->where("vehicle_device", $device);
		$this->db->join("user", "vehicle_user_id = user_id");
		$this->db->join("bank", "user_agent = bank_agent", "left outer");
		$q = $this->db->get("vehicle");
		// print_r($q->result_array());exit();

		// echo "<pre>";
		// var_dump($q->result_array());die();
		// echo "<pre>";

		$row = $q->row();

		// cek expire

		if ($row->vehicle_active_date2 && ($row->vehicle_active_date2 < date("Ymd")))
		{
			$row->vehicle_active_date2_fmt = inttodate($row->vehicle_active_date2);
			// print_r("asd");exit();
			$json = json_decode($row->vehicle_info);

				echo json_encode(array("info"=>"expired", "vehicle"=>$row));
				return;
		}

		$arr = explode("@", $device);

		$devices[0] = (count($arr) > 0) ? $arr[0] : "";
		$devices[1] = (count($arr) > 1) ? $arr[1] : "";

		$row->gps = $this->gpsmodel->GetLastInfo($devices[0], $devices[1], true, false, $lasttime, $row->vehicle_type);
		if ($this->gpsmodel->fromsocket)
		{
			$datainfo = $this->gpsmodel->datainfo;
			$fromsocket = $this->gpsmodel->fromsocket;
		}


		$gtps = $this->config->item("vehicle_gtp");

		if (! in_array(strtoupper($row->vehicle_type), $gtps) && $row->vehicle_type != "TK309PTO" && $row->vehicle_type != "GT06PTO")
		{
			$row->status = "-";

			$taktif = dbintmaketime($row->vehicle_active_date, 0);

			$json = json_decode($row->vehicle_info);
			if (isset($json->sisapulsa))
			{
				if (strlen($json->masaaktif) == 6)
				{
					$taktif = dbintmaketime1($json->masaaktif, 0);
				}
				else
				{
					$taktif = dbintmaketime2($json->masaaktif, 0);
				}

				if (date("Y", $taktif) < 2000)
				{
					$row->pulse = false;
				}
				else
				{
					$row->pulse = sprintf("Rp %s", number_format($json->sisapulsa, 0, "", "."));
					$row->masaaktif = date("d/m/Y", $taktif);
				}
			}
			else
			{
				$row->pulse = false;
			}

		}
		else
		{
			if (isset($row->gps) && $row->gps && date("Ymd", $row->gps->gps_timestamp) >= date("Ymd"))
			{
				if (! isset($fromsocket))
				{
					$tables = $this->gpsmodel->getTable($row);
					$this->db = $this->load->database($tables["dbname"], TRUE);
				}

			}
			else
			if (! isset($fromsocket))
			{
				$tables['info'] = sprintf("%s@%s_info", strtolower($devices[0]), strtolower($devices[1]));
				$istbl_history = $this->config->item("dbhistory_default");
				if($this->config->item("is_dbhistory") == 1)
				{
					$istbl_history = $row->vehicle_dbhistory_name;
				}
				$this->db = $this->load->database($istbl_history, TRUE);
			}

			// ambil informasi di gps_info

			if (! isset($datainfo))
			{
				$this->db->order_by("gps_info_time", "DESC");
				$this->db->where("gps_info_device", $device);
				$q = $this->db->get($tables['info'], 1, 0);
				$totalinfo = $q->num_rows();
				if ($totalinfo)
				{
					$rowinfo = $q->row();
				}
			}
			else
			{
				$rowinfo = $datainfo;
				$totalinfo = 1;
			}

			if ($totalinfo == 0)
			{
				$row->status = "-";
				$row->status1 = false;
				$row->status2 = false;
				$row->status3 = false;
				$row->pulse = "-";
			}
			else
			{
				$ioport = $rowinfo->gps_info_io_port;

				$row->status3 = ((strlen($ioport) > 1) && ($ioport[1] == 1)); // opened/closed
				$row->status2 = ((strlen($ioport) > 3) && ($ioport[3] == 1)); // release/hold
				if(isset($devices[1]) && ($devices[1] == "GT06" || $devices[1] == "A13" || $devices[1] == "TK309" || $devices[1] == "TK309PTO" || $devices[1] == "GT06PTO"))
				{
					if(isset($row->gps->gps_speed_fmt) && $row->gps->gps_speed_fmt > 0)
					{
						$row->status1 = true;
					}
					else
					{
						$row->status1 = ((strlen($ioport) > 4) && ($ioport[4] == 1)); // on/off
					}
				}
				else
				{
					$row->status1 = ((strlen($ioport) > 4) && ($ioport[4] == 1)); // on/off
				}
				$row->status = $row->status2 || $row->status1 || $row->status3;

				$pulses = $this->config->item("vehicle_pulse");
				if (! in_array(strtoupper($row->vehicle_type), $pulses))
				{
					$json = json_decode($row->vehicle_info);
					if (isset($json->sisapulsa))
					{
						if (strlen($json->masaaktif) == 6)
						{
							$taktif = dbintmaketime1($json->masaaktif, 0);
						}
						else
						{
							$taktif = dbintmaketime2($json->masaaktif, 0);
						}

						if (date("Y", $taktif) < 2000)
						{
							$row->pulse = false;
						}
						else
						{
							$row->pulse = sprintf("Rp %s", number_format($json->sisapulsa, 0, "", "."));
							$row->masaaktif = date("d/m/Y", $taktif);
						}
					}
					else
					{
						$row->pulse = false;
					}
				}
				else
				{
					//$rowinfo->gps_info_ad_input = "00B0742177";

					$pulsa = number_format(hexdec(substr($rowinfo->gps_info_ad_input, 0, 5)), 0, "", ".");
					$aktif = hexdec(substr($rowinfo->gps_info_ad_input, 5));

					$taktif = dbintmaketime1($aktif, 0);

					if (date("Y", $taktif) < 2000)
					{
						$row->pulse = false;
					}
					else
					{
						$row->pulse = sprintf("Rp %s", $pulsa);
						$row->masaaktif = date("d/m/Y", $taktif);
					}
				}

				$fuels = $this->config->item("vehicle_fuel");
				if (! in_array(strtoupper($row->vehicle_type), $fuels))
				{
					$row->fuel = false;
				}
				else
				{
					$row->fuel = "-";
					if($rowinfo->gps_info_ad_input != ""){
						if($rowinfo->gps_info_ad_input != 'FFFFFF' || $rowinfo->gps_info_ad_input != '999999' || $rowinfo->gps_info_ad_input != 'YYYYYY'){
							$fuel_1 = hexdec(substr($rowinfo->gps_info_ad_input, 0, 4));
							$fuel_2 = (hexdec(substr($rowinfo->gps_info_ad_input, 0, 2))) * 0.1;

							$fuel = $fuel_1 + $fuel_2;
							//print_r($fuel);exit;
							//Deteksi Fuel Capacity

							$this->db = $this->load->database("default", TRUE);

							if ($row->vehicle_fuel_capacity != 0 && $row->vehicle_fuel_capacity == 300)
							{

								$sql = "SELECT * FROM (
										(
											SELECT *
											FROM `webtracking_fuel`
											WHERE `fuel_tank_capacity` = ". $row->vehicle_fuel_capacity ."
											AND `fuel_led_resistance` <= ". $fuel ."
											ORDER BY fuel_led_resistance DESC
											LIMIT 1
										)
									) tbldummy";
							}
							else
							{
								$sql = "SELECT * FROM (
										(
											SELECT *
											FROM `webtracking_fuel`
											WHERE `fuel_tank_capacity` = ". $row->vehicle_fuel_capacity ."
											AND `fuel_led_resistance` >= ". $fuel ."
											ORDER BY fuel_led_resistance ASC
											LIMIT 1
										) UNION (
											SELECT *
											FROM `webtracking_fuel`
											WHERE `fuel_tank_capacity` = ". $row->vehicle_fuel_capacity ."
											AND `fuel_led_resistance` <= ". $fuel ."
											ORDER BY fuel_led_resistance DESC
											LIMIT 1
										)
									) tbldummy";
							}
							$qfuel = $this->db->query($sql);
							if ($qfuel->num_rows() > 0){
   								$rfuel = $qfuel->result();

								if ($qfuel->num_rows() == 1){
									$row->blink = false;
									$row->fuel_scale = $rfuel[0]->fuel_gas_scale * 10;
									$row->fuel = $rfuel[0]->fuel_volume . "L";
								}else{
									$row->blink = true;
									$row->fuel_scale = $rfuel[1]->fuel_gas_scale * 10;
									$row->fuel = $rfuel[0]->fuel_volume . "L - " . $rfuel[1]->fuel_volume . "L";
								}
							}
						}
					}

				}
				$row->totalodometer = round(($rowinfo->gps_info_distance+$row->vehicle_odometer*1000)/1000);
				//$row->totalodometer = str_split($strodometer);
			}



			//since at ssi

			/*if($this->sess->user_id == 1933){

				$dev = explode("@", $device);

				$json = json_decode($row->vehicle_info);
				if ($row->vehicle_info)
				{
					if (isset($json->vehicle_ip) && isset($json->vehicle_port))
					{
						$databases = $this->config->item('databases');

						if (isset($databases[$json->vehicle_ip][$json->vehicle_port]))
						{
							$database = $databases[$json->vehicle_ip][$json->vehicle_port];
							$tablegps = $this->config->item("external_gpstable");
							$tablegpsinfo = $this->config->item("external_gpsinfotable");
							$tablegpshist = $this->config->item("external_gpstable_history");
							$this->db = $this->load->database($database, TRUE);
						}
					}

					if(isset($json->vehicle_ws))
					{
						$istbl_history = $this->config->item("dbhistory_default");
						if($this->config->item("is_dbhistory") == 1)
						{
							$istbl_history = $row->vehicle_dbhistory_name;
						}
						$tablegps = strtolower($dev[0]."@".$dev[1]."_gps");
						$tablegpsinfo = strtolower($dev[0]."@".$dev[1]."_info");
						$this->db = $this->load->database($istbl_history, TRUE);
					}

					if(! isset($tablegps))
					{
						$table_hist = $this->config->item("table_hist");
						$tablegps = $this->gpsmodel->getGPSTable($vehicle->vehicle_type);
						$tablegpshist = $table_hist[strtoupper($vehicle->vehicle_type)];
					}

				}

				$this->db->select("gps_info_io_port, gps_info_time, gps_info_device, gps_speed");
				$this->db->order_by("gps_info_time", "asc");
				$this->db->where("gps_info_device", $device);
				$this->db->join($tablegps, "gps_info_time = gps_time AND gps_info_device = CONCAT(gps_name,'@',gps_host)");
				$q = $this->db->get($tablegpsinfo);
				$rowstartoff = $q->result();


				for ($i=0;$i<count($rowstartoff);$i++)
				{
					if ($rowstartoff[$i]->gps_info_io_port == "0000000000")
					{
						if (isset($rowstartoff[$i-1]->gps_info_io_port))
						{
							if ($rowstartoff[$i-1]->gps_info_io_port == "0000000000"){}
							else if ($rowstartoff[$i-1]->gps_info_io_port == "0000100000")
							{
								$startoff_ssi = $rowstartoff[$i]->gps_info_time;
							}
							else{}
						}
					}

					if($rowstartoff[$i]->gps_info_io_port == "0000100000" && $rowstartoff[$i]->gps_speed == 0)
					{
						if (isset($rowstartoff[$i-1]->gps_info_io_port))
						{
							if ($rowstartoff[$i-1]->gps_info_io_port == "0000100000" && $rowstartoff[$i]->gps_speed == 0){}
							else if ($rowstartoff[$i-1]->gps_info_io_port == "0000000000")
							{
								$starton_ssi = $rowstartoff[$i]->gps_info_time;
							}
							else{}
						}
					}
				}


				if(isset($starton_ssi))
				{
					$ontime = date("Y-m-d H:i:s", strtotime("+7 hour", strtotime($starton_ssi)));
					$timenow = date("Y-m-d H:i:s");
					$oncalculation = get_time_difference($ontime, $timenow);

					$showon_ssi = " ".$ontime;
					$showdurationon_ssi = " "." ";
					if($oncalculation[0]!=0){
						$showdurationon_ssi .= $oncalculation[0] ." Day ";
					}
					if($oncalculation[1]!=0){
						$showdurationon_ssi .= $oncalculation[1] ." Hour ";
					}
					if($oncalculation[2]!=0){
						$showdurationon_ssi .= $oncalculation[2] ." Min ";
					}

					if($showdurationon_ssi == " "." "){
						$showdurationon_ssi .= "0 Min ";
					}

				}

				if(isset($startoff_ssi))
				{
					$offtime = date("Y-m-d H:i:s", strtotime("+7 hour", strtotime($startoff_ssi)));
					$timenow = date("Y-m-d H:i:s");
					$offcalculation = get_time_difference($offtime, $timenow);
					$showoff_ssi = " ".$offtime;
					$showduration_ssi = " "." ";
					if($offcalculation[0]!=0){
						$showduration_ssi .= $offcalculation[0] ." Day ";
					}
					if($offcalculation[1]!=0){
						$showduration_ssi .= $offcalculation[1] ." Hour ";
					}
					if($offcalculation[2]!=0){
						$showduration_ssi .= $offcalculation[2] ." Min ";
					}

					if($showduration_ssi == " "." "){
						$showduration_ssi .= "0 Min ";
					}

				}

			} */


				/*
				//Get Start Off
				$this->db->order_by("gps_info_time", "asc");
				$this->db->where("gps_info_device", $device);
				$q = $this->db->get($tables['info']);

				$rowstartoff = $q->result();
				//print_r($rowstartoff);exit;

				for ($i=0;$i<count($rowstartoff);$i++)
				{
					if ($rowstartoff[$i]->gps_info_io_port == "0000000000")
					{
						if (isset($rowstartoff[$i-1]->gps_info_io_port))
						{
							if ($rowstartoff[$i-1]->gps_info_io_port == "0000000000"){}
							else if ($rowstartoff[$i-1]->gps_info_io_port == "0000100000")
							{
								$startoff = $rowstartoff[$i]->gps_info_time;
							}
							else{}
						}
					}

					if ($rowstartoff[$i]->gps_info_io_port == "0000100000")
					{
						if (isset($rowstartoff[$i-1]->gps_info_io_port))
						{
							if ($rowstartoff[$i-1]->gps_info_io_port == "0000100000"){}
							else if ($rowstartoff[$i-1]->gps_info_io_port == "0000000000")
							{
								$starton = $rowstartoff[$i]->gps_info_time;
							}
							else{}
						}
					}
				}

				if (isset($startoff))
				{
					$offtime = date("Y-m-d H:i:s", strtotime("+7 hour", strtotime($startoff)));
					$timenow = date("Y-m-d H:i:s");
					$offcalculation = get_time_difference($offtime, $timenow);

					$showoff = " ".$offtime;
					$showduration = " "." ";
					if($offcalculation[0]!=0){
						$showduration .= $offcalculation[0] ." Day ";
					}
					if($offcalculation[1]!=0){
						$showduration .= $offcalculation[1] ." Hour ";
					}
					if($offcalculation[2]!=0){
						$showduration .= $offcalculation[2] ." Min ";
					}

					if($showduration == " "." "){
						$showduration .= "0 Min ";
					}
				}

				if (isset($starton))
				{
					$ontime = date("Y-m-d H:i:s", strtotime("+7 hour", strtotime($starton)));
					$timenow = date("Y-m-d H:i:s");
					$oncalculation = get_time_difference($ontime, $timenow);

					$showon = " ".$ontime;
					$showdurationon = " "." ";
					if($oncalculation[0]!=0){
						$showdurationon .= $oncalculation[0] ." Day ";
					}
					if($oncalculation[1]!=0){
						$showdurationon .= $oncalculation[1] ." Hour ";
					}
					if($oncalculation[2]!=0){
						$showdurationon .= $oncalculation[2] ." Min ";
					}

					if($showdurationon == " "." "){
						$showdurationon .= "0 Min ";
					}
				}
				*/
		}

		// echo "<pre>";
		// var_dump($tables["dbname"]);die();
		// echo "<pre>";


		$t = dbintmaketime($row->vehicle_active_date1, 0);
		$row->vehicle_active_date1_fmt = date("M, jS Y", $t);

		$t = dbintmaketime($row->vehicle_active_date2, 0);
		$row->vehicle_active_date2_fmt = date("M, jS Y", $t);

		$arr = explode("@", $device);

		$devices[0] = (count($arr) > 0) ? $arr[0] : "";
		$devices[1] = (count($arr) > 1) ? $arr[1] : "";


		$row->vehicle_device_name = $devices[0];
		$row->vehicle_device_host = $devices[1];

		$params["vehicle"] = $row;

		//$row->gps = $this->gpsmodel->GetLastInfo($devices[0], $devices[1], true, false, $lasttime, $row->vehicle_type);

		if (! $row->gps)
		{
			echo json_encode(array("info"=>"", "vehicle"=>$row));
			return;
		}

		$delayresatrt = mktime() - $row->gps->gps_timestamp;
		$kdelayrestart = $this->config->item("restart_delay")*60;


		if (true)
		{
			$restart = $this->smsmodel->restart($row->vehicle_type, $row->vehicle_operator);
			$row->restartcommand = $restart;
		}
		else
		{
			$row->restartcommand = "";
		}

		if (in_array(strtoupper($row->vehicle_type), $this->config->item("vehicle_T1")))
		{
			$row->checkpulsa = $this->smsmodel->checkpulse($row->vehicle_operator);
		}
		else
		{
			$row->checkpulsa = "";
		}




		//get geofence location
			if (!in_array(strtoupper($row->vehicle_type), $this->config->item("vehicle_others"))){
				$row->geofence_location = $this->getGeofence_location($row->gps->gps_longitude, $row->gps->gps_ew, $row->gps->gps_latitude, $row->gps->gps_ns, $row->vehicle_user_id);
			}
			else
			{
				$row->geofence_location = $this->getGeofence_location_others($row->gps->gps_longitude_real, $row->gps->gps_latitude_real, $row->vehicle_user_id);
			}

		//APP CO Reksa
		$app_co = $this->config->item("app_co");
		if ($app_co && $app_co == 1)
		{
			$row->destination = $this->getdestination($row->vehicle_device);
		}
		//end

		//RENTCAR
		$apprentcar = $this->config->item("rentcar_app");
		$appdosj = $this->config->item("app_dosj");
		$appfan = $this->config->item("fan_app");

		//untuk semua transporter company terdaftar
		$app_dosj_all = $this->config->item("app_dosj_all");

		//Balrich
		$appvehicle_detail = $this->config->item("vehicle_detail_app");

		if ($appvehicle_detail && $appvehicle_detail == 1)
		{
			$row->v_detail = $this->getvehicledetail($row->vehicle_device);
		}

		if ($row->vehicle_type == "T5DOOR" || $row->vehicle_type == "T5FAN" || $row->vehicle_type == "T5PTO")
		{
			$row->fan = $this->getFanStatus($row->gps->gps_msg_ori);
		}

		if($row->vehicle_type == "T5SUHU")
		{
			$row->suhu = $this->getSuhu($row->gps->gps_msg_ori);
		}

		if ($apprentcar && $apprentcar == 1)
		{
			$row->customer = $this->getcustomer($row->vehicle_no);
			$x = explode("," ,$row->customer);
			$y = $x[0];
			$row->tenant = $this->get_tenant($y);
			$params["tenant"] = $row->tenant;
			$params["customer"]= $row->customer;
			//print_r ($row->tenant);exit;
		}
		//END RENTCAR



		if (isset($appdosj) && ($appdosj == 1))
		{
			//Get Driver by schedule DO/SJ
			$row->driver = $this->getdriver_dosj($row->vehicle_device);
			$row->dosj = $this->get_dosj($row->vehicle_device);
		}
		else
		{
			$row->driver = $this->getdriver($row->vehicle_id);

		}



		//get driver rf id
		$apprfid = $this->config->item("app_driver_rdif");

		//dosj untuk semua transporter company terdaftar
		$usercompanykpa = 20;
		if (isset($app_dosj_all) && ($app_dosj_all == 1) && in_array(strtoupper($usercompanykpa), $this->config->item("company_view_dosj")))
		{
			//Get Driver by schedule DO/SJ
			$row->driver = $this->getdriver_dosj_all($row->vehicle_device);
			$row->dosj = $this->get_dosj_all($row->vehicle_device);
			//$row->customer_groups = $this->getcustomer_dosj_all($row->vehicle_device);
		}
		else
		{
			$row->driver = $this->getdriver($row->vehicle_id);
		}


		//Transporter Powerblock
		$app_powerblock = $this->config->item("app_powerblock");
		if(isset($app_powerblock) && $app_powerblock == 1){

			//$row->id_so = $this->get_so($row->vehicle_device);
			$row->no_sj = $this->get_new_sj($row->vehicle_device);
			$row->driver_sj = $this->get_new_driver($row->vehicle_device);

			/*print_r($row->id_sj);
			print_r($row->driver);
			exit();*/

		}

		// DATA PROJECT
		if (in_array(strtoupper($user_id_fix), $this->config->item("user_view_pto"))) {
				$row->dataproject = $this->getdataproject($row->vehicle_device);
				// print_r("masuk");exit();
		}

		$app_tag = $this->config->item("app_tag");
		if(isset($app_tag) && $app_tag == 1){
			$row->off_autocheck = $this->get_off_autocheck($row->vehicle_device);
		}

		//Transporter Tupperware
		$app_tupperware = $this->config->item("app_tupperware");

    $row->customer_groups = $this->getcustomer_groups($row->vehicle_group);
		$row->since_geofence_in = "";

		if ($row->vehicle_company != 0)
		{
			$row->company = $this->getCompany($row->vehicle_company);
			//print_r($row->company);exit;
		}
		else
		{
			$row->company = 0;
		}

		//INFO : Since At Geofence
		$app_sinceat = $this->config->item("since_at_geofence");
		if ($app_sinceat && $app_sinceat==1)
		{
			if(isset($row->geofence_location) && $row->geofence_location != "")
			{
				$row->since_geofence_in = $this->getInGeofence($row->vehicle_device, $row->geofence_location);
			}
		}


		//tcontinent
		$apptcontinent = $this->config->item("tcontinent_app");
		if ($apptcontinent && $apptcontinent == 1)
		{
			$row->jobfile = $this->getjobfile($row->vehicle_id);

			$params["jobfile"]= $row->jobfile;
			//print_r($row->jobfile);exit;
		}
		//END tcontinent

		//SSI
		$appssi = $this->config->item("ssi_app");
		if ($appssi && $appssi == 1)
		{
			$row->team = $this->getteam($row->vehicle_id);
			$params["team"]= $row->team;

			//comment
			$row->comment = $this->getcomment($row->vehicle_id);
			$params["comment"]= $row->comment;
		}
		//END SSI

		//comment app
		$appcomment = $this->config->item("comment_app");
		if ($appcomment && $appcomment == 1)
		{
			$row->comment = $this->getcomment($row->vehicle_id);
			$params["comment"]= $row->comment;
		}

		$app = $this->config->item("alatberat_app");
		if ($app && $app==1)
		{
			$row->hourmeter = $this->getHourmeter($row->gps->gps_msg_ori);
		}

		if (isset($showoff))
		{
			$row->startoff = $showoff;
			$row->offduration = $showduration;
		}

		if (isset($showon))
		{
			$row->starton = $showon;
			$row->onduration = $showdurationon;
		}

		if($row->vehicle_type == "TK309PTO" || $row->vehicle_type == "GT06PTO")
		{
			$row->traccarPTO = $this->getTraccarPTO($rowinfo->gps_info_io_port);
		}

		if($row->vehicle_type == "TK315DOOR")
		{
			$row->fan = $this->getDoorStatus($row->gps->gps_msg_ori);
		}

		if($row->vehicle_type == "X3_DOOR" || $row->vehicle_type == "TK315DOOR_NEW" || $row->vehicle_type == "X3_PTO" || $row->vehicle_type == "TK510DOOR" || $row->vehicle_type == "TK510CAMDOOR" || $row->vehicle_type == "TK315FAN" || $row->vehicle_type == "GT08SDOOR" ||
		   $row->vehicle_type == "GT08DOOR" || $row->vehicle_type == "GT08CAMDOOR" || $row->vehicle_type == "GT08SPTO" || $row->vehicle_type == "GT08PTO")
		{
			$row->fan = $row->gps->gps_cs;
		}

		if($row->vehicle_type == "TJAM")
		{
			$parse = explode(",",$row->gps->gps_msg_ori);
			if(isset($parse[13]))
			{
				$row->battery = $parse[13];
			}
		}

		if($row->vehicle_type == "AT5")
		{
			$row->battery = $this->getPersen($row->gps->gps_cs);
		}

		if($row->vehicle_type == "GT08SRFID" || $row->vehicle_type == "GT08SRFIDDOOR" || $row->vehicle_type == "GT08SRFIDPTO")
		{
			$row->driver_rfid = $this->getdriver_rfid($row->vehicle_device,$row->vehicle_dbname_live);
		}


		if ($row->vehicle_user_id == 389 && $row->vehicle_type != "A13") //khusus farrasindo
		{
			$row->cutpower = $this->getCutPower($row->vehicle_id,$row->gps->gps_time);
			$params["cutpower"] = $row->cutpower;
		}

		//app workhour (engine on dan off
		if (in_array(strtoupper($row->vehicle_type), $this->config->item("vehicle_workhour"))){
			$row->workhour = $this->getWorkhour($row->gps->gps_workhour);
		}else{
			$row->workhour = $this->getWorkhour(0);
		}

		if (in_array(strtoupper($row->vehicle_type), $this->config->item("vehicle_cam")))
		{
			$row->snap = $this->getLastSnap($row->vehicle_device,$row->vehicle_dbname_live);
			$exp = explode("|", $row->snap);
			$row->snapimage = $exp[0];
			$row->snaptime = date("d F Y H:i:s", strtotime($exp[1]));
			// echo "<pre>";
			// var_dump($row->snaptime);die();
			// echo "<pre>";
		}

		$params["company"] = $row->company;
		$params["driver"] = $row->driver;
		//APP CO REKSA
		if ($app_co && $app_co == 1)
		{
			$params["destination"] = $row->destination;
		}
		//end
		$params["devices"] = $devices;
		$params["data"] = $row->gps;

    // echo "<pre>";
    // var_dump($params["data"]);die();
    // echo "<pre>";


    return $row;
		// $info = $this->load->view("map/info", $params, TRUE);

		// echo json_encode(array("info"=>$info, "vehicle"=>$row));
	}

  function getGeofence_location_pbi($longitude, $ew, $latitude, $ns, $vehicle_device) {
    $this->db = $this->load->database("default", TRUE);

    $lng = getLongitude($longitude, $ew);
    $lat = getLatitude($latitude, $ns);
        $geo_name = "''";

    $sql = sprintf("
          SELECT 	*
          FROM 	%sgeofence
          WHERE 	TRUE
                            AND (geofence_name <> %s)
              AND CONTAINS(geofence_polygon, GEOMFROMTEXT('POINT(%s %s)'))
                            AND (geofence_user = 1147)
                            AND (geofence_status = 1)
          LIMIT 1 OFFSET 0", $this->db->dbprefix, $geo_name, $lng, $lat, $this->sess->user_id);
        //print_r($sql);
    $q = $this->db->query($sql);

    if ($q->num_rows() > 0)
    {
      $row = $q->row();

            $data = $row->geofence_name;

            return $data;


    }else
        {
            return false;
        }

  }

  function getGeofence_location_others($longitude, $latitude, $vehicle_user) {
		$this->db = $this->load->database("default", TRUE);

		$lng = $longitude;
		$lat = $latitude;
    $geo_name = "''";

		$sql = sprintf("
					SELECT 	*
					FROM 	%sgeofence
					WHERE 	TRUE
                            AND (geofence_name <> %s)
							AND CONTAINS(geofence_polygon, GEOMFROMTEXT('POINT(%s %s)'))
                            AND (geofence_user = '%s' )
                            AND (geofence_status = 1)
					LIMIT 1 OFFSET 0", $this->db->dbprefix, $geo_name, $lng, $lat, $vehicle_user);
        //print_r($sql);
		$q = $this->db->query($sql);

		if ($q->num_rows() > 0)
		{
			$row = $q->row();

            $data = $row->geofence_name;

            return $data;


		}else
        {
            return false;
        }

	}

  function getdriver($driver_vehicle) {

	$this->dbtransporter = $this->load->database('transporter',true);
	$this->dbtransporter->select("*");
	$this->dbtransporter->from("driver");
	$this->dbtransporter->order_by("driver_update_date","desc");
	$this->dbtransporter->where("driver_vehicle", $driver_vehicle);
	$this->dbtransporter->limit(1);
	$q = $this->dbtransporter->get();

	if ($q->num_rows > 0 ){
		$row = $q->row();
		$data = $row->driver_id;
		$data .= "-";
		$data .= $row->driver_name;
		return $data;
		$this->dbtransporter->close();
	}
	else {
	$this->dbtransporter->close();
	return false;
	}

	}

	function getdriver_dosj($value)
	{
		$this->dbtransporter = $this->load->database('transporter',true);
		$my_company = $this->sess->user_company;
		$ship_date = date("Y-m-d");

		$this->dbtransporter->order_by("do_delivered_id","desc");
		$this->dbtransporter->where("do_delivered_vehicle", $value);
		$this->dbtransporter->where("do_delivered_company", $my_company);
		$this->dbtransporter->where("do_delivered_date", $ship_date);
		$this->dbtransporter->join("driver", "driver_id = do_delivered_driver", "left");
		$this->dbtransporter->limit(1);
		$qdr = $this->dbtransporter->get("dosj_delivered");
		$rowdr = $qdr->row();

		if ($qdr->num_rows>0)
		{
			$data_driver = $rowdr->driver_id;
			$data_driver .= "-";
			$data_driver .= $rowdr->driver_name;
			$this->dbtransporter->close();
			return $data_driver;
		}
		else
		{
			$this->dbtransporter->close();
			return false;
		}

	}

	function get_dosj($value)
	{
		$this->dbtransporter = $this->load->database('transporter',true);
		$my_company = $this->sess->user_company;
		$ship_date = date("Y-m-d");

		$this->dbtransporter->order_by("do_delivered_id","desc");
		$this->dbtransporter->where("do_delivered_vehicle", $value);
		$this->dbtransporter->where("do_delivered_company", $my_company);
		$this->dbtransporter->where("do_delivered_date", $ship_date);
		$qdr = $this->dbtransporter->get("dosj_delivered");
		$rowdr = $qdr->result();

		if ($qdr->num_rows>0)
		{
			for ($t=0;$t<count($rowdr);$t++)
			{
				$data_dosj[] = $rowdr[$t]->do_delivered_do_number;
			}

			$this->dbtransporter->close();
			return $data_dosj;
		}
		else
		{
			$this->dbtransporter->close();
			return false;
		}
	}

	function getdriver_dosj_all($value)
	{
		$this->dbtransporter = $this->load->database('transporter',true);
		$my_company = $this->sess->user_company;
		$ship_date = date("Y-m-d");
		//hanya yg belum completed; status = 0
		$this->dbtransporter->order_by("do_delivered_id","asc");
		$this->dbtransporter->where("do_delivered_flag", 0);
		$this->dbtransporter->where("do_delivered_status", 0);
		$this->dbtransporter->where("do_delivered_vehicle", $value);
		$this->dbtransporter->where("do_delivered_company", $my_company);
		//$this->dbtransporter->where("do_delivered_date", $ship_date);
		$this->dbtransporter->join("driver", "driver_id = do_delivered_driver", "left");
		$this->dbtransporter->limit(1);
		$qdr = $this->dbtransporter->get("dosj_delivered_all");
		$rowdr = $qdr->row();

		if ($qdr->num_rows>0)
		{
			$data_driver = $rowdr->driver_id;
			$data_driver .= "-";
			$data_driver .= $rowdr->driver_name;
			$this->dbtransporter->close();
			return $data_driver;
		}
		else
		{
			$this->dbtransporter->close();
			return false;
		}

	}

	function getcustomer_dosj_all($value)
	{
		$this->dbtransporter = $this->load->database('transporter',true);
		$my_company = $this->sess->user_company;
		$ship_date = date("Y-m-d");
		//hanya yg belum completed; status = 0
		$this->dbtransporter->order_by("do_delivered_id","asc");
		$this->dbtransporter->where("do_delivered_flag", 0);
		$this->dbtransporter->where("do_delivered_status", 0);
		$this->dbtransporter->where("do_delivered_vehicle", $value);
		$this->dbtransporter->where("do_delivered_company", $my_company);
		//$this->dbtransporter->where("do_delivered_date", $ship_date);
		$this->dbtransporter->limit(1);
		$qdr = $this->dbtransporter->get("dosj_delivered_all");
		$rowdr = $qdr->row();

		if ($qdr->num_rows>0)
		{
			$so_number = $rowdr->do_delivered_do_number;

			$this->dbtransporter->order_by("dosj_id","asc");
			$this->dbtransporter->where("dosj_flag", 0);
			$this->dbtransporter->where("dosj_delivery_status", 1);
			$this->dbtransporter->where("dosj_no", $so_number);
			$this->dbtransporter->where("dosj_company", $my_company);
			//$this->dbtransporter->where("do_delivered_date", $ship_date);
			$this->dbtransporter->limit(1);
			$qdo = $this->dbtransporter->get("dosj_all");
			$rowdo = $qdo->row();
			if ($qdo->num_rows>0){

				$customer_id = $rowdo->dosj_customer_id;
				$data_customer = $this->getcustomer_groups($customer_id);
			}else{
				$data_customer = "";
			}

			return $data_customer;

		}
		else
		{
			$this->dbtransporter->close();
			return false;
		}

	}

	function get_dosj_all($value)
	{

		$this->dbtransporter = $this->load->database('transporter',true);
		$my_company = $this->sess->user_company;
		$ship_date = date("Y-m-d");

		$this->dbtransporter->order_by("do_delivered_id","asc");
		$this->dbtransporter->where("do_delivered_flag", 0);
		$this->dbtransporter->where("do_delivered_status", 0);
		$this->dbtransporter->where("do_delivered_vehicle", $value);
		$this->dbtransporter->where("do_delivered_company", $my_company);
		//$this->dbtransporter->where("do_delivered_date", $ship_date);
		$qdr = $this->dbtransporter->get("dosj_delivered_all");
		$rowdr = $qdr->result();

		if ($qdr->num_rows>0)
		{
			for ($t=0;$t<count($rowdr);$t++)
			{
				$data_dosj[] = $rowdr[$t]->do_delivered_do_number;
			}

			$this->dbtransporter->close();
			return $data_dosj;
		}
		else
		{
			$this->dbtransporter->close();
			return false;
		}
	}

    function getcustomer_groups($vehicle_group)
    {
        $this->db->select("*");
        $this->db->from("group");
        $this->db->where('group_id', $vehicle_group);
        $q = $this->db->get();
        $row = $q->row();

        if ($q->num_rows())
        {
            $data = $row->group_name;
        }
        else
        {
            $data = "";
        }

        return $data;

    }

	function getInGeofence($vehicle_device, $geofence_location){

		$sql = "SELECT * FROM webtracking_geofence_alert a
				JOIN webtracking_geofence b ON b.geofence_id=a.geoalert_geofence
				WHERE a.geoalert_vehicle='" . $vehicle_device ."'
				AND b.geofence_name='" . $geofence_location ."'
				AND a.geoalert_direction = 1
				ORDER BY a.geoalert_time desc LIMIT 1";

		$q = $this->db->query($sql);

		if($q->num_rows() > 0){

			$row = $q->row();

			$today = date("Y-m-d");
			$alert_date = substr($row->geoalert_time, 0,10);
			if($today == $alert_date){
				$data = substr($row->geoalert_time, -8);
			}else{
				$data = date('d/m/Y H:i:s', strtotime($row->geoalert_time));
			}

			$this->load->helper('kopindosat');
			$duration = get_time_difference($row->geoalert_time, date("Y-m-d H:i:s"));

					$show = "";
					if($duration[0]!=0){
						$show .= $duration[0] ." Day ";
					}
					if($duration[1]!=0){
						$show .= $duration[1] ." Hour ";
					}
					if($duration[2]!=0){
						$show .= $duration[2] ." Min ";
					}

					if($show == ""){
						$show .= "0 Min ";
					}

			$data .= " ( Duration : " . $show . " )";

			return $data;
		}

		return false;
	}

	function getHourmeter($value)
	{
			$totstring = strlen($value);
			$getstr = substr($value, -8);
			$conval = hexdec($getstr);

			if ($conval > 172800)
			{
				$format = 'j \d\a\y\s H:i:s';
			}
			else if ($conval > 86400)
			{
				$format = 'j \d\a\y H:i:s';
			}
			else
			{
				$format = 'H:i:s';
			}
			$val = gmdate($format, $conval);

			return $val;
	}

	function getCompany($v)
	{
		$this->db->where("company_id", $v);
		$this->db->limit(1);
		$q = $this->db->get("company");
		$row = $q->row();
		return $row->company_name;
	}

	function getcustomer($vehicle_no)
	{

	$this->dbcar = $this->load->database('rentcar',true);
	$this->dbcar->select("*");
	$this->dbcar->from("rentcar_settenant_vehicle");
	$this->dbcar->where("vehicle_status",0);
	$this->dbcar->where("settenant_flag",0);
	$this->dbcar->where("vehicle_no", $vehicle_no);
	$this->dbcar->order_by("start_date","asc");


	$this->dbcar->limit(1);
	$q = $this->dbcar->get();
	$row = $q->row();

	if ($q->num_rows > 0 ){

		$row = $q->row();

		$data = $row->settenant_name;
		$data .= ",";
		$data .= $row->longtime;
		$data .= ",";
		$data .= $row->start_date;
		$data .= ",";
		$data .= $row->end_date;
		$data .= ",";
		$data .= $row->vehicle_status;
		$data .= ",";
		return $data;
		$this->dbcar->close();
		}

		else {
		$this->dbcar->close();
		return false;
		}
	}

	function get_tenant($id)
	{
		$this->dbcar = $this->load->database("rentcar", true);
		$this->dbcar->where("customer_id", $id);
		$this->dbcar->where("customer_flag", 0);

		$q = $this->dbcar->get("customer");
		$row = $q->row();

		if ($q->num_rows > 0 ){
			$row = $q->row();
			return $row->customer_name;
		}
		else {
			$this->dbcar->close();
			return false;
		}


	}

	function getFanStatus($val)
	{
		//$val = "(000000001271BP05000000000001271120804A0617.4940S10657.9536E000.004514179.73001100000L00000000";
		$totstring = strlen($val);
		$value = substr($val, 79, 1);
		//print_r($value);
		return($value);
	}
	function getDoorStatus($val)
	{
		//0 = close, else open
		$val_new = json_decode($val);
		$value = hexdec($val_new[9]);

		return($value);
	}

	function getvehicledetail($val)
	{
		$this->dbtransporter = $this->load->database('transporter',true);
		$this->dbtransporter->select("mobil_model");
		$this->dbtransporter->where("mobil_device", $val);
		$this->dbtransporter->limit("1");
		$qv = $this->dbtransporter->get("mobil");


		if ($qv->num_rows>0)
		{
			$rowv = $qv->row();
			$data = $rowv->mobil_model;
			return $data;
			$this->dbtransporter->close();
		}
		else
		{
			$this->dbtransporter->close();
			$data = "";
			return $data;
		}
	}


	function get_so($v){

		$now = date("Y-m-d");
		$this->dbtrans = $this->load->database("transporter",true);
		//$this->dbtrans->order_by("suratjalan_id","desc");

		$exp = explode("@", $v);

		$this->dbtrans->select("suratjalan_sales_order_block,suratjalan_id,suratjalan_sales_order_bond");
		$this->dbtrans->where("suratjalan_ship_date",$now);
		$this->dbtrans->where("suratjalan_vehicle_id",$exp[0]."@T5");
		$q    = $this->dbtrans->get("powerblock_suratjalan");
		$data = $q->result();

		$mydata = "";
		if ($q->num_rows > 0)
		{
			for ($i=0;$i<count($data);$i++)
			{
				$mydata .= $data[$i]->suratjalan_id.";".$data[$i]->suratjalan_sales_order_block.";".$data[$i]->suratjalan_sales_order_bond;
				$mydata .= "|";
			}

			$this->dbtrans->close();
			return $mydata;
		}
		else
		{
			$this->dbtrans->close();
			return false;
		}

	}


	//Transporter Tupperware
	function get_id_booking($v)
	{
		$this->dbtrans = $this->load->database("transporter",true);
		$my_company = $this->sess->user_company;
		$this->dbtrans->order_by("id","desc");
		$this->dbtrans->select("booking_id");
		$this->dbtrans->where("booking_status",1);
		$this->dbtrans->where("booking_delivery_status",1);
		$this->dbtrans->where("booking_vehicle",$v);
		$this->dbtrans->limit(2);
		$q = $this->dbtrans->get("id_booking");
		$data = $q->result();
		$mydata = "";
		if ($q->num_rows > 0)
		{
			for ($i=0;$i<count($data);$i++)
			{
				$mydata .= $data[$i]->booking_id;
				$mydata .= "|";
			}

			$this->dbtrans->close();
			return $mydata;
		}
		else
		{
			$this->dbtrans->close();
			return false;
		}
	}

	function get_noso($v)
	{
		$mydr = "";
		$this->dbtrans = $this->load->database("transporter",true);
		$my_company = $this->sess->user_company;
		$this->dbtrans->select("booking_id");
		$this->dbtrans->where("booking_status",1);
		$this->dbtrans->where("booking_delivery_status",1);
		$this->dbtrans->where("booking_vehicle",$v);
		$q = $this->dbtrans->get("id_booking");
		$rows = $q->result();

		if (isset($rows))
		{
			for ($i=0;$i<count($rows);$i++)
			{
				$this->dbtrans->where("transporter_dr_booking_id",$rows[$i]->booking_id);
				if (isset($this->sess->dist_code))
				{
					$this->dbtrans->where("transporter_db_code",$this->sess->dist_code);
				}
				$qdr = $this->dbtrans->get("tupper_dr");
				$rdr = $qdr->result();
				if (isset($rdr) && count($rdr)>0)
				{
					for ($i=0;$i<count($rdr);$i++)
					{
						$mydr .= $rdr[$i]->transporter_dr_so;
						$mydr .= "|";
						//$mydr .= $rdr->transporter_dr_dr;
						//$mydr .= "|";
					}
				}
			}

			$this->dbtrans->close();
			return $mydr;
		}
		else
		{
			$this->dbtrans->close();
			return false;
		}
	}

	function get_nodr($v)
	{
		$mydr = "";
		$this->dbtrans = $this->load->database("transporter",true);
		$my_company = $this->sess->user_company;
		$this->dbtrans->select("booking_id");
		$this->dbtrans->where("booking_status",1);
		$this->dbtrans->where("booking_delivery_status",1);
		$this->dbtrans->where("booking_vehicle",$v);
		$q = $this->dbtrans->get("id_booking");
		$rows = $q->result();

		if (isset($rows))
		{
			for ($i=0;$i<count($rows);$i++)
			{
				$this->dbtrans->where("transporter_dr_booking_id",$rows[$i]->booking_id);
				if (isset($this->sess->dist_code))
				{
					$this->dbtrans->where("transporter_db_code",$this->sess->dist_code);
				}
				$qdr = $this->dbtrans->get("tupper_dr");
				$rdr = $qdr->result();
				if (isset($rdr) && count($rdr)>0)
				{
					for ($i=0;$i<count($rdr);$i++)
					{
						//$mydr .= $rdr[$i]->transporter_dr_so;
						//$mydr .= "|";
						$mydr .= $rdr[$i]->transporter_dr_dr;
						$mydr .= "|";
					}
				}
			}
			$this->dbtrans->close();
			return $mydr;
		}
		else
		{
			$this->dbtrans->close();
			return false;
		}
	}

	function get_dbcode($v)
	{
		$mydbcode = "";
		$this->dbtrans = $this->load->database("transporter",true);
		$my_company = $this->sess->user_company;
		$this->dbtrans->select("booking_id");
		$this->dbtrans->where("booking_status",1);
		$this->dbtrans->where("booking_delivery_status",1);
		$this->dbtrans->where("booking_vehicle",$v);
		$q = $this->dbtrans->get("id_booking");
		$rows = $q->result();

		if (isset($rows))
		{
			for ($i=0;$i<count($rows);$i++)
			{
				$this->dbtrans->select("transporter_db_code, dist_name");
				$this->dbtrans->where("transporter_dr_booking_id",$rows[$i]->booking_id);
				$this->dbtrans->where("transporter_dr_status",1);
				$this->dbtrans->join("transporter_dist_tupper","dist_code = transporter_db_code","left_outer");
				if (isset($this->sess->dist_code))
				{
					$this->dbtrans->where("transporter_db_code",$this->sess->dist_code);
				}
				$qdr = $this->dbtrans->get("tupper_dr");
				$rdr = $qdr->result();

				if (isset($rdr) && count($rdr)>0)
				{
					for($j=0;$j<count($rdr);$j++)
					{
						if (isset($rdr[$j]->transporter_db_code) && $rdr[$j]->transporter_db_code != 0)
						{
							$mydbcode .= $rdr[$j]->transporter_db_code.",".$rdr[$j]->dist_name;
							$mydbcode .= "|";
						}
					}
				}
			}

			$this->dbtrans->close();
			return $mydbcode;
		}
		else
		{
			$this->dbtrans->close();
			return false;
		}
	}

	function get_slcars($v)
	{
		$slcars = "";
		$mycode = explode("|",$v);
		$this->dbtrans = $this->load->database("transporter",true);
		$my_company = $this->sess->user_company;
		$this->dbtrans->select("transporter_barcode_slcars, transporter_barcode_expedition_name");
		$this->dbtrans->where("transporter_barcode",$mycode[0]);
		$q = $this->dbtrans->get("tupper_barcode");
		$rows = $q->result();

		if (isset($rows))
		{
			for ($i=0;$i<count($rows);$i++)
			{
				if (isset($rows[$i]->transporter_barcode_slcars) && isset($rows[$i]->transporter_barcode_expedition_name))
				{
					$slcars .= $rows[$i]->transporter_barcode_slcars.",".$rows[$i]->transporter_barcode_expedition_name;
					$slcars .= "|";
				}
			}

			$this->dbtrans->close();
			return $slcars;
		}
		else
		{
			$this->dbtrans->close();
			return false;
		}
	}

	function getdestination($destination_vehicle) {

		$this->dbtrans = $this->load->database('transporter',true);
		$now = date("Y-m-d");
		$this->dbtrans->order_by("destination_id","desc");
		$this->dbtrans->where("destination_vehicle",$destination_vehicle);
		$this->dbtrans->where("destination_status",1);
		$this->dbtrans->where("destination_company",$this->sess->user_company);
		$this->dbtrans->where("destination_date",$now);
		$this->dbtrans->limit("1");
		$q = $this->dbtrans->get("destination_reksa");

		if ($q->num_rows > 0 ){
			$row = $q->row();
			$data = $row->destination_id;
			$data .= "-";
			$data .= $row->destination_name1;
			return $data;
			$this->dbtrans->close();
		}
		else {
		$this->dbtrans->close();
			$data = "";
			$data .= "-";
			$data .= "";
			return $data;
		}

	}

	//tampil list jobfile dimenu tracking
	function getjobfile($vehicle_id)
	{
		$now = date("Y-m-d");
		//print_r($now);exit();
		$this->dbtransporter = $this->load->database('transporter',true);
		$this->dbtransporter->select("*");
		$this->dbtransporter->from("tcont_jobfile");
		$this->dbtransporter->where("transporter_job_flag", 0);
		$this->dbtransporter->where("transporter_job_status", 1); // 1: on going 2: delivered
		$this->dbtransporter->where("transporter_job_vehicle_id", $vehicle_id);
		$this->dbtransporter->order_by("transporter_job_date", "asc");
		$this->dbtransporter->order_by("transporter_job_time", "asc");
		$this->dbtransporter->limit(1);
		$q = $this->dbtransporter->get();
		$row = $q->row();

		if ($q->num_rows > 0 ){

				$row = $q->row();
				$data = $row->transporter_job_id;
				$data .= "|";
				$data .= $row->transporter_job_number;
				return $data;
				$this->dbtransporter->close();
			}

		else {
			$this->dbtransporter->close();
			return false;
		}
	}

	//tampil list muatan dimenu tracking (bangun cilegon)
	function getmuatan($vehicle_id)
	{
		$now = date("Y-m-d");

		$this->dbtransporter = $this->load->database('transporter',true);
		$this->dbtransporter->limit(1);
		$this->dbtransporter->order_by("muatan_startdate", "asc");
		$this->dbtransporter->order_by("muatan_starttime", "asc");
		$this->dbtransporter->where("muatan_flag", 0);
		$this->dbtransporter->where("muatan_status", 0); // 0: default no condition
		$this->dbtransporter->where("muatan_vehicle_id", $vehicle_id);
		$this->dbtransporter->where("muatan_startdate", $now);
		$this->dbtransporter->join("bangun_muatan_data", "muatan_data_id = muatan_data", "left");
		$q = $this->dbtransporter->get("bangun_muatan");
		$row = $q->row();
		//print_r($row);exit();
		if ($q->num_rows > 0 ){

				$row = $q->row();
				$data = $row->muatan_id;
				$data .= "|";
				$data .= $row->muatan_data_name;
				return $data;
				$this->dbtransporter->close();
			}

		else {
			$this->dbtransporter->close();
			return false;
		}
	}

	//tampil list team ( SSI )
	function getteam($vehicle_id)
	{
		$now = date("Y-m-d H:i:s");
		$this->dbtransporter = $this->load->database('transporter',true);
		$this->dbtransporter->limit(1);
		$this->dbtransporter->order_by("team_sch_start", "asc");
		$this->dbtransporter->order_by("team_sch_end", "asc");
		$this->dbtransporter->where("team_flag", 0);
		$this->dbtransporter->where("team_vehicle_id", $vehicle_id);
		$this->dbtransporter->where("team_sch_start <=", $now);
		$this->dbtransporter->where("team_sch_end >=", $now);
		$q = $this->dbtransporter->get("ssi_team");
		$row = $q->row();

		if ($q->num_rows > 0 ){

				$row = $q->row();
				$data = $row->team_id;
				$data .= "|";
				$data .= $row->team_staff;
				$data .= "|";
				$data .= $row->team_driver;
				$data .= "|";
				$data .= $row->team_pengaman1;
				return $data;
				$this->dbtransporter->close();
			}

		else {
			$this->dbtransporter->close();
			return false;
		}
	}

	function getcomment($vehicle_id)
	{
		$now = date("Y-m-d H:i:s");
		$this->db->select("vehicle_id,vehicle_user_id");
		$this->db->limit(1);
		$this->db->where("vehicle_id", $vehicle_id);
		$qv = $this->db->get("vehicle");
		if ($qv->num_rows > 0){
			$rowv = $qv->row();
			$userid = $rowv->vehicle_user_id;

			if ($userid == "1933"){ //khusus ssi
				$comment_table = "ssi_vehicle_comment";
			}else{
				$comment_table = "vehicle_comment";
			}
		}
		$this->dbtransporter = $this->load->database('transporter',true);
		$this->dbtransporter->limit(1);
		$this->dbtransporter->order_by("comment_datetime", "desc");
		$this->dbtransporter->where("comment_flag", 0);
		$this->dbtransporter->where("comment_status", 0);
		$this->dbtransporter->where("comment_vehicle_id", $vehicle_id);

		$q = $this->dbtransporter->get($comment_table);

		$row = $q->row();

		if ($q->num_rows > 0 ){

				$row = $q->row();
				$data = $row->comment_id;
				$data .= "|";
				$data .= $row->comment_title;
				$data .= "|";
				$data .= $row->comment_vehicle_id;

				return $data;
				$this->dbtransporter->close();
			}

		else {
				$data = 0;
				$data .= "|";
				$data .= "Add Comment";
				$data .= "|";
				$data .= $vehicle_id;
			$this->dbtransporter->close();
			return false;
		}
	}
	function geocode2()
    {
        $coord = $this->input->post('lokasi');

        if (!$coord) return;

        $coord1 = explode(",", $coord);
        $lat = $coord1[0];
        $lng = $coord1[1];

        if (!$lat) return;
        if (!$lng) return;

        $callback['lat'] = $lat;
        $callback['lng'] = $lng;

        echo json_encode($callback);

    }
    function getSuhu($val)
	{
		//$val = "(000000001271BP05000000000001271120804A0617.4940S10657.9536E000.004514179.73001100000L00000000";
		$totstring = strlen($val);
		$value = substr($val, 94, 4);
		$value = (hexdec($value)/10);
		//print_r($value);
		return($value);
	}
	function getTraccarPTO($val)
	{
		$totstring = strlen($val);
		$value = substr($val, 4, 1);
		if($value == 0)
		{
			$value = "OFF";
		}
		else
		{
			$value = "ON";
		}
		return($value);
	}

	function get_new_sj($v){

		$now = date("Y-m-d");
		$exp = explode("@", $v);


		$this->db->select("sj_sj_no,sj_sj_date,sj_di_no,sj_item,sj_uj_date,sj_category,sj_item,sj_uj_no,sj_so_no,sj_cust_name,sj_wilayah,sj_status_kirim");
		$this->db->order_by("sj_id","desc");
		$this->db->where("sj_vehicle_device",$exp[0]."@".$exp[1]);
		$this->db->where("sj_status",1);
		$this->db->where("sj_flag",0);
		$q = $this->db->get("sj");
		$data = $q->result();

		$mydata = "";
		if ($q->num_rows > 0)
		{
			for($i=0; $i < count($data); $i++){
				$mydata .= ""."<font color='blue'>".$data[$i]->sj_uj_date."</font>"."<br />".
						"<font color='blue'>".$data[$i]->sj_cust_name."</font>"."<br /> ".
					   "No.SJ: "."<font color='blue'>".$data[$i]->sj_sj_no."</font>"."<font color='green'>"." (".date("d-m-Y", strtotime($data[$i]->sj_sj_date)).")"."</font>"."<br /> ".
					   "No.SO: "."<font color='blue'>".$data[$i]->sj_so_no."</font>"."<br /> ".
					   "Wilayah: "."<font color='blue'>".$data[$i]->sj_wilayah."</font>"."<br /> ".
					   "Status: "."<font color='blue'>".$data[$i]->sj_status_kirim."</font><br /><hr>
					   ";
			}

			$this->db->close();
			return $mydata;
		}
		else
		{
			$mydata .= "Tidak ada dokumen";
			$this->db->close();
			return $mydata;
		}

	}

	function getdataproject($v){
		// print_r("asd");exit();
		$this->dbtransporter = $this->load->database("transporter", true);
		$this->dbtransporter->select("*");

		if ($this->sess->user_id == "389") {
			$this->dbtransporter->where("project_user_id", $this->sess->user_id);
		}else {
			$this->dbtransporter->where("project_vehicle_company", $this->sess->user_company);
		}

		$this->dbtransporter->where("project_vehicle_device", $v);
		$this->dbtransporter->where("project_flag", "0");
		$this->dbtransporter->where("project_status", "!=3");
		$this->dbtransporter->order_by("project_startdate", "asc");
		$q = $this->dbtransporter->get("project_schedule");
		$data = $q->result();

		$mydata = "";
		if ($q->num_rows > 0)
		{
			for($i=0; $i < count($data); $i++){
				$mydata .= ""."<font color='blue'>".$data[$i]->project_name."</font>"."<br />".
						"<font color='black'>".$data[$i]->project_customer_name."</font>"."<br /> ".
					   "<font color='green'>"." (".date("d-m-Y H:i", strtotime($data[$i]->project_startdate)).")"."</font>"."<br /> ".
					   "<font color='blue'>".$data[$i]->project_durationofwork."</font>"."<br />
					   ";
			}

			$this->db->close();
			return $mydata;
		}
		else
		{
			$mydata .= "";
			$this->db->close();
			return $mydata;
		}
	}

	function get_new_driver($v){

		$now = date("Y-m-d");
		$exp = explode("@", $v);

		$this->db->select("sj_driver");
		$this->db->order_by("sj_id","desc");
		$this->db->where("sj_vehicle_device",$exp[0]."@".$exp[1]);
		$this->db->where("sj_status",1);
		$this->db->where("sj_flag",0);
		$q = $this->db->get("sj");
		$data = $q->result();

		$mydata = "";
		if ($q->num_rows > 0)
		{
			for($i=0; $i < count($data); $i++){

				$mydata .= "".$data[$i]->sj_driver."<br /><hr>";
			}
			$this->db->close();
			return $mydata;
		}
		else
		{
			$this->db->close();
			return false;
		}

	}

	function get_off_autocheck($v){

		$now = date("Y-m-d");
		$exp = explode("@", $v);

		$this->db->limit(1);
		$this->db->select("auto_vehicle_device,auto_last_engine,auto_change_engine_status,
						   auto_last_update,auto_change_engine_datetime");
		$this->db->order_by("auto_id","asc");
		$this->db->where("auto_vehicle_device",$exp[0]."@".$exp[1]);
		$this->db->where("auto_flag",0);
		$q = $this->db->get("vehicle_autocheck");
		$data = $q->row();
		//print_r($data);exit();
		$mydata = "";
		if ($q->num_rows > 0)
		{
			//cek durasi engine Off
			if($data->auto_last_engine == "OFF" && $data->auto_change_engine_status == "OFF"){
				//compare kolom change engine
				$param1 = strtotime($data->auto_last_update);
				$param2 = strtotime($data->auto_change_engine_datetime);
				$diff = $param1 - $param2;
				if($diff > 1800){ //lebih dari 30 menit (1800)
					$img_url = base_url()."assets/images/parking-icon.png";
					$mydata .= "<img src="."'".$img_url."'"."width='20' title='> 30 Min'/><font color='red'></img>";
					$this->db->close();
					return $mydata;
				}
			}else{
				$this->db->close();
				return false;
			}

		}
		else
		{
			$this->db->close();
			return false;
		}

	}

	function getdriver_idcard($vehicle_device) {

		//get ID CARD
		$this->dbalert = $this->load->database('GPS_NORAN_ALERT',true);
		$this->dbalert->select("device,item");
		$this->dbalert->order_by("datetime","desc");
		$this->dbalert->where("device", $vehicle_device);
		$this->dbalert->where("message", "31003300");
		$this->dbalert->limit(1);
		$q_alert = $this->dbalert->get('webtracking_gps_alert');
		$row_alert = $q_alert->row();

		if (count($row_alert) > 0 ){

			$idcard = $row_alert->item;

			//get driver by id card
			$this->dbtransporter = $this->load->database('transporter',true);
			$this->dbtransporter->order_by("driver_id","desc");
			$this->dbtransporter->where("driver_idcard", $idcard);
			$this->dbtransporter->where("driver_status", 1);
			$this->dbtransporter->limit(1);
			$q = $this->dbtransporter->get('driver');
			$row = $q->row();

			if ($q->num_rows > 0 ){
				$row = $q->row();
				$data = $row->driver_id;
				$data .= "-";
				$data .= $row->driver_name;
				$data .= "-";
				$data .= $row->driver_idcard;

				return $data;
				$this->dbtransporter->close();
			}
		}
		else
		{
			$this->dbalert->close();
			return false;
		}

	}

	function getPersen($volt)
	{
		if ($volt >= 4.13) {
			$persen = 100;
		}else if ($volt >= 4.08 && $volt <= 4.12) {
			$persen = 90;
		}else if ($volt >= 4 && $volt <= 4.07) {
			$persen = 80;
		}else if ($volt >= 3.91 && $volt <= 3.99) {
			$persen = 70;
		}else if ($volt >= 3.87 && $volt <= 3.90) {
			$persen = 60;
		}else if ($volt >= 3.81 && $volt <= 3.86) {
			$persen = 50;
		}else if ($volt >= 3.78 && $volt <= 3.80) {
			$persen = 40;
		}else if ($volt >= 3.75 && $volt <= 3.77) {
			$persen = 30;
		}else if ($volt >= 3.73 && $volt <= 3.74) {
			$persen = 20;
		}else if ($volt >= 3.70 && $volt <= 3.72) {
			$persen = 10;
		}else if ($volt >= 3.60 && $volt <= 3.69) {
			$persen = 5;
		}else if ($volt >= 3.55 && $volt <= 3.59) {
			$persen = 1;
		}else if ($volt <= 3.54) {
			$persen = 1;
		}else{
			$persen = 0;
		}

		return($persen);
	}

	function getCutPower($id,$gpstime) {

		$nowdate = date("Y-m-d H:i:s");

		$this->db->select("vehicle_id,vehicle_device,vehicle_type,vehicle_info");
		$this->db->order_by("vehicle_id","desc");
		$this->db->where("vehicle_id", $id);
		$this->db->where("vehicle_status <> ",3);
		$q = $this->db->get('vehicle');
		$rowvehicle = $q->row();

		$user_dblive = $this->sess->user_dblive;

		if (count($rowvehicle) > 0 ){
			//get table port
			$vehicle_ex = explode("@", $rowvehicle->vehicle_device);
			$name = $vehicle_ex[0];
			$host = $vehicle_ex[1];

			$alert_code = array('BO010','dt');
				$tables = $this->gpsmodel->getTable($rowvehicle);
				$lasthour = date("Y-m-d H:i:s", strtotime("-8 hour", strtotime($nowdate)));
				$nowhour = date("Y-m-d H:i:s", strtotime("-7 hour", strtotime($nowdate)));

				$nowgpstime_sec = strtotime($gpstime);
				$nowdatetime_sec = strtotime($nowhour);

				$delta = $nowdatetime_sec - $nowgpstime_sec;

				//jika gps update (kurang dari 3600 s)
				if($delta > 0 && $delta < 3600){
					$condition_update = 1;
				}
				else if($delta < 0){
					$condition_update = 1;
				}
				else
				{
					$condition_update = 0;
				}

				$this->db = $this->load->database($user_dblive, TRUE);
				$this->db->limit(1);
				$this->db->select("gps_name,gps_alert,gps_time");
				$this->db->order_by("gps_time", "desc");
				$this->db->where("gps_name", $name);
				$this->db->where("gps_host", $host);
				$this->db->where_in("gps_alert", $alert_code);
				if($condition_update == 1){
					$this->db->where("gps_time >=", $lasthour);
					$this->db->where("gps_time <=", $nowhour);
				}
				$qalert = $this->db->get("gps_alert");
				$rowalert = $qalert->row();
				if(count($rowalert)>0)
				{
					$gpsalert_time = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rowalert->gps_time)));
					$this->db->close();
					return $gpsalert_time;
				}


		}
		else
		{
			$this->db->close();
			return false;
		}

	}

	function getCutPower_old($id,$gpstime) {

		$list_1 = array('T5','T5SILVER','T5PULSE','T5DOOR','T5');
		$list_2 = array('GT06', 'GT06PTO', 'GT06N', 'TK303', 'TK309', 'TK309N', 'TK315N', 'TK309PTO', 'TK315DOOR', 'A13', 'TK315_NEW', 'TK309_NEW', 'TK315DOOR_NEW','TK315','GT06_NEW','AT5','X3','X3_DOOR');
		$nowdate = date("Y-m-d H:i:s");

		$this->db->select("vehicle_id,vehicle_device,vehicle_type,vehicle_info");
		$this->db->order_by("vehicle_id","desc");
		$this->db->where("vehicle_id", $id);
		$this->db->where("vehicle_status <> ",3);
		$q = $this->db->get('vehicle');
		$rowvehicle = $q->row();

		if (count($rowvehicle) > 0 ){
			//get table port
			$vehicle_ex = explode("@", $rowvehicle->vehicle_device);
			$name = $vehicle_ex[0];
			$host = $vehicle_ex[1];
			if (in_array(strtoupper($rowvehicle->vehicle_type), $list_1)){
				$alert_code = "BO010";
				$tables = $this->gpsmodel->getTable($rowvehicle);
				$lasthour = date("Y-m-d H:i:s", strtotime("-8 hour", strtotime($nowdate)));
				$nowhour = date("Y-m-d H:i:s", strtotime("-7 hour", strtotime($nowdate)));

				$nowgpstime_sec = strtotime($gpstime);
				$nowdatetime_sec = strtotime($nowhour);

				$delta = $nowdatetime_sec - $nowgpstime_sec;

				//jika gps update (kurang dari 3600 s)
				if($delta > 0 && $delta < 3600){
					$condition_update = 1;
				}
				else if($delta < 0){
					$condition_update = 1;
				}
				else
				{
					$condition_update = 0;
				}

				$this->db = $this->load->database($tables["dbname"], TRUE);
				$this->db->limit(1);
				$this->db->select("gps_name,gps_alert,gps_time");
				$this->db->order_by("gps_time", "desc");
				$this->db->where("gps_name", $name);
				$this->db->where("gps_host", $host);
				$this->db->where("gps_alert", $alert_code);
				if($condition_update == 1){
					$this->db->where("gps_time >=", $lasthour);
					$this->db->where("gps_time <=", $nowhour);
				}
				$qalert = $this->db->get("gps_alert");
				$rowalert = $qalert->row();
				if(count($rowalert)>0)
				{
					$gpsalert_time = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rowalert->gps_time)));
					$this->db->close();
					return $gpsalert_time;
				}
			}
			else if (in_array(strtoupper($rowvehicle->vehicle_type), $list_2)){
				$alert_code = "dt";
				$tables = $this->gpsmodel->getTable($rowvehicle);
				$lasthour = date("Y-m-d H:i:s", strtotime("-1 hour", strtotime($nowdate)));
				$nowhour = $nowdate;

				$nowgpstime_sec = strtotime($gpstime);
				$nowdatetime_sec = strtotime($nowhour);

				$delta = $nowdatetime_sec - $nowgpstime_sec;

				//jika gps update (kurang dari 3600 s)
				if($delta > 0 && $delta < 3600){
					$condition_update = 1;
				}
				else if($delta < 0){
					$condition_update = 1;
				}
				else
				{
					$condition_update = 0;
				}


				$this->db = $this->load->database($tables["dbname"], TRUE);
				$this->db->limit(1);
				$this->db->select("device,datetime,item");
				$this->db->order_by("datetime", "desc");
				$this->db->where("device", $rowvehicle->vehicle_device);
				$this->db->where("item", "dt");
				if($condition_update == 1){
					$this->db->where("datetime >=", $lasthour);
					$this->db->where("datetime <=", $nowhour);
				}
				$qalert = $this->db->get("gps_alert");
				$rowalert = $qalert->row();
				if(count($rowalert)>0)
				{
					$gpsalert_time = date("d-m-Y H:i:s", strtotime($rowalert->datetime));
					$this->db->close();
					return $gpsalert_time;
				}
			}
			else
			{
				$this->db->close();
				return false;
			}

		}
		else
		{
			$this->db->close();
			return false;
		}

	}

	function getWorkhour($v) {

		if($v > 0){
			$init = $v;
			$hours = floor($init / 3600);
			$minutes = floor(($init / 60) % 60);
			$seconds = $init % 60;

			if($hours == 0){
				$hours = "";
			}
			else
			{
				$hours = $hours." hour ";
			}

			if($minutes == 0){
				$minutes = "";
			}
			else
			{
				$minutes = $minutes." min ";
			}
			$data = $hours."".$minutes;

		}else{
			$data = "";
		}
		//print_r($data);exit();

		return $data;

	}

	function getLastSnap($deviceid,$vehicle_dblive) {

		$ex_vehicle = explode("@", $deviceid);
		$imei = $ex_vehicle[0];

		//vehicle_dbname_live
		$this->db = $this->load->database($vehicle_dblive, TRUE);
		$this->db->select("picture_url,picture_datetime");
		$this->db->order_by("picture_datetime","desc");
		$this->db->where("picture_imei", $imei);
		$this->db->where("picture_status",1);
		$this->db->limit(1);
		$q = $this->db->get('picture');
		$rowsnap = $q->row();

		$vehicle_snap = "";

		if (count($rowsnap) > 0 ){


			$vehicle_snap = $rowsnap->picture_url;
			$vehicle_snap .= "|";
			$vehicle_snap .= $rowsnap->picture_datetime;

			$this->db->close();
			return $vehicle_snap;

		}
		else
		{
			$this->db->close();
			return false;
		}

	}

	function getdriver_rfid($deviceid,$dblive) {

		$ex_vehicle = explode("@", $deviceid);
		$imei = $ex_vehicle[0];

		//get ID CARD
		$this->dblive = $this->load->database($dblive,true);
		$this->dblive->select("gps_name,gps_mv");
		$this->dblive->order_by("gps_time","desc");
		$this->dblive->where("gps_name", $imei);
		$this->dblive->limit(1);
		$q_live = $this->dblive->get('webtracking_gps');
		$row_gps = $q_live->row();

		if (count($row_gps) > 0 ){

			$rfid = $row_gps->gps_mv;

			//get driver by id card
			$this->dbtransporter = $this->load->database('transporter',true);
			$this->dbtransporter->order_by("driver_id","desc");
			$this->dbtransporter->where("driver_rfid", $rfid);
			$this->dbtransporter->where("driver_status", 1);
			$this->dbtransporter->limit(1);
			$q = $this->dbtransporter->get('driver');
			$row = $q->row();

			if ($q->num_rows > 0 ){
				$row = $q->row();
				$data = $row->driver_id;
				$data .= "-";
				$data .= $row->driver_name;
				$data .= "-";
				$data .= $row->driver_rfid;

				return $data;
				$this->dbtransporter->close();
			}
		}
		else
		{
			$this->dblive->close();
			return false;
		}

	}

  function getGeofence_location($longitude, $ew, $latitude, $ns, $vehicle_device) {
		$user_id_fix = 981;
		$this->db = $this->load->database("default", TRUE);

		$lng = getLongitude($longitude, $ew);
		$lat = getLatitude($latitude, $ns);
        $geo_name = "''";

		$sql = sprintf("
					SELECT 	*
					FROM 	%sgeofence
					WHERE 	TRUE
                            AND (geofence_name <> %s)
							AND CONTAINS(geofence_polygon, GEOMFROMTEXT('POINT(%s %s)'))
                            AND (geofence_user = '%s' )
                            AND (geofence_status = 1)
					LIMIT 1 OFFSET 0", $this->db->dbprefix, $geo_name, $lng, $lat, $user_id_fix);
        //print_r($sql);
		$q = $this->db->query($sql);

		if ($q->num_rows() > 0)
		{
			$row = $q->row();

            $data = $row->geofence_name;

            return $data;


		}else
        {
            return false;
        }

	}


}
