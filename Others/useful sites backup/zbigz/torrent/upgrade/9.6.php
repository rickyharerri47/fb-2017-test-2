<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$config['version_id'] = "9.7";
$config['reg_multi_ip'] = "1";
$config['top_number'] = "10";
$config['tags_number'] = "40";
$config['mail_title'] = "";
$config['o_seite'] = "0";
$config['online_status'] = "1";
$config['avatar_size'] = "100";

if ( $config['allow_admin_wysiwyg'] == "yes" ) $config['allow_admin_wysiwyg'] = "1"; else $config['allow_admin_wysiwyg'] = "0";
if ( $config['allow_static_wysiwyg'] == "yes" ) $config['allow_static_wysiwyg'] = "1"; else $config['allow_static_wysiwyg'] = "0";
if ( $config['allow_site_wysiwyg'] == "yes" ) $config['allow_site_wysiwyg'] = "1"; else $config['allow_site_wysiwyg'] = "0";
if ( $config['allow_comments_wysiwyg'] == "yes" ) $config['allow_comments_wysiwyg'] = "1"; else $config['allow_comments_wysiwyg'] = "0";

$files_type = $config['files_type'];
$max_file_size = $config['max_file_size'];
$files_max_speed = $config['files_max_speed'];

unset($config['files_type']);
unset($config['max_file_size']);
unset($config['files_max_speed']);

$tableSchema = array();

$tableSchema[] = "ALTER TABLE `" . PREFIX . "_complaint` ADD `date` INT(11) UNSIGNED NOT NULL DEFAULT '0'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `pm_question` TINYINT(1) NOT NULL DEFAULT '0'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `captcha_feedback` TINYINT(1) NOT NULL DEFAULT '1'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `feedback_question` TINYINT(1) NOT NULL DEFAULT '0'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `files_type` VARCHAR(255) NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `max_file_size` MEDIUMINT(9) NOT NULL DEFAULT '0'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `files_max_speed` SMALLINT(6) NOT NULL DEFAULT '0'";
$tableSchema[] = "UPDATE " . PREFIX . "_usergroups SET `files_type` = '{$files_type}' WHERE id != '5'";
$tableSchema[] = "UPDATE " . PREFIX . "_usergroups SET `max_file_size` = '{$max_file_size}' WHERE id != '5'";
$tableSchema[] = "UPDATE " . PREFIX . "_usergroups SET `files_max_speed` = '{$files_max_speed}'";

foreach($tableSchema as $table) {
	$db->query ($table);
}


$handler = fopen(ENGINE_DIR.'/data/config.php', "w") or die("Désolé, mais vous ne pouvez pas écrire dans le fichier <b>.engine/data/config.php</b>.<br />Vérifiez les autorisations CHMOD!");
fwrite($handler, "<?PHP \n\n//System Configurations\n\n\$config = array (\n\n");
foreach($config as $name => $value)
{
	fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
}
fwrite($handler, ");\n\n?>");
fclose($handler);


require_once(ENGINE_DIR.'/data/videoconfig.php');

$video_config['audio_width'] = "300";

$con_file = fopen(ENGINE_DIR.'/data/videoconfig.php', "w+") or die("Désolé, mais vous ne pouvez pas écrire dans le fichier <b>.engine/data/videoconfig.php</b>.<br />Vérifiez les autorisations CHMOD!");
fwrite( $con_file, "<?PHP \n\n//Videoplayers Configurations\n\n\$video_config = array (\n\n" );
foreach ( $video_config as $name => $value ) {
		
	fwrite( $con_file, "'{$name}' => \"{$value}\",\n\n" );
	
}
fwrite( $con_file, ");\n\n?>" );
fclose($con_file);

$fdir = opendir( ENGINE_DIR . '/cache/system/' );
while ( $file = readdir( $fdir ) ) {
	if( $file != '.' and $file != '..' and $file != '.htaccess' ) {
		@unlink( ENGINE_DIR . '/cache/system/' . $file );
		
	}
}

@unlink(ENGINE_DIR.'/data/snap.db');

clear_cache();

if ($db->error_count) {

	$error_info = "Toutes les demandes prévues: <b>".$db->query_num."</b> Impossible d'exécuter la requête: <b>".$db->error_count."</b>. Peut-être qu'ils ont déjà effectué plus tôt.<br /><br /><div class=\"quote\"><b>Список не выполненных запросов:</b><br /><br />"; 

	foreach ($db->query_list as $value) {

		$error_info .= $value['query']."<br /><br />";

	}

	$error_info .= "</div>";

} else $error_info = "";

msgbox("info","informations", "<form action=\"index.php\" method=\"GET\">Mise à niveau de la base de données de la version <b>9.6</b> à la version <b>9.7</b> Réussi.<br />{$error_info}<br />Cliquez sur Suivant pour continuer le processus de mise à niveau du script<br /><br /><input type=\"hidden\" name=\"next\" value=\"next\"><input class=\"edit\" type=\"submit\" value=\"Suivant ...\"></form>");
?>