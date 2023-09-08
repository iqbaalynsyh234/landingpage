<?php
include "base.php";

class Sdp_cronjob extends Base {

	function Sdp_cronjob()
	{
		parent::Base();
		$this->load->model("gpsmodel");
    $this->load->model("m_integrationmodul");
    $this->load->library('ftp');
	}


  function integrationtocustomer(){
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

      $devices = explode("@", $vehicle[$i]['vehicle_device']);
      $gps        = $this->gpsmodel->GetLastInfo($devices[0], $devices[1], true, false, 0, $vehicle[$i]['vehicle_type']);
      array_push($datafix, array(
        "processing_date" => date("Y-m-d H:i:s"),
        "vehicle_id"      => $vehicle[$i]['vehicle_no'].' - '.$vehicle[$i]['vehicle_name'],
        "longitude"       => $gps->gps_longitude_real_fmt,
        "latitude"        => $gps->gps_latitude_real_fmt,
        "address"         => $gps->georeverse->display_name
      ));
    }

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
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        @mkdir($report_path, DIR_WRITE_MODE);
        $filedate = date("YmdHi");
        $filecreatedname = "suryadharma"."_".$filedate.".csv";
        $objWriter->save($report_path.$filecreatedname);
        printf("==== CREATE FILE DONE : %s \r\n",$filecreatedname);

        printf("===== FTP CONFIG ===== \r\n");
        printf("===== DIR FOR UPLOAD ====="."suryadharma"."/".$filecreatedname." \r\n");

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
		    $ftp_hostname = 'kalamaya.nutrifood.co.id'; // change this
		    $ftp_username = 'suryadharma'; // change this
		    $ftp_password = 'suryadharma'; // change this
		    $remote_dir   = 'suryadharma/'; // change this
		    $src_file     = $report_path.$filecreatedname;

        // remote file path
        $dst_file = $remote_dir . $src_file;

        // connect ftp
        $ftpcon = ftp_connect($ftp_hostname) or die('Error connecting to ftp server...');

        // ftp login
        $ftplogin = ftp_login($ftpcon, $ftp_username, $ftp_password);

        // ftp upload
        if (ftp_put($ftpcon, $filecreatedname, $src_file, FTP_ASCII)){
						printf("===== File uploaded successfully to FTP server! ==== \r\n");

        }else{
						printf("===== Error uploading file! Please try again later. ==== \r\n");

				}

        // close ftp stream
        ftp_close($ftpcon);

        printf("===== FINISH UPLOADING FILE ==== \r\n");
    }else {
      printf("===== TIDAK ADA MOBIL DALAM PENGIRIMAN KE NUTRIFOOD ==== \r\n");
    }
		printf("===== STARTING CRON ".$date." ===== \r\n");
		printf("===== END CRON ".date("d-m-Y H:i:s")." ===== \r\n");
  }

}
