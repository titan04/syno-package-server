<?php

include '../db.php';
if (isset($_POST['upload'])) {  // a file has been uploaded
	$dest_dir = getcwd() . '/SPKs/';
	$tmp_dir = realpath(getcwd() . '/../tmp/') . '/';
	if (strstr($_FILES['spk']['type'], 'spk')) {  // check type
		$filename = $_FILES['spk']['name'];
		$tmp_filepath = $tmp_dir . $filename;
		move_uploaded_file($_FILES['spk']['tmp_name'], $tmp_filepath);  // move it in the right place
		preg_match('/^(.*)-([0-9\.]*|tip)-([0-9]*)-(.*)\.spk$/', $filename, &$matches);  // identify variables for further use
		$package = $matches[1];
		$pkg_version = $matches[2];
		$spk_version = $matches[3];
		$arch = $matches[4];
		$maintainer = $_POST['maintainer'];
		
		// untar the INFO file
		exec('cd ' . $tmp_dir . ' && tar -xf ' . $filename . ' INFO');
		
		// parse the INFO file
		$info = parse_ini_file($tmp_dir . 'INFO');
		
		// create the destination filepath
		$dest_filepath = $dest_dir . $info['package'] . '/' . $filename;
		
		// clean up database
		$sql = 'DELETE FROM packages WHERE package = :package AND arch = :arch';
		$q = $db->prepare($sql);
		$q->bindParam(':package', $info['package'], PDO::PARAM_STR);
		$q->bindParam(':arch', $info['arch'], PDO::PARAM_STR);
		$q->execute();
		
		// creating variables
		$url = 'http://' . $_SERVER['HTTP_HOST'] . '/SPKs/' . $info['package'] . '/' . $filename;
		$md5 = md5_file($tmp_filepath);
		$size = filesize($tmp_filepath);
		$qinst = true;
		$start = true;
		$beta = ($_POST['beta'] == 'on' ? 1 : 0);
		$changelog = (isset($_POST['changelog']) && $_POST['changelog'] != '' ? $_POST['changelog'] : null);
		
		// insert
		$sql = 'INSERT INTO packages (package,version,dname,`desc`,arch,link,md5,icon,size,qinst,depsers,deppkgs,start,maintainer,changelog,beta)
			VALUES (:package,:version,:dname,:desc,:arch,:link,:md5,:icon,:size,:qinst,:depsers,:deppkgs,:start,:maintainer,:changelog,:beta)';
		$q = $db->prepare($sql);
		$q->bindParam(':package', $info['package'], PDO::PARAM_STR);
		$q->bindParam(':version', $info['version'], PDO::PARAM_STR);
		$q->bindParam(':dname', $info['displayname'], PDO::PARAM_STR);
		$q->bindParam(':desc', $info['description'], PDO::PARAM_STR);
		$q->bindParam(':arch', $info['arch'], PDO::PARAM_STR);
		$q->bindParam(':link', $url, PDO::PARAM_STR);
		$q->bindParam(':md5', $md5, PDO::PARAM_STR);
		$q->bindParam(':icon', $info['package_icon'], PDO::PARAM_LOB);
		$q->bindParam(':size', $size, PDO::PARAM_STR);
		$q->bindParam(':qinst', $qinst, PDO::PARAM_BOOL);
		$q->bindParam(':depsers', $info['install_dep_services'], PDO::PARAM_STR);
		$q->bindParam(':deppkgs', $info['install_dep_packages'], PDO::PARAM_STR);
		$q->bindParam(':start', $start, PDO::PARAM_BOOL);
		$q->bindParam(':maintainer', $info['maintainer'], PDO::PARAM_STR);
		$q->bindParam(':changelog', $changelog, PDO::PARAM_STR);
		$q->bindParam(':beta', $beta, PDO::PARAM_BOOL);
		$q->execute();
		$package_id = $db->lastInsertId();
		
		// add descriptions
		$sql = 'INSERT INTO package_descriptions (package_id,language,description) VALUES (:package_id,:language,:description)';
		$q = $db->prepare($sql);
		foreach ($info as $k => $v) {
			if (preg_match('/^description_(\w{3})$/', $k, &$matches)) {
				$q->bindParam(':package_id', $package_id, PDO::PARAM_INT);
				$q->bindParam(':language', $matches[1], PDO::PARAM_STR, 3);
				$q->bindParam(':description', $info[$matches[0]], PDO::PARAM_STR);
				$q->execute();
			}
		}
		
		// move the SPK so it is available on internet
		if (!is_dir($dest_dir . $info['package'])) {
			mkdir($dest_dir . $info['package'], 0755);
		}
		rename($tmp_filepath, $dest_filepath);
		
		// clean up
		exec('rm -f ' . $tmp_dir . '*');
	}
}

?>
<html>
<head>
	<title>SPK Upload</title>
	<meta name="robots" content="noindex,nofollow" />
</head>
<body>
	<form method="post" enctype="multipart/form-data" action="uploadpackage.php">
		<label for="spk">SPK (Max: <?php echo ini_get('upload_max_filesize'); ?>):</label><input type="file" name="spk" id="spk" /><br />
		<input type="checkbox" name="beta" id="beta" /><label for="beta">beta</label><br />
		<label for="changelog">changelog</label><textarea id="changelog" name="changelog"></textarea><br/>
		<input type="submit" name="upload" value="OK" />
	</form>
</body>
</html>
