<?php
/*
   ----------------------------------------------------------
   Plugin Inline Frame 1.0
   GLPI 9.1.6 
  
   Autor: Javier David Marín Zafrilla.
   Fecha: Julio 2018

   ----------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginIframeProfile extends Profile {

   static $rightname = "profile";

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType()=='Profile') {
            return PluginIframeIframe::getTypeName(2);
      }
      return '';
   }

   static function getIcon() {
		return "fas fa-user-lock";
	} 


   static function DisplayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      global $CFG_GLPI;

      if ($item->getType()=='Profile') {
         $ID = $item->getID();
         $prof = new self();

         self::addDefaultProfileInfos($ID, 
                                    array('plugin_iframe' => 0));
         $prof->showForm($ID);
      }
      return true;
   }
   
   static function createFirstAccess($ID) {
      //85
      self::addDefaultProfileInfos($ID,
                                    array('plugin_iframe' => 127), true);
   }
  

    /**
    * @param $profile
   **/
  static function addDefaultProfileInfos($profiles_id, $rights, $drop_existing = false) {
      global $DB;
      
      $profileRight = new ProfileRight();
      foreach ($rights as $right => $value) {
		  
		  $criteria = [
"profiles_id" => $profiles_id,
"name" => $right,
];
		  
         if (countElementsInTable('glpi_profilerights',
                                   $criteria) && $drop_existing) {
            $profileRight->deleteByCriteria($criteria);
         }
         if (!countElementsInTable('glpi_profilerights',
                                   $criteria)) {
            $myright['profiles_id'] = $profiles_id;
            $myright['name']        = $right;
            $myright['rights']      = $value;
            $profileRight->add($myright);

            //Add right to the current session
            $_SESSION['glpiactiveprofile'][$right] = $value;
         }
      }
   }  
   /**
    * Show profile form
    *
    * @param $items_id integer id of the profile
    * @param $target value url of target
    *
    * @return nothing
    **/
   function showForm($profiles_id=0, $openform=TRUE, $closeform=TRUE) {

      echo "<div class='firstbloc'>";
      if (($canedit = Session::haveRightsOr(self::$rightname, array(CREATE)))
          && $openform) {
         $profile = new Profile();
         echo "<form method='post' action='".$profile->getFormURL()."'>";
      }

      $profile = new Profile();
      $profile->getFromDB($profiles_id);
      if ($profile->getField('interface') == 'central') {
         $rights = $this->getAllRights();	 
         $profile->displayRightsChoiceMatrix($rights, array('canedit'       => $canedit,
                                                         'default_class' => 'tab_bg_2',
                                                         'title'         => __('General')));

   	  }
       
      if ($canedit
          && $closeform) {
         echo "<div class='center'>";
         echo Html::hidden('id', array('value' => $profiles_id));
         echo Html::submit(_sx('button', 'Save'), array('name' => 'update'));
         echo "</div>\n";
         Html::closeForm();
      }
      echo "</div>";
   }

   static function getAllRights($all = false) {
      $rights = array(
          array('rights'    => array(ALLSTANDARDRIGHT => 'habilitar'),
		        'itemtype'  => 'PluginIframeIframe',
                'label'     => _n('Iframe', 'Iframe', 2, 'iframe'),
                'field'     => 'plugin_iframe'
          ),
      );
      
      return $rights;
   }
   
     
   /**
   * Initialize profiles, and migrate it necessary
   */
   static function initProfile() {
      global $DB;
      $profile = new self();

      //Add new rights in glpi_profilerights table
      foreach ($profile->getAllRights(true) as $data) {
		  
		 $criteria = [
"name" => $data['field'],
]; 
		  
         if (countElementsInTable("glpi_profilerights", 
                                  $criteria) == 0) {
            ProfileRight::addProfileRights(array($data['field']));
         }
      }
      foreach ($DB->request("SELECT *
                           FROM `glpi_profilerights` 
                           WHERE `profiles_id`='".$_SESSION['glpiactiveprofile']['id']."' 
                              AND `name` LIKE '%plugin_iframe%'") as $prof) {
         $_SESSION['glpiactiveprofile'][$prof['name']] = $prof['rights']; 
      }
   }

   
  static function removeRightsFromSession() {
      foreach (self::getAllRights(true) as $right) {
         if (isset($_SESSION['glpiactiveprofile'][$right['field']])) {
            unset($_SESSION['glpiactiveprofile'][$right['field']]);
         }
      }
   }
   
   
      /**
    * @param $report
   **/
   static function showForIframe(PluginIframeIframe $iframe) {
      global $DB;
	  
	  $iframe_id = $iframe->fields['id'];
      if (empty($iframe) || !Session::haveRight('profile', READ)) {
         return false;
      }
	  
      $canedit = Session::haveRight('profile', UPDATE);

      if ($canedit) {
         echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>\n";
      }

	    if ($_SESSION["glpiis_ids_visible"] || empty($iframe->fields['name'])) {
            $linkname = sprintf(__('%1$s (%2$s)'), $iframe->fields['name'], $iframe->fields['id']);
        } else {
			$linkname = sprintf(__('%1$s'), $iframe->fields['name']);
		}	
	  
      echo "<table class='tab_cadre' width='300px'>\n";
      echo "<tr><th colspan='2'>'".$linkname."'</th></tr>";
	  echo "<tr class='tab_bg_1'><td width='60%'  bgcolor='#F6E3CE' align='center'>PERFIL</td><td width='40%'  bgcolor='#F6E3CE' align='center'>ACCESO</td></tr>";
      $query = "SELECT `id`, `name`
                FROM `glpi_profiles`
                ORDER BY `name`";

      foreach ($DB->request($query) as $data) {
		  
		  $permisos = "select rights from glpi_profilerights
					   where name='plugin_iframe_iframes_".$iframe_id ."' 
					   and profiles_id=".$data['id'].";";
		  
		  $result = $DB->query($permisos);
	     $rights = $DB->fetchAssoc($result);

          echo "<tr class='tab_bg_1'><th style='background-color: #f9fbfb;' width='70%' align='left'>" . $data['name'] . "&nbsp: </th><td align='center' width='40%'>";	
$rand = mt_rand();
         echo "<span class='switch pager_controls'>
            <label for='".$data['id']."witch$rand' title='".__('Mostrar avisos p&uacute;blicos')."'>
               <input type='hidden' name='".$data['id']."' value='0'>
                              <input type='checkbox' id='".$data['id']."witch$rand' name='".$data['id']."' value='1'".
                     ((isset($rights['rights'])&& ($rights['rights']==READ))?1:0
                        ? "checked='checked'"
                        : "")."
               >
               <span class='lever'></span>
            </label>
         </span>";
		  
          //  Dropdown::showYesNo($data['id'], (isset($rights['rights'])&& ($rights['rights']==READ))?1:0);
         echo "</td></tr>\n";
      }

      if ($canedit) {
         echo "<tr class='tab_bg_1'><td colspan='2' class='center'>";
         echo "<input type='hidden' name='iframe' value='$iframe_id'>";
         echo "<input type='submit' name='update' value='"._sx('button', 'Update')."' ".
                "class='submit'>";
         echo "</td></tr>\n";
         echo "</table>\n";
         Html::closeForm();
      } else {
         echo "</table>\n";
      }
   }
   
   
      /**
    * @param $avisos
   **/
   static function updateForIframe($iframe) {
      global $DB;
	  
	  $delete = "delete from glpi_profilerights where name='plugin_iframe_iframes_".$iframe['iframe']."'";	  
	  $result = $DB->query($delete);
	 // var_dump($iframe);
	  foreach ($iframe as $key => $val) {
		  
		//  echo $key." = ".$val."<br>";
		  
		if (($key!= 'iframe') && ($key!= 'update') && ($key!= '_glpi_csrf_token')){
			if ($val == 1){
				$right = READ;
			} else {
				$right = 0;
			}
			$update = "INSERT INTO glpi_profilerights (name, rights, profiles_id)
				 VALUES ('plugin_iframe_iframes_".$iframe['iframe']."',".$right.",".$key.");";
			$result = $DB->query($update);
		}		 	  
	  }
	return true;
	
   }
   
   
}
?>