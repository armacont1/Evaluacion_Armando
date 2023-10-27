<?php
session_start();
require_once("conexion.php");

//$db->debug=true;

echo"<html> 
       <head>
         <link rel='stylesheet' href='css/estilos.css' type='text/css'>
       </head>
       <body>
         <p></p>
         <h1>LISTADO DE TABLAS DEL SISTEMA</h1>";
         $sql1 = $db->Prepare("SELECT nombre, logo
                    FROM internado
                    WHERE id_internado = 1
                    AND  estado <> 'X' 
                  ");

          $rs1 =$db->GetAll($sql1);
          $nombre = $rs1[0]["nombre"];
          $logo=$rs1[0]["logo"];
          echo"<img src='privada/internado/logos/{$logo}' width='8%' >";

        
if (isset($_SESSION["sesion_id_rol"])){

	$sql = $db->Prepare("SELECT ac.*, op.id_opcion, op.orden, op.contenido, gr.id_grupo, gr.grupo, op.opcion 
					 FROM accesos ac
					 INNER JOIN opciones op ON ac.id_opcion = op.id_opcion
					 INNER JOIN grupos gr ON op.id_grupo = gr.id_grupo
					 WHERE ac.id_rol = '".$_SESSION["sesion_id_rol"]."'
					 AND ac.estado <> 'X'
					 AND op.estado <> 'X'
					 AND gr.estado <> 'X'
					 ORDER BY op.id_grupo, op.orden
						");
	$rs = $db->Execute($sql);


  echo"<h3>USUARIO:".$_SESSION['sesion_usuario']."&nbsp;&nbsp;";
  echo"ROL: ".$_SESSION['sesion_rol']."</h3>";


 echo "<div class='menu'>";
foreach ($rs as $r => $fila) {
	echo"<a onclick='location.href=\"sis_internado2_2022/".$fila["contenido"]."\"' style='cursor:pointer;'> ";
	  echo$fila["grupo"]." -- ".$fila["opcion"]."<br>";
	echo"</a>";
}      
 

 echo"<a onclick='location.href=\"validar.php\"'><input type='button'name='accion' value='Cerrar Sesion' style='cursor:pointer;border-radius:10px;font-weight:bold;height: 25px;'></a>";	
 echo"</div>";
}


echo "</body>
      </html> ";



?>