<?php
class Driver_model extends Model {

    function Driver_model()
    {
		parent::Model();
    }

    function getalldatabyuserid($table, $where, $wherenya){
      return $this->db->query(
        "SELECT * FROM $table where $where = '$wherenya' && vehicle_status != '3' order by vehicle_no asc"
      )->result_array();
    }

    function index1(){
      $this->db = $this->load->database("default", true);
			$this->db->select("vehicle_id, vehicle_no, vehicle_name");
			$this->db->where("vehicle_status != '3'");
			$q2 = $this->db->get("vehicle");
			return $q2->result();
    }

    function get1($table, $where, $wherenya){
      return $this->db->query(
        "SELECT * FROM $table where $where = '$wherenya'"
      )->result_array();
    }

    function getalldatadbtransporter($tableprefix, $where, $wherenya){
      $this->dbtransporter = $this->load->database("transporter", true);
  		$this->dbtransporter->select("*");
  		$this->dbtransporter->from($tableprefix);
		  $this->dbtransporter->where($where, $wherenya);
      $q = $this->dbtransporter->get()->result_array();

      return $q;
      $this->dbtransporter->close();
    }

    function updateDatadbtransporter($tableprefix, $where, $wherenya, $datanya){
      $this->dbtransporter = $this->load->database("transporter", true);
      $this->dbtransporter->where($where, $wherenya);
      return $this->dbtransporter->update($tableprefix, $datanya);
    }

    function insertDataDbTransporter($tableprefix, $datanya){
      $this->dbtransporter = $this->load->database("transporter", true);
      return $this->dbtransporter->insert($tableprefix, $datanya);
    }



}
?>
