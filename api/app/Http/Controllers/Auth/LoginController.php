<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
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
        $datetimenow = Carbon::now()->format('Y-m-d h:i:s');
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

        if($user->sms_authentication) {
            $user->sms_code = $this->generateOtp();
            $user->save();
        }

        $timenow = Carbon::now();
        if(isset($user->two_factor_until) && $timenow > $user->two_factor_until) {
            $user->two_factor_until = null;
            $user->save();
        }

        $data['user'] = $user;
        $data['two_factor_until'] = $user->two_factor_until;

        return response()->json($data);
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
}
