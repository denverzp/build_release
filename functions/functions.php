<?php

namespace BuildRelease;

function create_path($directories, $target_dir)
{
	$path = $target_dir;

	foreach ($directories as $directory) {
		$path = $path . '/' . $directory;

		if (!\is_dir($path)) {
			@\mkdir($path, 0777);
		}
	}
}

function delete_path($dir)
{
	$files = \array_diff(\scandir($dir, null), array('.', '..'));
	foreach ($files as $file) {
		(\is_dir("$dir/$file")) ? delete_path("$dir/$file") : \unlink("$dir/$file");
	}
	return \rmdir($dir);
}
