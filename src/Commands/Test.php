<?php

namespace SiranixSociety\HMFramework\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hm:testcommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Just a simple test command';

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
        shell_exec('composer dump-autoload');
        $this->info('Working');
    }
}