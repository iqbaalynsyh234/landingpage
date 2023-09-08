<style media="screen">
  #page-content-new{
    width: 92%;
  }
</style>
<!-- start sidebar menu -->
<div class="sidebar-container">
  <?=$sidebar;?>
</div>
<!-- end sidebar menu -->

<!-- start page content -->
<div class="page-content-wrapper">
  <div class="page-content" id="page-content-new">
    <br>
    <?php if ($this->session->flashdata('notif')) {?>
      <div class="alert alert-success" id="notifnya" style="display: none;"><?php echo $this->session->flashdata('notif');?></div>
    <?php }?>
    <div class="alert alert-success" id="notifnya2" style="display: none;"></div>
    <div class="row">
      <div class="col-md-12" id="tablecustomer">
          <div class="card-box">
            <div class="card-body">
              <table id="example1" class="table table-striped">
                <thead>
          				<tr>
          					<th>
                      No
                    </th>
          					<th>Vehicle No</th>
          					<th>Vehicle Name</th>
                    <th>Vehicle Device</th>
                    <th>Expired Date</th>
          				</tr>
          			</thead>
                <tbody>
                  <?php for($i=0;$i<count($resultactive);$i++) { ?>
          				  <tr>
            					<td width="2%"><?=$i+1?></td>
      								<td><?php echo $resultactive[$i]['vehicle_no'];?></td>
                      <td><?php echo $resultactive[$i]['vehicle_name'];?></td>
                      <td><?php echo $resultactive[$i]['vehicle_imei'];?></td>
                      <td><?php echo date("d-m-Y", strtotime($resultactive[$i]['vehicle_active_date2'])) ;?></td>
                    </tr>
                  <? } ?>
  							</tbody>
  						</table>
            </div>
      </div>
    </div>
  </div>
</div>
</div>
