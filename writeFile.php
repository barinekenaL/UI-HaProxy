<?php
$path = "/home/bary/Desktop/fichier";
//$myfile = fopen($path, "r+") or die("Unable to open file!");
/* $data = "";
while(!feof($myfile)) {
	$data .= fgets($myfile)."<br/>";
}
fclose($myfile);*/
$data = "";
?>
<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Write file</title>
</head>
<body>
	<div style="border: 2">
		<?php echo $data; ?>
		<?php echo `whoami`; ?>
	</div>
</body>
</html>