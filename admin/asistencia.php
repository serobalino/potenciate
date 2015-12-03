<?php require_once('../Connections/potenciate.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
function registrar($numerodeCI){
	
}
$colname_estudiante = "00";
if (isset($_POST['ci'])) {
  $colname_estudiante = $_POST['ci'];
}
mysql_select_db($database_potenciate, $potenciate);
$query_estudiante = sprintf("SELECT * FROM estudiantes WHERE CI = %s", GetSQLValueString($colname_estudiante, "text"));
$estudiante = mysql_query($query_estudiante, $potenciate) or die(mysql_error());
$row_estudiante = mysql_fetch_assoc($estudiante);
$totalRows_estudiante = mysql_num_rows($estudiante);

mysql_select_db($database_potenciate, $potenciate);
$query_nombre = "SELECT * FROM cursos WHERE CODIGO =".$_POST['codigo'];
$nombre = mysql_query($query_nombre, $potenciate) or die(mysql_error());
$row_nombre = mysql_fetch_assoc($nombre);
$totalRows_nombre = mysql_num_rows($nombre);
?>
<!doctype html>
<html lang="es">
<head>
<link rel="icon" type="image/x-icon" href="../logo.png">
<meta name="twitter:image:src" content="http://www.feuce.ec/potenciate/logo.png">
<meta itemprop="image" content="http://www.feuce.ec/potenciate/logo.png">
<meta property="og:image" content="http://www.feuce.ec/potenciate/logo.png">
<meta name="msapplication-TileImage" content="http://feuce.ec/potenciate/logo.png">
<meta name="description" content="FEUCE-Q POTÉNCIATE"/>
<meta name="author" content="SebastiaN RobalinO">
<meta name="keywords" content="POTÉNCIATE">
<link rel="shortcut icon" type="image/x-icon" href="/logo.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<title>Asistencia | Poténciate</title>
</head>
<body class="bg-info">
<header class="bg-primary center-block">
    <div class="center-block text-center">
	<div class="container hidden-xs" >
		<img src="http://www.plusresource.com/repository/dbepuce/FeuceBaner.jpg" alt="feuceLogo" class="img-rounded" height="90px">
    </div>
    <div class="container visible-xs">
		<img src="http://www.plusresource.com/repository/dbepuce/FeuceBaner.jpg" alt="feuceLogo" class="img-responsive img-rounded">
    </div>
    </div>
</header>
<br>
<br>
<br>
	<article class="container">
    
        <div class="panel panel-default">
          <div class="panel-heading"><h3>Asistencia para <?php echo $row_nombre['DESCRIPCION']; ?></h3></div>
          <div class="panel-body">
              <form action="" method="post">
              <div class="col-md-6 col-md-offset-3">
              	<p>Ingrese el Numero de Cédula</p>
                <div class="form-group">
                	<div class="input-group">
                        <label for="cedula" class="sr-only">Cedula:</label>
                        <div class="input-group-addon">CI</div>
                        <input class="form-control" type="text" id="cedula" name="ci" placeholder="CI/PASAPORTE" required autofocus/>
                        <input type="hidden" name="codigo" value="<?php echo $_POST['codigo'];?>">
                        <span class="input-group-btn"><button class="btn btn-success" type="submit" name="go"><span class="glyphicon glyphicon-play"></span></button></span>
                    </div>
                </div>
                <?php
				if (isset($_POST['ci'])){
                if ($totalRows_estudiante>0 ) {
					$updateSQL = sprintf("UPDATE registros SET ASISTENCIA=%s WHERE CODIGO=%s AND CI=%s",
                       GetSQLValueString("1", "text"),
                       GetSQLValueString($_POST['codigo'], "text"),
                       GetSQLValueString($_POST['ci'], "text"));
					  mysql_select_db($database_potenciate, $potenciate);
					  //$Result1 = mysql_query($updateSQL, $potenciate) or die(mysql_error());
					  $Result1 = mysql_query($updateSQL, $potenciate) or die(mysql_error());
					  //mysql_query("INSERT INTO registros (CODIGO,CI,FECHA_INSCRIPCION,ASISTENCIA) VALUES (".$_POST['codigo'].",".$_POST['ci'].",CURRENT_TIMESTAMP,'1')",$potenciate
				  echo "<p class='text-success'><b>".$row_estudiante['NOMBRE']." ".$row_estudiante['APELLIDO']."</b> asistencia registrada</p>";
				  
				}else{ ?>
				  <form action="post">
                  	<p><?php echo $_POST['ci'] ;?> no consta en la base de datos, desea inscribirlo y Agregarlo al Sistema?</p>
                    <div class="form-group">
                        <div class="input-group">
                            <button class="btn btn-success" type="submit" name="si"><span class="glyphicon glyphicon-ok-circle"></span> SI</button>
                            <button class="btn btn-danger" type="submit" name="no"><span class="glyphicon glyphicon-remove-circle"></span> NO</button>
                        </div>
                    </div>
                  </form>
				<?php
                }}
				?>
                </div>
              </form>
          </div>
      </div>
    </article>
</body>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</html>
<?php
mysql_free_result($estudiante);

mysql_free_result($nombre);
?>
