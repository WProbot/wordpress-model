<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the WordPress Model package.
 *
 * (c) Guido Scialfa <dev@guidoscialfa.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace WordPressModel\Tests\Unit\Model;

use function date;
use function explode;
use ProjectTestsHelper\Phpunit\TestCase;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use Widoz\Bem\Service;
use Widoz\Bem\Valuable;
use WordPressModel\Model\DayArchiveLink as Testee;
use WordPressModel\Model\PostDateTime;

/**
 * Class DayArchiveLinkTest
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class DayArchiveLinkTest extends TestCase
{
    /**
     * Test Instance
     */
    public function testInstance()
    {
        $bem = $this->createMock(Service::class);
        $post = $this->getMockBuilder('WP_Post')->getMock();
        $text = 'Inner Text';
        $postDateTime = $this->createMock(PostDateTime::class);
        $testee = new Testee($bem, $post, $text, $postDateTime);

        self::assertInstanceOf(Testee::class, $testee);
    }

    /**
     * Test Data Model Contains Correct Values and Filter is Applied
     */
    public function testFilterGetAppliedWithCorrectData()
    {
        $expectedLink = 'Expected Link';
        $postDate = date('Y m d');
        $expectedClass = $this->createMock(Valuable::class);

        $bem = $this->createMock(Service::class);
        $post = $this->getMockBuilder('WP_Post')->getMock();
        $text = 'Inner Text';
        $postDateTime = $this->createMock(PostDateTime::class);
        $testee = new Testee($bem, $post, $text, $postDateTime);

        Functions\expect('get_day_link')
            ->once()
            ->with(...explode(' ', $postDate))
            ->andReturn($expectedLink);

        $postDateTime
            ->expects($this->once())
            ->method('date')
            ->with($post, 'Y m d')
            ->willReturn($postDate);

        $bem
            ->expects($this->once())
            ->method('forElement')
            ->willReturn($expectedClass);

        Filters\expectApplied(Testee::FILTER_DATA, [
            'text' => $text,
            'attributes' => [
                'href' => $expectedLink,
                'class' => $expectedClass,
            ],
        ]);

        $testee->data();
    }
}