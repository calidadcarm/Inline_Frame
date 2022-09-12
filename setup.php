<?php
/*
   ----------------------------------------------------------
   Plugin Iframe 1.0
   GLPI 9.1.6 
  
   Autor: Javier David Marín Zafrilla.
   Fecha: Febrero 2019

   ----------------------------------------------------------
 */
include (GLPI_ROOT."/plugins/iframe/inc/function.iframe.php");

// Init the hooks of the plugins -Needed
function plugin_init_iframe() {
   global $PLUGIN_HOOKS, $CFG_GLPI, $GO_CLASS_RELATIONS;
   
   // CSRF compliance : All actions must be done via POST and forms closed by Html::closeForm();
   $PLUGIN_HOOKS['csrf_compliant']['iframe'] = true;
   
  // $GO_CLASS_RELATIONS = array('Profile', 'Entity', 'PluginRelationRelation', 'Computer', 'Monitor', 'PluginFormcreatorForm', 'Software', 'User', 'PluginServiciosServicio', 'NetworkEquipment', 'NetworkPort', 'Peripheral', 'Printer', 'Cartridge', 'Consumable', 'Phone', 'Ticket', 'Problem', 'Change', 'Planning', 'Stat', 'TicketRecurrent', 'Budget', 'Supplier', 'Contact', 'Contract', 'Document', 'Project', 'ProjectTask', 'Reminder', 'RSSFeed', 'Log', 'Reservation', 'Report', 'MigrationCleaner', 'AuthLDAP', 'Group', 'Rule', 'RuleCollection', 'RuleImportEntityCollection', 'RuleImportEntity', 'RuleImportComputerCollection', 'RuleImportComputer', 'RuleMailCollectorCollection', 'MailCollector', 'RuleRightCollection', 'RuleRight', 'RuleSoftwareCategoryCollection', 'RuleSoftwareCategory', 'RuleTicketCollection', 'RuleTicket', 'Transfer', 'RuleDictionnaryDropdown', 'RuleDictionnarySoftware', 'RuleDictionnaryPrinter', 'QueuedMail', 'Backup', 'Event', 'PluginRenamerMenu', 'NetworkPortInstantiation', 'Location', 'State', 'Manufacturer', 'Blacklist', 'BlacklistedMailContent', 'ITILCategory', 'TaskCategory', 'SolutionType', 'RequestType', 'SolutionTemplate', 'ProjectState', 'ProjectType', 'ProjectTaskType', 'ComputerType', 'NetworkEquipmentType', 'PrinterType', 'MonitorType', 'PeripheralType', 'PhoneType', 'SoftwareLicenseType', 'CartridgeItemType', 'ConsumableItemType', 'ContractType', 'ContactType', 'DeviceMemoryType', 'SupplierType', 'InterfaceType', 'DeviceCaseType', 'PhonePowerSupply', 'Filesystem', 'PrinterModel', 'MonitorModel', 'PhoneModel', 'VirtualMachineType', 'VirtualMachineSystem', 'VirtualMachineState', 'DocumentCategory', 'DocumentType', 'KnowbaseItemCategory', 'Calendar', 'Holiday', 'OperatingSystem', 'OperatingSystemVersion', 'OperatingSystemServicePack', 'AutoUpdateSystem', 'NetworkInterface', 'NetworkEquipmentFirmware', 'Netpoint', 'Domain', 'Network', 'Vlan', 'IPNetwork', 'FQDN', 'WifiNetwork', 'NetworkName', 'SoftwareCategory', 'UserTitle', 'UserCategory', 'RuleRightParameter', 'Fieldblacklist', 'SsoVariable', 'DeviceMotherboard', 'DeviceProcessor', 'DeviceMemory', 'DeviceHardDrive', 'DeviceNetworkCard', 'DeviceDrive', 'DeviceControl', 'DeviceGraphicCard', 'DeviceSoundCard', 'DevicePci', 'DeviceCase', 'DevicePowerSupply', 'Notification', 'SLA', 'Control', 'Crontask', 'Auth', 'AuthMail', 'Link', 'DisplayPreference', 'InfoCom');    
   
   // Configure current profile ...
   $PLUGIN_HOOKS['change_profile']['iframe'] = array('PluginIframeProfile','initProfile');  
   $PLUGIN_HOOKS['config_page']['iframe'] = 'front/config.php';   
   
   $Plugin = new Plugin();
   if ($Plugin->isActivated('iframe')) {

	  // Registro de clases
  
	    Plugin::registerClass('PluginIframeIframe', array('addtabon' => array('Central'))); // Pestaña en Central 	 
		Plugin::registerClass('PluginIframeProfile', array('addtabon' => array('Profile'))); // Perfil 
			
 				
   }
   
		if (Session::haveRight("plugin_iframe",READ)) {
			$PLUGIN_HOOKS['menu_toadd']['iframe'] = array('config' => 'PluginIframeConfig');
		}   
			
				
//	$PLUGIN_HOOKS['post_init']['iframe'] = 'plugin_iframe_postinit';
	
  // $PLUGIN_HOOKS['use_massive_action']['Iframe'] = 1;
   
 //  $PLUGIN_HOOKS['post_init']['iframe'] = 'plugin_iframe_postinit';	//Iniciar los items de para registrar los tabs.
   
   //$PLUGIN_HOOKS['add_javascript']['iframe'][] = 'js/modernizr.custom.04022.js';
   //$PLUGIN_HOOKS['add_css']['iframe'][]        = 'css/style2.css';
   return $PLUGIN_HOOKS;
}


// Get the name and the version of the plugin
function plugin_version_iframe() {

   return array('name'          => _n('Inline Frame' , 'Inline Frame' ,2, 'Iframe'),
                'version'        => '1.1.2',
                'license'        => 'AGPL3',
                'author'         => '<a href="http://www.carm.es">CARM</a>',
                'homepage'       => 'http://www.carm.es',
                'minGlpiVersion' => '9.1');
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_iframe_check_prerequisites() {

   // GLPI must be at least 0.84 ...
   if (version_compare(GLPI_VERSION,'9.1','lt')) {
      echo "This plugin requires GLPI >= 9.1";
      return false;
   }
   return true;
}


// Check configuration process for plugin : need to return true if succeeded
// Can display a message only if failure and $verbose is true
function plugin_iframe_check_config($verbose=false) {
   if (true) {
      // Always true ...
      return true;
   }

   if ($verbose) {
      _e('Installed / not configured', 'iframe');
   }
   return false;
}
?>
