<?php

function MsgErr($msg, $filename) {
	$image  = imagecreate(200, 30); /* Create a black image */
	$tc = imagecolorallocate($image, 255, 255, 255);
	$bgc  = imagecolorallocate($image, 0, 0, 0);
	imagefilledrectangle($image, 0, 0, 200, 30, $bgc);
	/* Output an errmsg */
	imagestring($image, 2, 50, 2, "Error loading:", $tc);
	imagestring($image, 2, 5, 15, $filename, $tc);
}

function Load(&$image, $filename) {
  $image_info = @getimagesize($filename);
  $image_type = $image_info[2];
  if( $image_type == IMAGETYPE_JPEG ) {
	$image = @imagecreatefromjpeg($filename);
  } elseif( $image_type == IMAGETYPE_GIF ) {
	$image = @imagecreatefromgif($filename);
  } elseif( $image_type == IMAGETYPE_PNG ) {
	$image = @imagecreatefrompng($filename);
  }

  if (empty($image)) { /* See if it failed */
	MsgErr('Error loading:', $filename);
	return false;
  }

  return true;
}

function Save($image, $filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
  if( $image_type == IMAGETYPE_JPEG ) {
	imagejpeg($image,$filename,$compression);
  } elseif( $image_type == IMAGETYPE_GIF ) {
	imagegif($image,$filename);
  } elseif( $image_type == IMAGETYPE_PNG ) {
	imagepng($image,$filename);
  }
  if( $permissions != null) {
	chmod($filename,$permissions);
  }
}

function Output($image, $image_type=IMAGETYPE_JPEG) {
  if( $image_type == IMAGETYPE_JPEG ) {
	imagejpeg($image);
  } elseif( $image_type == IMAGETYPE_GIF ) {
	imagegif($image);
  } elseif( $image_type == IMAGETYPE_PNG ) {
	imagepng($image);
  }
}

function GetWidth($image) {
  return imagesx($image);
}

function GetHeight($image) {
  return imagesy($image);
}

function Resize($image, $width, $height) {
  $new_image = imagecreatetruecolor($width, $height);
  imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, GetWidth($image), GetHeight($image));
  return $new_image;
}

function ResizeToHeight($image, $height) {
  $ratio = $height / GetHeight($image);
  $width = GetWidth($image) * $ratio;
  return Resize($image, $width, $height);
}

function ResizeToWidth($image, $width) {
  $ratio = $width / GetWidth($image);
  $height = GetHeight($image) * $ratio;
  return Resize($image, $width, $height);
}

function Scale($image, $scale) {
  $width = GetWidth($image) * $scale/100;
  $height = Getheight($image) * $scale/100; 
  return Resize($image, $width, $height);
}

//---main-------------------------------------------------------------------------
$file = $_REQUEST['file'];
if(empty($file) || !is_file($file)){
	echo $file;
	exit;
}

//print_r(getimagesize($file));
//exit;

$exibiu = false;
$path = $_REQUEST['path'];
$new_width = $_REQUEST['width'];
if (isset($_REQUEST['resize']))
	$resize = $_REQUEST['resize'];
else
	$resize = '';

if (empty($new_width) || $resize == 'no' || !function_exists('gd_info') || !extension_loaded('gd'))
{
	if( strpos($file, '/var/httpd/') === 0) {
		$file = str_replace('/var/httpd/viars/ct00062752/www.imobiliariasaogeraldo.com.br/htdocs', '', $file);
	}
	header("Location: ".$file);
	exit;
}

try {
	ob_start();
	if (Load($image, $path.$file)) {
		$image = ResizeToWidth($image, $new_width);
		header('Content-Type: image/jpeg');
		Output($image);
		// Tudo certo entao exibe a imagem tratada
		ob_end_flush();
		$exibiu = true;
	}
} catch (Exception $e) {
	ob_end_clean();
}

if (!$exibiu) {
	if( strpos($file, '/var/httpd/') === 0) {
		$file = str_replace('/var/httpd/viars/ct00062752/www.imobiliariasaogeraldo.com.br/htdocs', '', $file);
	}
	header("Location: ".$file);
}
?>
