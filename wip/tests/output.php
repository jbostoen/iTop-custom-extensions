<?php

	print_r($_POST);

	file_put_contents('output.txt', print_r($_POST, true));
	