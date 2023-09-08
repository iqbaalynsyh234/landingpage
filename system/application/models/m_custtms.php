<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_custtms extends Model {

	function M_custtms(){

	parent::Model();
	$this->load->model("gpsmodel");
	$this->load->model("m_poipoolmaster");
	}

	function getcusttms(){
    $userid          = $this->sess->user_id;
		$user_company    = $this->sess->user_company;
		$user_subcompany = $this->sess->user_subcompany;
		$user_group      = $this->sess->user_group;
		$user_subgroup   = $this->sess->user_subgroup;
		$user_level		   = $this->sess->user_level;

    $this->dbtransporter = $this->load->database("transporter", true);
    $this->dbtransporter->order_by("customer_name", "ASC");

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

  function insertdata($table, $data){
    $this->dbtransporter = $this->load->database("transporter", true);
    return $this->dbtransporter->insert($table, $data);
  }

	function getthiscustomer($idcustomer){
		$this->dbtransporter = $this->load->database("transporter", true);
		$this->dbtransporter->where("customer_id", $idcustomer);
		$q    = $this->dbtransporter->get("joborder_customer");
		return  $q->result_array();
	}

	function updatedata($table, $custid, $data){
		$this->dbtransporter = $this->load->database("transporter", true);
		$this->dbtransporter->where("customer_id", $custid);
		return $this->dbtransporter->update($table, $data);
	}

	function deletedata($table, $custid, $data){
		$this->dbtransporter = $this->load->database("transporter", true);
		$this->dbtransporter->where("customer_id", $custid);
		return $this->dbtransporter->update($table, $data);
	}

}

?>
