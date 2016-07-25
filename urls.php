<html>
<head>
<title>CSS Example</title>
<link rel="stylesheet" href="style.css"
</title>
</head>
<body>
<?php
// include the menu
include ("menu.html");

// configuration
$url = $_SERVER['REQUEST_URI'];
$file = 'urls.txt';

// check if form has been submitted
if (isset($_POST['text'])) {
$trimmed = rtrim($_POST['text']);
	$urls = explode("\n", $trimmed);
	foreach ($urls as $data) {
		$data = rtrim($data);
        	if (filter_var($data, FILTER_VALIDATE_URL) === FALSE) {
	            exit("$data is not a valid URL");
	        }
	}

    // save the text contents
    file_put_contents($file, $trimmed);

    // redirect to form again
    header(sprintf('Location: %s', $url));
}

// read the textfile
$text = file_get_contents($file);
?>

Fill out urls to load <br>
<form action="" method="post">
<textarea name="text"><?php echo htmlspecialchars($text) ?></textarea>
<br><input type="submit" />
</form>

</body>
</html>
