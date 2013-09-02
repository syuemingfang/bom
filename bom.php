<?php
// Remove UTF-8 Boms By magicbug@gmail.com //
$baseDir=isset($_GET['dir']) ? $_GET['dir']: '.'; //Base Dir
$auto=true; 
checkDir($baseDir);

function checkDir($baseDir){
	if($dh=opendir($baseDir)){
		while(($file=readdir($dh)) !== false){
			if($file != '.' && $file != '..'){
				if(!is_dir($baseDir.'/'.$file)){
					echo 'Filename: '.$baseDir.'/'.$file.' '.checkBOM($baseDir.'/'.$file).' <br />';
				} else{
					$dirname=$baseDir.'/'.$file;
					checkDir($dirname);
				}
			}
		}
	closedir($dh);
	}
}

function checkBOM($fn) {
	global $auto;
	$contents=file_get_contents($fn);
	$charset[1]=substr($contents, 0, 1); 
	$charset[2]=substr($contents, 1, 1); 
	$charset[3]=substr($contents, 2, 1); 
	if(ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191){
		if($auto){
			$rest=substr($contents, 3);
			reWrite($fn, $rest);
			return('<b>BOM Found, Automatically Removed</b>');
		} else{
			return('<b>BOM Found</b>');
		}
	} 
	else return ('BOM Not Found');
}

function reWrite($fn, $data){
	$num=fopen($fn, 'w');
	flock($num, LOCK_EX);
	fwrite($num, $data);
	fclose($num);
}
?>