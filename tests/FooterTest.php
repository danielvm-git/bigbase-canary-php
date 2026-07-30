<?php

// story: e01s01
// scenario: SC-e01s01-P0-01

declare(strict_types=1);

namespace App\Tests;

use App\Footer;
use PHPUnit\Framework\TestCase;

final class FooterTest extends TestCase
{
    public function testRenderContainsVersion(): void
    {
        $versionFile = tempnam(sys_get_temp_dir(), "version");
        file_put_contents($versionFile, "0.1.0\n");

        $html = Footer::render($versionFile);

        $this->assertStringContainsString("0.1.0", $html);
        $this->assertStringContainsString("bigbase canary (PHP)", $html);

        unlink($versionFile);
    }

    public function testRootVersionFileIsValidSemver(): void
    {
        $versionFilePath = __DIR__ . '/../VERSION';
        $this->assertFileExists($versionFilePath);

        $version = trim((string) file_get_contents($versionFilePath));
        $this->assertMatchesRegularExpression('/^[0-9]+\.[0-9]+\.[0-9]+$/', $version);
    }
}

