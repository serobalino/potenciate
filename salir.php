<?php require_once('Connections/potenciate.php'); ?>
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
$s=$_GET['z'];
$colname_smail = "-1";
if (isset($_GET['aquiponer'])) {
  $colname_smail = $_GET['aquiponer'];
}
mysql_select_db($database_potenciate, $potenciate);
$query_smail = sprintf("SELECT MAIL FROM estudiantes WHERE CI ='".$s."'", GetSQLValueString($colname_smail, "text"));
$smail = mysql_query($query_smail, $potenciate) or die(mysql_error());
$row_smail = mysql_fetch_assoc($smail);
$totalRows_smail = mysql_num_rows($smail);

mysql_select_db($database_potenciate, $potenciate);
$query_email = "SELECT cursos.HORA_INICIO, cursos.HORA_FIN, cursos.FECHA, cursos.DESCRIPCION, cursos.TIPO, cursos.LUGAR FROM cursos,registros,estudiantes WHERE registros.Ci=estudiantes.CI AND registros.CODIGO=cursos.CODIGO AND registros.CI ='".$s."' AND registros.FECHA_INSCRIPCION  BETWEEN estudiantes.FECHAI AND now()";
$email = mysql_query($query_email, $potenciate) or die(mysql_error());
$row_email = mysql_fetch_assoc($email);
$totalRows_email = mysql_num_rows($email);
?>
<!doctype html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<META HTTP-EQUIV="REFRESH" CONTENT="5;URL=http://www.feuce.ec/potenciate">
<link rel="stylesheet" href="css/estilos.css">
<title>Gracias por participar | Poténciate</title>
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
<h2>
<div class='alert alert-success text-center'>
<p class='help-block'><span class='glyphicon glyphicon-education'></span> No faltes a tus eventos inscritos.</p>
</div>
</h2>
<?php
	$cuerpo='<div class="container"> <div class="list-group">';
	do { 
		if($row_email['DESCRIPCION']!=""){
    	$cuerpo.='<a href="#" class="list-group-item">';
      	$cuerpo.='<h4 class="list-group-item-Item">'.($row_email['DESCRIPCION']);
		if ($row_email['TIPO']!="") ($cuerpo.=' / '.$row_email['TIPO'].'</h4>');
      	$cuerpo.='<p class="list-group-item-text">De '.(date_format(new DateTime($row_email['HORA_INICIO']),'H:i')).' a '.(date_format(new DateTime($row_email['HORA_FIN']),'H:i')).' el '.$row_email['FECHA'].'</p>';
        if($row_email['LUGAR'] != "") ($cuerpo.='En '.$row_email['LUGAR']);
		$cuerpo.='</a>';
		}else{
			echo "<div class='alert alert-danger text-center'><p class='help-block'><span class=' glyphicon glyphicon-thumbs-down'></span> No te has inscrito a ningun evento.</p></div>";
		}
	}while ($row_email = mysql_fetch_assoc($email));
	$cuerpo.='</div></div>';
	echo $cuerpo;
?>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
<?php
	$para ="'".$row_smail['MAIL']."'";
	$asunto='Inscripción Poténciate';
	$cabeza='<head> <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"></head><body class="bg-info"><header class="bg-primary center-block"> <div class="center-block text-center"><div class="container hidden-xs" ><img src="http://www.plusresource.com/repository/dbepuce/FeuceBaner.jpg" alt="feuceLogo" class="img-rounded" height="90px"> </div><div class="container visible-xs"><img src="http://www.plusresource.com/repository/dbepuce/FeuceBaner.jpg" alt="feuceLogo" class="img-responsive img-rounded"> </div></div></header><article>';
	$pie='</article><article class="container center-block text-center"> <br><br><br><a href="http://www.feuce.ec/potenciate" class="btn btn-success ">Registrate</a></article></body>';
	$mensaje=$cabeza.$cuerpo.$pie;
	$cabeceras  = 'MIME-Version: 1.0' . '\r\n';
	$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . '\r\n';
	$cabeceras .= 'Para:<'.$row_smail['MAIL'].'>'.'\r\n';
	$cabeceras .= 'De: Inscripción Poténciate'. '\r\n';
	mail($para, $asunto, $mensaje,$cabeceras);?>
</html>
<?php
mysql_free_result($smail);
mysql_free_result($email);
 $_SESSION['MM_Username'] = NULL;
 $_SESSION['MM_UserGroup'] = NULL;
 $_SESSION['PrevUrl'] = NULL;
 unset($_SESSION['MM_Username']);
 unset($_SESSION['MM_UserGroup']);
 unset($_SESSION['PrevUrl']);
 
?>