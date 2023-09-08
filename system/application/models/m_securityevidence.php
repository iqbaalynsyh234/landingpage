<?php
class M_securityevidence extends Model {

    function getdevice(){
      $user_level      = $this->sess->user_level;
      $user_company    = $this->sess->user_company;
      $user_subcompany = $this->sess->user_subcompany;
      $user_group      = $this->sess->user_group;
      $user_subgroup   = $this->sess->user_subgroup;
      $user_id         = $this->sess->user_id;
      $user_id_fix     = "";

      if($user_id == "1445"){
        $user_id_fix = $user_id; //tag
      }else{
        $user_id_fix = $this->sess->user_id;
      }

      //GET DATA FROM DB
      $this->db     = $this->load->database("default", true);
      $this->db->select("*");
      $this->db->order_by("vehicle_name","asc");

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

      $this->db->where_in("vehicle_type", array("MV03"));
      $this->db->where("vehicle_status <>", 3);
      $q       = $this->db->get("vehicle");
      return  $q->result_array();
    }

    function getalarmcategory(){
      $this->dbalarm = $this->load->database("webtracking_ts", true);
      $this->dbalarm->select("*");
      $this->dbalarm->where("webtracking_alarmcategory_flag", 1);
      $this->dbalarm->order_by("webtracking_alarmcategory_name","asc");
      $q        = $this->dbalarm->get("webtracking_ts_alarmcategory");
      return  $q->result_array();
    }

    function getalarmsubcategory($id){
      $this->dbalarm = $this->load->database("webtracking_ts", true);
      // $this->dbalarm->select("*");
        if ($id != "All") {
          $this->dbalarm->where("webtracking_alarmsubcategory_categoryid", $id);
        }else {
          $this->dbalarm->where("webtracking_alarmsubcategory_flag", 1);
        }
      $this->dbalarm->order_by("webtracking_alarmsubcategory_name","asc");
      $q        = $this->dbalarm->get("webtracking_ts_alarmsubcategory");
      return  $q->result_array();
    }

    function getalarmtype(){
      $this->dbalarm = $this->load->database("webtracking_ts", true);
      $this->dbalarm->select("*");
      $this->dbalarm->where("alarm_status", 1);
      $this->dbalarm->order_by("alarm_name","asc");
      $q        = $this->dbalarm->get("webtracking_ts_alarm");
      return  $q->result_array();
    }

    function detailalert($typealert){
      if ($typealert[0] == 0) {
        $this->dbalarm = $this->load->database("webtracking_ts", true);
        $this->dbalarm->select("alarm_name");
        $this->dbalarm->where_in("alarm_type", $typealert);
        $q        = $this->dbalarm->get("webtracking_ts_alarm");
        return  $q->result_array();
      }else {
        $this->dbalarm = $this->load->database("webtracking_ts", true);
        $this->dbalarm->select("alarm_name");
        $this->dbalarm->where_in("alarm_type", $typealert);
        $q        = $this->dbalarm->get("webtracking_ts_alarm");
        return  $q->result_array();
      }
    }

    function searchthisreport($table, $vehicle, $startdatefix, $enddatefix, $alarmtype){
      // $vehicle.'-'.$startdate.'-'.$shour.'-'.$enddate.'-'.$ehour.'-'.$alarmtype

  		// echo "<pre>";
  		// var_dump($alarmtype);die();
  		// echo "<pre>";
      $this->dbalarm = $this->load->database("webtracking_kalimantan", true);
      $this->dbalarm->where("alarm_report_vehicle_id", $vehicle);
      $this->dbalarm->where("alarm_report_media", 0);
      $this->dbalarm->where("alarm_report_start_time >=", $startdatefix);
      $this->dbalarm->where("alarm_report_end_time <=", $enddatefix);
        if ($alarmtype != "ALL") {
          $this->dbalarm->where_in('alarm_report_type', $alarmtype);
        }
      // $this->dbalarm->order_by("alarm_report_name","asc");
      $this->dbalarm->order_by("alarm_report_start_time","desc");
      $this->dbalarm->group_by("alarm_report_start_time");
      $q             = $this->dbalarm->get($table);
      return  $q->result_array();
    }

    function getdetailreport($table, $alertid, $sdate){
      $this->dbalarm = $this->load->database("webtracking_kalimantan", true);
      $this->dbalarm->where("alarm_report_vehicle_id", $alertid);
      $this->dbalarm->where("alarm_report_start_time", $sdate);
      $this->dbalarm->where("alarm_report_media", 0);
      $this->dbalarm->group_by("alarm_report_start_time");
      $q             = $this->dbalarm->get($table);
      return  $q->result_array();
    }

    function getdetailreportvideo($table, $alertid, $sdate){
      $this->dbalarm = $this->load->database("webtracking_kalimantan", true);
      $this->dbalarm->select("alarm_report_downloadurl");
      $this->dbalarm->where("alarm_report_vehicle_id", $alertid);
      $this->dbalarm->where("alarm_report_start_time", $sdate);
      $this->dbalarm->where("alarm_report_media", 1);
      $this->dbalarm->group_by("alarm_report_start_time");
      $q             = $this->dbalarm->get($table);
      return  $q->result_array();
    }

    function getsession(){
      $this->dbalarm = $this->load->database("webtracking_ts", true);
      $this->dbalarm->select("*");
      $this->dbalarm->where("sess_type", "LOGIN");
      $this->dbalarm->order_by("sess_lastmodified", "desc");
      $this->dbalarm->limit(1);
      $q        = $this->dbalarm->get("webtracking_ts_sess");
      return  $q->result_array();
    }

}
?>
