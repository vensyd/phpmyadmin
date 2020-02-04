<?php
/**
 * Tests for PhpMyAdmin\Navigation\Nodes\NodeProcedure class
 */
declare(strict_types=1);

namespace PhpMyAdmin\Tests\Navigation\Nodes;

use PhpMyAdmin\Navigation\NodeFactory;
use PhpMyAdmin\Tests\PmaTestCase;

/**
 * Tests for PhpMyAdmin\Navigation\Nodes\NodeProcedure class
 */
class NodeProcedureTest extends PmaTestCase
{
    /**
     * SetUp for test cases
     */
    protected function setUp(): void
    {
        $GLOBALS['server'] = 0;
    }

    /**
     * Test for __construct
     *
     * @return void
     */
    public function testConstructor()
    {
        $parent = NodeFactory::getInstance('NodeProcedure');
        $this->assertArrayHasKey(
            'text',
            $parent->links
        );
        $this->assertStringContainsString(
            'index.php?route=/database/routines',
            $parent->links['text']
        );
    }
}
