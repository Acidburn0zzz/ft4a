<?php

//Sessions
ob_start();
session_start();

//set timezone
date_default_timezone_set('Europe/Paris');

//SQL--------------------------------------------------------------------------------
define('DBHOST','localhost');
define('DBUSER','xxxxxxxx');
define('DBPASS','xxxxxxxxxxxxxxxxxx');
define('DBNAME','xxxxxxxxxxxxxx');

try {
	$db = new PDO("mysql:host=".DBHOST.";port=8889;dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
	//show error
    	echo '<p>'.$e->getMessage().'</p>';
    	exit;
}

//PARAMETRES DU SITE-----------------------------------------------------------------
define('WEBPATH','/var/www/ft4a.xyz/web/');
define('SITENAME','ft4a');
define('SITENAMELONG','ft4a.xyz');
define('SITESLOGAN','Free Torrents For All');
define('SITEDESCRIPTION','Bittorrent tracker for free - as in freedom - and opensource media ONLY!');
define('SITEKEYWORDS','bittorrent,torrent,ft4a,partage,échange,peer,p2p,licence,license,medias,libre,free,opensource,gnu,téléchargement,download,upload,xbt,tracker,php,mysql,linux,bsd,os,système,system,exploitation,debian,arch,fedora,ubuntu,manjaro,mint,film,movie,picture,video,mp3,musique,music,mkv,avi,mpeg,gpl,creativecommons,cc,mit,apache,cecill,artlibre');
define('SITEURL','http://www.ft4a.xyz');
define('SITEURLHTTPS','https://www.ft4a.xyz');
define('SITEMAIL','xxxxxxxxxxxxxxxxxxxxxx');
define('SITEOWNORNAME','xxxxxxxxxxxxxxx');
define('SITEAUTOR','xxxxxxxxxxxxxxx');
define('SITEOWNORADDRESS','xxxxxxxxxxxxxxxxxxxx');
//define('SITEDISQUS','xxxxxxxxxxxx');
define('ANNOUNCEPORT','55555');
define('SITEVERSION','2.0.13-a');
define('SITEDATE','18/08/19');
define('COPYDATE','2019');
define('CHARSET','UTF-8');
//Nb de torrents sur la page ... torrents.php
define('NBTORRENTS','15');
// Announce
$ANNOUNCEURL = SITEURL.':'.ANNOUNCEPORT.'/announce';
// Répertoire des images
$REP_IMAGES = '/var/www/ft4a.xyz/web/images/';

//Paramètres pour le fichier torrent (upload.php)
define('MAX_FILE_SIZE', 1048576); // Taille maxi en octets du fichier .torrent
$WIDTH_MAX = 500; // Largeur max de l'image en pixels
$HEIGHT_MAX = 500; // Hauteur max de l'image en pixels
$REP_TORRENTS = '/var/www/ft4a.xyz/web/torrents/'; // Répertoire des fichiers .torrents

//Paramètres pour l'icone de présentation du torrent (index.php, edit-post.php, ...)
$WIDTH_MAX_ICON = 150; //largeur maxi de l'icone de présentation dut orrent
$HEIGHT_MAX_ICON = 150; //Hauteur maxi de l'icone de présentation du torrent
$MAX_SIZE_ICON = 30725; // Taille max en octet de l'icone de présentation du torrent (30 Ko)
$REP_IMAGES_TORRENTS = '/var/www/ft4a.xyz/web/images/imgtorrents/';
$WEB_IMAGES_TORRENTS = 'images/imgtorrents/';

//Paramètres pour l'avatar membre (profile.php, edit-profil.php, ...)
$MAX_SIZE_AVATAR = 51200; // Taille max en octets du fichier (50 Ko)
$WIDTH_MAX_AVATAR = 200; // Largeur max de l'image en pixels
$HEIGHT_MAX_AVATAR = 200; // Hauteur max de l'image en pixels
$EXTENSIONS_VALIDES = array( 'jpg' , 'png' ); //extensions d'images valides
$REP_IMAGES_AVATARS = '/var/www/ft4a.xyz/web/images/avatars/'; // Répertoires des images avatar des membres

// Edito - Page d'accueil
$EDITO = '
<p class="justify">
</p>
';

// Deconnexion auto au bout de 10 minutes
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
	if (isset($_SESSION['time'])) {
        	if ($_SESSION['time'] + 600 > time()) {
                	$_SESSION['time'] = time();
             	}
		else {
			header ('Location: '.SITEURL.'/logout.php');
		}
     	}
	else {
		$_SESSION['time'] = time();
	}
}

// -----------------------------------------------------------------------------------
// CLASSES
// -----------------------------------------------------------------------------------

//load classes as needed
function __autoload($class) {
   
   $class = strtolower($class);

   //if call from within assets adjust the path
   $classpath = 'classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
   }  
   
   //if call from within admin adjust the path
   $classpath = '../classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
   }
   
   //if call from within admin adjust the path
   $classpath = '../../classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
   }     
    
}

$user = new User($db); 

// On inclut le fichier de fonctions
// et les fichiers d'encodage et de décodage des torrents 
require_once('functions.php');
require_once('BDecode.php');
require_once('BEncode.php');

?>
