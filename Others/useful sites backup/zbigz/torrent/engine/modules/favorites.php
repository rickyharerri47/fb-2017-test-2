<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004,2014 SoftNews Media Group
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: favorites.php
-----------------------------------------------------
 Назначение: вывод новостей из закладок
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

if( isset( $_REQUEST['doaction'] ) ) $doaction = $_REQUEST['doaction']; else $doaction = "";

$allow_add_comment = FALSE;
$allow_full_story = FALSE;
$allow_comments = FALSE;
$allow_userinfo = FALSE;

if( $doaction == "add" ) {
	
	$id = intval( $_GET['id'] );
	if( ! $id ) die( "Hacking attempt!" );
	
	$error = "";
	
	$row = $db->super_query( "SELECT favorites  FROM " . USERPREFIX . "_users where user_id = '$member_id[user_id]'" );
	
	$list = explode( ",", $row['favorites'] );
	
	foreach ( $list as $daten ) {
		if( $daten == $id and $id ) $error = "stop";
	}
	
	if( $error != "stop" and $id ) {
		$list[] = $id;
		$favorites = implode( ",", $list );
		
		if( $member_id['favorites'] == "" ) $favorites = $id;
		
		$member_id['favorites'] = $favorites;
		
		$db->query( "UPDATE " . USERPREFIX . "_users set favorites='$favorites' where user_id = '$member_id[user_id]'" );
	
	}

}
if( $doaction == "del" ) {
	
	$id = intval( $_GET['id'] );
	
	$list = explode( ",", $member_id['favorites'] );
	$i = 0;
	
	foreach ( $list as $daten ) {
		if( $daten == $id ) unset( $list[$i] );
		$i ++;
	}
	
	if( count( $list ) ) $member_id['favorites'] = implode( ",", $list );
	else $member_id['favorites'] = "";
	
	$db->query( "UPDATE " . USERPREFIX . "_users set favorites='$member_id[favorites]' where user_id = '$member_id[user_id]'" );

}
if( ! isset( $cstart ) ) $cstart = 0;

if( $cstart ) {
	$cstart = $cstart - 1;
	$cstart = $cstart * $config['news_number'];
	$start_from = $cstart;
}

$cstart = intval($cstart);

$url_page = $config['http_home_url'] . "favorites";
$user_query = "do=favorites";

$list = explode( ",", $member_id['favorites'] );

foreach ( $list as $daten ) {
	$fav_list[] = "'" . intval($daten) . "'";
}

$list = implode( ",", $fav_list );

$favorites = "(" . $list . ")";


if( $config['news_sort'] == "" ) $config['news_sort'] = "date";
if( $config['news_msort'] == "" ) $config['news_msort'] = "DESC";

$allow_list = explode( ',', $user_group[$member_id['user_group']]['allow_cats'] );

if( $allow_list[0] != "all" ) {
	
	if( $config['allow_multi_category'] ) {
		
		$stop_list = "category regexp '[[:<:]](" . implode( '|', $allow_list ) . ")[[:>:]]' AND ";
	
	} else {
		
		$stop_list = "category IN ('" . implode( "','", $allow_list ) . "') AND ";
	
	}

} else
	$stop_list = "";

if( $user_group[$member_id['user_group']]['allow_short'] ) $stop_list = "";

$news_sort_by = ($config['news_sort']) ? $config['news_sort'] : "date";
$news_direction_by = ($config['news_msort']) ? $config['news_msort'] : "DESC";

if (isset ( $_SESSION['dle_sort_favorites'] )) $news_sort_by = $_SESSION['dle_sort_favorites'];
if (isset ( $_SESSION['dle_direction_favorites'] )) $news_direction_by = $_SESSION['dle_direction_favorites'];


$sql_select = "SELECT p.id, p.autor, p.date, p.short_story, CHAR_LENGTH(p.full_story) as full_story, p.xfields, p.title, p.category, p.alt_name, p.comm_num, p.allow_comm, p.fixed, p.tags, e.news_read, e.allow_rate, e.rating, e.vote_num, e.votes, e.view_edit, e.editdate, e.editor, e.reason FROM " . PREFIX . "_post p LEFT JOIN " . PREFIX . "_post_extras e ON (p.id=e.news_id) WHERE {$stop_list}id in $favorites ORDER BY " . $news_sort_by . " " . $news_direction_by . " LIMIT " . $cstart . "," . $config['news_number'];
$sql_count = "SELECT COUNT(*) as count FROM " . PREFIX . "_post where {$stop_list}id in {$favorites}";

$allow_active_news = TRUE;

require (ENGINE_DIR . '/modules/show.short.php');

if( $config['files_allow'] ) if( strpos( $tpl->result['content'], "[attachment=" ) !== false ) {
	$tpl->result['content'] = show_attach( $tpl->result['content'], $attachments );
}
?>