<?php

namespace App\Console\Commands;

use App\Sample;
use App\Employee;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Notifications\SampleDueDate;

class NotifyDueDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:dueDate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->notifySampleIn(0);
        $this->notifySampleIn(1);
        $this->notifySampleIn(3);
    }

    private function getSamples()
    {
        $samples = Sample::where('dueDate', '>=', Carbon::now()->format('Y-m-d H:i'))->get();

        return $samples;
    }

    private function notifySampleIn($days)
    {
        if(count($this->getSamples()) > 0) {
            foreach ($this->getSamples() as $sample) {
                $users = Employee::whereIn('userType', ['secretary', 'administrator', 'analyst'])->get();
                if(Carbon::parse($sample->dueDate)->subDays($days)->format('Y-m-d H:i') == Carbon::now()->format('Y-m-d H:i')) {
                    \Notification::send($users, new SampleDueDate($sample, $days));
                }
            }
        }
    }
}
