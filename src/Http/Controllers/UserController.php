<?php

namespace Bitsoftsol\LaravelAdministration\Http\Controllers;

use Bitsoftsol\LaravelAdministration\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('is_superuser', false)->get(); // Fetch users data from your database

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $editRoute = route('users.edit', $row->id);
                    $deleteRoute = route('users.destroy', $row->id);
                    $csrfField = csrf_field();
                    $methodField = method_field('DELETE');

                    return '<a href="' . $editRoute . '" class="btn btn-warning btn-sm">Edit</a>' .
                        '<form class="delete" action="' . $deleteRoute . '" method="POST" style="display: inline; padding-left:10px">' .
                        $csrfField .
                        $methodField .
                        '<button type="submit" class="btn btn-danger btn-sm">Delete</button>' .
                        '</form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('laravel-admin::users.list'); // Replace 'users.index' with the actual view name for your DataTable
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('laravel-admin::users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $inputs = $request->all();
            if($request->user_id != ''){
                $user = User::where('id', $request->user_id)->first();
                $rules = [
                    'first_name' => ['required', 'string', 'max:255'],
                    'last_name' => ['required', 'string', 'max:255'],
                ];

                // Check if the username in the request is different from the current username in the database
                if ($request->username != $user->username) {
                    $rules['username'] = ['required', 'string', 'max:255', 'unique:users'];
                }
                if ($request->email != $user->email) {
                    $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
                }

                $validated = $request->validate($rules);

                $user->name = $request->first_name. ' '. $request->last_name;
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->username = $request->username;
                $user->is_active = isset($inputs['is_active']) ? true : false;

                if($request->password != ''){
                    $user->password = Hash::make($request->password);
                }
                $user->save();

            }else{

                $validated = $request->validate([
                    'first_name' => ['required', 'string', 'max:255'],
                    'last_name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'username' => ['required', 'string', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);

                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' =>  $request->last_name,
                    'name' =>  $request->first_name. ' '. $request->last_name,
                    'email' =>  $request->email,
                    'username' => $request->username,
                    'is_superuser' => false,
                    'last_login' => Carbon::now(),
                    'date_joined' => Carbon::now(),
                    'is_active' => isset($inputs['is_active']) ? true : false,
                    'is_staff' => true,
                    'password' => Hash::make($request->password),
                ]);

            }
            DB::commit();
            if($request->action == 'save_and_add'){
                return redirect()->to(PREFIX_ADMIN_FOR_ROUTES . "users/create")->with('success', 'The user was saved successfully. You may add another user below.');
            }elseif($request->action == 'save_and_edit'){
                return redirect()->to(PREFIX_ADMIN_FOR_ROUTES . "users/" . $user->id . "/edit")->with('success', 'The user was saved successfully. You may edit it again below.');
            }else{
                return redirect()->to(PREFIX_ADMIN_FOR_ROUTES . 'users')->with('success', 'The user was saved successfully.');
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
        $user = User::where('id', $id)->first();
        return view('laravel-admin::users.create', compact('user'));
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
        User::where('id', $id)->delete();
        return true;
    }
}
