<?php
/*
   ----------------------------------------------------------
   Plugin Iframe 1.0
   GLPI 9.1.6 
  
   Autor: Javier David Marín Zafrilla.
   Fecha: Febrero 2019

   ----------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginIframeIframe_Group extends CommonDBRelation {

   // From CommonDBRelation
   static public $itemtype_1 = 'PluginIframeIframe';
   static public $items_id_1 = 'plugin_iframe_iframes_id';
    
   static public $itemtype_2 = 'Group';
   static public $items_id_2 = 'groups_id';
   
   static $rightname = "plugin_iframe";
   
   static function cleanForGroup(CommonDBTM $group) {
      $temp = new self();
      $temp->deleteByCriteria(
         array('groups_id' => $group->getField('id'))
      );
   }
   
   static function cleanForItem(CommonDBTM $item) {
      $temp = new self();
	  if ($item->getType()== 'Group'){
		 $temp->deleteByCriteria(
				array('groups_id' => $item->getField('id')));
	  } else if ($item->getType()== 'PluginIframeIframe') {
		  $temp->deleteByCriteria(
				array('plugin_iframe_iframes_id' => $item->getField('id')));
	  }
   }
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
		if ($item->getType()=='PluginIframeIframe') {
            return _n('Grupos','Grupos',2);
		}
	}


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {  
      if ($item->getType()=='PluginIframeIframe') {        
        self::showForIframe($item);
      } 
      return true;
   }
   
   static function countForAviso(PluginIframeIframe $frame) {
      return countElementsInTable('glpi_plugin_iframe_iframes_groups',
                                  " AND `plugin_iframe_iframes_id` = '".$frame->getID()."'");
   }

   function addItem($values) {

      $this->add(array('plugin_iframe_iframes_id' =>$values["plugin_iframe_iframes_id"],
                        'groups_id'=>$values["groups_id"]));
    
   }
   
	/**
	* Muestra los elementos de un aviso.
    **/
	
   static function showForIframe(PluginIframeIframe $frame) {
      global $DB, $CFG_GLPI;
	  
      $instID = $frame->fields['id'];
	
      if (!$frame->can($instID, READ)) {
         return false;
      }
      $canedit = $frame->can($instID, UPDATE);

      $rand   = mt_rand();
         echo "<form name='iframegroup_form$rand' id='iframegroup_form$rand' method='post'
               action='".Toolbox::getItemTypeFormURL("PluginIframeIframe_Group")."'>";
      if ($canedit) {
         echo "<div class='firstbloc'>";


         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_2'><th colspan='2'>A&ntilde;adir grupos a los que mostrar Iframe</th></tr>";

         echo "<tr class='tab_bg_1'><td class='center'>";
         
		 Group::dropdown(array('name'      => 'groups_id',
                               'entity'    => $frame->fields["entities_id"],
                               'condition' => ['is_assign' => 1]));		
         echo "</td><td class='center'>";
         echo "<input type='submit' name='addgroup' value=\""._sx('button', 'Add')."\" class='submit'>";
         echo "<input type='hidden' name='plugin_iframe_iframes_id' value='$instID'>";
         echo "</td></tr>";
         echo "</table>";
         echo "</div>";
      }

      echo "<div class='spaced'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";

      if ($canedit) {
         echo "<th width='10'>&nbsp;</th>";
      }
      echo "<th>Elementos</th>";
      echo "</tr>";

      $column = "name";
      $query     = "SELECT `glpi_groups`.*,
                           `glpi_plugin_iframe_iframes_groups`.`id` AS IDD, ";


      $query .= "`glpi_entities`.`id` AS entity
                  FROM `glpi_plugin_iframe_iframes_groups`, `glpi_groups`, `glpi_entities` ";
      $query .= "WHERE plugin_iframe_iframes_id=".$instID." and `glpi_groups`.`id` = `glpi_plugin_iframe_iframes_groups`.`groups_id`
				 GROUP BY `glpi_groups`.id ORDER BY `glpi_groups`.name";
      if ($result_linked = $DB->query($query)) {
               if ($DB->numrows($result_linked)) {
                  while ($data = $DB->fetch_assoc($result_linked)) {
                     $linkname = $data["name"];
                     if ($_SESSION["glpiis_ids_visible"]
                         || empty($data["name"])) {
                        $linkname = sprintf(__('%1$s (%2$s)'), $linkname, $data["id"]);
                     }

                     $link = '../../../front/group.form.php';
                     $name = "<a href=\"".$link."?id=".$data["id"]."\">".$linkname."</a>";

                     echo "<tr class='tab_bg_1'>";

                     if ($canedit) {
                        echo "<td width='10' style='padding-top: 0'>";
                        echo "<button type='submit'  value='".$data["id"]."' name='elimina' style='border:0; background-color: Transparent;' 
						onclick=\"return confirm('¿Seguro que deseas quitarle a este grupo este Ifrmae?');\">
						<img src='".$_SESSION["glpiroot"]."/plugins/avisos/imagenes/error.png' /></button>";
                        echo "</td>";
                     }
                     echo "<td ".
                           (isset($data['is_deleted']) && $data['is_deleted']?"class='tab_bg_2_2'":"").
                          ">".$name."</td>";
                     echo "</tr>";
                  }
               }
      }
      echo "</table>";
      if ($canedit) {
         $paramsma['ontop'] =false;
         
      }
	  Html::closeForm();
      echo "</div>";
	  echo "</form>";

   }
   

   /**
    * @since version 0.84
   **/
   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }
   

}
?>