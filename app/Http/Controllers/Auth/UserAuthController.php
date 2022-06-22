<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use http\Env\Response;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as P2;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => P2::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
                ->rules(["required", "confirmed"]),
        ]);

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        $token = $user->createToken('API Token')->accessToken;

        return route("home",['token'=>$token]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => P2::min(4)
        ]);

        if (!auth()->attempt($data)) {

            return response(['code' => 401, 'error_message' => 'Errore! Credenziali errate']);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response(['user' => auth()->user(), 'token' => $token]);

    }

    public function verifyToken(Request $request)
    {

        $request->validate(['access_token' => 'required']);
        $request->validate(['user_id' => 'required']);

        $token = $request->get('access_token');
        $user_id = $request->get('user_id');

        $res = DB::table('oauth_access_tokens')->where('id', '=', Hash::make($token))->where('user_id', '=', $user_id)->where('expires_at', '>', Carbon::now())->get();

        return ($res->count() === 1 ? response(['code' => 200]) : response(['code' => 401]));
    }

    public function resetPasswordRequest(Request $request)
    {

        $request->validate(['email' => 'required|email|in:users']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response(['code' => 200, 'message' => 'Ti abbiamo inviato un link per reimpostare la password! Controlla la tua casella di posta'])
            : response(['code' => 501, 'message' => $status]);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed', P2::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );


        return $status === Password::PASSWORD_RESET
            ? response(['code' => 200, 'message' => 'Password reimpostata correttamente. Verrai reindirizzato alla schermata di login!'])
            : response(['code' => 501, 'message' => $status]);
    }

//    public function getUserFromBearer(Request $request)
//    {
//        $user = Auth::guard('api')->user();
//
//        if ($user !== null) {
//            return response(["code" => 200, "user" => $user]);
//        }
//        return response(["code" => 403, "user" => null]);
//
//    }
}
