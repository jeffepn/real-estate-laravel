<?php

namespace Jeffpereira\RealEstate\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'realestatelaravel:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install realestatelaravel package dependencies';

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
        $this->info('Initing instalation realestatelaravel...');

        $this->callSilent('vendor:publish', ['--force' => true, '--tag' => 'realestatelaravel-assets',]);
        $this->callSilent('vendor:publish', ['--force' => true, '--tag' => 'realestatelaravel-config']);

        $this->info('');
        $this->info('realestatelaravel was installed successfully.');
    }
}
