<?php
/*
   ----------------------------------------------------------
   Plugin Iframe 1.0
   GLPI 9.1.6 
  
   Autor: Javier David Marín Zafrilla.
   Fecha: Febrero 2019

   ----------------------------------------------------------
*/
 
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");
include_once (GLPI_ROOT."/plugins/iframe/inc/profile.class.php");

Session::checkRight("profile",CREATE);
$prof=new PluginIframeProfile();


//Save profile
if (isset ($_POST['UPDATE'])) {
	$prof->update($_POST);
	Html::back();
}

?>
