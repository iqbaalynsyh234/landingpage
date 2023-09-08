<div class="panel" id="panel_form">
  <header class="panel-heading panel-heading-blue">Report Result</header>
  <div class="panel-body" id="bar-parent10">
    <?php if (sizeof($content) < 1) {?>
      <?php echo "Data is Empty" ?>
    <?php }else {?>
      <table class="table table-striped table-bordered" style="font-size: 11px; overflow-y:auto;">
        <thead>
          <tr>
            <th>No</th>
            <th>Detail</th>
            <th>Vehicle</th>
            <th>Alarm Type</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Alarm Duration</th>
            <th>Start Position</th>
            <th>End Position</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; for ($i=0; $i < sizeof($content); $i++) {?>
            <tr>
              <td><?php echo $no ?></td>
              <td>
                <button type="button" class="btn btn-primary" onclick="getdetailinfo('<?php echo $content[$i]['alarm_report_vehicle_id'].','.$content[$i]['alarm_report_start_time'] ?>');">
                  <span class="fa fa-list"></span>
                </button>
              </td>
              <td><?php echo $content[$i]['alarm_report_vehicle_no'].' '.$content[$i]['alarm_report_vehicle_name'] ?></td>
              <td><?php echo $content[$i]['alarm_report_name'] ?></td>
              <td><?php echo date("d-m-Y H:i:s", strtotime($content[$i]['alarm_report_start_time'])) ?></td>
              <td><?php echo date("d-m-Y H:i:s", strtotime($content[$i]['alarm_report_end_time'])) ?></td>

              <td>
                <?php
                $diff = strtotime($content[$i]['alarm_report_end_time']) - strtotime($content[$i]['alarm_report_start_time']);
                echo $diff."S";
                 ?>
              </td>

              <td>
                <a href='http://maps.google.com/maps?z=12&t=m&q=loc:<?php echo $content[$i]['alarm_report_coordinate_start'] ?>' target='_blank'><?php echo $content[$i]['alarm_report_coordinate_start'] ?></a>
              </td>
              <td>
                <a href='http://maps.google.com/maps?z=12&t=m&q=loc:<?php echo $content[$i]['alarm_report_coordinate_end'] ?>' target='_blank'><?php echo $content[$i]['alarm_report_coordinate_end'] ?></a>
              </td>
            </tr>
          <?php $no++; } ?>
        </tbody>
      </table>
    <?php } ?>
  </div>
</div>
