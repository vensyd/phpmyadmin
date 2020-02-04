<?php
declare(strict_types=1);

namespace PhpMyAdmin\Controllers\Database;

use function json_encode;

/**
 * Table/Column autocomplete in SQL editors.
 */
class SqlAutoCompleteController extends AbstractController
{
    /**
     * @return array JSON
     */
    public function index(): array
    {
        global $cfg, $db, $sql_autocomplete;

        $sql_autocomplete = true;
        if ($cfg['EnableAutocompleteForTablesAndColumns']) {
            $db = $_POST['db'] ?? $db;
            $sql_autocomplete = [];
            if ($db) {
                $tableNames = $this->dbi->getTables($db);
                foreach ($tableNames as $tableName) {
                    $sql_autocomplete[$tableName] = $this->dbi->getColumns(
                        $db,
                        $tableName
                    );
                }
            }
        }
        return ['tables' => json_encode($sql_autocomplete)];
    }
}
