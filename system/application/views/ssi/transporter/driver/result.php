<script>
function driver_image(v)
			{
				showdialog();
				jQuery.post('<?php echo base_url(); ?>transporter/driver/upload_image/', {id: v},
					function(r)
					{
						showdialog(r.html, "Driver Profile");
					}
					, "json"
				);
			}
			
function frmsearch_onsubmit()
	{
		var field = jQuery("#field").val();
		location = '<?php echo base_url();?>transporter/driver/index/'+jQuery("#field").val()+"/"+jQuery("#keyword").val();
		return false;
	}
			
jQuery(document).ready(
	function()
	{
		showclock();
			/* /* 
			jQuery("#sortby").val('<?=$sortby?>');
			jQuery("#orderby").val('<?=$orderby?>') */
			
			/* field_onchange();
			page(0);	 */	
	}
	);

function delete_data(id)
		{
			if (confirm("Are you sure delete this data?")) {
				jQuery.post('<?=base_url()?>transporter/driver/delete_driver/' + id, {}, function(r){
					if (r.error) {
						alert(r.message);
						return;
					}else{
						alert(r.message);
						page();
						return;
					}
				}, "json");
			}		
		}
		
function assign_car(id)
		{
			showdialog();
			jQuery.post('<?=base_url()?>transporter/driver/assign_car/' + id, {},
				function(r)
				{
					if (r.error)
					{
						alert(r.message);
						return;
					}
					
					showdialog(r.html, 'Assign Vehicle');
				}
				, "json"
			);			
		}
</script>
<div style="position: absolute; margin: 0; padding: 0; z-index: 1000; width: 100%;">
	<?=$navigation;?>
	<div id="main" style="margin: 20px;">
		<div class="block-border">
        <form class="block-content form" name="frmsearch" id="frmsearch" onsubmit="javascript:return frmsearch_onsubmit()">
		<h1><?php echo "Driver List"; ?> (<?php echo $total;?>)</h1>
		<fieldset class="grey-bg required">
        <legend><?=$this->lang->line("lsearchby");?></legend>
			<input type="hidden" name="offset" id="offset" value="" />
			<input type="hidden" id="sortby" name="sortby" value="" />
			<input type="hidden" id="orderby" name="orderby" value="" />	
			
        <table width="100%" cellpadding="3" class="tablelist">
				<tr>
					<td>
						<select id="field" name="field">
							<option value="All">All</option>
							<option value="driver_name">Name</option>
						</select>
						<input type="text" name="keyword" id="keyword" value="" class="formdefault" />
						<input type="submit" value="<?=$this->lang->line("lsearch");?>" />
					</td>
				</tr>
			</table>
        </fieldset>
        [ <a href="<?php echo base_url();?>transporter/driver/add"><font color="#0000ff"><?php echo $this->lang->line("ladd")." "."Driver"; ?></font></a> ]
		</form>
		<table width="100%" cellpadding="3" class="table sortable no-margin">
			<thead>
				<tr>
					<th width="1%">&nbsp;</td>
					<th width="2%" style="text-align:center;"><?=$this->lang->line("lno"); ?></td>
					<th width="10%" style="text-align:center;"><?php echo "Name" ?></td>
					<th width="10%" style="text-align:center;"><?php echo "Vehicle" ?></td>
					<th style="text-align:center;"><?php echo "Address" ?></td>
					<th width="7%" style="text-align:center;"><?php echo "Phone" ?></td>
					<th width="7%" style="text-align:center;"><?php echo "Mobile" ?></td>
					<th style="text-align:center;"><?php echo "Licence" ?></td>
					<th width="7%" style="text-align:center;"><?php echo "Licence No" ?></td>
					<th style="text-align:center;"><?php echo "Sex" ?></td>
					<th width="10%" style="text-align:center;"><?php echo "Joint Date" ?></td>
					<th width="10%" style="text-align:center;"><?php echo "Note" ?></td>
					<th width="10%" style="text-align:center;"><?php echo "Control" ?></td>
				</tr>
			</thead>
			<tbody>
				<?php
					if ($data) {
						for ($i=0; $i<count($data); $i++)
					{
				?>
				<tr <?=($i%2) ? "class='odd'" : "";?>>
					<th width="1%">&nbsp;</td>
					<td width="2%"><?=$i+1+$offset?></td>
					<td width="10%"><?=$data[$i]->driver_name;?></td>
					<td width="10%">
						<?php 
									
										if (isset($car))
										{
											foreach ($car as $car_name)
											{
												if ($car_name->vehicle_id == $data[$i]->driver_vehicle)
												{
												
													echo $car_name->vehicle_no."<br />".$car_name->vehicle_name;
													
												}
											}
										}
									
									
									?>
					</td>
					<td><?=$data[$i]->driver_address;?></td>
					<td width="10%"><?=$data[$i]->driver_phone;?></td>
					<td width="10%"><?=$data[$i]->driver_mobile;?></td>
					<td style="text-align:center;"><?=$data[$i]->driver_licence;?></td>
					<td width="10%"><?=$data[$i]->driver_licence_no;?></td>
					<td style="text-align:center;"><?=$data[$i]->driver_sex;?></td>
					<td width="10%"><?=$data[$i]->driver_joint_date;?></td>
					<td width="10%"><?=$data[$i]->driver_note;?></td>
					<td width="10%" style="text-align:center;">
				
					<a href="<?=base_url();?>transporter/driver/edit/<?=$data[$i]->driver_id;?>">
						<img src="<?=base_url();?>assets/images/edit.gif" border="0" alt="<?=$this->lang->line("ledit_data"); ?>" title="<?=$this->lang->line("ledit_data"); ?>">
					</a>
					<a href="javascript:driver_image(<?php echo $data[$i]->driver_id;?>)">
						<img src="<?=base_url();?>assets/transporter/images/driver_photo.png" width="16px" height="16px" border="0" alt="<?php echo "Upload Photo"; ?>" title="<?php echo "Upload Photo"; ?>">
					</a>
					<?php if ($this->sess->user_group <> 0) { ?>
					<a href="#" onclick="javascript:assign_car(<?=$data[$i]->driver_id;?>)">
						<img src="<?=base_url();?>assets/images/addvehicle.png" border="0" width="20px" height="20px" alt="Assign Vehicle" title="Assign Vehicle"></a>
					<?php } ?>
					<a href="javascript:delete_data(<?php echo $data[$i]->driver_id;?>)">
						<img src="<?=base_url();?>assets/images/trash.gif" border="0" alt="<?=$this->lang->line("lremove_data"); ?>" title="<?=$this->lang->line("lremove_data"); ?>">
					</a>
				
				</td>
				</tr>
				<?php } 
				} else { 
					echo "Data Not Available";
				}?>
			</tbody>
			
			<tfoot>
					<tr>
						<td colspan="13"><?=$paging?></td>
					</tr>
			</tfoot>
			
		</table>
	</div>
    </div>
</div>