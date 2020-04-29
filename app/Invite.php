<?php
/**
 * Invite.php
 *
 * @package default
 */


namespace App;

use DB;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordSignature;
use Illuminate\Database\QueryException;

class Invite extends Model
{

    use RecordSignature;

    /**
     * fillable - attributes that can be mass-assigned
     */
    protected
        $fillable = [
        'id',
        'organization_id',
        'email',
        'name',
        'role',
        'token',
        'expires_at',
    ];

    /**
     * Get Grid/index data PAGINATED
     *
     * @param unknown $per_page
     * @param unknown $column
     * @param unknown $direction
     * @param string $keyword (optional)
     * @return mixed
     */
    static function filteredData(
        $per_page,
        $column,
        $direction,
        $keyword = '')
    {
        return self::buildBaseGridQuery($column, $direction, $keyword)
            ->paginate($per_page);
    }


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
            return false;
        } catch (QueryException $e) {
            info(__METHOD__ . ' line: ' . __LINE__ . ':  ' . $e->getMessage());
            return false;
        }


        return true;
    }


    /**
     * See if the invitation has expired
     *
     * @return bool
     */
    public function hasExpired()
    {
        return Carbon::now()->gte(Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['expires_at']));
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
     * @param unknown $columns (optional)
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
        }

        $query = Invite::select(['invites.*','organizations.id AS organization_id','organizations.alias AS organization_alias','roles.name AS role_name'],
            DB::raw("CASE WHEN expires_at < now() THEN 'Expired' ELSE '' END AS has_expired"))
            ->leftJoin('organizations', 'organizations.id', '=', 'invites.organization_id')
            ->leftJoin('roles', 'roles.id', '=', 'invites.role')
            ->orderBy($column, $direction);

        if ($keyword) {
            $query->where('invites.name', 'like', '%' . $keyword . '%');
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
    static public function getOptions(
        $flat = false)
    {

        $thisModel = new static;

        $records = $thisModel::select('id',
            'name')
            ->orderBy('name')
            ->get();

        if (!$flat) {
            return $records;
        } else {
            $data = [];

            foreach ($records as $rec) {
                $data[] = ['id' => $rec['id'], 'name' => $rec['name']];
            }

            return $data;
        }

    }


    /**
     *
     * @return unknown
     */
    public function canDelete()
    {
        return true;
    }
}
