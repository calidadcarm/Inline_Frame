<?php

/*
   ----------------------------------------------------------
   Plugin Iframe 1.0
   GLPI 9.1.6 
  
   Autor: Javier David Marín Zafrilla.
   Fecha: Febrero 2019

   ----------------------------------------------------------
 */
 
include_once ("../../../inc/includes.php");

Session::checkRight('profile', READ);

Plugin::load('iframe', true);

Html::header(__('iframe', 'iframe'), $_SERVER['PHP_SELF'] ,"config", "PluginIframeConfig", "right");

require_once "../inc/profile.class.php";

$iframe = new PluginIframeIframe();
if (isset($_POST['plugin_iframe_iframes_id'])) {
	$iframe -> getFromDB($_POST['plugin_iframe_iframes_id']);
}

$warning =''; // Variable que contiene el id del iframe
if (isset($_POST['iframe'])) {
   $warning=$_POST['iframe'];
}
$prof = new PluginIframeProfile();

if (isset($_POST['delete']) && $warning) {
   $profile_right = new ProfileRight;
   $profile_right->deleteByCriteria(array('name' => "plugin_iframe_iframes_$iframe"));
   ProfileRight::addProfileRights(array("plugin_iframe_iframes_$iframe"));

} else if (isset($_POST['update']) && $warning) {
   Session::checkRight('profile', UPDATE);   
   if (PluginIframeProfile::updateForIframe($_POST)){
		$_POST['plugin_iframe_iframes_id'] = $warning; 
		$iframe -> getFromDB($_POST['plugin_iframe_iframes_id']);	
   }
}
echo "<br>";
echo "<form method='post' action=\"".$_SERVER["PHP_SELF"]."\">";
echo "<table class='tab_cadre_fixe' align='center'><tr><th colspan='2'>";
echo __('Selecciona un iframe para ver o editar sus permisos de acceso por perfiles', 'iframe'). "</th></tr>\n";

echo "<tr class='tab_bg_1'><td align='center'>iframe&nbsp; ";
PluginIframeIframe::dropdown(array('name' => 'plugin_iframe_iframes_id'));

echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='"._sx('button', 'Post')."' class='submit' ></td></tr>";
echo "</table><br>";
Html::closeForm();

if (isset($_POST['plugin_iframe_iframes_id'])) {
   PluginIframeProfile::showForIframe($iframe);
}

Html::footer();
