<?php

namespace App\Http\Controllers;

use App\Services\ServiceUsers;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $serviceUsers;
    public function __construct(ServiceUsers $serviceUsers)
    {
        $this->serviceUsers = $serviceUsers;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'tablespace' => 'required',
            'quota' => 'required',
        ]);
        try{
            $this->serviceUsers->create($request->all());
            return redirect()->back()->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $get = $this->serviceUsers->getUser($id);
        $data = $get['data'];
        $tablespaces = $get['tablespaces'];
        return view('dashboard.admin_users.edit',compact('data','tablespaces'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'password' => 'nullable',
            'temporary_tablespace' => 'required',
            'default_tablespace' => 'required',
            'account_status' => 'required',
        ]);
        try{
            $this->serviceUsers->updateUser($request->all(), $id);
            return redirect()->back()->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try{
            $this->serviceUsers->deleteUser($id);
            return redirect()->back()->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('error', $e->getMessage());
        }
    }

    public function viewRoles()
    {
        $roles = $this->serviceUsers->getRoles();

        return view('dashboard.admin_users.roles', compact('roles'));
    }

    public function assingRole(Request $request, $id)
    {
        $request->validate([
            'role*' => 'required'
        ]);
        try{
            $this->serviceUsers->assignRolesUser($request->role, $id);
            return redirect()->back()->with('success', 'Role assigned successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('error', $e->getMessage());
        }
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'privileges*' => 'required'
        ]);
        try{
            $this->serviceUsers->createRole($request->all());
            return redirect()->back()->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('error', $e->getMessage());
        }
    }

    public function destroyRole($id)
    {
        try{
            $this->serviceUsers->deleteRole($id);
            return redirect()->back()->with('success', 'Role deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('error', $e->getMessage());
        }
    }

}
