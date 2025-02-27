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
namespace org\bovigo\vfs\visitor;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
use function bovigo\assert\predicate\equals;
/**
 * Test for org\bovigo\vfs\visitor\vfsStreamStructureVisitor.
 *
 * @since  0.10.0
 * @see    https://github.com/mikey179/vfsStream/issues/10
 * @group  issue_10
 */
class vfsStreamStructureVisitorTestCase extends TestCase
{
    private $structureVisitor;

    protected function setUp(): void
    {
        $this->structureVisitor = new vfsStreamStructureVisitor();
    }
    /**
     * @test
     */
    public function visitFileCreatesStructureForFile()
    {
        assertThat(
            $this->structureVisitor->visitFile(
                vfsStream::newFile('foo.txt')->withContent('test')
            )->getStructure(),
            equals(['foo.txt' => 'test'])
        );
    }

    /**
     * @test
     */
    public function visitFileCreatesStructureForBlock()
    {
        assertThat(
            $this->structureVisitor->visitBlockDevice(
                vfsStream::newBlock('foo')->withContent('test')
            )->getStructure(),
            equals(['[foo]' => 'test'])
        );
    }

    /**
     * @test
     */
    public function visitDirectoryCreatesStructureForDirectory()
    {
        assertThat(
            $this->structureVisitor->visitDirectory(
                  vfsStream::newDirectory('baz')
            )->getStructure(),
            equals(['baz' => []])
        );
    }

    /**
     * @test
     */
    public function visitRecursiveDirectoryStructure()
    {
        $structure = [
          'root' => ['test' => [
                        'foo'     => ['test.txt' => 'hello'],
                        'baz.txt' => 'world'
                    ],
                    'foo.txt' => ''
        ]];
        $root = vfsStream::setup('root', null, $structure['root']);
        assertThat(
            $this->structureVisitor->visitDirectory($root)->getStructure(),
            equals($structure)
        );
    }
}
