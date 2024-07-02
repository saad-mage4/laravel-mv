<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheRemoveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cache, routes, config clear, and Redis cache clear';

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
     * @return int
     */
    public function handle()
    {
        $this->call('cache:clear');
        $this->call('route:clear');
        $this->call('config:clear');
        $this->call('view:clear');
        // Redis::flushall();
        Cache::flush();

        $this->info('Redis cache cleared successfully.');
    }
}
