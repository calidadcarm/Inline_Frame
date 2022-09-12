<?php
/*
   ----------------------------------------------------------
   Plugin Iframe 1.0
   GLPI 9.1.6 
  
   Autor: Javier David Marín Zafrilla.
   Fecha: Febrero 2019

   ----------------------------------------------------------
 */

 include_once(GLPI_ROOT. "/plugins/iframe/inc/profile.class.php");
 
function plugin_iframe_postinit() {
    
	return true;
         
}

 
// Install process for plugin : need to return true if succeeded
function plugin_iframe_install() {
   global $DB;

   Toolbox::logInFile("iframe", "Plugin installation\n");
   
   include_once (GLPI_ROOT."/plugins/iframe/inc/profile.class.php");
      
  PluginIframeProfile::initProfile();
  PluginIframeProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);
  
//creamos la tabla si no existe
	   if //(
	   (!$DB->TableExists("glpi_plugin_iframe_iframes")) 
		   //OR (!$DB->TableExists("glpi_plugin_iframe_estados"))) // INFORGES - estados no necesario
		   {
			$fichero_install = GLPI_ROOT . '/plugins/iframe/sql/install.sql';
			if (file_exists($fichero_install)){
				Session::addMessageAfterRedirect("<strong><font color='green'>Ejecutando fichero: </font><font size='2.5 px'; style='font-style: oblique;' color='#1d05b5'>install.sql</font></strong><br>",true);
				$DB->runFile($fichero_install);
				Session::addMessageAfterRedirect("<strong><font color='#54850d'>Instalación realizado con Éxito</font><BR><BR>Plugin Iframe versión 1.0.0</strong><br>",true);
			} else {
				Session::addMessageAfterRedirect("<strong><font color='#993333'>Error: <br></font><font color='#ef091a'>- No existe el fichero: </font><font size='2.5 px'; style='font-style: oblique;' color='#1d05b5'>install.sql</font></strong><br>",true);
			}  			
	}
  
  
   
   return true;
}


// Uninstall process for plugin : need to return true if succeeded
function plugin_iframe_uninstall() {
 /*  global $DB;
   
      // Keep  these itemtypes as string because classes might not be available (if plugin is inactive)
      $itemtypes = [
         'PluginIframeIframe',
         'PluginIframeIframe_Group',
      ];

      foreach ($itemtypes as $itemtype) {
         $table = getTableForItemType($itemtype);
         $log = new Log();
         $log->deleteByCriteria(['itemtype' => $itemtype]);

         $displayPreference = new DisplayPreference();
         $displayPreference->deleteByCriteria(['itemtype' => $itemtype]);

         $DB->query("DROP TABLE IF EXISTS `$table`");
      }   */
   
   Toolbox::logInFile("iframe", "Plugin Uninstallation\n");
    
   return true;
}


// Define dropdown relations
function plugin_iframe_getDatabaseRelations() {

   $plugin = new Plugin();

   if ($plugin->isActivated("iframe")) {
      return array(                				
                  "glpi_users" => array("glpi_plugin_iframe_iframes"=>"users_id_recipient"),                  
				  "glpi_groups" => array("glpi_plugin_iframe_iframes_groups"=>"groups_id"));                 
   } else {
      return array();
   }
}

////// SEARCH FUNCTIONS ///////

// Define Additionnal search options for types (other than the plugin ones)
function plugin_iframe_getAddSearchOptions($itemtype) {
   global $LANG, $CFG_GLPI;

	
   $sopt = array();

   if ($itemtype == 'PluginIframeIframe' && Session::haveRight("plugin_iframe",READ)) {
	        

	 		// Grupos que pueden visualizar un iframe.
		$sopt[101]['table']     = 'glpi_groups';
		$sopt[101]['field']     = 'name';	 
		$sopt[101]['name']      = 'Grupos con visibilidad';
		$sopt[101]['datatype']      = 'itemlink';
		$sopt[101]['linkfield'] = 'groups_id';
		$sopt[101]['forcegroupby'] = true;
		$sopt[101]['splititems']   = false;		
		$sopt[101]['massiveaction'] = false;				  
		$sopt[101]['joinparams'] = array('beforejoin'
										=> array('table'      => 'glpi_plugin_iframe_iframes_groups',
												'joinparams' => array('jointype' => 'child')));
	
		
				
   }  
   return $sopt;
}





?>