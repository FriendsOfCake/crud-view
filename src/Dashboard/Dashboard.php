<?php
declare(strict_types=1);

namespace CrudView\Dashboard;

use Cake\View\Cell;
use InvalidArgumentException;
use function Cake\I18n\__d;

class Dashboard
{
    /**
     * Supported number of columns mapped to the corresponding Bootstrap grid column width.
     *
     * @var array<int, int>
     */
    private const COLUMN_MAP = [
        1 => 12,
        2 => 6,
        3 => 4,
        4 => 3,
        6 => 2,
        12 => 1,
    ];

    /**
     * The name of the dashboard.
     *
     * @var string
     */
    public string $title;

    /**
     * Modules added to each column, keyed by column number.
     *
     * @var array<int, array<int, \Cake\View\Cell>>
     */
    public array $children = [];

    /**
     * Bootstrap grid class for the columns, e.g. "col-md-6".
     *
     * @var string
     */
    public string $columnClass;

    // PHPCS does not yet support property hooks (https://github.com/PHPCSStandards/PHP_CodeSniffer/issues/734),
    // so the following sniffs emit false positives for hooked properties.
    // phpcs:disable PSR2.Classes.PropertyDeclaration, Squiz.Scope.MemberVarScope, Generic.WhiteSpace.ScopeIndent
    /**
     * Number of columns in the dashboard.
     *
     * @var int
     */
    public int $columns {
        set(int $value) {
            if (!isset(self::COLUMN_MAP[$value])) {
                throw new InvalidArgumentException('Valid columns value must be one of [1, 2, 3, 4, 6, 12]');
            }

            $this->columnClass = sprintf('col-md-%d', self::COLUMN_MAP[$value]);

            $this->columns = $value;
        }
    }

    // phpcs:enable

    /**
     * Constructor
     *
     * @param string $title The name of the group
     * @param int $columns Number of columns
     */
    public function __construct(?string $title = null, int $columns = 1)
    {
        if ($title === null) {
            $title = __d('CrudView', 'Dashboard');
        }

        $this->title = $title;
        $this->columns = $columns;
    }

    /**
     * Returns the children from a given column
     *
     * @param int $column a column number
     * @return array
     */
    public function getColumnChildren(int $column): array
    {
        return $this->children[$column] ?? [];
    }

    /**
     * Adds a Cell to a given column
     *
     * @param \Cake\View\Cell $module instance of Cell
     * @param int $column a column number
     * @return $this
     */
    public function addToColumn(Cell $module, int $column = 1): static
    {
        $this->children[$column][] = $module;

        return $this;
    }
}
