<?php
/**
 * tests for methods under PhpMyAdmin\UserPreferencesHeader class
 */
declare(strict_types=1);

namespace PhpMyAdmin\Tests;

use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Relation;
use PhpMyAdmin\Template;
use PhpMyAdmin\UserPreferencesHeader;
use Throwable;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

/**
 * tests for methods under PhpMyAdmin\UserPreferencesHeader class
 */
class UserPreferencesHeaderTest extends PmaTestCase
{
    /**
     * Setup various pre conditions
     */
    protected function setUp(): void
    {
        $GLOBALS['server'] = 0;
        $GLOBALS['PMA_PHP_SELF'] = 'index.php';
    }

    /**
     * Test for getContent with selected tab
     *
     * @throws Throwable
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testGetContentWithSelectedTab(): void
    {
        $dbi = $this->getMockBuilder(DatabaseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $GLOBALS['dbi'] = $dbi;
        $_GET['route'] = '/preferences/forms';
        $_GET['form'] = 'Features';

        $template = new Template();
        $content = UserPreferencesHeader::getContent($template, new Relation($dbi, $template));

        $this->assertStringContainsString(
            '<li class="active">',
            $content
        );
        $this->assertStringContainsString(
            '<a href="index.php?route=/preferences/forms&amp;form=Features&amp;server=0&amp;lang=en" class="tabactive">',
            $content
        );
        $this->assertStringContainsString(
            '<img src="themes/dot.gif" title="Features" alt="Features" class="icon ic_b_tblops">&nbsp;Features',
            $content
        );
    }

    /**
     * Test for getContent with "saved" get parameter
     *
     * @throws Throwable
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testGetContentAfterSave(): void
    {
        $dbi = $this->getMockBuilder(DatabaseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $GLOBALS['dbi'] = $dbi;
        $_GET['saved'] = true;

        $template = new Template();
        $this->assertStringContainsString(
            'Configuration has been saved.',
            UserPreferencesHeader::getContent($template, new Relation($dbi, $template))
        );
    }

    /**
     * Test for getContent with session storage
     *
     * @throws Throwable
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testGetContentWithSessionStorage(): void
    {
        $dbi = $this->getMockBuilder(DatabaseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $GLOBALS['dbi'] = $dbi;

        $template = new Template();
        $this->assertStringContainsString(
            'Your preferences will be saved for current session only. Storing them permanently requires',
            UserPreferencesHeader::getContent($template, new Relation($dbi, $template))
        );
    }
}
