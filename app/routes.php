<?php

// Home page
$app->get('/', function () {
	require '../src/model.php';

	ob_start(); // start buffering HTML output
	require '../views/view.php';
	return ob_get_clean();
});