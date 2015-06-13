<?php

function ubd_menu_print_javascript()
{
	echo "<link rel=stylesheet href=\"css/styles.css\">\n";
	
//	echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js\">\n</script>\n";
	echo "<script src=css/jquery.min.js>\n</script>\n";
	ubd_menu_body_script();
}

function ubd_menu_body_script()
{
	echo "<script type=\"text/javascript\">\n\n";
	echo "$(function() {\n\t";
	echo "var menu_ul = $('.menu > li > ul'),\n\t";
	echo "menu_a  = $('.menu > li > a');\n\t";
	    
	echo "menu_ul.hide();\n\t";
	
	echo "menu_a.click(function(e) {\n\t";
	//echo "if (child.parent().attr(\"item4\")) {\n\t";
	//echo "e.preventDefault(); \n\t}";
	echo "if(!$(this).hasClass('active')) {\n\t\t";
	echo "menu_a.removeClass('active');\n\t\t";
	echo "menu_ul.filter(':visible').slideUp('normal');\n\t\t";
	echo "$(this).addClass('active').next().stop(true,true).slideDown('normal');\n\t\t";
	echo "        } else {\n\t\t";
	echo "            $(this).removeClass('active');\n\t\t";
	echo "            $(this).next().stop(true,true).slideUp('normal');\n\t\t";
	echo "        }\n\t\t";
	echo "});\n\t";
	
	echo "});\n\n\n";
	echo "</script>\n";
}

// ============================================
//	PRINT EDIT/ADD FORM - BAND MENU
// ============================================
function ubd_print_menu_item($debug, $bandID, $item, $URL, $target, $label, $spanMsg, $withSubMenues)
{
	if ($spanMsg)
		$span = "<span>$spanMsg</span>";
//	if($item != "item4")
		echo "\t\t\t<li class=$item><a href=$URL target=$target$bandID>$label $span</a>";
//	else // with submenu
//		echo "\t\t\t<li class=$item><a href=\"\">$label $span</a>";
//	echo "\t\t\t<li class=subitem1><a href=$URL target=$target$bandID>$label $span</a>";
	if (!$withSubMenues) echo "</li>\n";
	else echo "\n\t\t\t<ul>";
}

function ubd_print_submenu_item($debug, $bandID, $item, $URL, $target, $label, $spanMsg)
{
	if ($spanMsg)
		$span = "<span>$spanMsg</span>";
	//echo "\t\t\t<li class=$item><a href=\"http://192.168.2.22/band1test/$URL\" target=$target>$label $span</a></li>\n";
	echo "<li class=subitem1><a href=$URL target=$target$bandID>$label $span</a>";

}

function ubd_menu_print($debug, $bandID, $bandMgmtPHP, $lyricsPHP)
{
//	if ($debug) up_error("ubd_menu_print_form: ENTER");
	if ($bandMgmtPHP == "" ||  $lyricsPHP == "")
	{
		up_warning("ubd_menu_print: targetPHP not given. Using default menues");
		$bandMgmtPHP 	= "ub_test.php";
		$lyricsPHP		= "index.php";
	}
	// START
	echo "<div id=\"wrapper\">\n\n";
	echo "<ul class=\"menu\">\n\n";
	// ============== START MENU
	// HOME
	ubd_print_menu_item($debug, $bandID, "item1", "$bandMgmtPHP?bandID=$bandID&action=view_start_page", "bandframe", "Home" , "News!", 0); //$bandID
	// SONGS
	$debug = 0;
	$nofNextRehersal = uly_count_lyrics($debug, $bandID, 3);
	$nofRehersed = uly_count_lyrics($debug, $bandID, 0);
//	echo "$nofNextRehersal";
	$sum = $nofNextRehersal + $nofRehersed; 
	$nofProposed = uly_count_lyrics($debug, $bandID, 1);
	$nofGigSongs = 0; // uly_count_lyrics($debug, $bandID, 1);
	$sumAll = $sum + $nofProposed;
	ubd_print_menu_item($debug, $bandID, "item4", "#", "hiddenarea", "Låtar" , $sum, 1); //$bandID
	
//	up_uls();  // START OF Submenus
	ubd_print_submenu_item($debug, $bandID, $item, "$lyricsPHP?bandID=$bandID&action=view_next_rehersal_songs", "bandframe", "Nästa rep" , $nofNextRehersal); //$bandID
	if($nofGigSongs > 0)
	ubd_print_submenu_item($debug, $bandID, $item, "$lyricsPHP?bandID=$bandID&action=view_next_gig_songs", "bandframe", "Nästa spelning" , $nofGigSongs); //$bandID
	ubd_print_submenu_item($debug, $bandID, $item, "$lyricsPHP?bandID=$bandID&action=view_rehersed_songs", "bandframe", "Inrepade" , $nofRehersed); //$bandID
	ubd_print_submenu_item($debug, $bandID, $item, "$lyricsPHP?bandID=$bandID&action=view_proposed_songs", "bandframe", "Förslag" , $nofProposed); //$bandID
	ubd_print_submenu_item($debug, $bandID, $item, "$lyricsPHP?bandID=$bandID&action=view_all_lyrics", "bandframe", "Alla" , $sumAll); //$bandID
	ubd_print_submenu_item($debug, $bandID, $item, "$lyricsPHP?bandID=$bandID&action=add_lyrics", "bandframe", "Lägg till..." , ""); //$bandID
	$nofMainDbLyrics = uly_count_lyrics_main_db($debug);
	ubd_print_submenu_item($debug, $bandID, $item, "$lyricsPHP?bandID=$bandID&action=import_lyrics", "bandframe", "Importera..." , $nofMainDbLyrics); //$bandID
	up_ule();	// END OF Submenus
	echo "</li>";
	// Låtlistor
	$debug = 0;
	$nofRepertoirs = ubd_rp_calc_repertoirs($debug, $bandID);
	ubd_print_menu_item($debug, $bandID, "item5", "$bandMgmtPHP?bandID=$bandID&action=view_repertoirs", "bandframe", "Låtlistor", $nofRepertoirs, 0);
	// REP
	$debug = 0;
	$nofRehersals = ubd_rh_calc_future_rehersals($debug, $bandID);
	ubd_print_menu_item($debug, $bandID, "item5", "$bandMgmtPHP?bandID=$bandID&action=view_rehersals", "bandframe", "Rep", $nofRehersals, 0);
	// SPELNINGAR
	$nofGigs= ubd_gi_calc_future_gigs($debug, $bandID);
	//echo "nof gigs = $nofGigs";
	ubd_print_menu_item($debug, $bandID, "item5", "$bandMgmtPHP?bandID=$bandID&action=view_gigs", "bandframe", "Spelningar", $nofGigs, 0);
	// VIDEOS
	$nofVideos = ubd_vi_calc_videos($debug, $bandID);
	ubd_print_menu_item($debug, $bandID, "item2", "$bandMgmtPHP?bandID=$bandID&action=view_band_videos", "bandframe", "Videos" , $nofVideos, 0); //$bandID
	/*
	// IMAGES
	ubd_print_menu_item($debug, $bandID, "item3", "$bandMgmtPHP?bandID=$bandID&action=view_images", "bandframe", "Bilder" , "Antal", 0); //$bandID
	*/
	// ============== END MENU
	echo "</ul>\n";
	// END
	echo "</div>";
	//ubd_menu_body_script();
//	if ($debug) up_error("ubd_menu_print_form: EXIT");
}

// ============================================
//	VIEW - SOLO PRACTICE
// ============================================

function ubd_menu_view($debug, $bandID, $soloID)// If rehersalID == "" => View All
{
	if ($debug) up_error("ubd_menu_view: ENTER");

	if ($debug) up_error("ubd_menu_view: EXIT");
}

// ============================================
//	ADD - SOLO PRACTICE
// ============================================

function ubd_menu_add($debug, $bandID, $title, $artist  )
{
	if ($debug) up_error("ubd_menu_add: ENTER");

	if ($debug) up_error("ubd_menu_add: EXIT");
}

// ============================================
//	UPDATE - SOLO PRACTICE
// ============================================

function ubd_menu_update( $debug, $bandID, $soloID, $date, $time, $notes, $location )	
{
	if ($debug) up_error("ubd_menu_update: ENTER");

	if ($debug) up_error("ubd_menu_update: EXIT");
}

// ============================================
//	DELETE - SOLO PRACTICE
// ============================================

function ubd_menu_delete($debug, $bandID, $soloID)
{
	if ($debug) up_error("ubd_menu_delete: ENTER");

	if ($debug) up_error("ubd_menu_delete: EXIT");
}	

?>