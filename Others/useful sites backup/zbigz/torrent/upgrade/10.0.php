<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$config['version_id'] = "10.1";
$config['create_metatags'] = "1";
$config['admin_allowed_ip'] = "";
$config['related_only_cats'] = "0";
$config['allow_links'] = "1";

$tableSchema = array();

$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `allow_lostpassword` TINYINT(1) NOT NULL DEFAULT '1'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `spamfilter` TINYINT(1) NOT NULL DEFAULT '2'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_category` ADD `show_sub` TINYINT(1) NOT NULL DEFAULT '0'";
$tableSchema[] = "UPDATE " . PREFIX . "_usergroups SET `spamfilter` = '{$config['sec_addnews']}'";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_links";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_links (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `only_one` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "ALTER TABLE `" . PREFIX . "_files` CHANGE `dcount` `dcount` MEDIUMINT(8) NOT NULL DEFAULT '0'";

foreach($tableSchema as $table) {
	$db->query ($table);
}


$handler = fopen(ENGINE_DIR.'/data/config.php', "w") or die("Désolé, mais vous ne pouvez pas écrire des informations dans un fichier <b>.engine/data/config.php</b>.<br />Vérifiez le CHMOD!");
fwrite($handler, "<?PHP \n\n//System Configurations\n\n\$config = array (\n\n");
foreach($config as $name => $value)
{
	fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
}
fwrite($handler, ");\n\n?>");
fclose($handler);


require_once(ENGINE_DIR.'/data/videoconfig.php');

$video_config['use_html5'] = "0";

$con_file = fopen(ENGINE_DIR.'/data/videoconfig.php', "w+") or die("Désolé, mais vous ne pouvez pas écrire des informations dans un fichier <b>.engine/data/videoconfig.php</b>.<br />Vérifiez le CHMOD!");
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

	$error_info = "Toutes les demandes prévues: <b>".$db->query_num."</b> L'échec exécuter des requêtes: <b>".$db->error_count."</b>. Peut-être qu'ils ont fait auparavant.<br /><br /><div class=\"quote\"><b>Aucune liste des requêtes:</b><br /><br />"; 

	foreach ($db->query_list as $value) {

		$error_info .= $value['query']."<br /><br />";

	}

	$error_info .= "</div>";

} else $error_info = "";

msgbox("info","informations", "<form action=\"index.php\" method=\"GET\">Mettre à jour la version de base de données<b>10.0</b> à la version <b>10.1</b> réussi.<br />{$error_info}<br />Cliquez sur Suivant pour continuer le processus de script de mise à niveau <br /><br /><input type=\"hidden\" name=\"next\" value=\"next\"><input class=\"btn btn-success\" type=\"submit\" value=\"Suivant ...\"></form>");
?>