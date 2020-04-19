<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\InitialPermissions;


class SetInitialPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c4kc:set-initial-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create initial permissions and roles, asign to firt user';

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
        $perms = new InitialPermissions();

    }
}