<?php

namespace App\Modules\MobileAppV2\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\MobileAppV2\Models\MobileAppV2;
class MobileAppV2Controller extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return MobileAppV2::login();
    }

    public function verify_otp()
    {
        return MobileAppV2::verify_otp();
    }

    public function resend_otp()
    {
        return MobileAppV2::resend_otp();
    }

    public function check_app_version()
    {
        return MobileAppV2::check_app_version();
    }

    public function scan_qr_code()
    {
        return MobileAppV2::scan_qr_code();
    }

    public function transact_voucher()
    {
        return MobileAppV2::transact_voucher();
    }

    
}
