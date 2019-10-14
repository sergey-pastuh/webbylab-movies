<?php

$excludeDirs = [VIEW];

function classloader($dir) {
    global $excludeDirs;
	if (!file_exists($dir) || !is_dir($dir) || in_array($dir, $excludeDirs)) {
		return;
	}

	$files = scandir($dir);
    usort($files, function($a, $b) {
        if (strpos($a, 'Base') !== false) {
            return -1;
        }
        if (strpos($b, 'Base') !== false) {
            return 1;
        }
        return 0;
    });

	foreach ($files as $filename) {
		$file = $dir.'/'.$filename;
		if (preg_match('/^\.*$/', $filename) || !file_exists($file)) {
			continue;
		}

		if (is_dir($file)) {
			classloader($file);
			continue;
		}

		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if ($ext === 'php') {
			require_once $file;
		}
	}
}

classloader(LIB);
