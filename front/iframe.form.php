<?php
/*
   ----------------------------------------------------------
   Plugin Iframe 1.0
   GLPI 9.1.6 
  
   Autor: Javier David Marín Zafrilla.
   Fecha: Febrero 2019
   ----------------------------------------------------------
 */

include ("../../../inc/includes.php");
                       

if (!isset($_GET["id"])) $_GET["id"] = "";
if (!isset($_GET["withtemplate"])) $_GET["withtemplate"] = "";

$PluginIframeIframe = new PluginIframeIframe();
  

if (isset($_POST["add"])) {	
	$newID=$PluginIframeIframe->add($_POST);
    Html::redirect($_SERVER['HTTP_REFERER']);
	
} else if (isset($_POST["delete"])) {

	$PluginIframeIframe->delete($_POST);
	Html::redirect(Toolbox::getItemTypeSearchURL('PluginIframeIframe'));
	
} else if (isset($_POST["restore"])) {

	$PluginIframeIframe->check($_POST['id'],'w');
	$PluginIframeIframe->restore($_POST);
	Html::redirect(Toolbox::getItemTypeSearchURL('PluginIframeIframe'));
	
} else if (isset($_POST["purge"])) {

	$PluginIframeIframe->delete($_POST,1);
	Html::redirect(Toolbox::getItemTypeSearchURL('PluginIframeIframe'));
	
} else if (isset($_POST["update"])) {
	
	$PluginIframeIframe->update($_POST);
	Html::redirect($_SERVER['HTTP_REFERER']);
 }                                          
  else {
	  	
   Html::header(__('Más Iframe', 'iframe'),
      $_SERVER['PHP_SELF'],
      "config",
      "PluginIframeConfig",
      "iframe"
    );
/*
    if (!isset($_SESSION['glpi_js_toload']['colorpicker'])) {
    echo Html::css('lib/jqueryplugins/spectrum-colorpicker/spectrum.css');
    Html::requireJs('colorpicker');
    } 	
*/ 
    if (Session::haveRight('plugin_iframe',UPDATE)) {			
		$PluginIframeIframe ->display(array('id' => $_GET["id"]));
		
		Html::footer(); 
	} else {
			Html::displayRightError();
	}     
}

?>