<?php
/*
   ----------------------------------------------------------
   Plugin Inline Frame 1.0
   GLPI 9.1.6 
  
   Autor: Javier David Marín Zafrilla.
   Fecha: Febrero 2019

   ----------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
        die("Sorry. You can't access directly to this file");
}

// Class of the defined type
class PluginIframeIframe extends CommonDBTM {
	
	 public $dohistory=true;

	 const CONFIG_PARENT   = - 2;
   
	   // From CommonDBTM
   public $table            = 'glpi_plugin_iframe_iframes';
   public $type             = 'PluginIframeIframe';  

   static $rightname = "plugin_iframe";
	
   static function canView() {
      return Session::haveRight('plugin_iframe', READ);
   }
	
   // Should return the localized name of the type
   static function getTypeName($nb = 0) {
      return "Plugin Iframe"; 
   }	

	 static function getIcon() {
		return "fas fa-crop-alt";
	 }
	
   /**
    * Get search function for the class
    *
    * @return array of search option
   **/
   
	 function rawSearchOptions() {

	$tab = [];

	$tab = array_merge($tab, parent::rawSearchOptions());


	$tab[] = [
	'id' => '100',
	'table' => $this->getTable(),
	'field' => 'name',
	'name' => __('Frame','Frame'),
	'datatype' => 'itemlink',
	'massiveaction' => false,
	];

	$tab[] = [
	'id' => '102',
	'table' => $this->getTable(),
	'field' => 'comment',
	'name' => __('Descripcion','Descripcion'),
	'datatype' => 'text',
	'massiveaction' => true,
	];
	
	$tab[] = [
	'id' => '103',
	'table' => $this->getTable(),
	'field' => 'url',
	'name' => __('Url','Url'),
	'datatype' => 'text',
	'massiveaction' => false,
	];	
	
	 
	$tab[] = [
	'id' => '104',
	'table' => $this->getTable(),
	'field' => 'color',
	'name' => __('Color','Color'),
	'datatype' => 'text',
	'massiveaction' => true,
	];
	
		$tab[] = [
	'id' => '105',
	'table' => $this->getTable(),
	'field' => 'active',
	'name' => __('Activo','Activo'),
	'datatype' => 'bool',
	'massiveaction' => true,
	];

		$tab[] = [
	'id' => '106',
	'table' => $this->getTable(),
	'field' => 'date_creation',
	'name' => __('Fecha de creaci&oacute;n','Fecha de creaci&oacute;n'),
	'datatype' => 'datetime',
	'massiveaction' => false,
	];

		$tab[] = [
	'id' => '107',
	'table' => $this->getTable(),
	'field' => 'date_mod',
	'name' => __('Fecha de modificaci&oacute;n','Fecha de modificaci&oacute;n'),
	'datatype' => 'datetime',
	'massiveaction' => false,
	];

		$tab[] = [
	'id' => '108',
	'table' => 'glpi_entities',
	'field' => 'completename',
	'name' => _n('Entity', 'Entities', 1),
	'datatype' => 'dropdown',
	'massiveaction' => true,
	];


		$tab[] = [
	'id' => '109',
	'table' => 'glpi_users',
	'field' => 'name',
	'linkfield'     => 'users_id_recipient',
	'name' => _n('Creado por (Usuario)', 'Creado por (Usuario)', 1),
	'datatype' => 'dropdown',
	'massiveaction' => true,
	];

		$tab[] = [
	'id' => '110',
	'table' => 'glpi_users',
	'field' => 'name',
	'linkfield'     => 'users_id_lastupdater',
	'name' => _n('Actualizado por (Usuario)', 'Actualizado por (Usuario)', 1),
	'datatype' => 'dropdown',
	'massiveaction' => true,
	];

		$tab[] = [
	'id' => '111',
	'table' => $this->getTable(),
	'field' => 'Show',
	'name' =>  __('Mostrar Iframe','Mostrar Iframe'),
	'datatype' => 'bool',
	'massiveaction' => true,
	];


	
	return $tab;

	}   


	
    function defineTabs($options=array()) {

      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('PluginIframeIframe', $ong, $options);
	  $this->addStandardTab('PluginIframeIframe_Group', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);
      return $ong;
   }    
         
   /**
    * Return the name of the tab for item including forms like the config page
    *
    * @param  CommonGLPI $item         Instance of a CommonGLPI Item (The Config Item)
    * @param  integer    $withtemplate
    *
    * @return String                   Name to be displayed
    */
   public function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      switch ($item->getType()) {

         case 'Central':
            return _n('Vista Gobernanza', 'Vista Gobernanza', 2, 'iframe');
            break;
	
      }
      return '';
   }	

   /**
    * Display a list of all forms on the configuration page
    *
    * @param  CommonGLPI $item         Instance of a CommonGLPI Item (The Config Item)
    * @param  integer    $tabnum       Number of the current tab
    * @param  integer    $withtemplate
    *
    * @see CommonDBTM::displayTabContentForItem
    *
    * @return null                     Nothing, just display the list
    */
   public static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
	    

    if (strpos($_SERVER['REQUEST_URI'], 'central.php') <> false) {
	
		$iframe = find_Iframes();
 
		$tabs = show_tabs_Iframes($iframe);
	
		echo  $tabs;
	
	}	  

   }   
   

  public function showForm ($ID, $options=array()) {
	global $CFG_GLPI, $DB;
	
	  // In percent
      $colsize1 = '13';
      $colsize2 = '37';
	  
      $showuserlink              = 0;
      if (Session::haveRight('user', READ)) {
         $showuserlink = 1;
      }	  
	  
	  $this->initForm($ID, $options);
      $this->showFormHeader($options);

	 //Usuario del Iframe
	
  	 if ((!isset($ID)) or (empty($ID))){  
	 echo "<input type='hidden' name='users_id_recipient' value='".$_SESSION['glpiID']."'>\n";
	  } else {  
	 echo "<input type='hidden' name='users_id_lastupdater' value='".$_SESSION['glpiID']."'>\n"; 
	  }
	  
	 //Nombre del Iframe
      echo "<tr class='tab_bg_1'>";
			echo "<th class='left'  colspan='1'>".__('Name','Names')."</th>";
			echo "<td class='left'  colspan='3'>";
				//Html::autocompletionTextField($this,"name",array('size' => "124"));
			echo Html::input('name', ['value' => $this->fields['name'], 'size' => "127"]);
			echo "</td>";
      echo "</tr>";

	  // Descripción del Iframe
	  echo "<tr class='tab_bg_1'>";
	  echo "<th class='left'  colspan='1'>Descripción</th>";
	  echo "<td class='left' colspan='3'><textarea cols='125' rows='3' name='comment'>".
            $this->fields["comment"]."</textarea>";
      echo "</td></tr>";
	  
	  // Select del elemento
	  echo "<tr class='tab_bg_1'>";
	  echo "<th class='left' width='$colsize1%' colspan='1'>URL</th>";
      echo "<td class='left' colspan='3'><textarea cols='125' rows='10' name='url'>".
            $this->fields["url"]."</textarea>";
      echo "</td></tr>";
	 
	   
	  // Selecciona color hexadecimal
	  echo "<tr class='tab_bg_1'>";
      echo "<th class='left'  colspan='1'>Color<br>(hexadecimal)</th>";
	  echo "<td class='left'  widht='30px'>";
	  
	  	  if (empty($this->fields['color'])) { $color="#000"; } else { $color=$this->fields['color']; }
	
	$rand = mt_rand();

         echo "<div class='fa-label'>
            <i class='fas fa-tint fa-fw' title='".__('Color')."'></i>";         
		
		Html::showColorField('color', ['value' => $color, 'rand' => $rand]);
         echo "</div>";	
	

//			Html::showColorField('color', array('value' => $color));			
	  echo "</td>";

	   
	// En qué evento se muestra el iframe.
	  echo "<tr class='tab_bg_1'>";
	  echo "<th class='left'  colspan='1'>Mostrar Iframe</th>";
	  		
	  echo "<td>";
	   echo "<div class='fa-label'>
           <i class='fas fa-eye fa-fw' title='".__('Mostrar Iframe')."'></i> &nbsp;";  

         echo "<span class='switch pager_controls'>
            <label for='showswitch$rand' title='".__('Mostrar Iframe')."'>
               <input type='hidden' name='show' value='0'>
                              <input type='checkbox' id='showswitch$rand' name='show' value='1'".
                     ($this->fields["show"]
                        ? "checked='checked'"
                        : "")."
               >
               <span class='lever'></span>
            </label>
         </span>
		 </div>";		
		
	  echo "</tr>";	   
	   
	  echo "<tr class='tab_bg_1'>";
	  echo "<th class='left'  colspan='1'>Activo</th>";
	  echo "<td class='left'  widht='10px'>";
	  
       echo "<div class='fa-label'>
            <i class='fas fa-lock fa-fw' title='".__('Activo')."'></i> &nbsp;";         
         echo "<span class='switch pager_controls'>
            <label for='activeswitch$rand' title='".__('Activo')."'>
               <input type='hidden' name='active' value='0'>
                              <input type='checkbox' id='activeswitch$rand' name='active' value='1'".
                     ($this->fields["active"]
                        ? "checked='checked'"
                        : "")."
               >
               <span class='lever'></span>
            </label>
         </span>";
         echo "</div>";		  
	  
      echo "</td>";
	  echo "</tr>";	  
	  
	// Ultima modificación
	echo "<tr>";
	  echo "<td class='center' colspan='4'>";

	   if ($this->fields['users_id_lastupdater'] > 0) {

		  printf(__('Last update by %1$s on %2$s'), getUserName($this->fields["users_id_recipient"], $showuserlink),
                   Html::convDateTime($this->fields["date_creation"]));				   
				   
	   } else { 
	   	   if ($this->fields['users_id_recipient'] > 0) {
			   			   
          printf(__('Create by %1$s on %2$s'), getUserName($this->fields["users_id_recipient"], $showuserlink),
                   Html::convDateTime($this->fields["date_creation"]));	  
	   }
	   }
      echo "</td>";
	echo "</tr>";
	  $this->showFormButtons($options);
	    
      return true;
   }
   
   
     static function DropdownItem($myname, $value=0){
	global $DB,$CFG_GLPI;
	$query = "select id, name from glpi_plugin_iframe_iframes where order by 1";
		$result=$DB->query($query);
		//Desplegable Iframes
		echo "<select name=$myname id=$myname>\n";
		if ($DB->numrows($result)){
			while ($data=$DB->fetchAssoc($result)){
				echo "<option value='".$data[0]."'>".$data[1]."</option>\n";			
			}
		}
		echo "</select>\n";		
		
		
	} 

}
?>