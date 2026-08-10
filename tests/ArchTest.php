<?php

namespace BetterFuturesStudio\FilamentLocalLogins\Tests;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class ArchTest extends TestCase
{
    public function test_source_files_do_not_use_debugging_functions(): void
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(dirname(__DIR__).'/src'),
        );

        foreach ($files as $file) {
            if (! $file instanceof SplFileInfo || ! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $contents = file_get_contents($file->getPathname());

            self::assertIsString($contents);
            self::assertDoesNotMatchRegularExpression(
                '/\\b(?:dd|dump|ray)\\s*\\(/',
                $contents,
                "Debugging function found in {$file->getPathname()}",
            );
        }
    }
}
