<?php
include "base.php";

class INTEGRATION extends Base {

	function INTEGRATION()
	{
		parent::Base();
		$this->load->model("gpsmodel");
    $this->load->model("m_integrationmodul");

		if (! isset($this->sess->user_type))
		{
			redirect(base_url());
		}

		if (! $this->sess->user_type)
		{
			redirect(base_url());
		}
	}


  function index(){
    $datavehicle  = $this->m_integrationmodul->allvehicle();
    $datacustomer = $this->m_integrationmodul->allcustomer();
    $total        = sizeof($datavehicle);
    // echo "<pre>";
    // var_dump($datavehicle);die();
    // echo "<pre>";
    $config['uri_segment'] = 5;
    $config['base_url']    = base_url()."integration";
    $config['total_rows']  = $total;
    $config['per_page']    = $this->config->item("limit_records");

    $this->pagination->initialize($config);

    $this->params['title']        = "Integration Modul";
    $this->params["paging"]       = $this->pagination->create_links();
    $this->params["offset"]       = 0;
    $this->params["total"]        = $total;
    $this->params["datavehicle"]  = $datavehicle;
    $this->params["datacustomer"] = $datacustomer;
    // $this->params["contentpoi"]   = $this->load->view('poi/tblpoi', $this->params, true);
    $this->params["content"]      = $this->load->view('integrationmodul/v_home_integration', $this->params, true);
    $this->load->view("templatesess", $this->params);
  }

	function submitintegration(){
		$integration = $_POST['integration'];
		$vals        = explode(",", $_POST['vals']);
		$totaldata   = sizeof($vals);

			if ($integration == "") {
				echo json_encode(array("msg" => "Silahkan pilih customer terlebih dahulu"));
			}

			if ($vals[0] != "") {
				for ($i=0; $i < sizeof($vals); $i++) {
					$vehicleid       = explode("|", $vals[$i]);
					$vehicleidfix    = $vehicleid[0];
					$vehiclegroupfix = $vehicleid[1];

					if ($vehiclegroupfix == 0) {
						$data = array(
							"vehicle_group" => $integration
						);
					}else {
						$data = array(
							"vehicle_group" => $vehiclegroupfix
						);
					}
					$updatevehicle = $this->m_integrationmodul->updatevehiclegroup($vehicleidfix, $data);
				}
				// echo "<pre>";
				// var_dump($vehicleidfix);die();
				// echo "<pre>";
			}else {
				echo json_encode(array("code" => "400", "msg" => "Silahkan checklist kendaraan yang sedang dalam perjalanan terlebih dahulu"));
			}
			echo json_encode(array("code" => "200", "msg" => "Data kendaraan yang sedang pengiriman berhasil di integrasikan."));
	}

	function releaseintegration(){
		$releasevehicle = explode(",", $_POST['releasevehicle']);
		$totaldata      = sizeof($releasevehicle);

		// echo "<pre>";
		// var_dump($releasevehicle);die();
		// echo "<pre>";

			for ($i=0; $i < sizeof($releasevehicle); $i++) {
				$vehicleid       = explode("|", $releasevehicle[$i]);
				$vehicleidfix    = $vehicleid[0];

				$data = array(
					"vehicle_group" => 0
				);

				$updatevehicle = $this->m_integrationmodul->updatevehiclegroup($vehicleidfix, $data);
			}
			// echo "<pre>";
			// var_dump($vehicleidfix);die();
			// echo "<pre>";
		echo json_encode(array("code" => "200", "msg" => "Data integrasi berhasil di set ke available"));
	}

	function testingexcel(){
		$date = date("d-m-Y H:i:s");
		$customeridforftp = array("2135");
		//GET DATA FROM DB
		$this->db         = $this->load->database("default", true);
		$this->db->select("*");
		$this->db->order_by("vehicle_name","asc");
		$this->db->where("vehicle_user_id", 4174);
		$this->db->where_in("vehicle_group", $customeridforftp);
		$this->db->where("vehicle_status <>", 3);
		$vehicle          = $this->db->get("vehicle")->result_array();

		$datafix = array();
		for ($i=0; $i < sizeof($vehicle); $i++) {
			// printf("===== GET DATA ".($i+1)." ".date("d-m-Y H:i:s")." ===== \r\n");

			$devices     = explode("@", $vehicle[$i]['vehicle_device']);
			$gps         = $this->gpsmodel->GetLastInfo($devices[0], $devices[1], true, false, 0, $vehicle[$i]['vehicle_type']);
			$vehiclefix  = explode("-", $vehicle[$i]['vehicle_no']);
			$vehiclefix2 = explode(" ", $vehiclefix[0]);
			array_push($datafix, array(
				"processing_date" => date("Y-m-d H:i:s"),
				"vehicle_id"      => str_replace(" ", "", $vehiclefix2[0]),
				"longitude"       => $gps->gps_longitude_real_fmt,
				"latitude"        => $gps->gps_latitude_real_fmt,
				"address"         => $gps->georeverse->display_name
			));
		}

		// echo "<pre>";
		// var_dump($datafix);die();
		// echo "<pre>";

		if (sizeof($datafix) > 0) {
			/** PHPExcel */
			include 'class/PHPExcel.php';

			/** PHPExcel_Writer_Excel2007 */
			include 'class/PHPExcel/Writer/Excel2007.php';

			// Create new PHPExcel object
			$objPHPExcel   = new PHPExcel();

			$domain_server = "103.253.107.157";
			$report_path   = "/home/lacakmobil/public_html/transporter.lacak-mobil.com/public/assets/media/sdpreport/"; //web server
			$pub_path      = "assets/media/sdpreport/";

			// Set properties
			$objPHPExcel->getProperties()->setCreator("Suryadharma");
			$objPHPExcel->getProperties()->setLastModifiedBy("Suryadharma");
			$objPHPExcel->getProperties()->setTitle("Last Position");
			$objPHPExcel->getProperties()->setSubject("Last Position");
			$objPHPExcel->getProperties()->setDescription("Last Position");

			//set document
			$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(1);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.50);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1);
			$objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman');
			$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);

			//Top Header
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'NO');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'PROCESSING DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'VEHICLE ID');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'LONGITUDE');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'LATITUDE');

			$i = 1;
			for ($j=0;$j<count($datafix);$j++){
				// printf("===== ROW ".($j+1)." ".date("d-m-Y H:i:s")." ===== \r\n");
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.(1+$i), $i);
				$objPHPExcel->getActiveSheet()->getStyle('A'.(1+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objPHPExcel->getActiveSheet()->SetCellValue('B'.(1+$i), $datafix[$j]['processing_date']);
				$objPHPExcel->getActiveSheet()->getStyle('B'.(1+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

				$objPHPExcel->getActiveSheet()->SetCellValue('C'.(1+$i), $datafix[$j]['vehicle_id']);
				$objPHPExcel->getActiveSheet()->getStyle('C'.(1+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

				$objPHPExcel->getActiveSheet()->SetCellValue('D'.(1+$i), $datafix[$j]['longitude']);
				$objPHPExcel->getActiveSheet()->getStyle('D'.(1+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

				$objPHPExcel->getActiveSheet()->SetCellValue('E'.(1+$i), $datafix[$j]['latitude']);
				$objPHPExcel->getActiveSheet()->getStyle('E'.(1+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

				$i++;
			}

			$styleArray = array(
						'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
						)
					);

				// $objPHPExcel->getActiveSheet()->getStyle('A1:F'.(0+$i))->applyFromArray($styleArray);
				// $objPHPExcel->getActiveSheet()->getStyle('A1:F'.(0+$i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				// $objPHPExcel->getActiveSheet()->getStyle('A1:F'.(0+$i))->getAlignment()->setWrapText(true);

				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Last Position');
				printf("===== CREATE FILE ===== \r\n");

				// Save Excel
				// $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				// $objWriter->setDelimiter(',');
				// $objWriter->setEnclosure('');
				// $objWriter->setLineEnding("\r\n");
				// $objWriter->setSheetIndex(0);
				@mkdir($report_path, DIR_WRITE_MODE);
				$filedate        = date("YmdHi");
				$filecreatedname = "suryadharmatest"."_".$filedate.".xlsx";
				$objWriter->save($report_path.$filecreatedname);
				printf("==== CREATE FILE DONE : %s \r\n",$filecreatedname);

				// printf("===== FTP CONFIG ===== \r\n");
				// printf("===== DIR FOR UPLOAD ====="."suryadharma"."/".$filecreatedname." \r\n");

				// $config['hostname'] = 'kalamaya.nutrifood.co.id';
				// $config['username'] = 'suryadharma';
				// $config['password'] = 'suryadharma';
				// $config['port']     = '22';
				// $config['debug'] 	= FALSE;
				// $this->ftp->connect($config);
				// printf("===== FTP CONNECTED ===== \r\n");
				// $this->ftp->upload("ftp://kalamaya.nutrifood.co.id/suryadharma/", $filecreatedname);
				// $this->ftp->close();

				// ftp settings
				// $ftp_hostname = 'kalamaya.nutrifood.co.id'; // change this
				// $ftp_username = 'suryadharma'; // change this
				// $ftp_password = 'suryadharma'; // change this
				// $remote_dir   = 'suryadharma/'; // change this
				// $src_file     = $report_path.$filecreatedname;
				// $src_file2    = $report_path.$filecreatedname;

				// remote file path
				// $dst_file = $remote_dir . $src_file;

				// connect ftp
				// $ftpcon = ftp_connect($ftp_hostname) or die('Error connecting to ftp server...');

				// ftp login
				// $ftplogin = ftp_login($ftpcon, $ftp_username, $ftp_password);

				// ftp upload
				// if (ftp_put($ftpcon, $filecreatedname, $src_file, FTP_ASCII)){
				// 		printf("===== File uploaded successfully to FTP server! ==== \r\n");
				//
				// }else{
				// 		printf("===== Error uploading file! Please try again later. ==== \r\n");
				//
				// }

				// close ftp stream
				// ftp_close($ftpcon);
				//
				// printf("===== FINISH UPLOADING FILE ==== \r\n");
		}else {
			printf("===== TIDAK ADA MOBIL DALAM PENGIRIMAN KE NUTRIFOOD ==== \r\n");
		}
		// printf("===== STARTING CRON ".$date." ===== \r\n");
		// printf("===== END CRON ".date("d-m-Y H:i:s")." ===== \r\n");
	}

	function testvolttoltr(){
		$fullcap             = 50; // liter
		$fullpercent         = 100; // percentage
		$fullvolt		         = 5;
		// $voltasepersatuliter = 0.1;

		$currentvolt         = 2.5;

		$percenvoltase   = $currentvolt * ($fullpercent / $fullvolt); // persentase yg didapat dari perubahan voltase;
		$sisaliterbensin = ($percenvoltase * $fullcap) / $fullpercent;

		$parameter = "<br><br>Full Capacity : ".$fullcap.'<br>'.'Percent Full : '.$fullpercent.'<br>'.
								 'Full Voltage : '.$fullvolt.'<br>'.'Current Volt : '.$currentvolt.'<br><br>';
		$result = "<br>Persentase : ". $percenvoltase.'%<br>'."Bensin : ".$sisaliterbensin." Ltr";
		echo "<pre>";
		var_dump($parameter.$result);die();
		echo "<pre>";
	}


}
