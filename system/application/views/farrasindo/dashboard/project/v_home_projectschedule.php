<!-- start sidebar menu -->
<div class="sidebar-container">
  <?=$sidebar;?>
</div>

<div class="page-content-wrapper">
  <div class="page-content" id="page-content-new">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="panel" id="panel_form">
          <header class="panel-heading panel-heading-blue">Project Schedule</header>
          <div class="panel-body" id="bar-parent10">
            <form class="block-content form" name="frmsearch" id="frmsearch">
              <a class="btn btn-primary" href="<?=base_url();?>project/add_project">Add Project Schedule</a>
              <!-- <a class="btn btn-success" href="<?=base_url();?>project/pool_list">Pool List</a> -->
        		</form>

            <table class="table" id="example1" style="font-size: 12px;">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Project Name</th>
                  <th>Vehicle</th>
                  <th width="8%;">Company</th>
                  <th>Driver / Operator</th>
                  <th>Customer</th>
                  <th width="18%;">Address</th>
                  <th>Project Date</th>
                  <th>Project Price</th>
                  <th>Status</th>
                  <th>Option</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach ($dataproject as $rowproject) {?>
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $rowproject['project_name'] ?></td>
                  <td><?php echo $rowproject['project_vehicle_no'].'-'.$rowproject['project_vehicle_name'] ?></td>
                  <td>

                      <?php foreach ($company as $rowcompany) {?>
                          <?php if ($rowcompany['company_id'] == $rowproject['project_vehicle_company']) {?>
                            <?php echo $rowcompany['company_name'] ?>
                          <?php } ?>
                      <?php } ?>


                  </td>
                  <td><?php echo $rowproject['project_driver_operator_name']?></td>
                  <td><?php echo $rowproject['project_customer_name'] ?></td>
                  <td>
                    <?php echo $rowproject['project_address'] ?><br>
                    Coord : <a href="https://www.google.com/maps?z=12&t=m&q=loc:<?php echo $rowproject['project_latitude'].','.$rowproject['project_longitude']?>" target="_blank">
                      <?php echo $rowproject['project_latitude'].','.$rowproject['project_longitude']?>
                    </a>
                  </td>
                  <td>
                    <?php echo date("d-m-Y H:i", strtotime($rowproject['project_startdate'])).' s/d '.date("d-m-Y H:i", strtotime($rowproject['project_enddate'])); ?><br>
                    <?php echo $rowproject['project_durationofwork']; ?>
                  </td>
                  <td>
                    <?php echo "Rp. ". number_format($rowproject['project_price'], 0, ",", "."); ?>
                  </td>
                  <td>
                    <?php $status = $rowproject['project_status']; ?>
                    <?php if ($status == 0) {?>
                      <?php echo "On Schedule" ?>
                    <?php }elseif ($status == 1) {?>
                      <?php echo "On Duty" ?>
                    <?php }elseif ($status == 2) {?>
                      <?php echo "On Site" ?>
                    <?php }elseif ($status == 3) {?>
                      <?php echo "Completed" ?>
                    <?php } ?>
                  </td>
                  <td>
                  <?php if ($status != 3) {?>
                    <img width="20px;" src="<?=base_url();?>assets/images/update.png" border="0" title="Complete Project" onclick="completeproject('<?php echo $rowproject['project_no']?>')">
                    <a href="<?=base_url();?>project/edit/<?php echo $rowproject['project_no'];?>">
          						<img src="<?=base_url();?>assets/images/edit.gif" border="0" alt="<?=$this->lang->line("ledit_data"); ?>" title="<?=$this->lang->line("ledit_data"); ?>">
          					</a>

                    <a href="javascript:delete_data(<?php echo $rowproject['project_no'];?>)">
          						<img src="<?=base_url();?>assets/images/trash.gif" border="0" alt="<?=$this->lang->line("lremove_data"); ?>" title="<?=$this->lang->line("lremove_data"); ?>">
          					</a>
                  <?php } ?>


                  </td>
                </tr>
              <?php $no++; } ?>
              <!-- <tfoot>
                  <tr>
                      <td colspan="11"><?=$paging?></td>
                  </tr>
              </tfoot> -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div id="loader2" class="mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>
    <div id="result" style="width:100%"></div>
  </div>
</div>

<script>
function completeproject(id){
  console.log("id : ", id);
  if (confirm("Are you sure want to complete this project ?") == true) {
    jQuery.post('<?php echo base_url(); ?>project/completingproject', {id:id},
     function(response)
     {
       console.log("response : ", response);
       if (response.error == "false") {
         if (confirm(alert("Project Failed to Completed"))) {
           window.location = '<?php echo base_url()?>project';
         }
       }else {
         if (confirm(alert("Project Completed"))) {
           window.location = '<?php echo base_url()?>project';
         }
       }
     }
     , "json"
   );
   } else {
     return false;
   }
}

function delete_data(id)
		{
      console.log("id : ", id);
			if (confirm("Are you sure delete this data?")) {
				jQuery.post('<?=base_url()?>project/delete_project/' + id, {}, function(r){
					if (r.error) {
						if (confirm(alert(r.message))) {
              window.location = '<?php echo base_url()?>project/schedule';
            };
						return;
					}else{
            if (confirm(alert(r.message))) {
              window.location = '<?php echo base_url()?>project/schedule';
            };
						page();
						return;
					}
				}, "json");
			}
		}

</script>
