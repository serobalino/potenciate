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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_nuevo")) {
  $insertSQL = sprintf("INSERT INTO cursos (FECHA, HORA_INICIO, HORA_FIN, DESCRIPCION, TIPO, CUPO, PONENTE, LUGAR) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['fecha'], "date"),
                       GetSQLValueString($_POST['hora_inicio'], "date"),
                       GetSQLValueString($_POST['hora_fin'], "date"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_POST['tipo'], "text"),
                       GetSQLValueString($_POST['cupo'], "int"),
                       GetSQLValueString($_POST['ponente'], "text"),
                       GetSQLValueString($_POST['lugar'], "text"));

  mysql_select_db($database_potenciate, $potenciate);
  $Result1 = mysql_query($insertSQL, $potenciate) or die(mysql_error());

  $insertGoTo = "administrador.php?alerta=ne";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_GET['CODIGO'])) && ($_GET['CODIGO'] != "")) {
  $deleteSQL = sprintf("DELETE FROM cursos WHERE CODIGO=%s",
                       GetSQLValueString($_GET['CODIGO'], "int"));

  mysql_select_db($database_potenciate, $potenciate);
  $Result1 = mysql_query($deleteSQL, $potenciate) or die(mysql_error());

  $deleteGoTo = "administrador.php?alerta=ee";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
if(isset($_POST['cons']) && isset($_POST['fechaD']) && isset($_POST['fechaH'])){
$feD=$_POST['fechaD'];
$feH=$_POST['fechaH'];
mysql_select_db($database_potenciate, $potenciate);
$query_xfacul = "SELECT estudiantes.FACULTAD,COUNT(estudiantes.CI) FROM registros,estudiantes,cursos WHERE registros.CI=estudiantes.CI and registros.CODIGO=cursos.CODIGO AND cursos.FECHA  BETWEEN '".$feD."' AND '".$feH."' GROUP BY estudiantes.FACULTAD";
$xfacul = mysql_query($query_xfacul, $potenciate) or die(mysql_error());
$row_xfacul = mysql_fetch_assoc($xfacul);
$totalRows_xfacul = mysql_num_rows($xfacul);
}
$hoy=date('Y-m-d'); ?>
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
<link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link href="../css/bootstrapValidator.css" rel="stylesheet" media="screen">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<title>Administrador | Poténciate</title>
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
<div class="container">
  <legend class="text-left">Administración</legend>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#esta">Estadísticas</a></li>
    <li><a data-toggle="tab" href="#menu2">Nuevo Evento</a></li>
    <li><a data-toggle="tab" href="#menu3">Eli./Edi. Eventos</a></li>
  </ul>
  <div class="tab-content">
    <div id="esta" class="tab-pane fade in active">
      <h3>Estadísticas</h3>
	  <h4>Consulta de inscritos por fecha de Evento</h4>
      <form action=""  method="post" class="form" name="consulta" role="form">
        <fieldset>
		      <div class="form-group col-md-6">
                <label for="dtp_input2" class="control-label">Desde</label>
                <div class="input-group date form_dateC" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
                    <input name="fehaD" class="form-control form-group" size="18" type="text" value="" readonly required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label for="dtp_input2" class="control-label">Hasta</label>
                <div class="input-group date form_dateC" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input name="fehaH" class="form-control form-group" size="18" type="text" value="" readonly required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
              </div>
              <input type="hidden" id="dtp_input1" name="fechaD" value="" />
              <input type="hidden" id="dtp_input2" name="fechaH" value="" />
              <div class="">
              <input type="hidden" id="dtp_input2" name="fechai" value="" />
              <button type="submit"  class="form-control btn btn-info" value="Buscar" name="cons">
              	<span class="glyphicon glyphicon-search"></span> Consultar
              </button>
            </div>
        </fieldset>
        <br>
        <br>
        <br>
      </form>
        <?php if(isset($_POST['cons']) && isset($_POST['fechaD']) && isset($_POST['fechaH'])){?>
        <div class="table-responsive">
        <table class="table table-hover table-bordered">
			<tr>
            	<th>Facultad</th>
                <th>Inscritos</th>
                <th>Asisténcia</th>
            </tr>
            <?php $ins=0 ?>
            <?php do { ?>
            <tr>
            	<td><?php echo $row_xfacul['FACULTAD']; ?></td>
                	<?php $ins+=$row_xfacul['COUNT(estudiantes.CI)']; ?>
            	<td><?php echo $row_xfacul['COUNT(estudiantes.CI)']; ?></td>
                <td></td> 	
            </tr>
            <?php } while ($row_xfacul = mysql_fetch_assoc($xfacul)); ?>
            <tr class="bg-success">
            	<td> TOTAL</td>
                <td><?php echo $ins;?></td>
            </tr>
        </table>
        </div>
		<?php } ?>
    </div>
    <div id="menu2" class="tab-pane fade">
    <?php 
	mysql_select_db($database_potenciate, $potenciate);
	$query_lugares = "SELECT * FROM lugares ORDER BY REFERENCIA ASC";
	$lugares = mysql_query($query_lugares, $potenciate) or die(mysql_error());
	$row_lugares = mysql_fetch_assoc($lugares);
	$totalRows_lugares = mysql_num_rows($lugares);
	
	mysql_select_db($database_potenciate, $potenciate);
$query_eventos = "SELECT * FROM cursos ORDER BY FECHA DESC";
$eventos = mysql_query($query_eventos, $potenciate) or die(mysql_error());
$row_eventos = mysql_fetch_assoc($eventos);
$totalRows_eventos = mysql_num_rows($eventos);
	?>
      <h3>Crear Nuevo Evento</h3>
      <div class="container">
      	<form method="POST" action="<?php echo $editFormAction; ?>" name="frm_nuevo" class="" id="frm_nuevo">
        	<fieldset>
            
              <div class="form-group">
                <label for="dtp_input3" class="col-md-2 control-label">Fecha del evento</label>
                <div class="input-group date form_date_nuevo" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                    <input name="fecha_nueva" class="form-control form-group col-md-4" size="18" type="text" value="" readonly required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    
                </div>
                <input type="hidden" id="dtp_input3" value="" name="fecha" />
              </div>
              
              <div class="form-group">
                <label for="dtp_input4" class="col-md-2 control-label">Hora de inicio</label>
                <div class="input-group date form_time1 col-md-4" data-date="" data-date-format="hh:ii" data-link-field="dtp_input4" data-link-format="hh:ii">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                </div>
				<input type="hidden" id="dtp_input4" value="" name="hora_inicio"/>
              </div>
              
              <div class="form-group">
                <label for="dtp_input5" class="col-md-2 control-label">Hora de fin</label>
                <div class="input-group date form_time2 col-md-4" data-date="" data-date-format="hh:ii" data-link-field="dtp_input5" data-link-format="hh:ii">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                </div>
				<input type="hidden" id="dtp_input5" value="" name="hora_fin"/>
              </div>
              
              <div class="form-group">
              <div class="input-group">
              <label for="descripcion" class="sr-only">Descripción de evento:</label>
                  <div class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></div>
                  <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripción corta del evento" required>
              </div>
              </div>
              
              <div class="form-group">
              <div class="input-group">
              <label for="tipo" class="sr-only">Tipo de evento:</label>
                  <div class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></div>
                  <input type="text" class="form-control" name="tipo" id="tipo" placeholder="Tipo de evento" required>
              </div>
              </div>
              
              <div class="form-group">
              <div class="input-group">
              <label for="cupo" class="sr-only">Cupo :</label>
                  <div class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></div>
                  <input type="text" class="form-control" name="cupo" id="cupo" placeholder="Cupo (este campo puede star vacio)">
              </div>
              </div>
              
              <div class="form-group">
              <div class="input-group">
              <label for="ponente" class="sr-only">Ponente :</label>
                  <div class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></div>
                  <input type="text" class="form-control" name="ponente" id="ponente" placeholder="Ponente (este campo puede star vacio)">
              </div>
              </div>
              
              <div class="form-group">
              <a href="lugar.php">Nuevo Lugar</a>
              <div class="input-group">
              <label for="lugar" class="sr-only">Lugar de evento :</label>
                  <div class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></div>
                  <select name="lugar" id="lugar" class="form-control" required >
                      <option default value=" ">-</option>
                      <?php do { ?>
                      <option value="<?php echo $row_lugares['COD_L']; ?>"><?php echo $row_lugares['REFERENCIA']; ?></option>
                      <?php } while ($row_lugares = mysql_fetch_assoc($lugares)); ?>
                  </select>
              </div>
              </div>
              
			  <button type="submit"  class="form-control btn btn-success" name="nuevo_curso">
              	<span class="glyphicon glyphicon-plus"></span> Publicar
              </button>

            </fieldset>
        	<input type="hidden" name="MM_insert" value="frm_nuevo">
        </form>
      </div>
    </div>
    <div id="menu3" class="tab-pane fade">
      <h3>Eliminar/Editar Eventos</h3>
      <div class="table-responsive">
        <table class="table table-hover container">
            <tr>
              <th class="text-center">Horario</th>
              <th>Descripción</th>
              <th class="hidden-xs">Tipo</th>
              <th class="hidden-xs">Cupo</th>
              <th>Ponente</th>
              <th>Lugar</th>
              <th class="text-center">Editar</th>
              <th class="text-center">Eliminar</th>
            </tr>
          <?php do { ?>  
          <tr>
              <td class="text-center"><?php echo date_format(new DateTime($row_eventos['HORA_INICIO']), 'H:i') ." - ".date_format(new DateTime($row_eventos['HORA_FIN']), 'H:i'); ?></td>
              <td><?php echo $row_eventos['DESCRIPCION']; ?></td>
              <td class="hidden-xs"><?php echo $row_eventos['TIPO']; ?></td>
              <td class="hidden-xs"><?php if ($row_eventos['CUPO']=='' || $row_eventos['CUPO']==0) echo "Ilimitado" ;else echo $row_eventos['CUPO']; ?></td>
              <td><?php echo $row_eventos['PONENTE']; ?></td>
              <td><?php echo $row_eventos['LUGAR']; ?></td>
              <td class="text-center"><a><span class="glyphicon glyphicon-pencil"></span></a></td>
              <td class="text-center"><a href="CODIGO=".<?php echo $row_eventos['CODIGO']; ?>><span class="glyphicon glyphicon-remove"></span></a></td>
          </tr>
          <?php } while ($row_eventos = mysql_fetch_assoc($eventos)); ?> 
        </table>
        </div>
    </div>
  </div>
</div>

</body>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/bootstrapValidator.js" charset="UTF-8"></script>
<script type="text/javascript" src="../js/validador.js" charset="UTF-8"></script>
<script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../js/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>
<script type="text/javascript" src="../js/options.js" charset="UTF-8"></script>
<script type="text/javascript">
	$('.form_date').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startDate: "<?php echo $hoy; ?>",
		startView: 2,
		minView: 2,
		forceParse: 0
		
    });
	$('.form_dateC').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
		
    });
	$('.form_date_nuevo').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startDate: "<?php echo $hoy; ?>",
		startView: 2,
		minView: 2,
		forceParse: 0
		
    });
	$('.form_time1').datetimepicker({
        language:  'es',
		weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
    });
	$('.form_time2').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
    });
</script>
</body>
</html>
<?php
if(isset($_POST['cons']) && isset($_POST['fechaD']) && isset($_POST['fechaH']))
mysql_free_result($xfacul);

mysql_free_result($lugares);
mysql_free_result($eventos);
?>
