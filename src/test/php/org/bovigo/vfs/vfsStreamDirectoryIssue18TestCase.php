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
use function bovigo\assert\assertTrue;
use function bovigo\assert\predicate\equals;
use function bovigo\assert\predicate\isInstanceOf;
use function bovigo\assert\predicate\isOfSize;
/**
 * Test for org\bovigo\vfs\vfsStreamDirectory.
 *
 * @group  bug_18
 */
class vfsStreamDirectoryIssue18TestCase extends TestCase
{
    /**
     * access to root directory
     *
     * @var  vfsStreamDirectory
     */
    protected $rootDirectory;

    /**
     * set up test environment
     */
    protected function setUp(): void
    {
        $this->rootDirectory = vfsStream::newDirectory('/');
        $this->rootDirectory->addChild(vfsStream::newDirectory('var/log/app'));
        $dir = $this->rootDirectory->getChild('var/log/app');
        $dir->addChild(vfsStream::newDirectory('app1'));
        $dir->addChild(vfsStream::newDirectory('app2'));
        $dir->addChild(vfsStream::newDirectory('foo'));
    }

    /**
     * @test
     */
    public function shouldContainThreeSubdirectories()
    {
        assertThat(
            $this->rootDirectory->getChild('var/log/app')->getChildren(),
            isOfSize(3)
        );
    }

    /**
     * @test
     */
    public function shouldContainSubdirectoryFoo()
    {
        assertTrue($this->rootDirectory->getChild('var/log/app')->hasChild('foo'));
        assertThat(
            $this->rootDirectory->getChild('var/log/app')->getChild('foo'),
            isInstanceOf(vfsStreamDirectory::class)
        );
    }

    /**
     * @test
     */
    public function shouldContainSubdirectoryApp1()
    {
        assertTrue($this->rootDirectory->getChild('var/log/app')->hasChild('app1'));
        assertThat(
            $this->rootDirectory->getChild('var/log/app')->getChild('app1'),
            isInstanceOf(vfsStreamDirectory::class)
        );
    }

    /**
     * @test
     */
    public function shouldContainSubdirectoryApp2()
    {
        assertTrue($this->rootDirectory->getChild('var/log/app')->hasChild('app2'));
        assertThat(
            $this->rootDirectory->getChild('var/log/app')->getChild('app2'),
            isInstanceOf(vfsStreamDirectory::class)
        );
    }
}
