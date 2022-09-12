<?php

/*
   ----------------------------------------------------------
   Plugin Iframe 1.0
   GLPI 9.1.6 
  
   Autor: Javier David Marín Zafrilla.
   Fecha: Febrero 2019

   ----------------------------------------------------------
 */

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

class PluginIframeConfig extends CommonDBTM {
 //  public $table            = 'glpi_plugin_iframe_configs';
 //  public $type             = 'PluginIframeConfig';
   
   static $rightname = "config";
	
   public static function getTypeName($nb = 0) {
      return __('Plugin Iframe', 'Plugin Iframe');
   }   
   
   
  static function getMenuContent() {
      global $CFG_GLPI;

      $menu['page'] = "/plugins/iframe/front/config.php";
      $menu['title'] = self::getTypeName();
	  	  
      $menu['options']['iframe']['page']               = "/plugins/iframe/front/iframe.php";
      $menu['options']['iframe']['title']              = __('Iframe', 'Iframe');
      $menu['options']['iframe']['links']['add']       = '/plugins/iframe/front/iframe.form.php';
      $menu['options']['iframe']['links']['search']    = '/plugins/iframe/front/iframe.php';

	  $menu['options']['right']['page']               = "/plugins/iframe/front/right.form.php";
      $menu['options']['right']['title']              = __("Permisos por iframe", "Permisos por iframe");
      
	  return $menu;
   }


    public function getTabNameForItem(CommonGLPI $item, $withtemplate=0)
   {
      switch ($item->getType()) {
         case "PluginIframeConfig":
            $object  = new self;
            $found = $object->find();
            $number  = count($found);
            return self::createTabEntry(self::getTypeName($number), $number);
            break;
      }
      return '';
   }     
   
   static function showConfigPage()	 {
       global $CFG_GLPI;
      
		echo "<div class='center'>";
		echo "<table class='tab_cadre'>";
		echo "<tr><th>".__('Configuraci&oacute;n plugin Iframe','Configuración plugin Iframe')."</th></tr>";

		if (Session::haveRight('plugin_iframe', UPDATE)) {
		 		   
		 // Gestión de iframe
		   echo "<tr class='tab_bg_1 center'><td>";
		   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/iframe/front/iframe.php' >".__('Ver o modificar iframe','Ver o modificar iframe')."</a>";
		   echo "</td/></tr>\n";

		   // Gestión de derechos por iframe
		   echo "<tr class='tab_bg_1 center'><td>";
		   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/iframe/front/right.form.php' >".__('Gesti&oacute;n de derechos por iframe','Gesti&oacute;n de derechos por iframe')."</a>";
		   echo "</td/></tr>\n";			   
		}

		echo "</table></div>";
   }	  
   
}

?>