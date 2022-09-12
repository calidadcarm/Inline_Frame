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


$PluginIframeIframe_Group = new PluginIframeIframe_Group();

 if (isset($_POST["addgroup"])) {   
   if ($_POST['groups_id']>0) {
       $PluginIframeIframe_Group ->addItem($_POST);
   }
   Html::back();  
} else if (isset($_POST["elimina"])){
	$query= "delete from glpi_plugin_iframe_iframes_groups where plugin_iframe_iframes_id=".$_POST["plugin_iframe_iframes_id"]."
			 and groups_id=".$_POST["elimina"];
    $DB->query($query);
	Html::back();

} else {
	  
   Html::header(__('Iframe', 'Iframe'),
      $_SERVER['PHP_SELF'],
      "config",
      "PluginIframeConfig",
      "iframe"
 );
			   
   $PluginIframeIframe_Group->display($_GET["id"]);
   Html::footer();
   
}
?>
