  			var gtminfo = null;
        	var glastlat = null;
        	var glastlon = null;        
        	var gcar = null;
        	var glasttime = 0;
        	var gtimer = <?=$this->config->item('timer_realtime')?>;
  			
  			function updateLocation(device)
  			{  		  					  			
  				jQuery.post("<?=base_url();?>mod_db_tupperware/lastinfo", {device: device, lasttime: glasttime},
  					function(r)
  					{ 
		        		if (gtminfo)
		        		{
		        			clearTimeout(gtminfo);
		        		}		        	

  						if (! r)
  						{
  							gtminfo = setTimeout("updateLocation('" + device + "')", gtimer);
  							return;
  						}
  						
  						var nextdevice = update(r);
  						if (nextdevice)
  						{
  							gtminfo = setTimeout("updateLocation('" + nextdevice + "')", gtimer)
  							return;
  						}
  						
		        		gtminfo = setTimeout("updateLocation('" + device + "')", gtimer);
		        	
  					}
  					, "json"
  				);  				
	      	}
