<?php
	include '../db.php';
	if (isset($_POST['upload'])) {
		$dest_dir = 'SPKs/';
		$tmp_dir = '../tmp/';
		if (strstr($_FILES['spk_file']['type'], 'spk')) {
			move_uploaded_file($_FILES['spk_file']['tmp_name'], $tmp_dir . $_FILES['spk_file']['name']);
			preg_match('/^(.*)-([0-9\.]*|tip)-([0-9]*)-(.*)\.spk$/', $_FILES['spk_file']['name'], &$matches);
			echo '<pre>';
			print_r($matches);
			echo '</pre>';
		}
	}
?>
<html>
<head>
	<title>Diaoul SPK Upload</title>
	<meta name="robots" content="noindex,nofollow" />
</head>
<body>
	<form method="post" enctype="multipart/form-data" action="uploadpackage.php">
		<label for="spk_file">SPK:</label><input type="file" name="spk_file" id="spk_file" />
		<input type="submit" name="upload" value="OK" />
	</form>
</body>
</html>
