<?php
include "base.php";

class Cronjobs_tmsmodul extends Base {

  function Cronjobs_tmsmodul()
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

	function lastposition()
	{
    // $tms_user = array('1857', '1783');
		// date_default_timezone_set('UTC');
		$this->dbtransporter   = $this->load->database("transporter",true);

		$this->dbtransporter->select("*");
		$this->dbtransporter->where("order_flag", 0);
    $this->dbtransporter->where_in("order_status", array("0", "1"));
		// $this->dbtransporter->where_in("order_user_company", $tms_user);
		$q = $this->dbtransporter->get("joborder");

    // echo "<pre>";
    // var_dump($q->result());die();
    // echo "<pre>";

		if ($q->num_rows() == 0)
		{
			echo "Tidak ada devices yang aktif !\r\n";
			return;
		}

		$rows = $q->result();

    // echo "<pre>";
    // var_dump($rows);die();
    // echo "<pre>";

    for ($i=0; $i < sizeof($rows); $i++) {
      printf("\r\n\r\n[%s WIB] %02d %s %s\r\n", date("Y-m-d H:i:s"), $i, $rows[$i]->order_vehicle_device, $rows[$i]->order_vehicle_no);
			$this->dogeofencealert($rows[$i]);
    }
		printf("----- PENGECEKAN SELESAI \r\n");
	}

	function dogeofencealert($device)
	{
    $order_user_company    = $device->order_user_company;
    $order_user_subcompany = $device->order_user_subcompany;

    // echo "<pre>";
    // var_dump($device->order_id.'-'.$order_user_company.'-'.$order_user_subcompany);die();
    // echo "<pre>";

    $origincoordinate    = "";
    $idforcekorigincoord = "";
    if ($order_user_subcompany == "" || $order_user_subcompany == "0" || $order_user_subcompany == NULL) {
      $idforcekorigincoord = $order_user_company;
      $this->dbtransporter   = $this->load->database("transporter",true);
  		$this->dbtransporter->select("*");
  		$this->dbtransporter->where("origin_company_id", $idforcekorigincoord);
  		// $this->dbtransporter->where_in("order_user_company", $tms_user);
  		$q = $this->dbtransporter->get("branch_origin")->result();
      $origincoordinatelat = $q[0]->origin_lat;
      $origincoordinatelng = $q[0]->origin_lng;
    }else {
      $idforcekorigincoord = $order_user_subcompany;
      $this->dbtransporter = $this->load->database("transporter",true);
  		$this->dbtransporter->select("*");
  		$this->dbtransporter->where("origin_subbranch_subcompanyid", $idforcekorigincoord);
  		// $this->dbtransporter->where_in("order_user_company", $tms_user);
  		$q = $this->dbtransporter->get("subbranch_origin")->result();
      $origincoordinatelat = $q[0]->origin_subbranch_lat;
      $origincoordinatelng = $q[0]->origin_subbranch_lng;
    }

    // echo "<pre>";
    // var_dump($origincoordinatelat.'-'.$origincoordinatelng);die();
    // echo "<pre>";

		$nowdate     = date("Y-m-d H:i:s");
		$devices     = explode("@",$device->order_vehicle_device);

    // GET VEHICLE DATA
    $this->db    = $this->load->database("default",true);
    $this->db->select("*");
    $this->db->where("vehicle_device", $device->order_vehicle_device);
    $q           = $this->db->get("vehicle");
    $vehicledata = $q->result();
    $deviceimei = $vehicledata[0]->vehicle_imei;

    // GET CURRENT POSITION
    $coordinate = explode(",",$device->order_jobordercust_coordinate);
    $latTujuan  = $coordinate[0];
    $lngTujuan  = $coordinate[1];
    $orderid    = $device->order_id;

		//get job file
		$distance      = 999999999999999999; //default terjauh

    $gps        = $this->gpsmodel->GetLastInfo($devices[0], $devices[1], true, false, 0, $vehicledata[0]->vehicle_type);
    $currentlat = $gps->gps_latitude_real_fmt;
    $currentlng = $gps->gps_longitude_real_fmt;

    // BILA KELUAR 500 METER DARI ORIGIN MAKA TERHITUNG ON TRIP
    // PERHITUNGAN JARAK DARI ORIGIN KE SAAT INI
    $origindistance    = round($this->getDistance($origincoordinatelat, $origincoordinatelng, $currentlat, $currentlng),1);
    $origindistancefix = $origindistance*1000; //convert km to m
    $jarakforintrip    = 500; //meter

    if ($origindistancefix >= $jarakforintrip) {
      $data = array(
        "order_status"             => 1,
        "order_completed_datetime" => date("Y-m-d H:i:s")
      );
      $this->dbtransporter    = $this->load->database("transporter",true);
      $this->dbtransporter->where("order_id", $device->order_id);
      $this->dbtransporter->update("joborder", $data);
    }

    // echo "<pre>";
    // var_dump($origindistancefix);die();
    // echo "<pre>";

    // PERHITUNGAN JARAK DARI POSISI SAAT INI KE TUJUAN
    $distance_origin1 = round($this->getDistance($currentlat,$currentlng,$latTujuan,$lngTujuan),1);
    $distance_origin = $distance_origin1*1000; //convert km to m
    $jarakdalammeter = 100; //meter

    printf("----- ORDER ID : ".$device->order_id." \r\n");
    printf("----- DESTINATION : ".$device->order_jobordercust_name." \r\n");
    printf("----- ORIGIN 1 : ".$distance_origin1."KM \r\n");
    printf("----- JARAK TEMPUH : ".$distance_origin."M \r\n");
    printf("----- RADIUS : ".$jarakdalammeter."M \r\n");

    //tidak send email
    if ($distance_origin < $jarakdalammeter){
      printf("----- PENGIRIMAN SELESAI  \r\n");
      $data = array(
        "order_status"             => 2,
        "order_completed_datetime" => date("Y-m-d H:i:s")
      );
      $this->dbtransporter    = $this->load->database("transporter",true);
      $this->dbtransporter->where("order_id", $device->order_id);
      $this->dbtransporter->update("joborder", $data);
      return;
    }else{
      printf("----- MASIH DALAM PERJALANAN \r\n");
    }

    // $distance_origin.'-'.$currentlat.'-'.$currentlng.'-'.$latTujuan.'-'.$lngTujuan
    // echo "<pre>";
    // var_dump($device);die();
    // echo "<pre>";
	}

  function getPosition($longitude, $ew, $latitude, $ns){
    $gps_longitude_real     = getLongitude($longitude, $ew);
    $gps_latitude_real      = getLatitude($latitude, $ns);

    $gps_longitude_real_fmt = number_format($gps_longitude_real, 4, ".", "");
    $gps_latitude_real_fmt  = number_format($gps_latitude_real, 4, ".", "");
    $georeverse             = $this->gpsmodel->GeoReverse($gps_latitude_real_fmt, $gps_longitude_real_fmt);
    return $georeverse;
  }

  function getPosition_other($longitude, $latitude)
	{
		$georeverse = $this->gpsmodel->GeoReverse($latitude, $longitude);
		return $georeverse;
	}

	function getDistance($latitude1, $longitude1, $latitude2, $longitude2)
	{
	  $earth_radius = 6371;

	  $dLat = deg2rad($latitude2 - $latitude1);
	  $dLon = deg2rad($longitude2 - $longitude1);

	  $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
	  $c = 2 * asin(sqrt($a));
	  $d = $earth_radius * $c;

	  return $d;
	}

	function getMilleage($lat1, $lon1, $lat2, $lon2, $unit)
	{
	  $theta = $lon1 - $lon2;
	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	  $dist = acos($dist);
	  $dist = rad2deg($dist);
	  $miles = $dist * 60 * 1.1515;
	  $unit = strtoupper($unit);

	  if ($unit == "K") {
		  return ($miles * 1.609344);
	  } else if ($unit == "N") {
		  return ($miles * 0.8684);
	  } else {
		  return $miles;
	  }
	}

	function distancematrix($latitude1, $longitude1, $latitude2, $longitude2, $apikey){
    $dataJson = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".$latitude1.",".$longitude1."&destinations=".$latitude2.",".$longitude2."&key=".$apikey."");
		$data     = json_decode($dataJson,true);
		/*$api_status = $data['rows'][0]['elements'][0]['status']['value'];
		$eta = "";

		if($api_status == "O"){
			$duration_sec = $data['rows'][0]['elements'][0]['duration']['value'];
			$dateinterval = new DateTime($lastgpstime);
			$dateinterval->add(new DateInterval('PT'.$duration_sec.'S'));
			$eta = $dateinterval->format('Y-m-d H:i:s');
		}
		printf("ETA %s \r\n", $eta);*/
        //return $eta;
		return $data;
  }

}
