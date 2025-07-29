<?php
use TrashBlx\Core\SoapUtils;
try{
	require $_SERVER['DOCUMENT_ROOT'].'/config/main.php';

	$soapUtils = new SoapUtils();
	$userid = (int)$_GET['userId'];
	function deleteOlderFiles($path,$days) {
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				$filelastmodified = filemtime($path . $file);
				if((time() - $filelastmodified) > $days*24*3600)
				{
					if(is_file($path . $file)) {
						unlink($path . $file);
					}
				}
			}
			closedir($handle);
		}
	}
	$path = ($_SERVER['DOCUMENT_ROOT'].'/3dcache/');
	$days = 2;
	deleteOlderFiles($path,$days);
	$yes = $soapUtils->renderUser3D($userid);
	$json = json_decode($yes[0],true);
	$obj = base64_decode($json['files']['scene.obj']['content']);
	$mtl = base64_decode($json['files']['scene.mtl']['content']);

	$textures = array();
	foreach ($json['files'] as $filename => $file) {
		if (strpos($filename, 'Tex.png') !== false || strpos($filename, 'tex.png') !== false) {
			$texture = base64_decode($file['content']);
			$texturehash = "/3dcache/" . md5($texture) . ".txt";
			$mtl = str_replace($filename, md5($texture), $mtl);
			if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $texturehash)) {
				file_put_contents($_SERVER['DOCUMENT_ROOT'] . $texturehash, $texture);
			}
			$textures[] = '"'.md5($texture).'"';
		}
	}

	$objhash = md5($obj) . ".txt";
	$mtlhash = md5($mtl) . ".txt";

	if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/3dcache/' . $objhash)) {
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/3dcache/' . $objhash, $obj);
	}

	if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/3dcache/' . $mtlhash)) {
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/3dcache/' . $mtlhash, $mtl);
	}
	

	$xpos = $json['camera']['position']['x'];
	$ypos = $json['camera']['position']['y'];
	$zpos = $json['camera']['position']['z'];
	$xdir = $json['camera']['direction']['x'];
	$ydir = $json['camera']['direction']['y'];
	$zdir = $json['camera']['direction']['z'];
	$xaabbmin = $json['AABB']['min']['x'];
	$yaabbmin = $json['AABB']['min']['y'];
	$zaabbmin = $json['AABB']['min']['z'];
	$xaabbmax = $json['AABB']['max']['x'];
	$yaabbmax = $json['AABB']['max']['y'];
	$zaabbmax = $json['AABB']['max']['z'];

	$texturearray = implode(',', $textures);
	echo'{"camera":{"position":{"x":'.$xpos.',"y":'.$ypos.',"z":'.$zpos.'},"direction":{"x":'.$xdir.',"y":'.$ydir.',"z":'.$zdir.'},"fov":70.0},"aabb":{"min":{"x":'.$xaabbmin.',"y":'.$yaabbmin.',"z":'.$zaabbmin.'},"max":{"x":'.$xaabbmax.',"y":'.$yaabbmax.',"z":'.$zaabbmax.'}},"mtl":"'.md5($mtl).'","obj":"'.md5($obj).'","textures":['.$texturearray.']}';
}catch(Throwable $e){
	echo $e;
}