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
            'role' => 'nullable',
        ]);
        try {
            $this->serviceUsers->create($request->all());
            return redirect()->back()->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function edit($id)
    {
        $get = $this->serviceUsers->getUser($id);
        $user = $get['user'];
        $databases = $get['databases'];
        $users_permissions = $get['userPermissions'];
        $available_privileges = ['CONNECT', 'CREATE', 'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'TRUNCATE', 'REFERENCES', 'TRIGGER'];
        return view('dashboard.admin_users.edit', compact('user', 'databases', 'users_permissions', 'available_privileges'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        try{
            $this->serviceUsers->updateUser($request->all(), $id);
            return redirect()->back()->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('error', $e->getMessage());
        }
    }

    public function updatePermissions(Request $request, $id)
    {
        $request->validate([
            'permissions*' => 'required',
            'database' => 'required'
        ]);
        try{
            $this->serviceUsers->updatePermissions($request->permissions, $id, $request->database);
            return redirect()->back()->with('success', 'Permissions updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('error', $e->getMessage());
        }
    }

    public function updateUM(Request $request)
    {
        try{
            $this->serviceUsers->updateUserMap($request->all());
            return redirect()->back()->with('success', 'User map updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('error', $e->getMessage());
        }
    }

    public function destroyUM(Request $request)
    {
        try{
            $this->serviceUsers->deleteUserMap($request->all());
            return redirect()->back()->with('success', 'User map deleted successfully');
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
