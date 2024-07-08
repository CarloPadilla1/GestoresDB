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
            'username' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
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
        return view('dashboard.admin_users.edit',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'password' => 'nullable',
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
            'privileges*' => 'required'
        ]);
        try{
            $this->serviceUsers->assignRolesUser($request->privileges, $id);
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