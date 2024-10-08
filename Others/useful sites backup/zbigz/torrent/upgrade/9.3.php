<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$config['version_id'] = "9.4";
$config['auth_metod'] = "0";
$config['comments_ajax'] = "0";
$config['create_catalog'] = "0";
$config['mobile_news'] = "10";
$config['reg_question'] = "0";

$tableSchema = array();

$tableSchema[] = "ALTER TABLE `" . PREFIX . "_static` ADD `allow_count` TINYINT(1) NOT NULL DEFAULT '1'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_complaint` CHANGE `to` `to` VARCHAR(255) NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `news_question` TINYINT(1) NOT NULL DEFAULT '0'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `comments_question` TINYINT(1) NOT NULL DEFAULT '0'";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_admin_logs";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_admin_logs (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `date` int(11) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(16) NOT NULL DEFAULT '',
  `action` int(11) NOT NULL DEFAULT '0',
  `extras` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `date` (`date`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_question";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_question (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL DEFAULT '',
  `answer` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM /*!40101 DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci */";

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

$video_config['fullsizeview'] = "2";

$con_file = fopen(ENGINE_DIR.'/data/videoconfig.php', "w+") or die("Désolé, mais vous ne pouvez pas créer un fichier <b>.engine/data/videoconfig.php</b>.<br />Vérifiez les autorisations CHMOD!");
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

	$error_info = "Toutes les demandes prévues: <b>".$db->query_num."</b> Échec de la demande: <b>".$db->error_count."</b>. Peut-être qu'ils ont déjà effectué plus tôt.<br /><br /><div class=\"quote\"><b>Liste des demandes ayant échoué:</b><br /><br />";

	foreach ($db->query_list as $value) {

		$error_info .= $value['query']."<br /><br />";

	}

	$error_info .= "</div>";

} else $error_info = "";

msgbox("info","informations", "<form action=\"index.php\" method=\"GET\">Mise à niveau de la base de données de la version <b>9.3</b> à la version <b>9.4</b> Réussi.<br />{$error_info}<br />Cliquez sur Suivant pour continuer la mise à jour du script<br /><br /><input type=\"hidden\" name=\"next\" value=\"94\"><input class=\"edit\" type=\"submit\" value=\"Suivant ...\"></form>");
?>