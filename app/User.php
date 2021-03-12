<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HistoryTrait;
use App\Traits\RecordSignature;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;

    use HasApiTokens, Notifiable, HasRoles;
    use RecordSignature;
    use HistoryTrait;

    /**
     * fillable - attributes that can be mass-assigned
     */
    protected $fillable = [
            'id',
            'organization_id',
            'name',
            'email',
            'active',
            'email_verified_at',
            'password',
            'remember_token',
        ];

    protected $hidden = [
        'created_by',
        'modified_by',
        'purged_by',
        'created_at',
        'updated_at',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(\App\Organization::class);
    }

    public function add($attributes)
    {
        try {
            $this->fill($attributes);

            // Hash the pw
            $this->password = bcrypt($this->password);

            $this->save();
            $this->syncRoles($selected_roles);
        } catch (Exception $e) {
            info(__METHOD__.' line: '.__LINE__.':  '.$e->getMessage());
            throw new Exception($e->getMessage());
        } catch (QueryException $e) {
            info(__METHOD__.' line: '.__LINE__.':  '.$e->getMessage());
            throw new Exception($e->getMessage());
        }

        return true;
    }

    public function canDelete()
    {
        return true;
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
        return self::buildBaseGridQuery($column, $direction, $keyword,
            ['id',
                'name',
                'email',
                'active',
            ])
            ->paginate($per_page);
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

        $query = self::select(['users.*', 'organizations.id AS organization_id', 'organizations.alias AS organization_alias'])
            ->leftJoin('organizations', 'organizations.id', '=', 'users.organization_id')
        ->orderBy($column, $direction);

        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%'.$keyword.'%')
                    ->orWhere('email', 'like', '%'.$keyword.'%');
            });
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

    /**
     * Get "options" for HTML select tag
     *
     * If flat return an array.
     * Otherwise, return an array of records.  Helps keep in proper order durring ajax calls to Chrome
     */
    public static function getRoleOptions($flat = false)
    {
        $thisModel = new static;

        $query = Role::select('id',
            'name')
            ->orderBy('name');

        if (! Auth::user()->hasRole('super-admin')) {
            $query->where('can_assign', 1);
        }

        $records = $query->get();

        if (! $flat) {
            $data = [];

            foreach ($records as $rec) {
                $data[] = ['id' => $rec['name'], 'name' => $rec['name']];
            }

            return $data;
        } else {
            $data = [];

            foreach ($records as $rec) {
                $data[] = ['id' => $rec['name'], 'name' => $rec['name']];
            }

            return $data;
        }
    }

    public function areRolesDirty($current_roles, $new_roles)
    {
        $roles_is_dirty = false;
        $old_roles = [];
        foreach ($current_roles as $user_role) {
            $old_roles[] = $user_role->name;
        }
        sort($new_roles);
        sort($old_roles);
        if ($new_roles != $old_roles) {
            $roles_is_dirty = true;
        }

        return $roles_is_dirty;
    }
}
