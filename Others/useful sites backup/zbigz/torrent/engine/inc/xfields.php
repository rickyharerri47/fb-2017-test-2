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
 Файл: xfields.php
-----------------------------------------------------
 Назначение: управление дополнительными полями
=====================================================
*/
if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

if (!isset($xfieldsaction)) $xfieldsaction = $_REQUEST['xfieldsaction'];
if (isset ( $_REQUEST['xfieldssubactionadd'] )) $xfieldssubactionadd = $_REQUEST['xfieldssubactionadd'];
if (isset ( $_REQUEST['xfieldssubaction'] )) $xfieldssubaction = $_REQUEST['xfieldssubaction'];
if (isset ( $_REQUEST['xfieldsindex'] )) $xfieldsindex = intval($_REQUEST['xfieldsindex']);
if (isset ( $_REQUEST['editedxfield'] )) $editedxfield = $_REQUEST['editedxfield'];

if (isset ($xfieldssubactionadd))
if ($xfieldssubactionadd == "add") {
  $xfieldssubaction = $xfieldssubactionadd;
}

if (!isset($xf_inited)) $xf_inited = "";

if ($xf_inited !== true) { // Prevent "Cannot redeclare" error

	function xfieldssave($data) {
		global $lang, $dle_login_hash;
	
		if ($_REQUEST['user_hash'] == "" OR $_REQUEST['user_hash'] != $dle_login_hash) {
	
			  die("Hacking attempt! User not found");
	
		}
	
	    $data = array_values($data);
		$filecontents = "";
	
	    foreach ($data as $index => $value) {
	      $value = array_values($value);
	      foreach ($value as $index2 => $value2) {
	        $value2 = stripslashes($value2);
	        $value2 = str_replace("|", "&#124;", $value2);
	        $value2 = str_replace("\r\n", "__NEWL__", $value2);
	        $filecontents .= $value2 . ($index2 < count($value) - 1 ? "|" : "");
	      }
	      $filecontents .= ($index < count($data) - 1 ? "\r\n" : "");
	    }
	
	    $filehandle = fopen(ENGINE_DIR.'/data/xfields.txt', "w+");
	    if (!$filehandle)
	    msg("error", $lang['xfield_error'], "$lang[xfield_err_1] \"".ENGINE_DIR."/data/xfields.txt\", $lang[xfield_err_1]");
	
		$find = array ('/data:/i', '/about:/i', '/vbscript:/i', '/onclick/i', '/onload/i', '/onunload/i', '/onabort/i', '/onerror/i', '/onblur/i', '/onchange/i', '/onfocus/i', '/onreset/i', '/onsubmit/i', '/ondblclick/i', '/onkeydown/i', '/onkeypress/i', '/onkeyup/i', '/onmousedown/i', '/onmouseup/i', '/onmouseover/i', '/onmouseout/i', '/onselect/i', '/javascript/i', '/javascript/i' );
		$replace = array ("d&#097;ta:", "&#097;bout:", "vbscript<b></b>:", "&#111;nclick", "&#111;nload", "&#111;nunload", "&#111;nabort", "&#111;nerror", "&#111;nblur", "&#111;nchange", "&#111;nfocus", "&#111;nreset", "&#111;nsubmit", "&#111;ndblclick", "&#111;nkeydown", "&#111;nkeypress", "&#111;nkeyup", "&#111;nmousedown", "&#111;nmouseup", "&#111;nmouseover", "&#111;nmouseout", "&#111;nselect", "j&#097;vascript" );
		
		$filecontents = preg_replace( $find, $replace, $filecontents );
		$filecontents = preg_replace( "#<iframe#i", "&lt;iframe", $filecontents );
		$filecontents = preg_replace( "#<script#i", "&lt;script", $filecontents );
		$filecontents = str_replace( "<?", "&lt;?", $filecontents );
		$filecontents = str_replace( "?>", "?&gt;", $filecontents );
		$filecontents = str_replace( "$", "&#036;", $filecontents );
	
	    fwrite($filehandle, $filecontents);
	    fclose($filehandle);
	
	    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] .
	        "?mod=xfields&xfieldsaction=configure");
	    die();
	}


	$xf_inited = true;
}

$xfields = xfieldsload();
switch ($xfieldsaction) {
  case "configure":

	if( ! $user_group[$member_id['user_group']]['admin_xfields'] ) {
		msg( "error", $lang['index_denied'], $lang['index_denied'] );
		die();
	}

    switch ($xfieldssubaction) {
      case "delete":
        if (!isset($xfieldsindex)) {
          msg("error", $lang['xfield_error'], $lang['xfield_err_5'],"javascript:history.go(-1)");
        }
        msg("options", "info", "$lang[xfield_err_6]<br /><br /><input onclick=\"document.location='?mod=xfields&xfieldsaction=configure&xfieldsindex={$xfieldsindex}&xfieldssubaction=delete2&user_hash={$dle_login_hash}'\" type=\"button\" class=\"btn btn-green\" value=\"{$lang['opt_sys_yes']}\">&nbsp;&nbsp;<input onclick=\"document.location='?mod=xfields&xfieldsaction=configure'\" type=\"button\" class=\"btn btn-red\" value=\"{$lang['opt_sys_no']}\">");
        break;
      case "delete2":
        if (!isset($xfieldsindex)) {
          msg("error", $lang['xfield_error'], $lang['xfield_err_5'],"javascript:history.go(-1)");
        }
		$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '73', '{$xfields[$xfieldsindex][0]}')" );

        unset($xfields[$xfieldsindex]);
        @xfieldssave($xfields);
        break;
      case "add":
        $xfieldsindex = count($xfields);
        // Fall trough to edit
      case "edit":
        if (!isset($xfieldsindex)) {
          msg("error", $lang['xfield_error'], $lang['xfield_err_8'],"javascript:history.go(-1)");
        }
    
        if (!$editedxfield) {
          $editedxfield = $xfields[$xfieldsindex];
        } elseif (strlen(trim($editedxfield[0])) > 0 and
            strlen(trim($editedxfield[1])) > 0) {
          foreach ($xfields as $name => $value) {
            if ($name != $xfieldsindex and
                $value[0] == $editedxfield[0]) {
              msg("error", $lang['xfield_error'], $lang['xfield_err_9'],"javascript:history.go(-1)");
            }
          }
          $editedxfield[0] = totranslit(trim($editedxfield[0]));

			$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '74', '{$editedxfield[0]}')" );

		  if (!count($editedxfield[2])) $editedxfield[2][0] ="";
		  elseif (count($editedxfield[2]) > 1 AND $editedxfield[2][0] == "") unset($editedxfield[2][0]);

			$category_list = array();
		
			foreach ( $editedxfield[2] as $catval ) {
				if($catval) $category_list[] = intval($catval);
			}

		  $editedxfield[2] 	= implode(',', $category_list);

		  $editedxfield[3] = totranslit(trim($editedxfield[3]));

          if ($editedxfield[3] == "select") {
            $options = array();
            foreach (explode("\r\n", $editedxfield["4_select"]) as $name => $value) {
              $value = trim($value);
              if (!in_array($value, $options)) {
                $options[] = $value;
              }
            }
            if (count($options) < 2) {
            msg("error", $lang['xfield_error'], $lang['xfield_err_10'],"javascript:history.go(-1)");
            }
            $editedxfield[4] = implode("\r\n", $options);
          } else {
            $editedxfield[4] = $editedxfield["4_{$editedxfield[3]}"];
          }

          unset($editedxfield["2_custom"], $editedxfield["4_text"], $editedxfield["4_textarea"], $editedxfield["4_select"]);

          if ($editedxfield[3] == "select") {
            $editedxfield[5] = 0;
          } else {
            $editedxfield[5] = ($editedxfield[5] == "on" ? 1 : 0);
          }

          if ($editedxfield[3] == "text" OR $editedxfield[3] == "select") {
			$editedxfield[6] = ($editedxfield[6] == "on" ? 1 : 0);
          } else $editedxfield[6] = 0;

          if ($editedxfield[3] == "textarea") {
			$editedxfield[7] = ($editedxfield[7] == "on" ? 1 : 0);
          } else $editedxfield[7] = 0;

          if ($editedxfield[3] == "text" OR $editedxfield[3] == "textarea") {
			$editedxfield[8] = ($editedxfield[8] == "on" ? 1 : 0);
          } else $editedxfield[8] = 0;

          ksort($editedxfield);
          
          $xfields[$xfieldsindex] = $editedxfield;
          ksort($xfields);
          @xfieldssave($xfields);
          break;
        } else {
          msg("error", $lang['xfield_error'], $lang['xfield_err_11'],"javascript:history.go(-1)");
        }

        echoheader( "<i class=\"icon-reorder\"></i>".$lang['header_nf_1'], $lang['header_nf_2'] );
        $checked = ($editedxfield[5] ? " checked" : "");
        $checked2 = ($editedxfield[6] ? " checked" : "");
        $checked3 = ($editedxfield[7] ? " checked" : "");
        $checked4 = ($editedxfield[8] ? " checked" : "");
?>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" name="xfieldsform" class="form-horizontal">
      <script language="javascript">
      function ShowOrHideEx(id, show) {
        var item = null;
        if (document.getElementById) {
          item = document.getElementById(id);
        } else if (document.all) {
          item = document.all[id];
        } else if (document.layers){
          item = document.layers[id];
        }
        if (item && item.style) {
          item.style.display = show ? "" : "none";
        }
      }
      function onTypeChange(value) {
        ShowOrHideEx("default_text", value == "text");
        ShowOrHideEx("optional2", value == "text" || value == "select");
        ShowOrHideEx("default_textarea", value == "textarea");
        ShowOrHideEx("optional3", value == "textarea");
        ShowOrHideEx("optional4", value == "text" || value == "textarea");
        ShowOrHideEx("select_options", value == "select");
        ShowOrHideEx("optional", value != "select");
      }
      function onCategoryChange(value) {
        ShowOrHideEx("category_custom", value == "custom");
      }
      </script>
      <input type="hidden" name="mod" value="xfields">
	  <input type="hidden" name="user_hash" value="<?php echo $dle_login_hash; ?>">
      <input type="hidden" name="xfieldsaction" value="configure">
      <input type="hidden" name="xfieldssubaction" value="edit">
      <input type="hidden" name="xfieldsindex" value="<?php echo $xfieldsindex; ?>">
<div class="box">
  <div class="box-header">
    <div class="title"><?php echo $lang['xfield_title']; ?></div>
  </div>
  <div class="box-content">

	<div class="row box-section">
		<div class="form-group">
		  <label class="control-label col-lg-2"><?php echo $lang['xfield_xname']; ?></label>
		  <div class="col-lg-10">
			<input style="width:100%;max-width: 200px;" type="text" name="editedxfield[0]" value="<?php echo $editedxfield[0];?>" /> <i class="icon-warning-sign"></i> <?php echo $lang['xf_lat']; ?></span>
		  </div>
		 </div>	
		<div class="form-group">
		  <label class="control-label col-lg-2"><?php echo $lang['xfield_xdescr']; ?></label>
		  <div class="col-lg-10">
			<input style="width:100%;max-width: 350px;" type="text" name="editedxfield[1]" value="<?php echo $editedxfield[1];?>" />
		  </div>
		 </div>	
<?php
        $cat_options = CategoryNewsSelection(explode (',', $editedxfield[2]), 0, FALSE);
		if ($editedxfield[2] == "") $cats_value = "selected"; else $cats_value = "";

echo <<<HTML
		<div class="form-group">
		  <label class="control-label col-lg-2">{$lang['xfield_xcat']}</label>
		  <div class="col-lg-10">
			<select name="editedxfield[2][]" id="category" class="categoryselect" data-placeholder="{$lang['addnews_cat_sel']}" style="width:350px;;height:100px;" multiple><option value="" {$cats_value}>{$lang['xfield_xall']}</option>{$cat_options}</select>
		  </div>
		 </div>	
HTML;

?>
		<div class="form-group">
		  <label class="control-label col-lg-2"><?php echo $lang['xfield_xtype']; ?></label>
		  <div class="col-lg-10">
			<select class="uniform" name="editedxfield[3]" id="type" onchange="onTypeChange(this.value)" />
          <option value="text"<?php if($editedxfield[3] != "textarea") echo " selected"; else echo "";?>><?php echo $lang['xfield_xstr']; ?></option>
          <option value="textarea"<?php echo ($editedxfield[3] == "textarea") ? " selected" : "";?>><?php echo $lang['xfield_xarea']; ?></option>
          <option value="select"<?php echo ($editedxfield[3] == "select") ? " selected" : "";?>><?php echo $lang['xfield_xsel']; ?></option>
        </select>
		  </div>
		 </div>		 
		<div class="form-group" id="default_text">
		  <label class="control-label col-lg-2"><?php echo $lang['xfield_xfaul']; ?></label>
		  <div class="col-lg-10">
			<input style="width:100%;max-width: 350px;" type="text" name="editedxfield[4_text]" value="<?php if ($editedxfield[3] == "text") echo htmlspecialchars($editedxfield[4], ENT_QUOTES, $config['charset']); else echo ""; ?>" />
		  </div>
		 </div>	
		<div class="form-group" id="default_textarea">
		  <label class="control-label col-lg-2"><?php echo $lang['xfield_xfaul']; ?></label>
		  <div class="col-lg-10">
			<textarea style="width:100%;max-width: 350px;height: 100px;" name="editedxfield[4_textarea]"><?php echo ($editedxfield[3] == "textarea") ? $editedxfield[4] : "";?></textarea>
		  </div>
		 </div>	
		<div class="form-group" id="select_options">
		  <label class="control-label col-lg-2"><?php echo $lang['xfield_xfaul']; ?></label>
		  <div class="col-lg-10">
			<textarea style="width:100%;max-width: 350px; height: 100px;" name="editedxfield[4_select]"><?php if ($editedxfield[4]{0} == "\r") $editedxfield[4] = "\n".$editedxfield[4]; echo ($editedxfield[3] == "select") ? $editedxfield[4] : "";?></textarea><br><?php echo $lang['xfield_xfsel']; ?></td>
		  </div>
		 </div>	
		<div class="form-group">
		  <label class="control-label col-lg-2"></label>
		  <div class="col-lg-10">
			<div id="optional">
      <span><input class="icheck" type="checkbox" name="editedxfield[5]"<?php echo $checked; ?> id="editxfive" />
    <label for="editxfive"> <?php echo $lang['xfield_xw']; ?></label></span></div>
<div id="optional4">
      <span><input  class="icheck" type="checkbox" name="editedxfield[8]"<?php echo $checked4; ?> id="editx8" />
    <label for="editx8"> <?php echo $lang['opt_sys_sxfield']; ?>&nbsp;<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="right" data-content="<?php echo $lang['opt_sys_sxfieldd']; ?>" >?</span></label></span></div>
<div id="optional3">
      <span><input  class="icheck" type="checkbox" name="editedxfield[7]"<?php echo $checked3; ?> id="editx7" />
    <label for="editx7"> <?php echo $lang['xfield_xw4']; ?></label></span></div>
	<div id="optional2">
      <span><input class="icheck" type="checkbox" name="editedxfield[6]"<?php echo $checked2; ?> id="editxsixt" />
    <label for="editxsixt"> <?php echo $lang['xfield_xw2']; ?>&nbsp;<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="right" data-content="<?php echo $lang['xfield_xw3']; ?>" >?</span></label></span></div>
		  </div>
		 </div>			 
		 
	</div>
	
   </div>
<div class="box-footer padded">
<input type="submit" class="btn btn-green" value="<?php echo $lang['user_save']; ?>">
</div>
</div>
<script type="text/javascript">
$(function(){
	$('.categoryselect').chosen({allow_single_deselect:true, no_results_text: '<?php echo $lang['addnews_cat_fault'] ?>'});
});
</script>
</form>
    <script type="text/javascript">
    <!--
      var item_type = null;
      var item_category = null;
      if (document.getElementById) {
        item_type = document.getElementById("type");
        item_category = document.getElementById("category");
      } else if (document.all) {
        item_type = document.all["type"];
        item_category = document.all["category"];
      } else if (document.layers) {
        item_type = document.layers["type"];
        item_category = document.layers["category"];
      }
      if (item_type) {
        onTypeChange(item_type.value);
        onCategoryChange(item_category.value);
      }
    // -->
    </script>
<?php
        echofooter();
        break;

      default:

        echoheader( "<i class=\"icon-reorder\"></i>".$lang['header_nf_1'], $lang['header_nf_2'] );
?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get" name="xfieldsform">
<input type="hidden" name="mod" value="xfields">
<input type="hidden" name="xfieldsaction" value="configure">
<input type="hidden" name="xfieldssubactionadd" value="">
<input type="hidden" name="user_hash" value="<?php echo $dle_login_hash; ?>">
<div class="box">
  <div class="box-header">
    <div class="title"><?php echo $lang['xfield_xlist']; ?></div>
  </div>
  <div class="box-content">

	<div class="row box-section">
<?php
        if (count($xfields) == 0) {

          echo "<center><br /><br />{$lang['xfield_xnof']}<br /><br /></center>";

        } else {

			$x_list = "<ol class=\"dd-list\">";
	
			foreach ($xfields as $name => $value) {
	
				$cats_v = trim($value[2]) ? $value[2] : $lang['xfield_xall'];
	
				if ( $value[3] == "text" ) $type=$lang['xfield_xstr'];
				elseif($value[3] == "textarea") $type=$lang['xfield_xarea'];
				elseif($value[3] == "select") $type=$lang['xfield_xsel'];
	
				$req = $value[5] != 0 ? $lang['opt_sys_yes'] : $lang['opt_sys_no'];
	
				$x_list .= "<li class=\"dd-item\" data-id=\"{$name}\"><div class=\"dd-handle\"><b id=\"x_name\" class=\"s-el\">{$value[0]}</b><b id=\"x_cats\" class=\"s-el\">{$lang['xfield_xcat']}: {$cats_v}</b><b id=\"x_type\" class=\"s-el\">{$lang['xfield_xtype']}: {$type}</b><b class=\"s-el\">{$lang['xfield_xwt']}: {$req}</b><div style=\"float:right;\"><a href=\"?mod=xfields&xfieldsaction=configure&xfieldssubaction=edit&xfieldsindex={$name}&user_hash={$dle_login_hash}\"><i title=\"{$lang['cat_ed']}\" alt=\"{$lang['cat_ed']}\" class=\"icon-pencil bigger-130\"></i></a>&nbsp;&nbsp;<a class=\"maintitle\" href=\"?mod=xfields&xfieldsaction=configure&xfieldssubaction=delete&xfieldsindex={$name}&user_hash={$dle_login_hash}\"><i title=\"{$lang['cat_del']}\" alt=\"{$lang['cat_del']}\" class=\"icon-trash bigger-130 status-error\"></i></a></div></div></li>";		
	
			}

			$x_list .= "</ol>";
			echo "<div class=\"dd\">{$x_list}</div>";


        }
?>
	</div>
	
   </div>
	<div class="box-footer padded">
		<div class="pull-left">
	<?php if (count($xfields) > 0) { ?>
		<button id="xfsort" class="btn btn-blue"><?php echo $lang['xf_posi']; ?></button>
	<?php } ?>
		<input type="submit" class="btn btn-green" value=" <?php echo $lang['b_create']; ?> " onclick="document.forms['xfieldsform'].xfieldssubactionadd.value = 'add';">
		</div>
		<div class="pull-right">
		<a class="status-info" onclick="javascript:Help('xfields'); return false;" href="#"><?php echo $lang['xfield_xhelp']; ?></a>
		</div>
	</div>
</div>
  </form>
<script>
	$(document).ready(function(){

		$('.dd').nestable({
			maxDepth: 1
		});
		
		$('.dd-handle a').on('mousedown', function(e){
			e.stopPropagation();
		});

		$('#xfsort').click(function(){
			var xfsort =  window.JSON.stringify($('.dd').nestable('serialize'));
			var url = "action=xfsort&user_hash=<?php echo $dle_login_hash; ?>&list="+xfsort;

			ShowLoading('');
			$.post('engine/ajax/adminfunction.php', url, function(data){
	
				HideLoading('');
	
				if (data == 'ok') {

					document.location.reload(false);

				} else {

					DLEalert('<?php echo $lang['cat_sort_fail']; ?>', '<?php echo $lang['p_info']; ?>');

				}
	
			});

			return false;

		});

	});
</script>
<?php
      echofooter();
    }
    break;
case "list":
    $output = "";
    if (!isset($xfieldsid)) $xfieldsid = "";
    $xfieldsdata = xfieldsdataload ($xfieldsid);
    foreach ($xfields as $name => $value) {
      $fieldname = $value[0];
      if (!$xfieldsadd) {
        $fieldvalue = $xfieldsdata[$value[0]];

		if ( $xfieldmode == "site" ) $ed_mode = $config['allow_site_wysiwyg']; else $ed_mode = $config['allow_admin_wysiwyg'];

		$smode = $parse->safe_mode;

		if ( $value[8] ) {
			$parse->safe_mode = true;
		}

		if ($row['allow_br'])
        	$fieldvalue = $parse->decodeBBCodes($fieldvalue, false);
		else
        	$fieldvalue = $parse->decodeBBCodes($fieldvalue, true, $ed_mode);

		$parse->safe_mode = $smode;

      } elseif ($value[3] != "select") {
        $fieldvalue = $value[4];
      }

      $holderid = "xfield_holder_$fieldname";

      if ($value[3] == "textarea") {      

		$params = "";
		$panel = "<!--panel-->";

		if ( $value[7] ) {
			if ($bb_editor) $params = "onfocus=\"setFieldName(this.id)\" "; else $params = "class=\"wysiwygeditor\" ";
		} else $panel = "";

		if (!$value[5]) { 
			$uid = "uid=\"essential\" ";
			$params .= "rel=\"essential\" ";
		} else { 
			$uid = "";
		}

		if ($xfieldmode == "site") {

			if ( $value[7] ) {
				if ( $bb_editor ) $class_name = "bb-editor"; else $class_name = "wseditor";

	        $output .= <<<HTML
<tr id="$holderid" {$uid}>
<td class="xfields" colspan="2"><b>{$value[1]}:</b>&nbsp;[if-optional]({$lang['xf_not_notig']})[/if-optional][not-optional]({$lang['xf_notig']})[/not-optional]<div class="{$class_name}">{$panel}<textarea name="xfield[$fieldname]" id="xf_$fieldname" {$params}>$fieldvalue</textarea></div></td></tr>
HTML;

			} else { 

	        $output .= <<<HTML
<tr id="$holderid" {$uid}>
<td class="addnews">$value[1]:<br />[if-optional]({$lang['xf_not_notig']})[/if-optional][not-optional]({$lang['xf_notig']})[/not-optional]</td>
<td class="xfields"><textarea name="xfield[$fieldname]" id="xf_$fieldname" style="width:100%;height: 170px;" {$params}>{$fieldvalue}</textarea></td></tr>
HTML;

			}


		} else {

	        $output .= <<<HTML
<div id="$holderid" class="form-group" {$uid}>
  <label class="control-label col-lg-2">{$value[1]}:[if-optional]<div class="note large"><i class="icon-warning-sign"></i> {$lang['xf_not_notig']}</div>[/if-optional][not-optional]<div class="note large"><i class="icon-warning-sign"></i> {$lang['xf_notig']}</div>[/not-optional]</label>
  <div class="col-lg-10">
     <div class="editor-panel">{$panel}<textarea style="width:100%;height:200px;" name="xfield[$fieldname]" id="xf_$fieldname" {$params}>{$fieldvalue}</textarea></div>
  </div>
</div>
HTML;

		}

      } elseif ($value[3] == "text") {

		$fieldvalue = str_replace('"', '&quot;', $fieldvalue);
		$fieldvalue = str_replace('&amp;', '&', $fieldvalue);

		if (!$value[5]) { 
			$params = "rel=\"essential\" "; 
			$uid = "uid=\"essential\" "; 

		} else { 

			$params = ""; 
			$uid = "";

		}

		if ($value[6]) {
			$params .= "data-rel=\"links\" "; 
		}

		if ($xfieldmode == "site") {
		
$output .= <<<HTML
<tr id="$holderid" {$uid}>
<td class="addnews">$value[1]:</td>
<td class="xfields" colspan="2"><input type="text" name="xfield[$fieldname]" id="xf_$fieldname" value="$fieldvalue" {$params}/>&nbsp;&nbsp;[if-optional]({$lang['xf_not_notig']})[/if-optional][not-optional]({$lang['xf_notig']})[/not-optional]</td>
</tr>
HTML;

		} else {
		
$output .= <<<HTML
<div id="$holderid" class="form-group" {$uid}>
  <label class="control-label col-lg-2">{$value[1]}:</label>
  <div class="col-lg-10">
     <input type="text" style="width:437px;" name="xfield[$fieldname]" id="xf_$fieldname" value="$fieldvalue" {$params}/>&nbsp;&nbsp;[if-optional]<span class="note large"> <i class="icon-warning-sign"></i> {$lang['xf_not_notig']}</span>[/if-optional][not-optional]<span class="note large"> <i class="icon-warning-sign"></i> {$lang['xf_notig']}</span>[/not-optional]
  </div>
</div>
HTML;

		}
		
      } elseif ($value[3] == "select") {

		if ($xfieldmode == "site") {

			$output .= <<<HTML
<tr id="$holderid">
<td class="addnews">$value[1]:</td>
<td class="xfields" colspan="2"><select name="xfield[$fieldname]">
HTML;

		} else {

			$output .= <<<HTML
<div id="$holderid" class="form-group">
  <label class="control-label col-lg-2">{$value[1]}:</label>
  <div class="col-lg-10">
     <select class="uniform" style="min-width:100px;" name="xfield[$fieldname]">
HTML;
		}
		
		if ( !isset($fieldvalue) ) $fieldvalue = "";

		$fieldvalue = str_replace('&amp;', '&', $fieldvalue);
		$fieldvalue = str_replace('&quot;', '"', $fieldvalue);

        foreach (explode("\r\n", $value[4]) as $index => $value) {
		  $value = str_replace("'", "&#039;", $value);
          $output .= "<option value=\"$index\"" . ($fieldvalue == $value ? " selected" : "") . ">$value</option>\r\n";
        }
		if ($xfieldmode == "site") {
$output .= <<<HTML
</select></td>
</tr>
HTML;

		} else {
		
		$output .= "</select></div></div>";

		}
      }
      $output = preg_replace("'\\[if-optional\\](.*?)\\[/if-optional\\]'s", $value[5] ? "\\1" : "", $output);
      $output = preg_replace("'\\[not-optional\\](.*?)\\[/not-optional\\]'s", $value[5] ? "" : "\\1", $output);
      $output = preg_replace("'\\[if-add\\](.*?)\\[/if-add\\]'s", ($xfieldsadd) ? "\\1" : "", $output);
      $output = preg_replace("'\\[if-edit\\](.*?)\\[/if-edit\\]'s", (!$xfieldsadd) ? "\\1" : "", $output);
    }
    $output .= <<<HTML

<script type="text/javascript">
<!--
    onCategoryChange($('#category'));
// -->
</script>
HTML;
    break;
  case "init":

    $postedxfields = $_POST['xfield'];
    $newpostedxfields = array();
	$filecontents = array ();

	if ($ajax_edit == "yes") {
		foreach ($_POST['xfield'] as $key => $val )	{

			$postedxfields[$key] = convert_unicode( $val, $config['charset'] );

		}
	}
	
	foreach ($category as $cats_explode) {
		foreach ($xfields as $name => $value) {
			if ($value[2] != "" and !in_array($cats_explode, explode(",", $value[2]))) {
				continue;
			}

			if ($value[5] == 0 AND $postedxfields[$value[0]] == "" AND $value[3] != "select") {
	
				if ($add_module == "yes")
					$stop .= $lang['xfield_xerr1'];
				else
					msg("error", "error", $lang['xfield_xerr1'], "javascript:history.go(-1)");
		
			}

			if ($value[3] == "select") {
				$options = explode("\r\n", $value[4]);
		        $postedxfields[$value[0]] = $options[$_POST['xfield'][$value[0]]];
			}

			if (($value[8] == 1 OR $value[6] == 1 OR $value[3] == "select") AND $postedxfields[$value[0]] != "" ) {

				$newpostedxfields[$value[0]] = trim( htmlspecialchars(strip_tags( stripslashes($postedxfields[$value[0]]) ), ENT_QUOTES, $config['charset'] ));
				$newpostedxfields[$value[0]] = str_ireplace( "{include", "&#123;include", $newpostedxfields[$value[0]] );


			} elseif ( $postedxfields[$value[0]] != "" ) {

				if ($add_module == "yes") {

					if( $config['allow_site_wysiwyg'] OR $allow_br != '1' ) {
						
						$newpostedxfields[$value[0]] = $parse->BB_Parse($parse->process($postedxfields[$value[0]]));
					
					} else {
						
						$newpostedxfields[$value[0]] = $parse->BB_Parse($parse->process($postedxfields[$value[0]]), false);
					
					}

				} else {

					if( $config['allow_admin_wysiwyg'] OR $allow_br != '1' ) {
						
						$newpostedxfields[$value[0]] = $parse->BB_Parse($parse->process($postedxfields[$value[0]]));
					
					} else {
						
						$newpostedxfields[$value[0]] = $parse->BB_Parse($parse->process($postedxfields[$value[0]]), false);
					
					}

				}

			}

		}
	}

    $postedxfields = $newpostedxfields;

	if( !empty( $postedxfields ) ) {
		foreach ( $postedxfields as $xfielddataname => $xfielddatavalue ) {

			if( $xfielddatavalue == "" ) {
				continue;
			}
				
			$xfielddataname = str_replace( "|", "&#124;", $xfielddataname );
			$xfielddataname = str_replace( "\r\n", "__NEWL__", $xfielddataname );
			$xfielddatavalue = str_replace( "|", "&#124;", $xfielddatavalue );
			$xfielddatavalue = str_replace( "\r\n", "__NEWL__", $xfielddatavalue );
			$filecontents[] = "$xfielddataname|$xfielddatavalue";
		}
		
		if ( count($filecontents) ) $filecontents = $db->safesql(implode( "||", $filecontents )); else $filecontents = '';
	
	} else $filecontents = '';

    break;
  case "delete":
    break;
  case "templatereplacepreview":
	if (isset ($_POST["xfield"])) $xfield = $_POST["xfield"];
    $xfieldsoutput = $xfieldsinput;

    foreach ($xfields as $value) {
      $preg_safe_name = preg_quote($value[0], "'");

      if ($value[3] == "select") {
        $options = explode("\r\n", $value[4]);
        $xfield[$value[0]] = $options[$xfield[$value[0]]];
      }

	  $parse->allow_code = true;

		if (($value[8] == 1 OR $value[3] == "select") AND $xfield[$value[0]] != "" ) {

			$xfield[$value[0]] = trim( htmlspecialchars(strip_tags( stripslashes($xfield[$value[0]]) ), ENT_QUOTES, $config['charset'] ));

		} elseif ( $xfield[$value[0]] != "" ) {

			if ($add_module == "yes") {
				if( $config['allow_site_wysiwyg'] OR $allow_br != '1' ) {
						
					$xfield[$value[0]] = $parse->BB_Parse($parse->process($xfield[$value[0]]));
					
				} else {
						
					$xfield[$value[0]] = $parse->BB_Parse($parse->process($xfield[$value[0]]), false);
					
				}
			} else {
				if( $config['allow_admin_wysiwyg'] OR $allow_br != '1' ) {
						
					$xfield[$value[0]] = $parse->BB_Parse($parse->process($xfield[$value[0]]));
					
				} else {
						
					$xfield[$value[0]] = $parse->BB_Parse($parse->process($xfield[$value[0]]), false);
					
				}
			}

		}

      $xfield[$value[0]] = stripslashes($xfield[$value[0]]);

       if (empty($xfield[$value[0]])) {
          $xfieldsoutput = preg_replace("'\\[xfgiven_{$preg_safe_name}\\].*?\\[/xfgiven_{$preg_safe_name}\\]'is", "", $xfieldsoutput);
          $xfieldsoutput = str_replace( "[xfnotgiven_{$value[0]}]", "", $xfieldsoutput );
          $xfieldsoutput = str_replace( "[/xfnotgiven_{$value[0]}]", "", $xfieldsoutput );
       } else {
          $xfieldsoutput = preg_replace( "'\\[xfnotgiven_{$preg_safe_name}\\](.*?)\\[/xfnotgiven_{$preg_safe_name}\\]'is", "", $xfieldsoutput );
          $xfieldsoutput = str_replace( "[xfgiven_{$value[0]}]", "", $xfieldsoutput );
          $xfieldsoutput = str_replace( "[/xfgiven_{$value[0]}]", "", $xfieldsoutput );
       }

      if ( preg_match( "#\\[xfvalue_{$preg_safe_name} limit=['\"](.+?)['\"]\\]#i", $xfieldsoutput, $matches ) ) {
			$count= intval($matches[1]);

			$xfield[$value[0]] = str_replace( "</p><p>", " ", $xfield[$value[0]] );
			$xfield[$value[0]] = strip_tags( $xfield[$value[0]], "<br>" );
			$xfield[$value[0]] = trim(str_replace( "<br>", " ", str_replace( "<br />", " ", str_replace( "\n", " ", str_replace( "\r", "", $xfield[$value[0]] ) ) ) ));

			if( $count AND dle_strlen( $xfield[$value[0]], $config['charset'] ) > $count ) {
							
				$xfield[$value[0]] = dle_substr( $xfield[$value[0]], 0, $count, $config['charset'] );
							
				if( ($temp_dmax = dle_strrpos( $xfield[$value[0]], ' ', $config['charset'] )) ) $xfield[$value[0]] = dle_substr( $xfield[$value[0]], 0, $temp_dmax, $config['charset'] );
						
			}

			$xfieldsoutput = str_replace($matches[0], $xfield[$value[0]], $xfieldsoutput);

      }

	  $xfieldsoutput = preg_replace("'\\[xfvalue_{$preg_safe_name}\\]'i", $xfield[$value[0]], $xfieldsoutput);

    }
    break;
  case "categoryfilter":
    $categoryfilter = <<<HTML
  <script type="text/javascript">
  function ShowOrHideEx(id, show) {
    var item = null;

    if (document.getElementById) {
      item = document.getElementById(id);
    } else if (document.all) {
      item = document.all[id];
    } else if (document.layers){
      item = document.layers[id];
    }
    if (item && item.style) {
      item.style.display = show ? "" : "none";
    }
  }

  function onCategoryChange(obj) {

	var value = $(obj).val();

	if ($.isArray(value)) {

HTML;


    foreach ($xfields as $value) {

      if ( $value[2] ) {

		$categories = explode(",", $value[2]);
		$temp_array = array();

		foreach ($categories as $temp_value) {

			$temp_array[] = "jQuery.inArray('{$temp_value}', value) != -1";

		}

		$categories = implode(" || ", $temp_array);

        $categoryfilter .= "ShowOrHideEx(\"xfield_holder_{$value[0]}\", {$categories} );\r\n";
      }
    }

$categoryfilter .= <<<HTML
	} else {

HTML;

    foreach ($xfields as $value) {
      $categories = str_replace(",", "||value==", $value[2]);
      if ($categories) {
        $categoryfilter .= "ShowOrHideEx(\"xfield_holder_{$value[0]}\", value == $categories);\r\n";
      }
    }

    $categoryfilter .= "}  }\r\n</script>";
    break;
  default:
  if (function_exists('msg'))
    msg("error", $lang['xfield_error'], $lang['xfield_xerr2']);
}
?>