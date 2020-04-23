<?php

namespace App\Console\Commands;

use App\Lib\Import\ImportAgreement;
use App\Lib\Import\ImportBusinessUnit;
use App\Lib\Import\ImportCareType;
use App\Lib\Import\ImportChangeLog;
use App\Lib\Import\ImportContact;
use App\Lib\Import\ImportContractContact;
use App\Lib\Import\ImportContractDocumentTask;
use App\Lib\Import\ImportContractParticipant;
use App\Lib\Import\ImportContractRole;
use App\Lib\Import\ImportCountyToRegion;
use App\Lib\Import\ImportDid;
use App\Lib\Import\ImportDidContact;
use App\Lib\Import\ImportDidLocation;
use App\Lib\Import\ImportDidPersonRelationship;
use App\Lib\Import\ImportEmail;
use App\Lib\Import\ImportEmailContact;
use App\Lib\Import\ImportEmailPeople;
use App\Lib\Import\ImportEmailPersonRelationship;
use App\Lib\Import\ImportFileUpload;
use App\Lib\Import\ImportLocation;
use App\Lib\Import\ImportManager;
use App\Lib\Import\ImportMedAssetsProgram;
use App\Lib\Import\ImportOrganizationMedAssetsProgram;
use App\Lib\Import\ImportOrganizationNote;
use App\Lib\Import\ImportOrganizationNoteType;
use App\Lib\Import\ImportOrganizationRelationshipType;
use App\Lib\Import\ImportOrganizationType;
use App\Lib\Import\ImportParticipant;
use App\Lib\Import\ImportPerson;
use App\Lib\Import\ImportPersonDid;
use App\Lib\Import\ImportPersonEmail;
use App\Lib\Import\ImportPersonRelationship;
use App\Lib\Import\ImportProgram;
use App\Lib\Import\ImportRegion;
use App\Lib\Import\ImportTypeOfDid;
use App\Lib\Import\ImportTypeOfEmail;
use Illuminate\Console\Command;
use App\Lib\Import\ImportOrganizations;

class ImportProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-production';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from legacy database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $Organizations = new ImportOrganizations();
//        $Organizations->import();


    }
}



//        $User = new \App\Lib\Import\ImportUser();
//        $User->import('proddb', 'users');