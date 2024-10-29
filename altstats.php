<?php
/*
Plugin Name: AltStats
Plugin URI: http://altmuzo.net/press/?page_id=322
Description: AltStats provides an easy-to-read summary of visitor activity on your Wordpress site.
Version: 1.0
Author: Nicholas Jensen
Author URI: http://altmuzo.net/press/?page_id=2
*/

/*  Copyright 2012  Nicholas Jensen  (email : kinlaso@altmuzo.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


// This adds the item to the dashboard admin menu.
function NJ_registerAdminPanel() {
	if (function_exists('add_dashboard_page')) {
		
		//http://codex.wordpress.org/Roles_and_Capabilities  |  manage_options publish_pages publish_posts edit_posts read
		if (current_user_can('manage_options'))  {
			add_dashboard_page('AltStats', 'AltStats', 4, basename(__FILE__), 'NJ_showStats');
		};

	};
};

// This adds the action hook for function.
add_action('admin_menu', 'NJ_registerAdminPanel');

function NJ_showStats() {
		
	//http://codex.wordpress.org/Roles_and_Capabilities  |  manage_options publish_pages publish_posts edit_posts read
	if (current_user_can('manage_options'))  {
		
		global $wpdb;
		if ($wpdb) {
																
			$iDBtableName = $wpdb->prefix . "NJ_FastStats";	
						
			////THIS deletes old (more than a day) vizitor entries
			//$iDays2Save = 7;// <-- visitor entries older than this (in days) will be deleted.
			//$iQryArySrt = time() - ($iDays2Save * 86400);
			//$wpdb->escape($iQryArySrt);
			//$wpdb->query("DELETE FROM {$iDBtableName} WHERE `CrtdTmStmp` < {$iQryArySrt} AND `EntryType` IS NULL");

			$iNowYMDfull = mktime(date("H"), date("i"), date("s"), date("n"), date("j"), date("Y"));
			$iNowYMDfull = $wpdb->escape($iNowYMDfull);
			$iNowYMD = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
			$iNowYMD = $wpdb->escape($iNowYMD);
			
			$iWkTmpSrt = $iNowYMD;
			//$iWkTmpEnd = $iNowYMD - 518400;
			//$iWkTmpEnd = $iNowYMD - 777600;
			//$iWkTmpEnd = $iNowYMD - 1036800;
			$iWkTmpEnd = $iNowYMD - 1296000;
										
			echo '<div class="wrap">';
			
			echo '<h2 style="font-weight:bold;">AltStats</h2>';
			
			// THIS starts the daying hits breakdown
			echo '<table cellpadding="2" cellspacing="0" border="1">';
			echo '<tr>';
			
				$iTDcntr = 1;
				while ($iWkTmpSrt > $iWkTmpEnd) {
					
					echo '<td valign="top">';
						
					echo '<div style="margin:5px;font-weight:bold;">' . date("Y.m.d", $iWkTmpSrt) . '</div>';
					//echo '<div style="float:left;border:solid red 1px;width:400px;">naj: ' . date("Y.m.d", $iWkTmpSrt);
					
					$iDyTotal = 0;
					$iCNTR = 0;
					while($iRsltAry = $wpdb->get_row("SELECT * FROM {$iDBtableName} WHERE `EntryType` = 'T1' AND `CrtdTmStmp` = {$iWkTmpSrt} ORDER BY `Counter` DESC", ARRAY_A, $iCNTR)) {

						if ($iRsltAry['wp_title']) {$iTtleYN = $iRsltAry['wp_title'];} else {$iTtleYN = $iRsltAry['siteurl'];};
						if (strlen($iTtleYN) > 30) {$iTtleYN = substr($iTtleYN, 0, 30) . '...';};
						
						if ($iRsltAry['Counter']) {$iDyTotal = $iDyTotal + $iRsltAry['Counter'];};
						if ($iCNTR > 0) {echo '<br />';};
						echo htmlspecialchars($iRsltAry['Counter'], ENT_QUOTES);
						if ($iRsltAry['siteurl']) {
							if (strpos(strtolower($iRsltAry['siteurl']), '?p=') != FALSE) {echo " ART";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&p=') != FALSE) {echo " ART";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?tag=') != FALSE) {echo " TAG";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&tag=') != FALSE) {echo " TAG";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?cat=') != FALSE) {echo " CAT";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&cat=') != FALSE) {echo " CAT";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?page_id=') != FALSE) {echo " PAGE";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&page_id=') != FALSE) {echo " PAGE";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?author=') != FALSE) {echo " AUTHOR";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&author=') != FALSE) {echo " AUTHOR";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?attachment_id=') != FALSE) {echo " ATTACH";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&attachment_id=') != FALSE) {echo " ATTACH";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?m=') != FALSE) {echo " DATE";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&m=') != FALSE) {echo " DATE";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?feed=') != FALSE) {echo " FEED";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&feed=') != FALSE) {echo " FEED";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?paged=') != FALSE) {echo " PAGED";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&paged=') != FALSE) {echo " PAGED";};
							echo ' <a target="_blank" href="' . htmlspecialchars($iRsltAry['siteurl'], ENT_QUOTES) . '">' . htmlspecialchars($iTtleYN, ENT_QUOTES) . '</a>';
						} else {
							echo ' ' . htmlspecialchars($iTtleYN, ENT_QUOTES);
						};

						//print "<pre>";print_r($iRsltAry);print "</pre>";
					  
						$iCNTR++;
					};//END while
					
					echo '<div style="margin:10px 0px 0px 10px;font-weight:bold;">Total Hits: ' . $iDyTotal . '</div>';
					
					echo '</td>';
					if ($iTDcntr == 3) {echo '</tr><tr>';$iTDcntr = 0;};
					//echo '</div>';
					
					$iWkTmpSrt = $iWkTmpSrt - 86400;
					$iTDcntr++;
					
					
				};//END while
				
			echo '</tr>';
			echo '</table>';
			
			//THIS starts the daily totals
					$iCNTR = 0;
					while($iRsltAry = $wpdb->get_row("SELECT * FROM {$iDBtableName} WHERE `EntryType` = 'T2' ORDER BY `CrtdTmStmp` DESC", ARRAY_A, $iCNTR)) {
						
						if ($iCNTR === 0) {
							echo '<div>';
							echo '<h3 style="font-weight:bold;">Daily Hits</h3>';
						};
							
						$iWhatYM = date("Y.m", $iRsltAry['CrtdTmStmp']);
						
						if ($iWhatYM != $iWhatYMlst) {
							echo '<div style="font-weight:bold;padding:5px;">' . date("Y F", $iRsltAry['CrtdTmStmp']) . '</div>';
							if ($iCNTR > 0) {
								echo '</div>';
							};
							echo '<div style="margin:0px 0px 0px 15px;">';
						};
						
						if (!$iRsltAry['Counter'] or $iRsltAry['Counter'] == NULL) {$iCounterDB = 0;} else {$iCounterDB = $iRsltAry['Counter'];};
						if ($iCounterDB == 1) {$iIsS = '';} else {$iIsS = 's';};

						if ($iCNTR > 0) {echo ', ';};
						echo date("jS", $iRsltAry['CrtdTmStmp']) . ': ' . $iCounterDB . " hit{$iIsS}";
						//print "<pre>";print_r($iRsltAry);print "</pre>";
					  
						$iWhatYMlst = $iWhatYM;
						$iCNTR++;
					};//END while
			if ($iCNTR > 0) {
				echo '</div></div>';
			};
			
			//THIS starts the referers
					$iCNTR = 0;
					while($iRsltAry = $wpdb->get_row("SELECT * FROM {$iDBtableName} WHERE `EntryType` = 'T4' ORDER BY `Counter` DESC", ARRAY_A, $iCNTR)) {
								
						if ($iCNTR === 0) {
							echo '<div style="clear:both;">';
							echo '<h3 style="font-weight:bold;">Referers</h3>';
							echo '<div style="margin:0px 0px 0px 15px;">';
						};
							
						if (!$iRsltAry['Counter'] or $iRsltAry['Counter'] == NULL) {$iCounterDB = 0;} else {$iCounterDB = $iRsltAry['Counter'];};
						
						if ($iCNTR > 0) {echo '<br />';};
						echo $iCounterDB . ' ' . $iRsltAry['HTTP_REFERER'];
						//print "<pre>";print_r($iRsltAry);print "</pre>";
					  
						$iWhatYMlst = $iWhatYM;
						$iCNTR++;
					};//END while
			if ($iCNTR > 0) {
				echo '</div></div>';
			};
			
			//THIS starts the Top Hits			
					$iCNTR = 0;
					while($iRsltAry = $wpdb->get_row("SELECT * FROM {$iDBtableName} WHERE `EntryType` = 'T3' ORDER BY `Counter` DESC", ARRAY_A, $iCNTR)) {

						if ($iCNTR === 0) {
							echo '<div style="clear:both;">';
							echo '<h3 style="font-weight:bold;">Top Hits</h3>';
							echo '<div style="margin:0px 0px 0px 15px;">';
						};
						
						if ($iRsltAry['wp_title']) {$iTtleYN = $iRsltAry['wp_title'];} else {$iTtleYN = $iRsltAry['siteurl'];};
						//if (strlen($iTtleYN) > 30) {$iTtleYN = substr($iTtleYN, 0, 30) . '...';};
						
						if ($iRsltAry['Counter']) {$iDyTotal = $iDyTotal + $iRsltAry['Counter'];};
						if ($iCNTR > 0) {echo '<br />';};
						echo htmlspecialchars($iRsltAry['Counter'], ENT_QUOTES);
						if ($iRsltAry['siteurl']) {
							if (strpos(strtolower($iRsltAry['siteurl']), '?p=') != FALSE) {echo " ART";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&p=') != FALSE) {echo " ART";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?tag=') != FALSE) {echo " TAG";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&tag=') != FALSE) {echo " TAG";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?cat=') != FALSE) {echo " CAT";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&cat=') != FALSE) {echo " CAT";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?page_id=') != FALSE) {echo " PAGE";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&page_id=') != FALSE) {echo " PAGE";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?author=') != FALSE) {echo " AUTHOR";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&author=') != FALSE) {echo " AUTHOR";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?attachment_id=') != FALSE) {echo " ATTACH";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&attachment_id=') != FALSE) {echo " ATTACH";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?m=') != FALSE) {echo " DATE";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&m=') != FALSE) {echo " DATE";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?feed=') != FALSE) {echo " FEED";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&feed=') != FALSE) {echo " FEED";};
							if (strpos(strtolower($iRsltAry['siteurl']), '?paged=') != FALSE) {echo " PAGED";};
							if (strpos(strtolower($iRsltAry['siteurl']), '&paged=') != FALSE) {echo " PAGED";};
							echo ' <a target="_blank" href="' . htmlspecialchars($iRsltAry['siteurl'], ENT_QUOTES) . '">' . htmlspecialchars($iTtleYN, ENT_QUOTES) . '</a>';
						} else {
							echo ' ' . htmlspecialchars($iTtleYN, ENT_QUOTES);
						};

						//print "<pre>";print_r($iRsltAry);print "</pre>";
					  
						$iCNTR++;
					};//END while
			if ($iCNTR > 0) {
				echo '</div></div>';
			};
					
			echo '<div style="margin:20px;clear:both;font-style:italic;">AltStats is a work in production by Kinlaso@altmuzo.net';
				
			echo '</div>';//END <div class="wrap">
				
		};//END if ($wpdb)

		
	};//END if (current_user_can('manage_options'))
		
		
};//END function NJ_showStats

function NJ_updateStats() {
	
	if (function_exists('NJ_checkInstall')) {
		
		NJ_checkInstall();
		
		// This will update if a user is not logged in.
		get_currentuserinfo();
		global $current_user;
		$iExstsUsr = 0;
		if ($current_user->user_login) {$iExstsUsr++;};
		if ($current_user->user_email) {$iExstsUsr++;};
		if ($current_user->user_firstname) {$iExstsUsr++;};
		if ($current_user->user_lastname) {$iExstsUsr++;};
		if ($current_user->display_name) {$iExstsUsr++;};
		if ($current_user->ID) {$iExstsUsr++;};
		
		if ($iExstsUsr < 1) {
					
			//print "<pre>";print_r($_SERVER);print "</pre>";
			//print "<pre>";print_r($GLOBALS);print "</pre>";
			
			global $wpdb;				
			if ($wpdb) {				
							
				$iNowTime = time();
				settype($iNowTime, 'string');
				$iInsertNew['CrtdTmStmp'] = $iNowTime;
				//$iInsertNew['DOCUMENT_ROOT'] = $wpdb->escape($_SERVER['DOCUMENT_ROOT']);
				//$iInsertNew['GATEWAY_INTERFACE'] = $wpdb->escape($_SERVER['GATEWAY_INTERFACE']);
				//$iInsertNew['HTTP_ACCEPT_CHARSET'] = $wpdb->escape($_SERVER['HTTP_ACCEPT_CHARSET']);
				//$iInsertNew['HTTP_ACCEPT_ENCODING'] = $wpdb->escape($_SERVER['HTTP_ACCEPT_ENCODING']);
				//$iInsertNew['HTTP_ACCEPT_LANGUAGE'] = $wpdb->escape($_SERVER['HTTP_ACCEPT_LANGUAGE']);
				//$iInsertNew['HTTP_CACHE_CONTROL'] = $wpdb->escape($_SERVER['HTTP_CACHE_CONTROL']);
				//$iInsertNew['HTTP_CONNECTION'] = $wpdb->escape($_SERVER['HTTP_CONNECTION']);
				//$iInsertNew['HTTP_COOKIE'] = $wpdb->escape($_SERVER['HTTP_COOKIE']);
				//$iInsertNew['HTTP_HOST'] = $wpdb->escape($_SERVER['HTTP_HOST']);
				//$iInsertNew['HTTP_KEEP_ALIVE'] = $wpdb->escape($_SERVER['HTTP_KEEP_ALIVE']);
				$iInsertNew['HTTP_REFERER'] = $wpdb->escape($_SERVER['HTTP_REFERER']);
				$iInsertNew['HTTP_USER_AGENT'] = $wpdb->escape($_SERVER['HTTP_USER_AGENT']);
				//$iInsertNew['PATH'] = $wpdb->escape($_SERVER['PATH']);
				//$iInsertNew['PATH_INFO'] = $wpdb->escape($_SERVER['PATH_INFO']);
				//$iInsertNew['PHPRC'] = $wpdb->escape($_SERVER['PHPRC']);
				//$iInsertNew['QUERY_STRING'] = $wpdb->escape($_SERVER['QUERY_STRING']);
				//$iInsertNew['RAILS_ENV'] = $wpdb->escape($_SERVER['RAILS_ENV']);
				//$iInsertNew['REDIRECT_QUERY_STRING'] = $wpdb->escape($_SERVER['REDIRECT_QUERY_STRING']);
				//$iInsertNew['REDIRECT_RAILS_ENV'] = $wpdb->escape($_SERVER['REDIRECT_RAILS_ENV']);
				//$iInsertNew['REDIRECT_STATUS'] = $wpdb->escape($_SERVER['REDIRECT_STATUS']);
				//$iInsertNew['REDIRECT_SUBDOMAIN_DOCUMENT_ROOT'] = $wpdb->escape($_SERVER['REDIRECT_SUBDOMAIN_DOCUMENT_ROOT']);
				//$iInsertNew['REDIRECT_URL'] = $wpdb->escape($_SERVER['REDIRECT_URL']);
				//$iInsertNew['REMOTE_ADDR'] = $wpdb->escape($_SERVER['REMOTE_ADDR']);
				//$iInsertNew['REMOTE_PORT'] = $wpdb->escape($_SERVER['REMOTE_PORT']);
				//$iInsertNew['REQUEST_METHOD'] = $wpdb->escape($_SERVER['REQUEST_METHOD']);
				//$iInsertNew['REQUEST_URI'] = $wpdb->escape($_SERVER['REQUEST_URI']);
				//$iInsertNew['SCRIPT_FILENAME'] = $wpdb->escape($_SERVER['SCRIPT_FILENAME']);
				//$iInsertNew['SCRIPT_NAME'] = $wpdb->escape($_SERVER['SCRIPT_NAME']);
				//$iInsertNew['SERVER_ADDR'] = $wpdb->escape($_SERVER['SERVER_ADDR']);
				//$iInsertNew['SERVER_ADMIN'] = $wpdb->escape($_SERVER['SERVER_ADMIN']);
				//$iInsertNew['SERVER_NAME'] = $wpdb->escape($_SERVER['SERVER_NAME']);
				//$iInsertNew['SERVER_PORT'] = $wpdb->escape($_SERVER['SERVER_PORT']);
				//$iInsertNew['SERVER_PROTOCOL'] = $wpdb->escape($_SERVER['SERVER_PROTOCOL']);
				//$iInsertNew['SERVER_SIGNATURE'] = $wpdb->escape($_SERVER['SERVER_SIGNATURE']);
				//$iInsertNew['SERVER_SOFTWARE'] = $wpdb->escape($_SERVER['SERVER_SOFTWARE']);
				//$iInsertNew['SPI'] = $wpdb->escape($_SERVER['SPI']);
				//$iInsertNew['SUBDOMAIN_DOCUMENT_ROOT'] = $wpdb->escape($_SERVER['SUBDOMAIN_DOCUMENT_ROOT']);
				//$iInsertNew['PHP_SELF'] = $wpdb->escape($_SERVER['PHP_SELF']);
					
				//if (wp_title('', false)) {
				  $iInsertNew['wp_title'] = $wpdb->escape(trim(wp_title('', false)));
				//} else {
				//  $iInsertNew['wp_title'] = $wpdb->escape(trim(get_option('blogname')));
				//};
				
				//$iInsertNew['siteurl'] = $wpdb->escape(trim(get_option('siteurl')));
				$iInsertNew['siteurl'] = $wpdb->escape(trim(wp_guess_url()));
				
				//if ($_SERVER["REMOTE_ADDR"] === "98.244.3.240") {print "<pre>";print_r($iInsertNew);print "</pre>";};
				
							
				$iDBtableName = $wpdb->prefix . "NJ_FastStats";	
				
				if ($iDBtableName) {					
					$iDBtableName = $wpdb->escape($iDBtableName);
					
					// The new site visitor is added here
					//$wpdb->insert( $iDBtableName, $iInsertNew ); <-- this is for individual hits
										
					// - ++ - ++ - ++ - ++ START THIS updates the Totals or sets a new one
					$iNowYMDfull = mktime(date("H"), date("i"), date("s"), date("n"), date("j"), date("Y"));
					$iNowYMDfull = $wpdb->escape($iNowYMDfull);
					$iNowYMD = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
					$iNowYMD = $wpdb->escape($iNowYMD);
					
					$iWhatTotal = 0;// T1=url hts per day; T2=Ttl hts per day; T3=Ttl hts per url; T4=Ttl cnt for referers
					//$iWhatTmStmp = " AND `CrtdTmStmp` = '{$iNowYMD}'";
					//$iWhat3rd = " AND `siteurl` = '{$iInsertNew['siteurl']}'";
					//$iSlctQry = "SELECT * FROM {$iDBtableName} WHERE `EntryType` = 'T{$iWhatTotal}'{$iWhatTmStmp}{$iWhat3rd}";
					//$iSetLstDateStmp = NULL;
					
					while ($iWhatTotal >= 0 and $iWhatTotal < 4) {
						
						$iWhatTotal++;
						
						// set UPDATE info here
						if ($iWhatTotal == 1) {$iSetLstDateStmp = NULL;$iWhatTmStmp = " AND `CrtdTmStmp` = '{$iNowYMD}'";$iWhat3rd = " AND `siteurl` = '{$iInsertNew['siteurl']}'";};
						if ($iWhatTotal == 2) {$iSetLstDateStmp = NULL;$iWhatTmStmp = " AND `CrtdTmStmp` = '{$iNowYMD}'";$iWhat3rd = '';};
						if ($iWhatTotal == 3) {$iSetLstDateStmp = ", `CrtdTmStmp` = '" .time(). "'";$iWhatTmStmp = NULL;$iWhat3rd = " AND `siteurl` = '{$iInsertNew['siteurl']}'";};
						if ($iWhatTotal == 4) {
							$iSetLstDateStmp = ", `CrtdTmStmp` = '" .time(). "'";$iWhatTmStmp = NULL;$iWhat3rd = " AND `HTTP_REFERER` = '{$iInsertNew['HTTP_REFERER']}'";
							if (!$iInsertNew['HTTP_REFERER']) {continue;};
						};
						
						$iSlctQry = NULL;
						$iSlctQry = "SELECT * FROM {$iDBtableName} WHERE `EntryType` = 'T{$iWhatTotal}'{$iWhatTmStmp}{$iWhat3rd}";
						//echo $iSlctQry;
													
						if($wpdb->get_var($iSlctQry)) {
							
							$iQryUp = "UPDATE {$iDBtableName} SET `Counter` = `Counter` + 1{$iSetLstDateStmp} WHERE `EntryType` = 'T{$iWhatTotal}'{$iWhatTmStmp}{$iWhat3rd} LIMIT 1 ;";
							$wpdb->query($iQryUp);
																										
						} else {
							
							$iInsertNewCNTR['EntryType'] = 'T' . $iWhatTotal;
							$iInsertNewCNTR['Counter'] = 1;
							if ($iWhatTotal == 1 or $iWhatTotal == 2) {$iInsertNewCNTR['CrtdTmStmp'] = $iNowYMD;}
								else {$iInsertNewCNTR['CrtdTmStmp'] = time();};
							$iInsertNewCNTR['wp_title'] = $iInsertNew['wp_title'];
							$iInsertNewCNTR['siteurl'] = $iInsertNew['siteurl'];
							$iInsertNewCNTR['HTTP_REFERER'] = $wpdb->escape($_SERVER['HTTP_REFERER']);
							$iInsertNewCNTR['HTTP_USER_AGENT'] = $wpdb->escape($_SERVER['HTTP_USER_AGENT']);
							$wpdb->insert( $iDBtableName, $iInsertNewCNTR );							
													
						};//END if($wpdb->get_var($iSlctQry))
																			
					};//END while
					// - ++ - ++ - ++ - ++ END  THIS updates the Totals
				
				};//END if ($iDBtableName)
						
			};//END if ($wpdb)
		
		};//END if ($iExstsUsr < 1)
		
	};
	
};//END function NJ_updateStats

function NJ_checkInstall() {
	
	// This is the version number and it checks wp to see what the currently installed one is at
	$iVersionNumber = '1.0';
		
	global $wpdb;
	$iDBtableName = $wpdb->prefix . "NJ_FastStats";	
	
	if(get_option('NJ_FstSts_VN') != $iVersionNumber or $wpdb->get_var("SHOW TABLES LIKE '$iDBtableName'") != $iDBtableName) {
				
		update_option('NJ_FstSts_VN', $iVersionNumber);
		
		if (file_exists(ABSPATH . 'wp-admin/includes/upgrade.php')) {

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			
			$sql = "CREATE TABLE " . $iDBtableName . ' ('
			. "\n`UN` mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT ,"
			. "\nPRIMARY KEY  UN (UN) ,"
			. "\n`EntryType` tinytext NULL ,"
			. "\n`Counter` int(12) NULL ,"
			. "\n`CrtdTmStmp` tinytext NULL ,"
			//. "\n`DOCUMENT_ROOT` text NULL ,"
			//. "\n`GATEWAY_INTERFACE` text NULL ,"
			//. "\n`HTTP_ACCEPT` text NULL ,"
			//. "\n`HTTP_ACCEPT_CHARSET` text NULL ,"
			//. "\n`HTTP_ACCEPT_ENCODING` text NULL ,"
			//. "\n`HTTP_ACCEPT_LANGUAGE` text NULL ,"
			//. "\n`HTTP_CACHE_CONTROL` text NULL ,"
			//. "\n`HTTP_CONNECTION` text NULL ,"
			//. "\n`HTTP_COOKIE` text NULL ,"
			//. "\n`HTTP_HOST` text NULL ,"
			//. "\n`HTTP_KEEP_ALIVE` text NULL ,"
			. "\n`HTTP_REFERER` text NULL ,"
			. "\n`HTTP_USER_AGENT` text NULL ,"
			//. "\n`PATH` text NULL ,"
			//. "\n`PATH_INFO` text NULL ,"
			//. "\n`PHPRC` text NULL ,"
			//. "\n`QUERY_STRING` text NULL ,"
			//. "\n`RAILS_ENV` text NULL ,"
			//. "\n`REDIRECT_QUERY_STRING` text NULL ,"
			//. "\n`REDIRECT_RAILS_ENV` text NULL ,"
			//. "\n`REDIRECT_STATUS` text NULL ,"
			//. "\n`REDIRECT_SUBDOMAIN_DOCUMENT_ROOT` text NULL ,"
			//. "\n`REDIRECT_URL` text NULL ,"
			//. "\n`REMOTE_ADDR` text NULL ,"
			//. "\n`REMOTE_PORT` text NULL ,"
			//. "\n`REQUEST_METHOD` text NULL ,"
			//. "\n`REQUEST_URI` text NULL ,"
			//. "\n`SCRIPT_FILENAME` text NULL ,"
			//. "\n`SCRIPT_NAME` text NULL ,"
			//. "\n`SERVER_ADDR` text NULL ,"
			//. "\n`SERVER_ADMIN` text NULL ,"
			//. "\n`SERVER_NAME` text NULL ,"
			//. "\n`SERVER_PORT` text NULL ,"
			//. "\n`SERVER_PROTOCOL` text NULL ,"
			//. "\n`SERVER_SIGNATURE` text NULL ,"
			//. "\n`SERVER_SOFTWARE` text NULL ,"
			//. "\n`SPI` text NULL ,"
			//. "\n`SUBDOMAIN_DOCUMENT_ROOT` text NULL ,"
			//. "\n`PHP_SELF` text NULL ,"
			. "\n`wp_title` text NULL ,"
			. "\n`siteurl` text NULL "
			. "\n);";
			
			//if ($_SERVER["REMOTE_ADDR"] == "98.244.3.240") {print "<pre>";print_r($sql);print "</pre>";exit;};	
		
			if (function_exists('dbDelta')) {
				dbDelta($sql);	
			};
			
		};//END if (file_exists(ABSPATH . 'wp-admin/includes/upgrade.php'))
					
	};

};//END function NJ_checkInstall

// This updates whenever the 'get_header' action hook is called.
add_action('shutdown', NJ_updateStats);
