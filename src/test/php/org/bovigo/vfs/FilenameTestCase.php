<?php
declare(strict_types=1);
/**
 * This file is part of vfsStream.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  org\bovigo\vfs
 */
namespace org\bovigo\vfs;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
use function bovigo\assert\predicate\contains;
use function bovigo\assert\predicate\equals;
/**
 * Test for directory iteration.
 *
 * @group  issue_104
 * @group  issue_128
 * @since  1.6.2
 */
class FilenameTestCase extends TestCase
{
    private $rootDir;
    private $lostAndFound;

    /**
     * set up test environment
     */
    protected function setUp(): void
    {
        vfsStream::setup('root');
        $this->rootDir = vfsStream::url('root');
        $this->lostAndFound = $this->rootDir . '/lost+found/';
        mkdir($this->lostAndFound);
    }

    /**
     * @test
     */
    public function worksWithCorrectName()
    {
        $results = [];
        $it = new \RecursiveDirectoryIterator($this->lostAndFound);
        foreach ($it as $f) {
            $results[] = $f->getPathname();
        }

        assertThat($results, equals([
            'vfs://root/lost+found' . DIRECTORY_SEPARATOR . '.',
            'vfs://root/lost+found' . DIRECTORY_SEPARATOR . '..'
        ]));
    }

    /**
     * @test
     */
    public function doesNotWorkWithInvalidName()
    {
        expect(function() {
            new \RecursiveDirectoryIterator($this->rootDir . '/lost found/');
        })->throws(\UnexpectedValueException::class)
          ->message(contains('failed to open dir'));
    }

    /**
     * @test
     */
    public function returnsCorrectNames()
    {
        $results = [];
        $it = new \RecursiveDirectoryIterator($this->rootDir);
        foreach ($it as $f) {
            $results[] = $f->getPathname();
        }

        assertThat($results, equals([
          'vfs://root' . DIRECTORY_SEPARATOR . '.',
          'vfs://root' . DIRECTORY_SEPARATOR . '..',
          'vfs://root' . DIRECTORY_SEPARATOR . 'lost+found'
        ]));
    }
}
