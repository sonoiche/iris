<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\Role;
use App\Models\Client\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\RoleRequest;
use App\Http\Resources\Client\RoleResource;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $agency_id = $request['agency_id'];
        $roles = Role::with(['permissions','users'])
            ->where(function ($query) use ($agency_id) {
                $query->orWhere('agency_id', $agency_id)
                    ->orWhere('agency_id', 0);
            })
            ->get();
        return RoleResource::collection($roles);
    }

    public function getPermissions(Request $request)
    {
        $role_id = $request['role_id'];
        if($role_id) {
            $data['data'] = Permission::where('role_id', $role_id)->get();
        } else {
            $data['data'] = config('iris.permissions');
        }
        return response()->json($data);
    }

    public function store(RoleRequest $request)
    {
        $role            = new Role;
        $role->agency_id = $request['agency_id'];
        $role->name      = $request['name'];
        $role->save();

        $permissions = json_decode($request['permissions']);
        foreach ($permissions as $item) {
            foreach ($item->items as $result) {
                $permission              = new Permission;
                $permission->role_id     = $role->id;
                $permission->name        = $result->name;
                $permission->read        = ($result->read == true) ? 1 : 0;
                $permission->write       = ($result->write == true) ? 1 : 0;
                $permission->delete      = ($result->delete == true) ? 1 : 0;
                $permission->can_read    = ($result->can_read == true) ? 1 : 0;
                $permission->can_write   = ($result->can_write == true) ? 1 : 0;
                $permission->can_delete  = ($result->can_delete == true) ? 1 : 0;
                $permission->save();
            }
        }

        $data['message'] = 'Role has been added';
        return response()->json($data);
    }

    public function update(RoleRequest $request, $id)
    {
        $role            = Role::find($id);
        $role->name      = $request['name'];
        $role->save();

        $data['message'] = 'Role has been updated.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new RoleResource(Role::with(['permissions','users'])->find($id));
    }

    public function updatePermissions(Request $request)
    {
        $role_id = $request['role_id'];
        $type = $request['type'];

        if($type == true) {
            Permission::where('role_id', $role_id)
                ->update([
                    'can_read'    => 1,
                    'can_write'   => 1,
                    'can_delete'  => 1
                ]);
        } else {
            Permission::where('role_id', $role_id)
                ->update([
                    'can_read'    => 0,
                    'can_write'   => 0,
                    'can_delete'  => 0
                ]);
        }

        $data['message'] = 'Permission has been updated.';
        return response()->json($data);
    }

    public function updatePermission(Request $request)
    {
        $id     = $request['id'];
        $type   = $request['type'];
        $event  = $request['event'];

        $permission = Permission::find($id);
        if($type == 'can_read') {
            $permission->can_read = ($event == true) ? 1 : 0;
        }

        if($type == 'can_write') {
            $permission->can_write = ($event == true) ? 1 : 0;
        }

        if($type == 'can_delete') {
            $permission->can_delete = ($event == true) ? 1 : 0;
        }

        $permission->save();

        $data['message'] = 'Permission has been updated.';
        return response()->json($data);
    }
}
