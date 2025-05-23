#!/usr/bin/env php
<?php

$projectRoot = dirname(__DIR__);
$stylesDir = "$projectRoot/src/styles";
$dependencyDir = "$projectRoot/src/styles/dependencies";
$outputFile = "$stylesDir/styles-out.scss";

// Create _dependencies.scss file if it doesn't exist.
file_put_contents($outputFile, '');

// Process styles.scss
// Process dependencies.scss
// Process custom.scss

$processedContent = file_get_contents($stylesDir . "/styles.scss") . "\n";

$depFiles = glob("$dependencyDir/*.scss");
foreach ($depFiles as $file) {
	$content = file($file);

	// Process each line of the file
	$partialPath = '';
	foreach ($content as $line) {
		if (str_starts_with($line, '@use')) {
			$path = str_replace('@use "../../../', '', $line);
			$path = trim(str_replace('";', '', $path));

			$fileContent = file("$projectRoot/$path");

			// Clean any @use and @import statements
			foreach ($fileContent as $innerLine) {
				if (str_contains($innerLine, '@use')) {
					continue;
				}

				if (str_contains($innerLine, '@import')) {
					continue;
				}

				$processedContent .= $innerLine . "\n";
			}
		} else {
			// Copy other content as is
			$processedContent .= $line . "\n";
		}
	}
}

$processedContent .= file_get_contents($stylesDir . "/custom.scss") . "\n";

// Remove empty lines
$cleanContent = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $processedContent);
file_put_contents($outputFile, $cleanContent);
