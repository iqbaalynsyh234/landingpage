<?php
include "base.php";

class Cron_report_tmsmodul extends Base {

  function Cron_report_tmsmodul()
  {
    parent::Base();
    $this->load->model("gpsmodel");
    $this->load->model("vehiclemodel");
    $this->load->model("configmodel");
    $this->load->model("custommodel");
    $this->load->helper('common_helper');
    $this->load->helper('kopindosat');

    $this->load->helper('common');
		$this->load->helper('email');
		$this->load->library('email');
  }

  function index() { }

  function all_report($userid="", $company="all", $orderby="", $startdate = "", $enddate = "")
	{
		$this->operational($userid,$orderby, $startdate, $enddate);//T5
		// $this->operational_other($userid, $company, $orderby, $startdate, $enddate);//CONCOX
		// $this->door($userid,$startdate, $enddate);
		// $this->door_other($userid, $startdate, $enddate);
		// $this->door_x3($userid, $startdate, $enddate);
	}

  function reportdailyactivity($userid="", $orderby="", $startdate = "", $enddate = ""){
    ini_set('memory_limit', '2G');
    printf("PROSES AUTO REPORT DATA OPERASIONAL KHUSUS PORT >> START \r\n");
    $startproses         = date("Y-m-d H:i:s");
		$name                = "";
		$host                = "";

    $report_type         = "operasional";
    $process_date        = date("Y-m-d H:i:s");
		$start_time          = date("Y-m-d H:i:s");
    //$domain_server     = "http        ://202.129.190.194/";
		$report              = "operasional_";
    $reportinsert        = "dailyactivity_";
    $reportdailyactivity = "dailyactivityreport_";

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

  		if ($orderby == "") {
          $orderby = "asc";
      }

      // $month = "March";

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

        $sdate = date("Y-m-d H:i:s", strtotime($startdate));
        $edate = date("Y-m-d H:i:s", strtotime($enddate));
        // $sdate = date("2021-04-08 00:00:00");
        // $edate = date("2021-04-08 23:59:59");
        // print_r($sdate.'||'.$edate);exit();
        $z     = 0;

	      $this->db->order_by("vehicle_id", $orderby);
      		if ($name != "" && $host != "")
          {
              $dev = $name."@".$host;
              $this->db->where("vehicle_device", $dev);
          }

      		if ($userid != "")
      		{
      			$this->db->where("user_id",$userid);
      		}

        $this->db->join("user", "vehicle_user_id = user_id", "left outer");

      		if ($userid == "")
      		{
      			$this->db->where("user_company >",0);
      		}
      		$this->db->where("vehicle_user_id", $userid);
      		$this->db->where("vehicle_status <>", 3);
      		//$this->db->where_not_in("vehicle_type", $this->config->item('vehicle_no_engine'));
      		$this->db->where_not_in("vehicle_type", $this->config->item('vehicle_others'));

          $q = $this->db->get("vehicle");
          $rowvehicle = $q->result();

          // echo "<pre>";
          // var_dump($rowvehicle);die();
          // echo "<pre>";

          $total_process = count($rowvehicle);
          printf("TOTAL PROSES VEHICLE : %s \r\n",$total_process);
          printf("============================================ \r\n");

          $datastartend = array();
          for ($i=0; $i < $total_process; $i++) {
            $vehicledevice  = $rowvehicle[$i]->vehicle_device;//"58042081134959@GT08S"; //$rowvehicle[$i]->vehicle_device;
            $query1         = $this->getquery1($vehicledevice, $sdate, $edate, $dbtable);
              for ($j=0; $j < sizeof($query1); $j++) {
                $query2         = $this->getquery2($vehicledevice, $query1[$j]->trip_mileage_start_time, $sdate, $edate, $dbtable);
                  if (sizeof($query2) > 0) {
                    $starttime   = $query1[$j]->trip_mileage_start_time;
                    $endtime     = $query2[0]->trip_mileage_end_time;

                    $now         = strtotime($starttime);
                    $difference  = strtotime($endtime) - $now;
                    $intervalnya = floor($difference / 60);

                    array_push($datastartend, array(
                       "vehicledevice"              => $vehicledevice,
                       "vehicleno"                  => $rowvehicle[$i]->vehicle_no,
                       "vehiclename"                => $rowvehicle[$i]->vehicle_name,
                       "geofence_start"             => $query1[$j]->trip_mileage_geofence_start,
                       "geofence_end"               => $query2[0]->trip_mileage_geofence_end,
                       "start_time"                 => $starttime,
                       "end_time"                   => $endtime,
                       "dailyactivity_waktu_trip"   => $intervalnya,
                       "dailyactivity_km_tempuh"    => ($query1[0]->trip_mileage_trip_mileage+$query2[0]->trip_mileage_trip_mileage),
                       "dailyactivity_waktu_tempuh" => "0",
                       "dailyactivity_kmperjam"     => "0",
                       "dailyactivity_max_speed"    => ""
                     ));
                  }
              }
          }

          // echo "<pre>";
          // var_dump($datastartend[0]);die();
          // echo "<pre>";

          for ($loop=0; $loop < sizeof($datastartend); $loop++) {
           $cekdata = $this->checkthisout2($dbtable3, $datastartend[$loop]['vehicledevice'], $datastartend[$loop]['start_time']);
           // echo "total data : ". sizeof($cekdata)."<br>";
             if (sizeof($cekdata) < 1) {
               $insertstartend = array(
                                   "dailyactivity_vehicle_device" => $datastartend[$loop]['vehicledevice'],
                                   "dailyactivity_vehicle_no"     => $datastartend[$loop]['vehicleno'],
                                   "dailyactivity_vehicle_name"   => $datastartend[$loop]['vehiclename'],
                                   "dailyactivity_geofence_start" => $datastartend[$loop]['geofence_start'],
                                   "dailyactivity_geofence_end"   => $datastartend[$loop]['geofence_end'],
                                   "dailyactivity_from_origin"    => $datastartend[$loop]['start_time'],
                                   "dailyactivity_backto_origin"  => $datastartend[$loop]['end_time'],
                                   "dailyactivity_waktu_trip"     => $datastartend[$loop]['dailyactivity_waktu_trip'],
                                   "dailyactivity_km_tempuh"      => $datastartend[$loop]['dailyactivity_km_tempuh'],
                                   "dailyactivity_waktu_tempuh"   => "0",
                                   "dailyactivity_kmperjam"       => "0",
                                   "dailyactivity_max_speed"      => ""
                                 );

                $this->dbtrip = $this->load->database("operational_report",TRUE);
                $this->dbtrip->insert($dbtable3, $insertstartend);
                printf("SUCCESS INPUT DATA REPORT DAILY ACTIVITY \r\n");

                // echo "<pre>";
                // var_dump($insertstartend);die();
                // echo "<pre>";
             }
          }



          $dataforinsert = array();
          for ($k=0; $k < sizeof($datastartend); $k++) {
            $vehicledevice = $datastartend[$k]['vehicledevice'];
            $starttime     = $datastartend[$k]['start_time'];
            $endtime       = $datastartend[$k]['end_time'];

            $query3        = $this->getquery3($vehicledevice, $starttime, $endtime, $dbtable);
              if (isset($query3)) {
                for ($l=0; $l < sizeof($query3); $l++) {
                  $cekdata = $this->checkthisout($dbtable2, $query3[$l]->trip_mileage_id);
                    if ($cekdata < 1) {
                      $insertthis['trip_mileage_id']                  = $query3[$l]->trip_mileage_id;
                      $insertthis['trip_mileage_vehicle_id']          = $query3[$l]->trip_mileage_vehicle_id;
                      $insertthis['trip_mileage_vehicle_no']          = $query3[$l]->trip_mileage_vehicle_no;
                      $insertthis['trip_mileage_vehicle_name']        = $query3[$l]->trip_mileage_vehicle_name;
                      $insertthis['trip_mileage_vehicle_type']        = $query3[$l]->trip_mileage_vehicle_type;
                      $insertthis['trip_mileage_trip_no']             = $query3[$l]->trip_mileage_trip_no;
                      $insertthis['trip_mileage_engine']              = $query3[$l]->trip_mileage_engine;
                      $insertthis['trip_mileage_start_time']          = $query3[$l]->trip_mileage_start_time;
                      $insertthis['trip_mileage_end_time']            = $query3[$l]->trip_mileage_end_time;
                      $insertthis['trip_mileage_duration']            = $query3[$l]->trip_mileage_duration;
                      $insertthis['trip_mileage_duration_sec']        = $query3[$l]->trip_mileage_duration_sec;
                      $insertthis['trip_mileage_trip_mileage']        = $query3[$l]->trip_mileage_trip_mileage;
                      $insertthis['trip_mileage_cummulative_mileage'] = $query3[$l]->trip_mileage_cummulative_mileage;
                      $insertthis['trip_mileage_location_start']      = $query3[$l]->trip_mileage_location_start;
                      $insertthis['trip_mileage_location_end']        = $query3[$l]->trip_mileage_location_end;
                      $insertthis['trip_mileage_geofence_start']      = $query3[$l]->trip_mileage_geofence_start;
                      $insertthis['trip_mileage_geofence_end']        = $query3[$l]->trip_mileage_geofence_end;
                      $insertthis['trip_mileage_coordinate_start']    = $query3[$l]->trip_mileage_coordinate_start;
                      $insertthis['trip_mileage_coordinate_end']      = $query3[$l]->trip_mileage_coordinate_end;
                      $insertthis['trip_mileage_door_start']          = $query3[$l]->trip_mileage_door_start;
                      $insertthis['trip_mileage_door_end']            = $query3[$l]->trip_mileage_door_end;
                      $insertthis['trip_mileage_lat']                 = $query3[$l]->trip_mileage_lat;
                      $insertthis['trip_mileage_lng']                 = $query3[$l]->trip_mileage_lng;
                      $insertthis['trip_mileage_totaldata']           = $query3[$l]->trip_mileage_totaldata;

                      $this->dbtrip = $this->load->database("operational_report",TRUE);
            					$this->dbtrip->insert($dbtable2, $insertthis);
                      printf("SUCCESS INPUT DATA PERJALANAN \r\n");
                    }
                }
              }
          }

          // echo "<pre>";
          // var_dump($datastartend);die();
          // echo "<pre>";
          printf("CRON SELESAI \r\n");
  }

  function getquery1($vehicledevice, $sdate, $edate, $dbtable){
    $this->dboperationalfix = $this->load->database("operational_report",TRUE);
    $this->dboperationalfix->where("trip_mileage_vehicle_id", $vehicledevice);
    $this->dboperationalfix->where("trip_mileage_geofence_start !=", "0");
    $this->dboperationalfix->where("trip_mileage_geofence_end", "0");
    $this->dboperationalfix->where("trip_mileage_start_time >= ", $sdate);
    $this->dboperationalfix->where("trip_mileage_end_time <= ", $edate);
    $q          = $this->dboperationalfix->get($dbtable);
    return $q->result();
    // echo "<pre>";
    // var_dump($q->result());die();
    // echo "<pre>";
  }

  function getquery2($vehicledevice, $sdateorigin, $sdate, $edate, $dbtable){
    $this->dboperationalfix = $this->load->database("operational_report",TRUE);
    $this->dboperationalfix->where("trip_mileage_vehicle_id", $vehicledevice);
    $this->dboperationalfix->where("trip_mileage_geofence_start", "0");
    $this->dboperationalfix->where("trip_mileage_geofence_end !=", "0");
    $this->dboperationalfix->where("trip_mileage_start_time >= ", $sdateorigin);
    $this->dboperationalfix->where("trip_mileage_end_time <= ", $edate);
    $this->dboperationalfix->order_by("trip_mileage_geofence_end", "ASC");
    // $this->dboperationalfix->where("trip_mileage_start_time >= ", $sdate);
    $this->dboperationalfix->limit("1");
    $q          = $this->dboperationalfix->get($dbtable);
    return $q->result();

    // echo "<pre>";
    // var_dump($vehicledevice.'||'.$sdateorigin.'||'. $sdate.'||'. $edate.'||'. $dbtable);die();
    // echo "<pre>";
  }

  function getquery3($vehicledevice, $sdate, $edate, $dbtable){
    $this->dboperationalfix = $this->load->database("operational_report",TRUE);
    $this->dboperationalfix->where("trip_mileage_vehicle_id", $vehicledevice);
    $this->dboperationalfix->where("trip_mileage_geofence_start", "0");
    $this->dboperationalfix->where("trip_mileage_geofence_end", "0");
    $this->dboperationalfix->where("trip_mileage_engine", "0");
    $this->dboperationalfix->where("trip_mileage_start_time >= ", $sdate);
    $this->dboperationalfix->where("trip_mileage_end_time <= ", $edate);
    $q          = $this->dboperationalfix->get($dbtable);
    return $q->result();
    // echo "<pre>";
    // var_dump($q->result());die();
    // echo "<pre>";
  }

  function checkthisout($table, $id){
    $this->dboperationalfix = $this->load->database("operational_report",TRUE);
    $this->dboperationalfix->where("trip_mileage_id", $id);
    $q          = $this->dboperationalfix->get($table);
    return $q->num_rows();
    // echo "<pre>";
    // var_dump($q->num_rows());die();
    // echo "<pre>";
  }

  function checkthisout2($table, $vdevice, $sdate){
    $this->dboperationalfix = $this->load->database("operational_report",TRUE);
    $this->dboperationalfix->select("dailyactivity_vehicle_device", $vdevice);
    $this->dboperationalfix->where("dailyactivity_from_origin", $sdate);
    $q          = $this->dboperationalfix->get($table)->result();
    return $q;
    // echo "<pre>";
    // var_dump($table.'||'. $vdevice.'||'. $sdate);
    // echo "<pre>";
  }

}
