<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Models\Client\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, 
            [
                'email' => ['required'],
                'password' => ['required'],
            ]
        );
    }

    public function login(Request $request)
    {
        $this->validator($request->all())->validate();
        $datetimenow = Carbon::now()->addDay()->format('Y-m-d h:i:s');
        $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $request['email'])->first();
        if(!$user || !Hash::check($request['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credential is incorrect.']
            ]);
        }

        $oauth = DB::table('oauth_access_tokens')->where('user_id', $user->id)
            ->where('expires_at', '>', $datetimenow)
            ->first();

        if(!empty($oauth)){
            if($user->access_token) {
                $data['token'] = $user->access_token;
            } else {
                $data['token'] = $token = $user->createToken($user->name)->accessToken;
                User::where('id', $user->id)->update(['access_token' => $token]);
            }
        } else {
            $data['token'] = $token = $user->createToken($user->name)->accessToken;
            User::where('id', $user->id)->update(['access_token' => $token]);
        }

        if($user->two_factor_secret) {
            $data['qrcode'] = $user->twoFactorQrCodeSvg();
            $data['code']   = '';
            foreach (json_decode(decrypt($user->two_factor_recovery_codes, true)) as $code) {
                $data['code'] = trim($code);
                break;
            }
        }

        // if($user->sms_authentication) {
        //     $user->sms_code = $this->generateOtp();
        //     $user->save();

        //     // send OTP
        //     $phone_number = (string) PhoneNumber::make($user->sms_auth_number)->ofCountry('PH');
        //     $otp_password = $user->sms_code;

        //     $sid    = "ACc27ac3a6b54f5abf0471c2b1c5ff4376"; 
        //     $token  = "47cabe340916bca8d41f764897fbc9e7"; 
        //     $twilio = new Client($sid, $token); 
            
        //     $twilio->messages->create($phone_number,
        //         [
        //             "messagingServiceSid" => "MG2e9435f1a0ed02995b56aa45c6247e82",      
        //             "body" => "IRIS Online one time password. Your OTP is ".$otp_password.". To continue logging in to your account, input this 6 digit code." 
        //         ] 
        //     ); 
        // }

        $timenow = Carbon::now();
        if(isset($user->two_factor_until) && $timenow > $user->two_factor_until) {
            $user->two_factor_until = null;
            $user->save();
        }

        return $user;

        // // insert to activity log
        // $activity = new ActivityLog;
        // $activity->user_id          = $user->id;
        // $activity->username         = $user->fname.' '.$user->lname;
        // $activity->activity_type    = 'access';
        // $activity->ip_address       = $this->getIp();
        // $activity->save();

        // $data['user'] = $user;
        // $data['two_factor_until'] = $user->two_factor_until;

        // return response()->json($data);
    }

    public function logout(Request $request)
    {
        $timenow = Carbon::now();
        $user_id = $request['user_id'];
        $user = User::find($user_id);
        if(isset($user->two_factor_until) && $timenow > $user->two_factor_until) {
            $user->two_factor_until = null;
            $user->save();
        }

        $data['status'] = 200;
        return response()->json($data);
    }

    private function generateOtp()
    {
        return random_int(100000, 999999);
    }

    private function getIp(){
        $ip = file_get_contents('https://api.ipify.org');
        if($ip) {
            return $ip;
        }

        return '';
    }
}
