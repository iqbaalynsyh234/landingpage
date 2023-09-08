<?php
class M_joborder extends Model {

  function getcustomer(){
    $userid          = $this->sess->user_id;
    $user_company    = $this->sess->user_company;
    $user_subcompany = $this->sess->user_subcompany;
    $user_group      = $this->sess->user_group;
    $user_subgroup   = $this->sess->user_subgroup;
    $user_level		   = $this->sess->user_level;
    $user_parent		 = $this->sess->user_parent;

    $this->dbtransporter = $this->load->database("transporter", true);
    $this->dbtransporter->order_by("customer_name", "ASC");
    $this->dbtransporter->select("*");

      if ($user_level == 1) {
        $this->dbtransporter->where("customer_parent", $userid);
      }elseif ($user_level == 2) {
        $this->dbtransporter->where("customer_createdby_company", $user_company);
      }else {
        $this->dbtransporter->where("customer_createdby_subcompany", $user_subcompany);
      }
    $this->dbtransporter->where("customer_flag", 1);

    $q    = $this->dbtransporter->get("joborder_customer");
    return  $q->result_array();
    // echo $user_company; exit();
  }

  function getvehicle(){
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

    // USER ID MEGABAJA
    if ($user_id == "4284") {
      $this->db->order_by("vehicle_nourut","asc");
    }else {
      $this->db->order_by("vehicle_no","asc");
    }

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

  function alldatajob(){
    $userid         = $this->sess->user_id;
		$companyid      = $this->sess->user_company; //1867;  //1867;
		$userlevel      = $this->sess->user_level; //3; //2;
    $usersubcompany = $this->sess->user_subcompany; //33;
    // $datestart = date("Y-m-d")." 00:00:00";
    // $dateuntil = date("Y-m-d")." 23:59:59";
    // echo "<pre>";
		// var_dump($userlevel.'-'.$userid.'-'.$companyid);die();
		// echo "<pre>";
    $this->dbtransporter     = $this->load->database("transporter", true);
    $this->dbtransporter->order_by("order_submit","desc");
    $this->dbtransporter->select("*");
    $this->dbtransporter->where("order_flag", 0);
    if ($userlevel == 1) {
      $this->dbtransporter->where("order_parentid", $userid);
    }elseif ($userlevel == 2) {
      $this->dbtransporter->where("order_user_company", $companyid);
    }else {
      $this->dbtransporter->where("order_user_subcompany", $usersubcompany);
    }
    // $this->db->where("order_tgl_memo >= ", $datestart);
    // $this->db->where("order_tgl_memo <= ", $dateuntil);
    $q       = $this->dbtransporter->get("joborder");
    return  $q->result_array();
  }

  function checkvehicle($table, $vehicle){
    $this->db     = $this->load->database("default", true);
    $this->db->select("*");
    $this->db->where("vehicle_id", $vehicle);
		$q       = $this->db->get($table);
		return $q->result_array();
  }

  function checkcustomer($table, $jobordercustomer){
    $this->dbtransporter     = $this->load->database("transporter", true);
    $this->dbtransporter->select("*");
    $this->dbtransporter->where("customer_id", $jobordercustomer);
		$q       = $this->dbtransporter->get($table);
		return $q->result_array();
  }

  function insertdata($table, $data){
    $this->dbtransporter     = $this->load->database("transporter", true);
    return $this->dbtransporter->insert($table, $data);
  }

  function checkinjoborder($orderid){
    $this->dbtransporter     = $this->load->database("transporter", true);
    $this->dbtransporter->order_by("order_datetime","desc");
    $this->dbtransporter->select("*");
    $this->dbtransporter->where("order_id", $orderid);
    $this->dbtransporter->limit(1);
    $q       = $this->dbtransporter->get("joborder");
    return  $q->result_array();
  }

  function checkinjoborder2($orderid){
    $this->dbtransporter     = $this->load->database("transporter", true);
    $this->dbtransporter->order_by("order_datetime","desc");
    $this->dbtransporter->select("*");
    $this->dbtransporter->where("order_randomid_sharelink", $orderid);
    $this->dbtransporter->limit(1);
    $q       = $this->dbtransporter->get("joborder");
    return  $q->result_array();
  }

  function checkinjoborder3($vehicleid, $startdatetime, $enddatetime){
    $this->dbtransporter     = $this->load->database("transporter", true);
    $this->dbtransporter->order_by("order_datetime","asc");
    $this->dbtransporter->select("*");
    $this->dbtransporter->where("order_vehicle_id", $vehicleid);
    $this->dbtransporter->where("order_flag", 0);
    $this->dbtransporter->where("order_status <>", 2);
    // $this->dbtransporter->where("order_datetime >=", $startdatetime);
    // $this->dbtransporter->where("order_datetime <=", $enddatetime);
    $q       = $this->dbtransporter->get("joborder");
    return  $q->result_array();
  }

  function changestatusnow($table, $orderid, $data){
    $this->dbtransporter     = $this->load->database("transporter", true);
    $this->dbtransporter->where("order_id", $orderid);
    return $this->dbtransporter->update($table, $data);
  }

  function canceljobordernow($table, $orderid, $data){
    $this->dbtransporter     = $this->load->database("transporter", true);
    $this->dbtransporter->where("order_id", $orderid);
    return $this->dbtransporter->update($table, $data);
  }

  function getbranchoffice($table, $userid){
    $this->dbtransporter     = $this->load->database("transporter", true);
    $this->dbtransporter->order_by("branch_name","asc");
    $this->dbtransporter->select("*");
    $this->dbtransporter->where("branch_created_by", $userid);
    $q       = $this->dbtransporter->get($table);
    return  $q->result_array();
  }

  function getthisbranchoffice($table, $companyid){
    $this->dbtransporter     = $this->load->database("transporter", true);
    $this->dbtransporter->order_by("branch_name","asc");
    $this->dbtransporter->select("*");
    $this->dbtransporter->where("branch_company_id", $companyid);
    $q       = $this->dbtransporter->get($table);
    return  $q->result_array();
  }

  function getsubbranchoffice($table, $subcompanyid){
    $this->db     = $this->load->database("default", true);
    $this->db->order_by("subcompany_name","asc");
    $this->db->select("*");
    $this->db->where("subcompany_id", $subcompanyid);
    $q       = $this->db->get($table);
    return  $q->result_array();
  }

  function getsubbranchofficebycompanyid($table, $companyid){
    $this->db     = $this->load->database("default", true);
    $this->db->order_by("subcompany_name","asc");
    $this->db->select("*");
    $this->db->where("subcompany_parent", $companyid);
    $q       = $this->db->get($table);
    return  $q->result_array();
  }

  function getsbubranchofficebyid($table, $branchcompanyid){
    $this->db     = $this->load->database("default", true);
    $this->db->order_by("subcompany_name","asc");
    $this->db->select("*");
    $this->db->where("subcompany_parent", $branchcompanyid);
    $q       = $this->db->get($table);
    return  $q->result_array();
  }

  function vehiclebybranchoffice($table, $branchofficeid){
    $this->db     = $this->load->database("default", true);
    $this->db->order_by("vehicle_name","asc");
    $this->db->select("*");
    $this->db->where("vehicle_company", $branchofficeid);
    $q       = $this->db->get($table);
    return  $q->result_array();
  }

  function getdriver($table){
    $this->dbtransporter     = $this->load->database("transporter", true);
    $this->dbtransporter->order_by("driver_name","asc");
    $this->dbtransporter->select("*");
    $this->dbtransporter->where("driver_company", $this->sess->user_company);
    $q       = $this->dbtransporter->get($table);
    return  $q->result_array();
  }

  function vehiclebysubbranchoffice($table, $subbranchoffice){
    $this->db     = $this->load->database("default", true);
    $this->db->order_by("vehicle_name","asc");
    $this->db->select("*");
    $this->db->where("vehicle_subcompany", $subbranchoffice);
    $q       = $this->db->get($table);
    return  $q->result_array();
  }

  function getvehiclebysubcompanyid($table, $subcompanyid){
    $this->db     = $this->load->database("default", true);
    $this->db->order_by("vehicle_name","asc");
    $this->db->select("*");
    $this->db->where("vehicle_subcompany", $subcompanyid);
    $q       = $this->db->get($table);
    return  $q->result_array();
  }

  function getdatabynopol($vehicleid, $startdatetime, $enddatetime){
    $this->dbtransporter     = $this->load->database("transporter", true);
    $this->dbtransporter->where("order_vehicle_id", $vehicleid);
    $this->dbtransporter->where("order_flag", 0);
    $this->dbtransporter->where("order_datetime >=", $startdatetime);
    $this->dbtransporter->where("order_datetime <=", $enddatetime);
    return $this->dbtransporter->get("joborder")->result_array();
  }

}
