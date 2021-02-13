<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\UpdateEmailRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function getLogin()
    {
        if (Sentinel::check()) {
            return redirect()->route('dashboard.get');
        }
        return view('auth.login');
    }

    public function postLogin(LoginRequest $request)
    {
        $remember = $request->get('remember-me') == 1;
        $user = Sentinel::findByCredentials($request->only(['email', 'password']));
        if ($user) {
            if (Activation::completed($user)) {
                if (Sentinel::authenticate($request->only(['email', 'password']), $remember)) {
                    toastr('Login successfully');
                    return redirect()->route('dashboard.get');
                } else {
                    toastr('Invalid email or password', 'error');
                    return redirect()->route('auth.login.get');
                }
            } else {
                toastr('Activate your account via email first!', 'error');
                return redirect()->route('auth.login.get');
            }
        } else {
            toastr('Invalid email or password', 'error');
            return redirect()->route('auth.login.get');
        }
    }

    public function getRegister()
    {
        if (Sentinel::check()) {
            return redirect()->route('dashboard.get');
        }
        return view('auth.register');
    }

    public function postRegister(RegisterRequest $request)
    {
        $request = $request->only(['email', 'password']);
        $user = Sentinel::register($request);
        if ($user) {
            $role = Sentinel::findRoleBySlug('user');
            $role->users()->attach($user);
            $activation = Activation::create($user);
            Mail::send('mail.success_registration', [
                'user' => $user,
                'activation' => $activation
            ], function ($mail) use ($user) {
                $mail->to($user->email);
                $mail->subject('Account activation | ReleaseRadar');
                $mail->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
            toastr('Register successfully');
            return view('auth.success_registration');
        }
        toastr('Error registration account', 'error');
        return redirect()->route('auth.register.get');
    }

    public function getActivateUser(Request $request)
    {
        if ($request->has('code') && $request->has('user_id')) {
            $code = $request->get('code');
            $user_id = $request->get('user_id');
            $user = Sentinel::findById($user_id);
            if ($user && Activation::exists($user, $code)) {
                Activation::complete($user, $code);
                toastr('Activation successfully');
                Sentinel::login($user, true);
                return redirect()->route('dashboard.get');
            }
        }
        toastr('Error activating account', 'error');
        return redirect()->route('auth.login.get');
    }

    public function getForgotPassword()
    {
        if (Sentinel::check()) {
            return redirect()->route('dashboard.get');
        }
        return view('auth.forgot');
    }

    public function postForgotPassword(ForgotPasswordRequest $request)
    {
        $user = Sentinel::findByCredentials(['email' => $request->get('email')]);
        if ($user) {
            $reminder = Reminder::create($user);
            Mail::send('mail.forgot_password', [
                'user' => $user,
                'reminder' => $reminder
            ], function ($mail) use ($user) {
                $mail->to($user->email);
                $mail->subject('Restore access | ReleaseRadar');
                $mail->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
            toastr('Email was sent successfully');
            return view('auth.forgot_success');
        }
        toastr('Email not found', 'error');
        return redirect()->route('auth.forgot.get');
    }

    public function getResetPassword(Request $request)
    {
        if (Sentinel::check() || !$request->has('code') || !$request->has('user_id')) {
            return redirect()->route('dashboard.get');
        }
        $code = $request->get('code');
        $user_id = $request->get('user_id');
        $user = Sentinel::findById($user_id);
        if (!$user || !$code) {
            return redirect()->route('auth.login.get');
        }
        return view('auth.reset', compact('code', 'user'));
    }

    public function postResetPassword(ResetPasswordRequest $request)
    {
        $request = $request->only(['code', 'password', 'user_id']);
        $user = Sentinel::findById($request['user_id']);
        $exist_reminder = Reminder::exists($user, $request['code']);
        if ($user && $exist_reminder) {
            Reminder::complete($user, $request['code'], $request['password']);
            toastr('Password successfully updated!');
            return redirect()->route('auth.login.get');
        }
        toastr('Updating password error!', 'error');
        return redirect()->route('auth.login.get');
    }

    public function postUpdateEmail(UpdateEmailRequest $request)
    {
        $user = Sentinel::getUser();
        $email = $request->get('email');
        if($user && $email) {
            if(Sentinel::update($user, ['email' => $email])) {
                toastr('Email successfully updated');
                Sentinel::logout();
                return redirect()->route('auth.login.get');
            }
        }
        toastr('Error updating email');
        return redirect()->to(route('dashboard.get').'#profile');

    }

    public function postUpdatePassword(UpdatePasswordRequest $request)
    {
        $user = Sentinel::getUser();
        $password = $request->get('password');
        if($user && $password) {
            if(Sentinel::update($user, ['password' => $password])) {
                toastr('Password successfully updated');
                Sentinel::logout();
                return redirect()->route('auth.login.get');
            }
        }
        toastr('Error updating password');
        return redirect()->to(route('dashboard.get').'#profile');
    }

    public function getLogout()
    {
        Sentinel::logout(null, true);
        toastr('Logged out successfully');
        return redirect()->route('landing.get');
    }
}
