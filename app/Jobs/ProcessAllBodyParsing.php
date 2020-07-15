<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAllBodyParsing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $pages = \App\Page::all();
        $max = count($pages) + 1;
        $cnt = 1;
        foreach($pages as $page){
            \App\PageMorpheme::insertBodyDecomposeWords($page);
            echo $cnt . '/' . $max . PHP_EOL;
            $cnt++;
        }
    }
}
