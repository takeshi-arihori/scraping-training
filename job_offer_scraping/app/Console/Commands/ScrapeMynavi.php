<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrapeMynavi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:mynavi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Mynavi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $crawler = \Goutte::request('GET', 'https://duckduckgo.com/html/?q=Laravel');
        // $crawler->filter('.result__title .result__a')->each(function ($node) {
        //     dump($node->text());
        // });


        $url = 'https://tenshoku.mynavi.jp/list/pg3/';
        $crawler = \Goutte::request('GET', $url);
        $crawler->filter('.cassetteRecruit__copy > a')->each(function
        ($node)
        {
            $href = $node->attr('href');
            dump(substr($href, 0, strpos($href, '/', 1) + 1));
        });
    }
}
