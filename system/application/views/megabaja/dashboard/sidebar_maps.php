<?php
$getvehicle_byowneringofence = $this->dashboardmodel->getvehicle_byowneringeofence();
$totalvehicleingeofence      = sizeof($getvehicle_byowneringofence);
$getvehicle_byowner          = $this->dashboardmodel->getvehicle_byowner();
$totalmobilnya               = sizeof($getvehicle_byowner);
// $totalmobilnya      = 0;
  if ($totalmobilnya == 0) {
    $name         = "0";
    $host         = "0";
  }else {
    $arr          = explode("@", $getvehicle_byowner[0]->vehicle_device);
    $name         = $arr[0];
    $host         = $arr[1];
  }

  if ($totalvehicleingeofence == 0) {
    $namegeofence = "0";
    $hostgeofence = "0";
  }elseif ($totalvehicleingeofence > 1) {
    $arrgeofence  = explode("@", $getvehicle_byowneringofence[1]->geofence_vehicle);
    $namegeofence = $arrgeofence[0];
    $hostgeofence = $arrgeofence[1];
  }else {
    $arrgeofence  = explode("@", $getvehicle_byowneringofence[0]->geofence_vehicle);
    $namegeofence = $arrgeofence[0];
    $hostgeofence = $arrgeofence[1];
  }
 ?>
<style>
  #newex3 {
    color: white;
    height: 554px;
    overflow-x: hidden;
    overflow-x: auto;
    text-align: justify;
  }

  div#modalfivereport {
    margin-top: 5%;
    margin-left: 30%;
    max-height: 500px;
    max-width: 900px;
    overflow-x: auto;
    position: absolute;
    z-index: 9;
    background-color: #f1f1f1;
    text-align: left;
    border: 1px solid #d3d3d3;
  }
</style>

<div class="sidemenu-container navbar-collapse collapse fixed-menu" style="border:0px solid black; margin-left:0px;">
  <!-- mn monitoring -->

  <div id="newex3">
    <div id="remove-scroll">
      <?php if (isset($code_view_menu)) {?>
        <?php if ($code_view_menu == "monitor") {?>
          <div id="sidebar_monitoring">
            <ul class="sidemenu page-header-fixed p-t-20" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
              <li class="sidebar-user-panel">
                <div class="profile-usertitle-job">
                  Total(
                  <a href="<?=base_url();?>maps" style="color:yellow;">
                    <?=$total_vehicle;?>
                  </a>) | Online(
                  <a href="<?=base_url();?>maps/online" style="color:yellow;">
                    <?=$total_online;?>
                  </a>) | Offline(
                  <a href="<?=base_url();?>maps/offline" style="color:yellow;">
                    <?=$total_offline;?>
                  </a>)
                </div>
              </li>

              <li class="nav-item">
                <input type="text" name="searchnopol" id="searchnopol" placeholder="input vehicle no" size="10px;" style="margin-left: 1%;">
                <button type="button" class="btn btn-success btn-sm" id="btnSearchNopol" onclick="forsearchinput()">
                  <span class="fa fa-search"></span>
                </button>
                <?php
              $userid = $this->sess->user_id;
               if ($userid == 3212 || $userid == 1445) {?>
                  <a href="<?=base_url();?>dashboard" class="nav-link nav-toggle">
                    <i class="material-icons">dashboard</i>
                    <span class="title">Dashboard</span>
                  </a>
              </li>
              <?php } ?>

                <li>
                  <a href="<?php echo base_url()?>maps/outofgeofence" class="nav-link nav-toggle">
                    <i class=""></i><small>Out Of Geofence</small>
                  </a>
                </li>

                <!-- MODUL TMS -->
                <?php $user_level = $this->sess->user_level; ?>
                    <li class="nav-item">
                       <!-- style="display:none;" -->
                        <a href="#" class="nav-link nav-toggle">
                          <span class="label label-rouded label-menu label-danger">new</span>
                          <i class=""></i><small>TMS Modul</small>
                          <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                          <li class="nav-item">
                            <a href="<?=base_url();?>joborder" class="nav-link ">
                              <span class="title"><small>Job Order</small></span>
                            </a>
                          </li>

                          <li class="nav-item">
                            <!--<a href="<?=base_url();?>transporter/customer" class="nav-link ">-->
                            <a href="<?=base_url();?>customer" class="nav-link ">
                              <span class="title"><small>Customer (Droppoint)</small></span>
                            </a>
                          </li>
                        </ul>
                    </li>
                  <!-- MODUL TMS -->

                <?php if ($userid == 4122) {?>
                  <li>
                    <a href="<?php echo base_url()?>demotimeline/v2" class="nav-link nav-toggle">
                      <span class="label label-rouded label-menu label-danger">new</span>
                      <i class=""></i><small>Demo Timeline</small>
                    </a>
                  </li>
                <?php } ?>

                <li class="nav-item">
                  <?php
                    if (isset($url_code_view)) {
                      $url_code_viewfix = $url_code_view;
                    }else {
                      $url_code_viewfix = 0;
                    }
                  ?>
                        <?php
                  //echo $cur_url;
                  if($url_code_viewfix == 1){?>
                    <a href="#" class="nav-link nav-toggle">
                      <i class=""></i><small>List Vehicle</small>
                      <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                      <li class="nav-item">
                        <a href="<?=base_url();?>maps" class="nav-link"><small>All Vehicle</small></a>
                      </li>

                      <?php
              						if (isset($vehicledata) && (count($vehicledata)>0)){
              							for ($i=0;$i<count($vehicledata);$i++){
              						?>
                          <?php
                          $lastengine = "";
													$lastspeed = "";

													//check expired
                          $view_status = "";
													if($vehicledata[$i]['vehicle_active_date2'] < date("Ymd")){
														$view_status = "<font color='black'>Expired</font>";
													}else {
                            $datajson = $this->dashboardmodel->getjson_status2($vehicledata[$i]['vehicle_device']);
														if($datajson != ""){
															// if($datajson->auto_last_speed > 0){
																$lastengine = $datajson->auto_last_engine;
																$lastspeed  = round($datajson->auto_last_speed)." kph";
																if($lastspeed > 0){
																	$view_status = "<font color='yellow'>".$lastspeed." Moving </font>";
																}else{
																	$view_status = "<font color='white'>Stop </br>". date("d-m-Y H:i:s", strtotime($datajson->auto_last_update)) ."</font>";
																}
														}
                          }
                          ?>
                          <?php if($vehicledata[$i]['vehicle_active_date2'] < date("Ymd")){ ?>
                            <li class="nav-item">
                              <a href="#" class="nav-link" disable><small><?=$vehicledata[$i]['vehicle_no'].' '.$vehicledata[$i]['vehicle_name'].' <br>'.$view_status;?> </small></a>
                            </li>
                          <?php }else {?>
                            <li class="nav-item">
                              <a href="#" class="nav-link" onclick="forgetcenter2('<?=$vehicledata[$i]['vehicle_device'];?>')"><small><?=$vehicledata[$i]['vehicle_no'].' '.$vehicledata[$i]['vehicle_name'].' <br>'.$view_status;?> </small></a>
                            </li>
                          <?php } ?>
                      <?php }} ?>
                    </ul>
                </li>

                <?php if (isset($vehicle)) {?>
                  <?php for ($j=0; $j < sizeof($vehicle); $j++) {?>
                    <li class="nav-item">
                      <a href="javascript:;" class="nav-link nav-toggle">
                        <small><?=$vehicle[$j]['company_name'];?></small><span class="arrow"></span>
                      </a>

                      <ul class="sub-menu">
                        <li class="nav-item">
                          <a href="<?=base_url();?>maps/area/<?=$vehicle[$j]['company_id'];?>" class="nav-link-togle">
                            <small>All <?=$vehicle[$j]['company_name'];?></small></a> <span class="arrow"></span>
                        </li>

                        <?php for ($k=0; $k < sizeof($vehicle[$j]['vehicle']); $k++) {?>
                          <?php
                          $lastengine = "";
													$lastspeed = "";

													//check expired
													if($vehicle[$j]['vehicle'][$k]->vehicle_active_date2 < date("Ymd")){
														$view_status = "<font color='black'>Expired</font>";
													}else {
                            $datajson = $this->dashboardmodel->getjson_status2($vehicle[$j]['vehicle'][$k]->vehicle_device);
														if($datajson != ""){
															// if($datajson->auto_last_speed > 0){
																$lastengine = $datajson->auto_last_engine;
																$lastspeed  = round($datajson->auto_last_speed)." kph";
																if($lastspeed > 0){
																	$view_status = "<font color='yellow'>".$lastspeed." Moving </font>";
																}else{
																	$view_status = "<font color='white'>Stop </br>". date("d-m-Y H:i:s", strtotime($datajson->auto_last_update)) ."</font>";
																}
														}
                          }
                          ?>
                          <li class="nav-item">
                            <a href="#" class="nav-link-togle" onclick="forgetcenter2('<?=$vehicle[$j]['vehicle'][$k]->vehicle_device;?>')">
                              <small><?=$vehicle[$j]['vehicle'][$k]->vehicle_no . ' ' .$vehicle[$j]['vehicle'][$k]->vehicle_name. ' ' . $view_status;?></small></a>
                              <span class="arrow"></span>
                          </li>
                        <?php } ?>

                      </ul>
                    </li>
                  <?php } ?>
                <?php } ?>
            <?php } ?>
            </div>
      <?php }elseif ($code_view_menu == "configuration") {?>
        <div id="sidebar_config">
          <ul class="sidemenu page-header-fixed p-t-20" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="menu-heading">
              <span>-- Configuration</span>
            </li>

            <?php
            $userid = $this->sess->user_id;
             if ($userid == "389") {?>
               <li class="nav-item start">
                 <span class="label label-rouded label-menu label-danger">new</span>
                 <a href="<?=base_url();?>project/schedule" class="nav-link">
                   <span class="title"><small>Project Schedule</small></span>
                 </a>
               </li>
            <?php } ?>

            <li class="nav-item start">
              <!-- <a href="<?=base_url();?>user/add/<?=$this->sess->user_id;?>" class="nav-link "> -->
              <a href="<?=base_url();?>account/add/<?=$this->sess->user_id;?>" class="nav-link">
                <span class="title"><small>Private Information</small></span>
              </a>
            </li>

            <li class="nav-item start">
              <!-- <a href="<?=base_url();?>transporter/branchoffice" class="nav-link "> -->
              <a href="<?=base_url();?>account/branch" class="nav-link ">
                <span class="title"><small>Branch Office</small></span>
              </a>
            </li>
            <li class="nav-item start">
              <!-- <a href="<?=base_url();?>transporter/branchoffice" class="nav-link "> -->
              <a href="<?=base_url();?>account/subbranchoffice" class="nav-link ">
                <span class="title"><small>Sub Branch Office</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>vehicles/workshop" class="nav-link">
                <span class="label label-rouded label-menu label-danger">new</span>
                <span class="title"><small>Workshop / Agency / Location</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>vehicles/maintenance" class="nav-link">
                <span class="label label-rouded label-menu label-danger">new</span>
                <span class="title"><small>Maintenance</small></span>
              </a>
            </li>
            <li class="nav-item start">
              <!-- <a href="<?=base_url();?>transporter/mod_vehicle_maintenance" class="nav-link "> -->
                <a href="<?=base_url();?>vehicles" class="nav-link">
                <span class="title"><small>Vehicle</small></span>
              </a>
            </li>
            <li class="nav-item start">
              <!--<a href="<?=base_url();?>transporter/driver" class="nav-link ">-->
             <a href="<?=base_url();?>driver" class="nav-link ">
                <span class="title"><small>Driver</small></span>
              </a>
            </li>
            <li class="nav-item start">
              <!--<a href="<?=base_url();?>transporter/customer" class="nav-link ">-->
              <a href="<?=base_url();?>account/customer" class="nav-link ">
                <span class="title"><small>Customer</small></span>
              </a>
            </li>
            <li class="nav-item start">
              <!--<a href="<?=base_url();?>transporter/customer" class="nav-link ">-->
              <a href="<?=base_url();?>account/subcustomer" class="nav-link ">
                <span class="title"><small>Sub Customer</small></span>
              </a>
            </li>
            <li class="nav-item start">
              <!--<a href="<?=base_url();?>transporter/user" class="nav-link ">-->
               <a href="<?=base_url();?>account" class="nav-link ">
                <span class="title"><small>User</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>poolmaster" class="nav-link">
                <span class="title"><small>Pool</small></span>
              </a>
            </li>
            <li class="nav-item start">
              <a href="<?=base_url();?>destinationmaster" class="nav-link ">
                <span class="title"><small>Destination</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>streetdata" class="nav-link ">
                <span class="title"><small>Street</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>geofencedata" class="nav-link ">
                <span class="title"><small>Geofence Setup</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>geofencedatalist" class="nav-link ">
                <span class="title"><small>Geofence List</small></span>
              </a>
            </li>
            <?php
              $userid = $this->sess->user_id;
              if ($userid == 4201) {?>
                <li class="nav-item">
                  <a href="<?=base_url();?>geofencedatalive" class="nav-link ">
                    <span class="title"><small>Geofence Setup (Live)</small></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?=base_url();?>geofencedatalistlive" class="nav-link ">
                    <span class="title"><small>Geofence List (Live)</small></span>
                  </a>
                </li>
              <?php } ?>
            <li class="nav-item start">
              <a href="<?=base_url();?>poidata" class="nav-link ">
                <span class="title"><small>POI (Point of Interest)</small></span>
              </a>
            </li>
            <li class="nav-item start">
              <a href="<?=base_url();?>ritase" class="nav-link ">
                <span class="title"><small>Ritase</small></span>
              </a>
            </li>
            <?php
              $user_level = $this->sess->user_level;
              if ($user_level == 1) {?>
                <li class="nav-item start">
                  <a href="<?=base_url();?>device" class="nav-link ">
                    <span class="label label-rouded label-menu label-danger">new</span>
                    <span class="title"><small>Device</small></span>
                  </a>
                </li>
              <?php } ?>
            <?php
            $userid = $this->sess->user_id;
            if ($userid == 4050) {?>
              <li class="nav-item">
               <a href="<?=base_url();?>tms" class="nav-link">
                 <span class="label label-rouded label-menu label-danger">beta</span>
                 <span class="title"><small>TMS</small></span>
               </a>
             </li>
            <?php } ?>
          </ul>
        </div>
      <?php }elseif ($code_view_menu == "report") {?>
        <div id="sidebar_report">
          <ul class="sidemenu page-header-fixed p-t-20" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="menu-heading">
              <span>-- Report</span>
            </li>
            <li class="nav-item start">
               <!-- onclick="showmodalfivereport()" -->
              <a href="<?php echo base_url()?>triphistory/overspeed/<?php echo $name.'/'.$host ?>" class="nav-link ">
                <span class="title"><small>Overspeed</small></span>
              </a>
            </li>
            <li class="nav-item">
              <!-- onclick="showmodalfivereport()" -->
              <a href="<?php echo base_url()?>triphistory/parkingtime/<?php echo $name.'/'.$host ?>" class="nav-link ">
                <span class="title"><small>Parking</small></span>
              </a>
            </li>
            <li class="nav-item">
              <!-- onclick="showmodalfivereport()" -->
              <a href="<?php echo base_url()?>triphistory/history/<?php echo $name.'/'.$host ?>" class="nav-link ">
                <span class="title" ><small>History</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>tripreport/history" class="nav-link">
                <span class="label label-rouded label-menu label-danger">new</span>
                <span class="title"><small>History Map</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>vehicles/maintenancehistory" class="nav-link">
                <span class="label label-rouded label-menu label-danger">new</span>
                <span class="title"><small>Maintenance History</small></span>
              </a>
            </li>

            <?php if ($this->sess->user_id == 4201) {?>
              <li class="nav-item">
                <a href="<?=base_url();?>securityevidence" class="nav-link">
                  <span class="label label-rouded label-menu label-danger">new</span>
                  <span class="title"><small>Security Evidence</small></span>
                </a>
              </li>
            <?php } ?>

           <?php if (in_array(strtoupper($this->sess->user_id), $this->config->item("user_view_snap"))) { ?>
             <li class="nav-item">
              <a href="<?=base_url();?>snapreport" class="nav-link">
                <span class="label label-rouded label-menu label-danger">new</span>
                <span class="title"><small>Snap Report</small></span>
              </a>
            </li>
            <?php }?>
            <?php if ($this->sess->user_id == 4122) {?>
                       <li>
                         <a href="<?php echo base_url()?>tripreport/brake" class="nav-link nav-toggle">
                           <span class="label label-rouded label-menu label-danger">new</span>
                           <i class=""></i><small>Brake/Stop Report</small>
                         </a>
                       </li>
                 <?php } ?>


            <!-- <li class="nav-item">
              <a href="<?=base_url();?>historynew" class="nav-link ">
                <span class="title"><small>History New</small></span>
              </a>
            </li> -->
            <li class="nav-item">
              <!-- onclick="showmodalfivereport()" -->
              <a href="<?php echo base_url()?>triphistory/workhour/<?php echo $name.'/'.$host ?>" class="nav-link ">
                <span class="title" ><small>Workhour</small></span>
              </a>
            </li>
            <li class="nav-item">
              <!-- onclick="showmodalfivereport()" -->
              <a href="<?php echo base_url()?>triphistory/geofence/<?php echo $namegeofence.'/'.$hostgeofence ?>" class="nav-link ">
                <span class="title"><small>Geofence</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>tripreport/geofence" class="nav-link ">
                <span class="title"><small>In Out Geofence</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>tripreport/tripmileage" class="nav-link ">
                <span class="title"><small>Trip Mileage</small></span>
              </a>
            </li>
            <li class="nav-item">
              <!-- <a href="<?=base_url();?>report/mn_playback" class="nav-link "> -->
                <a href="<?=base_url();?>tripreport/playback" class="nav-link ">
                <span class="title"><small>Playback</small></span>
              </a>
            </li>
            <?php if (in_array(strtoupper($this->sess->user_id), $this->config->item("user_view_customreport"))) { ?>
              <li>
                <a href="<?php echo base_url()?>dailyactivityreport" class="nav-link nav-toggle">
                  <span class="label label-rouded label-menu label-danger">new</span>
                  <i class=""></i><small>Daily Activity</small>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?=base_url();?>operationalreport" class="nav-link ">
                  <span class="title"><small>Operational Report</small></span>
                </a>
              </li>
              <li>
                <a href="<?=base_url();?>operationalreport/summary">
                  <span class="title"><small>Operational Report (Summary)</small></span>
                </a>
              </li>
              <?php } ?>
            <li class="nav-item">
              <a href="<?=base_url();?>tripreport/door" class="nav-link ">
                <span class="title"><small>Door Report</small></span>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?=base_url();?>tripreport/pto" class="nav-link ">
                <span class="title"><small>PTO Report</small></span>
              </a>
            </li>
            <?php
            $userid = $this->sess->user_id;
             if ($userid == "389") {?>
               <li class="nav-item">
                 <a href="<?=base_url();?>operationalreport/pto" class="nav-link ">
                   <span class="title"><small>Operational Report PTO</small></span>
                 </a>
               </li>
            <?php } ?>
            <li class="nav-item">
              <!-- <a href="<?=base_url();?>report/mn_driver_hist" class="nav-link "> -->
                <a href="<?=base_url();?>driverhistory" class="nav-link ">
                <span class="title"><small>Driver History</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>tripreport/ritase" class="nav-link ">
                <span class="title"><small>Ritase Report</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url()?>devicealert/listdevicealert" class="nav-link ">
                <span class="title"><small>Device Alert</small></span>
              </a>
            </li>
            <?php
              if ($userid == 389) {?>
                <li class="nav-item">
                  <a href="<?=base_url();?>download/histcsv" class="nav-link ">
                    <span class="title"><small>Daily History (CSV)</small></span>
                  </a>
                </li>
            <?php  } ?>
              <?php
              $userid = $this->sess->user_id;
               if ($userid == 3212 || $userid == 1445) {?>
                <li class="nav-item">
                 <a href="javascript:;" class="nav-link nav-toggle">
                   <small>Summary KM</small><span class="arrow"></span>
                 </a>
                 <ul class="sub-menu">
                   <!--<li class="nav-item">
              <a href="<?=base_url();?>dashboard/summary/all" class="nav-link">All</a>
              </li>-->
                   <?php
              if (isset($company) && (count($company)>0)){
              for ($i=0;$i<count($company);$i++){ ?>
                     <li class="nav-item">
                       <a href="#" class="nav-link"><small><?=$company[$i]->company_name;?></small></a>
                     </li>
                     <?php }} ?>
                 </ul>
                </li>

              <?php } ?>
            </li>
          </ul>
        </div>
      <?php }else {?>
        <div id="sidebar_billing">
          <ul class="sidemenu page-header-fixed p-t-20" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="menu-heading">
              <span>-- Billing</span>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link nav-toggle">
                <i class=""></i><small>Vehicle Status</small>
                <span class="arrow"></span>
              </a>

              <ul class="sub-menu">
                <li class="nav-item">
                  <a href="<?=base_url();?>billing/active" class="nav-link"><small>Active (<?php echo sizeof($resultactive); ?>)</small></a>
                </li>

                <li class="nav-item">
                  <a href="<?=base_url();?>billing/expired" class="nav-link"><small>Expired (<?php echo sizeof($resultexpired); ?>)</small></a>
                </li>

                <li class="nav-item">
                  <a href="<?=base_url();?>billing/devices" class="nav-link"><small>Devices (<?php echo sizeof($resulttotaldev); ?>)</small></a>
                </li>
              </ul>
            </li>

          </ul>
        </div>
      <?php } ?>
  <?php } ?>

  <div id="sidebar_monitoring" style="display: none;">
    <ul class="sidemenu page-header-fixed p-t-20" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
      <li class="sidebar-user-panel">
        <div class="profile-usertitle-job">
          Total(
          <a href="<?=base_url();?>maps" style="color:yellow;">
            <?=$total_vehicle;?>
          </a>) | Online(
          <a href="<?=base_url();?>maps/online" style="color:yellow;">
            <?=$total_online;?>
          </a>) | Offline(
          <a href="<?=base_url();?>maps/offline" style="color:yellow;">
            <?=$total_offline;?>
          </a>)
        </div>
      </li>

      <li class="nav-item">
        <input type="text" name="searchnopol" id="searchnopol" placeholder="input vehicle no" size="10px;" style="margin-left: 1%;">
        <button type="button" class="btn btn-success btn-sm" id="btnSearchNopol" onclick="forsearchinput()">
          <span class="fa fa-search"></span>
        </button>
        <?php
      $userid = $this->sess->user_id;
       if ($userid == 3212 || $userid == 1445) {?>
          <a href="<?=base_url();?>dashboard" class="nav-link nav-toggle">
            <i class="material-icons">dashboard</i>
            <span class="title">Dashboard</span>
          </a>
      </li>
      <?php } ?>

        <li>
          <a href="<?php echo base_url()?>maps/outofgeofence" class="nav-link nav-toggle">
            <i class=""></i><small>Out Of Geofence</small>
          </a>
        </li>

        <!-- MODUL TMS -->
        <?php $user_level = $this->sess->user_level; ?>
            <li class="nav-item">
               <!-- style="display:none;" -->
                <a href="#" class="nav-link nav-toggle">
                  <span class="label label-rouded label-menu label-danger">new</span>
                  <i class=""></i><small>TMS Modul</small>
                  <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                  <li class="nav-item">
                    <a href="<?=base_url();?>joborder" class="nav-link ">
                      <span class="title"><small>Job Order</small></span>
                    </a>
                  </li>

                  <li class="nav-item">
                    <!--<a href="<?=base_url();?>transporter/customer" class="nav-link ">-->
                    <a href="<?=base_url();?>customer" class="nav-link ">
                      <span class="title"><small>Customer (Droppoint)</small></span>
                    </a>
                  </li>
                </ul>
            </li>
s          <!-- MODUL TMS -->

        <?php if ($userid == 4122) {?>
          <li>
            <a href="<?php echo base_url()?>demotimeline/v2" class="nav-link nav-toggle">
              <span class="label label-rouded label-menu label-danger">new</span>
              <i class=""></i><small>Demo Timeline</small>
            </a>
          </li>
        <?php } ?>

        <li class="nav-item">
          <?php
            if (isset($url_code_view)) {
              $url_code_viewfix = $url_code_view;
            }else {
              $url_code_viewfix = 0;
            }
          ?>
                <?php
          //echo $cur_url;
          if($url_code_viewfix == 1){?>
            <a href="#" class="nav-link nav-toggle">
              <i class=""></i><small>List Vehicle</small>
              <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
              <li class="nav-item">
                <a href="<?=base_url();?>maps" class="nav-link"><small>All Vehicle</small></a>
              </li>

              <?php
                  if (isset($vehicledata) && (count($vehicledata)>0)){
                    for ($i=0;$i<count($vehicledata);$i++){
                  ?>
                  <?php
                  $lastengine = "";
                  $lastspeed = "";

                  //check expired
                  if($vehicledata[$i]['vehicle_active_date2'] < date("Ymd")){
                    $view_status = "<font color='black'>Expired</font>";
                  }else {
                    $datajson = $this->dashboardmodel->getjson_status2($vehicledata[$i]['vehicle_device']);
                    if($datajson != ""){
                      // if($datajson->auto_last_speed > 0){
                        $lastengine = $datajson->auto_last_engine;
                        $lastspeed  = round($datajson->auto_last_speed)." kph";
                        if($lastspeed > 0){
                          $view_status = "<font color='yellow'>".$lastspeed." Moving </font>";
                        }else{
                          $view_status = "<font color='white'>Stop </br>". date("d-m-Y H:i:s", strtotime($datajson->auto_last_update)) ."</font>";
                        }
                    }
                  }
                  ?>
                  <?php if($vehicledata[$i]['vehicle_active_date2'] < date("Ymd")){ ?>
                    <li class="nav-item">
                      <a href="#" class="nav-link" disable><small><?=$vehicledata[$i]['vehicle_no'].' '.$vehicledata[$i]['vehicle_name'].' <br>'.$view_status;?> </small></a>
                    </li>
                  <?php }else {?>
                    <li class="nav-item">
                      <a href="#" class="nav-link" onclick="forgetcenter2('<?=$vehicledata[$i]['vehicle_device'];?>')"><small><?=$vehicledata[$i]['vehicle_no'].' '.$vehicledata[$i]['vehicle_name'].' <br>'.$view_status;?> </small></a>
                    </li>
                  <?php } ?>
              <?php }} ?>
            </ul>
        </li>

        <?php if (isset($vehicle)) {?>
          <?php for ($j=0; $j < sizeof($vehicle); $j++) {?>
            <li class="nav-item">
              <a href="javascript:;" class="nav-link nav-toggle">
                <small><?=$vehicle[$j]['company_name'];?></small><span class="arrow"></span>
              </a>

              <ul class="sub-menu">
                <li class="nav-item">
                  <a href="<?=base_url();?>maps/area/<?=$vehicle[$j]['company_id'];?>" class="nav-link-togle">
                    <small>All <?=$vehicle[$j]['company_name'];?></small></a> <span class="arrow"></span>
                </li>

                <?php for ($k=0; $k < sizeof($vehicle[$j]['vehicle']); $k++) {?>
                  <?php
                  $lastengine = "";
                  $lastspeed = "";

                  //check expired
                  if($vehicle[$j]['vehicle'][$k]->vehicle_active_date2 < date("Ymd")){
                    $view_status = "<font color='black'>Expired</font>";
                  }else {
                    $datajson = $this->dashboardmodel->getjson_status2($vehicle[$j]['vehicle'][$k]->vehicle_device);
                    if($datajson != ""){
                      // if($datajson->auto_last_speed > 0){
                        $lastengine = $datajson->auto_last_engine;
                        $lastspeed  = round($datajson->auto_last_speed)." kph";
                        if($lastspeed > 0){
                          $view_status = "<font color='yellow'>".$lastspeed." Moving </font>";
                        }else{
                          $view_status = "<font color='white'>Stop </br>". date("d-m-Y H:i:s", strtotime($datajson->auto_last_update)) ."</font>";
                        }
                    }
                  }
                  ?>
                  <li class="nav-item">
                    <a href="#" class="nav-link-togle" onclick="forgetcenter2('<?=$vehicle[$j]['vehicle'][$k]->vehicle_device;?>')">
                      <small><?=$vehicle[$j]['vehicle'][$k]->vehicle_no . ' ' . $view_status;?></small></a>
                      <span class="arrow"></span>
                  </li>
                <?php } ?>

              </ul>
            </li>
          <?php } ?>
        <?php } ?>
    <?php } ?>
    </div>

    <!-- report -->
    <div id="sidebar_report" style="display:none">
      <ul class="sidemenu page-header-fixed p-t-20" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <li class="menu-heading">
          <span>-- Report</span>
        </li>
        <li class="nav-item start">
          <a href="<?php echo base_url()?>triphistory/overspeed/<?php echo $name.'/'.$host ?>" class="nav-link ">
            <span class="title"><small>Overspeed</small></span>
          </a>
        </li>
        <li class="nav-item">
          <!-- onclick="showmodalfivereport()" -->
          <a href="<?php echo base_url()?>triphistory/parkingtime/<?php echo $name.'/'.$host ?>" class="nav-link ">
            <span class="title"><small>Parking</small></span>
          </a>
        </li>
        <li class="nav-item">
          <!-- onclick="showmodalfivereport()" -->
          <a href="<?php echo base_url()?>triphistory/history/<?php echo $name.'/'.$host ?>" class="nav-link ">
            <span class="title" ><small>History</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>tripreport/history" class="nav-link">
            <span class="label label-rouded label-menu label-danger">new</span>
            <span class="title"><small>History Map</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>vehicles/maintenancehistory" class="nav-link">
            <span class="label label-rouded label-menu label-danger">new</span>
            <span class="title"><small>Maintenance History</small></span>
          </a>
        </li>

        <?php if ($this->sess->user_id == 4201) {?>
          <li class="nav-item">
            <a href="<?=base_url();?>securityevidence" class="nav-link">
              <span class="label label-rouded label-menu label-danger">new</span>
              <span class="title"><small>Security Evidence</small></span>
            </a>
          </li>
        <?php } ?>

   <?php if (in_array(strtoupper($this->sess->user_id), $this->config->item("user_view_snap"))) { ?>
    <li class="nav-item">
      <a href="<?=base_url();?>snapreport" class="nav-link">
        <span class="label label-rouded label-menu label-danger">new</span>
        <span class="title"><small>Snap Report</small></span>
      </a>
    </li>
   <?php }?>
   <?php if ($this->sess->user_id == 4122) {?>
              <li>
                <a href="<?php echo base_url()?>tripreport/brake" class="nav-link nav-toggle">
                  <span class="label label-rouded label-menu label-danger">new</span>
                  <i class=""></i><small>Brake/Stop Report</small>
                </a>
              </li>
        <?php } ?>
        <!-- <li class="nav-item">
          <a href="<?=base_url();?>historynew" class="nav-link ">
            <span class="title"><small>History New</small></span>
          </a>
        </li> -->
        <li class="nav-item">
          <!-- onclick="showmodalfivereport()" -->
          <a href="<?php echo base_url()?>triphistory/workhour/<?php echo $name.'/'.$host ?>" class="nav-link ">
            <span class="title" ><small>Workhour</small></span>
          </a>
        </li>
        <li class="nav-item">
          <!-- onclick="showmodalfivereport()" -->
          <a href="<?php echo base_url()?>triphistory/geofence/<?php echo $namegeofence.'/'.$hostgeofence ?>" class="nav-link ">
            <span class="title"><small>Geofence</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>tripreport/geofence" class="nav-link ">
            <span class="title"><small>In Out Geofence</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>tripreport/tripmileage" class="nav-link ">
            <span class="title"><small>Trip Mileage</small></span>
          </a>
        </li>
        <li class="nav-item">
          <!-- <a href="<?=base_url();?>report/mn_playback" class="nav-link "> -->
            <a href="<?=base_url();?>tripreport/playback" class="nav-link ">
            <span class="title"><small>Playback</small></span>
          </a>
        </li>
        <?php if (in_array(strtoupper($this->sess->user_id), $this->config->item("user_view_customreport"))) { ?>
          <li>
            <a href="<?php echo base_url()?>dailyactivityreport" class="nav-link nav-toggle">
              <span class="label label-rouded label-menu label-danger">new</span>
              <i class=""></i><small>Daily Activity</small>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="<?=base_url();?>operationalreport" class="nav-link ">
              <span class="title"><small>Operational Report</small></span>
            </a>
          </li>
          <li>
            <a href="<?=base_url();?>operationalreport/summary">
              <span class="title"><small>Operational Report (Summary)</small></span>
            </a>
          </li>
          <?php } ?>
        <li class="nav-item">
          <a href="<?=base_url();?>tripreport/door" class="nav-link ">
            <span class="title"><small>Door Report</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>tripreport/pto" class="nav-link ">
            <span class="title"><small>PTO Report</small></span>
          </a>
        </li>
        <?php
        $userid = $this->sess->user_id;
         if ($userid == "389") {?>
           <li class="nav-item">
             <a href="<?=base_url();?>operationalreport/pto" class="nav-link ">
               <span class="title"><small>Operational Report PTO</small></span>
             </a>
           </li>
         <?php } ?>
        <li class="nav-item">
          <!-- <a href="<?=base_url();?>report/mn_driver_hist" class="nav-link "> -->
            <a href="<?=base_url();?>driverhistory" class="nav-link ">
            <span class="title"><small>Driver History</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>tripreport/ritase" class="nav-link ">
            <span class="title"><small>Ritase Report</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url()?>devicealert/listdevicealert" class="nav-link ">
            <span class="title"><small>Device Alert</small></span>
          </a>
        </li>
        <?php
          if ($userid == 389) {?>
            <li class="nav-item">
              <a href="<?=base_url();?>download/histcsv" class="nav-link ">
                <span class="title"><small>Daily History (CSV)</small></span>
              </a>
            </li>
        <?php  } ?>

          <?php
          $userid = $this->sess->user_id;
           if ($userid == 3212 || $userid == 1445) {?>
             <li class="nav-item">
               <a href="javascript:;" class="nav-link nav-toggle">
                 <small>Summary KM</small><span class="arrow"></span>
               </a>
             <ul class="sub-menu">
               <!--<li class="nav-item">
           <a href="<?=base_url();?>dashboard/summary/all" class="nav-link">All</a>
           </li>-->
               <?php
           if (isset($company) && (count($company)>0)){
           for ($i=0;$i<count($company);$i++){ ?>
                 <li class="nav-item">
                   <a href="#" class="nav-link"><small><?=$company[$i]->company_name;?></small></a>
                 </li>
                 <?php }} ?>
             </ul>
           </li>
          <?php } ?>
      </ul>
    </div>

    <!-- configuration -->
    <div id="sidebar_config" style="display:none">
      <ul class="sidemenu page-header-fixed p-t-20" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <li class="menu-heading">
          <span>-- Configuration</span>
        </li>

        <?php
        $userid = $this->sess->user_id;
         if ($userid == "389") {?>
           <li class="nav-item start">
             <span class="label label-rouded label-menu label-danger">new</span>
             <a href="<?=base_url();?>project/schedule" class="nav-link">
               <span class="title"><small>Project Schedule</small></span>
             </a>
           </li>
        <?php } ?>

        <li class="nav-item start">
            <a href="<?=base_url();?>account/add/<?=$this->sess->user_id;?>" class="nav-link">
            <span class="title"><small>Private Information</small></span>
          </a>
        </li>

        <li class="nav-item start">
          <!-- <a href="<?=base_url();?>transporter/branchoffice" class="nav-link "> -->
            <a href="<?=base_url();?>account/branch" class="nav-link ">
            <span class="title"><small>Branch Office</small></span>
          </a>
        </li>
        <li class="nav-item start">
          <!-- <a href="<?=base_url();?>transporter/branchoffice" class="nav-link "> -->
          <a href="<?=base_url();?>account/subbranchoffice" class="nav-link ">
            <span class="title"><small>Sub Branch Office</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>vehicles/workshop" class="nav-link">
            <span class="label label-rouded label-menu label-danger">new</span>
            <span class="title"><small>Workshop / Agency / Location</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>vehicles/maintenance" class="nav-link">
            <span class="label label-rouded label-menu label-danger">new</span>
            <span class="title"><small>Maintenance</small></span>
          </a>
        </li>
        <li class="nav-item start">
          <!-- <a href="<?=base_url();?>transporter/mod_vehicle_maintenance" class="nav-link "> -->
            <a href="<?=base_url();?>vehicles" class="nav-link">
            <span class="title"><small>Vehicle</small></span>
          </a>
        </li>
        <li class="nav-item start">
          <!--<a href="<?=base_url();?>transporter/driver" class="nav-link ">-->
         <a href="<?=base_url();?>driver" class="nav-link ">
            <span class="title"><small>Driver</small></span>
          </a>
        </li>
        <li class="nav-item start">
          <!--<a href="<?=base_url();?>transporter/customer" class="nav-link ">-->
          <a href="<?=base_url();?>account/customer" class="nav-link ">
            <span class="title"><small>Customer</small></span>
          </a>
        </li>
        <li class="nav-item start">
          <!--<a href="<?=base_url();?>transporter/customer" class="nav-link ">-->
          <a href="<?=base_url();?>account/subcustomer" class="nav-link ">
            <span class="title"><small>Sub Customer</small></span>
          </a>
        </li>
        <li class="nav-item start">
          <!--<a href="<?=base_url();?>transporter/user" class="nav-link ">-->
           <a href="<?=base_url();?>account" class="nav-link ">
            <span class="title"><small>User</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>poolmaster" class="nav-link">
            <span class="title"><small>Pool</small></span>
          </a>
        </li>
        <li class="nav-item start">
          <a href="<?=base_url();?>destinationmaster" class="nav-link ">
            <span class="title"><small>Destination</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>streetdata" class="nav-link ">
            <span class="title"><small>Street</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>geofencedata" class="nav-link ">
            <span class="title"><small>Geofence Setup</small></span>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url();?>geofencedatalist" class="nav-link ">
            <span class="title"><small>Geofence List</small></span>
          </a>
        </li>
        <?php
          $userid = $this->sess->user_id;
          if ($userid == 4201) {?>
            <li class="nav-item">
              <a href="<?=base_url();?>geofencedatalive" class="nav-link ">
                <span class="title"><small>Geofence Setup (Live)</small></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>geofencedatalistlive" class="nav-link ">
                <span class="title"><small>Geofence List (Live)</small></span>
              </a>
            </li>
          <?php } ?>
        <li class="nav-item start">
          <a href="<?=base_url();?>poidata" class="nav-link ">
            <span class="title"><small>POI (Point of Interest)</small></span>
          </a>
        </li>
        <li class="nav-item start">
          <a href="<?=base_url();?>ritase" class="nav-link ">
            <span class="title"><small>Ritase</small></span>
          </a>
        </li>
        <?php
          $user_level = $this->sess->user_level;
          if ($user_level == 1) {?>
            <li class="nav-item start">
              <a href="<?=base_url();?>device" class="nav-link ">
                <span class="label label-rouded label-menu label-danger">new</span>
                <span class="title"><small>Device</small></span>
              </a>
            </li>
          <?php } ?>
        <?php
        $userid = $this->sess->user_id;
        if ($userid == 4050) {?>
          <li class="nav-item">
           <a href="<?=base_url();?>tms" class="nav-link">
             <span class="label label-rouded label-menu label-danger">beta</span>
             <span class="title"><small>TMS</small></span>
           </a>
         </li>
        <?php } ?>
      </ul>
    </div>

    <!-- billing -->
    <div id="sidebar_billing" style="display:none">
      <ul class="sidemenu page-header-fixed p-t-20" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <li class="menu-heading">
          <span>-- Billing</span>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link nav-toggle">
            <i class=""></i><small>Vehicle Status</small>
            <span class="arrow"></span>
          </a>

          <ul class="sub-menu">
            <li class="nav-item">
              <a href="<?=base_url();?>billing/active" class="nav-link"><small>Active (<?php echo sizeof($resultactive); ?>)</small></a>
            </li>

            <li class="nav-item">
              <a href="<?=base_url();?>billing/expired" class="nav-link"><small>Expired (<?php echo sizeof($resultexpired); ?>)</small></a>
            </li>

            <li class="nav-item">
              <a href="<?=base_url();?>billing/devices" class="nav-link"><small>Devices (<?php echo sizeof($resulttotaldev); ?>)</small></a>
            </li>
          </ul>
        </li>

      </ul>
    </div>

    </div>
  </div>
</div>
