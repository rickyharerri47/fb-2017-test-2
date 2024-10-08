<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$config['version_id'] = "10.2";
$config['site_offline'] = $config['site_offline']=="yes" ? "1" : "0";
$config['allow_alt_url'] = $config['allow_alt_url']=="yes" ? "1" : "0";
$config['hide_full_link'] = $config['hide_full_link']=="yes" ? "1" : "0";
$config['allow_comments'] = $config['allow_comments']=="yes" ? "1" : "0";
$config['allow_cache'] = $config['allow_cache']=="yes" ? "1" : "0";
$config['allow_gzip'] = $config['allow_gzip']=="yes" ? "1" : "0";
$config['allow_registration'] = $config['allow_registration']=="yes" ? "1" : "0";
$config['allow_votes'] = $config['allow_votes']=="yes" ? "1" : "0";
$config['allow_topnews'] = $config['allow_topnews']=="yes" ? "1" : "0";
$config['allow_calendar'] = $config['allow_calendar']=="yes" ? "1" : "0";
$config['allow_archives'] = $config['allow_archives']=="yes" ? "1" : "0";
$config['files_allow'] = $config['files_allow']=="yes" ? "1" : "0";
$config['files_count'] = $config['files_count']=="yes" ? "1" : "0";
$config['allow_sec_code'] = $config['allow_sec_code']=="yes" ? "1" : "0";
$config['allow_skin_change'] = $config['allow_skin_change']=="yes" ? "1" : "0";
$config['allow_watermark'] = $config['allow_watermark']=="yes" ? "1" : "0";

$config['comments_lazyload'] = '0';

$tableSchema = array();

$tableSchema[] = "ALTER TABLE `" . PREFIX . "_banners` ADD `innews` TINYINT(1) NOT NULL DEFAULT '0'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_links` ADD `replacearea` TINYINT(1) NOT NULL DEFAULT '1'";

foreach($tableSchema as $table) {
	$db->query ($table);
}


$handler = fopen(ENGINE_DIR.'/data/config.php', "w") or die("Désolé, mais ne peut pas écrire dans le fichier <b>.engine/data/config.php</b>.<br />Vérifiez CHMOD!");
fwrite($handler, "<?PHP \n\n//System Configurations\n\n\$config = array (\n\n");
foreach($config as $name => $value)
{
	fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
}
fwrite($handler, ");\n\n?>");
fclose($handler);

$fdir = opendir( ENGINE_DIR . '/cache/system/' );
while ( $file = readdir( $fdir ) ) {
	if( $file != '.' and $file != '..' and $file != '.htaccess' ) {
		@unlink( ENGINE_DIR . '/cache/system/' . $file );
		
	}
}

@unlink(ENGINE_DIR.'/data/snap.db');

clear_cache();

if ($db->error_count) {

	$error_info = "Toutes les requêtes planifiées: <b>".$db->query_num."</b> Impossible d'exécuter la requête: <b>".$db->error_count."</b>.Peut-être qu'ils ont déjà été effectué plus tôt.<br /><br /><div class=\"quote\"><b>Liste des requêtes:</b><br /><br />"; 

	foreach ($db->query_list as $value) {

		$error_info .= $value['query']."<br /><br />";

	}

	$error_info .= "</div>";

} else $error_info = "";

msgbox("info","Information", "Mise à niveau de la base de données de la version <b>10.1</b> vers <b>10.2</b> réussi.<br /><br />{$error_info}<br />Cliquez <a href=\"../index.php\" class=\"btn btn-green\">Ici</a> go index site.");
?>