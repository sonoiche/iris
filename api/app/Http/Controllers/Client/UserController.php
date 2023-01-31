<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Rules\PasswordCheck;
use Illuminate\Http\Request;
use App\Models\Client\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\Client\SendInviteEmail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\Client\SendInviteEmailJob;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Client\UserRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Client\UserResource;
use App\Http\Requests\Client\CreateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::orderBy('fname')->get());
    }

    public function store(CreateUserRequest $request)
    {
        $token              = Str::random(50);
        $user_id            = $request['user_id'];
        $user               = new User;
        $user->fname        = $request['fname'];
        $user->mname        = $request['mname'];
        $user->lname        = $request['lname'];
        $user->email        = $request['email'];
        $user->role_id      = $request['role_id'];
        $user->invite_token = $token;
        $user->save();

        $remarks            = $request['remarks'];
        // send email invitation
        $authuser = User::find($user_id);
        // SendInviteEmailJob::dispatch($user, $authuser, $remarks)->delay(Carbon::now()->addSeconds(5));
        Mail::to($user->email)->send(new SendInviteEmail($user, $authuser, $remarks));

        $data['message']    = 'Email has been sent to '.$user->fname.' '.$user->lname;

        return response()->json($data);
    }

    public function updateEmail(Request $request, $id)
    {
        $this->emailValidator($request->all())->validate();
        $user               = User::find($id);
        $user->email        = $request['email'];
        $user->save();

        $data['message']    = 'Email has been updated';
        $data['user']       = $user;

        return response()->json($data);
    }

    public function updatePassword(Request $request, $id)
    {
        $this->passwordValidator($request->all())->validate();
        $user               = User::find($id);
        $user->password     = bcrypt($request['password']);
        $user->save();

        $data['message']    = 'Password has been updated';
        $data['user']       = $user;

        return response()->json($data);
    }

    public function updateBackup(Request $request, $id)
    {
        $user               = User::find($id);
        $user->auto_backup  = ($request['auto_backup'] == true) ? 1 : 0;
        $user->save();

        $data['message']    = 'Data has been updated';
        $data['user']       = $user;

        return response()->json($data);
    }

    public function update(UserRequest $request, $id)
    {
        $user            = User::find($id);
        $user->fname     = $request['fname'];
        $user->lname     = $request['lname'];
        $user->contact_number = $request['contact_number'];

        if($request['photo'] != '' && $request->has('photo')) {
            $file  = $request->file('photo');
            $image = time().'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'uploads/users/'.$user->id,
                $file,
                $image
            );

            $user->photo = 'uploads/users/'.$user->id.'/'.$image;
        }

        $user->save();

        $data['message'] = 'User account has been updated';
        $data['user']    = $user;

        return response()->json($data);
    }

    public function show($id)
    {
        $data['user'] = $user = User::find($id);
        $data['code'] = '';
        if($user->two_factor_secret) {
            foreach (json_decode(decrypt($user->two_factor_recovery_codes, true)) as $code) {
                $data['code'] = trim($code);
                break;
            }
        }

        return response()->json($data);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        $data['message'] = 'User has been deactivated.';
        return response()->json($data);
    }

    public function datatable(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'name',
            2   => 'role',
            3   => 'last_login',
            4   => 'two_step',
            5   => 'created_at'
        ];
        
        $search     = $request['search'];

        $result     = User::join('roles','roles.id','=','users.role_id')
            ->select(['users.*','roles.name as role_name'])
            ->when($search, function ($query, $search) {
                $query->where(function ($queryString) use ($search) {
                    $queryString->orWhere('fname', 'like', '%'.$search.'%')
                        ->orWhere('mname', 'like', '%'.$search.'%')
                        ->orWhere('lname', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            });

        $totalData = $result->count();

        $totalFiltered = $totalData; 

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];

        $users = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();
        
        $data = [];
        if(!empty($users)) {
            foreach ($users as $user) {
                $nestedData['name']         = ['fullname' => $user->fullname, 'email' => $user->email, 'photo' => $user->display_photo];
                $nestedData['role']         = $user->role_name;
                $nestedData['two_step']     = '';
                $nestedData['last_login']   = '';
                $nestedData['created_at']   = $user->created_at_display;
                $nestedData['action']       = $user->id;
                $data[]                     = $nestedData;
            }
        }

        $json_data = [
            "draw"            => intval($request['draw']),
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        ];

        return response()->json($json_data);
    }

    public function selectOption()
    {
        $data['data'] = [];
        $users = User::where('status', USer::STATUS_ACTIVE)->orderBy('fname')->get();
        foreach ($users as $user) {
            $data['data'][] = [
                'id'    => $user->id,
                'name'  => $user->fullname
            ];
        }
        return response()->json($data);
    }

    public function activityLogs(Request $request)
    {
        $user_id        = $request['user_id'];
        $limit          = $request['limit'] ?? 10;
        $data['data']   = ActivityLog::where('user_id', $user_id)->latest()->limit($limit)->get();

    return response()->json($data);
    }

    protected function emailValidator(array $data)
    {
        $id = $data['user_id'];
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.(($id) ? $id : null).',id'],
            'password' => ['required', 'string', new PasswordCheck($id)]
        ]);
    }

    protected function passwordValidator(array $data)
    {
        $id = $data['user_id'];
        return Validator::make($data, 
            [
                'password' => [
                    'required', 
                    'string', 
                    'min:8', 
                    'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/', 
                    'confirmed'
                ],
                'current_password' => ['required', new PasswordCheck($id)]
            ],
            [
                'password.regex'        => 'The password must contains uppercase letter, lowercase letter, numbers and symbol',
                'password.confirmed'    => 'The password did not match to confirm password.'
            ]
        );
    }
}
