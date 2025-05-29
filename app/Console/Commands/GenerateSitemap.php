<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Post; // Import model Post của bạn

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml for the website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Start generating sitemap...');

        $sitemap = Sitemap::create()
            ->add(Url::create('https://www.sharecycle.online/')
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            ->add(Url::create('https://www.sharecycle.online/about')
                ->setPriority(0.8)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));


        // Ghi file sitemap.xml vào thư mục public
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully at /public/sitemap.xml');
    }
}
