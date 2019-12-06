<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$file = fopen("/etc/haproxy/haproxy.cfg", "r") or die("Unable to open file!");
	if(isset($_POST["action_WS"])) {
		$data = array();
		while(!feof($file)) {
			$line = fgets($file);
			if(preg_match("/".$_POST["ip_id"].":".$_POST["port_id"]."/i", $line)) {
				if($_POST["action_WS"] == "delete") {
					continue;
				} else {
					array_push($data, "server ".$_POST["nom"]." ".$_POST["ip"].":".$_POST["port"]." cookie s2 check"."\n");	
				}
			} else if(trim($line, " \n") != "") {
				array_push($data, $line."\n");
			}
		}
		fclose($file);
		$file = fopen("/etc/haproxy/haproxy.cfg", "w+") or die("Unable to open file!");
		for($i=0;$i<count($data);$i++) {
			fwrite($file, $data[$i]);
		}
		fclose($file);
	} else if(isset($_POST["add"])) {
		$data = array();
		while(!feof($file)) {
			$line = fgets($file);
			$begin = "# BEGIN WEB_SERVER";
			if($_POST["type"] == "db") {
				$begin = "# BEGIN DB_SERVER";
			}
			if(preg_match("/".$begin."/i", $line)) {
				array_push($data, $line."\n");
				array_push($data, "server ".$_POST["label"]." ".$_POST["ip"].":".$_POST["port"]."  cookie s2 check\n");
			} else if(trim($line, " \n") != "") {
				array_push($data, $line."\n");
			}
		}
		fclose($file);
		$file = fopen("/etc/haproxy/haproxy.cfg", "w+") or die("Unable to open file!");
		for($i=0;$i<count($data);$i++) {
			fwrite($file, $data[$i]);
		}
		fclose($file);
	} else if(isset($_POST["action_DB"])) {
		$data = array();
		while(!feof($file)) {
			$line = fgets($file);
			if(preg_match("/".$_POST["ip_id"].":".$_POST["port_id"]."/i", $line)) {
				if($_POST["action_DB"] == "delete") {
					continue;
				} else {
					array_push($data, "server ".$_POST["nom"]." ".$_POST["ip"].":".$_POST["port"]."\n");	
				}
			} else if(trim($line, " \n") != "") {
				array_push($data, $line."\n");
			}
		}
		fclose($file);
		$file = fopen("/etc/haproxy/haproxy.cfg", "w+") or die("Unable to open file!");
		for($i=0;$i<count($data);$i++) {
			fwrite($file, $data[$i]);
		}
		fclose($file);
	}
}
function getServer($file, $begin, $end) {
	$server = array();
	$i = 0;
	while(!feof($file)) {
		$line = fgets($file);
		if(preg_match("/".$begin."/i", $line)) {
			$line = fgets($file);
			while(!preg_match("/".$end."/i", $line)) {
				if(trim($line, " \n") != "") {
					array_push($server, $line);
				}
				$line = fgets($file);
			}
			break;
		} 
	}
	for($i = 0; $i < count($server); $i++) {
		$server[$i] = explode(" ", preg_replace('/\s{2,}/', ' ', trim($server[$i])));
		$server[$i][2] = explode(":", $server[$i][2]);
	}
	return $server;
}

function getDbServer($file) {
	while(!feof($myfile)) {
		$line = fgets($myfile); 
		if(preg_match("/# BEGIN DB_SERVER/i", $line)) {
			$line = fgets($myfile);
			while(!preg_match("/# END/i", $line)) {
				if($line != "") {
					array_push($dbServer, $line);
				}
				$line = fgets($myfile);
			}
			break;
		}
	}
	for($i = 0; $i < count($dbServer); $i++) {
		$dbServer[$i] = explode(" ", preg_replace('/\s{2,}/', ' ', trim($dbServer[$i])));
		$dbServer[$i][2] = explode(":", $dbServer[$i][2]);
	}
}
shell_exec('/home/bary/Documents/ITU/haReload.sh');
$myfile = fopen("/etc/haproxy/haproxy.cfg", "r") or die("Unable to open file!");
$webServer = getServer($myfile, "# BEGIN WEB_SERVER", "# END");
$dbServer = getServer($myfile, "# BEGIN DB_SERVER", "# END");
fclose($myfile);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Test</title>
	<style>
		.result {
			color: green;
			background-color: black;
		}
	</style>
</head>
<body>
	<div>
		<form action="test.php" method="POST">
			<label for="ip">IP</label>
			<input type="text" placeholder="127.0.0.1" name="ip">
			<label for="port">Port</label>
			<input type="text" placeholder="80" name="port">
			<label for="label">Nom</label>
			<input type="text" placeholder="Server 1" name="label">
			<select name="type" id="type">
				<option value="ws">Web Server</option>
				<option value="db">Data Server</option>
			</select>
			<button name="add" type="submit">Add</button>
		</form>
		<br>
		<table border=2>
			<caption>Serveur web</caption>
			<thead>
				<tr>
					<th>#</th>
					<th>IP</th>
					<th>Port</th>
					<th>nom</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php for($i=0; $i<count($webServer); $i++) { ?>
				<form action="test.php" method="POST">
				<input type="hidden" value="<?php echo $webServer[$i][2][0]; ?>" name="ip_id">
				<input type="hidden" value="<?php echo $webServer[$i][2][1]; ?>" name="port_id">
					<tr>
						<td><?php echo $i+1; ?></td>
						<td><input type="text" value="<?php echo $webServer[$i][2][0]; ?>" name="ip"></td>
						<td><input type="text" value="<?php echo $webServer[$i][2][1]; ?>" name="port"></td>
						<td><input type="text" value="<?php echo $webServer[$i][1]; ?>" name="nom"></td>
						<td><button type="submit" name="action_WS" value="edit">Edit</button></td>
						<td><button type="submit" name="action_WS" value="delete">Delete</button></td>
					</tr>
				</form>
				<?php } ?>
			</tbody>
		</table>
		<br>
		<table border=2>
			<caption>Serveur de donnee</caption>
			<thead>
				<tr>
					<th>#</th>
					<th>IP</th>
					<th>Port</th>
					<th>nom</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php for($i=0; $i<count($dbServer); $i++) { ?>
				<form action="test.php" method="POST">
				<input type="hidden" value="<?php echo $dbServer[$i][2][0]; ?>" name="ip_id">
				<input type="hidden" value="<?php echo $dbServer[$i][2][1]; ?>" name="port_id">
					<tr>
						<td><?php echo $i+1; ?></td>
						<td><input type="text" value="<?php echo $dbServer[$i][2][0]; ?>" name="ip"></td>
						<td><input type="text" value="<?php echo $dbServer[$i][2][1]; ?>" name="port"></td>
						<td><input type="text" value="<?php echo $dbServer[$i][1]; ?>" name="nom"></td>
						<td><button type="submit" name="action_DB" value="edit">Edit</button></td>
						<td><button type="submit" name="action_DB" value="delete">Delete</button></td>
					</tr>
				</form>
				<?php } ?>
			</tbody>
		</table>
	</div>
</body>
</html>