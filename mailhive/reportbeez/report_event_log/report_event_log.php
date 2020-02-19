	<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	$back_url = mh_href_link(FILENAME_MAILBEEZ, 'module=' . $_GET['module']);

 	$app_action = (isset($_GET['app_action']) ? $_GET['app_action'] : '');
	$msg='';

  if (mh_not_null($app_action)) {
    switch ($app_action) {
      case 'save':
        break;
    }
  }

?>	

<?php 
	// back to sequence page
	echo mb_admin_button($back_url, MH_BUTTON_BACK_REPORTS, '', 'link') . '<br /><br />';
 ?>
 	<?php if ($msg != ''): ?>
		<?php echo $msg; ?>
	<?php endif; ?>
 
<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
   <tr>
    <td class="pageHeading"><?php echo MAILBEEZ_REPORT_EVENT_LOG_TEXT_TITLE; ?></td>
  </tr>
</table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_BATCH_ID	; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE; ?></td>		
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_EVENT; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODULE; ?></td>																
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ENTRY; ?></td>
								<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODE; ?></td>		
              </tr>
<?php

	//$report_query_numrows = 25;

  if ($_GET[MH_PAGE_NAME] > 1) $rows = $_GET[MH_PAGE_NAME] * '20' - '20';
	
  $report_query_raw = "select * from " . TABLE_MAILBEEZ_EVENT_LOG . " order by log_id DESC";
  $report_split = new splitPageResults($_GET[MH_PAGE_NAME], '20', $report_query_raw, $report_query_numrows);
  $report_query = mh_db_query($report_query_raw);
  while ($report =mh_db_fetch_array($report_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='pointer'" onmouseout="this.className='dataTableRow'">
                <td class="dataTableContent"><?php echo $report['batch_id']; ?></td>
                <td class="dataTableContent"><?php echo $report['log_date']; ?>&nbsp;</td>
                <td class="dataTableContent"><?php echo $report['event_type']; ?>&nbsp;</td>								
                <td class="dataTableContent"><?php echo $report['module']; ?></td>
                <td class="dataTableContent"><?php echo $report['log_entry']; ?>&nbsp;</td>			
								<td class="dataTableContent"><?php echo ($report['simulation'] > 0) ? MAILBEEZ_SIMULATION_TAG : 'PROD'; ?>&nbsp;</td>					
              </tr>
<?php
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $report_split->display_count($report_query_numrows, '20', $_GET[MH_PAGE_NAME], MAILBEEZ_TEXT_DISPLAY_NUMBER_OF_ITEMS); ?></td>
                <td class="smallText" align="right"><?php echo $report_split->display_links($report_query_numrows, '20', MAX_DISPLAY_PAGE_LINKS, $_GET[MH_PAGE_NAME], mh_get_all_get_params(array(MH_PAGE_NAME))); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
			