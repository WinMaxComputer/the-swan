<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate multilingual sitemap files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = app('router')->dispatch(Request::create('/sitemap.xml', 'GET'));

        if ($response->getStatusCode() !== 200) {
            $this->error('Failed generating sitemap. HTTP status: '.$response->getStatusCode());

            return self::FAILURE;
        }

        $this->info('Sitemap generated successfully.');

        return self::SUCCESS;
    }
}
