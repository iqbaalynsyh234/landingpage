<script src="<?php echo base_url();?>assets/js/jsblong/jquery.table2excel.js"></script>
<script>
jQuery(document).ready(
		function()
		{
			jQuery("#export_xcel").click(function()
			{
				window.open('data:application/vnd.ms-excel,' + encodeURIComponent(jQuery('#isexport_xcel').html()));
			});
		}
	);


</script>


							<div class="col-lg-6 col-sm-6">
								<input id="btn_hide_form" class="btn btn-circle btn-danger" title="" type="button" value="Hide Form" onclick="javascript:return option_form('hide')" />
								<input id="btn_show_form" class="btn btn-circle btn-success" title="" type="button" value="Show Form" onClick="javascript:return option_form('show')" style="display:none"/>
							</div>
							<div class="col-lg-2 col-sm-2">
							</div>
							<br />

<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="panel">
			<header class="panel-heading panel-heading-blue">RESULT</header>
				<div class="panel-body" id="bar-parent10">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="col-lg-4 col-sm-4">
								<a href="javascript:void(0);" id="export_xcel" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-circle btn-info"><small>Export to Excel</small></a>
							</div>

							<div id="isexport_xcel">
                <table class="table table-striped table-hover" style="width: 50%; font-size:12px;">
                  <?php if (sizeof($data) > 0) {?>
                      <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td><?php echo $data[0]['periode']; ?></td>
                      </tr>

                      <tr>
                        <td>Nopol</td>
                        <td>:</td>
                        <td><?php echo $data[0]['vehicle_no']; ?></td>
                      </tr>

                      <tr>
                        <td>Jenis Kendaraan</td>
                        <td>:</td>
                        <td><?php echo $data[0]['vehicle_name']; ?></td>
                      </tr>
                  <?php } ?>

                </table>
							<table class="table table-striped custom-table table-hover" style="font-size:12px;">
								<thead>
									<tr>
										<th style="text-align:center;">Trip</th>
										<th style="text-align:center;">Jam Dari Toko</th>
										<th style="text-align:center;">Kembali Ke Toko</th>
										<th style="text-align:center;">Waktu 1 Trip (Min)</th>
										<th style="text-align:center;">Jumlah Tempat Drop Barang</th>
										<th style="text-align:center;">Total Waktu Drop Barang (Min)</th>
										<th style="text-align:center;">Jarak KM Tempuh</th>
										<th style="text-align:center;">Waktu Tempuh (Min)</th>
										<th style="text-align:center;">KM / Jam</th>
									</tr>
								</thead>
								<tbody>
				            <?php if (sizeof($data) > 0) {?>
                      <?php
												$totalwaktutrip       = 0;
												$totaldropbarang      = 0;
												$totalwaktudropbarang = 0;
												$totalkmtempuh        = 0;
												$totalwaktutempuh     = 0;
												$totalkmperjam        = 0;
												for ($i=0; $i < sizeof($data); $i++) {?>
                        <tr>
                          <td style="text-align:center;"><?php echo $i+1; ?></td>
                          <td style="text-align:center;"><?php echo date("d-m-Y H:i", strtotime($data[$i]['from_origin'])); ?></td>
                          <td style="text-align:center;"><?php echo date("d-m-Y H:i", strtotime($data[$i]['backto_origin'])); ?></td>
                          <td style="text-align:center;"><?php echo $data[$i]['waktu_trip']; ?></td>
                          <td style="text-align:center;"><?php echo $data[$i]['jumlah_drop_barang']; ?></td>
                          <td style="text-align:center;"><?php echo $data[$i]['totalwaktu_drop_barang']; ?></td>
                          <td style="text-align:center;"><?php echo $data[$i]['km_tempuh']; ?></td>
                          <td style="text-align:center;"><?php echo $data[$i]['totalwaktu_tempuh']; ?></td>
                          <td style="text-align:center;"><?php echo $data[$i]['km_perjam']; ?></td>
                        </tr>
												<?php
												$totalwaktutrip 				+= $data[$i]['waktu_trip'];
												$totaldropbarang 				+= $data[$i]['jumlah_drop_barang'];
												$totalwaktudropbarang 	+= $data[$i]['totalwaktu_drop_barang'];
												$totalkmtempuh 					+= $data[$i]['km_tempuh'];
												$totalwaktutempuh 			+= $data[$i]['totalwaktu_tempuh'];
												$totalkmperjam 					+= $data[$i]['km_perjam'];
												 ?>
                      <?php } ?>
											 <tr>
												<td style="text-align:center; font-weight:bold;"></td>
												<td style="text-align:center; font-weight:bold;"></td>
												<td style="text-align:center; font-weight:bold;">Total</td>
												<td style="text-align:center; font-weight:bold;"><?php echo $totalwaktutrip; ?></td>
												<td style="text-align:center; font-weight:bold;"><?php echo $totaldropbarang; ?></td>
												<td style="text-align:center; font-weight:bold;"><?php echo $totalwaktudropbarang; ?></td>
												<td style="text-align:center; font-weight:bold;"><?php echo $totalkmtempuh; ?></td>
												<td style="text-align:center; font-weight:bold;"><?php echo $totalwaktutempuh; ?></td>
												<td style="text-align:center; font-weight:bold;"><?php echo $totalkmperjam; ?></td>
											 </tr>
											 <tr>
												<td style="text-align:center; font-weight:bold;"></td>
												<td style="text-align:center; font-weight:bold;"></td>
												<td style="text-align:center; font-weight:bold;">Konversi Ke Jam</td>
												<td style="text-align:center; font-weight:bold;"><?php echo number_format($totalwaktutrip/60, 1); ?></td>
												<td style="text-align:center; font-weight:bold;"></td>
												<td style="text-align:center; font-weight:bold;"><?php echo number_format($totalwaktudropbarang/60, 1); ?></td>
												<td style="text-align:center; font-weight:bold;"></td>
												<td style="text-align:center; font-weight:bold;"><?php echo number_format($totalwaktutempuh/60, 1); ?></td>
												<td style="text-align:center; font-weight:bold;"><?php echo number_format($totalkmperjam/sizeof($data), 1); ?></td>
											 </tr>
                    <?php }else{?>
                      <tr>
                        <td>Data is empty</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    <?php } ?>
								</tbody>
							</table>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>
