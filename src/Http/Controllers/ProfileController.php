<?php

namespace Bitsoftsol\LaravelAdministration\Http\Controllers;

use Bitsoftsol\LaravelAdministration\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = new User();
    }
    /*
        @changePasswordForm
        Update the logged in user password
    */
    public function changePasswordForm(Request $request)
    {
        try {
            return view("laravel-admin::pages.change_password");

        } catch (QueryException $e) {

            // If any error occur in query execution
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        } catch (Exception $e) {

            // If any error occur that catch the exception like index name not found.
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /*
        @changePassword
        Update the logged in user password
    */
    public function changePassword(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate password and old password input confirmation
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|min:8|confirmed'
            ]);

            // Get All inputs
            $inputs = $request->all();

            // Fetch the Logged in user
            $user = $this->user->newQuery()->find(Auth::id());

            // Check the old password is matched with existing password in database
            if (Hash::check($inputs['old_password'], $user->password)) {

                // Create the encrypted new password and store
                $user->password = Hash::make($inputs['password']);

                // If model instance is saved then we commit the database changes and throw succes message
                if ($user->save()) {
                    DB::commit();
                    return back()->with('success', "Password changed Successfully");
                }
            }

            // If old password is not matched with existing user password then throw error message
            DB::rollback();
            return back()->with('error', "Old password is incorrect");

        } catch (QueryException $e) {

            // If any error occur in query execution
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        } catch (Exception $e) {

            // If any error occur that catch the exception like index name not found.
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
