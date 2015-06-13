<?php

// ============================================
//	 GET DATA (BAND MEMBER)
// ============================================
function ubd_get_nof_bandmembers($debug, $bandID)
{
	$sql = "SELECT COUNT(userID) AS nofBandMembers FROM bands.band_members WHERE bandID='$bandID'";

	$result = mysql_query($sql);

    $row = mysql_fetch_array ($result);
 
    $nofBandMembers = $row[nofBandMembers];	
  //  up_note("ubd_get_nof_musicians(): Returning '$nofBandMembers'");
	return $nofBandMembers;
}
// Returns the i'th band member. (starting at 0)
function ubd_get_ith_bandmember($debug, $bandID, $iThMember)
{
	$sql = "SELECT * FROM bands.band_members WHERE bandID='$bandID' ORDER BY userID";

	$result = mysql_query($sql);
	$i = 0;
	while ($row = mysql_fetch_array ($result))
	{	
		if ($i == $iThMember)	
			return $row[userID];
		$i++;
	}
	return -1; // not found
}

function ubd_get_band_member_data($bandMemberID, &$firstName, &$lastName, &$streetAddress, &$postalCode, &$city, &$phoneWork, &$phoneHome , &$phoneCellular, &$emPrivate, &$emWork, &$picture, &$logo)
{
//	if($debug) echo "<li> ubd_get_band_member_data($bandMemberID) : ENTER";
	$sql = "SELECT * FROM bands.band_member WHERE id='$bandMemberID'";
  	$result = mysql_query ($sql);

	while ($row = mysql_fetch_array ($result))
	{	
	 	$firstName		= $row[firstName];
	 	$lastName		= $row[lastName];
		$streetAddress	= $row[streetAddress];
		$postalCode 	= $row[postalCode];
		$city			= $row[city];
		$phoneWork 		= $row[phoneWork];
		$phoneHome 		= $row[phoneHome];
		$phoneCellular 	= $row[phoneCellular];
		$emPrivate 		= $row[emailAddressPrivate];
		$emWork 		= $row[emailAddressWork];
		$picture 		= $row[picture];
		$logo 		= $row[logo];
//		if($debug) echo "<li> ubd_get_band_member_data($bandMemberID) : RETURNING data of '$firstName $lastName'";
		return;
	}	

} 

function ubd_bm_is_bandmember($debug, $userID, $bandID)
{
	$query = "SELECT * FROM bands.band_members WHERE bandID='$bandID' AND userID='$userID'";
	
	$result = mysql_query($query);

    while ($row = mysql_fetch_array ($result))
    {
    	if($debug) up_info("ubd_bm_is_bandmember($debug, $userID, $bandID): TRUE");
    	return TRUE;
    }
    if($debug) up_info("ubd_bm_is_bandmember($debug, $userID, $bandID): FALSE");
	return FALSE;
}

// ==== MUSICIANS (All!)
function ubd_get_nof_musicians()
{
	$sql = "SELECT COUNT(id) AS nofBandMembers FROM bands.band_member";

	$result = mysql_query($sql);

    $row = mysql_fetch_array ($result);
 
    $nofBandMembers = $row[nofBandMembers];	
  //  up_note("ubd_get_nof_musicians(): Returning '$nofBandMembers'");
	return $nofBandMembers;
}

// ALL known Musicians
function ubd_print_musicians_option_menu($debug, $selectedPerson, $tableColumn, $colSpan, $bgcolor, $bandIDfilter)
{
	// up_select_header($debug, $targetPhp, $name, $actionOnValueChanged);
	
	$onChangeFunctionName = "handleOptionMenuSelection()";
	up_select_header($debug, $targetPhp, "bandMember", "bmID", $callItself, $onChangeFunctionName, $tableColumn, $colSpan, $bgcolor);

	$sql = "SELECT * FROM bands.band_member ORDER BY lastName";
  	$result = mysql_query ($sql);

		up_select_menu_item($debug, "Befintliga musiker", -1, $disabled);
	while ($row = mysql_fetch_array ($result))
	{	

		$firstName 		= mb_convert_encoding($row[firstName], 'UTF-8', 'macintosh');
		$firstName 		= iconv('UTF-8', 'macintosh', $firstName);
		$lastName 		= mb_convert_encoding($row[lastName], 'UTF-8', 'macintosh');
		$lastName 		= iconv('UTF-8', 'macintosh', $lastName);
		up_select_menu_item($debug, "$firstName $lastName", $row[id], $disabled);
	}
	up_select_footer($debug, $tableColumn);
}

// BAND members
function ubd_print_band_member_option_menu($debug, $selectedPerson, $tableColumn, $colSpan, $bgcolor, $bandIDfilter)
{
	// up_select_header($debug, $targetPhp, $name, $actionOnValueChanged);
	
	$onChangeFunctionName = "handleOptionMenuSelection()";
	up_select_header($debug, $targetPhp, "userID", "bmID", $callItself, $onChangeFunctionName, $tableColumn, $colSpan, $bgcolor);

	$sql = "SELECT * FROM bands.band_member ORDER BY lastName";
  	$result = mysql_query ($sql);

	up_select_menu_item($debug, "Välj bandmedlem", -1, $disabled);
	while ($row = mysql_fetch_array ($result))
	{	
		if(ubd_bm_is_bandmember($debug, $row[id], $bandIDfilter))
		{
			$firstName 		= mb_convert_encoding($row[firstName], 'UTF-8', 'macintosh');
			$firstName 		= iconv('UTF-8', 'macintosh', $firstName);
			$lastName 		= mb_convert_encoding($row[lastName], 'UTF-8', 'macintosh');
			$lastName 		= iconv('UTF-8', 'macintosh', $lastName);
			up_select_menu_item($debug, "$firstName $lastName", $row[id], $disabled);
		}
	}
	up_select_footer($debug, $tableColumn);
}

function ubd_print_musicians() // DEPRECATED
{
	up_error("ubd_print_musicians() - THIS FUNCTION SHOULD NOT BE USED.");
//	$text = "A strange string to pass, maybe with some å, ä, ö characters."; 

	ubd_get_band_member_data(3, &$firstName, &$lastName, &$streetAddress, &$postalCode, &$city, &$phoneWork, &$phoneHome , &$phoneCellular, &$emPrivate, &$emWork, &$picture, &$logo);
//
	foreach(mb_list_encodings() as $chr){ 
        echo mb_convert_encoding($firstName, 'UTF-8', $chr)." : ".$chr."<br>";    
        echo mb_convert_encoding($lastName, 'UTF-8', $chr)." : ".$chr."<br>";    
	 } 	
}

// ============================================
//	PRINT EDIT/ADD FORM  - BAND MEMBER
// ============================================

function ubd_bm_print_form($debug, $bandID, $edit, $bandMemberID, $targetPHP)
{
	if ($debug) up_error("ubd_bm_print_form: ENTER");

	if($bandID == ""||  $targetPHP == "")
	{
		up_error("ubd_bm_print_form($debug, $bandID, $edit, $bandMemberID, $targetPHP): Invalid NULL parameter.");
		return;
	}

	up_form_header($targetPHP, $target, "band_member_form" );
	up_table_header(0);
	up_table_column("Bandmedlem:", 1);
	ubd_print_musicians_option_menu($debug, $bandMemberID, 1, 2, $bgcolor);
// function up_image($image, $width, $height, $tableColumn, $noResize, $maxWidth, $maxHeight, $rowSpan, $valign)
	$rowSpan = 10;
	$colSpan = 1;
//	up_form_footer();
//	up_form_header($targetPHP);
	up_php_param("action","add_bandmember", "hidden");
	up_php_param("bandID","$bandID", "hidden");
	up_php_param("bandMemberID","$bandMemberID", "hidden");
	up_table_column("", 1);
	up_image("../common/music/images/MB.PNG", $width, "300", 1, $noResize, $maxWidth, $maxHeight, $rowSpan, $valign, $colSpan, "image");
	$rowSpan = 1;
	up_new_table_row();
	// First Name
	up_table_column("Förnamn:", 1);
	up_php_param("firstName","", "", 1, 15, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, 4, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	up_new_table_row();
	// First Name
	up_table_column("Efternamn:", 1);
	up_php_param("lastName","", "", 1, 15, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, 4, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	up_new_table_row();
	// ADDRESS
	up_table_column("Gatuadress:", 1);
	up_php_param("streetAddress","", "", 1, 25, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, 4, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
//  up_php_param($param, $value, $type, $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, 4, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked)
	up_new_table_row();
	// POST ADDRESS
	up_table_column("Postadress:", 1);
	up_php_param("postalCode","", "", 1, 8);
	// CITY
	up_table_column("Stad:", 1);
	up_php_param("city","", "", 1, 20);
	up_new_table_row();
	// PHONE WORK
	up_table_column("Telefon (arbete):", 1);
	up_php_param("phoneWork","", "", 1, 15, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, 3);
	up_new_table_row();
	// PHONE HOME
	up_table_column("Telefon (hem):", 1);
	up_php_param("phoneHome","", "", 1, 15, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, 3);
	up_new_table_row();
	// PHONE CELLULAR
	up_table_column("Telefon (mobil):", 1);
	up_php_param("phoneCellular","", "", 1, 15, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, 3);
	up_new_table_row();
	// EMAIL WORK
	up_table_column("E-post (arbete):", 1);
	up_php_param("emailWork","", "", 1, 50, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, 4);
	up_new_table_row();
	// EMAIL HOME
	up_table_column("E-post (privat):", 1);
	up_php_param("emailPrivate","", "", 1, 50, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, 4);
	up_new_table_row();
	
	// INSTRUMENTS CHECKS
	$tableColumn = 1;
	echo "<td colspan=5>\n\t";
	up_php_param("checkBoxes[]", "Vocal", "checkbox", !$tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	echo "Vocals";
	up_php_param("checkBoxes[]", "Bass", "checkbox", !$tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	echo "Bass";
	up_php_param("checkBoxes[]", "Drums", "checkbox", !$tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	echo "Drums";
	up_php_param("checkBoxes[]", "Guitar", "checkbox", !$tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	echo "Guitar";
	up_php_param("checkBoxes[]", "Keyboard", "checkbox", !$tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	echo "Keyboard";
	up_php_param("checkBoxes[]", "Saxophone", "checkbox", !$tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	echo "Saxophone";
	echo "</td>\n\t";
	up_new_table_row();
	// SAVE and CANCEL buttons
	// ub_save($URL, $height, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor)
	ub_save($URL, $height, $target, $title, 1, $valign, $columnWidth, "right", $bgcolor);
	up_form_footer();
//  ub_video($URL, $height, $target, $title, $tableColumn, $right, $valign, $columnWidth
	ub_cancel("$targetPHP?action=view_start_page&bandID=$bandID", $height, $target, $title, 1, 0, "baseline");
	up_table_footer();

	if ($debug) up_error("ubd_bm_print_form: EXIT");
}

function ubd_bm_add_to_band($debug, $bandID, $bandMemberID, $instruments)
{
	if ($debug) echo "ubd_bm_add_to_band($debug, $bandID, $bandMemberID, $instruments)";
	if ($bandID == ""|| $bandMemberID == "" || $bandMemberID == -1)
	{
		up_error("ubd_bm_add_to_band: Invalid NULL parameter(s).");	
		return;
	}
	$cnt = count($instruments);
	if ($debug) echo "<li> #instruments = $cnt";
	
	for($i=0; $i < count($instruments); $i++)
	{
		// $debug = 1;
    	if($debug) echo "Selected " . $instruments[$i] . "<br/>";
    	switch ($instruments[$i])
    	{
    	case "Bass":		$bass = 1;		if ($debug) up_note("Bass"); break;
    	case "Drums":		$drums = 1;		if ($debug) up_note("Drums"); break;
    	case "Guitar":		$guitar = 1;	if ($debug) up_note("Guitar"); 	break;
    	case "Keyboard":	$keyboard = 1;	if ($debug) up_note("Keyboard"); 	break;
    	case "Saxophone":	$saxophone = 1;	if ($debug) up_note("Saxophone"); 	break;
    	case "Vocal":		$vocal = 1;		if ($debug) up_note("Vocal"); break; 	
    	}	
	}	
	if ($bandID)
	{
		$variables = "bandID"; 
		$parameters = "'$bandID'"; 
	}
	if ($bandMemberID)
	{
		$variables = $variables . ", userID"; 
		$parameters = $parameters . ", '$bandMemberID'"; 
	}
	if ($vocal)
	{
		$variables = $variables . ", vocal"; 
		$parameters = $parameters . ", '$vocal'"; 
	}
	if ($guitar)
	{
		$variables = $variables . ", guitar"; 
		$parameters = $parameters . ", '$guitar'"; 
	}	
	if ($bass)
	{
		$variables = $variables . ", bass"; 
		$parameters = $parameters . ", '$bass'"; 
	}
	if ($drum)
	{
		$variables = $variables . ", drum"; 
		$parameters = $parameters . ", '$drum'"; 
	}
	if ($keyboard)
	{
		$variables = $variables . ", keyboard"; 
		$parameters = $parameters . ", '$keyboard'"; 
	}
	if ($saxophone)
	{
		$variables = $variables . ", saxophone"; 
		$parameters = $parameters . ", '$saxophone'"; 
	}

    $sql = "INSERT INTO bands.band_members ( $variables ) VALUES ( $parameters )";
    $result = mysql_query ($sql);	
      
    if ($result == FALSE)
    {
		up_error( "ubd_bm_add_to_band: Unable to add the post.");
		$msg = mysql_error();
		if($msg)	up_error($msg);
    } 	
	if ($debug) up_note("ubd_bm_add_to_band: EXIT");	
}

// ============================================
//	ADD - BAND MEMBER or rather MUSICIAN
// ============================================

function ubd_bm_add($debug, $bandID, $bandMemberID, $firstName, $lastName, $streetAddress, $postalCode, $city, $phoneWork, $phoneHome, $phoneCellular, $emailWork, $emailPrivate, $instruments  )
{
	if ($debug) up_note("ubd_bm_add: ENTER");
	if($bandMemberID == "" || $bandMemberID == -1) 
	{
		up_note("ubd_bm_add: New Musician");
	}
	else
	{
		if ($debug) up_note("ubd_bm_add: Old Musician ($firstName, $lastName)...updating data.");
		ubd_bm_update( $debug, $bandID, $bandMemberID, $firstName, $lastName, $streetAddress, $postalCode, $city, $phoneWork, $phoneHome, $phoneCellular, $emailWork, $emailPrivate  );
		ubd_bm_add_to_band($debug, $bandID, $bandMemberID, $instruments);
		return;
	}
	$bandMemberID	= uly_getUID("bands.band_member", "id");

	if ($bandID == ""|| $bandMemberID == ""|| $firstName == "" || $lastName == "")
	{
		up_error("Invalid NULL parameter(s). bID:$bandID != ''|| bmID:$bandMemberID != ''|| fname:$firstName != ''|| lname:$lastName != ''");	
		return;
	}
	if ($bandMemberID)
	{
		$variables = "id"; 
		$parameters = "'$bandMemberID'"; 
	}
	if ($firstName)
	{
		$variables = $variables . ", firstName"; 
		$parameters = $parameters . ", '$firstName'"; 
	}
	if ($lastName)
	{
		$variables = $variables . ", lastName"; 
		$parameters = $parameters . ", '$lastName'"; 
	}	
	if ($streetAddress)
	{
		$variables = $variables . ", streetAddress"; 
		$parameters = $parameters . ", '$streetAddress'"; 
	}
	if ($city)
	{
		$variables = $variables . ", city"; 
		$parameters = $parameters . ", '$city'"; 
	}
	if ($postalCode)
	{
		$variables = $variables . ", postalCode"; 
		$parameters = $parameters . ", '$postalCode'"; 
	}
	if ($phoneWork)
	{
		$variables = $variables . ", phoneWork"; 
		$parameters = $parameters . ", '$phoneWork'"; 
	}
	if ($phoneHome)
	{
		$variables = $variables . ", phoneHome"; 
		$parameters = $parameters . ", '$phoneHome'"; 
	}
	if ($phoneCellular)
	{
		$variables = $variables . ", phoneCellular"; 
		$parameters = $parameters . ", '$phoneCellular'"; 
	}
	if ($emailPrivate)
	{
		$variables = $variables . ", emailAddressPrivate"; 
		$parameters = $parameters . ", '$emailPrivate'"; 
	}
	if ($emailWork)
	{
		$variables = $variables . ", emailAddressWork"; 
		$parameters = $parameters . ", '$emailWork'"; 
	}

    $sql = "INSERT INTO bands.band_member ( $variables ) VALUES ( $parameters )";
    $result = mysql_query ($sql);	
      
    if ($result == FALSE)
    {
		up_error( "ubd_bm_add: Unable to add the band member.");
		$bandMemberID = -1;
		$msg = mysql_error();
		if($msg)	up_error($msg);
    } 	
    if ($bandMemberID > 0)
		ubd_bm_add_to_band($debug, $bandID, $bandMemberID, $instruments);
	if ($debug) up_note("ubd_bm_add: EXIT");
	return $bandMemberID;
}

// ============================================
//	VIEW - BAND MEMBER
// ============================================

// DEPRECATED - USE 
function ubd_bm_view($debug,  $bandID, $bandMemberID) // If $bandMemberID == "" => View All
{
	up_error("Do not use this function (ubd_bm_view)- USE view_start_page instead.");
	if ($debug) up_error("ubd_bm_view: ENTER");
	ubd_get_band_member_data($bandMemberID, &$firstName, &$lastName, &$streetAddress, &$postalCode, &$city, &$phoneWork, &$phoneHome , &$phoneCellular, &$emPrivate, &$emWork, &$picture, &$logo);

	up_table_header();
	// IMAGE - Image ROW SPAN = 
	
	// NAME (first + last)
	up_table_column("Namn:");
	
	up_new_table_row();
	// ADRESS
		
	up_new_table_row();
	up_table_footer();

	if ($debug) up_error("ubd_bm_view: EXIT");
}

function ubd_bm_exist($debug, $bandMemberID)
{
	// $debug = 1;
	if ($debug) up_info("ubd_bm_exist($debug, $bandMemberID): ENTER");
	
	$returnValue = FALSE;
	
	$query = "SELECT * FROM bands.band_member WHERE id='$bandMemberID'";
	
	$result = mysql_query($query);

    while ($row = mysql_fetch_array ($result))
    {
    	$returnValue = TRUE;
	}
	
	if ($debug) up_info("ubd_bm_exist($debug, $bandMemberID): RETURNING $returnValue. ");
	return $returnValue;
}

// ============================================
//	UPDATE - BAND MEMBER
// ============================================

function ubd_bm_update( $debug, $bandID, $bandMemberID, $firstName, $lastName, $streetAddress, $postalCode, $city, $phoneWork, $phoneHome, $phoneCellular, $emailWork, $emailPrivate, $picture, $logo  )	
{
	if ($bandMemberID == "")
	{
		up_error("ubd_bm_update: Invalid band member ID (NULL).");
		return;
	}
	else if (!ubd_bm_exist($debug, $bandMemberID))
	{
		up_error("The band member does not exist.");
		return;
	
	}
	if ($debug) up_error("ubd_bm_update: ENTER");
//	if ($bandID)
	{
//		$variables = "bandID='$bandID'";  // NOT CONNECTED TO SPECIFIC BAND!
	}
	if ($bandMemberID)
	{
		$variables = $variables . "id='$bandMemberID'"; 
	}
	if ($firstName)
	{
		$variables = $variables . ", firstName='$firstName'"; 
	}
	if ($lastName)
	{
		$variables = $variables . ", lastName='$lastName'"; 
	}	
	if ($streetAddress)
	{
		$variables = $variables . ", streetAddress='$streetAddress'"; 
	}
	if ($postalCode)
	{
		$variables = $variables . ", postalCode='$postalCode'"; 
	}
	if ($city)
	{
		$variables = $variables . ", city='$city'"; 
	}
	if ($phoneWork)
	{
		$variables = $variables . ", phoneWork='$phoneWork'"; 
	}
	if ($phoneHome)
	{
		$variables = $variables . ", phoneHome='$phoneHome'"; 
	}
	if ($phoneCellular)
	{
		$variables = $variables . ", phoneCellular='$phoneCellular'"; 
	}
	if ($emailWork)
	{
		$variables = $variables . ", emailAddressWork='$emailWork'"; 
	}
	if ($emailPrivate)
	{
		$variables = $variables . ", emailAddressPrivate='$emailPrivate'"; 
	}
	if ($picture)
	{
		$variables = $variables . ", picture='$picture'"; 	
	}
	if ($logo)
	{
		$variables = $variables . ", logo='$logo'"; 
	}

    $sql = "UPDATE bands.band_member SET $variables WHERE id='$bandMemberID'";
    $result = mysql_query ($sql);	
      
    if ($result == FALSE)
    {
		up_error( "ubd_bm_update: Unable to update the post.");
		up_error("SQL:$sql");
		$msg = mysql_error();
		if($msg)	up_error($msg);
    } 
	if ($debug) up_error("ubd_bm_update: EXIT");
}

// ============================================
//	DELETE - BAND MEMBER
// ============================================

function ubd_bm_delete($debug, $bandID, $bandMemberID)
{
	if ($debug) up_error("ubd_bm_delete: ENTER");

	$sql = "DELETE FROM bands.band_members WHERE bandID='$bandID' AND userID='$bandMemberID'";

    $result = mysql_query ($sql);	

	if ($debug) echo "sql:$sql ";
        
    if ($result == FALSE)
    {
		up_error("ERROR: ubd_bm_delete: Unable to delete the post.");
		$msg = mysql_error();
		if ($msg)
			up_error($msg);
		return -1;
    } 	
	if ($debug) up_error("ubd_bm_delete: EXIT");
}	

?>