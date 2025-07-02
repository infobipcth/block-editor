#!/usr/bin/env php
<?php

$projectRoot = dirname(__DIR__);
$stylesDir = "$projectRoot/src/styles";
$dependencyDir = "$projectRoot/src/styles/dependencies";
$outputFile = "$projectRoot/src/styles.scss";

// Create _dependencies.scss file if it doesn't exist.
file_put_contents($outputFile, '');

// Initialize processed content
$processedContent = '';
if (file_exists($stylesDir . "/styles.scss")) {
    $processedContent = file_get_contents($stylesDir . "/styles.scss") . "\n";
}

$depFiles = glob("$dependencyDir/*.scss");
foreach ($depFiles as $file) {
    if (!file_exists($file)) {
        continue;
    }
    $content = file($file);

    // Process each line of the file
    foreach ($content as $line) {
        if (str_starts_with($line, '@use')) {
            $path = str_replace('@use "../../../', '', $line);
            $path = trim(str_replace('";', '', $path));
            $sanitizedPath = realpath("$projectRoot/$path");
            if ($sanitizedPath && str_starts_with($sanitizedPath, $projectRoot)) {
                $fileContent = file($sanitizedPath);

                // Clean any @use and @import statements
                foreach ($fileContent as $innerLine) {
                    if (str_contains($innerLine, '@use') || str_contains($innerLine, '@import')) {
                        continue;
                    }
                    $processedContent .= $innerLine . "\n";
                }
            }
        } else {
            // Copy other content as is
            $processedContent .= $line . "\n";
        }
    }
}

if (file_exists($stylesDir . "/custom.scss")) {
    $processedContent .= file_get_contents($stylesDir . "/custom.scss") . "\n";
}

// Remove empty lines
$cleanContent = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $processedContent);
file_put_contents($outputFile, $cleanContent, FILE_APPEND);
