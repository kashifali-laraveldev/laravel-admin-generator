<?php

namespace Bitsoftsol\LaravelAdministration\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Bitsoftsol\LaravelAdministration\Http\Requests\Api\Auth\LoginRequest;
use Bitsoftsol\LaravelAdministration\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
        /**
     * @OA\Post(
     *      path="/api/admin",
     *      operationId="login",
     *      tags={"auth,login"},
     *      summary="authentication",
     *      description="",
     *      @OA\Parameter(
    *          name="username",
    *          description="Username or Email",
    *          required=true,
    *           in="query",
    *          @OA\Schema(
    *              type="string"
    *          )
    *      ),
    *      @OA\Parameter(
    *          name="password",
    *          description="Password",
    *          required=true,
    *         in="query",
    *          @OA\Schema(
    *              type="string"
    *          )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *       ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function login(LoginRequest $request)
    {
        try{

            DB::beginTransaction();

            $inputs = $request->all();

            if ($user  = User::where('username', $inputs['username'])->orWhere('email', $inputs['username'])->first())
            {

                if (Hash::check($inputs['password'], $user->password))
                {
                    Auth::login($user);

                    $user = Auth::user();

                    $user->token = $user->createToken('bearer_token')->plainTextToken;

                    DB::commit();

                    return successWithData(LOGIN_SUCCCESS_MESSAGE, $user);
                }

            }

            DB::rollBack();

            return error(INVALID_CREDENTIALS_MESSAGE, ERROR_401);

        } catch (QueryException $e) {

            DB::rollBack();

            return error($e->getMessage(), ERROR_500);

        } catch (Exception $e) {

            DB::rollBack();

            return error($e->getMessage(), ERROR_500);
        }
    }

    public function logout(Request $request)
    {
        try{

            DB::beginTransaction();
            // Get user who requested the logout
            $user = Auth::user();

            // Revoke current user token
            if(isset($user->currentAccessToken()->id))
            {
                if($user->tokens()->where('id', $user->currentAccessToken()->id)->first())
                {

                    $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

                }

            }

            DB::commit();

            return success(USER_LOG_OUT_SUCCESS_MESSAGE);

        } catch (QueryException $e) {

            DB::rollBack();

            return error($e->getMessage(), ERROR_500);

        } catch (Exception $e) {

            DB::rollBack();

            return error($e->getMessage(), ERROR_500);
        }
    }


}
