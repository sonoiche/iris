<?php

namespace App\Http\Controllers\Fortify;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Http\Controllers\Controller;
use Laravel\Fortify\Contracts\ConfirmPasswordViewResponse;

class ConfirmablePasswordController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Show the confirm password view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Fortify\Contracts\ConfirmPasswordViewResponse
     */
    public function show(Request $request)
    {
        return app(ConfirmPasswordViewResponse::class);
    }

    /**
     * Confirm the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function store(Request $request)
    {
        $user_id = $request['user_id'];
        $user    = User::find($user_id);

        if ((Hash::check($request['password'], $user->password))) {
            $data['qrcode'] = $user->twoFactorQrCodeSvg();

            $user->two_factor_confirmed_at = Carbon::now();
            $user->two_factor_until = Carbon::now();
            $user->save();

            $data['user'] = $user;
            $data['message'] = 'Two Factor Authentication has been setup.';

            return response()->json($data);
        }

        $data['status'] = 422;
        $data['message'] = 'Current password is incorrect.';
        return response()->json($data);
    }
}
