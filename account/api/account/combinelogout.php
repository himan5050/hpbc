<?php
	// Cleanup
	session_start();
	//user_logout();
	"<script type='text/javascript'>alert('hello');</script>";
	session_unset();
	session_destroy();
	setcookie('ersess', '1', time() - 3600,'/');
	setcookie('ersesslogout', '1', time() + 3600,'/');
	header("location:/scst/");
?>