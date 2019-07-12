<?php
// name of the archive file
define('FILENAME', 'last_changes_');
// path to project folder
define('SOURCE_DIR', realpath(__DIR__ . '/../') . '/');
// path to build folder
define('TARGET_DIR', __DIR__ . '/release/');
// temporary folder name - after script execution - removed
define('TEMP_DIR', __DIR__ . '/tmp/');
// set current date - used in name of the archive file
define('CURRENT_DATE', (new \DateTime())->format('Y-m-d'));
