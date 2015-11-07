<?php require_once('Connections/potenciate.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  $z=$_SESSION['MM_Username'];
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
  $logoutGoTo = "salir.php?z=".$z;
  $z=NULL;
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "registro_frm") ) {
	for($i=1;$i<=$_POST["num"];$i++){
		if (isset($_POST['S'.$i])){
		  $insertSQL = sprintf("INSERT INTO registros (CODIGO, CI, FECHA_INSCRIPCION) VALUES (%s, %s, now())",
							   GetSQLValueString($_POST['C'.$i], "int"),
							   GetSQLValueString($_POST['I'.$i], "text"));
		  mysql_select_db($database_potenciate, $potenciate);
		  $Result1 = mysql_query($insertSQL, $potenciate) or die(mysql_error());
		}
	}
}

$colname_fecha = "-1";
if (isset($_POST['fechai'])) {
  $colname_fecha = $_POST['fechai'];
}
mysql_select_db($database_potenciate, $potenciate);
$query_fecha = sprintf("SELECT * FROM cursos WHERE FECHA = %s ORDER BY HORA_INICIO", GetSQLValueString($colname_fecha, "date"));
$fecha = mysql_query($query_fecha, $potenciate) or die(mysql_error());
$row_fecha = mysql_fetch_assoc($fecha);
$totalRows_fecha = mysql_num_rows($fecha);

mysql_select_db($database_potenciate, $potenciate);
$query_CursEstudiantes = "SELECT CODIGO FROM registros WHERE CI ='".$_SESSION['MM_Username']."'";
$CursEstudiantes = mysql_query($query_CursEstudiantes, $potenciate) or die(mysql_error());
$row_CursEstudiantes = mysql_fetch_assoc($CursEstudiantes);
$totalRows_CursEstudiantes = mysql_num_rows($CursEstudiantes);
?>
<?php do { 
  $registrados[]= $row_CursEstudiantes['CODIGO'];
 } while ($row_CursEstudiantes = mysql_fetch_assoc($CursEstudiantes));
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
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="stylesheet" href="css/estilos.css">
<title>Seleccion de Cursos | Poténciate</title>
</head>
<body class="bg-info" onload="Javascript:history.go(1);" onunload="Javascript:history.go(1);">
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
<div class="container cuerpo">
<div class="main row text-center">
<article class="container-fluid" id="cuerpo">
<div class="row">
<div class="container">
	<div class="row container">
    <br>
                	<div class='alert alert-warning text-left'>
                		<strong><p class='help-block'><span class='glyphicon glyphicon-info-sign'></span> INSTRUCCIONES</p></strong>
                        <p>1)	Seleccionar en el calendario la fecha del evento</p>
                        <p>2)	Click en <strong>Buscar</strong></p>
                        <p>3)	Marcar con  <strong> ✔ </strong>  los eventos en los que quieres inscribirte</p>
                        <p>4)	Click en <strong>Confirmar</strong></p>
                        <p>5)	Seleccionar otra fecha y repetir el proceso</p>
                        <p>6)	<strong>Salir y Guardar</strong></p>
                    </div>
      </div>
    <form action=""  method="post" class="form"  role="form">
        <fieldset>
            <legend class="text-left">Buscar evento</legend>
		      <div class="form-group col-md-7">
                <label for="dtp_input2" class="control-label">Seleccionar fecha</label>
                <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input name="fecha2" class="form-control form-group" size="18" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>

              </div>
                <div class="col-md-5">
              <input type="hidden" id="dtp_input2" name="fechai" value="" />
              <label class="control-label"> &nbsp;</label>
              <button type="submit"  class="form-control btn btn-info" value="Buscar" name="buscar">
              	<span class="glyphicon glyphicon-search"></span> Buscar
              </button>
            </div>
        </fieldset>
  </form>
  </div>
</div>
</article>
</div>
</div>
<article class="container-fluid" id="main">
<?php if (isset($_POST['buscar'])){
  if($_POST['fechai']!=''){?>
<div class="row">
  <div class="container">
        <?php if ($row_fecha['DESCRIPCION']!=""){?>
        <h3>Fecha <?php echo $_POST['fecha2']; ?> </h3>
      	<form action="<?php echo $editFormAction; ?>" method="POST" name="registro_frm">
        <div class="table-responsive">
        <table class="table table-hover container">
            <tr>
              <th class="text-center">Horario</th>
              <th>Descripción</th>
              <th class="hidden-xs">Tipo</th>
              <th class="hidden-xs">Cupo</th>
              <th>Ponente</th>
              <th>Lugar</th>
              <th class="text-center">Inscribirse</th>
            </tr>
            <?php $n=0; ?>
            <?php do { ?>
            <?php $n++; 
				$checar=0;
			  		for($i=0;$i<sizeof($registrados);$i++)
				  		if($row_fecha['CODIGO']==$registrados[$i])
							$checar=1;
				if($checar==0)
						echo '<div class="checkbox"><tr>';
					else
						echo '<div class="checkbox"><tr class="bg-success">';
			?>
              <td class="text-center"><?php echo date_format(new DateTime($row_fecha['HORA_INICIO']), 'H:i') ." - ".date_format(new DateTime($row_fecha['HORA_FIN']), 'H:i'); ?></td>
              <td><?php echo $row_fecha['DESCRIPCION']; ?></td>
              <td class="hidden-xs"><?php echo $row_fecha['TIPO']; ?></td>
              <td class="hidden-xs"><?php if ($row_fecha['CUPO']=='') echo "Ilimitado" ;else echo $row_fecha['CUPO']; ?></td>
              <td><?php echo $row_fecha['PONENTE']; ?></td>
              <td><?php echo $row_fecha['LUGAR']; ?></td>
              <td align="center">
              <?php 
					if($checar==0)
						echo '<input type="checkbox" name="S'.$n.'" value="s"></input>';
					else
						echo '<input type="checkbox" name="S'.$n.'" value="s" disabled></input>';
			  ?>
              </td>
              
              <input type="hidden" name="<?php echo 'C'.$n; ?>" value="<?php echo $row_fecha['CODIGO']; ?>">
              <input type="hidden" name="<?php echo 'I'.$n; ?>" value="<?php echo $_SESSION['MM_Username'];?>">
            </tr>
            </div>
            <?php } while ($row_fecha = mysql_fetch_assoc($fecha)); ?>
          </table>
          </div>
              <input type="hidden" name="num" value="<?php echo $n;?>"><br>
              <div class="text-center">
              <input type="submit" class="btn btn-success col-md-4 col-md-offset-4 col-xs-offset-1 col-xs-10" value="Confirmar" name="confirmar">
              </div>
              <input type="hidden" name="MM_insert" value="registro_frm">
        </form>
          <?php } else { ?>
            <h6 align="center" class="alert alert-danger" ><span class="glyphicon glyphicon-thumbs-down" ></span> No hay cursos en esta fecha</h3>
          <?php } ?>
    </div>
</div>
<?php }}?>
</article>
<article class="container-fluid">
  <div class="container">
    <form class="form" name="cerrar" action="salir.php?z=<?php echo $_SESSION['MM_Username']?>" method="post">
      <br>
      <br>
      <button type="submit" name="cerrar" class="btn btn-danger col-xs-offset-1 col-xs-10 col-md-4 col-md-offset-4" value="Salir">
      	<span class="glyphicon glyphicon-floppy-saved" ></span>  Salir y Guardar
      </button>
      <br>
      <br>
      <br>
    </form>
  </div>
</article>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="js/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>
<script type="text/javascript" src="js/options.js" charset="UTF-8"></script>
<script type="text/javascript">
	$('.form_date').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startDate: "2015-11-01",
		startView: 2,
		minView: 2,
		forceParse: 0
		
    });
</script>
</body>
</html>
<?php
mysql_free_result($fecha);
mysql_free_result($CursEstudiantes);
 $_SESSION['PrevUrl'] = NULL;
 unset($_SESSION['PrevUrl']);
?>
