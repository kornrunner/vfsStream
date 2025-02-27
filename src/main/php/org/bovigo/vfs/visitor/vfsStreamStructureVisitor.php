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
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use org\bovigo\vfs\vfsStreamBlock;

/**
 * Visitor which traverses a content structure recursively to create an array structure from it.
 *
 * @since  0.10.0
 * @see    https://github.com/mikey179/vfsStream/issues/10
 */
class vfsStreamStructureVisitor extends vfsStreamAbstractVisitor
{
    /**
     * collected structure
     *
     * @var  array
     */
    protected $structure = [];
    /**
     * poiting to currently iterated directory
     *
     * @var  array
     */
    protected $current;

    /**
     * constructor
     *
     * @api
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * visit a file and process it
     *
     * @param   vfsStreamFile  $file
     * @return  vfsStreamStructureVisitor
     */
    public function visitFile(vfsStreamFile $file): vfsStreamVisitor
    {
        $this->current[$file->getName()] = $file->getContent();
        return $this;
    }

    /**
     * visit a block device and process it
     *
     * @param   vfsStreamBlock $block
     * @return  vfsStreamStructureVisitor
     */
    public function visitBlockDevice(vfsStreamBlock $block): vfsStreamVisitor
    {
        $this->current['[' . $block->getName() . ']'] = $block->getContent();
        return $this;
    }

    /**
     * visit a directory and process it
     *
     * @param   vfsStreamDirectory  $dir
     * @return  vfsStreamStructureVisitor
     */
    public function visitDirectory(vfsStreamDirectory $dir): vfsStreamVisitor
    {
        $this->current[$dir->getName()] = [];
        $tmp           =& $this->current;
        $this->current =& $tmp[$dir->getName()];
        foreach ($dir as $child) {
            $this->visit($child);
        }

        $this->current =& $tmp;
        return $this;
    }

    /**
     * returns structure of visited contents
     *
     * @return  array
     * @api
     */
    public function getStructure(): array
    {
        return $this->structure;
    }

    /**
     * resets structure so visitor could be reused
     *
     * @return  self
     */
    public function reset(): self
    {
        $this->structure = [];
        $this->current   =& $this->structure;
        return $this;
    }
}
