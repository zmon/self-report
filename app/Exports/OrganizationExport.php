<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Class OrganizationExport - Export to Excel Spreadsheet
 */
class OrganizationExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $dataQuery = null;

    public function __construct($dataQuery)
    {
        $this->dataQuery = $dataQuery;
    }

    public function query()
    {
        return $this->dataQuery;
    }

    ///////////////////////////////////////////////////////////////////////////

    // Add a line of column headings to the top
    public function headings(): array
    {
        return [
                        'id',
                        'name',
                        'alias',
                        'url_code',
                        'contact_name',
                        'title',
                        'phone_1',
                        'email',
                        'notes',
                        'active',
                    ];
    }

    // Map/format each field that's being exported
    // NOTE: to use raw values from SELECT (without having to manually specify
    // each column), comment out this function/"WithMapping" above
    public function map($organization): array
    {
        return [

                        $organization->id,
                        $organization->name,
                        $organization->alias,
                        $organization->url_code,
                        $organization->contact_name,
                        $organization->title,
                        $organization->phone_1,
                        $organization->email,
                        $organization->notes,
                        $organization->active,
                    ];
    }
}
