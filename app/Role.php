<?php
/**
 * Role.php
 *
 * @package default
 */


namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;


class Role extends \Spatie\Permission\Models\Role
{

    use RecordSignature;

    /**
     * fillable - attributes that can be mass-assigned
     */
    protected $fillable = [
        'id',
        'name',
        'can_assign',
        'guard_name',
        'deleted_at',
    ];

    protected $hidden = [
        'active',
        'created_by',
        'modified_by',
        'purged_by',
        'created_at',
        'updated_at',
    ];

    /**
     *
     * @param unknown $attributes
     * @return unknown
     */
    public function add($attributes)
    {

        try {
            $this->fill($attributes)->save();
        } catch (Exception $e) {
            info(__METHOD__ . ' line: ' . __LINE__ . ':  ' . $e->getMessage());
            throw new Exception($e->getMessage());
        } catch (QueryException $e) {
            info(__METHOD__ . ' line: ' . __LINE__ . ':  ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }


        return true;
    }


    /**
     *
     * @return unknown
     */
    public function canDelete()
    {
        return true;
    }


    /**
     * Get Grid/index data PAGINATED
     *
     * @param unknown $per_page
     * @param unknown $column
     * @param unknown $direction
     * @param string $keyword (optional)
     * @return mixed
     */
    static function indexData(
        $per_page,
        $column,
        $direction,
        $keyword = '')
    {
        return self::buildBaseGridQuery($column, $direction, $keyword,
            ['id',
                'name',
                'can_assign',
            ])
            ->paginate($per_page);
    }


    /**
     * Create base query to be used by Grid, Download, and PDF
     *
     * NOTE: to override the select you must supply all fields, ie you cannot add to the
     *       fields being selected.
     *
     * @param unknown $column
     * @param unknown $direction
     * @param string $keyword (optional)
     * @param string|array $columns
     * @return mixed
     */
    static function buildBaseGridQuery(
        $column,
        $direction,
        $keyword = '',
        $columns = '*')
    {
        // Map sort direction from 1/-1 integer to asc/desc sql keyword
        switch ($direction) {
            case '1':
                $direction = 'desc';
                break;
            case '-1':
                $direction = 'asc';
                break;
            default:
                $direction = 'asc';
                break;
        }

        $query = Role::select($columns)
            ->orderBy($column, $direction);

        if ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
        return $query;
    }


    /**
     * Get export/Excel/download data query to send to Excel download library
     *
     * @param unknown $column
     * @param unknown $direction
     * @param string $keyword (optional)
     * @param unknown $columns (optional)
     * @return mixed
     */
    static function exportDataQuery(
        $column,
        $direction,
        $keyword = '',
        $columns = '*')
    {

        info(__METHOD__ . ' line: ' . __LINE__ . " $column, $direction, $keyword");

        return self::buildBaseGridQuery($column, $direction, $keyword, $columns);

    }


    /**
     *
     * @param unknown $column
     * @param unknown $direction
     * @param unknown $keyword (optional)
     * @param unknown $columns (optional)
     * @return unknown
     */
    static function pdfDataQuery(
        $column,
        $direction,
        $keyword = '',
        $columns = '*')
    {

        info(__METHOD__ . ' line: ' . __LINE__ . " $column, $direction, $keyword");

        return self::buildBaseGridQuery($column, $direction, $keyword, $columns);

    }


    /**
     * Get "options" for HTML select tag
     *
     * If flat return an array.
     * Otherwise, return an array of records.  Helps keep in proper order durring ajax calls to Chrome
     *
     * @param unknown $flat (optional)
     * @return unknown
     */
    static public function getOptions($flat = false)
    {

        $thisModel = new static;

        $query = $thisModel::select('id',
            'name')
            ->orderBy('name');

        if (!Auth::user()->hasRole('super-admin')) {
            $query->where('can_assign', 1);
        }

        $records = $query->get();

        if (!$flat) {
            return $records;
        } else {
            $data = [];

            foreach ($records AS $rec) {
                $data[] = ['id' => $rec['id'], 'name' => $rec['name']];
            }

            return $data;
        }

    }


}
