<?php
session_start();
require_once("conexion.php");

//$db->debug=true;

echo"<html> 
       <head>
         <link rel='stylesheet' href='css/estilos.css' type='text/css'>
       </head>
       <body>
         <div class='banner'>SISTEMA DE CONTROL DE MENSUALIDAD</div>";

if ((isset($_POST["accion"])) and ($_POST["accion"]=="Ingresar")){
    $nick = $_POST["nick"];
    $password = $_POST["password"];

    $sql1 = $db->Prepare("SELECT u.clave
                          FROM usuarios u
                          WHERE u.nom_usuario = ?
                          AND u.estado <> 'X'   
                        ");
    $rs1 = $db->GetAll($sql1, array($nick));

    if ($rs1)
       $clave_bd = $rs1[0]["clave"];
    else
      $clave_bd = 0;

    //PARA SACAR LOS DATOS DE LA PERSONA CONECTADA
    $sql2 = $db->Prepare("SELECT p.*
                          FROM personas p, usuarios u
                          WHERE u.nom_usuario = ?
                          AND u.id_persona = p.id_persona
                          AND p.estado <> 'X'   
                          AND u.estado <> 'X'   
                        ");
    $rs2 = $db->GetAll($sql2, array($nick));
    if ($rs2){
        $nombres = $rs2[0]["nombres"];
        $ap = $rs2[0]["ap"];
        $am = $rs2[0]["am"];
        $nom_completo = $nombres." ".$ap." ".$am;
    } else{
        $nom_completo = '';  
    }
    //echo"verificacion".password_verify($password, $clave_bd);

    if (password_verify($password, $clave_bd)) {
      $sql = $db->Prepare("SELECT u.*, ur.id_rol, r.rol
                           FROM usuarios u  
                           INNER JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario
                           INNER JOIN roles r ON ur.id_rol = r.id_rol
                           WHERE u.nom_usuario = ?                           
                           AND u.estado <> 'X'
                           AND ur.estado <> 'X'
                           AND r.estado <> 'X'
                       ");
      $rs = $db->GetAll($sql, array($nick));
    
      if ($rs) {
       foreach ($rs as $k => $linea) {
               $_SESSION["sesion_id_usuario"] = $linea["id_usuario"];
               $_SESSION["sesion_usuario"] = $linea["nom_usuario"];
               $_SESSION["sesion_id_rol"] = $linea["id_rol"];
               $_SESSION["sesion_rol"] = $linea["rol"];    
               $_SESSION["sesion_id_usuario"]                 ;
        }
        echo"<div class='mensaje'>";
        $mensage = "DATOS CORRECTOS";
        echo"<h1>".$mensage."</h1>";
        $mensage1 = "BIENVENIDO AL SISTEMA  .....!!!";
        echo"<h1>".$mensage1."</h1>";
        echo"<h1 style='color:red'>".$nom_completo."</h1>";
        echo"<a href='listado_tablas.php'>
                  <input type='button'style='cursor:pointer;border-radius:10px;font-weight:bold;height: 25px;' value='Continuar>>'></input>
             </a>     
            ";
       echo"</div>" ;    
      } 
    } else {
          echo"<div class='mensaje'>";
          $mensage = "DATOS INCORRECTOS NO ENCUENTRA AL USUARIO!!!";
          echo"<h1>".$mensage."</h1>";
          $mensage1 = "POR FAVOR INTENTE NUEVAMENTE";
          echo"<h1>".$mensage1."</h1>";
          echo"<a href='index.php'>
                  <input type='button' value='Volver>>>'></input>
             </a>     
            ";
          echo"</div>" ;    
      }
} else {
echo"<div class='mensaje'>";
    $mensage = "CERRANDO LA SESION!!!!!!!!!!!";
    echo"<h1>".$mensage."</h1>";
    $mensage1 = "SE ESTA SALIENDO DEL SISTEMA............";    
    echo"<h1>".$mensage1."</h1>";
    $nom_completo = '';
    session_destroy();
    echo"<a href='index.php'>
                  <input type='button' value='Volver>>>'></input>
             </a>     
            ";
}

echo "</body>
      </html> ";

?>