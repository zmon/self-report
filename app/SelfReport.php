<?php

namespace App;

use App\Traits\HistoryTrait;
use App\Traits\RecordSignature;
use DB;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class SelfReport extends Model
{
//    use SoftDeletes;
    use RecordSignature;
    use HistoryTrait;

    /**
     * fillable - attributes that can be mass-assigned
     */
    protected $fillable = [
            'id',
            'organization_id',
            'name',
            'exposed',
            'public_private_exposure',
            'state',
            'kscounty',
            'mocounty',
            'city_kcmo',
            'zipcode',
            'selfreport_or_other',
            'whose_symptoms',
            'sex',
            'age',
            'any_other_symptoms',
            'symptom_severity',
            'immunocompromised',
            'symptom_start_date',
            'followup_contact',
            'preferred_contact_method',
            'is_voicemail_ok',
            'crowded_setting',
            'anything_else',
            'FormVersionId',
            'FormId',
            'FormVersionNumber',
            'ExternalId',
            'ExternalStatus',
            'ExternalRespondentId',
            'SourceType',
            'SourceElementId',
            'DataConnectionId',
            'CallCounter',
            'county_calc',
            'form_received_at',
        ];

    protected $hidden = [
        'active',
        'created_by',
        'modified_by',
        'purged_by',
        'created_at',
        'updated_at',
    ];

    public function organizations()
    {
        return $this->belongsTo(\App\Organization::class);
    }

    public function preexisting_conditions()
    {
        return $this->belongsToMany(\App\PreexistingCondition::class);
    }

    public function race_ethnicities()
    {
        return $this->belongsToMany(\App\RaceEthnicity::class);
    }

    public function symptoms()
    {
        return $this->belongsToMany(\App\Symptom::class);
    }

    public function add($attributes)
    {
        try {
            $this->fill($attributes)->save();
        } catch (\Exception $e) {
            info(__METHOD__.' line: '.__LINE__.':  '.$e->getMessage());
            throw new \Exception($e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            info(__METHOD__.' line: '.__LINE__.':  '.$e->getMessage());
            throw new \Exception($e->getMessage());
        }

        return true;
    }

    public function canDelete()
    {
        return true;
    }

    public static function summaryData()
    {
        $query = self::select(
            'organizations.name AS organization_name',
            DB::raw('COUNT(*) AS cnt')

        )
            ->leftJoin('organizations', 'organizations.id', '=', 'self_reports.organization_id')
            ->groupBy('organizations.name')
            ->orderBy('organizations.name');

        $organization_id = \Auth::user()->organization_id;

        if ($organization_id) {
            $query->where('organizations.id', '=', $organization_id);
        }

        return $query->get();
    }

    /**
     * Get Grid/index data PAGINATED
     *
     * @param $per_page
     * @param $column
     * @param $direction
     * @param string $keyword
     * @return mixed
     */
    public static function indexData(
        $per_page,
        $column,
        $direction,
        $keyword = '')
    {
        return  self::buildBaseGridQuery($column, $direction, $keyword,
            ['self_reports.id',
                    'organizations.alias',
                    'organization_id',
                    'self_reports.name',
                    'state',
                    'zipcode',
                    'symptom_start_date',
                    'county_calc',
                    'form_received_at',
            ])->paginate($per_page);
    }

    /**
     * Create base query to be used by Grid, Download, and PDF
     *
     * NOTE: to override the select you must supply all fields, ie you cannot add to the
     *       fields being selected.
     *
     * @param $column
     * @param $direction
     * @param string $keyword
     * @param string|array $columns
     * @return mixed
     */
    public static function buildBaseGridQuery(
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

        $query = self::select($columns)
            ->leftJoin('organizations', 'self_reports.organization_id', '=', 'organizations.id')
        ->orderBy($column, $direction);

        if ($keyword) {
            $query->where('name', 'like', '%'.$keyword.'%');
        }

        $organization_id = \Auth::user()->organization_id;

        if ($organization_id) {
            $query->where('organizations.id', '=', $organization_id);
        }

        return $query;
    }

    /**
     * Get export/Excel/download data query to send to Excel download library
     *
     * @param $per_page
     * @param $column
     * @param $direction
     * @param string $keyword
     * @return mixed
     */
    public static function exportDataQuery(
        $column,
        $direction,
        $keyword = '',
        $columns = '*')
    {
        info(__METHOD__.' line: '.__LINE__." $column, $direction, $keyword");

        return self::buildBaseGridQuery($column, $direction, $keyword, $columns);
    }

    public static function pdfDataQuery(
            $column,
            $direction,
            $keyword = '',
            $columns = '*')
    {
        info(__METHOD__.' line: '.__LINE__." $column, $direction, $keyword");

        return self::buildBaseGridQuery($column, $direction, $keyword, $columns);
    }

    /**
     * Get "options" for HTML select tag
     *
     * If flat return an array.
     * Otherwise, return an array of records.  Helps keep in proper order durring ajax calls to Chrome
     */
    public static function getOptions($flat = false)
    {
        $thisModel = new static;

        $records = $thisModel::select('id',
            'name')
            ->orderBy('name')
            ->get();

        if (! $flat) {
            return $records;
        } else {
            $data = [];

            foreach ($records as $rec) {
                $data[] = ['id' => $rec['id'], 'name' => $rec['name']];
            }

            return $data;
        }
    }
}
