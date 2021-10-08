<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotNullProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    public $presensi;
    public function __construct($item)
    {
        $this->presensi = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->presensi->jam_masuk == null){
            $this->presensi->update(['jam_masuk' => '00:00:00']);
        }
        
        if($this->presensi->jam_pulang == null){
            $this->presensi->update(['jam_pulang' => '00:00:00']);
        }
    }
}
