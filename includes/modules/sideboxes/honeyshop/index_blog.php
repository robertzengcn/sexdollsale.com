<?php
$content ='';
$title =  '<a href="/blog/" target="_blank">News &amp; Blog</a>';
$languages_id = zen_db_prepare_input((int)$_SESSION['languages_id']);
$article = $db->Execute('select post_title,guid from wp_posts where post_type="post" and post_name <>"" and post_status="publish" order by post_date_gmt desc limit 5');
	//echo 'select post_title,guid from wp_posts where post_type="post" order by post_date_gmt desc limit 16';
	while (!$article->EOF) {
			 $content .= ' <div class="box5"><a href="'.$article->fields['guid'].'" target="_blank">'.stripslashes($article->fields['post_title']).'</a></div>';	
				$article->MoveNext();
			}
    require($template->get_template_dir('tpl_box_default_left_news.php', DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . 'tpl_box_default_left_news.php'); 
	
?>