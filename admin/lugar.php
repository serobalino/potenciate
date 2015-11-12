<?php require_once('../Connections/potenciate.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_mapa")) {
  $insertSQL = sprintf("INSERT INTO lugares (REFERENCIA, DIRECCION, LONGITUD, LATITUD) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['referencia'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['longitud'], "double"),
                       GetSQLValueString($_POST['latitud'], "double"));

  mysql_select_db($database_potenciate, $potenciate);
  $Result1 = mysql_query($insertSQL, $potenciate) or die(mysql_error());

  $insertGoTo = "administrador.php?alerta=nl";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$latitud= "-0.2103276297291737";
$longitud="-78.4908664226532";
$zoom= "17";
$tipo_mapa = "TERRAIN";
$direccion = "";

if (isset($_GET["direccion"])) $direccion=  urldecode ($_GET["direccion"]);
else $direccion="";

// LONGITUD Y LATITUD SI ESTAN COMO PARAMETROS LOS COJO
if (isset($_GET["dir"])) $direccion = $_GET["dir"];
if (strlen ($direccion) <= 8) $direccion =""; // SI LA DIRECCION ES MENOR QUE 8 NO LA PROCESO

// LONGITUD Y LATITUD SI ESTAN COMO PARAMETROS LOS COJO
if (isset($_GET["lon"])) $longitud= $_GET["lon"];
if (isset($_GET["lat"])) $latitud= $_GET["lat"];

// ZOOM ENTRE 0 y 19
if (isset($_GET["zoom"])) $zoom= $_GET["zoom"];
if (($zoom<=0) || ($zoom>=20)){ $zoom= "17";}


// TIPO DE MAPA
if (isset($_GET["tipo"])) $tipo_mapa= strtoupper($_GET["tipo"]);

// COMPRUEBO QUE EL TIPO ES UNO DE LOS QUE ACEPTA GOOGLE
if ($tipo_mapa == "SATELLITE") $error=0;
else
  if ($tipo_mapa == "ROADMAP") $error=0;
  else
    if ($tipo_mapa == "TERRAIN")$error=0;
    else $tipo_mapa = "HYBRID";
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
<link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link href="../css/bootstrapValidator.css" rel="stylesheet" media="screen">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<title>Lugares | Poténciate</title>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">

// VARIABLES GLOBALES JAVASCRIPT
var geocoder;
var marker;
var latLng;
var latLng2;
var map;

// INICiALIZACION DE MAPA
function initialize() {
  geocoder = new google.maps.Geocoder();
  latLng = new google.maps.LatLng(<?php echo $latitud;?> ,<?php echo $longitud;?>);
  map = new google.maps.Map(document.getElementById('mapCanvas'), {
    zoom:<?php echo $zoom;?>,
    center: latLng,
    mapTypeId: google.maps.MapTypeId.<?php echo $tipo_mapa;?>
  });


// CREACION DEL MARCADOR
    marker = new google.maps.Marker({
    position: latLng,
    title: 'Arrastra el marcador si quieres moverlo',
    map: map,
    draggable: true
  });

// Escucho el CLICK sobre el mama y si se produce actualizo la posicion del marcador
     google.maps.event.addListener(map, 'click', function(event) {
     updateMarker(event.latLng);
   });

  // Inicializo los datos del marcador
  //    updateMarkerPosition(latLng);

      geocodePosition(latLng);

  // Permito los eventos drag/drop sobre el marcador
  google.maps.event.addListener(marker, 'dragstart', function() {
    updateMarkerAddress('Arrastrando...');
  });

  google.maps.event.addListener(marker, 'drag', function() {
    updateMarkerStatus('Arrastrando...');
    updateMarkerPosition(marker.getPosition());
  });

  google.maps.event.addListener(marker, 'dragend', function() {
    updateMarkerStatus('Arrastre finalizado');
    geocodePosition(marker.getPosition());
  });



}


// Permito la gesti¢n de los eventos DOM
google.maps.event.addDomListener(window, 'load', initialize);

// ESTA FUNCION OBTIENE LA DIRECCION A PARTIR DE LAS COORDENADAS POS
function geocodePosition(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      updateMarkerAddress(responses[0].formatted_address);
    } else {
      updateMarkerAddress('No puedo encontrar esta direccion.');
    }
  });
}

// OBTIENE LA DIRECCION A PARTIR DEL LAT y LON DEL FORMULARIO
function codeLatLon() {
      str= document.form_mapa.longitud.value+" , "+document.form_mapa.latitud.value;
      latLng2 = new google.maps.LatLng(document.form_mapa.latitud.value ,document.form_mapa.longitud.value);
      marker.setPosition(latLng2);
      map.setCenter(latLng2);
      geocodePosition (latLng2);
      // document.form_mapa.direccion.value = str+" OK";
}

// OBTIENE LAS COORDENADAS DESDE lA DIRECCION EN LA CAJA DEL FORMULARIO
function codeAddress() {
        var address = document.form_mapa.direccion.value;
          geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
             updateMarkerPosition(results[0].geometry.location);
             marker.setPosition(results[0].geometry.location);
             map.setCenter(results[0].geometry.location);
           } else {
            alert('ERROR : ' + status);
          }
        });
      }

// OBTIENE LAS COORDENADAS DESDE lA DIRECCION EN LA CAJA DEL FORMULARIO
function codeAddress2 (address) {

          geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
             updateMarkerPosition(results[0].geometry.location);
             marker.setPosition(results[0].geometry.location);
             map.setCenter(results[0].geometry.location);
             document.form_mapa.direccion.value = address;
           } else {
            alert('ERROR : ' + status);
          }
        });
      }

function updateMarkerStatus(str) {
  document.form_mapa.direccion.value = str;
}

// RECUPERO LOS DATOS LON LAT Y DIRECCION Y LOS PONGO EN EL FORMULARIO
function updateMarkerPosition (latLng) {
  document.form_mapa.longitud.value =latLng.lng();
  document.form_mapa.latitud.value = latLng.lat();
}

function updateMarkerAddress(str) {
  document.form_mapa.direccion.value = str;
}

// ACTUALIZO LA POSICION DEL MARCADOR
function updateMarker(location) {
        marker.setPosition(location);
        updateMarkerPosition(location);
        geocodePosition(location);
      }
</script>
</script>
<style type="text/css">
  html { height: 100% }
  body { height: 80%; margin: 0px; padding: 0px }
  #mapCanvas { height: 70% }
</style>
</head>
<body class="bg-info" <?php  if ($direccion != "") { ?> onload=" codeAddress2('<?php  echo $direccion; ?>')" <?php  } ?> >
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
<div id="formulario">
  <center>
  <form name="form_mapa" method="POST" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $editFormAction; ?>">
	<div class="form-group">
    	<label for="referencia" class="control-label">Referencia:</label>
        <input type="text" name="referencia" class="form-control form-group" id="referencia" value="<?php echo $direccion;?>" required/>
  	    <label for="direccion" class="control-label">Direccion:</label>
        <input type="text" name="direccion" class="form-control form-group" id="direccion" value="<?php echo $direccion;?>" required/>
    </div>
		<button type="button"  class="form-control btn btn-success" onclick="codeAddress()">
              	<span class="glyphicon glyphicon-plus"></span> Buscar
        </button>
	   <input type="hidden" name="latitud" value="<?php echo $latitud;?>" />
	   <input type="hidden" name="longitud" value="<?php echo $longitud;?>"/>
	  <button type="submit"  class="form-control btn btn-warning">
              	<span class="glyphicon glyphicon-map-marker"></span> Guardar
       </button>
	  <input type="hidden" name="MM_insert" value="form_mapa">
     </form>
   </center>
</div>
</div>
<div id="mapCanvas" class="img-responsive"></div>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
