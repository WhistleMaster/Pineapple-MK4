<?php 
	include('includes/db_connect.php'); 
	include('includes/functions.php'); 
	
	sec_session_start();
	
	if(login_check($mysqli) != true)
	{
		header('Location: ./');
	}

	mb_internal_encoding('UTF-8');
 
	/**
	 * Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'Data_Timestamp', 'Pineapple_Name', 'Station_SSID', 'Station_MAC', 'Station_Signal', 'Station_Signal_Quality');

	// Indexed column (used for fast and accurate table cardinality)
	$sIndexColumn = 'Data_ID';
  
	// DB table to use
	$sTable = 'PineappleStats_Data_View';
 
	// Input method (use $_GET, $_POST or $_REQUEST)
	$input =& $_GET;
 
	/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
 
	/**
	 * Character set to use for the MySQL connection.
	 * MySQL will return all strings in this charset to PHP (if the data is stored correctly in the database).
	 */
	$gaSql['charset']  = 'utf8';
  
	/**
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $input['iDisplayStart'] ) && $input['iDisplayLength'] != '-1' ) {
	    $sLimit = " LIMIT ".intval( $input['iDisplayStart'] ).", ".intval( $input['iDisplayLength'] );
	}
  
  
	/**
	 * Ordering
	 */
	$aOrderingRules = array();
	if ( isset( $input['iSortCol_0'] ) ) {
	    $iSortingCols = intval( $input['iSortingCols'] );
	    for ( $i=0 ; $i<$iSortingCols ; $i++ ) {
	        if ( $input[ 'bSortable_'.intval($input['iSortCol_'.$i]) ] == 'true' ) {
	            $aOrderingRules[] =
	                "`".$aColumns[ intval( $input['iSortCol_'.$i] ) ]."` "
	                .($input['sSortDir_'.$i]==='asc' ? 'asc' : 'desc');
	        }
	    }
	}
 
	if (!empty($aOrderingRules)) {
	    $sOrder = " ORDER BY ".implode(", ", $aOrderingRules);
	} else {
	    $sOrder = "";
	}
  
 
	/**
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$iColumnCount = count($aColumns);
 
	if ( isset($input['sSearch']) && $input['sSearch'] != "" ) {
	    $aFilteringRules = array();
	    for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
	        if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' ) {
	            $aFilteringRules[] = "`".$aColumns[$i]."` LIKE '%".$mysqli->real_escape_string( $input['sSearch'] )."%'";
	        }
	    }
	    if (!empty($aFilteringRules)) {
	        $aFilteringRules = array('('.implode(" OR ", $aFilteringRules).')');
	    }
	}
  
	// Individual column filtering
	for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
	    if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' && $input['sSearch_'.$i] != '' ) {
	        $aFilteringRules[] = "`".$aColumns[$i]."` LIKE '%".$mysqli->real_escape_string($input['sSearch_'.$i])."%'";
	    }
	}
 
	if (!empty($aFilteringRules)) {
	    $sWhere = " WHERE ".implode(" AND ", $aFilteringRules);
	} else {
	    $sWhere = "";
	}
  
	if(isset($input['Pineapple_ID']) && $input['Pineapple_ID'] != "")
		if($sWhere != "")
			$sWhere .= " AND Pineapple_ID = '".$input['Pineapple_ID']."'";
		else
			$sWhere .= " WHERE Pineapple_ID = '".$input['Pineapple_ID']."'";

	if(isset($input['Station_SSID']) && $input['Station_SSID'] != "")
		if($sWhere != "")
			$sWhere .= " AND Station_SSID = '".$input['Station_SSID']."'";
		else
			$sWhere .= " WHERE Station_SSID = '".$input['Station_SSID']."'";		

	if(isset($input['Station_MAC']) && $input['Station_MAC'] != "")
		if($sWhere != "")
			$sWhere .= " AND Station_MAC = '".$input['Station_MAC']."'";
		else
			$sWhere .= " WHERE Station_MAC = '".$input['Station_MAC']."'";	
	
	if(isset($input['Date_Start']) && $input['Date_Start'] != "")
		if($sWhere != "")
			$sWhere .= " AND DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') >= '".$input['Date_Start']."'";
		else
			$sWhere .= " WHERE DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') >= '".$input['Date_Start']."'";	
	
	if(isset($input['Date_End']) && $input['Date_End'] != "")
		if($sWhere != "")
			$sWhere .= " AND DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') <= '".$input['Date_End']."'";
		else
			$sWhere .= " WHERE DATE_FORMAT(`Data_Timestamp`,'%Y-%m-%d') <= '".$input['Date_End']."'";		
	/**
	 * SQL queries
	 * Get data to display
	 */
	$aQueryColumns = array();
	foreach ($aColumns as $col) {
	    if ($col != ' ') {
	        $aQueryColumns[] = $col;
	    }
	}
 
	$sQuery = "
	    SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
	    FROM `".$sTable."`".$sWhere.$sOrder.$sLimit;

		$rResult = $mysqli->query( $sQuery ) or die($mysqli->error."<br/>".$sQuery);
		  
	// Data set length after filtering
	$sQuery = "SELECT FOUND_ROWS()";
	$rResultFilterTotal = $mysqli->query( $sQuery ) or die($mysqli->error);
	list($iFilteredTotal) = $rResultFilterTotal->fetch_row();
 
	// Total data set length
	$sQuery = "SELECT COUNT(`".$sIndexColumn."`) FROM `".$sTable."`";
	$rResultTotal = $mysqli->query( $sQuery ) or die($mysqli->error);
	list($iTotal) = $rResultTotal->fetch_row();
  
  
	/**
	 * Output
	 */
	$output = array(
	    "sEcho"                => intval($input['sEcho']),
	    "iTotalRecords"        => $iTotal,
	    "iTotalDisplayRecords" => $iFilteredTotal,
	    "aaData"               => array(),
	);
  
	while ( $aRow = $rResult->fetch_assoc() ) {
	    $row = array();
	    for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
	        if ( $aColumns[$i] == 'version' ) {
	            // Special output formatting for 'version' column
	            $row[] = ($aRow[ $aColumns[$i] ]=='0') ? '-' : $aRow[ $aColumns[$i] ];
	        } elseif ( $aColumns[$i] != ' ' ) {
	            // General output
	            $row[] = $aRow[ $aColumns[$i] ];
	        }
	    }
	    $output['aaData'][] = $row;
	}
  
	echo json_encode( $output );

?>