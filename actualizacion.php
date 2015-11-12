<?php require_once('Connections/potenciate.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) {
  $isValid = False;
  if (!empty($UserName)) {
    $arrUsers = Explode(",", $strUsers);
    $arrGroups = Explode(",", $strGroups);
    if (in_array($UserName, $arrUsers)) {
      $isValid = true;
    }
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

mysql_select_db($database_potenciate, $potenciate);
$query_facultad = "SELECT * FROM facultades ORDER BY facul DESC";
$facultad = mysql_query($query_facultad, $potenciate) or die(mysql_error());
$row_facultad = mysql_fetch_assoc($facultad);
$totalRows_facultad = mysql_num_rows($facultad);

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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "datos")) {
  $updateSQL = sprintf("UPDATE estudiantes SET NOMBRE=%s, APELLIDO=%s, FACULTAD=%s, MAIL=%s,FECHAI=now() WHERE CI=".$_SESSION['MM_Username'],
                       GetSQLValueString(ucwords(strtolower($_POST['nombre'])), "text"),
                       GetSQLValueString(ucwords(strtolower($_POST['apellido'])), "text"),
                       GetSQLValueString($_POST['facultad'], "text"),
                       GetSQLValueString(strtolower($_POST['mail']), "text"),
                       GetSQLValueString($_POST['ci'], "text"));
  mysql_select_db($database_potenciate, $potenciate);
  $Result1 = mysql_query($updateSQL, $potenciate) or die(mysql_error());
  $updateGoTo = "inscripcion.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
mysql_select_db($database_potenciate, $potenciate);
$query_datos = "SELECT * FROM estudiantes WHERE CI = ".$_SESSION['MM_Username'];
$datos = mysql_query($query_datos, $potenciate) or die(mysql_error());
$row_datos = mysql_fetch_assoc($datos);
$totalRows_datos = mysql_num_rows($datos);
?>
<!doctype html>
<html lang="es">
<head>
<meta name="msapplication-TileImage" content="http://feuce.ec/potenciate/logo.png">
<meta name="description" content="FEUCE-Q POTÉNCIATE"/>
<meta name="author" content="SebastiaN RobalinO">
<meta name="keywords" content="POTÉNCIATE">
<link rel="shortcut icon" type="image/x-icon" href="/logo.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="stylesheet" href="css/estilos.css">
<title>Actualiza tus Datos| Poténciate</title>
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
<article class="container-fluid">
<div class="row">
<div class="container">
<legend class="text-left">Actualice sus datos<?php echo " ".$row_datos['NOMBRE']; ?></legend>
    <form method="POST" action="<?php echo $editFormAction; ?>" name="datos" class="form-horizontal">
    <fieldset>
        <div class="form-group">
            <label for="ci" class="control-label col-md-2">Cédula:</label>
            <div class="col-md-9">
            <input type="text" class="form-control" name="ci" id="ci" value="<?php echo $row_datos['CI']; ?>" disabled readonly>
            </div>
            </div>
        <div class="form-group">
            <label for="nombre" class="control-label col-md-2">Nombre:</label>
            <div class="col-md-9">
            <input type="text" class="form-control" name="nombre" value="<?php echo $row_datos['NOMBRE']; ?>" id="nombre" required>
            </div>
        </div>
        <div class="form-group">
            <label for="apellido" class="control-label col-md-2">Apellido:</label>
            <div class="col-md-9">
            <input type="text" class="form-control" name="apellido"  value="<?php echo $row_datos['APELLIDO']; ?>"id="apellido" required>
            </div>
        </div>
        <div class="form-group">
            <label for="facultad" class="control-label col-md-2">Facultad:</label>
            <div class="col-md-9">
                <select name="facultad" id="facultad" class="form-control" required>
                  <option default value="<?php echo $row_datos['FACULTAD'];?>"><?php echo $row_datos['FACULTAD'];?></option>
                  <?php do { ?>
                  <option value="<?php echo $row_facultad['facul'];?> "><?php echo $row_facultad['facul']; ?>  </option>
                  <?php } while ($row_facultad = mysql_fetch_assoc($facultad)); ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="mail" class="control-label col-md-2" >e-mail:</label>
            <div class="col-md-9">
            <input type="email" class="form-control" name="mail" value="<?php echo $row_datos['MAIL']; ?>" id="mail" required>
            </div>
        </div>
        <div class="form-group">
        <div class="col-md-3 col-md-offset-2">
            <button type="submit" value="Actualizar Datos" class="btn btn-success">
                <span class="glyphicon glyphicon-refresh"></span> Actualizar Datos
            </button>
            <input type="hidden" name="MM_update" value="datos">
        </div>
        </div>
    </fieldset>
    </form>
</div>
</div>
</article>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
<?php
mysql_free_result($facultad);

mysql_free_result($datos);
?>
