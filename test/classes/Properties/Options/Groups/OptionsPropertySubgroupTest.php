<?php
/**
 * tests for PhpMyAdmin\Properties\Options\Groups\OptionsPropertySubgroup class
 */
declare(strict_types=1);

namespace PhpMyAdmin\Tests\Properties\Options\Groups;

use PhpMyAdmin\Properties\Options\Groups\OptionsPropertySubgroup;
use PHPUnit\Framework\TestCase;

/**
 * tests for PhpMyAdmin\Properties\Options\Groups\OptionsPropertySubgroup class
 */
class OptionsPropertySubgroupTest extends TestCase
{
    protected $object;

    /**
     * Configures global environment.
     */
    protected function setUp(): void
    {
        $this->object = new OptionsPropertySubgroup();
    }

    /**
     * tearDown for test cases
     */
    protected function tearDown(): void
    {
        unset($this->object);
    }

    /**
     * Test for PhpMyAdmin\Properties\Options\Groups\OptionsPropertySubgroup::getItemType
     *
     * @return void
     */
    public function testGetItemType()
    {
        $this->assertEquals(
            'subgroup',
            $this->object->getItemType()
        );
    }

    /**
     * Test for
     *     - PhpMyAdmin\Properties\Options\Groups\OptionsPropertySubgroup::getSubgroupHeader
     *     - PhpMyAdmin\Properties\Options\Groups\OptionsPropertySubgroup::setSubgroupHeader
     *
     * @return void
     */
    public function testGetSetSubgroupHeader()
    {
        $this->object->setSubgroupHeader('subGroupHeader123');

        $this->assertEquals(
            'subGroupHeader123',
            $this->object->getSubgroupHeader()
        );
    }
}
