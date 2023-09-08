<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_integrationmodul extends Model {

  function allvehicle(){
    $user_level      = $this->sess->user_level;
    $user_company    = $this->sess->user_company;
    $user_subcompany = $this->sess->user_subcompany;
    $user_group      = $this->sess->user_group;
    $user_subgroup   = $this->sess->user_subgroup;

    if($this->sess->user_id == "1445"){
      $user_id = $this->sess->user_id; //tag
    }else{
      $user_id = $this->sess->user_id;
    }

    $user_id_fix     = $user_id;

    // echo "<pre>";
    // var_dump($user_id_fix);die();
    // echo "<pre>";

    //GET DATA FROM DB
    $this->db     = $this->load->database("default", true);
    $this->db->select("*");
    $this->db->order_by("vehicle_name","asc");

    if($user_level == 1 || $user_level == 0){
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

  function allcustomer(){
    $user_level      = $this->sess->user_level;
    $user_company    = $this->sess->user_company;
    $user_subcompany = $this->sess->user_subcompany;
    $user_group      = $this->sess->user_group;
    $user_subgroup   = $this->sess->user_subgroup;
    $user_parent     = $this->sess->user_parent;

    if($this->sess->user_id == "1445"){
      $user_id = $this->sess->user_id; //tag
    }else{
      $user_id = $this->sess->user_id;
    }

    $user_id_fix     = $user_id;
    //GET DATA FROM DB
    $this->db     = $this->load->database("default", true);
    $this->db->select("*");
    $this->db->order_by("group_name","asc");

    if($user_level == 1){
      $this->db->where("group_parent", $user_parent);
    }else if($user_level == 2){
      $this->db->where("group_company", $user_company);
    }else if($user_level == 3){
      $this->db->where("group_subcompany", $user_subcompany);
    }else{
      $this->db->where("group_id",99999);
    }

    $this->db->where("group_flag", 0);
    $q       = $this->db->get("group");
    return $q->result_array();
  }

  function updatevehiclegroup($vehicleid, $data){
    $this->db     = $this->load->database("default", true);
    $this->db->where("vehicle_id", $vehicleid);
    return $this->db->update("vehicle", $data);
  }

  function abcargodataintegrasi(){
    if($this->sess->user_id == "1445"){
      $user_id = $this->sess->user_id; //tag
    }else{
      $user_id = $this->sess->user_id;
    }

    $user_id_fix     = $user_id;
    //GET DATA FROM DB
    $this->db->transporter     = $this->load->database("transporter", true);
    $this->db->transporter->select("*");
    $this->db->transporter->order_by("integration_submit", "DESC");

    // $this->db->transporter->where("integration_status", 0);
    $q       = $this->db->transporter->get("integration_modul");
    return $q->result_array();
  }

  function submitabcargo($table, $data){
    $this->db->transporter     = $this->load->database("transporter", true);
    return $this->db->transporter->insert($table, $data);
  }

  function updateUmum($database, $table, $where, $wherenya, $data){
    $this->db     = $this->load->database($database, true);
    $this->db->where($where, $wherenya);
    return $this->db->update($table, $data);
  }


}
