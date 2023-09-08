<?php
include "base.php";

class Autorestart extends Base {
	var $otherdb;
	function Autorestart()
	{
		parent::Base();	
		$this->load->model("gpsmodel_autosetting");
		$this->load->model("vehiclemodel");
		$this->load->model("smsmodel");
		$this->load->model("gpsmodel");
	}
	
	function autocheck_new($groupname="", $userid="", $order="asc")
	{
		date_default_timezone_set("Asia/Jakarta");
		$nowtime = date("Y-m-d H:i:s");
		printf("===================== \r\n");
		
		
		printf("===Search SMS Modem Config at %s \r\n", $nowtime);
		printf("======================================\r\n");
		
		//select list sms modem cron aktif
		$this->db = $this->load->database("default", TRUE);
		//$this->db->select("modem_configdb,modem_cron_active,modem_cron_group");
		$this->db->where("modem_cron_group", $groupname);
		$this->db->where("modem_cronnew_active",1); //new config
		$this->db->where("modem_flag",0);
		$qmodem = $this->db->get("sms_modem");
		if ($qmodem->num_rows() == 0) return;

		$rowsmodem = $qmodem->result();
		$totalmodem = count($rowsmodem);
		
		$data_k = array();
		$data_m = array();
		$data_p = array();
		
		for($x=0;$x<$totalmodem;$x++)
		{
			$modem = $rowsmodem[$x]->modem_configdb;
			$modem_name = $rowsmodem[$x]->modem_name;
			$no_urut_modem = $x+1;
			$nowdate = date("Y-m-d");
			$running = 0;
			printf("===STARTING AUTOCHECK Now %s startdate %s \r\n", $nowtime, $nowdate);
			printf("===Prepare Check Last Info Modem SMS : %s (%d/%d)\n", $modem, $no_urut_modem, $totalmodem); 
			
			$this->db = $this->load->database("default",true); 
			$this->db->order_by("vehicle_id",$order);
			//$this->db->where("vehicle_user_id", $userid);
			$this->db->where("vehicle_modem", $modem);
			$this->db->where("vehicle_status <>", 3);
			$this->db->where("vehicle_dbname_live <>", "0");
			
			$this->db->from("vehicle");
			$q = $this->db->get();
			$rowvehicle = $q->result();
			$total_rows = count($rowvehicle);
			if(count($rowvehicle)>0){
			
				printf("===TOTAL VEHICLE : %s \r\n", $total_rows);
				$feature = array();
				$running = 1;
				$vehicle_gotohistory = 0;
				for($i=0;$i<$total_rows;$i++)
				{
					$no_urut = $i+1;
					printf("===PROSES DB LIVE: %s (%s of %s) \r\n", $rowvehicle[$i]->vehicle_no, $no_urut, $total_rows);
					$devices = explode("@", $rowvehicle[$i]->vehicle_device);
					$vehicle_dblive = $rowvehicle[$i]->vehicle_dbname_live;
					$vehicle_imei = $devices[0];
					$vehicle_no = $rowvehicle[$i]->vehicle_no;
					$vehicledevice = $rowvehicle[$i]->vehicle_device;
					$vehicleuser = $rowvehicle[$i]->vehicle_user_id;
					$vehicleidfix = $rowvehicle[$i]->vehicle_id;
					$vehiclecompany = $rowvehicle[$i]->vehicle_company;
				
					$gps = $this->getlastposition_fromDBLive($vehicle_imei,$vehicle_dblive);
					
						if(count($gps)>0){
							
							$lastposition = $this->getPosition_other($gps->gps_longitude_real, $gps->gps_latitude_real);
							$lastposition_time = date("Y-m-d H:i:s", strtotime($gps->gps_time . "+7hours"));
							$gps_realtime = $lastposition_time;
							$speed = number_format($gps->gps_speed*1.852, 0, "", ".");
							printf("===Raw GPS Time: %s  \r\n", $gps->gps_time);
							printf("===Now Time: %s  \r\n", $nowtime);
							printf("===GPS Time: %s  \r\n", $lastposition_time);
							printf("===Vehicle No %s \r\n", $vehicle_no);
							printf("===Speed %s \r\n", $speed);
							
							$lastlat = $gps->gps_latitude_real;
							$lastlong = $gps->gps_longitude_real;
							$course = $gps->gps_course;
							$coordinate = $lastlat.",".$lastlong;
							if($gps->gps_status == "A"){
								$gpsvalidstatus = "OK";
							}else{
								$gpsvalidstatus = "NOT OK";
							}
							
							$datajson = json_decode($gps->vehicle_autocheck);
							if($speed > 0)
							{
								$engine = "ON";
								printf("===HARCODE ENGINE %s \r\n", $engine);
							}
							else
							{
								$engine = $datajson->auto_last_engine;
								
							}
							
							printf("===Engine %s \r\n", $engine);
							
							$lastposition_time_sec = strtotime($lastposition_time);
							$coordinate = $gps->gps_latitude_real.",".$gps->gps_longitude_real;
							$url = "https://www.google.com/maps/search/?api=1&query=".$coordinate;
								
							$location = "-";
							
							//condition here
							if(isset($lastposition)){
								$ex_lastposition = explode(",",$lastposition->display_name);
								$street_name = $ex_lastposition[0];
							
								$location = $street_name;
								//printf("===Location %s \r\n", $street_name);
								$overspeed_status = 0;
									
							}
							
							
							//delta time gps VS gps now WITA
							$gps_realtime_sec = strtotime($gps_realtime);
							$nowtime_sec = strtotime($nowtime);
							$delta = $nowtime_sec - $gps_realtime_sec;
							$duration = get_time_difference($gps_realtime, $nowtime);
								
							$show = "";
							if($duration[0]!=0)
							{
								$show .= $duration[0] ." Day ";
							}
							if($duration[1]!=0)
							{
								$show .= $duration[1] ." Hour ";
							}
							if($duration[2]!=0)
							{
							   $show .= $duration[2] ." Min ";
							}
							if($show == "")
							{
								$show .= "0 Min";
							}
							
							printf("===Delta %s %s \r\n", $delta, $show);
							//cek delay kurang dari 1 jam
							if ($delta >= 600 && $delta <= 86400) //default 1jam(3600) -> 24jam(86400)
							{
								printf("===GPS DELAY \r\n");
								$statuscode = "K";
								$info_k = $rowvehicle[$i]->vehicle_no;
								array_push($data_k,$info_k);
								
										//update master vehicle autocheck
										$this->db->where("auto_user_id", $rowvehicle[$i]->vehicle_user_id);	
										$this->db->where("auto_vehicle_device", $rowvehicle[$i]->vehicle_device);
										$this->db->limit(1);
										$qcheck = $this->db->get("vehicle_autocheck");
										$rowcheck = $qcheck->row(); 			
										if ($qcheck->num_rows() == 0)
										{
											//insert
											unset($datacheck);
											$datacheck["auto_user_id"] = $rowvehicle[$i]->vehicle_user_id;
											$datacheck["auto_vehicle_id"] = $rowvehicle[$i]->vehicle_id;
											$datacheck["auto_vehicle_name"] = $rowvehicle[$i]->vehicle_name;
											$datacheck["auto_vehicle_no"] = $rowvehicle[$i]->vehicle_no;
											$datacheck["auto_vehicle_device"] = $rowvehicle[$i]->vehicle_device;
											$datacheck["auto_vehicle_type"] = $rowvehicle[$i]->vehicle_type;
											$datacheck["auto_vehicle_company"] = $rowvehicle[$i]->vehicle_company;
											$datacheck["auto_vehicle_subcompany"] = $rowvehicle[$i]->vehicle_subcompany;
											$datacheck["auto_vehicle_group"] = $rowvehicle[$i]->vehicle_group;
											$datacheck["auto_vehicle_subgroup"] = $rowvehicle[$i]->vehicle_subgroup;
											$datacheck["auto_vehicle_active_date2"] = $rowvehicle[$i]->vehicle_active_date2;
											$datacheck["auto_simcard"] = $rowvehicle[$i]->vehicle_card_no;
											$datacheck["auto_status"] = $statuscode;
											$datacheck["auto_last_update"] = $gps_realtime;
											$datacheck["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$datacheck["auto_last_position"] = $lastposition->display_name;
											$datacheck["auto_last_lat"] = $lastlat;
											$datacheck["auto_last_long"] = $lastlong;
											$datacheck["auto_last_engine"] = $engine;
											$datacheck["auto_last_speed"] = $speed;
											$datacheck["auto_last_gpsstatus"] = $gpsvalidstatus;
											$datacheck["auto_last_course"] = $course;
											/* $datacheck["auto_last_road"] = $jalur;
											$datacheck["auto_last_hauling"] = $hauling;
											$datacheck["auto_last_rom_name"] = $auto_last_rom_name;
											$datacheck["auto_last_rom_time"] = $auto_last_rom_time;
											$datacheck["auto_last_port_name"] = $auto_last_port_name;
											$datacheck["auto_last_port_time"] = $auto_last_port_time; */
											$datacheck["auto_flag"] = 0;
											
											//jika insert langsung di isi
											$datacheck["auto_change_engine_status"] = $engine;
											$datacheck["auto_change_engine_datetime"] = $gps_realtime;
											$datacheck["auto_change_position"] = $street_name;
											$datacheck["auto_change_coordinate"] = $lastlat.",".$lastlong;
											
											$this->db->insert("vehicle_autocheck",$datacheck);
											printf("===INSERT AUTOCHECK=== \r\n");	

											//json										
											$feature["auto_status"] = $statuscode;
											$feature["auto_last_update"] = $gps_realtime;
											$feature["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$feature["auto_last_position"] = $lastposition->display_name;
											$feature["auto_last_lat"] = $lastlat;
											$feature["auto_last_long"] = $lastlong;
											$feature["auto_last_engine"] = $engine;
											$feature["auto_last_speed"] = $speed;
											$feature["auto_last_gpsstatus"] = $gpsvalidstatus;
											$feature["auto_last_course"] = $course;
											/* $feature["auto_last_road"] = $jalur;
											$feature["auto_last_hauling"] = $hauling;
											
											$feature["auto_last_rom_name"] = $auto_last_rom_name;
											$feature["auto_last_rom_time"] = $auto_last_rom_time;
											$feature["auto_last_port_name"] = $auto_last_port_name;
											$feature["auto_last_port_time"] = $auto_last_port_time; */
											
											$feature["auto_flag"] = 0;
											$feature["vehicle_gotohistory"] = 0;
											$vehicle_gotohistory = 0;	
															
										}
										else
										{
											//update
											unset($datacheck);
											$datacheck["auto_user_id"] = $rowvehicle[$i]->vehicle_user_id;
											$datacheck["auto_vehicle_id"] = $rowvehicle[$i]->vehicle_id;
											$datacheck["auto_vehicle_name"] = $rowvehicle[$i]->vehicle_name;
											$datacheck["auto_vehicle_no"] = $rowvehicle[$i]->vehicle_no;
											$datacheck["auto_vehicle_device"] = $rowvehicle[$i]->vehicle_device;
											$datacheck["auto_vehicle_type"] = $rowvehicle[$i]->vehicle_type;
											$datacheck["auto_vehicle_company"] = $rowvehicle[$i]->vehicle_company;
											$datacheck["auto_vehicle_subcompany"] = $rowvehicle[$i]->vehicle_subcompany;
											$datacheck["auto_vehicle_group"] = $rowvehicle[$i]->vehicle_group;
											$datacheck["auto_vehicle_subgroup"] = $rowvehicle[$i]->vehicle_subgroup;
											$datacheck["auto_vehicle_active_date2"] = $rowvehicle[$i]->vehicle_active_date2;
											$datacheck["auto_simcard"] = $rowvehicle[$i]->vehicle_card_no;
											$datacheck["auto_status"] = $statuscode;
											$datacheck["auto_last_update"] = $gps_realtime;
											$datacheck["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$datacheck["auto_last_position"] = $lastposition->display_name;
											$datacheck["auto_last_lat"] = $lastlat;
											$datacheck["auto_last_long"] = $lastlong;
											$datacheck["auto_last_engine"] = $engine;
											$datacheck["auto_last_gpsstatus"] = $gpsvalidstatus;
											$datacheck["auto_last_speed"] = $speed;
											$datacheck["auto_last_course"] = $course;
											/* $datacheck["auto_last_road"] = $jalur;
											$datacheck["auto_last_hauling"] = $hauling;
											$datacheck["auto_last_rom_name"] = $auto_last_rom_name;
											$datacheck["auto_last_rom_time"] = $auto_last_rom_time;
											$datacheck["auto_last_port_name"] = $auto_last_port_name;
											$datacheck["auto_last_port_time"] = $auto_last_port_time; */
											$datacheck["auto_flag"] = 0;
											
											//json
											$feature["auto_status"] = $statuscode;
											$feature["auto_last_update"] = $gps_realtime;
											$feature["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$feature["auto_last_position"] = $lastposition->display_name;
											$feature["auto_last_lat"] = $lastlat;
											$feature["auto_last_long"] = $lastlong;
											$feature["auto_last_engine"] = $engine;
											$feature["auto_last_gpsstatus"] = $gpsvalidstatus;
											$feature["auto_last_speed"] = $speed;
											$feature["auto_last_course"] = $course;
											/* $feature["auto_last_road"] = $jalur;
											$feature["auto_last_hauling"] = $hauling;
											
											$feature["auto_last_rom_name"] = $auto_last_rom_name;
											$feature["auto_last_rom_time"] = $auto_last_rom_time;
											$feature["auto_last_port_name"] = $auto_last_port_name;
											$feature["auto_last_port_time"] = $auto_last_port_time; */
											
											$feature["auto_flag"] = 0;
											$feature["vehicle_gotohistory"] = 0;
											$vehicle_gotohistory = 0;	
											
											$this->db->where("auto_user_id", $rowvehicle[$i]->vehicle_user_id);	
											$this->db->where("auto_vehicle_device", $rowvehicle[$i]->vehicle_device);	
											$this->db->update("vehicle_autocheck",$datacheck);
											printf("===UPDATE AUTOCHECK=== \r\n");	
											
											
											
												
										}
								
							}
							else if($delta >= 86400) //lebih dari 1 hari //red condition 
							{
								printf("===GPS OFFLINE \r\n");
								$statuscode = "M";
								$info_m = $rowvehicle[$i]->vehicle_no;
								array_push($data_m,$info_m);
								printf("======================RED CONDITION======================== \r\n");
								
										unset($datavehicle);
										$datavehicle["vehicle_isred"] = 1;
										$this->db->where("vehicle_device", $rowvehicle[$i]->vehicle_device);
										$this->db->update("vehicle", $datavehicle);
										printf("===UPDATED STATUS IS RED YES=== %s \r\n", $rowvehicle[$i]->vehicle_no);
										
										//update master vehicle autocheck
										$this->db->where("auto_user_id", $rowvehicle[$i]->vehicle_user_id);	
										$this->db->where("auto_vehicle_device", $rowvehicle[$i]->vehicle_device);
										$this->db->limit(1);
										$qcheck = $this->db->get("vehicle_autocheck");
										$rowcheck = $qcheck->row(); 			
										if ($qcheck->num_rows() == 0)
										{
											//insert
											unset($datacheck);
											$datacheck["auto_user_id"] = $rowvehicle[$i]->vehicle_user_id;
											$datacheck["auto_vehicle_id"] = $rowvehicle[$i]->vehicle_id;
											$datacheck["auto_vehicle_name"] = $rowvehicle[$i]->vehicle_name;
											$datacheck["auto_vehicle_no"] = $rowvehicle[$i]->vehicle_no;
											$datacheck["auto_vehicle_device"] = $rowvehicle[$i]->vehicle_device;
											$datacheck["auto_vehicle_type"] = $rowvehicle[$i]->vehicle_type;
											$datacheck["auto_vehicle_company"] = $rowvehicle[$i]->vehicle_company;
											$datacheck["auto_vehicle_subcompany"] = $rowvehicle[$i]->vehicle_subcompany;
											$datacheck["auto_vehicle_group"] = $rowvehicle[$i]->vehicle_group;
											$datacheck["auto_vehicle_subgroup"] = $rowvehicle[$i]->vehicle_subgroup;
											$datacheck["auto_vehicle_active_date2"] = $rowvehicle[$i]->vehicle_active_date2;
											$datacheck["auto_simcard"] = $rowvehicle[$i]->vehicle_card_no;
											$datacheck["auto_status"] = $statuscode;
											$datacheck["auto_last_update"] = $gps_realtime;
											$datacheck["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$datacheck["auto_last_position"] = $lastposition->display_name;
											$datacheck["auto_last_lat"] = $lastlat;
											$datacheck["auto_last_long"] = $lastlong;
											$datacheck["auto_last_engine"] = $engine;
											$datacheck["auto_last_speed"] = $speed;
											$datacheck["auto_last_gpsstatus"] = $gpsvalidstatus;
											$datacheck["auto_last_course"] = $course;
											/* $datacheck["auto_last_road"] = $jalur;
											$datacheck["auto_last_hauling"] = $hauling;
											$datacheck["auto_last_rom_name"] = $auto_last_rom_name;
											$datacheck["auto_last_rom_time"] = $auto_last_rom_time;
											$datacheck["auto_last_port_name"] = $auto_last_port_name;
											$datacheck["auto_last_port_time"] = $auto_last_port_time; */
											$datacheck["auto_flag"] = 0;
											
											//jika insert langsung di isi
											$datacheck["auto_change_engine_status"] = $engine;
											$datacheck["auto_change_engine_datetime"] = $gps_realtime;
											$datacheck["auto_change_position"] = $street_name;
											$datacheck["auto_change_coordinate"] = $lastlat.",".$lastlong;
											
											$this->db->insert("vehicle_autocheck",$datacheck);
											printf("===INSERT AUTOCHECK=== \r\n");	

											//json										
											$feature["auto_status"] = $statuscode;
											$feature["auto_last_update"] = $gps_realtime;
											$feature["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$feature["auto_last_position"] = $lastposition->display_name;
											$feature["auto_last_lat"] = $lastlat;
											$feature["auto_last_long"] = $lastlong;
											$feature["auto_last_engine"] = $engine;
											$feature["auto_last_speed"] = $speed;
											$feature["auto_last_gpsstatus"] = $gpsvalidstatus;
											$feature["auto_last_course"] = $course;
											/* $feature["auto_last_road"] = $jalur;
											$feature["auto_last_hauling"] = $hauling;
											
											$feature["auto_last_rom_name"] = $auto_last_rom_name;
											$feature["auto_last_rom_time"] = $auto_last_rom_time;
											$feature["auto_last_port_name"] = $auto_last_port_name;
											$feature["auto_last_port_time"] = $auto_last_port_time; */
											
											$feature["auto_flag"] = 1;
											$feature["vehicle_gotohistory"] = 1;
											$vehicle_gotohistory = 1;	
															
										}
										else
										{
											//update
											unset($datacheck);
											$datacheck["auto_user_id"] = $rowvehicle[$i]->vehicle_user_id;
											$datacheck["auto_vehicle_id"] = $rowvehicle[$i]->vehicle_id;
											$datacheck["auto_vehicle_name"] = $rowvehicle[$i]->vehicle_name;
											$datacheck["auto_vehicle_no"] = $rowvehicle[$i]->vehicle_no;
											$datacheck["auto_vehicle_device"] = $rowvehicle[$i]->vehicle_device;
											$datacheck["auto_vehicle_type"] = $rowvehicle[$i]->vehicle_type;
											$datacheck["auto_vehicle_company"] = $rowvehicle[$i]->vehicle_company;
											$datacheck["auto_vehicle_subcompany"] = $rowvehicle[$i]->vehicle_subcompany;
											$datacheck["auto_vehicle_group"] = $rowvehicle[$i]->vehicle_group;
											$datacheck["auto_vehicle_subgroup"] = $rowvehicle[$i]->vehicle_subgroup;
											$datacheck["auto_vehicle_active_date2"] = $rowvehicle[$i]->vehicle_active_date2;
											$datacheck["auto_simcard"] = $rowvehicle[$i]->vehicle_card_no;
											$datacheck["auto_status"] = $statuscode;
											$datacheck["auto_last_update"] = $gps_realtime;
											$datacheck["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$datacheck["auto_last_position"] = $lastposition->display_name;
											$datacheck["auto_last_lat"] = $lastlat;
											$datacheck["auto_last_long"] = $lastlong;
											$datacheck["auto_last_engine"] = $engine;
											$datacheck["auto_last_gpsstatus"] = $gpsvalidstatus;
											$datacheck["auto_last_speed"] = $speed;
											$datacheck["auto_last_course"] = $course;
											/* $datacheck["auto_last_road"] = $jalur;
											$datacheck["auto_last_hauling"] = $hauling;
											$datacheck["auto_last_rom_name"] = $auto_last_rom_name;
											$datacheck["auto_last_rom_time"] = $auto_last_rom_time;
											$datacheck["auto_last_port_name"] = $auto_last_port_name;
											$datacheck["auto_last_port_time"] = $auto_last_port_time; */
											$datacheck["auto_flag"] = 0;
											
											//json
											$feature["auto_status"] = $statuscode;
											$feature["auto_last_update"] = $gps_realtime;
											$feature["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$feature["auto_last_position"] = $lastposition->display_name;
											$feature["auto_last_lat"] = $lastlat;
											$feature["auto_last_long"] = $lastlong;
											$feature["auto_last_engine"] = $engine;
											$feature["auto_last_gpsstatus"] = $gpsvalidstatus;
											$feature["auto_last_speed"] = $speed;
											$feature["auto_last_course"] = $course;
											/* $feature["auto_last_road"] = $jalur;
											$feature["auto_last_hauling"] = $hauling;
											
											$feature["auto_last_rom_name"] = $auto_last_rom_name;
											$feature["auto_last_rom_time"] = $auto_last_rom_time;
											$feature["auto_last_port_name"] = $auto_last_port_name;
											$feature["auto_last_port_time"] = $auto_last_port_time; */
											
											$feature["auto_flag"] = 1;
											$feature["vehicle_gotohistory"] = 1;
											$vehicle_gotohistory = 1;	
											
											$this->db->where("auto_user_id", $rowvehicle[$i]->vehicle_user_id);	
											$this->db->where("auto_vehicle_device", $rowvehicle[$i]->vehicle_device);	
											$this->db->update("vehicle_autocheck",$datacheck);
											printf("===UPDATE AUTOCHECK=== \r\n");	
											
											
											
												
										}
										
											
													
							}
							else //gps update condition
							{
								printf("===GPS UPDATE \r\n");
								$statuscode = "P";
								$info_p = $rowvehicle[$i]->vehicle_no;
								array_push($data_p,$info_p);
										
										
										
										if($gps->gps_status == "V")
										{
											printf("===Vehicle No %s NOT OK \r\n", $rowvehicle[$i]->vehicle_no);
											
											
										}
										else
										{
											printf("=================GPS UPDATE================ \r\n");
											unset($datavehicle);
											$datavehicle["vehicle_isred"] = 0;
											$this->db->where("vehicle_device", $rowvehicle[$i]->vehicle_device);
											$this->db->update("vehicle", $datavehicle);
											printf("===UPDATED STATUS DEFAULT=== %s \r\n", $rowvehicle[$i]->vehicle_no);
											
										}
										
										//update master vehicle autocheck
										$this->db->where("auto_user_id", $rowvehicle[$i]->vehicle_user_id);	
										$this->db->where("auto_vehicle_device", $rowvehicle[$i]->vehicle_device);
										$this->db->limit(1);
										$qcheck = $this->db->get("vehicle_autocheck");
										$rowcheck = $qcheck->row(); 			
										if ($qcheck->num_rows() == 0)
										{
											//insert
											unset($datacheck);
											$datacheck["auto_user_id"] = $rowvehicle[$i]->vehicle_user_id;
											$datacheck["auto_vehicle_id"] = $rowvehicle[$i]->vehicle_id;
											$datacheck["auto_vehicle_name"] = $rowvehicle[$i]->vehicle_name;
											$datacheck["auto_vehicle_no"] = $rowvehicle[$i]->vehicle_no;
											$datacheck["auto_vehicle_device"] = $rowvehicle[$i]->vehicle_device;
											$datacheck["auto_vehicle_type"] = $rowvehicle[$i]->vehicle_type;
											$datacheck["auto_vehicle_company"] = $rowvehicle[$i]->vehicle_company;
											$datacheck["auto_vehicle_subcompany"] = $rowvehicle[$i]->vehicle_subcompany;
											$datacheck["auto_vehicle_group"] = $rowvehicle[$i]->vehicle_group;
											$datacheck["auto_vehicle_subgroup"] = $rowvehicle[$i]->vehicle_subgroup;
											$datacheck["auto_vehicle_active_date2"] = $rowvehicle[$i]->vehicle_active_date2;
											$datacheck["auto_simcard"] = $rowvehicle[$i]->vehicle_card_no;
											$datacheck["auto_status"] = $statuscode;
											$datacheck["auto_last_update"] = $gps_realtime;
											$datacheck["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$datacheck["auto_last_position"] = $lastposition->display_name;
											$datacheck["auto_last_lat"] = $lastlat;
											$datacheck["auto_last_long"] = $lastlong;
											$datacheck["auto_last_engine"] = $engine;
											$datacheck["auto_last_speed"] = $speed;
											$datacheck["auto_last_gpsstatus"] = $gpsvalidstatus;
											$datacheck["auto_last_course"] = $course;
											/* $datacheck["auto_last_road"] = $jalur;
											$datacheck["auto_last_hauling"] = $hauling;
											$datacheck["auto_last_rom_name"] = $auto_last_rom_name;
											$datacheck["auto_last_rom_time"] = $auto_last_rom_time;
											$datacheck["auto_last_port_name"] = $auto_last_port_name;
											$datacheck["auto_last_port_time"] = $auto_last_port_time; */
											$datacheck["auto_flag"] = 0;
											
											//jika insert langsung di isi
											$datacheck["auto_change_engine_status"] = $engine;
											$datacheck["auto_change_engine_datetime"] = $gps_realtime;
											$datacheck["auto_change_position"] = $street_name;
											$datacheck["auto_change_coordinate"] = $lastlat.",".$lastlong;
											
											$this->db->insert("vehicle_autocheck",$datacheck);
											printf("===INSERT AUTOCHECK=== \r\n");	

											//json										
											$feature["auto_status"] = $statuscode;
											$feature["auto_last_update"] = $gps_realtime;
											$feature["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$feature["auto_last_position"] = $lastposition->display_name;
											$feature["auto_last_lat"] = $lastlat;
											$feature["auto_last_long"] = $lastlong;
											$feature["auto_last_engine"] = $engine;
											$feature["auto_last_speed"] = $speed;
											$feature["auto_last_gpsstatus"] = $gpsvalidstatus;
											$feature["auto_last_course"] = $course;
											/* $feature["auto_last_road"] = $jalur;
											$feature["auto_last_hauling"] = $hauling;
											
											$feature["auto_last_rom_name"] = $auto_last_rom_name;
											$feature["auto_last_rom_time"] = $auto_last_rom_time;
											$feature["auto_last_port_name"] = $auto_last_port_name;
											$feature["auto_last_port_time"] = $auto_last_port_time; */
											
											$feature["auto_flag"] = 0;
											$feature["vehicle_gotohistory"] = 0;
											$vehicle_gotohistory = 0;	
															
										}
										else
										{
											//update
											unset($datacheck);
											$datacheck["auto_user_id"] = $rowvehicle[$i]->vehicle_user_id;
											$datacheck["auto_vehicle_id"] = $rowvehicle[$i]->vehicle_id;
											$datacheck["auto_vehicle_name"] = $rowvehicle[$i]->vehicle_name;
											$datacheck["auto_vehicle_no"] = $rowvehicle[$i]->vehicle_no;
											$datacheck["auto_vehicle_device"] = $rowvehicle[$i]->vehicle_device;
											$datacheck["auto_vehicle_type"] = $rowvehicle[$i]->vehicle_type;
											$datacheck["auto_vehicle_company"] = $rowvehicle[$i]->vehicle_company;
											$datacheck["auto_vehicle_subcompany"] = $rowvehicle[$i]->vehicle_subcompany;
											$datacheck["auto_vehicle_group"] = $rowvehicle[$i]->vehicle_group;
											$datacheck["auto_vehicle_subgroup"] = $rowvehicle[$i]->vehicle_subgroup;
											$datacheck["auto_vehicle_active_date2"] = $rowvehicle[$i]->vehicle_active_date2;
											$datacheck["auto_simcard"] = $rowvehicle[$i]->vehicle_card_no;
											$datacheck["auto_status"] = $statuscode;
											$datacheck["auto_last_update"] = $gps_realtime;
											$datacheck["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$datacheck["auto_last_position"] = $lastposition->display_name;
											$datacheck["auto_last_lat"] = $lastlat;
											$datacheck["auto_last_long"] = $lastlong;
											$datacheck["auto_last_engine"] = $engine;
											$datacheck["auto_last_gpsstatus"] = $gpsvalidstatus;
											$datacheck["auto_last_speed"] = $speed;
											$datacheck["auto_last_course"] = $course;
											/* $datacheck["auto_last_road"] = $jalur;
											$datacheck["auto_last_hauling"] = $hauling;
											$datacheck["auto_last_rom_name"] = $auto_last_rom_name;
											$datacheck["auto_last_rom_time"] = $auto_last_rom_time;
											$datacheck["auto_last_port_name"] = $auto_last_port_name;
											$datacheck["auto_last_port_time"] = $auto_last_port_time; */
											$datacheck["auto_flag"] = 0;
											
											//json
											$feature["auto_status"] = $statuscode;
											$feature["auto_last_update"] = $gps_realtime;
											$feature["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
											$feature["auto_last_position"] = $lastposition->display_name;
											$feature["auto_last_lat"] = $lastlat;
											$feature["auto_last_long"] = $lastlong;
											$feature["auto_last_engine"] = $engine;
											$feature["auto_last_gpsstatus"] = $gpsvalidstatus;
											$feature["auto_last_speed"] = $speed;
											$feature["auto_last_course"] = $course;
										/* 	$feature["auto_last_road"] = $jalur;
											$feature["auto_last_hauling"] = $hauling;
											
											$feature["auto_last_rom_name"] = $auto_last_rom_name;
											$feature["auto_last_rom_time"] = $auto_last_rom_time;
											$feature["auto_last_port_name"] = $auto_last_port_name;
											$feature["auto_last_port_time"] = $auto_last_port_time; */
											
											$feature["auto_flag"] = 0;
											$feature["vehicle_gotohistory"] = 0;
											$vehicle_gotohistory = 0;	
											
											//cek engine jika tidak sama dengan sebelumnya maka di update
											if($rowcheck->auto_change_engine_status != $engine){
												printf("===!!CHANGE ENGINE DETECTED=== \r\n");	
												$datacheck["auto_change_engine_status"] = $engine;
												$datacheck["auto_change_engine_datetime"] = $gps_realtime;
												$datacheck["auto_change_position"] = $lastposition->display_name;
												$datacheck["auto_change_coordinate"] = $lastlat.",".$lastlong;
												
												//json
												$feature["auto_change_engine_status"] = $engine;
												$feature["auto_change_engine_datetime"] = $gps_realtime;
												$feature["auto_change_position"] = $lastposition->display_name;
												$feature["auto_change_coordinate"] = $lastlat.",".$lastlong;
											}
											
											
											$this->db->where("auto_user_id", $rowvehicle[$i]->vehicle_user_id);	
											$this->db->where("auto_vehicle_device", $rowvehicle[$i]->vehicle_device);	
											$this->db->update("vehicle_autocheck",$datacheck);
											printf("===UPDATE AUTOCHECK=== \r\n");	
											
											
											
												
										}
											
									
										
								
										
										
										
								
								
								
							}
								
								
							
						}
						else
						{
							
									printf("X==NO DATA IN DB LIVE !!\r\n");
								
									unset($datavehicle);
									$datavehicle["vehicle_isred"] = 1;
									$this->db->where("vehicle_device", $rowvehicle[$i]->vehicle_device);
									$this->db->update("vehicle", $datavehicle);
									printf("===UPDATED STATUS IS RED (NO DATA) YES=== %s \r\n", $rowvehicle[$i]->vehicle_no);
										
									//update master vehicle (khusus vehicle GO TO History)
									$this->db->where("auto_user_id", $rowvehicle[$i]->vehicle_user_id);	
									$this->db->where("auto_vehicle_device", $rowvehicle[$i]->vehicle_device);
									$this->db->limit(1);
									$qcheck = $this->db->get("vehicle_autocheck");
									//$rowcheck = $qcheck->row(); 			
									if ($qcheck->num_rows() == 0)
									{
										//insert
										unset($datacheck);
										$datacheck["auto_user_id"] = $rowvehicle[$i]->vehicle_user_id;
										$datacheck["auto_vehicle_id"] = $rowvehicle[$i]->vehicle_id;
										$datacheck["auto_vehicle_name"] = $rowvehicle[$i]->vehicle_name;
										$datacheck["auto_vehicle_no"] = $rowvehicle[$i]->vehicle_no;
										$datacheck["auto_vehicle_device"] = $rowvehicle[$i]->vehicle_device;
										$datacheck["auto_vehicle_type"] = $rowvehicle[$i]->vehicle_type;
										$datacheck["auto_vehicle_company"] = $rowvehicle[$i]->vehicle_company;
										$datacheck["auto_vehicle_subcompany"] = $rowvehicle[$i]->vehicle_subcompany;
										$datacheck["auto_vehicle_group"] = $rowvehicle[$i]->vehicle_group;
										$datacheck["auto_vehicle_subgroup"] = $rowvehicle[$i]->vehicle_subgroup;
										$datacheck["auto_vehicle_active_date2"] = $rowvehicle[$i]->vehicle_active_date2;
										$datacheck["auto_simcard"] = $rowvehicle[$i]->vehicle_card_no;
										$datacheck["auto_status"] = "M";
										$datacheck["auto_last_update"] = "";
										$datacheck["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
										$datacheck["auto_last_position"] = "Go to history";
										$datacheck["auto_last_lat"] = "";
										$datacheck["auto_last_long"] = "";
										$datacheck["auto_last_engine"] = "NO DATA";
										$datacheck["auto_last_gpsstatus"] = "";
										$datacheck["auto_last_speed"] = 0;
										$datacheck["auto_last_course"] = 0;
										/* $datacheck["auto_last_hauling"] = "";
										
										$datacheck["auto_last_rom_name"] = "";
										$datacheck["auto_last_rom_time"] = "";
										$datacheck["auto_last_port_name"] = "";
										$datacheck["auto_last_port_time"] = "";
										
										$datacheck["auto_last_road"] = ""; */
										$datacheck["auto_flag"] = 0;
										
														
										$this->db->insert("vehicle_autocheck",$datacheck);
										printf("===INSERT AUTOCHECK=== \r\n");
										
										//json
										$feature["auto_status"] = "M";
										$feature["auto_last_update"] = "";
										$feature["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
										$feature["auto_last_position"] = "Go to history";
										$feature["auto_last_lat"] = "";
										$feature["auto_last_long"] = "";
										$feature["auto_last_engine"] = "NO DATA";
										$feature["auto_last_gpsstatus"] = "";
										$feature["auto_last_speed"] = 0;
										$feature["auto_last_course"] = 0;
										/* $feature["auto_last_road"] = "";
										$feature["auto_last_hauling"] = "";
										$feature["auto_last_rom_name"] = "";
										$feature["auto_last_rom_time"] = "";
										$feature["auto_last_port_name"] = "";
										$feature["auto_last_port_time"] = ""; */
										
										$feature["auto_flag"] = 0;
										$feature["vehicle_gotohistory"] = 1;
										$vehicle_gotohistory = 1;	
														
									}
									else
									{
										//update
										unset($datacheck);
										$datacheck["auto_user_id"] = $rowvehicle[$i]->vehicle_user_id;
										$datacheck["auto_vehicle_id"] = $rowvehicle[$i]->vehicle_id;
										$datacheck["auto_vehicle_name"] = $rowvehicle[$i]->vehicle_name;
										$datacheck["auto_vehicle_no"] = $rowvehicle[$i]->vehicle_no;
										$datacheck["auto_vehicle_device"] = $rowvehicle[$i]->vehicle_device;
										$datacheck["auto_vehicle_type"] = $rowvehicle[$i]->vehicle_type;
										$datacheck["auto_vehicle_company"] = $rowvehicle[$i]->vehicle_company;
										$datacheck["auto_vehicle_subcompany"] = $rowvehicle[$i]->vehicle_subcompany;
										$datacheck["auto_vehicle_group"] = $rowvehicle[$i]->vehicle_group;
										$datacheck["auto_vehicle_subgroup"] = $rowvehicle[$i]->vehicle_subgroup;
										$datacheck["auto_vehicle_active_date2"] = $rowvehicle[$i]->vehicle_active_date2;
										$datacheck["auto_simcard"] = $rowvehicle[$i]->vehicle_card_no;
										$datacheck["auto_status"] = "M";
										$datacheck["auto_last_update"] ="";
										$datacheck["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
										$datacheck["auto_last_position"] = "Go to history";
										$datacheck["auto_last_lat"] = "";
										$datacheck["auto_last_long"] = "";
										$datacheck["auto_last_engine"] = "NO DATA";
										$datacheck["auto_last_gpsstatus"] = "";
										$datacheck["auto_last_speed"] = 0;
										$datacheck["auto_last_course"] = 0;
										/* $datacheck["auto_last_road"] = "";
										$datacheck["auto_last_hauling"] = "";
										$datacheck["auto_last_rom_name"] = "";
										$datacheck["auto_last_rom_time"] = "";
										$datacheck["auto_last_port_name"] = "";
										$datacheck["auto_last_port_time"] = ""; */
										
										$datacheck["auto_flag"] = 0;
										
										$this->db->where("auto_user_id", $rowvehicle[$i]->vehicle_user_id);	
										$this->db->where("auto_vehicle_device", $rowvehicle[$i]->vehicle_device);	
										$this->db->update("vehicle_autocheck",$datacheck);
										printf("===UPDATE AUTOCHECK=== \r\n");	
										
										//for json
										$feature["auto_status"] = "M";
										$feature["auto_last_update"] ="";
										$feature["auto_last_check"] = date("Y-m-d H:i:s", strtotime($nowdate));
										$feature["auto_last_position"] = "Go to history";
										$feature["auto_last_lat"] = "";
										$feature["auto_last_long"] = "";
										$feature["auto_last_engine"] = "NO DATA";
										$feature["auto_last_gpsstatus"] = "";
										$feature["auto_last_speed"] = 0;
										$feature["auto_last_course"] = 0;
										/* $feature["auto_last_road"] = "";
										$feature["auto_last_hauling"] = "";
										$feature["auto_last_rom_name"] = "";
										$feature["auto_last_rom_time"] = "";
										$feature["auto_last_port_name"] = "";
										$feature["auto_last_port_time"] = ""; */
										$feature["auto_flag"] = 0;
										$feature["vehicle_gotohistory"] = 1;
										$vehicle_gotohistory = 1;	
											
									}
									
						}
						
						if($running == 1){
							unset($datajson);
							//update to master vehicle
							$content = json_encode($feature);
							$datajson["vehicle_autocheck"] = $content;
							$datajson["vehicle_gotohistory"] = $vehicle_gotohistory;
							
							$this->db->where("vehicle_id", $rowvehicle[$i]->vehicle_id);	
							$this->db->limit(1);	
							$this->db->update("vehicle",$datajson);
							printf("===UPDATE JSON MASTER VEHICLE=== \r\n");	
						}
					
					
					printf("===================== \r\n");
					//exit();
				}
				
			
			}
		
		
		}
		
		
		$finishtime = date("Y-m-d H:i:s");
		$start_1 = dbmaketime($nowtime);
		$end_1 = dbmaketime($finishtime);
		$duration_sec = $end_1 - $start_1;
		
		
		$total_p = count($data_p);
		$total_k = count($data_k);
		$total_m = count($data_m);
		
		//send telegram 
		$cron_name = "AUTOCHECK ".$groupname;
		$statusname = "FINISH";
		$message =  urlencode(
			"".$cron_name." \n".
			"Start: ".$nowtime." \n".
			"Finish: ".$finishtime." \n".
			"Total Unit: ".$total_rows." \n".
			"GPS Update: ".$total_p." \n".
			"GPS Delay: ".$total_k." \n".
			"GPS Offline: ".$total_m." \n".
			"SMS Modem: ".$modem_name." \n".
			"Status: ".$statusname." \n".
			"Latency: ".$duration_sec." s"." \n"
			);
											
		$sendtelegram = $this->telegram_direct("-756535755",$message); //telegram ABDI AUTOCHECK
		printf("===SENT TELEGRAM OK\r\n");
	
		printf("=====FINISH %s %s lat: %s s =========== \r\n", $nowtime, $finishtime, $duration_sec);
		$this->db->close();
		$this->db->cache_delete_all();
	}
	
	function getvehicle($vehicle_device)
	{

		$this->db = $this->load->database("default",true);
		$this->db->select("vehicle_id,vehicle_device,vehicle_type,vehicle_name,vehicle_no,vehicle_mv03,vehicle_user_id,
							vehicle_company,vehicle_dbname_live,vehicle_info");
		$this->db->order_by("vehicle_id", "desc");
		//$this->db->where("vehicle_status <>", 3);
		$this->db->where("vehicle_device", $vehicle_device);
		$q = $this->db->get("vehicle");
		$rows = $q->row();
		$total_rows = count($rows);

		if($total_rows > 0){
			$data_vehicle = $rows;
			return $data_vehicle;
		}else{
			return false;
		}

	}
	
	function get_time_difference($starttime, $endtime)
	{
		
		$start_1 = dbmaketime($starttime);
		$end_1 = dbmaketime($endtime);
		$duration_sec = $end_1 - $start_1;
									
		$show = "";
			if($duration[0]!=0)
			{
                $show .= $duration[0] ." Day ";
			}
			if($duration[1]!=0)
			{
				$show .= $duration[1] ." Hour ";
			}
			if($duration[2]!=0)
			{
                $show .= $duration[2] ." Min ";
			}
            if($show == "")
            {
                $show .= "0 Min";
            }
						
			return $show;
	}
	
	function getCompanyName($companyid)
	{
		
		$this->db = $this->load->database("default",true);
		$this->db->select("company_name");	
		$this->db->order_by("company_name", "desc");
		$this->db->where("company_id ", $companyid);
		$q = $this->db->get("company");
		$rows = $q->row();
		$total_rows = count($rows);
		
		if($total_rows > 0){
			$company_name = $rows->company_name;
		}else{
			$company_name = "";
		}
		
		return $company_name;
	
	}
	
	function getCompanyAll($userid)
	{
		
		$this->db = $this->load->database("default",true);
		$this->db->select("company_id,company_name");	
		$this->db->order_by("company_name", "asc");
		$this->db->where("company_created_by ", $userid);
		$this->db->where("company_flag ", 0);
		$q = $this->db->get("company");
		$rows = $q->result();
		
		return $rows;
	
	}
	
	function get_username($id) 
	{	
		$this->db->select("user_name");
		$this->db->where("user_id",$id);
		$q = $this->db->get("user");
		$data = $q->row();
		
        return $data;
		
    }
	
	function getPosition($longitude, $ew, $latitude, $ns)
	{
        $gps_longitude_real = getLongitude($longitude, $ew);
        $gps_latitude_real = getLatitude($latitude, $ns);
        
        $gps_longitude_real_fmt = number_format($gps_longitude_real, 4, ".", "");
        $gps_latitude_real_fmt = number_format($gps_latitude_real, 4, ".", "");    
		$georeverse = $this->gpsmodel->GeoReverse($gps_latitude_real_fmt, $gps_longitude_real_fmt);
        
        return $georeverse;
    }
	
	function getPosition_other($longitude, $latitude)
	{
		$georeverse = $this->gpsmodel->GeoReverse($latitude, $longitude);	
		return $georeverse;
	}
			
	function telegram($user,$message)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
		$url = "http://admintib.pilartech.co.id/telegram/telegram_directpost";
        
        $data = array("id" => $user, "message" => $message);
        $data_string = json_encode($data);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, true);                                                           
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);                        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));  
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
            die("Curl failed: " . curL_error($ch));
        }
        echo $result;
        echo curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
    }
	
	function getlastposition_fromDBLive($imei,$dblive)
	{
		
		$this->dblive = $this->load->database($dblive,true);
		$this->dblive->select("gps_name,gps_time,gps_latitude_real,gps_longitude_real,gps_speed,
							   gps_status,gps_course,vehicle_autocheck");	
		$this->dblive->order_by("gps_time", "desc");
		$this->dblive->where("gps_name", $imei);
		$this->dblive->limit(1);
		$qpost = $this->dblive->get("gps");
		$rowpost = $qpost->row();
		
		$this->dblive->close();
		$this->dblive->cache_delete_all();
		
		return $rowpost;
		
	}
	
	function telegram_direct($groupid,$message)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
		$url = "http://admin.abditrack.com/telegram/telegram_directpost";
        
        $data = array("id" => $groupid, "message" => $message);
        $data_string = json_encode($data);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, true);                                                           
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);   
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);	//new
		
		
		/* curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_URL, 'http://admintib.pilartech.co.id/' );
		curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
		curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2 ); */

		//var_dump(curl_exec($ch));
		//var_dump(curl_getinfo($ch));
		//var_dump(curl_error($ch));
		
		/* curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_URL, 'http://admintib.buddiyanto.my.id/' );
		curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
		curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2 ); */
		
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,true);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));  
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
            die("Curl failed: " . curL_error($ch));
        }
        echo $result;
        echo curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
    }
		
	function getGeofence_location_live($longitude, $latitude, $vehicle_dblive) 
	{
		
		$this->db = $this->load->database($vehicle_dblive, true);
		$lng = $longitude;
		$lat = $latitude;
		$geo_name = "''";
		$sql = sprintf("
					SELECT 	geofence_name,geofence_id,geofence_speed,geofence_speed_muatan,geofence_type
					FROM 	webtracking_geofence 
					WHERE 	TRUE
							AND (geofence_name <> %s)
							AND CONTAINS(geofence_polygon, GEOMFROMTEXT('POINT(%s %s)')) 
							AND (geofence_status = 1)
					ORDER BY geofence_id DESC LIMIT 1 OFFSET 0", $geo_name, $lng, $lat);
		$q = $this->db->query($sql);
		if ($q->num_rows() > 0)
		{			
			$row = $q->row();
            /*$total = $q->num_rows();
            for ($i=0;$i<$total;$i++){
				$data = $row[$i]->geofence_name;
				$data = $row;
				return $data;
            }*/
			$data = $row;
			return $data;
			 
		}
		else
        {
			$data = false;
            return $data;
        }

	}
	
	function get_telegramgroup_overspeed($company_id)
	{
		//get telegram group by company
		$this->db = $this->load->database("default",TRUE);
		$this->db->select("company_id,company_telegram_speed");
		$this->db->where("company_id",$company_id);
		$qcompany = $this->db->get("company");
		$rcompany = $qcompany->row();
		if(count($rcompany)>0){
			$telegram_group = $rcompany->company_telegram_speed;
		}else{
			$telegram_group = 0;
		}
				
		return $telegram_group;
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
