<?php
include "base.php";

class Tools_balrich extends Base {
	function __construct()
	{
		parent::__construct();	
		
		$this->load->model("gpsmodel");
		$this->load->model("smsmodel");
		$this->load->model("configmodel");
		$this->load->library('email');
		$this->load->helper('email');
		$this->load->helper('common');
		
	}
	
	function sync_geoalert($duration="")
	{
		$offset = 0;
		$i = 0;
		$start_time = date("d-m-Y H:i:s");
		
		if($duration == ""){
			$duration = "-1";
		}
		$report = "inout_geofence_";
		
		$nowdate = date('Y-m-d');
		$limitdate = strtotime ($duration.' day' , strtotime ($nowdate." "."00:00:00"));
		$newdate = date('Y-m-d H:i:s' , $limitdate);
		
		//$newdate = date("Y-m-d H:i:s", strtotime("2017-05-16 00:00:00"));
		//$newdate2 = date("Y-m-d H:i:s", strtotime("2017-05-20 23:59:59"));
		
		$m1 = date("F", strtotime($newdate)); 
		$year = date("Y", strtotime($newdate));
		
		switch ($m1)
		{
			case "January":
            $dbtable = $report."januari_".$year;
			break;
			case "February":
            $dbtable = $report."februari_".$year;
			break;
			case "March":
            $dbtable = $report."maret_".$year;
			break;
			case "April":
            $dbtable = $report."april_".$year;
			break;
			case "May":
            $dbtable = $report."mei_".$year;
			break;
			case "June":
            $dbtable = $report."juni_".$year;
			break;
			case "July":
            $dbtable = $report."juli_".$year;
			break;
			case "August":
            $dbtable = $report."agustus_".$year;
			break;
			case "September":
            $dbtable = $report."september_".$year;
			break;
			case "October":
            $dbtable = $report."oktober_".$year;
			break;
			case "November":
            $dbtable = $report."november_".$year;
			break;
			case "December":
            $dbtable = $report."desember_".$year;
			break;
		}
		
		printf("PROSES SELECT GEOFENCE ALERT %s DAY \r\n", $duration);
		$this->db->order_by("geoalert_id","asc");
		$this->db->where("geoalert_time >=", $newdate);
		$q = $this->db->get("geofence_alert_balrich");
		$rows = $q->result();
		$total = count($rows);
		printf("GET GEO ALERT : %s \r\n", $total); 
		
		foreach($rows as $row)
		{
			
			if (($i+1) < $offset)
			{
				$i++;
				continue;
			}
			
			printf("PROCESS NUMBER	 : %s \r\n", ++$i." of ".$total);
			printf("PROSES GEO ALERT : ID GEOFENCE %s, ID GEOALERT %s, GEOALERT TIME %s \r\n", $row->geoalert_geofence, $row->geoalert_id, $row->geoalert_time); 
			$this->dbreport = $this->load->database("balrich_report", true);
		
			unset($data);
			$data["geoalert_id"] = $row->geoalert_id;
			$data["geoalert_vehicle"] = $row->geoalert_vehicle;
			$data["geoalert_vehicle_type"] = $row->geoalert_vehicle_type;
			$data["geoalert_vehicle_company"] = $row->geoalert_vehicle_company;
			$data["geoalert_direction"] = $row->geoalert_direction; 
			$data["geoalert_door"] = $row->geoalert_door;
			$data["geoalert_engine"] = $row->geoalert_engine; 
			$data["geoalert_speed"] = $row->geoalert_speed;
			$data["geoalert_date"] = date("Y-m-d", strtotime ($row->geoalert_time));
			$data["geoalert_time"] = date("H:i:s", strtotime ($row->geoalert_time));
			$data["geoalert_lat"] = $row->geoalert_lat;
			$data["geoalert_lng"] = $row->geoalert_lng;
			$data["geoalert_geofence"] = $row->geoalert_geofence;
			$data["geoalert_geofence_name"] = $row->geoalert_geofence_name;
			$data["geofence_created"] = $row->geofence_created;
			$data["geofence_lastchecked"] = $row->geofence_lastchecked;
			
			$this->dbreport->select("geoalert_id");
			$this->dbreport->where("geoalert_id", $row->geoalert_id);
			$qu = $this->dbreport->get($dbtable);
			$ru = $qu->row();
			if (count($ru)>0)
			{
				printf("UPDATE GEOALERT IN DB REPORT : ID GEOFENCE %s, ID GEOALERT %s, GEOALERT TIME %s \r\n", $row->geoalert_geofence, $row->geoalert_id, $row->geoalert_time); 
				$this->dbreport->where("geoalert_id", $row->geoalert_id);	
				$this->dbreport->update($dbtable,$data);
			}
			else
			{
				printf("INSERT GEOFEALERT IN DB REPORT : ID GEOFENCE %s, ID GEOALERT %s, GEOALERT TIME %s \r\n", $row->geoalert_geofence, $row->geoalert_id, $row->geoalert_time); 
				$this->dbreport->insert($dbtable,$data);
			}
			
			printf("FINISH SYNC GEO ALERT : ID GEOFENCE %s, ID GEOALERT %s, GEOALERT TIME %s \r\n", $row->geoalert_geofence, $row->geoalert_id, $row->geoalert_time); 
			printf("=============================================== \r\n"); 
			
		}
		
		$finish_time = date("d-m-Y H:i:s");
		
		//Send Email
		$cron_name = "BALRICH - SYNC GEOALERT REPORT";
		
		unset($mail);
		$mail['subject'] =  $cron_name.": ".$newdate;
		$mail['message'] = 
"
Cron Report Status :

Nama Cron  : ".$cron_name." : ".$newdate."
Start Cron : ".$start_time."
End Cron   : ".$finish_time."
Total Data : ".$total."
End Data   : "."( ".$i." / ".$total." )"."
Status     : Finish

Thanks

";
		$mail['dest'] = "budiyanto@lacak-mobil.com";
		$mail['bcc'] = "report.dokar@gmail.com";
		$mail['sender'] = "no-reply@lacak-mobil.com";
		lacakmobilmail($mail);
		
		printf("SEND EMAIL OK \r\n");
		
		return;   
		
	}
	
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
