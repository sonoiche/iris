<?php

namespace App\Http\Controllers\Fortify;

use Carbon\Carbon;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class TwoFactorAuthenticationController extends Controller
{
    public function store(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $user_id = $request['user_id'];
        $user    = User::find($user_id);
        $enable($user);

        $user->two_factor_confirmed_at = Carbon::now();
        $user->two_factor_until        = Carbon::now();
        $user->sms_authentication      = 0;
        $user->save();

        $data['code'] = '';
        foreach (json_decode(decrypt($user->two_factor_recovery_codes, true)) as $code) {
            $data['code'] = trim($code);
            break;
        }

        $data['user']    = $user;
        $data['qrcode']  = $user->twoFactorQrCodeSvg();
        $data['message'] = 'Two Factor Authentication is Enabled';

        return response()->json($data);
    }

    public function disable(Request $request)
    {
        $user_id = $request['user_id'];
        $user    = User::find($user_id);
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->two_factor_until = null;
        $user->save();

        $data['message'] = 'Two Factor Authentication is Disabled';
        $data['user']    = $user;
        return response()->json($data);
    }
    
    public function challenge(TwoFactorLoginRequest $request)
    {
        
        $this->validator($request->all())->validate();

        $user_id = $request['user_id'];
        $user    = User::find($user_id);
        $type    = $request['type'];
        
        switch ($type) {
            case 'recovery_code':
                
                $code = $request['code'];
        
                $authenticated = '';
                foreach (json_decode(decrypt($user->two_factor_recovery_codes, true)) as $recovery_code) {
                    if(hash_equals($recovery_code, $code)) {
                        $authenticated = 1;
                        break;
                    }
                }

                if($authenticated) {
                    $user->two_factor_until = Carbon::now()->addHours(2);
                    $user->save();

                    $data['authenticated'] = $authenticated;
                    $data['user'] = $user;
                    return response()->json($data);
                }

                break;

            case 'sms_code':
                
                $code = $request['code'];
                if($code == $user->sms_code) {
                    $user->two_factor_until = Carbon::now()->addHours(2);
                    $user->sms_code = null;
                    $user->save();

                    $data['authenticated'] = 1;
                    $data['user'] = $user;
                    return response()->json($data);
                }

                break;
            
            default:
                
                $authenticated = tap(
                    app(TwoFactorAuthenticationProvider::class)->verify(
                        decrypt($user->two_factor_secret), $request['code']
                    )
                );
        
                if($authenticated->target) {
                    $data['qrcode'] = $user->twoFactorQrCodeSvg();
                    $user->two_factor_until = Carbon::now()->addHours(2);
                    $user->save();
        
                    $data['authenticated'] = $authenticated->target;
                    $data['user'] = $user;
                    return response()->json($data);
                }

                break;
        }
        
        $data['errors']['code'] = ["Code is invalid."];
        return response()->json($data);
    }

    public function enableSms(Request $request)
    {
        $user_id = $request['user_id'];
        $sms_num = $request['sms_auth_number'];
        $user    = User::find($user_id);
        $user->two_factor_secret         = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at   = null;
        $user->two_factor_until          = null;
        $user->sms_authentication        = 1;
        $user->sms_auth_number           = $sms_num;
        $user->save();

        $data['user']    = $user;
        $data['message'] = 'Two Factor SMS Authentication is Enabled';

        return response()->json($data);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, 
            [
                'code' => ['required'],
            ]
        );
    }
}
