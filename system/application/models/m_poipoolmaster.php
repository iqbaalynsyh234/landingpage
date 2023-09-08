<?php
class M_poipoolmaster extends Model {

  function getfromdblive($table, $dblive){
    $this->db->dblive = $this->load->database($dblive, true);
		$q                  = $this->db->dblive->get($table);
		return $result      = $q->result_array();
  }

  function searchdblivedata($table, $dblive, $vehicle_device){
    $this->db->dblive = $this->load->database($dblive, true);
    $this->db->dblive->select("*");
    $this->db->dblive->where("gps_name", $vehicle_device);
		$q                  = $this->db->dblive->get($table);
		return $result      = $q->result_array();
  }

  function getmastervehiclebyarea($companyid){
    if($this->sess->user_id == "1445"){
      $user_id =  $this->sess->user_id; //tag
    }else{
      $user_id = $this->sess->user_id;
    }

    $user_level      = $this->sess->user_level;
		$user_company    = $this->sess->user_company;
		$user_subcompany = $this->sess->user_subcompany;
		$user_group      = $this->sess->user_group;
		$user_subgroup   = $this->sess->user_subgroup;
		$user_dblive 	   = $this->sess->user_dblive;
		$user_id_fix     = $user_id;
		//GET DATA FROM DB
		$this->db     = $this->load->database("default", true);
		$this->db->select("*");
		$this->db->order_by("vehicle_no","asc");

		if($user_level == 1){
			$this->db->where("vehicle_user_id", $user_id_fix);
		}else if($user_level == 2){
			$this->db->where("vehicle_company", $user_company);
		}else if($user_level == 3){
			$this->db->where("vehicle_subcompany", $user_subcompany);
		}else if($user_level == 4){
			$this->db->where("vehicle_group", $user_group);
		}else if($user_level == 5){
			$this->db->where("vehicle_subgroup", $user_subgroup);
		}else{
			$this->db->where("vehicle_no",99999);
		}

		$this->db->where("vehicle_status <>", 3);
    $this->db->where("vehicle_company", $companyid);
		$this->db->where("vehicle_gotohistory", 0);
		$this->db->where("vehicle_autocheck is not NULL");
		$q       = $this->db->get("vehicle");
		return $q->result_array();
  }



  function getmastervehicletest(){
    if($this->sess->user_id == "1445"){
      $user_id =  $this->sess->user_id; //tag
    }else{
      $user_id = $this->sess->user_id;
    }

    $user_level      = $this->sess->user_level;
		$user_company    = $this->sess->user_company;
		$user_subcompany = $this->sess->user_subcompany;
		$user_group      = $this->sess->user_group;
		$user_subgroup   = $this->sess->user_subgroup;
		$user_dblive 	   = $this->sess->user_dblive;
		$user_id_fix     = $user_id;
		//GET DATA FROM DB
		$this->db     = $this->load->database("default", true);
		$this->db->select("*");
		$this->db->order_by("vehicle_no","asc");

		if($user_level == 1){
			$this->db->where("vehicle_user_id", $user_id_fix);
		}else if($user_level == 2){
			$this->db->where("vehicle_company", $user_company);
		}else if($user_level == 3){
			$this->db->where("vehicle_subcompany", $user_subcompany);
		}else if($user_level == 4){
			$this->db->where("vehicle_group", $user_group);
		}else if($user_level == 5){
			$this->db->where("vehicle_subgroup", $user_subgroup);
		}else{
			$this->db->where("vehicle_no",99999);
		}

    $this->db->where("vehicle_no", "TA130 B9288TCN");
		$this->db->where("vehicle_status <>", 3);
		$this->db->where("vehicle_gotohistory", 0);
		$this->db->where("vehicle_autocheck is not NULL");
		$q       = $this->db->get("vehicle");
		return $q->result_array();
  }

  function getmastervehicle(){
    if($this->sess->user_id == "1445"){
      $user_id =  $this->sess->user_id; //tag
    }else{
      $user_id = $this->sess->user_id;
    }

    $user_level      = $this->sess->user_level;
		$user_company    = $this->sess->user_company;
		$user_subcompany = $this->sess->user_subcompany;
		$user_group      = $this->sess->user_group;
		$user_subgroup   = $this->sess->user_subgroup;
		$user_dblive 	   = $this->sess->user_dblive;
		$user_id_fix     = $user_id;

    // echo "<pre>";
		// var_dump($user_level.'-'.$user_company.'-'.$user_subcompany.'-'.$user_group.'-'.$user_subgroup.'-'.$user_dblive.'-'.$user_id_fix);die();
		// echo "<pre>";

		//GET DATA FROM DB
		$this->db     = $this->load->database("default", true);
		$this->db->select("*");
		$this->db->order_by("vehicle_no","asc");

		if($user_level == 1){
			$this->db->where("vehicle_user_id", $user_id_fix);
		}else if($user_level == 2){
			$this->db->where("vehicle_company", $user_company);
      $this->db->where("vehicle_user_id", $this->sess->user_parent);
		}else if($user_level == 3){
			$this->db->where("vehicle_subcompany", $user_subcompany);
      $this->db->where("vehicle_user_id", $this->sess->user_parent);
		}else if($user_level == 4){
			$this->db->where("vehicle_group", $user_group);
      $this->db->where("vehicle_user_id", $this->sess->user_parent);
		}else if($user_level == 5){
			$this->db->where("vehicle_subgroup", $user_subgroup);
      $this->db->where("vehicle_user_id", $this->sess->user_parent);
		}else{
			$this->db->where("vehicle_no",99999);
		}

		$this->db->where("vehicle_status <>", 3);
		$this->db->where("vehicle_gotohistory", 0);
		$this->db->where("vehicle_autocheck is not NULL");
		$q       = $this->db->get("vehicle");
		return $q->result_array();
  }

  function getmastervehiclebydevid($device_id){
    if($this->sess->user_id == "1445"){
      $user_id =  $this->sess->user_id; //tag
    }else{
      $user_id = $this->sess->user_id;
    }

    $user_level      = $this->sess->user_level;
		$user_company    = $this->sess->user_company;
		$user_subcompany = $this->sess->user_subcompany;
		$user_group      = $this->sess->user_group;
		$user_subgroup   = $this->sess->user_subgroup;
		$user_dblive 	   = $this->sess->user_dblive;
		$user_id_fix     = $user_id;
		//GET DATA FROM DB
		$this->db     = $this->load->database("default", true);
		$this->db->select("*");
		$this->db->order_by("vehicle_no","asc");

		if($user_level == 1){
			$this->db->where("vehicle_user_id", $user_id_fix);
		}else if($user_level == 2){
			$this->db->where("vehicle_company", $user_company);
		}else if($user_level == 3){
			$this->db->where("vehicle_subcompany", $user_subcompany);
		}else if($user_level == 4){
			$this->db->where("vehicle_group", $user_group);
		}else if($user_level == 5){
			$this->db->where("vehicle_subgroup", $user_subgroup);
		}else{
			$this->db->where("vehicle_no",99999);
		}

		$this->db->where("vehicle_status <>", 3);
    $this->db->where("vehicle_device", $device_id);
		$this->db->where("vehicle_gotohistory", 0);
		$this->db->where("vehicle_autocheck is not NULL");
		$q       = $this->db->get("vehicle");
		return $q->result_array();
  }

  function getLastPosition($table, $dblive, $gps_name){
    // print_r("devicenya : ".$gps_name);
    $this->db->dblive = $this->load->database($dblive, true);
    // $this->db->dblive->select("gps_name, vehicle_autocheck");
    $this->db->dblive->where("gps_name", $gps_name);
		$q                  = $this->db->dblive->get($table);
		return $result      = $q->result_array();
  }

  function insert_data($table, $data){
    return $this->db->insert($table, $data);
  }

  function getalldata($table, $user_id){
    $this->db->where("poi_creator_id", $user_id);
		$this->db->where("poi_flag", 0);
		$q             = $this->db->get($table);
		return $result = $q->result_array();
  }

  function getalldatabypoiid($table, $where, $where2, $id){
    $where;
		$this->db->where("poi_flag", 0);
    $this->db->where($where2, $id);
		$q             = $this->db->get($table);
		return $result = $q->result_array();
  }

  function update_date($table, $where, $id, $data){
    $this->db->where("poi_id", $id);
    return $this->db->update($table, $data);
  }

  function delete_data($table, $where, $iddelete, $data){
    $this->db->where($where, $iddelete);
    return $this->db->update($table, $data);
  }

  function getmastervehiclefivereport(){
    if($this->sess->user_id == "1445"){
      $user_id =  $this->sess->user_id; //tag
    }else{
      $user_id = $this->sess->user_id;
    }

    $user_level      = $this->sess->user_level;
    $user_company    = $this->sess->user_company;
    $user_subcompany = $this->sess->user_subcompany;
    $user_group      = $this->sess->user_group;
    $user_subgroup   = $this->sess->user_subgroup;
    $user_dblive 	   = $this->sess->user_dblive;
    $user_id_fix     = $user_id;
    //GET DATA FROM DB
    $this->db     = $this->load->database("default", true);
    $this->db->select("*");
    $this->db->order_by("vehicle_no","asc");

    if($user_level == 1){
      $this->db->where("vehicle_user_id", $user_id_fix);
    }else if($user_level == 2){
      $this->db->where("vehicle_company", $user_company);
    }else if($user_level == 3){
      $this->db->where("vehicle_subcompany", $user_subcompany);
    }else if($user_level == 4){
      $this->db->where("vehicle_group", $user_group);
    }else if($user_level == 5){
      $this->db->where("vehicle_subgroup", $user_subgroup);
    }else{
      $this->db->where("vehicle_no",99999);
    }

    $this->db->where("vehicle_status <>", 3);
    $q       = $this->db->get("vehicle");
    return $q->result_array();
  }

  function searchmasterdata($table, $key){
    if($this->sess->user_id == "1445"){
      $user_id =  $this->sess->user_id; //tag
    }else{
      $user_id = $this->sess->user_id;
    }

    $user_level      = $this->sess->user_level;
		$user_company    = $this->sess->user_company;
		$user_subcompany = $this->sess->user_subcompany;
		$user_group      = $this->sess->user_group;
		$user_subgroup   = $this->sess->user_subgroup;
		$user_dblive 	   = $this->sess->user_dblive;
		$user_id_fix     = $user_id;
		//GET DATA FROM DB
		$this->db     = $this->load->database("default", true);
		$this->db->select("*");
    $this->db->like("vehicle_no", $key);

		if($user_level == 1){
			$this->db->where("vehicle_user_id", $user_id_fix);
		}else if($user_level == 2){
			$this->db->where("vehicle_company", $user_company);
		}else if($user_level == 3){
			$this->db->where("vehicle_subcompany", $user_subcompany);
		}else if($user_level == 4){
			$this->db->where("vehicle_group", $user_group);
		}else if($user_level == 5){
			$this->db->where("vehicle_subgroup", $user_subgroup);
		}else{
			$this->db->where("vehicle_no",99999);
		}

		$this->db->where("vehicle_status <>", 3);
		$this->db->where("vehicle_gotohistory", 0);
		$this->db->where("vehicle_autocheck is not NULL");
		$q       = $this->db->get("vehicle");
		return $q->result_array();
  }

  function getGeofence_location($longitude, $ew, $latitude, $ns, $vehicle_user) {
    // echo "<pre>";
		// var_dump($longitude.'-'.$ew.'-'.$latitude.'-'.$ns.'-'.$vehicle_user);die();
		// echo "<pre>";
		$this->db = $this->load->database("default", true);
		$lng = getLongitude($longitude, $ew);
		$lat = getLatitude($latitude, $ns);

		$sql = sprintf("
					SELECT 	*
					FROM 	%sgeofence
					WHERE 	TRUE
							AND CONTAINS(geofence_polygon, GEOMFROMTEXT('POINT(%s %s)'))
							AND (geofence_user = '%s' )
                            AND (geofence_status = 1)
					LIMIT 1 OFFSET 0", $this->db->dbprefix, $lng, $lat, $vehicle_user);

		$q = $this->db->query($sql);

		if ($q->num_rows() > 0)
		{
			$row = $q->result();
            $total = $q->num_rows();
            for ($i=0;$i<$total;$i++){
            $data = $row[$i]->geofence_name;
            return $data;
            }

		}else
        {
            return false;
        }

	}

  function getGeofence_location_other($longitude, $latitude, $vehicle_user) {

		$this->db = $this->load->database("default", true);
		$lng = $longitude;
		$lat = $latitude;

		$sql = sprintf("
					SELECT 	*
					FROM 	%sgeofence
					WHERE 	TRUE
							AND CONTAINS(geofence_polygon, GEOMFROMTEXT('POINT(%s %s)'))
							AND (geofence_user = '%s' )
                            AND (geofence_status = 1)
					LIMIT 1 OFFSET 0", $this->db->dbprefix, $lng, $lat, $vehicle_user);
		$q = $this->db->query($sql);
		if ($q->num_rows() > 0)
		{
			$row = $q->result();
            $total = $q->num_rows();
            for ($i=0;$i<$total;$i++){
            $data = $row[$i]->geofence_name;
            return $data;
            }

		}else
        {
            return false;
        }

	}

  // DESTINATION MASTER
  function getdestinationbyid($table, $where, $v_device){
    $this->db->where($where, $v_device);
    $this->db->where("dest_endshowing_date", date("Y-m-d"));
		$q       = $this->db->get($table);
		return $q->result_array();
  }


  // TESTING FOR JS
  function getmastervehiclejs(){
    if($this->sess->user_id == "1445"){
      $user_id =  $this->sess->user_id; //tag
    }else{
      $user_id = $this->sess->user_id;
    }

    $user_level      = $this->sess->user_level;
		$user_company    = $this->sess->user_company;
		$user_subcompany = $this->sess->user_subcompany;
		$user_group      = $this->sess->user_group;
		$user_subgroup   = $this->sess->user_subgroup;
		$user_dblive 	   = $this->sess->user_dblive;
		$user_id_fix     = $user_id;

    // $user_level      = 1;
		// $user_company    = 1806;
		// $user_subcompany = 0;
		// $user_group      = 0;
		// $user_subgroup   = 0;
		// $user_dblive 	   = "webtracking_gps_tag_live";
		// $user_id_fix     = 3212;
    // 1-48-0-0-0-webtracking_gps_powerblock_live-1147
    // 1-1806-0-0-0-webtracking_gps_tag_live-3212

    // echo "<pre>";
		// var_dump($user_level.'-'.$user_company.'-'.$user_subcompany.'-'.$user_group.'-'.$user_subgroup.'-'.$user_dblive.'-'.$user_id_fix);die();
		// echo "<pre>";

		//GET DATA FROM DB
		$this->db     = $this->load->database("default", true);
		$this->db->select("*");
		$this->db->order_by("vehicle_no","asc");

		if($user_level == 1){
			$this->db->where("vehicle_user_id", $user_id_fix);
		}else if($user_level == 2){
			$this->db->where("vehicle_company", $user_company);
      $this->db->where("vehicle_user_id", $this->sess->user_parent);
		}else if($user_level == 3){
			$this->db->where("vehicle_subcompany", $user_subcompany);
      $this->db->where("vehicle_user_id", $this->sess->user_parent);
		}else if($user_level == 4){
			$this->db->where("vehicle_group", $user_group);
      $this->db->where("vehicle_user_id", $this->sess->user_parent);
		}else if($user_level == 5){
			$this->db->where("vehicle_subgroup", $user_subgroup);
      $this->db->where("vehicle_user_id", $this->sess->user_parent);
		}else{
			$this->db->where("vehicle_no",99999);
		}

		$this->db->where("vehicle_status <>", 3);
		$this->db->where("vehicle_gotohistory", 0);
		$this->db->where("vehicle_autocheck is not NULL");
		$q       = $this->db->get("vehicle");
		return $q->result_array();
  }

  function getdriverdata($vehicleid){
    $this->dbtransporter     = $this->load->database("transporter", true);
		$this->dbtransporter->select("*");
		$this->dbtransporter->where("driver_vehicle", $vehicleid);
		$q       = $this->dbtransporter->get("driver");
		return $q->result_array();
  }

}
