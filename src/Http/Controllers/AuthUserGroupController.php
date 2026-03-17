<?php

namespace Bitsoftsol\LaravelAdministration\Http\Controllers;

use Illuminate\Http\Request;
use Bitsoftsol\LaravelAdministration\Models\AuthGroup;
use Bitsoftsol\LaravelAdministration\Models\AuthUserGroup;
use DataTables;
use Bitsoftsol\LaravelAdministration\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AuthUserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::whereHas('auth_user_groups')->get(); // Fetch users who has groups

            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    $editRoute = route('auth_user_group.edit', $row->id);
                    $deleteRoute = route('auth_user_group.destroy', $row->id);
                    $csrfField = csrf_field();
                    $methodField = method_field('DELETE');

                    return '<a href="' . $editRoute . '" class="btn btn-warning btn-sm">Edit</a>' .
                        '<form class="delete" action="' . $deleteRoute . '" method="POST" style="display: inline; padding-left:10px">' .
                        $csrfField .
                        $methodField .
                        '<button type="submit" class="btn btn-danger btn-sm">Delete</button>' .
                        '</form>';
                })
                ->addColumn('first_name', function ($row) {
                    // Display user first and last name
                    $name = $row->first_name ?? '';
                    return $name;
                })
                ->addColumn('last_name', function ($row) {
                    // Display user first and last name
                    $name = $row->last_name ?? '';
                    return $name;
                })
                ->addColumn('group_name', function ($row) {
                    // Get all groups assigned to the current user in string format
                    $groups = '';
                    $ids = $row->auth_user_groups()->pluck('group_id')->toArray();
                    $authGroups = AuthGroup::whereIn('id', $ids)->get();
                    foreach($authGroups as $group)
                    {
                        $groups .= $group->name;
                        $groups .= '<br>';
                    }

                    return $groups;
                })
                ->rawColumns(['first_name', 'last_name', 'group_name', 'action']) // specify columns displayed in view
                ->make(true);
        }

        return view('laravel-admin::auth_user_group.list'); // Replace 'users.index' with the actual view name for your DataTable
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // List of all groups that can be assigned to any user
        $auth_groups = AuthGroup::all();
        $users = User::all();
        return view('laravel-admin::auth_user_group.create', compact('auth_groups', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $inputs = $request->all();
            // Validate the form data
            $request->validate([
                'user_id' => ['required','exists:users,id'],
                'choosen_groups' => ['required'],
            ]);
            // If user already contain groups then delete the groups
            if(AuthUserGroup::whereUserId($inputs['user_id'])->count() > 0)
            {
                AuthUserGroup::whereUserId($inputs['user_id'])->delete();
            }
            // Create new groups of user, all group ids
            foreach($inputs['choosen_groups'] as $group)
            {
                $auth_user_group = new AuthUserGroup();
                $auth_user_group->group_id = $group;
                $auth_user_group->user_id = $inputs['user_id'];
                $auth_user_group->save();
            }
            // If everything is fine then we commit
            DB::commit();
            // If user save and again add then this will work
            if($request->action == 'save_and_add'){
                return redirect()->to(PREFIX_ADMIN_FOR_ROUTES . 'auth_user_group/create')->with('success', 'The auth group user was saved successfully. You may add another auth group user below.');
            }elseif($request->action == 'save_and_edit'){ // save and continue to edit
                return redirect()->to(PREFIX_ADMIN_FOR_ROUTES . 'auth_user_group/' . $inputs['user_id'] . '/edit')->with('success', 'The auth user group was added successfully. You may edit it again below.');
            }else{
                return redirect()->to(PREFIX_ADMIN_FOR_ROUTES . 'auth_user_group')->with('success', 'The auth group user was saved successfully.');
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
        // On Edit get matched ID user
        $group_user = User::where('id', $id)->first();
        // All user list
        $users = User::get();
        // List of All Groups That are not assigned to matched user
        $auth_groups = AuthGroup::whereNotIn('id', $group_user->auth_user_groups->pluck('group_id')->toArray())->get();
        // All groups selected by the User
        $choosen_groups = AuthGroup::whereIn('id', $group_user->auth_user_groups->pluck('group_id')->toArray())->get();

        return view('laravel-admin::auth_user_group.create', compact('auth_groups','group_user', 'users', 'choosen_groups'));
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
        // Delete all Groups assigned to matched ID User.
        AuthUserGroup::where('user_id', $id)->delete();
        return true;
    }
}
