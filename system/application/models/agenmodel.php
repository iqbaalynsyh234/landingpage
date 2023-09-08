<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class AgenModel extends Model {
	
	function AgenModel () 
	{				
		parent::Model();		
	}	
	
	function getLicense($agent)
	{
		if ($agent->user_agent == $this->config->item("GPSANDALASID"))
		{
			return "GPS Andalas Coorp.";
		}
		
		if (! $agent->agent_site)
		{
			return "www.lacak-mobil.com";
		}
		
		$hostnames = explode(".", $agent->agent_site);
		
		if (count($hostnames) < 2)
		{
			return "www.lacak-mobil.com";
		}
		
		
		return sprintf("%s.%s", $hostnames[count($hostnames)-2], $hostnames[count($hostnames)-1]);
		
	}
	
	function getMail($agent)
	{
		if ($agent->agent_mail)
		{
			return $agent->agent_mail;
		}
		
		return "support@lacak-mobil.com";
	}
	
	function getMobiles($agentid)
	{
		if ($agentid == $this->config->item("GPSANDALASID"))
		{
			return "031-70771444";
		}
		
		$this->db->where("user_agent", $agentid);
		$this->db->where("user_type", 3);
		$q = $this->db->get("user");
		
		if ($q->num_rows() == 0) return "";
		
		$rows = $q->rows();
		
		$hp = "";
		for($i=0; $i < count($rows); $i++)
		{
			if (strlen($hp))
			{
				$hp .= ", ";
			}
			
			$hp .= str_replace(";", ",", $rows[$i]->user_mobile);
		}
		
		return $hp;
	}

	function getAgenList($agentid)
	{
		$this->db->where("user_type", 3);
		$this->db->where("user_agent", $agentid);
		$q = $this->db->get("user");
		if ($q->num_rows() == 0) return "";
		
		$rowagents = $q->result();
		
		$sagent = "";
		$idx = 1;
		foreach($rowagents as $agent)
		{
			$sagent .= sprintf("%d. %s %s %s  ", $idx, $agent->user_name, $agent->user_address." ".$agent->user_city." ".$agent->user_province, $agent->user_mobile);
			$idx++;
		}

		return $sagent;
	}
}

