<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class RolesPermissionController extends Controller
{
    use Upload;

    public function roleList()
    {
        $data['roles'] = Role::orderBy('id', 'asc')->get();
        return view('admin.role_permission.roleList', $data);
    }

    public function roleSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];

        $filterRole = $request->filterRole;
        $filterStatus = $request->filterStatus;

        $roles = Role::when(!empty($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        })
            ->when(!empty($filterRole), function ($query) use ($filterRole) {
                $query->where('name', 'LIKE', "%{$filterRole}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->orderBy('id', 'ASC');


        return DataTables::of($roles)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('name', function ($item) {
                return $item->name;
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                  </span>';

                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('In Active') . '
                  </span>';
                }
            })
            ->addColumn('action', function ($item) {

                $updateRoute = route('admin.editRole', $item->id);
                $deleteRoute = route('admin.deleteRole', $item->id);

                $update = '';
                $delete = '';
                if (adminAccessRoute(array_merge(config('permissionList.Roles_And_Permission.Available_Roles.permission.edit'), config('permissionList.Roles_And_Permission.Available_Roles.permission.delete')))) {
                    if (adminAccessRoute(config('permissionList.Roles_And_Permission.Available_Roles.permission.edit'))) {
                        $update = '<a href="' . $updateRoute . '"
                       class="btn btn-white btn-sm">
                        <i class="fal fa-edit me-1"></i> ' . trans("Update") . '
                      </a>';
                    }

                    if (adminAccessRoute(config('permissionList.Roles_And_Permission.Available_Roles.permission.delete'))) {
                        $delete = '<div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                        <a class="dropdown-item deleteRole" href="javascript:void(0)"
                           data-route="' . $deleteRoute . '"
                           data-bs-toggle="modal" data-bs-target="#deleteRoleModal">
                            <i class="bi bi-box-arrow-in-right dropdown-item-icon"></i>
                           ' . trans("Delete") . '
                        </a>
                      </div>
                    </div>';
                    }
                } else {
                    $update = '-';
                }

                return '<div class="btn-group" role="group">
                      ' . $update . '
                      ' . $delete . '
                  </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);

    }

    public function createRole()
    {
        return view('admin.role_permission.createRole');
    }

    public function roleStore(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required'],
        ];
        $message = [
            'name.required' => 'Role name field must be required',
            'permissions.required' => 'At least one menu permission is required',
        ];
        $this->validate($request, $rules, $message);

        DB::beginTransaction();
        try {
            $role = new Role();
            $role->created_by = auth()->id();
            $role->updated_by = auth()->id();
            $role->name = $request->name;
            $role->permission = (isset($request->permissions)) ? explode(',', join(',', $request->permissions)) : [];
            $role->status = $request->status;
            $role->save();
            DB::commit();
            return back()->with('success', 'New role created successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->with('error', $exception->getMessage());
        }
    }

    public function editRole($id)
    {
        $data['singleRole'] = Role::findOrFail($id);
        return view('admin.role_permission.editRole', $data);
    }

    public function roleUpdate(Request $request, $id)
    {
        $rules = [
            'name' => ['required'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required'],
        ];

        $message = [
            'name.required' => 'Role name field must be required',
            'permissions.required' => 'At least one menu permission is required',
        ];

        $this->validate($request, $rules, $message);

        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);
            $role->created_by = auth()->id();
            $role->updated_by = auth()->id();
            $role->name = $request->name;
            $role->permission = (isset($request->permissions)) ? explode(',', join(',', $request->permissions)) : [];
            $role->status = $request->status;
            $role->save();
            DB::commit();
            return back()->with('success', 'Role Updated Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteRole($id)
    {
        $role = Role::with(['roleUsers'])->find($id);
        if (count($role->roleUsers) > 0) {
            return back()->with('alert', 'This role has many users');
        }
        $role->delete();
        return back()->with('success', 'Role Deleted Successfully');
    }

    public function staffList()
    {
        $authenticateUser = Auth::guard('admin')->user();
        $records = Admin::with('role')
            ->when(!isset($authenticateUser->branch) && $authenticateUser->role_id == null, function ($query) {
                return $query->whereNotNull('role_id');
            })
            ->when(isset($authenticateUser->branch) && $authenticateUser->role_id != null, function ($q) use ($authenticateUser) {
                return $q->whereHas('branch', function ($q) use ($authenticateUser) {
                    $q->where('branch_id', optional($authenticateUser->branch)->branch_id);
                })->orWhereHas('driver', function ($q) use ($authenticateUser) {
                    $q->where('branch_id', optional($authenticateUser->branch)->branch_id);
                });
            })
            ->selectRaw('COUNT(id) AS totalStaffs')
            ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS activeStaffs')
            ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END) / COUNT(id)) * 100 AS activeStaffPercentage')
            ->selectRaw('COUNT(CASE WHEN status = 0 THEN id END) AS inActiveStaffs')
            ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END) / COUNT(id)) * 100 AS inActiveStaffPercentage')
            ->get()
            ->toArray();

        $data['staffRecords'] = collect($records)->collapse();
        return view('admin.role_permission.staffList', $data);
    }

    public function staffSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];
        $authenticateUser = Auth::guard('admin')->user();

        $filterUser = $request->filterUser;
        $filterRole = $request->filterRole;
        $filterStatus = $request->filterStatus;

        $roleUsers = Admin::with(['role'])
            ->when($authenticateUser->role_id == null, function ($query) {
                return $query->whereNotNull('role_id');
            })
            ->when(!empty($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%")->orWhere('username', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->when(!empty($filterUser), function ($query) use ($filterUser) {
                $query->where('name', 'LIKE', "%{$filterUser}%")
                    ->orWhere('username', 'LIKE', "%{$filterUser}%")
                    ->orWhere('email', 'LIKE', "%{$filterUser}%");
            })
            ->when(!empty($filterRole), function ($query) use ($filterRole) {
                return $query->whereHas('role', function ($q) use ($filterRole) {
                    $q->where('name', 'LIKE', "%{$filterRole}%");
                });
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->orderBy('role_id', 'ASC');

        return DataTables::of($roleUsers)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('name', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                                <div class="flex-shrink-0">
                                  ' . $item->adminProfilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . $item->name . '</h5>
                                  <span class="fs-6 text-body">@' . $item->username . '</span>
                                </div>
                              </a>';

            })
            ->addColumn('role', function ($item) {
                return optional($item->role)->name;
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                  </span>';

                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('In Active') . '
                  </span>';
                }
            })
            ->addColumn('action', function ($item) {

                $updateRoute = route('admin.role.staffEdit', $item->id);
                $deleteRoute = route('admin.staffDelete', $item->id);
                $loginAsStaffRoute = route('admin.role.staffLogin', $item->id);

                $update = '';
                $delete = '';
                $loginAsStaff = '';
                if (adminAccessRoute(array_merge(config('permissionList.Roles_And_Permission.Manage_Staff.permission.edit'), config('permissionList.Roles_And_Permission.Manage_Staff.permission.delete'), config('permissionList.Roles_And_Permission.Manage_Staff.permission.login_as')))) {
                    if (adminAccessRoute(config('permissionList.Roles_And_Permission.Manage_Staff.permission.edit'))) {
                        $update = '<a href="' . $updateRoute . '"
                       class="btn btn-white btn-sm">
                        <i class="fal fa-edit me-1"></i> ' . trans("Update") . '
                      </a>';
                    }
                    if (adminAccessRoute(config('permissionList.Roles_And_Permission.Manage_Staff.permission.delete'))) {
                        $delete = '<a class="dropdown-item deleteStaff" href="javascript:void(0)"
                           data-route="' . $deleteRoute . '"
                           data-bs-toggle="modal" data-bs-target="#deleteStaffModal">
                            <i class="bi bi-box-arrow-in-right dropdown-item-icon"></i>
                           ' . trans("Delete") . '
                        </a>';
                    }
                    if (adminAccessRoute(config('permissionList.Roles_And_Permission.Manage_Staff.permission.login_as'))) {
                        $loginAsStaff = '<a class="dropdown-item loginAccount" href="javascript:void(0)"
                           data-route="' . $loginAsStaffRoute . '"
                           data-bs-toggle="modal" data-bs-target="#loginAsStaffModal">
                            <i class="bi bi-box-arrow-in-right dropdown-item-icon"></i>
                           ' . trans("Login As Staff") . '
                        </a>';
                    }
                }

                return '<div class="btn-group" role="group">
                      ' . $update . '
                      <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                        ' . $loginAsStaff . '
                        ' . $delete . '
                      </div>
                    </div>
                  </div>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->make(true);
    }

    public function staffCreate()
    {
        $data['roles'] = Role::where('status', 1)->orderBy('id', 'ASC')->get();
        return view('admin.role_permission.staffCreate', $data);
    }

    public function staffStore(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'username' => ['required', 'string', 'max:50', 'unique:admins,username'],
            'password' => ['required', 'string', 'min:6'],
            'role_id' => ['required'],
        ];

        $message = [
            'name.required' => 'name is required',
            'email.required' => 'email is required',
            'username.required' => 'username is required',
            'role_id.required' => 'please select a role',
        ];

        $this->validate($request, $rules, $message);

        DB::beginTransaction();

        try {
            $user = new Admin();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->role_id = $request->role_id;
            $user->status = $request->status;
            $user->save();
            DB::commit();
            return back()->with('success', 'Staff Created Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function staffEdit($id)
    {
        $data['singleStaff'] = Admin::findOrFail($id);
        $data['roles'] = Role::where('status', 1)->orderBy('id', 'ASC')->get();
        return view('admin.role_permission.staffEdit', $data);
    }

    public function updateStaff(Request $request, $id)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'username' => ['required', 'string', 'max:50'],
            'role_id' => ['required'],
        ];

        $message = [
            'name.required' => 'name is required',
            'email.required' => 'email is required',
            'username.required' => 'username is required',
            'role_id.required' => 'please select a role',
        ];

        $this->validate($request, $rules, $message);

        DB::beginTransaction();
        try {
            $user = Admin::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->username = $request->username;
            $user->role_id = $request->role_id;
            $user->status = $request->status;
            $user->save();
            DB::commit();
            return back()->with('success', 'Staff Updated Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function staffDelete($id)
    {
        $staff = Admin::find($id);
        $staff->delete();
        return back()->with('success', 'Staff Deleted Successfully');
    }

    public function staffLogin($id)
    {
        $admin = Admin::findOrFail($id);
        if ($admin->status) {
            Auth::guard('admin')->loginUsingId($id);
            return redirect()->route('admin.dashboard');
        }
        return back()->with('error', 'This staff status is inactive!');
    }

}
