<?php

namespace App\Jobs;

use App\Http\Controllers\UserController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\callback;

class async_function implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $data;
    public function __construct($data)
    { 
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $data = $this->data;
        sleep(2);
        echo 'job function';
        $data = new UserController();
        $response = $data->callback();
        
        echo '==>==>>>>>>>>>',  $response;
        Log::info('ingresa job mostrando' . $response);
    }
}
