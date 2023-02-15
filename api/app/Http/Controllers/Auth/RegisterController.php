<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\SendPasswordResetEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Resources\Client\UserResource;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
                'fname' => ['required', 'string', 'max:255'],
                'lname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/']
            ],
            [
                'fname.required' => 'The first name field is required.',
                'lname.required' => 'The last name field is required.',
                'password.regex' => 'The password must contains uppercase, lowercase, numbers and symbols'
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $token          = $data['invite_token'];
        $user           = User::where('invite_token', $token)->first();
        $user->fname    = $data['fname'];
        $user->lname    = $data['lname'];
        $user->password =  Hash::make($data['password']);
        $user->status   = 'Active';
        $user->invite_token = NULL;
        $user->save();

        return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($data['user'] = $user = $this->create($request->all())));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        $data['token'] = $token = $user->createToken($user->name)->accessToken;
        User::where('id', $user->id)->update(['access_token' => $token]);
        $data['status'] = 200;

        return response()->json($data);
    }

    public function checkPassword(Request $request)
    {
        $string = $request['password'];
        $data['hasEightChars'] = (strlen($string) >= 8) ? true : false;
        $data['hasLetters']    = (preg_match("/[a-z]/i", $string)) ? true : false;
        $data['hasNumbers']    = (preg_match('~[0-9]+~', $string)) ? true : false;
        $data['hasSymbols']    = (preg_match('/[\'^£$%&*()}{!@#~?><>,|=_+¬-]/', $string)) ? true : false;

        return response()->json($data);
    }

    public function checkInviteToken(Request $request)
    {
        $token = $request['invite_token'];
        $user = User::where('invite_token', $token)->first();

        return new UserResource($user);
    }

    public function sendPasswordLink(Request $request)
    {
        $email = $request['email'];
        $user  = User::where('email', $email)->first();
        if(isset($user->id)) {
            $user->invite_token = Str::random(50);
            $user->save();

            // send email
            Mail::to($user->email)->send(new SendPasswordResetEmail($user));

            $data['message'] = 'Password reset link has been sent to your email.';
            return response()->json($data);
        }
    }

    public function passwordReset($code)
    {
        $data['user']  = User::where('invite_token', $code)->first();
        return response()->json($data);
    }

    public function changePassword(PasswordResetRequest $request)
    {
        $code = $request['invite_token'];
        $user = User::where('invite_token', $code)->first();
        $user->password                  = bcrypt($request['password']);
        $user->invite_token              = null;
        $user->two_factor_secret         = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at   = null;
        $user->two_factor_until          = null;
        $user->sms_authentication        = null;
        $user->sms_auth_number           = null;
        $user->sms_code                  = null;
        $user->save();

        $data['message'] = 'Password has been reset.';
        return response()->json($data);
    }
}
