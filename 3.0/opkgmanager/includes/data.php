<script type="text/javascript" charset="utf-8">
	$(document).ready( function () {
		var oTable = $('#grid').dataTable( {
			"sDom": 'R<"H"lfr>t<"F"ip>',
			"bJQueryUI": true,
			"sPaginationType": "full_numbers"
		} );
	});
</script>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="grid" width="100%">
	<thead>
		<tr>
			<th>Package Name</th>
			<th>Version</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		
		<?php 

		$odd=0;
		
		shell_exec ("opkg list-installed > /tmp/opkg_list_installed");
		shell_exec("opkg list | awk '{ print $1\"|\"$3 }' > /tmp/opkg_list_all");
		
		$contents = file_get_contents("/tmp/opkg_list_installed");
		
		$file_handle = fopen("/tmp/opkg_list_all", "r");
		while (!feof($file_handle))
		{
			$line = fgets($file_handle);
			if($line != "")
			{
				$line = explode("|", $line);
				
				if($odd % 2) echo '<tr class="odd">'; else echo '<tr class="even">';

				echo '<td>'.$line[0].'</td>';
				echo '<td>'.$line[1].'</td>';

				echo '<td>';
				echo '<a href="javascript:opkgmanager_show_actions(\''.$line[0].'\');">Actions</a>';
				echo '</td>';

				echo '</tr>';

				$odd += 1;
			}
		}
		fclose($file_handle);

		?>
	</tbody>
	<tfoot>
		<tr>
			<th>Package Name</th>
			<th>Version</th>
			<th>Actions</th>
		</tr>
	</tfoot>
</table>
