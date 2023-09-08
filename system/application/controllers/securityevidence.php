<?php
include "base.php";

class Securityevidence extends Base {
	var $period1;
	var $period2;
	var $tblhist;
	var $tblinfohist;
	var $otherdb;

	function Securityevidence()
	{
		parent::Base();
    // DASHBOARD START
    $this->load->helper('common_helper');
		$this->load->helper('email');
		$this->load->library('email');
		$this->load->model("dashboardmodel");
		$this->load->helper('common');
    // DASHBOARD END
		$this->load->model("gpsmodel");
    $this->load->model("m_securityevidence");
		$this->load->model("vehiclemodel");
		$this->load->model("configmodel");
		$this->load->model("historymodel");
	}

	function index(){
		if(! isset($this->sess->user_type)){
			redirect('dashboard');
		}

		// REDIRECT LANGSUNG KE PAGE TMS
		if ($this->sess->user_id == "4098") {
			redirect(base_url()."tms/");
		}

    $this->params['data']          = $this->m_securityevidence->getdevice();
    //$this->params['alarmcategory'] = $this->m_securityevidence->getalarmcategory();
	 $this->params['alarmtype'] = $this->m_securityevidence->getalarmtype();

    $this->params['code_view_menu'] = "report";

    // echo "<pre>";
		// var_dump($this->params['alarmcategory']);die();
		// echo "<pre>";

    $this->params["header"]   = $this->load->view('dashboard/header', $this->params, true);
		$this->params["sidebar"]  = $this->load->view('dashboard/sidebar', $this->params, true);
		$this->params["content"]  = $this->load->view('dashboard/securityevidence/v_securityevidence', $this->params, true);
		$this->load->view("dashboard/template_dashboard_report", $this->params);
	}

	function getalarmsubcat(){
		$subcategoryid                = $this->input->post("id");
		$callback['alarmsubcategory'] = $this->m_securityevidence->getalarmsubcategory($subcategoryid);

		// $this->params['alarmsubcategory'] = $this->m_securityevidence->getalarmsubcategory($subcategoryid);
		// $html                             = $this->load->view('dashboard/securityevidence/v_alarmsubcategory', $this->params, true);
		// $callback['html'] 							 	= $html;

		// echo "<pre>";
		// var_dump($this->params['alarmsubcategory']);die();
		// echo "<pre>";

		echo json_encode($callback);
	}

	function searchreport(){
		$vehicle          = explode("@", $this->input->post("vehicle"));
		$startdate        = $this->input->post("startdate");
		$shour            = $this->input->post("shour");
		$startdatefix     = date("Y-m-d H:i:s", strtotime($startdate." ".$shour.":00"));
		$enddate          = $this->input->post("enddate");
		$ehour            = $this->input->post("ehour");
		$enddatefix       = date("Y-m-d H:i:s", strtotime($enddate." ".$ehour.":00"));
		$alarmtype        = $this->input->post("alarmtype");
		$alarmfix 			  = $this->input->post("alarmfix");
		$alarmtypeexplode = explode(",", $alarmfix);
		$loopalarmtype    = "";
		$where            = array();
		$pratext          = "alarm_";
		$month            = date("F");
		$year             = date("Y");
		$table            = strtolower($pratext.$month.'_'.$year);

		// $vehicle.'-'.$startdate.'-'.$shour.'-'.$enddate.'-'.$ehour.'-'.$alarmtype

		if ($alarmtype != "All") {
			$thisreport = $this->m_securityevidence->searchthisreport($table, $vehicle[0], $startdatefix, $enddatefix, $alarmtypeexplode);
		}else {
			$thisreport = $this->m_securityevidence->searchthisreport($table, $vehicle[0], $startdatefix, $enddatefix, "ALL");
		}

		$this->params['content'] = $thisreport;
		$html                    = $this->load->view('dashboard/securityevidence/v_securityevidence_reportresult', $this->params, true);
		$callback["html"]        = $html;
		$callback["report"]      = $thisreport;

		// echo "<pre>";
		// var_dump($getdata);die();
		// echo "<pre>";
		echo json_encode($callback);
	}

	function getinfodetail(){
		$alert_id   = $this->input->post("alert_id");
		$sdate      = $this->input->post("sdate");
		$pratext    = "alarm_";
		$month      = date("F");
		$year       = date("Y");
		$table      = strtolower($pratext.$month.'_'.$year);

		$reportdetail       = $this->m_securityevidence->getdetailreport($table, $alert_id, $sdate);
		$reportdetailvideo  = $this->m_securityevidence->getdetailreportvideo($table, $alert_id, $sdate);
		$reportdetaildecode = explode("|", $reportdetail[0]['alarm_report_gpsstatus']);

		// echo "<pre>";
		// var_dump($this->params['urlvideo'] );die();
		// echo "<pre>";

		$this->params['content']  = $reportdetail;
		$this->params['urlvideo'] = $reportdetailvideo[0]['alarm_report_downloadurl'];
		$this->params['speed']    = $reportdetaildecode[7];
		$html                     = $this->load->view('dashboard/securityevidence/v_securityevidence_informationdetail', $this->params, true);
		$callback["html"]         = $html;
		$callback["report"]       = $reportdetail;


		echo json_encode($callback);
	}

	function getsessionlogin(){
		$device          = $_POST['device'];
		$url             = $_POST['url'];
		$username        = "IND.LacakMobil";
		$password        = "000000";

		$getthissession  = $this->m_securityevidence->getsession();
		$urlfix          = $url.$getthissession[0]['sess_value'];

		// GET LOGIN DENGAN SESSION LAMA
		$loginlama       = file_get_contents("http://47.91.108.9:8080/StandardApiAction_queryUserVehicle.action?jsession=".$getthissession[0]['sess_value']);
			if ($loginlama) {
				$loginlamadecode = json_decode($loginlama);
				if ($loginlamadecode->message == "Session does not exist!") {
					$loginbaru       = file_get_contents("http://47.91.108.9:8080/StandardApiAction_login.action?account=".$username."&password=".$password);
					$loginbarudecode = json_decode($loginbaru);
					$urlfix          = $url.$loginbarudecode->jsession;
				}
			}

			// echo "<pre>";
			// var_dump($urlfix);die();
			// echo "<pre>";

		$this->params['content'] = file_get_contents($urlfix);
		$this->params['urlfix']  = $urlfix;
		$html                    = $this->load->view('dashboard/livestream/v_livestream', $this->params, true);
		// echo "<pre>";
		// var_dump($this->params['content']);die();
		// echo "<pre>";
		echo json_encode(array("content" => $html, "urlfix" => $urlfix));
	}

	function realtimealert(){
		$device         = $_POST['device'];
		$username       = "IND.LacakMobil";
		$password       = "000000";

		$getthissession = $this->m_securityevidence->getsession();
		$sessionold     = $getthissession[0]['sess_value'];

		// GET ALERT DENGAN SESSION LAMA
		$getalert       = file_get_contents("http://47.91.108.9:8080/StandardApiAction_vehicleAlarm.action?jsession=".$sessionold."&DevIDNO=".$device."&toMap=1");
			if ($getalert) {
				$getalertdecode = json_decode($getalert);
				if (!$getalertdecode) {
					if ($getalertdecode->message == "Session does not exist!") {
						$loginbaru       = file_get_contents("http://47.91.108.9:8080/StandardApiAction_login.action?account=".$username."&password=".$password);
						$loginbarudecode = json_decode($loginbaru);
						$urlfix          = "http://47.91.108.9:8080/StandardApiAction_vehicleAlarm.action?jsession=".$loginbarudecode->jsession."&DevIDNO=".$device."&toMap=1";
						$alert           = file_get_contents($urlfix);
						$alertfix 			 = json_decode($alert);
					}
				}else {
						$urlfix          = "http://47.91.108.9:8080/StandardApiAction_vehicleAlarm.action?jsession=".$sessionold."&DevIDNO=".$device."&toMap=1";
						$alert           = file_get_contents($urlfix);
						$alertfix 			 = json_decode($alert);
					}
				}

				if (sizeof($alertfix->alarmlist) != 0) {
					$alertfixdecode = $alertfix;
					// echo "<pre>";
					// var_dump($alertfixdecode);die();
					// echo "<pre>";
					$valuealarm = "1";
				}else {
					$alertfix = '{"alarmlist":[{"DevIDNO":"020200360002","Gps":{"dct":0,"gt":"2020-09-17 10:38:06","hx":0,"lat":-6285461,"lc":0,"lid":32,"lng":106968451,"mlat":"-6.285461","mlng":"106.968451","net":0,"pk":1025,"s1":1,"s2":0,"s3":0,"s4":0,"sfg":0,"snm":0,"sp":50,"sst":0,"t1":0,"t2":0,"t3":0,"t4":4,"tsp":0,"yl":0},"desc":"SBAC=30333630303032200917103806000400","guid":"00020200360002200917103806000400","hd":0,"img":"","info":0,"p1":0,"p2":0,"p3":0,"p4":0,"rve":0,"srcAt":0,"srcTm":"2020-09-17 10:38:06","stType":252,"time":"2020-09-17 10:38:06","type":631}],"cmsserver":1,"more":0,"result":0}';
					$alertfixdecode = json_decode($alertfix);

					// echo "<pre>";
					// var_dump(sizeof($alertfixdecode));die();
					// echo "<pre>";
					$valuealarm = "0";
				}

				// echo "<pre>";
				// var_dump($valuealarm);die();
				// echo "<pre>";

		$arrayalert    = array();
		$valuealarmfix = $valuealarm; //$valuealarm -> dipakai kalo live // isi 1 saat develop

		if ($valuealarmfix > 0 ) { // isi dengan > 0 kalau sudah live < 1 untuk develop
			// DATA DARI API
			$datafix = array(
				"0"   => array("DevIDNO" => $alertfixdecode->alarmlist[0]->DevIDNO),
				"Gps" => array(
								"dct"  => $alertfixdecode->alarmlist[0]->Gps->dct,
								"gt"   => $alertfixdecode->alarmlist[0]->Gps->gt,
								"hx"   => $alertfixdecode->alarmlist[0]->Gps->hx,
								"lat"  => $alertfixdecode->alarmlist[0]->Gps->lat,
								"lc"   => $alertfixdecode->alarmlist[0]->Gps->lc,
								"lid"  => $alertfixdecode->alarmlist[0]->Gps->lid,
								"lng"  => $alertfixdecode->alarmlist[0]->Gps->lng,
								"mlat" => $alertfixdecode->alarmlist[0]->Gps->mlat,
								"mlng" => $alertfixdecode->alarmlist[0]->Gps->mlng,
								"net"  => $alertfixdecode->alarmlist[0]->Gps->net,
								"pk"   => $alertfixdecode->alarmlist[0]->Gps->pk,
								"s1"   => $alertfixdecode->alarmlist[0]->Gps->s1,
								"s2"   => $alertfixdecode->alarmlist[0]->Gps->s2,
								"s3"   => $alertfixdecode->alarmlist[0]->Gps->s3,
								"s4"   => $alertfixdecode->alarmlist[0]->Gps->s4,
								"sfg"  => $alertfixdecode->alarmlist[0]->Gps->sfg,
								"snm"  => $alertfixdecode->alarmlist[0]->Gps->snm,
								"sp"   => $alertfixdecode->alarmlist[0]->Gps->sp,
								"sst"  => $alertfixdecode->alarmlist[0]->Gps->sst,
								"t1"   => $alertfixdecode->alarmlist[0]->Gps->t1,
								"t2"   => $alertfixdecode->alarmlist[0]->Gps->t2,
								"t3"   => $alertfixdecode->alarmlist[0]->Gps->t3,
								"t4"   => $alertfixdecode->alarmlist[0]->Gps->t4,
								"tsp"  => $alertfixdecode->alarmlist[0]->Gps->tsp,
								"yl"   => $alertfixdecode->alarmlist[0]->Gps->yl
							),
				"desc"   => $alertfixdecode->alarmlist[0]->desc,
				"guid"   => $alertfixdecode->alarmlist[0]->guid,
				"hd"     => $alertfixdecode->alarmlist[0]->hd,
				"img"    => $alertfixdecode->alarmlist[0]->img,
				"info"   => $alertfixdecode->alarmlist[0]->info,
				"p1"     => $alertfixdecode->alarmlist[0]->p1,
				"p2"     => $alertfixdecode->alarmlist[0]->p2,
				"p3"     => $alertfixdecode->alarmlist[0]->p3,
				"p4"     => $alertfixdecode->alarmlist[0]->p4,
				"rve"    => $alertfixdecode->alarmlist[0]->rve,
				"srcAt"  => $alertfixdecode->alarmlist[0]->srcAt,
				"srcTm"  => $alertfixdecode->alarmlist[0]->srcTm,
				"stType" => $alertfixdecode->alarmlist[0]->stType,
				"time"   => $alertfixdecode->alarmlist[0]->time,
				"type"   => $alertfixdecode->alarmlist[0]->type
			);

			// PARSING DATA UNTUK VIEW
			$alertfixbgt = array();
			for ($j=0; $j < sizeof($datafix[0]); $j++) {
				$getdetailalert = $this->m_securityevidence->detailalert(array($datafix['stType'], $datafix['type']));
				if ($datafix['stType'] != "0") {
					array_push($alertfixbgt, array(
						"0"   => array(
							"device" => $datafix[0]['DevIDNO']
						),
						"Gps" => array(
										"dct"  => $datafix['Gps']['dct'],
										"gt"   => $datafix['Gps']['gt'],
										"hx"   => $datafix['Gps']['hx'],
										"lat"  => $datafix['Gps']['lat'],
										"lc"   => $datafix['Gps']['lc'],
										"lid"  => $datafix['Gps']['lid'],
										"lng"  => $datafix['Gps']['lng'],
										"mlat" => $datafix['Gps']['mlat'],
										"mlng" => $datafix['Gps']['mlng'],
										"net"  => $datafix['Gps']['net'],
										"pk"   => $datafix['Gps']['pk'],
										"s1"   => $datafix['Gps']['s1'],
										"s2"   => $datafix['Gps']['s2'],
										"s3"   => $datafix['Gps']['s3'],
										"s4"   => $datafix['Gps']['s4'],
										"sfg"  => $datafix['Gps']['sfg'],
										"snm"  => $datafix['Gps']['snm'],
										"sp"   => $datafix['Gps']['sp'],
										"sst"  => $datafix['Gps']['sst'],
										"t1"   => $datafix['Gps']['t1'],
										"t2"   => $datafix['Gps']['t2'],
										"t3"   => $datafix['Gps']['t3'],
										"t4"   => $datafix['Gps']['t4'],
										"tsp"  => $datafix['Gps']['tsp'],
										"yl"   => $datafix['Gps']['yl']
									),
						"desc"          => $datafix['desc'],
						"guid"          => $datafix['guid'],
						"hd"            => $datafix['hd'],
						"img"           => $datafix['img'],
						"info"          => $datafix['info'],
						"p1"            => $datafix['p1'],
						"p2"            => $datafix['p2'],
						"p3"            => $datafix['p3'],
						"p4"            => $datafix['p4'],
						"rve"           => $datafix['rve'],
						"srcAt"         => $datafix['srcAt'],
						"srcTm"         => $datafix['srcTm'],
						"stType_before" => $datafix['stType'],
						"stType"        => $getdetailalert[0]['alarm_name'],
						"time"          => $datafix['time'],
						"type_before"   => $datafix['type'],
						"type"          => $getdetailalert[1]['alarm_name']
					));
					$stTypeis = "yes";
				}else {
					array_push($alertfixbgt, array(
						"0"   => array(
							"device" => $datafix[0]['DevIDNO']
						),
						"Gps" => array(
										"dct"  => $datafix['Gps']['dct'],
										"gt"   => $datafix['Gps']['gt'],
										"hx"   => $datafix['Gps']['hx'],
										"lat"  => $datafix['Gps']['lat'],
										"lc"   => $datafix['Gps']['lc'],
										"lid"  => $datafix['Gps']['lid'],
										"lng"  => $datafix['Gps']['lng'],
										"mlat" => $datafix['Gps']['mlat'],
										"mlng" => $datafix['Gps']['mlng'],
										"net"  => $datafix['Gps']['net'],
										"pk"   => $datafix['Gps']['pk'],
										"s1"   => $datafix['Gps']['s1'],
										"s2"   => $datafix['Gps']['s2'],
										"s3"   => $datafix['Gps']['s3'],
										"s4"   => $datafix['Gps']['s4'],
										"sfg"  => $datafix['Gps']['sfg'],
										"snm"  => $datafix['Gps']['snm'],
										"sp"   => $datafix['Gps']['sp'],
										"sst"  => $datafix['Gps']['sst'],
										"t1"   => $datafix['Gps']['t1'],
										"t2"   => $datafix['Gps']['t2'],
										"t3"   => $datafix['Gps']['t3'],
										"t4"   => $datafix['Gps']['t4'],
										"tsp"  => $datafix['Gps']['tsp'],
										"yl"   => $datafix['Gps']['yl']
									),
						"desc"   => $datafix['desc'],
						"guid"   => $datafix['guid'],
						"hd"     => $datafix['hd'],
						"img"    => $datafix['img'],
						"info"   => $datafix['info'],
						"p1"     => $datafix['p1'],
						"p2"     => $datafix['p2'],
						"p3"     => $datafix['p3'],
						"p4"     => $datafix['p4'],
						"rve"    => $datafix['rve'],
						"srcAt"  => $datafix['srcAt'],
						"srcTm"  => $datafix['srcTm'],
						"stType_before" => $datafix['stType'],
						"stType" => "0",
						"time"   => $datafix['time'],
						"type_before"   => $datafix['type'],
						"type"   => $getdetailalert[0]['alarm_name']
					));
					$stTypeis = "no";
				}
			}
			$callback['alertdata'] = $alertfixbgt;
			$callback['sizedata']  = sizeof($alertfixbgt);
			$callback['stTypeis']  = $stTypeis;
		}else {
			$callback['alertdata'] = "empty";
			$callback['sizedata']  = "0";
			$callback['stTypeis']  = "0";
		}

			// echo "<pre>";
			// var_dump($callback['alertdata']);die();
			// echo "<pre>";

		// $this->params['content'] = $arrayalert; //$alertfix -> DIPAKAI KALAU REALTIME ALERT SUDAH JALAN
		// $html                    = $this->load->view('dashboard/livestream/v_realtimealert', $this->params, true);
		// $callback['html']        = $html;

		// echo "<pre>";
		// var_dump($this->params['content']);die();
		// echo "<pre>";
		echo json_encode($callback);
	}

  function livestream(){
		if(! isset($this->sess->user_type)){
			redirect('dashboard');
		}

    $this->params['code_view_menu'] = "report";

    // echo "<pre>";
		// var_dump($this->params['alarmtype']);die();
		// echo "<pre>";

    $this->params["header"]   = $this->load->view('dashboard/header', $this->params, true);
		$this->params["sidebar"]  = $this->load->view('dashboard/sidebar', $this->params, true);
		$this->params["content"]  = $this->load->view('dashboard/livestream/v_livestream', $this->params, true);
		$this->load->view("dashboard/template_dashboard_kalimantan", $this->params);
	}

	function apilogin(){
		$url     = $_POST['url'];
		$content = file_get_contents($url);
		// echo "<pre>";
		// var_dump($content);die();
		// echo "<pre>";
		echo json_encode($content);
	}

	function apigetvehicledata(){
		$url     = $_POST['url'];
		$content = file_get_contents($url);
		// echo "<pre>";
		// var_dump($content);die();
		// echo "<pre>";
		echo json_encode($content);
	}

	function vehiclelive(){
		$url                     = $_POST['url'];
		$this->params['content'] = file_get_contents($url);
		$html                    = $this->load->view('dashboard/livestream/v_vehiclelive', $this->params, true);
		$callback["html"]        = $html;
		// echo "<pre>";
		// var_dump($html);die();
		// echo "<pre>";
		echo json_encode($callback);
	}

}
