<!--<!DOCTYPE html>-->
<?php
  $devicealert = $this->config->item('device_alert');
 ?>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta name="Transporter Lacakmobil" content="Transporter Lacakmobil" />
  <meta name="Lacakmobil" content="Lacakmobil" />
  <title><?=$this->sess->user_name;?> | Dashboard Monitoring System</title>
  <!-- google font -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
  <!-- icons -->
  <link href="<?php echo base_url();?>assets/dashboard/assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url();?>assets/dashboard/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <!--bootstrap -->
  <link href="<?php echo base_url();?>assets/dashboard/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

  <link href="<?php echo base_url();?>assets/dashboard/assets/plugins/summernote/summernote.css" rel="stylesheet">
  <!-- morris chart -->
  <link href="<?php echo base_url();?>assets/dashboard/assets/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
  <!-- Material Design Lite CSS -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/assets/plugins/material/material.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/assets/css/material_style.css">
  <!-- animation -->
  <link href="<?php echo base_url();?>assets/dashboard/assets/css/pages/animate_page.css" rel="stylesheet">
  <!-- inbox style -->
  <link href="<?php echo base_url();?>assets/dashboard/assets/css/pages/inbox.min.css" rel="stylesheet" type="text/css" />
  <!-- Template Styles -->
  <link href="<?php echo base_url();?>assets/dashboard/assets/css/plugins.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url();?>assets/dashboard/assets/css/style.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url();?>assets/dashboard/assets/css/responsive.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url();?>assets/dashboard/assets/css/theme-color.css" rel="stylesheet" type="text/css" />
  <!-- Owl Carousel Assets -->
  <link href="<?php echo base_url();?>assets/dashboard/assets/plugins/owl-carousel/owl.carousel.css" rel="stylesheet">
  <link href="<?php echo base_url();?>assets/dashboard/assets/plugins/owl-carousel/owl.theme.css" rel="stylesheet">

  <!-- for form -->
  <!--select2-->
  <link href="<?php echo base_url();?>assets/dashboard/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url();?>assets/dashboard/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url();?>assets/dashboard/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
  <!-- Date Time item CSS -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dashboard/assets/plugins/material-datetimepicker/bootstrap-material-datetimepicker.css" />
  <!-- end for form ->

	<!-- favicon -->
  <link rel="shortcut icon" href="<?=base_url();?>assets/images/favicon_lacakmobil.ico" />
</head>
<!--<body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md header-blue blue-sidebar-color logo-blue" onload=display_ct();>-->

<body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md header-blue blue-sidebar-color logo-blue" style="min-height: 90%;">
  <div class="page-wrapper">

    <!-- start header -->
    <div class="page-header navbar navbar-fixed-top">
      <?=$header;?>
    </div>
    <!-- end header -->

    <!-- start page container -->
    <!--<div class="page-container" style="border:0px solid black; min-height: calc(100vh - 226px); position: absolute;">-->
	<div class="page-container">
      <?=$content;?>
        <div class="scroll-to-top">
          <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- end page container -->

    <!-- start footer -->
    <!-- <div class="page-footer">
      <div class="page-footer-inner"> 2020 &copy; Monitoring GPS Tracking System
        <a href="http://www.lacak-mobil.com" target="_blank" class="makerCss">www.lacak-mobil.com</a>
      </div>
    </div> -->
    <!-- end footer -->
  </div>
  </body>
</html>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/jquery/jquery.min.js"></script>
  <!--<script src="<?php echo base_url();?>assets/dashboard/assets/plugins/popper/popper.min.js" ></script>-->
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/jquery-blockui/jquery.blockui.min.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/moment/moment.min.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/counterup/jquery.waypoints.min.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/counterup/jquery.counterup.min.js"></script>
  <!-- owl carousel -->
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/owl-carousel/owl.carousel.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/js/pages/owl-carousel/owl_data.js"></script>
  <!-- Common js-->
  <script src="<?php echo base_url();?>assets/dashboard/assets/js/app.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/js/layout.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/js/theme-color.js"></script>
  <!-- Material -->
  <!--<script src="<?php echo base_url();?>assets/dashboard/assets/plugins/material/material.min.js"></script>-->
  <!-- animation -->
  <script src="<?php echo base_url();?>assets/dashboard/assets/js/pages/ui/animations.js"></script>
  <!-- sparkline -->
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/js/pages/sparkline/sparkline-data.js"></script>
  <!-- summernote -->
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/summernote/summernote.min.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/js/pages/summernote/summernote-data.js"></script>


  <!-- echart -->
  <!--<script src="<?php echo base_url();?>assets/dashboard/assets/plugins/echarts/echarts.js" ></script>
    <script src="<?php echo base_url();?>assets/dashboard/assets/js/pages/chart/echart/echart-data.js" ></script>-->

  <!--Chart JS-->
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/chart-js/Chart.bundle.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/chart-js/utils.js"></script>
  <!--<script src="<?php echo base_url();?>assets/dashboard/assets/js/pages/chart/chartjs/chartjs-data.js" ></script>-->

  <!-- data tables -->
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/js/pages/table/table_data.js"></script>

  <!-- for form -->
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js"></script>
  <!--select2-->
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/select2/js/select2.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/js/pages/select2/select2-init.js"></script>
  <!-- floating select -->
  <script src="<?php echo base_url();?>assets/dashboard/assets/js/pages/material_select/getmdl-select.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/material-datetimepicker/moment-with-locales.min.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/material-datetimepicker/bootstrap-material-datetimepicker.js"></script>
  <script src="<?php echo base_url();?>assets/dashboard/assets/plugins/material-datetimepicker/datetimepicker.js"></script>

  <script type="text/javascript">
  var devalertarray = {"dt" : "Cut Power Alert", "BO010" : "Cut Power Alert", "BO012" : "Panic Button (SOS)"};
  window.onload = function() {
    // setInterval(getalertalways, 3000);
    // FOR GET THE NOTIFICATION
    var url = '<?php echo base_url()?>vehicles/getfornotif';
    $.post(url, {}, function(response) {
      console.log("response header : ", response);
      // FOR KIR
      var totalkirexpdate = response.total_kirexpdate;
      if (totalkirexpdate > 0) {
        $("#tablekir").show();
        for (var i = 0; i < totalkirexpdate; i++) {
          var stnk = '<tr>';
          stnk += '<td>' + (i+1) + '. </td>';
          stnk += '<td> ' +response.data_notifkir[i].vehicle_no + ' <br> '+response.data_notifkir[i].vehicle_name+'</td>';
          stnk += '<td> ' +response.data_notifkir[i].vehicle_kirexpdate + '</td>';
          stnk += '</tr>';
          $("#kirexpdate").before(stnk);
        }
      }else {
        $("#tablekir").hide();
            var stnk = '<tr>';
            stnk += '<td>Data Not Available </td>';
            stnk += '<td>Data Not Available </td>';
            stnk += '<td>Data Not Available </td>';
            stnk += '</tr>';
            $("#kirexpdate").before(stnk);
      }
      // FOR STNK
      var totalstnkexp = response.total_stnkexpdate;
      if (totalstnkexp > 0) {
        $("#tablestnk").show();
        for (var i = 0; i < totalstnkexp; i++) {
          var stnk = '<tr>';
          stnk += '<td>' + (i+1) + '. </td>';
          stnk += '<td> ' +response.data_notifstnk[i].vehicle_no + ' <br> '+response.data_notifstnk[i].vehicle_name+'</td>';
          stnk += '<td> ' +response.data_notifstnk[i].vehicle_stnkexpdate + '</td>';
          stnk += '</tr>';
          $("#stnkexpdate").before(stnk);
        }
      }else {
        $("#tablestnk").hide();
            var stnk = '<tr>';
            stnk += '<td>Data Not Available </td>';
            stnk += '<td>Data Not Available </td>';
            stnk += '<td>Data Not Available </td>';
            stnk += '</tr>';
            $("#stnkexpdate").before(stnk);
      }

      // FOR SERVICE PERKM
      var total_notifserviceperkm = response.total_notifserviceperkm;
      if (total_notifserviceperkm > 0) {
        $("#tableserviceperkm").show();
        for (var i = 0; i < total_notifserviceperkm; i++) {
          var serviceperkm = '<tr>';
          serviceperkm += '<td>' + (i+1) + '. </td>';
          serviceperkm += '<td> ' +response.data_notifserviceperkm[i].vehicle_no + ' <br> '+response.data_notifserviceperkm[i].vehicle_name+'</td>';
          serviceperkm += '<td> ' +response.data_notifserviceperkm[i].lastodometerfromgps + '</td>';
          serviceperkm += '<td> ' +response.data_notifserviceperkm[i].odometerforservice + '</td>';
          serviceperkm += '</tr>';
          $("#serviceperkm").before(serviceperkm);
        }
      }else {
        $("#tableserviceperkm").hide();
            var serviceperkm = '<tr>';
            serviceperkm += '<td>Data Not Available </td>';
            serviceperkm += '<td>Data Not Available </td>';
            serviceperkm += '<td>Data Not Available </td>';
            serviceperkm += '</tr>';
            $("#serviceperkm").before(serviceperkm);
      }

      // FOR SERVICE PERMONTH
      var total_notifservicepermonth = response.total_notifservicepermonth;
      if (total_notifservicepermonth > 0) {
        $("#tableservicepermonth").show();
        for (var i = 0; i < total_notifservicepermonth; i++) {
          var servicepermonth = '<tr>';
          servicepermonth += '<td>' + (i+1) + '. </td>';
          servicepermonth += '<td> ' +response.data_notifservicepermonth[i].vehicle_no + ' <br> '+response.data_notifservicepermonth[i].vehicle_name+'</td>';
          servicepermonth += '<td> ' +response.data_notifservicepermonth[i].last_service + '</td>';
          servicepermonth += '<td> ' +response.data_notifservicepermonth[i].next_service + '</td>';
          servicepermonth += '</tr>';
          $("#servicepermonth").before(servicepermonth);
        }
      }else {
        $("#tableservicepermonth").hide();
            var servicepermonth = '<tr>';
            servicepermonth += '<td>Data Not Available </td>';
            servicepermonth += '<td>Data Not Available </td>';
            servicepermonth += '<td>Data Not Available </td>';
            servicepermonth += '</tr>';
            $("#servicepermonth").before(servicepermonth);
      }


      var totalallnotif = totalstnkexp + totalkirexpdate + total_notifserviceperkm + total_notifservicepermonth;
        console.log("total data Maintenance : ", totalallnotif);
        if (totalallnotif > 0) {
          $("#totalnotifmaintenance").html(totalallnotif);
          $("#totalnotifmaintenance").show();
        }
    }, 'json');

    function getalertalways() {
        $.post("<?php echo base_url()?>devicealert/getallalert", {}, function(response){
          // console.log("response ready");
          $("#devicenotif").html("");
          var code          = response.code;
          var data          = response.data;
          console.log("data : ", data);
          var totaldata = response.data.length;
            if (response.data == "empty") {
              $("#totalnotifdevicealert").hide();
            }else {
              if (totaldata > 0) {
                $("#totalnotifdevicealert").html(totaldata);
                $("#totalnotifdevicealert").show();
              }
            }

          var htmlnotif = "";
            if (code == 200) {
              htmlnotif += '<li>';
              htmlnotif += '<ul class="dropdown-menu-list small-slimscroll-style" data-handle-color="#637283">';
                for (var i = 0; i < data.length; i++) {
                  var alert_name    = data[i].vehicle_alert;
                  var devicealertfix = alert_name in devalertarray ? devalertarray[alert_name] : alert_name;
                  // console.log("alert_name : ", alert_name);
                  // console.log("databasealert : ", devicealertfix);
                  // console.log("devalertarray : ", devalertarray[alert_name]);
                   htmlnotif += '<li>'+
                                  '<a href="<?php echo base_url()?>devicealert/listalert">'+
                                    '<span class="subject">'+
                                    '<span>  </span>'+
                                    '<span class="time"> '+ data[i].vehicle_alert_datetime +' </span>'+
                                    '</span>'+
                                    (1 + i) + '. <span style="font-size: 12px;">'+ data[i].vehicle_no +'</span> <span class="label label-sm label-warning"> '+ devicealertfix +' </span>'+
                                  '</a>'+
                                '</li>';
                  }
                  htmlnotif += '</ul>';
                  htmlnotif += '<div class="dropdown-menu-footer text-right">';
                    htmlnotif += '<a href="<?php echo base_url()?>devicealert/listalert" target="_blank" class="btn btn-success btn-sm" title="Detail Alert"><span class="fa fa-external-link"></span></a>';
                    htmlnotif += '<button onclick="btnClearNotif();" class="btn btn-danger btn-sm" title="Clear Notification"><span class="fa fa-trash"></span></button>';
                  htmlnotif += '</div>';
                htmlnotif += '</li>';
            }else {
              htmlnotif += '<li>'+
                            '<ul class="dropdown-menu-list small-slimscroll-style" data-handle-color="#637283">'+
                              '<li>'+
                                '<a href="#">'+
                                  '<span class="subject">'+
                                  '<span class="from"> </span>'+
                                  '<span class="time"> </span>'+
                                  '</span>'+
                                  '<span class="message">no data Alert </span>'+
                                '</a>'+
                              '</li>'+
                            '</ul>'+
                          '</li>';
            }
            $("#devicenotif").html(htmlnotif);
        }, 'json');
    }
    setInterval(getalertalways, 30000);
  }

  function btnClearNotif(){
    console.log("onclick ok");
    $.post("<?php echo base_url()?>devicealert/clearnotif", {}, function(response){
      console.log("response : ", response);
      var code = response.code;
      if (code == 200) {
        alert("Alert Successfully Cleared");
        // $("#notifnya").html("Alert Successfully Cleared");
        // $("#notifnya").fadeIn(1000);
        // $("#notifnya").fadeOut(5000);
      }else {
        alert("Alert Failed Cleared");
        // $("#notifnya").html("Alert Failed Cleared");
        // $("#notifnya").fadeIn(1000);
        // $("#notifnya").fadeOut(5000);
      }
    }, 'json');
  }

    function display_c() {
      var refresh = 1000; // Refresh rate in milli seconds
      mytime = setTimeout('display_ct()', refresh)
    }

    /*function display_ct() {
    	var x = new Date()
    	var x1=x.toUTCString();// changing the display to UTC string
    	document.getElementById('ct').innerHTML = x1;
    	tt=display_c();
    }*/
    function display_ct() {
      var x = new Date();
      // date part ///
      var month = x.getMonth() + 1;
      var day = x.getDate();
      var year = x.getFullYear();
      if (month < 10) {
        month = '0' + month;
      }
      if (day < 10) {
        day = '0' + day;
      }
      var x3 = month + '-' + day + '-' + year;

      // time part //
      var hour = x.getHours();
      var minute = x.getMinutes();
      var second = x.getSeconds();
      if (hour < 10) {
        hour = '0' + hour;
      }
      if (minute < 10) {
        minute = '0' + minute;
      }
      if (second < 10) {
        second = '0' + second;
      }
      var x3 = x3 + ' ' + hour + ':' + minute + ':' + second;
      display_c();
    }

    function monitoring_sidebar() {
      jQuery("#sidebar_monitoring").show();
      jQuery("#sidebar_config").hide();
      jQuery("#sidebar_report").hide();
      jQuery("#sidebar_billing").hide();
    }

    function config_sidebar() {
      jQuery("#sidebar_monitoring").hide();
      jQuery("#sidebar_config").show();
      jQuery("#sidebar_report").hide();
      jQuery("#sidebar_billing").hide();
    }

    function report_sidebar() {
      jQuery("#sidebar_monitoring").hide();
      jQuery("#sidebar_config").hide();
      jQuery("#sidebar_report").show();
      jQuery("#sidebar_billing").hide();
    }

    function billing_sidebar() {
      jQuery("#sidebar_monitoring").hide();
      jQuery("#sidebar_config").hide();
      jQuery("#sidebar_report").hide();
      jQuery("#sidebar_billing").show();
    }

    function showmodalfivereport(){
      $("#vehiclelistfivereport").html("");
      $.post("<?php echo base_url()?>maps/getallvehicle", {}, function(response){
        // console.log("response getallvehicle : ", response);
        var JSONString = JSON.parse(response);
        // console.log("response getallvehicle 2: ", JSONString);
        var htmlvehiclelistfivereport = "";
        var data          = JSONString.data;
        // console.log("vdevicearray : ", vdevicearray);
        for (var i = 0; i < data.length; i++) {
          var vdevice      = data[i].vehicle_device;
          var vdevicearray = vdevice.split("@");
          htmlvehiclelistfivereport += '<tr id="rowid_'+data[i].vehicle_device+'">'+
                                  '<td><a name="'+(i+1)+'"></a>'+
                                  '<td style="font-size:12px;">'+(i+1)+'</td>'+
                                  '<td style="font-size:12px;">'+data[i].vehicle_no + " - " + data[i].vehicle_name+'</td>'+
                                  '<td style="font-size:12px;">'+
                                  '<a title="History" href="<?php echo base_url()?>triphistory/history/'+vdevicearray[0]+"/"+vdevicearray[1]+'" target="_blank" class="btn btn-primary btn-sm"><span class="fa fa-car"></span></a>' + '<a title="Workhour" href="<?php echo base_url()?>triphistory/workhour/'+vdevicearray[0]+"/"+vdevicearray[1]+'" target="_blank" class="btn btn-success btn-sm"><span class="fa fa-clock-o"></span></a>' + '<a title="Overspeed" href="<?php echo base_url()?>triphistory/overspeed/'+vdevicearray[0]+"/"+vdevicearray[1]+'" target="_blank" class="btn btn-info btn-sm"><span class="fa fa-dashboard"></span></a>'+ '<a title="Geofence" href="<?php echo base_url()?>triphistory/geofence/'+vdevicearray[0]+"/"+vdevicearray[1]+'" target="_blank" class="btn btn-warning btn-sm"><span class="fa fa-globe"></span></a>' + '<a title="Parking Time" href="<?php echo base_url()?>triphistory/parkingtime/'+vdevicearray[0]+"/"+vdevicearray[1]+'" target="_blank" class="btn btn-danger btn-sm"><span class="fa fa-stop"></span></a>' +
                                  '</td>'+
                               '</tr>';
        }
        $("#vehiclelistfivereport").html(htmlvehiclelistfivereport);
        $("#modalfivereport").show();
      });
    }

    function closemodalfivereport(){
      $("#modalfivereport").hide();
    }

    function btnNotif() {
      $("#ultooltip").toggle('slow');
    }
</script>

  <!-- end for form ->
