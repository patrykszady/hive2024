<?php

namespace App\Jobs;

use App\Mail\VendorPaymentMade;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

class SendVendorPaymentEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $auth_user;
    protected $vendor;
    protected $check;

    public function __construct($auth_user, $vendor, $check)
    {
        $this->auth_user = $auth_user;
        $this->vendor = $vendor;
        $this->check = $check;
    }

    /**
     * Execute the job.
     */

    public function handle(): void
    {
        if(env('APP_ENV') == 'production'){
            Mail::to($this->vendor->business_email)
                ->cc([$this->auth_user->vendor->business_email])
                ->send(new VendorPaymentMade($this->vendor, $this->auth_user->vendor, $this->check));
        }
    }
}
