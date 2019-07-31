<?php

namespace App\Jobs;

use App\Sample;
use App\Sample_Tests;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\SampleDueDate;
use Carbon\Carbon;

class ProcessNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sample, $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sample, $user) 
    {
        $this->sample = $sample;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user->notify(new SampleDueDate($sample));
    }
}
