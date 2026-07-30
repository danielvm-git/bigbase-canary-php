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

    public function testRenderReturnsExpectedHtmlStructure(): void
    {
        $versionFile = tempnam(sys_get_temp_dir(), "version");
        file_put_contents($versionFile, "1.2.3");

        $html = Footer::render($versionFile);

        $this->assertStringContainsString("<h1>bigbase canary (PHP)</h1>", $html);
        $this->assertStringContainsString("<footer>v1.2.3</footer>", $html);

        unlink($versionFile);
    }

    public function testRenderTrimsWhitespaceFromVersion(): void
    {
        $versionFile = tempnam(sys_get_temp_dir(), "version");
        file_put_contents($versionFile, "  2.0.0  \n\n");

        $html = Footer::render($versionFile);

        $this->assertStringContainsString("<footer>v2.0.0</footer>", $html);

        unlink($versionFile);
    }

    public function testRenderThrowsOnMissingFile(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('VERSION file not found');

        Footer::render('/nonexistent/path/VERSION');
    }

    public function testRenderThrowsOnEmptyFile(): void
    {
        $versionFile = tempnam(sys_get_temp_dir(), "version");
        file_put_contents($versionFile, "");

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('VERSION file is empty');

        try {
            Footer::render($versionFile);
        } finally {
            unlink($versionFile);
        }
    }

    public function testRenderThrowsOnWhitespaceOnlyFile(): void
    {
        $versionFile = tempnam(sys_get_temp_dir(), "version");
        file_put_contents($versionFile, "   \n  \t  ");

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('VERSION file is empty');

        try {
            Footer::render($versionFile);
        } finally {
            unlink($versionFile);
        }
    }

    public function testRootVersionFileIsValidSemver(): void
    {
        $versionFilePath = __DIR__ . '/../VERSION';
        $this->assertFileExists($versionFilePath);

        $version = trim((string) file_get_contents($versionFilePath));
        $this->assertMatchesRegularExpression('/^[0-9]+\.[0-9]+\.[0-9]+$/', $version);
    }

    public function testRootVersionFileMatchesLatestGitTag(): void
    {
        $versionFilePath = __DIR__ . '/../VERSION';
        $version = trim((string) file_get_contents($versionFilePath));

        $tag = trim((string) shell_exec('git describe --tags --abbrev=0 2>/dev/null | sed "s/^v//"'));
        if ($tag === '') {
            $this->markTestSkipped('No git tags found');
        }

        $this->assertSame($tag, $version, "VERSION file ($version) should match latest git tag ($tag)");
    }
}
