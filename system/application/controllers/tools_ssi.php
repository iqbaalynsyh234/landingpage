<?php
include "base.php";

class Tools_ssi extends Base {

	function Tools_ssi()
	{
		parent::Base();	
		$this->load->model("gpsmodel");
        $this->load->model("vehiclemodel");
        $this->load->model("configmodel");
        $this->load->helper('common_helper');
        $this->load->helper('kopindosat');
        $this->load->model("historymodel");
	}
    
	function playback($userid="", $name = "", $host="", $startdate = "", $enddate = "")
	{
	
		printf("PROSES AUTO REPORT PLAYBACK >> START \r\n");
        $startproses = date("Y-m-d H:i:s");
        
       /*  include 'class/PHPExcel.php';
        include 'class/PHPExcel/Writer/Excel2007.php'; */
        
        $report_type = "playback";
        $process_date = date("Y-m-d H:i:s");
        $domain_server = "http://202.129.190.194/";
        $z = 0;
		$report = "playback_";
        
        if ($startdate == "")
		{
            $startdate = date("Y-m-d 00:00:00", strtotime("yesterday"));
            $datefilenamestart = date("Ymd", strtotime("yesterday")); 
			$month = date("F", strtotime("yesterday"));
			$year = date("Y", strtotime("yesterday"));
        }
        
        if ($startdate != "")
        {
            $datefilenamestart = date("Ymd", strtotime($startdate));     
            $startdate = date("Y-m-d 00:00:00", strtotime($startdate));
			$month = date("F", strtotime($startdate));
			$year = date("Y", strtotime($startdate));
        }
        
        if ($enddate != "")
        {
            $enddate = date("Y-m-d 23:59:59", strtotime($enddate));
            $datefilenameend = date("Ymd", strtotime($enddate));
        }
        
        if ($enddate == "") {
            $enddate = date("Y-m-d 23:59:59", strtotime("yesterday"));
            $datefilenameend = date("Ymd", strtotime("yesterday"));
        }
        
        $datefilename = $datefilenamestart."_".$datefilenameend;
        
        switch ($month)
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
        
        $sdate = date("Y-m-d H:i:s", strtotime("-7 hour", strtotime($startdate)));
        $edate = date("Y-m-d H:i:s", strtotime("-7 hour", strtotime($enddate)));
        $this->db->order_by("vehicle_id", "asc");
        if ($name != "" && $host != "")
        {
            $dev = $name."@".$host;
            $this->db->where("vehicle_device", $dev);    
        } 
       
		if ($userid != "")
		{
			$this->db->where("user_id",$userid);
		}
		
        $this->db->join("user", "vehicle_user_id = user_id", "left outer");
		
        if ($userid == "")
		{
			$this->db->where("user_company >",0);
		}
		//ssi 1933
        $this->db->where("vehicle_user_id", 1933);
		$this->db->where("vehicle_status <>", 3);
        $q = $this->db->get("vehicle");
        $rowvehicle = $q->result();
        
        $total_process = count($rowvehicle);
        printf("TOTAL PROSES VEHICLE : %s \r\n",$total_process);
        printf("============================================ \r\n");
		//exit();
		$this->dbtrans = $this->load->database("transporter",true);
		$this->dbhist = $this->load->database("gpshistory",true);
        $this->dbhist2 = $this->load->database("gpshistory2",true);
        for($x=0;$x<count($rowvehicle);$x++) 
        {
            printf("PROSES VEHICLE : %s (%d/%d) \r\n",$rowvehicle[$x]->vehicle_device, ++$z, $total_process);
            
            if (isset($rowvehicle[$x]->vehicle_info))
            {
                $json = json_decode($rowvehicle[$x]->vehicle_info);
               
				unset($data_insert);
				//port only
				if (isset($json->vehicle_ip) && isset($json->vehicle_port))
                {
					$databases = $this->config->item('databases');
					if (isset($databases[$json->vehicle_ip][$json->vehicle_port]))
					{
						$database = $databases[$json->vehicle_ip][$json->vehicle_port];
						$table = $this->config->item("external_gpstable");
						$tableinfo = $this->config->item("external_gpsinfotable");				
						$this->dbhist = $this->load->database($database, TRUE);
						$this->dbhist2 = $this->load->database("gpshistory",true);		
					}
					else
					{
						$table = $this->gpsmodel->getGPSTable($rowvehicle[$x]->vehicle_type);
						$tableinfo = $this->gpsmodel->getGPSInfoTable($rowvehicle[$x]->vehicle_type);
						$this->dbhist = $this->load->database("default", TRUE);
						$this->dbhist2 = $this->load->database("gpshistory",true);		
					}
					
					$vehicle_device = explode("@", $rowvehicle[$x]->vehicle_device);  
                    $vehicle_no = $rowvehicle[$x]->vehicle_no;
					$vehicle_dev = $rowvehicle[$x]->vehicle_device;
					$vehicle_name = $rowvehicle[$x]->vehicle_name;
						
					if ($rowvehicle[$x]->vehicle_type == "T5" || $rowvehicle[$x]->vehicle_type == "T5 PULSE")
                    {
                        $tablehist = $vehicle_device[0]."@t5_gps";
                        $tablehistinfo = $vehicle_device[0]."@t5_info";    
                    }
                    else
                    {
						$tablehist = strtolower($vehicle_device[0])."@".strtolower($vehicle_device[1])."_gps";
						$tablehistinfo = strtolower($vehicle_device[0])."@".strtolower($vehicle_device[1])."_info";
                    }
					
                    //cek apakah sudah pernah ada filenya
                    $this->dbtrans->where("autoreport_vehicle_id",$rowvehicle[$x]->vehicle_id);
                    $this->dbtrans->where("autoreport_data_startdate",$startdate);
                    $this->dbtrans->limit(1);
                    $qrpt = $this->dbtrans->get("autoreport_playback_ssi");
                    $rrpt = $qrpt->row();
                    
                    if (count($rrpt)>0)
                    {
                        printf("VEHICLE %s SUDAH PERNAH DI PROSES, HAPUS DULU DATA SEBELUMNYA \r\n", $rowvehicle[$x]->vehicle_device);     
                    }
                    else
                    {
					
                       
                
                        $ex_vno = explode("/",$vehicle_no);
						
                        
            
						/* $this->dbhist->join($tableinfo, "gps_info_time = gps_time AND gps_info_device = CONCAT(gps_name,'@',gps_host)");    
                        $this->dbhist->where_in("gps_info_device", $rowvehicle[$x]->vehicle_device);
                        $this->dbhist->where("gps_time >=", $sdate);
                        $this->dbhist->where("gps_time <=", $edate);    
                        $this->dbhist->order_by("gps_time","asc");
                        $this->dbhist->from($table);
                        $q = $this->dbhist->get();
                        $rows1 = $q->result();
						
						$this->dbhist2->join($tableinfo, "gps_info_time = gps_time AND gps_info_device = CONCAT(gps_name,'@',gps_host)");    
                        $this->dbhist2->where_in("gps_info_device", $rowvehicle[$x]->vehicle_device);
                        $this->dbhist2->where("gps_time >=", $sdate);
                        $this->dbhist2->where("gps_time <=", $edate);    
                        $this->dbhist2->order_by("gps_time","asc");
                        $this->dbhist2->from($table);
                        $q2 = $this->dbhist2->get();
                        $rows2 = $q2->result(); */
						
						$this->dbhist->join($tableinfo, "gps_info_time = gps_time AND gps_info_device = CONCAT(gps_name,'@',gps_host)");    
                        $this->dbhist->where("gps_info_device", $rowvehicle[$x]->vehicle_device);
                        $this->dbhist->where("gps_time >=", $sdate);
                        $this->dbhist->where("gps_time <=", $edate);    
                        $this->dbhist->order_by("gps_time","asc");
                        $this->dbhist->from($table);
                        $q = $this->dbhist->get();
                        $rows1 = $q->result();
                        //print_r($rows);exit;
						
						$this->dbhist2->join($tablehistinfo, "gps_info_time = gps_time AND gps_info_device = CONCAT(gps_name,'@',gps_host)");    
                        $this->dbhist2->where("gps_info_device", $rowvehicle[$x]->vehicle_device);
                        $this->dbhist2->where("gps_time >=", $sdate);
                        $this->dbhist2->where("gps_time <=", $edate);    
                        $this->dbhist2->order_by("gps_time","asc");
                        $this->dbhist2->from($tablehist);
                        $q2 = $this->dbhist2->get();
                        $rows2 = $q2->result();
						
						//edited
						//$rows = $rows1;
						$rows = array_merge($rows1, $rows2);
						//print_r($rows);exit();
						//print_r("disini23");exit();
                        $data = array(); // initialization variable
                        $vehicle_device = "";
                        $engine = "";
                
                        foreach($rows as $obj)
                        {
                            if($vehicle_device != $rowvehicle[$x]->vehicle_device)
                            {
                                $no=0;
                                $no_data = 1;
                            }
                
                            if(substr($obj->gps_info_io_port, 4, 1) == 1)
                            { //engine ON
                                if($engine != "ON") 
                                {
                                    $no++;
                                    $no_data = 1;
                                }
                    
                                if($no == 0) $no++;
                                if($no_data == 1)
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['ON']['start'] = $obj;
                                }
                                else
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['ON']['end'] = $obj;
                                }
                                $no_data++;
                                $engine = "ON";
                            }
                
                            if(substr($obj->gps_info_io_port, 4, 1) == 0)
                            { //engine OFF
                                if($engine != "OFF") 
                                {
                                    $no++;
                                    $no_data = 1;
                                }
                    
                                if($no == 0) $no++;
                                if($no_data == 1)
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['OFF']['start'] = $obj;
                                }
                                else
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['OFF']['end'] = $obj;
                                }
                    
                                $no_data++;
                                $engine = "OFF";
                            }
                    
                            $vehicle_device = $rowvehicle[$x]->vehicle_device;
                        }//end loop foreach rows
                
                        $i=1;
                        $cummulative = 0;
						$cummulative_dur = 0; 
                        printf("WRITE DATA : ");
                        foreach($data as $vehicles=>$value_vehicles)
                        {
                            foreach($value_vehicles as $number=>$value_number)
                            {
                                foreach($value_number as $engine=>$report)
                                {
                                    if(!isset($report['end']))
                                    {
                                        $report['end'] = $report['start'];
                                    }
									
                                    $data_report[$vehicles][$number][$engine]['start'] = $report['start'];
                                    $data_report[$vehicles][$number][$engine]['end'] = $report['end'];
                                    $duration = get_time_difference($report['start']->gps_time, $report['end']->gps_time);
                       
									
									$start_1 = dbmaketime($report['start']->gps_time);
									$end_1 = dbmaketime($report['end']->gps_time);
									$lama_durasi = $end_1 - $start_1;
										
                                    $show_duration = "";
                                    if($duration[0]!=0)
                                    {
                                        $show_duration .= $duration[0] ." Day ";
                                    }
                        
                                    if($duration[1]!=0)
                                    {
                                        $show_duration .= $duration[1] ." Hour ";
                                    }
                        
                                    if($duration[2]!=0)
                                    {
                                        $show_duration .= $duration[2] ." Min";
                                    }
                        
                                    if($show_duration == "")
                                    {
                                        $show_duration .= "0 Min";
                                    }
									
									unset($datainsert);
                                    $data_report[$vehicles][$number][$engine]['duration'] = $show_duration;
                                    $mileage = round(($report['end']->gps_info_distance - $report['start']->gps_info_distance)/1000, 2);
                                    $data_report[$vehicles][$number][$engine]['mileage'] = round($mileage, 2);
									
                                    $location_start = $this->getPosition($report['start']->gps_longitude, $report['start']->gps_ew, $report['start']->gps_latitude, $report['start']->gps_ns);
                                    $data_report[$vehicles][$number][$engine]['location_start'] = $location_start;
                        
                                    $geofence_start = $this->getGeofence_location($report['start']->gps_longitude, $report['start']->gps_ew, $report['start']->gps_latitude, $report['start']->gps_ns, $report['start']->gps_name);
                                    $data_report[$vehicles][$number][$engine]['geofence_start'] = $geofence_start;
                        
                                    $location_end = $this->getPosition($report['end']->gps_longitude, $report['end']->gps_ew, $report['end']->gps_latitude, $report['end']->gps_ns);
                                    $data_report[$vehicles][$number][$engine]['location_end'] = $location_end;
                        
                                    $geofence_end = $this->getGeofence_location($report['end']->gps_longitude, $report['end']->gps_ew, $report['end']->gps_latitude, $report['end']->gps_ns, $report['end']->gps_name);
                                    $data_report[$vehicles][$number][$engine]['geofence_end'] = $geofence_end;
                        
                                    $cummulative += $data_report[$vehicles][$number][$engine]['mileage'];
									$cummulative_dur += $lama_durasi;
                                    //print_r($data_report[$vehicles][$number][$engine]['location_start']->display_name);exit();
							
                                    printf("|%s|",$i);
                                    
                                    //mileage
                                    $xme = $data_report[$vehicles][$number][$engine]['mileage'];
                                    $xxme = explode(".",$xme);
                                    if (isset($xxme[1]))
                                    {
                                        $xsub = substr($xxme[1],0,2); 
                                        $x_mile = $xxme[0].".".$xsub;     
                                    }
                                    else
                                    {
                                        $x_mile = $xxme[0];
                                    }
                            
                                    //cummulative mileage
                                    $xcum = $cummulative;
                                    $xxcum = explode(".",$xcum);
                                    if (isset($xxcum[1]))
                                    {
                                        $xcumsub = substr($xxcum[1],0,2); 
                                        $x_cum = $xxcum[0].".".$xcumsub;     
                                    }
                                    else
                                    {
                                        $x_cum = $xxcum[0];
                                    }

                                    if (isset($data_report[$vehicles][$number][$engine]['geofence_start']) && ($data_report[$vehicles][$number][$engine]['geofence_start'] != ""))
                                    {
                                        $z = $data_report[$vehicles][$number][$engine]['geofence_start'];
                                        $y = explode("#",$z);
                            
                                        $valexcel = "Geofence : "." ".$y[1]." ".($data_report[$vehicles][$number][$engine]['location_start']->display_name);
                                        /* $objPHPExcel->getActiveSheet()->SetCellValue('I'.(5+$i), $valexcel);
                                        $objPHPExcel->getActiveSheet()->getStyle('I'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); */
										$datainsert["playback_location_start"] = $valexcel;
                                    } 
                                    else
                                    {
                                        /* $objPHPExcel->getActiveSheet()->SetCellValue('I'.(5+$i), ($data_report[$vehicles][$number][$engine]['location_start']->display_name));
                                        $objPHPExcel->getActiveSheet()->getStyle('I'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); */
										$datainsert["playback_location_start"] = $data_report[$vehicles][$number][$engine]['location_start']->display_name;
                                    }
                        
                                    if (isset($data_report[$vehicles][$number][$engine]['geofence_end']) && ($data_report[$vehicles][$number][$engine]['geofence_end'] != ""))
                                    {
                                        $j = $data_report[$vehicles][$number][$engine]['geofence_end'];
                                        $k = explode("#",$j);
                                        $valexcel = "Geofence : "." ".$k[1]." ".($data_report[$vehicles][$number][$engine]['location_end']->display_name);
                                        /* $objPHPExcel->getActiveSheet()->SetCellValue('J'.(5+$i), $valexcel);
                                        $objPHPExcel->getActiveSheet()->getStyle('J'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); */
										$datainsert["playback_location_end"] = $valexcel;
                                    }
                                    else
                                    {
                                        /* $objPHPExcel->getActiveSheet()->SetCellValue('J'.(5+$i), ($data_report[$vehicles][$number][$engine]['location_end']->display_name));
                                        $objPHPExcel->getActiveSheet()->getStyle('J'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); */
										$datainsert["playback_location_end"] = $data_report[$vehicles][$number][$engine]['location_end']->display_name;
                                    }
									
									//kondisi on && 0 km speed 0 
									//1800 == 30 menit
									if($mileage < 1 && $engine ="ON" && $lama_durasi > 1800 ){
										if ($engine == 1 )
										{
											$statusengine = "ON";
										}else{
											$statusengine = "OFF";
										}
									
										unset($newdata);
										$location_start = $this->getPosition($report['start']->gps_longitude, $report['start']->gps_ew, $report['start']->gps_latitude, $report['start']->gps_ns);
										$data_report[$vehicles][$number][$engine]['location_start'] = $location_start;
							
										$geofence_start = $this->getGeofence_location($report['start']->gps_longitude, $report['start']->gps_ew, $report['start']->gps_latitude, $report['start']->gps_ns, $report['start']->gps_name);
										$data_report[$vehicles][$number][$engine]['geofence_start'] = $geofence_start;
							
										$location_end = $this->getPosition($report['end']->gps_longitude, $report['end']->gps_ew, $report['end']->gps_latitude, $report['end']->gps_ns);
										$data_report[$vehicles][$number][$engine]['location_end'] = $location_end;
							
										$geofence_end = $this->getGeofence_location($report['end']->gps_longitude, $report['end']->gps_ew, $report['end']->gps_latitude, $report['end']->gps_ns, $report['end']->gps_name);
										$data_report[$vehicles][$number][$engine]['geofence_end'] = $geofence_end;
										
										$newdata["playback_vehicle_device"] = $rowvehicle[$x]->vehicle_device;
										$newdata["playback_vehicle_no"] = $rowvehicle[$x]->vehicle_no;
										$newdata["playback_vehicle_name"] = $rowvehicle[$x]->vehicle_name;
										$newdata["playback_engine"] = $statusengine;
										$newdata["playback_start_time"] = date("Y-m-d H:i:s", strtotime("+7 hour", strtotime($report['start']->gps_time)));
										$newdata["playback_end_time"] = date("Y-m-d H:i:s", strtotime("+7 hour", strtotime($report['end']->gps_time)));
										//$newdata["playback_duration"] = $data_report[$vehicles][$number][$engine]['duration'];
										$newdata["playback_duration"] = $lama_durasi;
										//$newdata["playback_cumm_duration"] = $cummulative_dur;
										$newdata["playback_mileage"] = $x_mile;
										$newdata["playback_cumm_mileage"] = $x_cum;
										$newdata["playback_coor_start"] = $report['start']->gps_latitude_real." ".$report['start']->gps_longitude_real;
										$newdata["playback_coor_end"] = $report['end']->gps_latitude_real." ".$report['end']->gps_longitude_real;
										$this->dbplayback = $this->load->database("ssi_playback",TRUE);
										$this->dbplayback->insert($dbtable,$newdata);
										
										printf("KET : ".$lama_durasi." ".$engine." ".$mileage."\r\n");
										printf("INSERT TO NEW DATA \r\n");
	
									}
									
									
									$datainsert["playback_vehicle_device"] = $rowvehicle[$x]->vehicle_device;
									$datainsert["playback_vehicle_no"] = $rowvehicle[$x]->vehicle_no;
									$datainsert["playback_vehicle_name"] = $rowvehicle[$x]->vehicle_name;
									$datainsert["playback_engine"] = $engine;
									$datainsert["playback_start_time"] = date("Y-m-d H:i:s", strtotime("+7 hour", strtotime($report['start']->gps_time)));
									$datainsert["playback_end_time"] = date("Y-m-d H:i:s", strtotime("+7 hour", strtotime($report['end']->gps_time)));
									//$datainsert["playback_duration"] = $data_report[$vehicles][$number][$engine]['duration'];
									$datainsert["playback_duration"] = $lama_durasi;
									$datainsert["playback_cumm_duration"] = $cummulative_dur;
									$datainsert["playback_mileage"] = $x_mile;
									$datainsert["playback_cumm_mileage"] = $x_cum;
									$datainsert["playback_coor_start"] = $report['start']->gps_latitude_real." ".$report['start']->gps_longitude_real;
									$datainsert["playback_coor_end"] = $report['end']->gps_latitude_real." ".$report['end']->gps_longitude_real;
									
									$this->dbplayback = $this->load->database("ssi_playback",TRUE);
									$this->dbplayback->insert($dbtable,$datainsert);
									
                                    $i++;
                                }
                            }
                        }
            
                        /* $styleArray = array(
                            'borders' => array(
                            'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                            )
                            );
            
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J'.(4+$i))->applyFromArray($styleArray);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J'.(4+$i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J'.(4+$i))->getAlignment()->setWrapText(true);
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Playback_Report');
            
                        printf("CREATE FILE FOR : %s \r\n",$rowvehicle[$x]->vehicle_device);
                        
                        // Save Excel
                        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
                        @mkdir($report_path, DIR_WRITE_MODE); //REPORT_PATH -> config->constant */
                
                        /* if (isset($ex_vno))
                        {
                            if (isset($ex_vno[1]))
                            {
                                $filecreatedname = "Playback_".$ex_vno[0].$ex_vno[1]."_".$datefilename.".xls";    
                            }
                            else
                            {
                                $filecreatedname = "Playback_".$ex_vno[0]."_".$datefilename.".xls";       
                            }
                        }
                        else
                        {
                            $filecreatedname = "Playback_".$vehicle_no."_".$datefilename.".xls";  
                        } */
                  
                       /*  $objWriter->save($report_path.$filecreatedname);
                        printf("CREATE FILE DONE : %s \r\n",$filecreatedname);
            
                        $public_path = $domain_server.$pub_path.$filecreatedname; */
            
                        printf("INSERT TO DATABASE TRANSPORTER \r\n");
                        $data_insert["autoreport_vehicle_id"] = $rowvehicle[$x]->vehicle_id;
                        $data_insert["autoreport_vehicle_device"] = $rowvehicle[$x]->vehicle_device;
                        $data_insert["autoreport_user_id"] = $rowvehicle[$x]->user_id;
                        $data_insert["autoreport_company"] = $rowvehicle[$x]->user_company;
                        $data_insert["autoreport_data_startdate"] = $startdate;
                        $data_insert["autoreport_data_enddate"] = $enddate;
                        $data_insert["autoreport_type"] = $report_type;
                        /* $data_insert["autoreport_file_path"] = $report_path;
                        $data_insert["autoreport_filename"] = $filecreatedname;
                        $data_insert["autoreport_download_path"] = $report_path.$filecreatedname;
                        $data_insert["autoreport_public_path"] = $public_path; */
                        $data_insert["autoreport_process_date"] = $process_date;
                        $data_insert["autoreport_insert_db"] = date("Y-m-d H:i:s");
            
                        $this->dbtrans->insert("autoreport_playback_ssi",$data_insert);
                        printf("INSERT OK \r\n");
            
                        printf("DELETE CACHE HISTORY \r\n");
                        $this->dbhist->cache_delete_all();
                        $this->dbtrans->cache_delete_all();
                        unset($data);
            
                        printf("FINISH FOR VEHICLE : %s \r\n",$rowvehicle[$x]->vehicle_device);
                        printf("============================================ \r\n");
                    }        
                }
                else
                {
                    printf("SKIP VEHICLE ( NO VEHICLE WEB SOCKET ) \r\n");
                    printf("-------------------------------------- \r\n");
                }    
            }
            else
            {
                printf("SKIP VEHICLE ( NO VEHICLE INFO ) \r\n");
                printf("-------------------------------- \r\n");    
            }
        }//end loop vehicle
		
        unset($datalog);
        $datalog["cron_name"] = "Playback Report SSI";
        $datalog["cron_start_data"] = $startdate;
        $datalog["cron_end_data"] = $enddate;
        $datalog["cron_start_proses"] = $startproses;
        $datalog["cron_end_proses"] = date("Y-m-d H:i:s");
        $this->dbtrans->insert("log_cronjobs",$datalog);
        
		$finishtime = date("d-m-Y H:i:s");
        printf("AUTOREPORT PLAYBACK DONE %s\r\n",$finishtime);
	}
	
	//no
    function playback_peruser($userid = "", $startdate = "", $enddate = "")
    {
        printf("PROSES AUTO REPORT PLAYBACK >> START \r\n");
        $startproses = date("Y-m-d H:i:s");
        
        include 'class/PHPExcel.php';
        include 'class/PHPExcel/Writer/Excel2007.php';
        
        $report_type = "playback";
        $process_date = date("Y-m-d H:i:s");
        $domain_server = "http://202.129.190.194/";
        $z = 0;

        if ($startdate == "") {
            $startdate = date("Y-m-d 00:00:00", strtotime("yesterday"));
            $datefilenamestart = date("Ymd", strtotime("yesterday")); 
        }
        
        if ($startdate != "")
        {
            $datefilenamestart = date("Ymd", strtotime($startdate));     
            $startdate = date("Y-m-d 00:00:00", strtotime($startdate));
        }
        
        if ($enddate != "")
        {
            $enddate = date("Y-m-d 23:59:59", strtotime($enddate));
            $datefilenameend = date("Ymd", strtotime($enddate));
        }
        
        if ($enddate == "") {
            $enddate = date("Y-m-d 23:59:59", strtotime("yesterday"));
            $datefilenameend = date("Ymd", strtotime("yesterday"));
        }
        
        $datefilename = $datefilenamestart."_".$datefilenameend;
        
        $month = date("F", strtotime($startdate));
        $year = date("Y", strtotime($startdate));
        
        $sdate = date("Y-m-d H:i:s", strtotime("-7 hour", strtotime($startdate)));
        $edate = date("Y-m-d H:i:s", strtotime("-7 hour", strtotime($enddate)));
               
        if ($userid != "")
        {
            $this->db->where("vehicle_user_id", $userid);    
        } 

        $this->db->join("user", "vehicle_user_id = user_id", "left outer");
        $this->db->where("vehicle_status <>", 3);
        $q = $this->db->get("vehicle");
        $rowvehicle = $q->result();
        
        $total_process = count($rowvehicle);
        printf("TOTAL PROSES VEHICLE : %s \r\n",$total_process);
        printf("============================================ \r\n");
        
        for($x=0;$x<count($rowvehicle);$x++) 
        {
            printf("PROSES VEHICLE : %s (%d/%d) \r\n",$rowvehicle[$x]->vehicle_device, ++$z, $total_process);
            
                    $this->dbhist = $this->load->database("gpshistory",true);
					$this->dbhist2 = $this->load->database("gpshistory2",true);
                    $this->dbtrans = $this->load->database("transporter",true);
        
                    unset($data_insert);
                    
                    //cek apakah sudah pernah ada filenya
                    $this->dbtrans->where("autoreport_vehicle_id",$rowvehicle[$x]->vehicle_id);
                    $this->dbtrans->where("autoreport_data_startdate",$startdate);
                    $this->dbtrans->limit(1);
                    $qrpt = $this->dbtrans->get("autoreport_playback");
                    $rrpt = $qrpt->row();
                    
                    if (count($rrpt)>0)
                    {
                        printf("VEHICLE %s SUDAH PERNAH DI PROSES, HAPUS DULU DATA SEBELUMNYA \r\n", $rowvehicle[$x]->vehicle_device);     
                    }
                    else
                    {
                        $objPHPExcel = new PHPExcel();
                        $objPHPExcel->getProperties()->setCreator("lacak-mobil.com");
                        $objPHPExcel->getProperties()->setLastModifiedBy("lacak-mobil.com");
                        $objPHPExcel->getProperties()->setTitle("Playback Report");
                        $objPHPExcel->getProperties()->setSubject("Playback Report Lacak-mobil.com");
                        $objPHPExcel->getProperties()->setDescription("Playback Report Lacak-mobil.com");    
                
                        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(1);
                        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
                        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
                        $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1);
                        $objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman');
                        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
                        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
                        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);            
                        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(45);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(45);
                
                        //Header
                        $objPHPExcel->setActiveSheetIndex(0);
                        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
                        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'PLAYBACK REPORT');
                        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                        $objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
                
                        //Top Header
                        $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'No');
                        $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'Vehicle');
                        $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Engine');
                        $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Start Time');
                        $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'End Time');
                        $objPHPExcel->getActiveSheet()->SetCellValue('F5', 'Duration');
                        $objPHPExcel->getActiveSheet()->SetCellValue('G5', 'Trip Mileage');
                        $objPHPExcel->getActiveSheet()->SetCellValue('H5', 'Cummulative Mileage');
                        $objPHPExcel->getActiveSheet()->SetCellValue('I5', 'Location Start');
                        $objPHPExcel->getActiveSheet()->SetCellValue('J5', 'Location End');
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J5')->getFont()->setBold(true);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J5')->getFill()->getStartColor()->setRGB('bfbfbf;');
                
                        $vehicle_device = explode("@", $rowvehicle[$x]->vehicle_device);  
                        $vehicle_no = $rowvehicle[$x]->vehicle_no;
                
                        $ex_vno = explode("/",$vehicle_no);

                        if ($rowvehicle[$x]->vehicle_type == "T5" || $rowvehicle[$x]->vehicle_type == "T5 PULSE")
                        {
                            $table = $vehicle_device[0]."@t5_gps";
                            $tableinfo = $vehicle_device[0]."@t5_info";    
                        }
                        else
                        {
                            $table = strtolower($vehicle_device[0])."@".strtolower($vehicle_device[1])."_gps";
                            $tableinfo = strtolower($vehicle_device[0])."@".strtolower($vehicle_device[1])."_info";
                        }
            
                        $report_path = "/home/transporter/public_html/assets/media/autoreport/".$report_type."/".
                                $year."/".$month."/".$vehicle_device[0]."/";
                        $pub_path = "assets/media/autoreport/".$report_type."/".$year."/".$month."/".$vehicle_device[0]."/";
            
                        $this->dbhist->join($tableinfo, "gps_info_time = gps_time AND gps_info_device = CONCAT(gps_name,'@',gps_host)");    
                        $this->dbhist->where_in("gps_info_device", $rowvehicle[$x]->vehicle_device);
                        $this->dbhist->where("gps_time >=", $sdate);
                        $this->dbhist->where("gps_time <=", $edate);    
                        $this->dbhist->order_by("gps_time","asc");
                        $this->dbhist->from($table);
                        $q = $this->dbhist->get();
                        $rows1 = $q->result();
						
						$this->dbhist2->join($tableinfo, "gps_info_time = gps_time AND gps_info_device = CONCAT(gps_name,'@',gps_host)");    
                        $this->dbhist2->where_in("gps_info_device", $rowvehicle[$x]->vehicle_device);
                        $this->dbhist2->where("gps_time >=", $sdate);
                        $this->dbhist2->where("gps_time <=", $edate);    
                        $this->dbhist2->order_by("gps_time","asc");
                        $this->dbhist2->from($table);
                        $q2 = $this->dbhist2->get();
                        $rows2 = $q2->result();
						
						$rows = array_merge($rows1, $rows2);
                
                        $data = array(); // initialization variable
                        $vehicle_device = "";
                        $engine = "";
                
                        foreach($rows as $obj)
                        {
                            if($vehicle_device != $rowvehicle[$x]->vehicle_device)
                            {
                                $no=0;
                                $no_data = 1;
                            }
                
                            if(substr($obj->gps_info_io_port, 4, 1) == 1)
                            { //engine ON
                                if($engine != "ON") 
                                {
                                    $no++;
                                    $no_data = 1;
                                }
                    
                                if($no == 0) $no++;
                                if($no_data == 1)
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['ON']['start'] = $obj;
                                }
                                else
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['ON']['end'] = $obj;
                                }
                                $no_data++;
                                $engine = "ON";
                            }
                
                            if(substr($obj->gps_info_io_port, 4, 1) == 0)
                            { //engine OFF
                                if($engine != "OFF") 
                                {
                                    $no++;
                                    $no_data = 1;
                                }
                    
                                if($no == 0) $no++;
                                if($no_data == 1)
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['OFF']['start'] = $obj;
                                }
                                else
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['OFF']['end'] = $obj;
                                }
                    
                                $no_data++;
                                $engine = "OFF";
                            }
                    
                            $vehicle_device = $rowvehicle[$x]->vehicle_device;
                        }//end loop foreach rows
                
                        $i=1;
                        $cummulative = 0; 
                        printf("WRITE DATA EXCEL : ");
                        foreach($data as $vehicles=>$value_vehicles)
                        {
                            foreach($value_vehicles as $number=>$value_number)
                            {
                                foreach($value_number as $engine=>$report)
                                {
                                    if(!isset($report['end']))
                                    {
                                        $report['end'] = $report['start'];
                                    }
                            
                                    $data_report[$vehicles][$number][$engine]['start'] = $report['start'];
                                    $data_report[$vehicles][$number][$engine]['end'] = $report['end'];
                                    $duration = get_time_difference($report['start']->gps_time, $report['end']->gps_time);
                       
                                    $show_duration = "";
                                    if($duration[0]!=0)
                                    {
                                        $show_duration .= $duration[0] ." Day ";
                                    }
                        
                                    if($duration[1]!=0)
                                    {
                                        $show_duration .= $duration[1] ." Hour ";
                                    }
                        
                                    if($duration[2]!=0)
                                    {
                                        $show_duration .= $duration[2] ." Min";
                                    }
                        
                                    if($show_duration == "")
                                    {
                                        $show_duration .= "0 Min";
                                    }
                        
                                    $data_report[$vehicles][$number][$engine]['duration'] = $show_duration;
                                    $mileage = round(($report['end']->gps_info_distance - $report['start']->gps_info_distance)/1000, 2);
                                    $data_report[$vehicles][$number][$engine]['mileage'] = round($mileage, 2);
                        
                                    $location_start = $this->getPosition($report['start']->gps_longitude, $report['start']->gps_ew, $report['start']->gps_latitude, $report['start']->gps_ns);
                                    $data_report[$vehicles][$number][$engine]['location_start'] = $location_start;
                        
                                    $geofence_start = $this->getGeofence_location($report['start']->gps_longitude, $report['start']->gps_ew, $report['start']->gps_latitude, $report['start']->gps_ns, $report['start']->gps_name);
                                    $data_report[$vehicles][$number][$engine]['geofence_start'] = $geofence_start;
                        
                                    $location_end = $this->getPosition($report['end']->gps_longitude, $report['end']->gps_ew, $report['end']->gps_latitude, $report['end']->gps_ns);
                                    $data_report[$vehicles][$number][$engine]['location_end'] = $location_end;
                        
                                    $geofence_end = $this->getGeofence_location($report['end']->gps_longitude, $report['end']->gps_ew, $report['end']->gps_latitude, $report['end']->gps_ns, $report['end']->gps_name);
                                    $data_report[$vehicles][$number][$engine]['geofence_end'] = $geofence_end;
                        
                                    $cummulative += $data_report[$vehicles][$number][$engine]['mileage'];
                                    //print_r($data_report[$vehicles][$number][$engine]['location_start']->display_name);exit();
                        
                                    printf("|%s|",$i);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.(5+$i), $i);
                                    $objPHPExcel->getActiveSheet()->getStyle('A'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.(5+$i), $rowvehicle[$x]->vehicle_no);
                                    $objPHPExcel->getActiveSheet()->getStyle('B'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.(5+$i), $engine);
                                    $objPHPExcel->getActiveSheet()->getStyle('C'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.(5+$i), date("d/m/Y H:i:s", strtotime("+7 hour", strtotime($report['start']->gps_time))));
                                    $objPHPExcel->getActiveSheet()->getStyle('D'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.(5+$i), date("d/m/Y H:i:s", strtotime("+7 hour", strtotime($report['end']->gps_time))));
                                    $objPHPExcel->getActiveSheet()->getStyle('E'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.(5+$i), $data_report[$vehicles][$number][$engine]['duration']);
                                    $objPHPExcel->getActiveSheet()->getStyle('F'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            
                                    //mileage
                                    $xme = $data_report[$vehicles][$number][$engine]['mileage'];
                                    $xxme = explode(".",$xme);
                                    if (isset($xxme[1]))
                                    {
                                        $xsub = substr($xxme[1],0,2); 
                                        $x_mile = $xxme[0].".".$xsub;     
                                    }
                                    else
                                    {
                                        $x_mile = $xxme[0];
                                    }
                            
                                    //cummulative mileage
                                    $xcum = $cummulative;
                                    $xxcum = explode(".",$xcum);
                                    if (isset($xxcum[1]))
                                    {
                                        $xcumsub = substr($xxcum[1],0,2); 
                                        $x_cum = $xxcum[0].".".$xcumsub;     
                                    }
                                    else
                                    {
                                        $x_cum = $xxcum[0];
                                    }
                            
                            
                                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.(5+$i), $x_mile." "."KM");
                                    $objPHPExcel->getActiveSheet()->getStyle('G'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('H'.(5+$i), $x_cum." "."KM");
                                    $objPHPExcel->getActiveSheet()->getStyle('H'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        
                                    if (isset($data_report[$vehicles][$number][$engine]['geofence_start']) && ($data_report[$vehicles][$number][$engine]['geofence_start'] != ""))
                                    {
                                        $z = $data_report[$vehicles][$number][$engine]['geofence_start'];
                                        $y = explode("#",$z);
                            
                                        $valexcel = "Geofence : "." ".$y[1]." ".($data_report[$vehicles][$number][$engine]['location_start']->display_name);
                                        $objPHPExcel->getActiveSheet()->SetCellValue('I'.(5+$i), $valexcel);
                                        $objPHPExcel->getActiveSheet()->getStyle('I'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    } 
                                    else
                                    {
                                        $objPHPExcel->getActiveSheet()->SetCellValue('I'.(5+$i), ($data_report[$vehicles][$number][$engine]['location_start']->display_name));
                                        $objPHPExcel->getActiveSheet()->getStyle('I'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                        
                                    if (isset($data_report[$vehicles][$number][$engine]['geofence_end']) && ($data_report[$vehicles][$number][$engine]['geofence_end'] != ""))
                                    {
                                        $j = $data_report[$vehicles][$number][$engine]['geofence_end'];
                                        $k = explode("#",$j);
                                        $valexcel = "Geofence : "." ".$k[1]." ".($data_report[$vehicles][$number][$engine]['location_end']->display_name);
                                        $objPHPExcel->getActiveSheet()->SetCellValue('J'.(5+$i), $valexcel);
                                        $objPHPExcel->getActiveSheet()->getStyle('J'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                                    else
                                    {
                                        $objPHPExcel->getActiveSheet()->SetCellValue('J'.(5+$i), ($data_report[$vehicles][$number][$engine]['location_end']->display_name));
                                        $objPHPExcel->getActiveSheet()->getStyle('J'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                                    $i++;
                                }
                            }
                        }
            
                        $styleArray = array(
                            'borders' => array(
                            'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                            )
                            );
            
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J'.(4+$i))->applyFromArray($styleArray);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J'.(4+$i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J'.(4+$i))->getAlignment()->setWrapText(true);
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Playback_Report');
            
                        printf("CREATE FILE FOR : %s \r\n",$rowvehicle[$x]->vehicle_device);
                        
                        // Save Excel
                        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
                        @mkdir($report_path, DIR_WRITE_MODE); //REPORT_PATH -> config->constant
                
                        if (isset($ex_vno))
                        {
                            if (isset($ex_vno[1]))
                            {
                                $filecreatedname = "Playback_".$ex_vno[0].$ex_vno[1]."_".$datefilename.".xls";    
                            }
                            else
                            {
                                $filecreatedname = "Playback_".$ex_vno[0]."_".$datefilename.".xls";       
                            }
                        }
                        else
                        {
                            $filecreatedname = "Playback_".$vehicle_no."_".$datefilename.".xls";  
                        }
                  
                        $objWriter->save($report_path.$filecreatedname);
                        printf("CREATE FILE DONE : %s \r\n",$filecreatedname);
            
                        $public_path = $domain_server.$pub_path.$filecreatedname;
            
                        printf("INSERT TO DATABASE TRANSPORTER \r\n");
                        $data_insert["autoreport_vehicle_id"] = $rowvehicle[$x]->vehicle_id;
                        $data_insert["autoreport_vehicle_device"] = $rowvehicle[$x]->vehicle_device;
                        $data_insert["autoreport_user_id"] = $rowvehicle[$x]->user_id;
                        $data_insert["autoreport_company"] = $rowvehicle[$x]->user_company;
                        $data_insert["autoreport_data_startdate"] = $startdate;
                        $data_insert["autoreport_data_enddate"] = $enddate;
                        $data_insert["autoreport_type"] = $report_type;
                        $data_insert["autoreport_file_path"] = $report_path;
                        $data_insert["autoreport_filename"] = $filecreatedname;
                        $data_insert["autoreport_download_path"] = $report_path.$filecreatedname;
                        $data_insert["autoreport_public_path"] = $public_path;
                        $data_insert["autoreport_process_date"] = $process_date;
                        $data_insert["autoreport_insert_db"] = date("Y-m-d H:i:s");
            
                        $this->dbtrans->insert("autoreport_playback",$data_insert);
                        printf("INSERT OK \r\n");
            
                        printf("DELETE CACHE HISTORY \r\n");
                        $this->dbhist->cache_delete_all();
                        $this->dbtrans->cache_delete_all();
                        unset($data);
            
                        printf("FINISH FOR VEHICLE : %s \r\n",$rowvehicle[$x]->vehicle_device);
                        printf("============================================ \r\n");
                    }        
        }//end loop vehicle
        
        unset($datalog);
        $datalog["cron_name"] = "Autoreport Trip Mileage";
        $datalog["cron_start_data"] = $startdate;
        $datalog["cron_end_data"] = $enddate;
        $datalog["cron_start_proses"] = $startproses;
        $datalog["cron_end_proses"] = date("Y-m-d H:i:s");
        $this->dbtrans->insert("log_cronjobs",$datalog);
        
        printf("AUTOREPORT PLAYBACK DONE \r\n");
    }
    
	//no
    function playback_pervehicle($name = "", $host = "", $startdate = "", $enddate = "")
    {
        printf("PROSES AUTO REPORT PLAYBACK >> START \r\n");
        $startproses = date("Y-m-d H:i:s");
        
        include 'class/PHPExcel.php';
        include 'class/PHPExcel/Writer/Excel2007.php';
        
        $report_type = "playback";
        $process_date = date("Y-m-d H:i:s");
        $domain_server = "http://202.129.190.194/";
        $z = 0;

        if ($startdate == "") {
            $startdate = date("Y-m-d 00:00:00", strtotime("yesterday"));
            $datefilenamestart = date("Ymd", strtotime("yesterday")); 
        }
        
        if ($startdate != "")
        {
            $datefilenamestart = date("Ymd", strtotime($startdate));     
            $startdate = date("Y-m-d 00:00:00", strtotime($startdate));
        }
        
        if ($enddate != "")
        {
            $enddate = date("Y-m-d 23:59:59", strtotime($enddate));
            $datefilenameend = date("Ymd", strtotime($enddate));
        }
        
        if ($enddate == "") {
            $enddate = date("Y-m-d 23:59:59", strtotime("yesterday"));
            $datefilenameend = date("Ymd", strtotime("yesterday"));
        }
        
        $datefilename = $datefilenamestart."_".$datefilenameend;
        
        $month = date("F", strtotime($startdate));
        $year = date("Y", strtotime($startdate));
        
        $sdate = date("Y-m-d H:i:s", strtotime("-7 hour", strtotime($startdate)));
        $edate = date("Y-m-d H:i:s", strtotime("-7 hour", strtotime($enddate)));
        
        
        if ($name != "" && $host != "")
        {
            $this->db->where("vehicle_device", $name."@".$host);    
        } 

        $this->db->join("user", "vehicle_user_id = user_id", "left outer");
        $this->db->where("vehicle_status <>", 3);
        $q = $this->db->get("vehicle");
        $rowvehicle = $q->result();
        
        $total_process = count($rowvehicle);
        printf("TOTAL PROSES VEHICLE : %s \r\n",$total_process);
        printf("============================================ \r\n");
        
        for($x=0;$x<count($rowvehicle);$x++) 
        {
            printf("PROSES VEHICLE : %s (%d/%d) \r\n",$rowvehicle[$x]->vehicle_device, ++$z, $total_process);
            
                    $this->dbhist = $this->load->database("gpshistory",true);
					$this->dbhist2 = $this->load->database("gpshistory2",true);
                    $this->dbtrans = $this->load->database("transporter",true);
        
                    unset($data_insert);
                    
                    //cek apakah sudah pernah ada filenya
                    $this->dbtrans->where("autoreport_vehicle_id",$rowvehicle[$x]->vehicle_id);
                    $this->dbtrans->where("autoreport_data_startdate",$startdate);
                    $this->dbtrans->limit(1);
                    $qrpt = $this->dbtrans->get("autoreport_playback");
                    $rrpt = $qrpt->row();
                    
                    if (count($rrpt)>0)
                    {
                        printf("VEHICLE %s SUDAH PERNAH DI PROSES, HAPUS DULU DATA SEBELUMNYA \r\n", $rowvehicle[$x]->vehicle_device);     
                    }
                    else
                    {
                        $objPHPExcel = new PHPExcel();
                        $objPHPExcel->getProperties()->setCreator("lacak-mobil.com");
                        $objPHPExcel->getProperties()->setLastModifiedBy("lacak-mobil.com");
                        $objPHPExcel->getProperties()->setTitle("Playback Report");
                        $objPHPExcel->getProperties()->setSubject("Playback Report Lacak-mobil.com");
                        $objPHPExcel->getProperties()->setDescription("Playback Report Lacak-mobil.com");    
                
                        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(1);
                        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
                        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
                        $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1);
                        $objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman');
                        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
                        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
                        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);            
                        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(45);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(45);
                
                        //Header
                        $objPHPExcel->setActiveSheetIndex(0);
                        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
                        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'PLAYBACK REPORT');
                        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                        $objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
                
                        //Top Header
                        $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'No');
                        $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'Vehicle');
                        $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Engine');
                        $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Start Time');
                        $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'End Time');
                        $objPHPExcel->getActiveSheet()->SetCellValue('F5', 'Duration');
                        $objPHPExcel->getActiveSheet()->SetCellValue('G5', 'Trip Mileage');
                        $objPHPExcel->getActiveSheet()->SetCellValue('H5', 'Cummulative Mileage');
                        $objPHPExcel->getActiveSheet()->SetCellValue('I5', 'Location Start');
                        $objPHPExcel->getActiveSheet()->SetCellValue('J5', 'Location End');
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J5')->getFont()->setBold(true);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J5')->getFill()->getStartColor()->setRGB('bfbfbf;');
                
                        $vehicle_device = explode("@", $rowvehicle[$x]->vehicle_device);  
                        $vehicle_no = $rowvehicle[$x]->vehicle_no;
                
                        $ex_vno = explode("/",$vehicle_no);

                        if ($rowvehicle[$x]->vehicle_type == "T5" || $rowvehicle[$x]->vehicle_type == "T5 PULSE")
                        {
                            $table = $vehicle_device[0]."@t5_gps";
                            $tableinfo = $vehicle_device[0]."@t5_info";    
                        }
                        else
                        {
                            $table = strtolower($vehicle_device[0])."@".strtolower($vehicle_device[1])."_gps";
                            $tableinfo = strtolower($vehicle_device[0])."@".strtolower($vehicle_device[1])."_info";
                        }
            
                        $report_path = "/home/transporter/public_html/assets/media/autoreport/".$report_type."/".
                                $year."/".$month."/".$vehicle_device[0]."/";
                        $pub_path = "assets/media/autoreport/".$report_type."/".$year."/".$month."/".$vehicle_device[0]."/";
            
                        $this->dbhist->join($tableinfo, "gps_info_time = gps_time AND gps_info_device = CONCAT(gps_name,'@',gps_host)");    
                        $this->dbhist->where_in("gps_info_device", $rowvehicle[$x]->vehicle_device);
                        $this->dbhist->where("gps_time >=", $sdate);
                        $this->dbhist->where("gps_time <=", $edate);    
                        $this->dbhist->order_by("gps_time","asc");
                        $this->dbhist->from($table);
                        $q = $this->dbhist->get();
                        $rows1 = $q->result();
						
						$this->dbhist2->join($tableinfo, "gps_info_time = gps_time AND gps_info_device = CONCAT(gps_name,'@',gps_host)");    
                        $this->dbhist2->where_in("gps_info_device", $rowvehicle[$x]->vehicle_device);
                        $this->dbhist2->where("gps_time >=", $sdate);
                        $this->dbhist2->where("gps_time <=", $edate);    
                        $this->dbhist2->order_by("gps_time","asc");
                        $this->dbhist2->from($table);
                        $q2 = $this->dbhist2->get();
                        $rows2 = $q2->result();
						
						$rows = array_merge($rows1, $rows2);
                
                        $data = array(); // initialization variable
                        $vehicle_device = "";
                        $engine = "";
                
                        foreach($rows as $obj)
                        {
                            if($vehicle_device != $rowvehicle[$x]->vehicle_device)
                            {
                                $no=0;
                                $no_data = 1;
                            }
                
                            if(substr($obj->gps_info_io_port, 4, 1) == 1)
                            { //engine ON
                                if($engine != "ON") 
                                {
                                    $no++;
                                    $no_data = 1;
                                }
                    
                                if($no == 0) $no++;
                                if($no_data == 1)
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['ON']['start'] = $obj;
                                }
                                else
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['ON']['end'] = $obj;
                                }
                                $no_data++;
                                $engine = "ON";
                            }
                
                            if(substr($obj->gps_info_io_port, 4, 1) == 0)
                            { //engine OFF
                                if($engine != "OFF") 
                                {
                                    $no++;
                                    $no_data = 1;
                                }
                    
                                if($no == 0) $no++;
                                if($no_data == 1)
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['OFF']['start'] = $obj;
                                }
                                else
                                {
                                    $data[$rowvehicle[$x]->vehicle_no."#".$rowvehicle[$x]->vehicle_name][$no]['OFF']['end'] = $obj;
                                }
                    
                                $no_data++;
                                $engine = "OFF";
                            }
                    
                            $vehicle_device = $rowvehicle[$x]->vehicle_device;
                        }//end loop foreach rows
                
                        $i=1;
                        $cummulative = 0; 
                        printf("WRITE DATA EXCEL : ");
                        foreach($data as $vehicles=>$value_vehicles)
                        {
                            foreach($value_vehicles as $number=>$value_number)
                            {
                                foreach($value_number as $engine=>$report)
                                {
                                    if(!isset($report['end']))
                                    {
                                        $report['end'] = $report['start'];
                                    }
                            
                                    $data_report[$vehicles][$number][$engine]['start'] = $report['start'];
                                    $data_report[$vehicles][$number][$engine]['end'] = $report['end'];
                                    $duration = get_time_difference($report['start']->gps_time, $report['end']->gps_time);
                       
                                    $show_duration = "";
                                    if($duration[0]!=0)
                                    {
                                        $show_duration .= $duration[0] ." Day ";
                                    }
                        
                                    if($duration[1]!=0)
                                    {
                                        $show_duration .= $duration[1] ." Hour ";
                                    }
                        
                                    if($duration[2]!=0)
                                    {
                                        $show_duration .= $duration[2] ." Min";
                                    }
                        
                                    if($show_duration == "")
                                    {
                                        $show_duration .= "0 Min";
                                    }
                        
                                    $data_report[$vehicles][$number][$engine]['duration'] = $show_duration;
                                    $mileage = round(($report['end']->gps_info_distance - $report['start']->gps_info_distance)/1000, 2);
                                    $data_report[$vehicles][$number][$engine]['mileage'] = round($mileage, 2);
                        
                                    $location_start = $this->getPosition($report['start']->gps_longitude, $report['start']->gps_ew, $report['start']->gps_latitude, $report['start']->gps_ns);
                                    $data_report[$vehicles][$number][$engine]['location_start'] = $location_start;
                        
                                    $geofence_start = $this->getGeofence_location($report['start']->gps_longitude, $report['start']->gps_ew, $report['start']->gps_latitude, $report['start']->gps_ns, $report['start']->gps_name);
                                    $data_report[$vehicles][$number][$engine]['geofence_start'] = $geofence_start;
                        
                                    $location_end = $this->getPosition($report['end']->gps_longitude, $report['end']->gps_ew, $report['end']->gps_latitude, $report['end']->gps_ns);
                                    $data_report[$vehicles][$number][$engine]['location_end'] = $location_end;
                        
                                    $geofence_end = $this->getGeofence_location($report['end']->gps_longitude, $report['end']->gps_ew, $report['end']->gps_latitude, $report['end']->gps_ns, $report['end']->gps_name);
                                    $data_report[$vehicles][$number][$engine]['geofence_end'] = $geofence_end;
                        
                                    $cummulative += $data_report[$vehicles][$number][$engine]['mileage'];
                                    //print_r($data_report[$vehicles][$number][$engine]['location_start']->display_name);exit();
                        
                                    printf("|%s|",$i);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.(5+$i), $i);
                                    $objPHPExcel->getActiveSheet()->getStyle('A'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('B'.(5+$i), $rowvehicle[$x]->vehicle_no);
                                    $objPHPExcel->getActiveSheet()->getStyle('B'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.(5+$i), $engine);
                                    $objPHPExcel->getActiveSheet()->getStyle('C'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.(5+$i), date("d/m/Y H:i:s", strtotime("+7 hour", strtotime($report['start']->gps_time))));
                                    $objPHPExcel->getActiveSheet()->getStyle('D'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.(5+$i), date("d/m/Y H:i:s", strtotime("+7 hour", strtotime($report['end']->gps_time))));
                                    $objPHPExcel->getActiveSheet()->getStyle('E'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.(5+$i), $data_report[$vehicles][$number][$engine]['duration']);
                                    $objPHPExcel->getActiveSheet()->getStyle('F'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            
                                    //mileage
                                    $xme = $data_report[$vehicles][$number][$engine]['mileage'];
                                    $xxme = explode(".",$xme);
                                    if (isset($xxme[1]))
                                    {
                                        $xsub = substr($xxme[1],0,2); 
                                        $x_mile = $xxme[0].".".$xsub;     
                                    }
                                    else
                                    {
                                        $x_mile = $xxme[0];
                                    }
                            
                                    //cummulative mileage
                                    $xcum = $cummulative;
                                    $xxcum = explode(".",$xcum);
                                    if (isset($xxcum[1]))
                                    {
                                        $xcumsub = substr($xxcum[1],0,2); 
                                        $x_cum = $xxcum[0].".".$xcumsub;     
                                    }
                                    else
                                    {
                                        $x_cum = $xxcum[0];
                                    }
                            
                            
                                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.(5+$i), $x_mile." "."KM");
                                    $objPHPExcel->getActiveSheet()->getStyle('G'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    $objPHPExcel->getActiveSheet()->SetCellValue('H'.(5+$i), $x_cum." "."KM");
                                    $objPHPExcel->getActiveSheet()->getStyle('H'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        
                                    if (isset($data_report[$vehicles][$number][$engine]['geofence_start']) && ($data_report[$vehicles][$number][$engine]['geofence_start'] != ""))
                                    {
                                        $z = $data_report[$vehicles][$number][$engine]['geofence_start'];
                                        $y = explode("#",$z);
                            
                                        $valexcel = "Geofence : "." ".$y[1]." ".($data_report[$vehicles][$number][$engine]['location_start']->display_name);
                                        $objPHPExcel->getActiveSheet()->SetCellValue('I'.(5+$i), $valexcel);
                                        $objPHPExcel->getActiveSheet()->getStyle('I'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    } 
                                    else
                                    {
                                        $objPHPExcel->getActiveSheet()->SetCellValue('I'.(5+$i), ($data_report[$vehicles][$number][$engine]['location_start']->display_name));
                                        $objPHPExcel->getActiveSheet()->getStyle('I'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                        
                                    if (isset($data_report[$vehicles][$number][$engine]['geofence_end']) && ($data_report[$vehicles][$number][$engine]['geofence_end'] != ""))
                                    {
                                        $j = $data_report[$vehicles][$number][$engine]['geofence_end'];
                                        $k = explode("#",$j);
                                        $valexcel = "Geofence : "." ".$k[1]." ".($data_report[$vehicles][$number][$engine]['location_end']->display_name);
                                        $objPHPExcel->getActiveSheet()->SetCellValue('J'.(5+$i), $valexcel);
                                        $objPHPExcel->getActiveSheet()->getStyle('J'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                                    else
                                    {
                                        $objPHPExcel->getActiveSheet()->SetCellValue('J'.(5+$i), ($data_report[$vehicles][$number][$engine]['location_end']->display_name));
                                        $objPHPExcel->getActiveSheet()->getStyle('J'.(5+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                                    $i++;
                                }
                            }
                        }
            
                        $styleArray = array(
                            'borders' => array(
                            'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                            )
                            );
            
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J'.(4+$i))->applyFromArray($styleArray);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J'.(4+$i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                        $objPHPExcel->getActiveSheet()->getStyle('A5:J'.(4+$i))->getAlignment()->setWrapText(true);
                        // Rename sheet
                        $objPHPExcel->getActiveSheet()->setTitle('Playback_Report');
            
                        printf("CREATE FILE FOR : %s \r\n",$rowvehicle[$x]->vehicle_device);
                        
                        // Save Excel
                        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
                        @mkdir($report_path, DIR_WRITE_MODE); //REPORT_PATH -> config->constant
                
                        if (isset($ex_vno))
                        {
                            if (isset($ex_vno[1]))
                            {
                                $filecreatedname = "Playback_".$ex_vno[0].$ex_vno[1]."_".$datefilename.".xls";    
                            }
                            else
                            {
                                $filecreatedname = "Playback_".$ex_vno[0]."_".$datefilename.".xls";       
                            }
                        }
                        else
                        {
                            $filecreatedname = "Playback_".$vehicle_no."_".$datefilename.".xls";  
                        }
                  
                        $objWriter->save($report_path.$filecreatedname);
                        printf("CREATE FILE DONE : %s \r\n",$filecreatedname);
            
                        $public_path = $domain_server.$pub_path.$filecreatedname;
            
                        printf("INSERT TO DATABASE TRANSPORTER \r\n");
                        $data_insert["autoreport_vehicle_id"] = $rowvehicle[$x]->vehicle_id;
                        $data_insert["autoreport_vehicle_device"] = $rowvehicle[$x]->vehicle_device;
                        $data_insert["autoreport_user_id"] = $rowvehicle[$x]->user_id;
                        $data_insert["autoreport_company"] = $rowvehicle[$x]->user_company;
                        $data_insert["autoreport_data_startdate"] = $startdate;
                        $data_insert["autoreport_data_enddate"] = $enddate;
                        $data_insert["autoreport_type"] = $report_type;
                        $data_insert["autoreport_file_path"] = $report_path;
                        $data_insert["autoreport_filename"] = $filecreatedname;
                        $data_insert["autoreport_download_path"] = $report_path.$filecreatedname;
                        $data_insert["autoreport_public_path"] = $public_path;
                        $data_insert["autoreport_process_date"] = $process_date;
                        $data_insert["autoreport_insert_db"] = date("Y-m-d H:i:s");
            
                        $this->dbtrans->insert("autoreport_playback",$data_insert);
                        printf("INSERT OK \r\n");
            
                        printf("DELETE CACHE HISTORY \r\n");
                        $this->dbhist->cache_delete_all();
                        $this->dbtrans->cache_delete_all();
                        unset($data);
            
                        printf("FINISH FOR VEHICLE : %s \r\n",$rowvehicle[$x]->vehicle_device);
                        printf("============================================ \r\n");
                    }        
        }//end loop vehicle
        
        unset($datalog);
        $datalog["cron_name"] = "Autoreport Trip Mileage";
        $datalog["cron_start_data"] = $startdate;
        $datalog["cron_end_data"] = $enddate;
        $datalog["cron_start_proses"] = $startproses;
        $datalog["cron_end_proses"] = date("Y-m-d H:i:s");
        $this->dbtrans->insert("log_cronjobs",$datalog);
        
        printf("AUTOREPORT PLAYBACK DONE \r\n");
    }
    
    function getGeofence_location($longitude, $ew, $latitude, $ns, $vehicle_device) 
    {
        
        $this->db = $this->load->database("default", true);
        $lng = getLongitude($longitude, $ew);
        $lat = getLatitude($latitude, $ns);
                                                                           
        $sql = sprintf("
                    SELECT     *
                    FROM     %sgeofence 
                    WHERE     TRUE
                            AND CONTAINS(geofence_polygon, GEOMFROMTEXT('POINT(%s %s)')) 
                            AND (geofence_user = 1933 )
                            AND (geofence_status = 1)
                    LIMIT 1 OFFSET 0", $this->db->dbprefix, $lng, $lat, $vehicle_device);

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
    
    function getPosition($longitude, $ew, $latitude, $ns){
        $gps_longitude_real = getLongitude($longitude, $ew);
        $gps_latitude_real = getLatitude($latitude, $ns);
        
        $gps_longitude_real_fmt = number_format($gps_longitude_real, 4, ".", "");
        $gps_latitude_real_fmt = number_format($gps_latitude_real, 4, ".", "");    
       // print_r($gps_longitude_real_fmt." ".$gps_latitude_real_fmt);exit();           
        $georeverse = $this->gpsmodel->GeoReverse($gps_latitude_real_fmt, $gps_longitude_real_fmt);
        
        return $georeverse;
    }
	//for ssi
	function data_operasional($startdate = "", $enddate = "")
    {
        printf("PROSES AUTO REPORT DATA OPERASIONAL KHUSUS PORT >> START \r\n");
        $startproses = date("Y-m-d H:i:s");
		$name = "";
		$host = "";
		$userid = "";
		
        $report_type = "operasional";
        $process_date = date("Y-m-d H:i:s");
		$start_time = date("Y-m-d H:i:s");
        //$domain_server = "http://202.129.190.194/";
		$report = "operasional_";
        
        if ($startdate == "") {
            $startdate = date("Y-m-d 00:00:00", strtotime("yesterday"));
            $datefilename = date("Ymd", strtotime("yesterday"));
			$month = date("F", strtotime("yesterday"));
			$year = date("Y", strtotime("yesterday"));
        }
        
        if ($startdate != "")
        {
            $datefilename = date("Ymd", strtotime($startdate));     
            $startdate = date("Y-m-d 00:00:00", strtotime($startdate));
			$month = date("F", strtotime($startdate));
			$year = date("Y", strtotime($startdate));
        }
        
        if ($enddate != "")
        {
            $enddate = date("Y-m-d 23:59:59", strtotime($enddate));
        }
        
        if ($enddate == "") {
            $enddate = date("Y-m-d 23:59:59", strtotime("yesterday"));
        }
		
		switch ($month)
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
        
        $sdate = date("Y-m-d H:i:s", strtotime("-7 hour", strtotime($startdate)));
        $edate = date("Y-m-d H:i:s", strtotime("-7 hour", strtotime($enddate)));
        $z =0;
		
		$this->db->order_by("vehicle_device", "asc");
        if ($name != "" && $host != "")
        {
            $dev = $name."@".$host;
            $this->db->where("vehicle_device", $dev);    
        } 
        
		if ($userid != "")
		{
			$this->db->where("user_id",$userid);
		}
       
        $this->db->join("user", "vehicle_user_id = user_id", "left outer");
        
		if ($userid == "")
		{
			$this->db->where("user_company >",0);
		}
		
		$this->db->where("vehicle_user_id", 1933);
		$this->db->where("vehicle_group", 1224); //1224 : mandiri
		$this->db->where("vehicle_status <>", 3);
		$this->db->where("vehicle_type","T5");
        $q = $this->db->get("vehicle");
        $rowvehicle = $q->result();
        
        $total_process = count($rowvehicle);
        printf("TOTAL PROSES VEHICLE : %s \r\n",$total_process);
        printf("============================================ \r\n");
        
		$this->dbtrans = $this->load->database("transporter",true); 
        for($x=0;$x<count($rowvehicle);$x++)
        {
            printf("PROSES VEHICLE : %s (%d/%d) \r\n",$rowvehicle[$x]->vehicle_device, ++$z, $total_process);
            unset($data_insert);
            //PORT Only
            if (isset($rowvehicle[$x]->vehicle_info))
            {
                $json = json_decode($rowvehicle[$x]->vehicle_info);
                if (isset($json->vehicle_ip) && isset($json->vehicle_port))
                {
                    
					$databases = $this->config->item('databases');
					if (isset($databases[$json->vehicle_ip][$json->vehicle_port]))
					{
						$database = $databases[$json->vehicle_ip][$json->vehicle_port];
						$table = $this->config->item("external_gpstable");
						$tableinfo = $this->config->item("external_gpsinfotable");				
						$this->dbhist = $this->load->database($database, TRUE);
						$this->dbhist2 = $this->load->database("gpshistory",true);		
					}
					else
					{
						$table = $this->gpsmodel->getGPSTable($rowvehicle[$x]->vehicle_type);
						$tableinfo = $this->gpsmodel->getGPSInfoTable($rowvehicle[$x]->vehicle_type);
						$this->dbhist = $this->load->database("default", TRUE);
						$this->dbhist2 = $this->load->database("gpshistory",true);		
					}
					
					$vehicle_device = explode("@", $rowvehicle[$x]->vehicle_device);  
                    $vehicle_no = $rowvehicle[$x]->vehicle_no;
					$vehicle_dev = $rowvehicle[$x]->vehicle_device;
					$vehicle_name = $rowvehicle[$x]->vehicle_name;
						
					if ($rowvehicle[$x]->vehicle_type == "T5" || $rowvehicle[$x]->vehicle_type == "T5 PULSE")
                    {
                        $tablehist = $vehicle_device[0]."@t5_gps";
                        $tablehistinfo = $vehicle_device[0]."@t5_info";    
                    }
                    else
                    {
						$tablehist = strtolower($vehicle_device[0])."@".strtolower($vehicle_device[1])."_gps";
						$tablehistinfo = strtolower($vehicle_device[0])."@".strtolower($vehicle_device[1])."_info";
                    }
					
                    //cek apakah sudah pernah ada filenya
                    $this->dbtrans->select("autoreport_vehicle_id");
                    $this->dbtrans->where("autoreport_vehicle_id",$rowvehicle[$x]->vehicle_id);
                    $this->dbtrans->where("autoreport_data_startdate",$startdate);
                    $this->dbtrans->limit(1);
                    $qrpt = $this->dbtrans->get("autoreport_operasional_ssi");
                    $rrpt = $qrpt->row();
                    
                    if (count($rrpt) > 0)
                    {
                        printf("VEHICLE %s SUDAH PERNAH DI PROSES, HAPUS DULU DATA SEBELUMNYA \r\n", $rowvehicle[$x]->vehicle_device);     
                        printf("------------------------------------------------------------- \r\n");
                    }
                    else
                    {

                        $this->dbhist->join($tableinfo, "gps_info_time = gps_time AND gps_info_device = CONCAT(gps_name,'@',gps_host)");    
                        $this->dbhist->where("gps_info_device", $rowvehicle[$x]->vehicle_device);
                        $this->dbhist->where("gps_time >=", $sdate);
                        $this->dbhist->where("gps_time <=", $edate);    
                        $this->dbhist->order_by("gps_time","asc");
                        $this->dbhist->from($table);
                        $q = $this->dbhist->get();
                        $rows1 = $q->result();
                        //print_r($rows);exit;
						
						
						$this->dbhist2->join($tablehistinfo, "gps_info_time = gps_time AND gps_info_device = CONCAT(gps_name,'@',gps_host)");    
                        $this->dbhist2->where("gps_info_device", $rowvehicle[$x]->vehicle_device);
                        $this->dbhist2->where("gps_time >=", $sdate);
                        $this->dbhist2->where("gps_time <=", $edate);    
                        $this->dbhist2->order_by("gps_time","asc");
                        $this->dbhist2->from($tablehist);
                        $q2 = $this->dbhist2->get();
                        $rows2 = $q2->result();
						
						$rows = array_merge($rows1, $rows2);
                
                        $data = array();
                        $nopol = "";
                        $on = false;
                        $trows = count($rows);
        
                        printf("TOTAL DATA : %s \r\n",$trows);
						
						////-------------KONDISI ON-------------////
						if ($trows > 0){
						for($i=0;$i<$trows;$i++)
                        {
                            //if($rows[$i]->gps_speed == 0) continue;
                            if($nopol != $rowvehicle[$x]->vehicle_no)
                            { //new vehicle
                                if($on && $i!=0)
                                { 
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_position'] = $this->getPosition($rows[$i-1]->gps_longitude, $rows[$i-1]->gps_ew, $rows[$i-1]->gps_latitude, $rows[$i-1]->gps_ns);
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_mileage'] = $rows[$i-1]->gps_info_distance;
									$data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['engine'] = substr($rows[$i]->gps_info_io_port, 4, 1);
                                }
                        
                                if(substr($rows[$i]->gps_info_io_port, 4, 1) == 1)
                                {                                
                                    $trip_no = 1;                    
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no++]['start_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_position'] = $this->getPosition($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns);
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_mileage'] = $rows[$i]->gps_info_distance;
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['vehicle_name'] = $rowvehicle[$x]->vehicle_name;
									$data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['engine'] = substr($rows[$i]->gps_info_io_port, 4, 1);
                                    $on = true;
                            
                                    if($i==$trows-1)
                                    {                                                
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_position'] = $this->getPosition($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_mileage'] = $rows[$i]->gps_info_distance;
                                    }
                                }
                                else
                                {
                                    $trip_no = 1;
                                    $on = false;
                                }
                            }
                            else
                            { //same vehicle /edit on
                                if(substr($rows[$i]->gps_info_io_port, 4, 1) == 1)
                                {
									
                                    if(!$on)
                                    {    
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no++]['start_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_position'] = $this->getPosition($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_mileage'] = $rows[$i]->gps_info_distance;
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['vehicle_name'] = $rowvehicle[$x]->vehicle_name;
										$data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['engine'] = substr($rows[$i]->gps_info_io_port, 4, 1);
                                    }
                                    $on = true;    
                                    if($i==$trows-1 && $on)
                                    {                                                
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_position'] = $this->getPosition($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_mileage'] = $rows[$i]->gps_info_distance;
                                    }                                    
                                }
								//edit off
                                else
                                {            
                                    if($on)
                                    {
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));    
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_position'] = $this->getPosition($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_mileage'] = $rows[$i]->gps_info_distance;
                                    }
                                    $on = false;
                                }
                            }
                            $nopol = $rowvehicle[$x]->vehicle_no;
                        }
                        
                        //Write Data On
                        $i=1;
                        $new = "";
                        printf("WRITE DATA ON : ");
                        foreach($data as $vehicle_no=>$val)
                        {
                            if($new != $vehicle_no)
                            {
                                $cumm = 0;
                                $trip_no = 1;
                            }
							
                            foreach($val as $no=>$report)
                            {
                                $mileage = $report['end_mileage']- $report['start_mileage'];
                               // if($mileage != 0) // edit 0 km engine ON
                               // {
                                    $duration = get_time_difference($report['start_time'], $report['end_time']);
									
									$start_1 = dbmaketime($report['start_time']);
									$end_1 = dbmaketime($report['end_time']);
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
                                    $tm = round(($mileage/1000),2);
                                    $cumm += $tm;
                                    printf("|%s|",$i);
									
									$notrip = $trip_no++;
								 
                                    //mileage
                                    $xme = $tm;
                                    $xxme = explode(".",$xme);
                                    if (isset($xxme[1]))
                                    {
                                        $xsub = substr($xxme[1],0,2); 
                                        $x_mile = $xxme[0].".".$xsub;     
                                    }
                                    else
                                    {
                                        $x_mile = $xxme[0];
                                    }
                            
                                    //cummulative
                                    $xcum = $cumm;
                                    $xxcum = explode(".",$xcum);
                                    if (isset($xxcum[1]))
                                    {
                                        $xcumsub = substr($xxcum[1],0,2); 
                                        $x_cum = $xxcum[0].".".$xcumsub;     
                                    }
                                    else
                                    {
                                        $x_cum = $xxcum[0];
                                    }
									
									$geofence_start = $report['start_geofence_location'];
									$geofence_end = $report['end_geofence_location'];
									
									//edit flag engine ON , nol km, lebih dari 15 menit 
								if(isset($report['vehicle_name'])){
									//if ($duration_sec > 900) {
										
										unset($datainsert);

										$datainsert["trip_mileage_vehicle_id"] = $vehicle_dev;
										$datainsert["trip_mileage_vehicle_no"] = $vehicle_no;
										$datainsert["trip_mileage_vehicle_name"] = $report['vehicle_name'];
										$datainsert["trip_mileage_trip_no"] = $notrip;
										$datainsert["trip_mileage_engine"] = $report['engine'];
										$datainsert["trip_mileage_start_time"] = date("Y-m-d H:i:s", strtotime($report['start_time']));
										$datainsert["trip_mileage_end_time"] = date("Y-m-d H:i:s", strtotime($report['end_time']));
										$datainsert["trip_mileage_duration"] = $show;
										$datainsert["trip_mileage_duration_sec"] = $duration_sec;
										$datainsert["trip_mileage_trip_mileage"] = $x_mile;
										$datainsert["trip_mileage_cummulative_mileage"] = $x_cum;
										$datainsert["trip_mileage_location_start"] = $report['start_position']->display_name;
										$datainsert["trip_mileage_location_end"] = $report['end_position']->display_name;
										$datainsert["trip_mileage_geofence_start"] = $geofence_start;
										$datainsert["trip_mileage_geofence_end"] = $geofence_end;
										
										$this->dbtrip = $this->load->database("ssi_operasional",TRUE);
										$this->dbtrip->insert($dbtable,$datainsert);
									/* }else{
										printf("durasi kurang : ".$duration_sec." "." \r\n");
									} */
								}

                                    $i++;
								//}
                            }
                        }
						
						/* printf("DELETE CACHE HISTORY \r\n");
                        $this->dbhist->cache_delete_all();
                        $this->dbtrans->cache_delete_all(); */
                        unset($data);
            
                        printf("FINISH FOR VEHICLE ON : %s \r\n",$rowvehicle[$x]->vehicle_device);
                        printf("============================================ \r\n");
						}
						
						////---------------KONDISI OFF---------------///
						if ($trows > 0){
                        for($i=0;$i<$trows;$i++)
                        {
                            //if($rows[$i]->gps_speed == 0) continue;
                            if($nopol != $rowvehicle[$x]->vehicle_no)
                            { //new vehicle
                                if($on && $i!=0)
                                { 
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_position'] = $this->getPosition($rows[$i-1]->gps_longitude, $rows[$i-1]->gps_ew, $rows[$i-1]->gps_latitude, $rows[$i-1]->gps_ns);
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_mileage'] = $rows[$i-1]->gps_info_distance;
									$data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['engine'] = substr($rows[$i]->gps_info_io_port, 4, 1);
                                }
                        
                                if(substr($rows[$i]->gps_info_io_port, 4, 1) == 0)
                                {                                
                                    $trip_no = 1;                    
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no++]['start_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_position'] = $this->getPosition($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns);
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_mileage'] = $rows[$i]->gps_info_distance;
                                    $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['vehicle_name'] = $rowvehicle[$x]->vehicle_name;
									$data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['engine'] = substr($rows[$i]->gps_info_io_port, 4, 1);
                                    $on = true;
                            
                                    if($i==$trows-1)
                                    {                                                
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_position'] = $this->getPosition($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_mileage'] = $rows[$i]->gps_info_distance;
                                    }
                                }
                                else
                                {
                                    $trip_no = 1;
                                    $on = false;
                                }
                            }
                            else
                            { //same vehicle /edit off
                                if(substr($rows[$i]->gps_info_io_port, 4, 1) == 0)
                                {
									//print_r(substr($rows[$i]->gps_info_io_port, 4, 1));exit();
                                    if(!$on)
                                    {    
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no++]['start_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_position'] = $this->getPosition($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['start_mileage'] = $rows[$i]->gps_info_distance;
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['vehicle_name'] = $rowvehicle[$x]->vehicle_name;
										$data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['engine'] = substr($rows[$i]->gps_info_io_port, 4, 1);
                                    }
                                    $on = true;    
                                    if($i==$trows-1 && $on)
                                    {                                                
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_position'] = $this->getPosition($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_mileage'] = $rows[$i]->gps_info_distance;
                                    }                                    
                                }
								//edit off
                                else
                                {            
                                    if($on)
                                    {
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_time'] = date("d-m-Y H:i:s", strtotime("+7 hour", strtotime($rows[$i]->gps_time)));    
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_position'] = $this->getPosition($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_geofence_location'] = $this->getGeofence_location($rows[$i]->gps_longitude, $rows[$i]->gps_ew, $rows[$i]->gps_latitude, $rows[$i]->gps_ns, $rows[$i]->gps_name);
                                        $data[$rowvehicle[$x]->vehicle_no][$trip_no-1]['end_mileage'] = $rows[$i]->gps_info_distance;
                                    }
                                    $on = false;
                                }
                            }
                            $nopol = $rowvehicle[$x]->vehicle_no;
                        }
                        
                        //Write Data off
                        $i=1;
                        $new = "";
                        printf("WRITE DATA OFF : ");
                        foreach($data as $vehicle_no=>$val)
                        {
                            if($new != $vehicle_no)
                            {
                                $cumm = 0;
                                $trip_no = 1;
                            }
							
                            foreach($val as $no=>$report_off)
                            {
                                $mileage = $report_off['end_mileage']- $report_off['start_mileage'];
                               // if($mileage != 0) // edit 0 km engine off
                               // {
                                    $duration = get_time_difference($report_off['start_time'], $report_off['end_time']);
									
									$start_1 = dbmaketime($report_off['start_time']);
									$end_1 = dbmaketime($report_off['end_time']);
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
                                    $tm = round(($mileage/1000),2);
                                    $cumm += $tm;
                                    printf("|%s|",$i);
									
									$notrip = $trip_no++;
								 
                                    //mileage
                                    $xme = $tm;
                                    $xxme = explode(".",$xme);
                                    if (isset($xxme[1]))
                                    {
                                        $xsub = substr($xxme[1],0,2); 
                                        $x_mile = $xxme[0].".".$xsub;     
                                    }
                                    else
                                    {
                                        $x_mile = $xxme[0];
                                    }
                            
                                    //cummulative
                                    $xcum = $cumm;
                                    $xxcum = explode(".",$xcum);
                                    if (isset($xxcum[1]))
                                    {
                                        $xcumsub = substr($xxcum[1],0,2); 
                                        $x_cum = $xxcum[0].".".$xcumsub;     
                                    }
                                    else
                                    {
                                        $x_cum = $xxcum[0];
                                    }
									$geofence_start_off = $report_off['start_geofence_location'];
									$geofence_end_off = $report_off['end_geofence_location'];

									//edit flag engine OFF , nol km, lebih dari 15 menit
								if (isset($report_off['vehicle_name'])){
									//if ($duration_sec > 900) {
										
										unset($datainsert);
										$datainsert["trip_mileage_vehicle_id"] = $vehicle_dev;
										$datainsert["trip_mileage_vehicle_no"] = $vehicle_no;
										$datainsert["trip_mileage_vehicle_name"] = $report_off['vehicle_name'];
										$datainsert["trip_mileage_trip_no"] = $notrip;
										$datainsert["trip_mileage_engine"] = $report_off['engine'];
										$datainsert["trip_mileage_start_time"] = date("Y-m-d H:i:s", strtotime($report_off['start_time']));
										$datainsert["trip_mileage_end_time"] = date("Y-m-d H:i:s", strtotime($report_off['end_time']));
										$datainsert["trip_mileage_duration"] = $show;
										$datainsert["trip_mileage_duration_sec"] = $duration_sec;
										$datainsert["trip_mileage_trip_mileage"] = $x_mile;
										$datainsert["trip_mileage_cummulative_mileage"] = $x_cum;
										$datainsert["trip_mileage_location_start"] = $report_off['start_position']->display_name;
										$datainsert["trip_mileage_location_end"] = $report_off['end_position']->display_name;
										$datainsert["trip_mileage_geofence_start"] = $geofence_start_off;
										$datainsert["trip_mileage_geofence_end"] = $geofence_end_off;
										
										$this->dbtrip = $this->load->database("ssi_operasional",TRUE);
										$this->dbtrip->insert($dbtable,$datainsert);
									/* }else{
										printf("durasi kurang : ".$duration_sec." "." \r\n");
									}  */
								}

                                    $i++;
                             // }
                            }
                        }
                        printf("FINISH FOR VEHICLE OFF : %s \r\n",$rowvehicle[$x]->vehicle_device);
						printf("============================================ \r\n");
						}
						
                        printf("INSERT TO DATABASE CONFIG AUTOREPORT  \r\n");
                        $data_insert["autoreport_vehicle_id"] = $rowvehicle[$x]->vehicle_id;
                        $data_insert["autoreport_vehicle_device"] = $rowvehicle[$x]->vehicle_device;
                        $data_insert["autoreport_user_id"] = $rowvehicle[$x]->user_id;
                        $data_insert["autoreport_company"] = $rowvehicle[$x]->user_company;
                        $data_insert["autoreport_data_startdate"] = $startdate;
                        $data_insert["autoreport_data_enddate"] = $enddate;
                        $data_insert["autoreport_type"] = $report_type;
                        /* $data_insert["autoreport_file_path"] = $report_path;
                        $data_insert["autoreport_filename"] = $filecreatedname;
                        $data_insert["autoreport_download_path"] = $report_path.$filecreatedname;
                        $data_insert["autoreport_public_path"] = $public_path; */
                        $data_insert["autoreport_process_date"] = $process_date;
                        $data_insert["autoreport_insert_db"] = date("Y-m-d H:i:s");
            
                        $this->dbtrans->insert("autoreport_operasional_ssi",$data_insert);
                        printf("INSERT CONFIG OK \r\n");
            
                        printf("DELETE CACHE HISTORY \r\n");
                        $this->dbhist->cache_delete_all();
                        $this->dbtrans->cache_delete_all();
                        unset($data);
            
                       
                        printf("============================================ \r\n");

                    }
                }
                else
                {
                    printf("SKIP VEHICLE ( NO VEHICLE PORT ) \r\n");
                    printf("-------------------------------------- \r\n");    
                }    
            }
            else
            {
                printf("SKIP VEHICLE ( NO VEHICLE INFO ) \r\n");
                printf("-------------------------------- \r\n");
            }
        }
        
		$finish_time = date("Y-m-d H:i:s");
        printf("AUTOREPORT DATA OPERASIONAL DONE %s\r\n",$finish_time);
		
		//Send Email
		$cron_name = "CRON OPERASIONAL REPORT SSI";
		$this->dbtrip = $this->load->database("ssi_operasional",TRUE);
        $this->dbtrip->select("trip_mileage_id");
        $this->dbtrip->where("trip_mileage_start_time >=",$startdate);
        $this->dbtrip->where("trip_mileage_end_time <=",$enddate);
        $qtrip = $this->dbtrip->get($dbtable);
        $rtrip = $qtrip->result();
		$total_data = count($rtrip);
		
		unset($mail);
		$mail['subject'] =  "Cron : ".$cron_name."";
		$mail['message'] = 
"
Cron Report Status :

Nama Cron  : ".$cron_name." : ".$startdate." to ".$enddate."
Start Cron : ".$start_time."
End Cron   : ".$finish_time."
Total Data : ".$total_data."
Status     : Finish

Terima Kasih

";
		$mail['dest'] = "budiyanto@lacak-mobil.com";
		$mail['bcc'] = "";
		$mail['sender'] = "cron@lacak-mobil.com";
		lacakmobilmail($mail);
			
		printf("Send Email OK");
        
    } 
	   
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
