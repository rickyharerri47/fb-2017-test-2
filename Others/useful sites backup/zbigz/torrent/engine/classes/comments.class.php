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
 Файл: comments.class.php
-----------------------------------------------------
 Назначение: создание уменьшенных копий
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

class DLE_Comments {

	var $db = false;
	var $query = false;
	var $cstart = 0;
	var $total_comments = 0;
	var $comments_per_pages = 0;
	var $intern_count = 0;
	var $extras_rules = array();
	var $comments_group = 0;

	function DLE_Comments( $db, $total_comments, $comments_per_pages ) {

		$this->db = $db;
		$this->total_comments = $total_comments;
		$this->comments_per_pages = $comments_per_pages;

		if ( isset( $_GET['cstart'] ) ) $this->cstart = intval( $_GET['cstart'] ); else $this->cstart = 0;
		
		if( $this->cstart > 0) {
			$this->cstart = $this->cstart - 1;
			$this->cstart = $this->cstart * $comments_per_pages;
		} else $this->cstart = 0;

	}

	//----------------------------------
	// Добавление дополнительных тегов в комментарии
	// $type может принимать значения 'set' или 'set_block'
	//----------------------------------
	function add_rules( $find, $replace, $type ) {

		$this->extras_rules[] = array($type, $find, $replace);

	}

	function build_comments( $template, $area, $allow_cache = false, $re_url = false ) { 
		global $config, $tpl, $is_logged, $member_id, $user_group, $lang, $dle_login_hash, $_TIME, $allow_comments_ajax, $ajax_adds, $news_date, $replace_links;

		$tpl->load_template( $template );

		$tpl->copy_template = "<div id='comment-id-{id}'>" . $tpl->copy_template . "</div>";
		$tpl->template = "<div id='comment-id-{id}'>" . $tpl->template . "</div>";
		
		if( strpos( $tpl->copy_template, "[xfvalue_" ) !== false ) $xfound = true;
		else $xfound = false;
		
		if( $xfound ) $xfields = xfieldsload( true );

		if ($area != 'ajax' AND $config['comm_msort'] == "DESC" )		
			$tpl->copy_template = "\n<div id=\"dle-ajax-comments\"></div>\n" . $tpl->copy_template;

		if ($area != 'ajax')
			$tpl->copy_template = "<form method=\"post\" action=\"\" name=\"dlemasscomments\" id=\"dlemasscomments\"><div id=\"dle-comments-list\">\n" . $tpl->copy_template;
		
		if ($area != 'ajax')
			$tpl->copy_template = "<a name=\"comment\"></a>" . $tpl->copy_template;

		$rows = false;

		if ( $allow_cache ) $rows = dle_cache ( "comm_".$allow_cache, $this->query . " LIMIT " . $this->cstart . "," . $this->comments_per_pages );

		if( $rows ) { 
			$rows = unserialize($rows);

			if ( !is_array($rows) ) die( "Cache data not correct" );

			$full_cache = true;
		} else {
			$rows = $this->db->super_query(  $this->query . " LIMIT " . $this->cstart . "," . $this->comments_per_pages, true );
			if ( $allow_cache ) create_cache ( "comm_".$allow_cache, serialize($rows), $this->query . " LIMIT " . $this->cstart . "," . $this->comments_per_pages );
		}

		$this->intern_count = 0;

		if ( count( $rows ) ) foreach ( $rows as $row ) {

			$this->intern_count ++;

			$row['date'] = strtotime( $row['date'] );
			
			$row['gast_name'] = stripslashes( $row['gast_name'] );
			$row['gast_email'] = stripslashes( $row['gast_email'] );
			$row['name'] = stripslashes( $row['name'] );


			if( ! $row['is_register'] or $row['name'] == '' ) {

				if( $row['gast_email'] != "" ) {

					$tpl->set( '{author}', "<a href=\"mailto:".htmlspecialchars($row['gast_email'], ENT_QUOTES, $config['charset'])."\">" . $row['gast_name'] . "</a>" );
				
				} else {
					$tpl->set( '{author}', $row['gast_name'] );
				}

				$tpl->set( '{login}', $row['gast_name'] );
				$tpl->set( '[profile]', "" );
				$tpl->set( '[/profile]', "" );

			} else {
				
				if( $config['allow_alt_url'] ) {

					$go_page = $config['http_home_url'] . "user/" . urlencode( $row['name'] ) . "/";					
					$tpl->set( '[profile]', "<a href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['name'] ) . "/\">" );

				} else {
					
					$go_page = "$PHP_SELF?subaction=userinfo&user=" . urlencode( $row['name'] );
					$tpl->set( '[profile]', "<a href=\"$PHP_SELF?subaction=userinfo&amp;user=" . urlencode( $row['name'] ) . "\">" );				
				}
				

				$go_page = "onclick=\"ShowProfile('" . urlencode( $row['name'] ) . "', '" . htmlspecialchars( $go_page, ENT_QUOTES, $config['charset'] ) . "', '" . $user_group[$member_id['user_group']]['admin_editusers'] . "'); return false;\"";
				
				if( $config['allow_alt_url'] ) $tpl->set( '{author}', "<a {$go_page} href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['name'] ) . "/\">" . $row['name'] . "</a>" );
				else $tpl->set( '{author}', "<a {$go_page} href=\"$PHP_SELF?subaction=userinfo&amp;user=" . urlencode( $row['name'] ) . "\">" . $row['name'] . "</a>" );

				$tpl->set( '{login}', $row['name'] );
				$tpl->set( '[/profile]', "</a>" );
			
			}

			if( $is_logged and $member_id['user_group'] == '1' ) $tpl->set( '{ip}', "IP: <a onclick=\"return dropdownmenu(this, event, IPMenu('" . $row['ip'] . "', '" . $lang['ip_info'] . "', '" . $lang['ip_tools'] . "', '" . $lang['ip_ban'] . "'), '190px')\" href=\"https://www.nic.ru/whois/?ip={$row['ip']}\" target=\"_blank\">{$row['ip']}</a>" );
			else $tpl->set( '{ip}', '' );

			$edit_limit = false;
			if (!$user_group[$member_id['user_group']]['edit_limit']) $edit_limit = true;
			elseif ( ($row['date'] + ($user_group[$member_id['user_group']]['edit_limit'] * 60)) > $_TIME ) {
				$edit_limit = true;
			}
	
			if( $is_logged AND $edit_limit AND (($member_id['name'] == $row['name'] AND $row['is_register'] AND $user_group[$member_id['user_group']]['allow_editc']) OR $user_group[$member_id['user_group']]['edit_allc']) ) {
				$tpl->set( '[com-edit]', "<a onclick=\"ajax_comm_edit('" . $row['id'] . "', '" . $area . "'); return false;\" href=\"" . $config['http_home_url'] . "index.php?do=comments&amp;action=comm_edit&amp;id=" . $row['id'] . "&amp;area=" . $area ."\">" );
				$tpl->set( '[/com-edit]', "</a>" );
				$allow_comments_ajax = true;
			} else
				$tpl->set_block( "'\\[com-edit\\](.*?)\\[/com-edit\\]'si", "" );


			if( $is_logged AND $edit_limit AND (($member_id['name'] == $row['name'] AND $row['is_register'] AND $user_group[$member_id['user_group']]['allow_delc']) OR $member_id['user_group'] == '1' OR $user_group[$member_id['user_group']]['del_allc']) ) {
				$tpl->set( '[com-del]', "<a href=\"javascript:DeleteComments('{$row['id']}', '{$dle_login_hash}')\">" );
				$tpl->set( '[/com-del]', "</a>" );
			} else
				$tpl->set_block( "'\\[com-del\\](.*?)\\[/com-del\\]'si", "" );

			if( $is_logged AND $user_group[$member_id['user_group']]['allow_admin'] AND $user_group[$member_id['user_group']]['del_allc'] ) {
				$tpl->set( '[spam]', "<a href=\"javascript:MarkSpam('{$row['id']}', '{$dle_login_hash}');\">" );
				$tpl->set( '[/spam]', "</a>" );			
			} else
				$tpl->set_block( "'\\[spam\\](.*?)\\[/spam\\]'si", "" );

			if ( $user_group[$member_id['user_group']]['del_allc'] AND !$user_group[$member_id['user_group']]['edit_limit'] ) {

				$tpl->set( '{mass-action}', "<input name=\"selected_comments[]\" value=\"{$row['id']}\" type=\"checkbox\" />" );

			} else {

				$tpl->set( '{mass-action}', "" );

			}
			
			if ($area == 'lastcomments') {

				$tpl->set_block( "'\\[fast\\](.*?)\\[/fast\\]'si", "" );

			} else {

				if( ($user_group[$member_id['user_group']]['allow_addc']) and $config['allow_comments'] ) {
					if( ! $row['is_register'] or $row['name'] == '' ) $row['name'] = $row['gast_name'];
					else $row['name'] = $row['name'];
					$tpl->set( '[fast]', "<a onmouseover=\"dle_copy_quote('" . str_replace( array (" ", "&#039;" ), array ("&nbsp;", "&amp;#039;" ), $row['name'] ) . "');\" href=\"#\" onclick=\"dle_ins('{$row['id']}'); return false;\">" );
					$tpl->set( '[/fast]', "</a>" );
				} else
					$tpl->set_block( "'\\[fast\\](.*?)\\[/fast\\]'si", "" );

			}

			$tpl->set( '{mail}', $row['gast_email'] );
			$tpl->set( '{id}', $row['id'] );
			
			if( date( 'Ymd', $row['date'] ) == date( 'Ymd', $_TIME ) ) {
				
				$tpl->set( '{date}', $lang['time_heute'] . langdate( ", H:i", $row['date'] ) );
			
			} elseif( date( 'Ymd', $row['date'] ) == date( 'Ymd', ($_TIME - 86400) ) ) {
				
				$tpl->set( '{date}', $lang['time_gestern'] . langdate( ", H:i", $row['date'] ) );
			
			} else {
				
				$tpl->set( '{date}', langdate( $config['timestamp_comment'], $row['date'] ) );
			
			}

			$news_date = $row['date'];
			$tpl->copy_template = preg_replace_callback ( "#\{date=(.+?)\}#i", "formdate", $tpl->copy_template );

			if ($area == 'lastcomments') {

				$row['category'] = intval( $row['category'] );

				if( $config['allow_alt_url'] ) {
					
					if( $config['seo_type'] == 1 OR $config['seo_type'] == 2 ) {
						
						if( $row['category'] and $config['seo_type'] == 2 ) {
							
							$full_link = $config['http_home_url'] . get_url( $row['category'] ) . "/" . $row['post_id'] . "-" . $row['alt_name'] . ".html";
						
						} else {
							
							$full_link = $config['http_home_url'] . $row['post_id'] . "-" . $row['alt_name'] . ".html";
						
						}
					
					} else {
						
						$full_link = $config['http_home_url'] . date( 'Y/m/d/', strtotime ($row['newsdate']) ) . $row['alt_name'] . ".html";
					}
				
				} else {
					
					$full_link = $config['http_home_url'] . "index.php?newsid=" . $row['post_id'];
				
				}

				$tpl->set( '{news_title}', "<a href=\"" . $full_link . "\">" . stripslashes( $row['title'] ) . "</a>" );

			} else 	$tpl->set( '{news_title}', "" );


			if( $xfound ) {
				$xfieldsdata = xfieldsdataload( $row['xfields'] );
				
				foreach ( $xfields as $value ) {
					$preg_safe_name = preg_quote( $value[0], "'" );
					
					if( $value[5] != 1 or $member_id['user_group'] == 1 or ($is_logged and $row['is_register'] and $member_id['name'] == $row['name']) ) {

						if( empty( $xfieldsdata[$value[0]] ) ) {

							$tpl->copy_template = preg_replace( "'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template );
							$tpl->copy_template = str_replace( "[xfnotgiven_{$value[0]}]", "", $tpl->copy_template );
							$tpl->copy_template = str_replace( "[/xfnotgiven_{$value[0]}]", "", $tpl->copy_template );

						} else {
							$tpl->copy_template = preg_replace( "'\\[xfnotgiven_{$preg_safe_name}\\](.*?)\\[/xfnotgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template );
							$tpl->copy_template = str_replace( "[xfgiven_{$value[0]}]", "", $tpl->copy_template );
							$tpl->copy_template = str_replace( "[/xfgiven_{$value[0]}]", "", $tpl->copy_template );
						}

						$tpl->copy_template = preg_replace( "'\\[xfvalue_{$preg_safe_name}\\]'i", stripslashes( $xfieldsdata[$value[0]] ), $tpl->copy_template );

					} else {

						$tpl->copy_template = preg_replace( "'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template );
						$tpl->copy_template = preg_replace( "'\\[xfvalue_{$preg_safe_name}\\]'i", "", $tpl->copy_template );
						$tpl->copy_template = preg_replace( "'\\[xfnotgiven_{$preg_safe_name}\\](.*?)\\[/xfnotgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template );

					}
				}
			}

			if ($area == 'ajax' AND isset($ajax_adds) ) {

				$tpl->set( '{comment-id}', "--" );

			} elseif($area == 'lastcomments') {

				$tpl->set( '{comment-id}', $this->total_comments - $this->cstart - $this->intern_count + 1 );

			} else {

				if( $config['comm_msort'] == "ASC" ) $tpl->set( '{comment-id}', $this->cstart + $this->intern_count );
				else $tpl->set( '{comment-id}', $this->total_comments - $this->cstart - $this->intern_count + 1 );

			}

			if ( count(explode("@", $row['foto'])) == 2 ) {
			
				$tpl->set( '{foto}', 'http://www.gravatar.com/avatar/' . md5(trim($row['foto'])) . '?s=' . intval($user_group[$row['user_group']]['max_foto']) );	
			
			} else {
				
				if($row['foto']) $tpl->set( '{foto}', $config['http_home_url'] . "uploads/fotos/" . $row['foto'] );
				else $tpl->set( '{foto}', "{THEME}/dleimages/noavatar.png" );
			
			}

			if( $row['is_register'] AND $row['fullname'] ) {
				$tpl->set( '[fullname]', "" );
				$tpl->set( '[/fullname]', "" );
				$tpl->set( '{fullname}', stripslashes( $row['fullname'] ) );
				$tpl->set_block( "'\\[not-fullname\\](.*?)\\[/not-fullname\\]'si", "" );
			
			} else {
				$tpl->set_block( "'\\[fullname\\](.*?)\\[/fullname\\]'si", "" );
				$tpl->set( '{fullname}', "" );
				$tpl->set( '[not-fullname]', "" );
				$tpl->set( '[/not-fullname]', "" );
			}
			

			if( $row['is_register'] AND $row['land'] ) {
				$tpl->set( '[land]', "" );
				$tpl->set( '[/land]', "" );
				$tpl->set( '{land}', stripslashes( $row['land'] ) );
				$tpl->set_block( "'\\[not-land\\](.*?)\\[/not-land\\]'si", "" );
			
			} else {
				$tpl->set_block( "'\\[land\\](.*?)\\[/land\\]'si", "" );
				$tpl->set( '{land}', "" );
				$tpl->set( '[not-land]', "" );
				$tpl->set( '[/not-land]', "" );
			}			

			if( $row['comm_num'] ) {
		
				$tpl->set( '[comm-num]', "" );
				$tpl->set( '[/comm-num]', "" );
				$tpl->set( '{comm-num}', $row['comm_num'] );
				$tpl->set_block( "'\\[not-comm-num\\](.*?)\\[/not-comm-num\\]'si", "" );
			
			} else {
				
				$tpl->set( '{comm-num}', 0 );
				$tpl->set( '[not-comm-num]', "" );
				$tpl->set( '[/not-comm-num]', "" );
				$tpl->set_block( "'\\[comm-num\\](.*?)\\[/comm-num\\]'si", "" );
			}

			if( $row['news_num'] ) {
		
				$tpl->set( '[news-num]', "" );
				$tpl->set( '[/news-num]', "" );
				$tpl->set( '{news-num}', $row['news_num'] );
				$tpl->set_block( "'\\[not-news-num\\](.*?)\\[/not-news-num\\]'si", "" );
			
			} else {
				
				$tpl->set( '{news-num}', 0 );
				$tpl->set( '[not-news-num]', "" );
				$tpl->set( '[/not-news-num]', "" );
				$tpl->set_block( "'\\[news-num\\](.*?)\\[/news-num\\]'si", "" );
			}
			
			if( $row['is_register'] AND $row['reg_date'] ) $tpl->set( '{registration}', langdate( "j.m.Y", $row['reg_date'] ) );
			else $tpl->set( '{registration}', '--' );

			if( $row['is_register'] AND $row['lastdate'] ) {

				$tpl->set( '{lastdate}', langdate( "j.m.Y", $row['lastdate'] ) );

				if ( ($row['lastdate'] + 1200) > $_TIME OR ($row['user_id'] AND $row['user_id'] == $member_id['user_id'])) {

					$tpl->set( '[online]', "" );
					$tpl->set( '[/online]', "" );
					$tpl->set_block( "'\\[offline\\](.*?)\\[/offline\\]'si", "" );

				} else {
					$tpl->set( '[offline]', "" );
					$tpl->set( '[/offline]', "" );
					$tpl->set_block( "'\\[online\\](.*?)\\[/online\\]'si", "" );
				}

			} else { 

				$tpl->set( '{lastdate}', '--' );
				$tpl->set_block( "'\\[offline\\](.*?)\\[/offline\\]'si", "" );
				$tpl->set_block( "'\\[online\\](.*?)\\[/online\\]'si", "" );

			}
			
			if( $row['is_register'] AND $row['signature'] and $user_group[$row['user_group']]['allow_signature'] ) {
				
				$tpl->set_block( "'\\[signature\\](.*?)\\[/signature\\]'si", "\\1" );
				$tpl->set( '{signature}', stripslashes( $row['signature'] ) );
			
			} else {
				$tpl->set_block( "'\\[signature\\](.*?)\\[/signature\\]'si", "" );
			}

			if( $is_logged) {

				$tpl->set( '[complaint]', "<a href=\"javascript:AddComplaint('" . $row['id'] . "', 'comments')\">" );
				$tpl->set( '[/complaint]', "</a>" );
			
			} else {

				$tpl->set_block( "'\\[complaint\\](.*?)\\[/complaint\\]'si", "" );			
			
			}

			if( ! $row['user_group'] ) $row['user_group'] = 5;

			$this->comments_group = $row['user_group'];

			if (strpos ( $tpl->copy_template, "[commentsgroup=" ) !== false) {
				$tpl->copy_template = preg_replace_callback ( "#\\[(commentsgroup)=(.+?)\\](.*?)\\[/commentsgroup\\]#is", array( &$this, 'check_group'), $tpl->copy_template );
			}
		
			if (strpos ( $tpl->copy_template, "[not-commentsgroup=" ) !== false) {
				$tpl->copy_template = preg_replace_callback ( "#\\[(not-commentsgroup)=(.+?)\\](.*?)\\[/not-commentsgroup\\]#is", array( &$this, 'check_group'), $tpl->copy_template );
			}	

			if (strpos ( $tpl->copy_template, "[commentscount=" ) !== false) {
				$tpl->copy_template = preg_replace_callback ( "#\\[(commentscount)=(.+?)\\](.*?)\\[/commentscount\\]#is", array( &$this, 'check_commentscount'), $tpl->copy_template );
			}

			if (strpos ( $tpl->copy_template, "[not-commentscount=" ) !== false) {
				$tpl->copy_template = preg_replace_callback ( "#\\[(not-commentscount)=(.+?)\\](.*?)\\[/not-commentscount\\]#is", array( &$this, 'check_commentscount'), $tpl->copy_template );
			}
			
			if( $user_group[$row['user_group']]['icon'] ) $tpl->set( '{group-icon}', "<img src=\"" . $user_group[$row['user_group']]['icon'] . "\" alt=\"\" />" );
			else $tpl->set( '{group-icon}', "" );
			
			$tpl->set( '{group-name}', $user_group[$row['user_group']]['group_prefix'].$user_group[$row['user_group']]['group_name'].$user_group[$row['user_group']]['group_suffix'] );

			if ( count($this->extras_rules) ) {

				foreach ($this->extras_rules as $rules) {

					if ($rules[0] == 'set') {

						$tpl->set( $rules[1], $rules[2] );

					} else {

						$tpl->set_block( $rules[1], $rules[2] );
					}

				}


			}

			if ($config['allow_links'] AND function_exists('replace_links') AND isset($replace_links['comments'])) $row['text'] = replace_links ( $row['text'], $replace_links['comments'] );

			if( $user_group[$member_id['user_group']]['allow_hide'] ) $row['text'] = str_ireplace( "[hide]", "", str_ireplace( "[/hide]", "", $row['text']) );
			else $row['text'] = preg_replace ( "#\[hide\](.+?)\[/hide\]#is", "<div class=\"quote\">" . $lang['news_regus'] . "</div>", $row['text'] );

			$tpl->set( '{comment}', "<div id='comm-id-" . $row['id'] . "'>" . stripslashes( $row['text'] ) . "</div>" );
			
			$tpl->compile( 'comments' );

		} else {

			if ($config['seo_control']  AND $_GET['cstart'] AND $re_url) {
			
				$re_url = str_replace( $config['http_home_url'], "/", $re_url );
				header("HTTP/1.0 301 Moved Permanently");
				header("Location: {$re_url}");
				die("Redirect");
	
			}

			$tpl->result['comments'] = "";

			if ($area != 'ajax' AND $config['comm_msort'] == "DESC" )		
				$tpl->result['comments'] = "\n<div id=\"dle-ajax-comments\"></div>\n";
	
			if ($area != 'ajax')
				$tpl->result['comments'] = "<form method=\"post\" action=\"\" name=\"dlemasscomments\" id=\"dlemasscomments\"><div id=\"dle-comments-list\">\n" . $tpl->result['comments'];
			
			if ($area != 'ajax')
				$tpl->result['comments'] = "<a name=\"comment\"></a>" . $tpl->result['comments'];

		}

		$tpl->clear();

		$tpl->result['comments'] = preg_replace_callback ( "#\\[declination=(\d+)\\](.+?)\\[/declination\\]#is", "declination", $tpl->result['comments'] );

		if($config['comments_lazyload'] AND $area != 'ajax' AND $this->total_comments > $this->comments_per_pages) {
		
			$tpl->result['comments'] .= "\n<div class=\"ajax_comments_area\"><div class=\"ajax_loaded_comments\"></div><div class=\"ajax_comments_next\"></div></div>\n";
		
		}

		if ($area != 'ajax' AND $config['comm_msort'] == "ASC" )		
			$tpl->result['comments'] .= "\n<div id=\"dle-ajax-comments\"></div>\n";

		if ($area != 'ajax' AND $user_group[$member_id['user_group']]['del_allc'] AND !$user_group[$member_id['user_group']]['edit_limit'])		
			$tpl->result['comments'] .= "\n<div class=\"mass_comments_action\">{$lang['mass_comments']}&nbsp;<select name=\"mass_action\"><option value=\"\">{$lang['edit_selact']}</option><option value=\"mass_combine\">{$lang['edit_selcomb']}</option><option value=\"mass_delete\">{$lang['edit_seldel']}</option></select>&nbsp;&nbsp;<input type=\"submit\" class=\"bbcodes\" value=\"{$lang['b_start']}\" /></div>\n<input type=\"hidden\" name=\"do\" value=\"comments\" /><input type=\"hidden\" name=\"dle_allow_hash\" value=\"{$dle_login_hash}\" /><input type=\"hidden\" name=\"area\" value=\"{$area}\" />";

		if ($area != 'ajax')		
			$tpl->result['comments'] .= "</div></form>\n";


		if ( strpos ( $tpl->result['content'], "<!--dlecomments-->" ) !== false ) {

			$tpl->result['content'] = str_replace ( "<!--dlecomments-->", $tpl->result['comments'], $tpl->result['content'] );

		} else {

			$tpl->result['content'] .= $tpl->result['comments'];

		}

	}

	function build_navigation( $template, $alternative_link, $link, $re_url = false ) {
		global $tpl, $config, $lang, $news_id, $js_array; 

		if( $this->total_comments <= $this->comments_per_pages ) return;

		if($config['comments_lazyload'] AND $news_id ) {

			$js_array[] = "engine/classes/js/waypoints.js";
			$enpages_count = @ceil( $this->total_comments / $this->comments_per_pages );

			$tpl->result['content'] .= <<<HTML
<script type="text/javascript">  
<!--  
	var dle_news_id= '{$news_id}';
	var total_comments_pages= '{$enpages_count}';
	var current_comments_page= '1';

$(function(){

	$('.ajax_comments_next').waypoint(function() {

		if (current_comments_page < total_comments_pages ) {

			$.waypoints('disable');
			current_comments_page ++;
			ShowLoading('');

			$.get(dle_root + "engine/ajax/comments.php", { cstart: current_comments_page, news_id: dle_news_id, skin: dle_skin, massact:'disable' }, function(data){

				setTimeout(function() { $.waypoints('enable'); }, 300);		
				HideLoading('');
			
				$(".ajax_loaded_comments").append(data.comments);		
		
			}, "json");	

		} else {

			$.waypoints('destroy');
		}


	}, {
	  offset: 'bottom-in-view'
	});

});

//-->
</script>
HTML;

			return;

		}

		if( isset( $_GET['cstart'] ) ) $this->cstart = intval( $_GET['cstart'] );
		if( !$this->cstart OR $this->cstart < 0 ) $this->cstart = 1;

		$news_id = intval($news_id) > 0 ? intval($news_id): 0;

		$tpl->load_template( $template );

		//----------------------------------
		// Предыдущая страница
		//----------------------------------
		if( $this->cstart > 1 ) {
			$prev = $this->cstart - 1;

			if ( $config['comments_ajax'] AND $news_id ) $go_page = " onclick=\"CommentsPage('{$prev}', '{$news_id}'); return false;\""; else $go_page = "";
			if( $config['allow_alt_url'] AND $alternative_link) {

				if ( $prev == 1 AND $re_url ) $url = $re_url."#comment";
				else $url = str_replace ("{page}", $prev, $alternative_link );

				$tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<a href=\"" . $url . "\"{$go_page}>\\1</a>" );

			} else {

				if ( $prev == 1 AND $re_url ) $tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<a href=\"" . $re_url . "#comment\"{$go_page}>\\1</a>" );
				else $tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<a href=\"$PHP_SELF?cstart=" . $prev . "&amp;{$link}#comment\"{$go_page}>\\1</a>" );
			}
		
		} else {
			$tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<span>\\1</span>" );
			$no_prev = TRUE;
		}

		//----------------------------------
		// страницы
		//----------------------------------
		if( $this->comments_per_pages ) {
			
			$enpages_count = @ceil( $this->total_comments / $this->comments_per_pages );
			$pages = "";
			
			if( $enpages_count <= 10 ) {
				
				for($j = 1; $j <= $enpages_count; $j ++) {
					
					if( $j != $this->cstart  ) {

						if ( $config['comments_ajax'] AND $news_id ) $go_page = " onclick=\"CommentsPage('{$j}', '{$news_id}'); return false;\""; else $go_page = "";
						
						if( $config['allow_alt_url'] AND $alternative_link ) {

							if ( $j == 1 AND $re_url ) $url = $re_url."#comment";
							else $url = str_replace ("{page}", $j, $alternative_link );

							$pages .= "<a href=\"" . $url . "\"{$go_page}>$j</a> ";

						} else {

							if ( $j == 1 AND $re_url ) $pages .= "<a href=\"{$re_url}#comment\"{$go_page}>$j</a> ";
							else $pages .= "<a href=\"$PHP_SELF?cstart=$j&amp;{$link}#comment\"{$go_page}>$j</a> ";

						}
					
					} else {
						
						$pages .= "<span>$j</span> ";
					}
				
				}
			
			} else {
				
				$start = 1;
				$end = 10;
				$nav_prefix = "<span class=\"nav_ext\">{$lang['nav_trennen']}</span> ";
				
				if( $this->cstart  > 0 ) {
					
					if( $this->cstart  > 6 ) {
						
						$start = $this->cstart  - 4;
						$end = $start + 8;
						
						if( $end >= $enpages_count ) {
							$start = $enpages_count - 9;
							$end = $enpages_count - 1;
							$nav_prefix = "";
						} else
							$nav_prefix = "<span class=\"nav_ext\">{$lang['nav_trennen']}</span> ";
					
					}
				
				}
				
				if( $start >= 2 ) {

					if ( $config['comments_ajax'] AND $news_id ) $go_page = " onclick=\"CommentsPage('1', '{$news_id}'); return false;\""; else $go_page = "";
					
					if( $re_url ) {

						$pages .= "<a href=\"" . $re_url . "#comment\"{$go_page}>1</a> <span class=\"nav_ext\">{$lang['nav_trennen']}</span> ";

					} else $pages .= "<a href=\"$PHP_SELF?cstart=1&amp;{$link}#comment\"{$go_page}>1</a> <span class=\"nav_ext\">{$lang['nav_trennen']}</span> ";
				
				}
				
				for($j = $start; $j <= $end; $j ++) {
					
					if( $j != $this->cstart ) {

						if ( $config['comments_ajax'] AND $news_id ) $go_page = " onclick=\"CommentsPage('{$j}', '{$news_id}'); return false;\""; else $go_page = "";
						
						if( $config['allow_alt_url'] AND $alternative_link) {

							if ( $j == 1 AND $re_url ) $url = $re_url."#comment";
							else $url = str_replace ("{page}", $j, $alternative_link );

							$pages .= "<a href=\"" . $url . "\"{$go_page}>$j</a> ";

						} else {

							if ( $j == 1 AND $re_url ) $pages .= "<a href=\"{$re_url}#comment\"{$go_page}>$j</a> ";
							else $pages .= "<a href=\"$PHP_SELF?cstart=$j&amp;{$link}#comment\"{$go_page}>$j</a> ";

						}					
					} else {
						
						$pages .= "<span>$j</span> ";
					}
				
				}
				
				if( $this->cstart != $enpages_count ) {

					if ( $config['comments_ajax'] AND $news_id ) $go_page = " onclick=\"CommentsPage('{$enpages_count}', '{$news_id}'); return false;\""; else $go_page = "";
					
					if( $config['allow_alt_url'] AND $alternative_link) {

						$url = str_replace ("{page}", $enpages_count, $alternative_link );
						$pages .= $nav_prefix . "<a href=\"" . $url . "\"{$go_page}>{$enpages_count}</a>";

					} else $pages .= $nav_prefix . "<a href=\"$PHP_SELF?cstart={$enpages_count}&amp;{$link}#comment\"{$go_page}>{$enpages_count}</a>";
				
				} else
					$pages .= "<span>{$enpages_count}</span> ";
			
			}
			
			$tpl->set( '{pages}', $pages );
		
		}

		//----------------------------------
		// следующая страница
		//----------------------------------
		if( $this->cstart < $enpages_count ) {


			$next_page = $this->cstart + 1;

			if ( $config['comments_ajax'] AND $news_id ) $go_page = " onclick=\"CommentsPage('{$next_page}', '{$news_id}'); return false;\""; else $go_page = "";

			if( $config['allow_alt_url'] AND $alternative_link ) {

				$url = str_replace ("{page}", $next_page, $alternative_link );
				$tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "<a href=\"" . $url . "\"{$go_page}>\\1</a>" );

			} else $tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "<a href=\"$PHP_SELF?cstart=$next_page&amp;{$link}#comment\"{$go_page}>\\1</a>" );
		
		} else {
			$tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "<span>\\1</span>" );
			$no_next = TRUE;
		}
		
		$tpl->compile( 'commentsnavigation' );
		
		$tpl->clear();

		if ( strpos ( $tpl->result['content'], "<!--dlenavigationcomments-->" ) !== false ) {

			$tpl->result['content'] = str_replace ( "<!--dlenavigationcomments-->", "<div class=\"dle-comments-navigation\">".$tpl->result['commentsnavigation']."</div>", $tpl->result['content'] );

		} else {

			$tpl->result['content'] .= "<div class=\"dle-comments-navigation\">".$tpl->result['commentsnavigation']."</div>";

		}

	}
	
	function check_group( $matches=array() ) {

		$groups = $matches[2];
		$block = $matches[3];

		if ($matches[1] == "commentsgroup") $action = true; else $action = false;
		
		$groups = explode( ',', $groups );
		
		if( $action ) {
			
			if( !in_array( $this->comments_group, $groups ) ) return "";
		
		} else {
			
			if( in_array( $this->comments_group, $groups ) ) return "";
		
		}
		
		
		return $block;
	
	}

	function check_commentscount( $matches=array() ) {
	
		$block = $matches[3];
	
		$counts = explode( ',', $matches[2] );

	    if( $matches[1] == "commentscount" ) {
		
			if( !in_array( $this->intern_count, $counts ) ) return "";
	
		} else {

			if( in_array( $this->intern_count, $counts ) ) return "";

		}

		return $block;
		
	}
}
?>