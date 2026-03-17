<?php

namespace Bitsoftsol\LaravelAdministration\Http\Controllers;

use Bitsoftsol\LaravelAdministration\Models\AuthGroup;
use Bitsoftsol\LaravelAdministration\Models\AuthGroupPermission;
use Bitsoftsol\LaravelAdministration\Models\AuthPermission;
use DataTables;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AuthGroup::all(); // Fetch users data from your database

            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    $editRoute = route('auth_groups.edit', $row->id);
                    $deleteRoute = route('auth_groups.destroy', $row->id);
                    $csrfField = csrf_field();
                    $methodField = method_field('DELETE');

                    return '<a href="' . $editRoute . '" class="btn btn-warning btn-sm">Edit</a>' .
                        '<form class="delete" action="' . $deleteRoute . '" method="POST" style="display: inline; padding-left:10px">' .
                        $csrfField .
                        $methodField .
                        '<button type="submit" class="btn btn-danger btn-sm">Delete</button>' .
                        '</form>';
                })
                ->addColumn('group_name', function ($row) {
                    $name = $row->name;
                    return $name;
                })
                ->addColumn('permission_name', function ($row) {
                    $permissions = '';
                    foreach($row->auth_group_permissions as $item){
                        $permissions .= $item->auth_permission->name;
                        $permissions .= ' ('.$item->auth_permission->codename.')';
                        $permissions .= '<br>';
                    }

                    return $permissions;
                })
                ->rawColumns(['group_name', 'permission_name','action'])
                ->make(true);
        }

        return view('laravel-admin::group.list'); // Replace 'users.index' with the actual view name for your DataTable
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $auth_permissions = AuthPermission::all();
        return view('laravel-admin::group.create', compact('auth_permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $inputs = $request->all();
            // dd($inputs);
            if($request->group_id != ''){
                $validated = $request->validate([
                    'choosen_permissions' => ['required'],
                ]);
                $auth_group = AuthGroup::where('id', $request->group_id)->first();
                $rules = [
                    'choosen_permissions' => ['required'],
                ];

                // Check if the username in the request is different from the current username in the database
                if ($request->name != $auth_group->name) {
                    $rules['name'] = ['required','unique:auth_groups'];
                }

                $validated = $request->validate($rules);
                AuthGroupPermission::where('group_id', $auth_group->id)->delete();
                foreach($request->choosen_permissions as $data){
                    $auth_group_permissions = new AuthGroupPermission();
                    $auth_group_permissions->group_id = $auth_group->id;
                    $auth_group_permissions->permission_id	= $data;
                    $auth_group_permissions->save();
                }


            }else{
                $validated = $request->validate([
                    'name' => ['required','unique:auth_groups'],
                    'choosen_permissions' => ['required'],
                ]);

                $auth_group = New AuthGroup();
                $auth_group->name = $request->name;
                $auth_group->save();

                foreach($request->choosen_permissions as $data){
                    $auth_group_permissions = new AuthGroupPermission();
                    $auth_group_permissions->group_id = $auth_group->id;
                    $auth_group_permissions->permission_id	= $data;
                    $auth_group_permissions->save();
                }
            }

            DB::commit();
            if($request->action == 'save_and_add'){
                return redirect()->to(PREFIX_ADMIN_FOR_ROUTES . 'auth_groups/create')->with('success', 'The group "' . $inputs['name'] . '" was saved successfully. You may add another group below.');
            }elseif($request->action == 'save_and_edit'){
                return redirect()->to(PREFIX_ADMIN_FOR_ROUTES . 'auth_groups/' . $auth_group->id . '/edit')->with('success', 'The group "' . $inputs['name'] . '" was added successfully. You may edit it again below.');
            }else{
                return redirect()->to(PREFIX_ADMIN_FOR_ROUTES . 'auth_groups')->with('success', 'The group "' . $inputs['name'] . '" was saved successfully.');
            }

        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $auth_group = AuthGroup::where('id', $id)->first();
        $auth_permissions = AuthPermission::whereNotIn('id', $auth_group->auth_group_permissions->pluck('permission_id')->toArray())->get();
        return view('laravel-admin::roup.create', compact('auth_permissions','auth_group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        AuthGroupPermission::where('group_id', $id)->delete();
        AuthGroup::where('id', $id)->delete();
        return true;
    }
}
