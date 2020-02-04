<?php
/**
 * Holds DatabasesControllerTest class
 */
declare(strict_types=1);

namespace PhpMyAdmin\Tests\Controllers\Server;

use PhpMyAdmin\Config;
use PhpMyAdmin\Controllers\Server\DatabasesController;
use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Message;
use PhpMyAdmin\Response;
use PhpMyAdmin\Template;
use PHPUnit\Framework\TestCase;
use stdClass;
use function sprintf;

/**
 * Tests for DatabasesController class
 */
class DatabasesControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['PMA_Config'] = new Config();
        $GLOBALS['PMA_Config']->enableBc();

        $GLOBALS['server'] = 1;
        $GLOBALS['db'] = 'pma_test';
        $GLOBALS['table'] = '';
        $GLOBALS['PMA_PHP_SELF'] = 'index.php';
        $GLOBALS['cfg']['Server']['DisableIS'] = false;
        $GLOBALS['pmaThemeImage'] = 'image';
        $GLOBALS['text_dir'] = 'text_dir';
    }

    public function testIndexAction(): void
    {
        global $cfg, $dblist, $is_create_db_priv;

        $dblist = new stdClass();
        $dblist->databases = [
            'sakila',
            'employees',
        ];

        $controller = new DatabasesController(
            Response::getInstance(),
            $GLOBALS['dbi'],
            new Template()
        );

        $actual = $controller->index([
            'statistics' => null,
            'pos' => null,
            'sort_by' => null,
            'sort_order' => null,
        ]);

        $this->assertStringContainsString('data-filter-row="SAKILA"', $actual);
        $this->assertStringContainsString('sakila', $actual);
        $this->assertStringContainsString('utf8_general_ci', $actual);
        $this->assertStringContainsString('title="Unicode, case-insensitive"', $actual);
        $this->assertStringContainsString('data-filter-row="SAKILA"', $actual);
        $this->assertStringContainsString('employees', $actual);
        $this->assertStringContainsString('latin1_swedish_ci', $actual);
        $this->assertStringContainsString('title="Swedish, case-insensitive"', $actual);
        $this->assertStringContainsString('<span id="filter-rows-count">2</span>', $actual);
        $this->assertStringContainsString('name="pos" value="0"', $actual);
        $this->assertStringContainsString('name="sort_by" value="SCHEMA_NAME"', $actual);
        $this->assertStringContainsString('name="sort_order" value="asc"', $actual);
        $this->assertStringContainsString(__('Enable statistics'), $actual);
        $this->assertStringContainsString(__('No privileges to create databases'), $actual);
        $this->assertStringNotContainsString(__('Indexes'), $actual);

        $cfg['ShowCreateDb'] = true;
        $is_create_db_priv = true;

        $actual = $controller->index([
            'statistics' => '1',
            'pos' => null,
            'sort_by' => 'SCHEMA_TABLES',
            'sort_order' => 'desc',
        ]);

        $this->assertStringNotContainsString(__('Enable statistics'), $actual);
        $this->assertStringContainsString(__('Indexes'), $actual);
        $this->assertStringContainsString('name="sort_by" value="SCHEMA_TABLES"', $actual);
        $this->assertStringContainsString('name="sort_order" value="desc"', $actual);
        $this->assertStringContainsString('name="statistics" value="1"', $actual);
        $this->assertStringContainsString('title="3912174"', $actual);
        $this->assertStringContainsString('3,912,174', $actual);
        $this->assertStringContainsString('title="4358144"', $actual);
        $this->assertStringContainsString('4.2', $actual);
        $this->assertStringContainsString('MiB', $actual);
        $this->assertStringContainsString('name="db_collation"', $actual);
    }

    /**
     * @return void
     */
    public function testCreateDatabaseAction()
    {
        $dbi = $this->getMockBuilder(DatabaseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dbi->method('getError')
            ->willReturn('CreateDatabaseError');

        $response = Response::getInstance();
        $response->setAjax(true);

        $controller = new DatabasesController(
            $response,
            $dbi,
            new Template()
        );

        $actual = $controller->create([
            'new_db' => 'pma_test',
            'db_collation' => null,
        ]);

        $this->assertArrayHasKey('message', $actual);
        $this->assertInstanceOf(Message::class, $actual['message']);
        $this->assertStringContainsString('<div class="alert alert-danger" role="alert">', $actual['message']->getDisplay());
        $this->assertStringContainsString('CreateDatabaseError', $actual['message']->getDisplay());

        $dbi->method('tryQuery')
            ->willReturn(true);

        $actual = $controller->create([
            'new_db' => 'pma_test',
            'db_collation' => 'utf8_general_ci',
        ]);

        $this->assertArrayHasKey('message', $actual);
        $this->assertInstanceOf(Message::class, $actual['message']);
        $this->assertStringContainsString('<div class="alert alert-success" role="alert">', $actual['message']->getDisplay());
        $this->assertStringContainsString(
            sprintf(__('Database %1$s has been created.'), 'pma_test'),
            $actual['message']->getDisplay()
        );
    }

    /**
     * @return void
     */
    public function testDropDatabasesAction()
    {
        global $cfg;

        $dbi = $this->getMockBuilder(DatabaseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response = Response::getInstance();
        $response->setAjax(true);

        $cfg['AllowUserDropDatabase'] = true;

        $controller = new DatabasesController(
            $response,
            $dbi,
            new Template()
        );

        $actual = $controller->destroy([
            'drop_selected_dbs' => true,
            'selected_dbs' => null,
        ]);

        $this->assertArrayHasKey('message', $actual);
        $this->assertInstanceOf(Message::class, $actual['message']);
        $this->assertStringContainsString('<div class="alert alert-danger" role="alert">', $actual['message']->getDisplay());
        $this->assertStringContainsString(__('No databases selected.'), $actual['message']->getDisplay());
    }
}
