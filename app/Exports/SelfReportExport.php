<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Class SelfReportExport - Export to Excel Spreadsheet
 * @package App\Exports
 */
class SelfReportExport implements FromQuery, WithHeadings, WithMapping
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
            'exposed',
            'public_private_exposure',
            'state',
            'kscounty',
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
        ];
    }

    // Map/format each field that's being exported
    // NOTE: to use raw values from SELECT (without having to manually specify
    // each column), comment out this function/"WithMapping" above
    public function map($self_report): array
    {
        return [

            $self_report->id,
            $self_report->name,
            $self_report->exposed,
            $self_report->public_private_exposure,
            $self_report->state,
            $self_report->kscounty,
            $self_report->city_kcmo,
            $self_report->zipcode,
            $self_report->selfreport_or_other,
            $self_report->whose_symptoms,
            $self_report->sex,
            $self_report->age,
            $self_report->any_other_symptoms,
            $self_report->symptom_severity,
            $self_report->immunocompromised,
            $self_report->symptom_start_date,
            $self_report->followup_contact,
            $self_report->preferred_contact_method,
            $self_report->is_voicemail_ok,
            $self_report->crowded_setting,
            $self_report->anything_else,
            $self_report->FormVersionId,
            $self_report->FormId,
            $self_report->FormVersionNumber,
            $self_report->ExternalId,
            $self_report->ExternalStatus,
            $self_report->ExternalRespondentId,
            $self_report->SourceType,
            $self_report->SourceElementId,
            $self_report->DataConnectionId,
            $self_report->CallCounter,
        ];
    }
}
