<?php

namespace BuildRelease;

\clearstatcache();

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions/functions.php';
require_once __DIR__ . '/functions/utf8.php';

// get files list
$files = [];

$file = new \SplFileObject(__DIR__ . '/files.ini');

$file->setFlags(\SplFileObject::DROP_NEW_LINE); 
$file->setFlags(\SplFileObject::READ_AHEAD); 
$file->setFlags(\SplFileObject::SKIP_EMPTY); 

// Loop until we reach the end of the file.
while (!$file->eof()) {
    $files[] = rtrim($file->fgets());
}
$file = null; // Unset the file to call __destruct(), closing the file handle.

// Process
// create tmp directory
create_path(['tmp'], __DIR__);

// Copy files to tmp directory
foreach ($files as $file) {

	$source_file = SOURCE_DIR . $file;

	if(true === \file_exists($source_file)){

		$directories = \explode('/', \dirname($file));

		create_path($directories, TEMP_DIR);

		\copy($source_file, TEMP_DIR . $file);

	} else {
		echo 'Error! Cannot find file ' . $source_file . PHP_EOL;
	}
}

// Create ZIP
$filename = FILENAME . '_' . CURRENT_DATE. '.zip';

if(true === \file_exists(TARGET_DIR . $filename)){
	@\unlink(TARGET_DIR . $filename);
}

if(extension_loaded('zip')){

	$zip = new \ZipArchive();

	if ($zip->open(TARGET_DIR . $filename, \ZIPARCHIVE::CREATE) !== true) {
		die("Could not open archive");
	}

	$zip_files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(TEMP_DIR),	\RecursiveIteratorIterator::LEAVES_ONLY);

	foreach ($zip_files as $name => $file)
	{
		// Skip directories (they would be added automatically)
		if (!$file->isDir())
		{
			// Get real and relative path for current file
			$filePath = $file->getRealPath();
			$relativePath = \utf8_substr($filePath, \utf8_strlen(TEMP_DIR));

			// Add current file to archive
			$zip->addFile($filePath, $relativePath);
		}
	}
	$zip->close();
}

// Remove tmp directory
delete_path(TEMP_DIR);