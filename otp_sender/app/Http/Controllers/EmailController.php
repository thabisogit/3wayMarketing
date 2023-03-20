<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpEmail; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class EmailController extends Controller
{
    public function can_request_otp($user_id)
    {
        $otp_requests = Cache::get('otp_requests_'.$user_id, []);
        $current_time = Carbon::now();

        // Remove any requests from the previous hour
        $otp_requests = array_filter($otp_requests, function ($request_time) use ($current_time) {
            return $request_time->addHour()->isFuture();
        });

        if (count($otp_requests) >= env('MAX_REQUEST')) {
            // The user has exceeded the maximum number of OTP requests for the hour.
            return false;
        } else {
            // The user has not exceeded the maximum number of OTP requests for the hour.
            // Add the current request time to the list of OTP requests and store it in the cache.
            $otp_requests[] = $current_time;
            Cache::put('otp_requests_'.$user_id, $otp_requests, 60); // Cache the OTP requests for 1 hour
            return true;
        }
    }



    
    public function sendEmail(Request $request)
    {
        $user_identity = $request->email;
        if($this->can_request_otp($user_identity)){
            $otp = $this->generate_otp($user_identity);

            $data = [
                'name' => 'Thabiso',
                'email' => 'thabiso@example.com',
                'message' => 'Hello, Your OTP is '.$otp
            ];
    
            Mail::to('recipient@example.com')->send(new OtpEmail($data));
    
            return 'Email sent successfully!';
        }
        return 'Exceeded daily limit requests!';
    }


    public function generate_otp($user_id)
        {
            $last_otp_time = Cache::get('last_otp_time_'.$user_id);

            if ($last_otp_time && $last_otp_time->addDay()->isFuture()) {
                // A valid OTP exists for this user within the last 24 hours.
                // Generate a new one and store it.
                $otp = sprintf("%06d", mt_rand(1, 999999));
                Cache::put('last_otp_'.$user_id, $otp, 1440); // Cache the OTP for 24 hours
                
                Cache::put('otp_'.$user_id, $otp, env('OTP_EXP_SEC')); // Cache the OTP for 30 seconds
                Cache::put('otp_time_'.$user_id, Carbon::now(), env('OTP_EXP_SEC')); // Cache the current time for 30 seconds

            } else {
                // No valid OTP exists for this user within the last 24 hours.
                // Generate a new one and store it.
                $otp = sprintf("%06d", mt_rand(1, 999999));
                Cache::put('last_otp_'.$user_id, $otp, 1440); // Cache the OTP for 24 hours
                Cache::put('last_otp_time_'.$user_id, Carbon::now(), 1440); // Cache the current time for 24 hours
                
                Cache::put('otp_'.$user_id, $otp, env('OTP_EXP_SEC')); // Cache the OTP for 30 seconds
                Cache::put('otp_time_'.$user_id, Carbon::now(), env('OTP_EXP_SEC')); // Cache the current time for 30 seconds

            }

            return $otp;
        }

        public function verify_otp(Request $request)
        {
            // dd($request->otp);
            // dd($request->email);
            $user_id = $request->email; 
            $otp = $request->otp;
            $cached_otp = Cache::get('otp_'.$user_id);
            $otp_time = Cache::get('otp_time_'.$user_id);
            $used_otp_key = 'used_otp_'.$user_id.'_'.$otp;

            if ($cached_otp && $cached_otp === $otp && $otp_time && $otp_time->addSeconds(30)->isFuture()) {
                // The OTP is valid and has not expired.
                if (Cache::has($used_otp_key)) {
                    // The OTP has already been used.
                    return 'OTP has already beed used';
                } else {
                    // Mark the OTP as used and clear the OTP and its generation time from the cache.
                    Cache::put($used_otp_key, true, 3600); // Cache the used OTP for 1 hour
                    Cache::forget('otp_'.$user_id);
                    Cache::forget('otp_time_'.$user_id);
                    return 'OTP Verified';
                }
            } else {
                // The OTP is invalid or has expired.
                return 'The OTP is invalid or has expired';
            }
        }
}
