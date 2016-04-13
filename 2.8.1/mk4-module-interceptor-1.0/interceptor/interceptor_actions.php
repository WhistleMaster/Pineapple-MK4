<?php

require("interceptor_vars.php");

if (isset($_GET['boot']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		if(isset($_GET['8021X'])) $enable_8021X = 1; else $enable_8021X = 0;
		
		switch($action)
		{
			case 'enable':
				exec("sed -i '/exit 0/d' /etc/rc.local"); 
				if($enable_8021X)
					exec("echo ".$module_path."start_interceptor_8021X.sh >> /etc/rc.local");
				else
					exec("echo ".$module_path."start_interceptor.sh >> /etc/rc.local");
				exec("echo exit 0 >> /etc/rc.local");
			break;
			
			case 'disable': 
				if($enable_8021X)
					exec("sed -i '/start_interceptor_8021X.sh/d' /etc/rc.local");
				else
					exec("sed -i '/start_interceptor.sh/d' /etc/rc.local");
			break;
		}
	}
}

if (isset($_GET['interceptor']))
{
	if (isset($_GET['start']))
	{
		if(isset($_GET['8021X'])) $enable_8021X = 1; else $enable_8021X = 0;
		
		if($enable_8021X)
		{
			exec("echo \"8021X\" > ".$module_path."interceptor.run");
			$cmd = "echo ".$module_path."start_interceptor_8021X.sh | at now";
		}
		else
		{	
			exec("echo \"running\" > ".$module_path."interceptor.run");
			$cmd = "echo ".$module_path."start_interceptor.sh | at now";
		}
	}
	if (isset($_GET['stop']))
	{
		if(isset($_GET['8021X'])) $enable_8021X = 1; else $enable_8021X = 0;
		
		exec("rm -rf ".$module_path."interceptor.run");
		
		if($enable_8021X)
		{
			$cmd = "echo ".$module_path."stop_interceptor_8021X.sh | at now";
		}
		else
		{	
			$cmd = "echo ".$module_path."stop_interceptor.sh | at now";
		}
	}
	if (isset($_GET['install']))
	{
		exec("uci set network.interceptor=interface");
		exec("uci set network.interceptor.ifname=wlan0-1");
		exec("uci set network.interceptor.proto=static");
		exec("uci set network.interceptor.ipaddr=172.15.42.1");
		exec("uci set network.interceptor.netmask=255.255.255.0");
		
		exec("uci add wireless wifi-iface");
		exec("uci set wireless.@wifi-iface[-1].ssid=Interceptor");
		exec("uci set wireless.@wifi-iface[-1].device=radio0");
		exec("uci set wireless.@wifi-iface[-1].network=interceptor");
		exec("uci set wireless.@wifi-iface[-1].mode=ap");
		exec("uci set wireless.@wifi-iface[-1].encryption=psk2");
		exec("uci set wireless.@wifi-iface[-1].key=Int3rc3pt0r");
		
		exec("uci set dhcp.interceptor=dhcp");
		exec("uci set dhcp.interceptor.interface=interceptor");
		exec("uci set dhcp.interceptor.start=100");
		exec("uci set dhcp.interceptor.limit=150");
		exec("uci set dhcp.interceptor.leasetime=12h");
		exec("uci set dhcp.interceptor.ignore=0");
		exec("uci set dhcp.interceptor.dhcp_option=\"3,172.15.42.42 3,172.15.42.1 6,172.15.42.1,8.8.8.8 6,172.15.42.1,208.67.222.222\"");
		
		exec("uci commit network");
		exec("uci commit wireless");
		exec("uci commit dhcp");
		
		exec("/etc/init.d/network restart");
		exec("wifi");
		exec("/etc/init.d/dnsmasq restart");
	}
	if (isset($_GET['uninstall']))
	{
		exec("uci delete network.interceptor");
		exec("uci delete dhcp.interceptor");
		exec("uci delete wireless.@wifi-iface[-1]");

		exec("uci commit network");
		exec("uci commit wireless");
		exec("uci commit dhcp");
		
		exec("/etc/init.d/network restart");
		exec("wifi");
		exec("/etc/init.d/dnsmasq restart");
	}
}

if (isset($_GET['install_dep']))
{
	exec("echo \"<?php echo 'working'; ?>\" > ".$module_path."status.php");
	$cmd = "echo \"sh ".$module_path."install.sh\" | at now";
}

if($cmd != "")
{
	$output = shell_exec($cmd);
	echo trim($output);	
}

?>