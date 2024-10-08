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
 Файл: fullnews.php
-----------------------------------------------------
 Назначение: WYSIWYG для админпанели
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

if (!isset($row['template'])) $row['template'] = "";
$p_name = urlencode($member_id['name']);

if( $config['allow_static_wysiwyg'] == 1 ) {

echo <<<HTML
<script type="text/javascript" src="engine/editor/scripts/language/{$lang['wysiwyg_language']}/editor_lang.js"></script>
<script type="text/javascript" src="engine/editor/scripts/innovaeditor.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){

	create_editor('');

	setTimeout(function() {
		
	    for(var i = 0;i < oUtil.arrEditor.length;i++) {
	      var oEditor = eval("idContent" + oUtil.arrEditor[i]);
	      var sHTML;
	      if(navigator.appName.indexOf("Microsoft") != -1) {
	        sHTML = oEditor.document.documentElement.outerHTML
	      }else {
	        sHTML = getOuterHTML(oEditor.document.documentElement)
	      }
	      sHTML = sHTML.replace(/FONT-FAMILY/g, "font-family");
	      var urlRegex = /font-family?:.+?(\;|,|")/g;
	      var matches = sHTML.match(urlRegex);
	      if(matches) {
	        for(var j = 0, len = matches.length;j < len;j++) {
	          var sFont = matches[j].replace(/font-family?:/g, "").replace(/;/g, "").replace(/,/g, "").replace(/"/g, "");
			  sFont=sFont.split("'").join('');
	          sFont = jQuery.trim(sFont);
	          var sFontLower = sFont.toLowerCase();
	          if(sFontLower != "serif" && sFontLower != "arial" && sFontLower != "arial black" && sFontLower != "bookman old style" && sFontLower != "comic sans ms" && sFontLower != "courier" && sFontLower != "courier new" && sFontLower != "garamond" && sFontLower != "georgia" && sFontLower != "impact" && sFontLower != "lucida console" && sFontLower != "lucida sans unicode" && sFontLower != "ms sans serif" && sFontLower != "ms serif" && sFontLower != "palatino linotype" && sFontLower != "tahoma" && sFontLower != 
	          "times new roman" && sFontLower != "trebuchet ms" && sFontLower != "verdana") {
	            sURL = "http://fonts.googleapis.com/css?family=" + sFont + "&subset=latin,cyrillic";
	            var objL = oEditor.document.createElement("LINK");
	            objL.href = sURL;
	            objL.rel = "StyleSheet";
	            oEditor.document.documentElement.childNodes[0].appendChild(objL)
	          }
	        }
	      }
	    }
	}, 100);

});

function create_editor( root ) {

	var use_br = false;
	var use_div = true;

	oUtil.initializeEditor("wysiwygeditor",  {
		width: "100%", 
		height: "400", 
		css: root + "engine/editor/scripts/style/default.css",
		useBR: use_br,
		useDIV: use_div,
		groups:[
			["grpEdit1", "", ["Paragraph", "TextDialog", "FontDialog", "Subscript", "Superscript", "ForeColor", "BackColor", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "DLEPasteText", "Styles", "RemoveFormat"]],
			["grpEdit2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
			["grpEdit3", "", ["TableDialog", "DLESmiles", "FlashDialog", "CharsDialog", "Line", "BRK", "LinkDialog", "DLELeech", "ImageDialog", "DLEUpload", "YoutubeDialog"]],
			["grpEdit4", "", ["DLEQuote", "DLECode", "DLEHide", "DLESpoiler", "CustomTag", "BRK", "DLEVideo", "DLEAudio", "DLEMedia", "HTML5Video", "DLETypograf"]],
			["grpEdit5", "", ["SearchDialog", "SourceDialog", "BRK", "Undo", "Redo"]]
	    ],
		arrCustomButtons:[
			["DLEUpload", "media_upload('template', '{$p_name}', '{$row['id']}', '1')", "{$lang['bb_t_up']}", "dle_upload.gif"],
			["DLESmiles", "modalDialog('"+ root +"engine/editor/emotions.php',250,160)", "{$lang['bb_t_emo']}", "btnEmoticons.gif"],
			["DLEPasteText", "modalDialog('"+ root +"engine/editor/scripts/common/webpastetext.htm',450,380)", "{$lang['paste_text']}", "btnPaste.gif"],
			["DLETypograf", "tag_typograf()", "{$lang['bb_t_t']}", "dle_tt.gif"],
			["DLEQuote", "DLEcustomTag('[quote]', '[/quote]')", "{$lang['bb_t_quote']}", "dle_quote.gif"],
			["DLECode", "DLEcustomTag('[code]', '[/code]')", "{$lang['bb_t_code']}", "dle_code.gif"],
			["DLEHide", "DLEcustomTag('[hide]', '[/hide]')", "{$lang['bb_t_hide']}", "dle_hide.gif"],
			["DLESpoiler", "DLEcustomTag('[spoiler]', '[/spoiler]')", "{$lang['bb_t_spoiler']}", "dle_spoiler.gif"],
			["DLELeech", "DLEcustomTag('[leech=http://]', '[/leech]')", "{$lang['bb_t_leech']}", "dle_leech.gif"],
			["HTML5Video", "modalDialog('"+ root +"engine/editor/scripts/common/webvideo.htm',690,330)", "HTML5 Video", "btnMedia.gif"],
			["DLEVideo", "modalDialog('"+ root +"engine/editor/scripts/common/webbbvideo.htm',400,250)", "{$lang['bb_t_video']} (BB Codes)", "dle_video.gif"],
			["DLEAudio", "modalDialog('"+ root +"engine/editor/scripts/common/webbbaudio.htm',400,200)", "{$lang['bb_t_audio']} (BB Codes)", "dle_mp3.gif"],
			["DLEMedia", "modalDialog('"+ root +"engine/editor/scripts/common/webbbmedia.htm',400,250)", "{$lang['bb_t_yvideo']} (BB Codes)", "dle_media.gif"]
		],
		arrCustomTag: [
			["{$lang['bb_t_br']}", "{PAGEBREAK}"],
	        ["{$lang['bb_t_p']}", "[page=1][/page]"]
		]
		}
	);	
};

function tag_typograf() {

	ShowLoading('');

	var oEditor = oUtil.oEditor;
	var obj = oUtil.obj;

	obj.saveForUndo();
    oEditor.focus();
    obj.setFocus();

	var txt = obj.getXHTMLBody();

	$.post("engine/ajax/typograf.php", {txt: txt}, function(data){
	
		HideLoading('');
	
		obj.loadHTML(data); 
	
	});

};
</script>
    <div class="editor-panel"><textarea name="template" id="template" class="wysiwygeditor" style="width:98%;height:300px;">{$row['template']}</textarea></div>
HTML;


} else {

echo <<<HTML
<script type="text/javascript" src="engine/editor/jscripts/tiny_mce/tinymce.min.js"></script>
<script type="text/javascript">
$(function(){

	tinymce.init({
		selector: 'textarea.wysiwygeditor',
		language : "{$lang['wysiwyg_language']}",
		width : "100%",
		height : "400",
		theme: "modern",
		plugins: ["advlist autolink lists link image charmap anchor searchreplace visualblocks visualchars media nonbreaking table contextmenu emoticons paste textcolor colorpicker code spellchecker"],
		relative_urls : false,
		convert_urls : false,
		remove_script_host : false,
		toolbar_items_size: 'small',
		image_advtab: true,
		extended_valid_elements : "noindex,div[align|class|style|id|title]",
		custom_elements : 'noindex',

		menubar: "edit insert view format table tools",
		toolbar1: "fontselect fontsizeselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | dleupload link unlink dleemo image | spellchecker | bullist numlist | forecolor backcolor",


		spellchecker_language : "ru",
		spellchecker_languages : "Russian=ru,Ukrainian=uk,English=en",
		spellchecker_rpc_url : "http://speller.yandex.net/services/tinyspell",
		content_css : "{$config['http_home_url']}engine/editor/css/content.css",

		setup : function(ed) {
			ed.addMenuItem('dleleech', {
				text: '{$lang['bb_t_leech']}',
				context: 'insert',
				onclick: function() {
					ed.execCommand('mceReplaceContent',false,'[leech=http://]' + ed.selection.getContent() + '[/leech]');
				}
			});	
			ed.addMenuItem('dlequote', {
				text: '{$lang['bb_t_quote']}',
				context: 'insert',
				onclick: function() {
					ed.execCommand('mceReplaceContent',false,'[quote]' + ed.selection.getContent() + '[/quote]');
				}
			});		
			ed.addMenuItem('dlehide', {
				text: '{$lang['bb_t_hide']}',
				context: 'insert',
				onclick: function() {
					ed.execCommand('mceReplaceContent',false,'[hide]' + ed.selection.getContent() + '[/hide]');
				}
			});	
			ed.addMenuItem('dlecode', {
				text: '{$lang['bb_t_code']}',
				context: 'insert',
				onclick: function() {
					ed.execCommand('mceReplaceContent',false,'[code]' + ed.selection.getContent() + '[/code]');
				}
			});
			ed.addMenuItem('dlemp', {
				text: '{$lang['bb_t_video']} (BB Codes)',
				context: 'insert',
				onclick: function() {
					ed.execCommand('mceInsertContent',false,"[video=http://]");
				}
			});
			ed.addMenuItem('dletube', {
				text: '{$lang['bb_t_yvideo']} (Youtube)',
				context: 'insert',
				onclick: function() {
					ed.execCommand('mceInsertContent',false,"[media=http://]");
				}
			});	

			ed.addMenuItem('dlespoiler', {
				text: '{$lang['bb_t_spoiler']}',
				context: 'insert',
				onclick: function() {
					ed.execCommand('mceReplaceContent',false,'[spoiler]' + ed.selection.getContent() + '[/spoiler]');
				}
			});	
			
			ed.addMenuItem('dlebreak', {
				text: '{$lang['bb_t_br']}',
				context: 'tools',
				onclick: function() {
					ed.execCommand('mceInsertContent',false,'{PAGEBREAK}');
				}
			});
			ed.addMenuItem('dlepage', {
				text: '{$lang['bb_t_p']}',
				context: 'tools',
				onclick: function() {
					ed.execCommand('mceReplaceContent',false,'[page=1]' + ed.selection.getContent() + '[/page]');
				}
			});

			ed.addButton('dleupload', {
				title : '{$lang['bb_t_up']}',
				image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/skins/dle_upload.gif',
				onclick : function() {
					media_upload('template', '{$p_name}', '{$row['id']}', '2');
				}
	        });

			ed.addButton('dleemo', {
				title : '{$lang['bb_t_emo']}',
				icon : 'emoticons',
				onclick : function() {
					ed.windowManager.open({
					    title: "{$lang['bb_t_emo']}",
					    url: '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/plugins/emoticons/emotions.php',
					    width: 250,
					    height: 160
					});
				}
	        });
   		 }


	});

});
</script>
    <div class="editor-panel"><textarea name="template" id="template" class="wysiwygeditor" style="width:98%;height:300px;">{$row['template']}</textarea></div>
HTML;

}
?>