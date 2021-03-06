<?php
/**
 * @see       https://github.com/phly/keep-a-changelog for the canonical source repository
 * @copyright Copyright (c) 2019 Matthew Weier O'Phinney
 * @license   https://github.com/phly/keep-a-changelog/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\KeepAChangelog\Provider;

use Phly\KeepAChangelog\Exception\InvalidPackageNameException;
use Phly\KeepAChangelog\Provider\GitHub;
use PHPUnit\Framework\TestCase;

class GitHubTest extends TestCase
{
    public function setUp()
    {
        $this->github = new GitHub();
    }

    public function invalidPackageNames() : iterable
    {
        yield 'empty'                 => [''];
        yield 'invalid-vendor'        => ['@phly'];
        yield 'vendor-only'           => ['phly'];
        yield 'invalid-repo'          => ['phly/@invalid'];
        yield 'invalid-subgroup'      => ['phly/subgroup/package'];
    }

    /**
     * @dataProvider invalidPackageNames
     */
    public function testGeneratePullRequestLinkRaisesExceptionForInvalidPackageNames(string $package)
    {
        $this->expectException(InvalidPackageNameException::class);
        $this->github->generatePullRequestLink($package, 1);
    }

    public function validPackageNames() : iterable
    {
        // @codingStandardsIgnoreStart
        // @phpcs:disable
        yield 'typical'               => ['phly/keep-a-changelog', 42, 'https://github.com/phly/keep-a-changelog/pull/42'];
        yield 'typical-underscore'    => ['phly/keep_a_changelog', 42, 'https://github.com/phly/keep_a_changelog/pull/42'];
        // @phpcs:enable
        // @codingStandardsIgnoreEnd
    }

    /**
     * @dataProvider validPackageNames
     */
    public function testGeneratePullRequestLinkCreatesExpectedPackageLinks(string $package, int $pr, string $expected)
    {
        $link = $this->github->generatePullRequestLink($package, $pr);
        $this->assertSame($expected, $link);
    }
}
