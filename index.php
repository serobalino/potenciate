<?php require_once('Connections/potenciate.php'); ?>
<?php
$mensaje="<div class='alert alert-warning'><p class='help-block'><span class='glyphicon glyphicon-pencil'></span> Ingrese su cédula de identidad</p></div>";
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['ci'])) {
  $loginUsername=$_POST['ci'];
  $password=$_POST['ci'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "actualizacion.php";
  $MM_redirectLoginFailed = "";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_potenciate, $potenciate);

  $LoginRS__query=sprintf("SELECT CI, CI FROM estudiantes WHERE CI=%s AND CI=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"));

  $LoginRS = mysql_query($LoginRS__query, $potenciate) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";

	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
    }
	$mensaje ="<div class='alert alert-success'><p class='help-block'><span class='glyphicon glyphicon-ok'></span> Se encontró en la Base</p></div>";
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    $mensaje ="<div class='alert alert-danger'><p class='help-block'><span class=' glyphicon glyphicon-remove'></span> No es miembro de la PUCE</p></div>";
  }
}
?>
<!doctype html>
<html lang="es">
<head>
<link rel="icon" type="image/x-icon" href="http://feuce.ec/potenciate/logo.png">
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
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<title>Bienvenid@ | Poténciate</title>
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
    <br><br><br >
    <div class="container">
    <div class="main row text-center">
    <article class="col-md-10 col-md-offset-1">
    	<?php echo $mensaje;?>
        <form METHOD="POST" action="<?php echo $loginFormAction;?>" class="form-vertical">
        <div class="form-group">
        	<div class="input-group">
                <label for="cedula" class="sr-only">Cedula:</label>

                <div class="input-group-addon">CI</div>

                <input class="form-control" type="text" id="cedula" name="ci" placeholder="CI/PASAPORTE" required/>
                </div>
            </div>
            <br><br>
            <div class="form-group">
            <div class="col-md-4 col-md-offset-4">
            <button type="submit" value="Ver Cursos" class="form-control btn btn-success">
    				<span class="glyphicon glyphicon-thumbs-up"></span> Ingresar
            </button>
            </div>
            </div>
            </div>
        </form>
    </article>
    </div>
    </div>
    <footer class="bg-primary navbar navbar-fixed-bottom">
    <div class="container text-center">
    	<a href="../"><p class="text-muted">FEUCE</p></a>
    </div>
	</footer>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
