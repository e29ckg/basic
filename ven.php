<?php
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	// header('Location: '.$uri.'/dashboard/');
	header('Location: '.$uri.'/basic/web/ven/show_ven_change/'.$_GET['ref']);
	exit;
	// echo $_GET['ref'];
?>
Something is wrong with the XAMPP installation :-(
