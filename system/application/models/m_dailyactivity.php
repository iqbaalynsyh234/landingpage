<?php
  if (!defined('BASEPATH')) exit('No direct script access allowed');
  class M_dailyactivity extends Model {

    function getreport($table, $vehicle, $sdate, $edate){
      $this->dboperationalfix = $this->load->database("operational_report",TRUE);
      $this->dboperationalfix->where("dailyactivity_vehicle_device", $vehicle);
      $this->dboperationalfix->where("dailyactivity_from_origin >=", $sdate);
      $this->dboperationalfix->where("dailyactivity_backto_origin <=", $edate);
      $this->dboperationalfix->order_by("dailyactivity_from_origin", "ASC");
      $q          = $this->dboperationalfix->get($table)->result_array();
      return $q;
    }

    function getdataperjalanan($table, $vehicle, $starttime, $endtime, $parametersecond){
      $this->dboperationalfix = $this->load->database("operational_report",TRUE);
      $this->dboperationalfix->select("count(trip_mileage_duration_sec) as jumlahdropbarang, sum(trip_mileage_duration_sec) as totalwaktudropbarang");
      $this->dboperationalfix->where("trip_mileage_vehicle_id", $vehicle);
      $this->dboperationalfix->where("trip_mileage_start_time >=", $starttime);
      $this->dboperationalfix->where("trip_mileage_end_time <=", $endtime);
      $this->dboperationalfix->order_by("trip_mileage_start_time", "ASC");
      $this->dboperationalfix->where("trip_mileage_duration_sec >= ", $parametersecond);
      $q          = $this->dboperationalfix->get($table)->result_array();
      return $q;
    }

    function getkmtempuhtotal($table, $vehicle, $starttime, $endtime){
      // echo "<pre>";
      // var_dump($table.'||'. $vehicle.'||'. $starttime.'||'. $endtime);die();
      // echo "<pre>";
      $this->dboperationalfix = $this->load->database("operational_report",TRUE);
      $this->dboperationalfix->select("sum(trip_mileage_trip_mileage) as totalkmtempuh");
      $this->dboperationalfix->where("trip_mileage_vehicle_id", $vehicle);
      $this->dboperationalfix->where("trip_mileage_start_time >=", $starttime);
      $this->dboperationalfix->where("trip_mileage_end_time <=", $endtime);
      $this->dboperationalfix->order_by("trip_mileage_start_time", "ASC");
      $q          = $this->dboperationalfix->get($table)->result_array();
      return $q;
    }

    function getvehicledata($vehicledevice){
      //GET DATA FROM DB
      $this->db     = $this->load->database("default", true);
      $this->db->select("*");
      $this->db->order_by("vehicle_name","asc");
      $this->db->where("vehicle_device", $vehicledevice);
      $this->db->where("vehicle_status <>", 3);
      $q       = $this->db->get("vehicle");
      return $q->result_array();
    }

  }
