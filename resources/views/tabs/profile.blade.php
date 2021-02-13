<br>
<form action="{{ route('auth.email.post') }}" method="post">
    @csrf
    <div class="wrap-input m-b-16" data-validate = "Email is required">
        <input class="input" type="text" name="email" placeholder="Email" value="{{ $user->email }}">
        <span class="focus-input"></span>
    </div>
    <div class="container-form-btn m-t-17">
        <button class="form-btn" type="submit">Update Email</button>
    </div>
</form>
<br><hr><br>
<form action="{{ route('auth.password.post') }}" method="post">
    @csrf
    <div class="wrap-input m-b-16" data-validate = "Password is required">
        <input class="input" type="password" name="password" placeholder="New password">
        <span class="focus-input"></span>
    </div>
    <div class="wrap-input m-b-16" data-validate = "Password confirmation is required">
        <input class="input" type="password" name="password_confirmation" placeholder="New password confirmation">
        <span class="focus-input"></span>
    </div>
    <div class="container-form-btn m-t-17">
        <button class="form-btn" type="submit">Update Password</button>
    </div>
</form>
