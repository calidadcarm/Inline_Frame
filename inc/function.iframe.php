<?php
/*
   ----------------------------------------------------------
   Plugin Iframe 1.0
   GLPI 9.1.6 
  
   Autor: Javier David Marín Zafrilla.
   Fecha: Febrero 2019

   ----------------------------------------------------------
 */
	

 
// Devuelve true si un grupo tiene acceso a un aviso
// y false si no lo tiene
function mostrar_iframe_grupo($iframe_id, $groups_id){
	global $DB;
	$select = "SELECT * FROM glpi_plugin_iframe_iframes_groups
			   WHERE plugin_iframe_iframes_id=".$iframe_id." and groups_id=".$groups_id.";";
			 //  echo $select;
	$result = $DB->query($select);
	$num_rows = $DB->numrows($result);
	if ($num_rows>0){
		return true;
	} else{
		return false;
	}
}
 
 
   // Función monta las pestañas con los IFRAMES visibles.

 function show_tabs_Iframes($iframe) {	 
	
	if ($iframe) {	
		//echo count($iframe);
		$x=1;
		
		$cabecera='<style>
body {font-family: Arial;}

/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: 2px solid #ffffff;
  outline: none;
  cursor: pointer;
  padding: .5em 1em;
  transition: 0.3s;
  font-size: 11px;
  font-weight: bold;
  font-family: Verdana,Arial,sans-serif;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #fff;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
</style>

<div class="tab">';
		
		$body='';
		
	$grupos= array();
		
	foreach ($iframe as $iframes => $frame){
		
   if (!in_array($frame['id'], $grupos)) { // Controla que los Iframes no se dupliquen por grupos
				
  if (empty($frame['color'])) { $color="rgb(191, 8, 5)";  } else { $color=$frame['color'];  }
				
	   $cabecera=$cabecera.'  <button id="frame_'.$x.'_buttom" class="tablinks" style="color:'.$color.';  *border:0.2px solid #848484;" title="'.$frame['comment'].'" onclick="openFrame(event, \'frame_'.$x.'\', \''.$frame['query'].'\')">'.$frame['name'].'</button>';	   	 
	 
	   $body=$body.'<div id="frame_'.$x.'" style="background-color:#f3f3f3; *background-color:'.$color.';" class="tabcontent">';
	   //if (!empty($frame['comment'])) { $body=$body.'<h3>"'.$frame['comment'].'"</h3>';  } 	   
	   
	   if ($frame['show']>0) { 
	   	  
	   $body=$body.'<p><iframe id="frame'.$x.'" src="" width="100%" height="700px" style="*background-color: '.$color.'; border:2px solid #848484;"></iframe></p></div>';
	   } else { 
	   
	   $body=$body.'<h1>'.$frame['name'].'</h1><h3>'.$frame['comment'].'</h3><p><img title="'.$frame['comment'].'" src="'.$_SESSION['glpiroot'].'/plugins/iframe/img/seccion-mantenimiento.png"></p></div>'; 
	   
	   }	  	 
	   		
		$x++;
		
		array_push($grupos, $frame['id']); // Controla que los Iframes no se dupliquen por grupos
		
	} }
	
	$cabecera=$cabecera.'</div>';
	$body=$body.'<script>

function openFrame(evt, iframe, url) {
  var i, tabcontent, tablinks, frame=$(\'#\' + iframe.replace("_", ""));
  

  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {	  
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }    
  
  document.getElementById(iframe).style.display = "block";
  document.getElementById(iframe+\'_buttom\').className += " active";  
  
        if (frame.attr("src") == ""){            	
				  frame.attr(\'src\',url); 
        }              
};	
	
$( document ).ready(function() {
    	$("#frame_1_buttom").click();  
});

</script>';	

return $cabecera.$body;
		
		} else {  
		
		return "";
			
			
		}
			
		
	
  }	
  
  
  // Función que obtiene los IDs de los iframes activos y con permisos individual de usuario o por grupo
 
     function find_Iframes() {

	global $DB;
	 
	$sql_iframes="SELECT * FROM glpi_plugin_iframe_iframes a WHERE `active`=1 and is_deleted=0 "; 
				
	$result = $DB->query($sql_iframes);
	$num_rows = $DB->numrows($result);
	$iframe = NULL;
				
	//echo $sql_iframes."<BR>";
	
		if ($num_rows > 0){
		$users_id = Session::getLoginUserID(); // Usuario
		$groups = Group_User::getUserGroups($users_id); // Grupos a los que pertenece el usuario
		//var_dump($groups);
		while ($row = $DB->fetch_array($result)) {
						
			$id = $row['id'];
			$right = "plugin_iframe_iframes_".$id;
					  
					/*  echo "<BR><BR><BR>Session".Session::haveRight($right,READ)."<br>";
						echo "ID:".$_SESSION['glpiactiveprofile']["id"]."<BR>";
						echo "right - $right -- ".var_dump(Session::haveRight($right,READ));
						echo "<br>right:".$right."<BR>";
						echo "read:".READ."<BR>";
						echo "<br>".var_dump($_SESSION["glpiactiveprofile"]);
						echo "glpiactiveprofile ".$_SESSION["glpiactiveprofile"][$right]."<BR>";*/
						
			if (Session::haveRight($right,READ)) {
					
				// Si aplica chequear y mostrar el frame (revisando perfil)
				$select = $row['url'];
				$text = $row['name'];			
				$color = $row['color'];
				$active =  $row['active'];
				$comment =  $row['comment'];
				$entities_id = $row['entities_id']; 
				$show = $row['show'];
				$iframe[] = array('id' => $id,
                              'query'    => $select,
                              'name'    => $text,							  
							  'color'    => $color,
							  'active' => $active,
							  'comment' => $comment,
							  'entities_id' => $entities_id,
							  'show' => $show);
			} else { // Comprobar si tengo acceso a ese aviso por el grupo al que pertenezco
//echo "nnnnnnnnn<BR><BR><BR>";			
				$mostrar = false; 
				foreach ($groups as $group => $grupo){
				//	echo $grupo['id']."<br>";
					if (mostrar_iframe_grupo($id, $grupo['id']) == true) {
					//echo "grupo ".$grupo['id']." name ".$grupo['name']."<br>";
					
					/* //Cuantos usuarios tiene el grupo? 
					$groupusers = Group_User::getGroupUsers($grupo['id']);
					foreach ($groupusers as $groupuser) {
					//echo "<br> id ".$groupuser["id"]." name ". $groupuser['firstname']. " ". $groupuser['realname']."<br>";
					}*/
					
						// Si aplica chequear y mostrar el aviso (revisando grupo)
						$select = $row['url'];
						$text = $row['name'];			
						$color = $row['color'];
						$active =  $row['active'];
						$comment =  $row['comment'];
						$entities_id = $row['entities_id']; 
						$show = $row['show'];						
						$iframe[] = array('id' => $id,
                              'query'    => $select,
                              'name'    => $text,							  
							  'color'    => $color,
							  'active' => $active,
							  'comment' => $comment,
							  'entities_id' => $entities_id,
							  'show' => $show);
							  
					}
				}
			}
		}
	}
   //VAR_DUMP($iframe);	
   return $iframe;
  }	


?>