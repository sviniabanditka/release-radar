<br>
<form action="{{ route('telegram.update.post') }}" method="post">
    @csrf
    <div>Notifications period (UTC +2):</div>
    <div class="wrap-input100 validate-input m-b-16">
        <select name="telegram_notifications_period[type]" id="telegram_notifications_period[type]" class="input100">
            <option value="day" {{ $user->telegram_notifications_period['type'] == 'day' ? 'selected' : '' }}>Daily</option>
            <option value="week" {{ $user->telegram_notifications_period['type'] == 'week' ? 'selected' : '' }}>Weekly</option>
        </select>
        <span class="focus-input100"></span>
    </div>
    <div class="wrap-input100 validate-input m-b-16" id="telegram_notifications_period[day]" style="{{ $user->telegram_notifications_period['type'] == 'day' ? 'display:none;' : '' }}">
        <select name="telegram_notifications_period[day]" class="input100">
            @foreach(\Carbon\Carbon::getDays() as $key => $day)
                <option value="{{ $key }}" {{ $user->telegram_notifications_period['day'] == $key ? 'selected' : '' }}>{{ $day }}</option>
            @endforeach
        </select>
        <span class="focus-input100"></span>
    </div>
    <div class="wrap-input100 validate-input m-b-16">
        <select name="telegram_notifications_period[time]" id="telegram_notifications_period[time]" class="input100">
            @foreach (range(1,23) as $hour)
                <option value="{{ $hour }}" {{ $user->telegram_notifications_period['time'] == $hour ? 'selected' : '' }}>{{ \Illuminate\Support\Str::length($hour) == 1 ? '0'.$hour.':00' : $hour.':00' }}</option>
            @endforeach
        </select>
        <span class="focus-input100"></span>
    </div>

    <br><hr><br>
    <div>Notifications types:</div>
    <div class="">
        <table style="width: 100%; text-align: center;">
            <tr style="width: 100%;">
                <td>
                    <label for="telegram_notifications_types[album]" style="margin-top:25px;">Album</label>
                </td>
                <td>
                    <input class="option-input checkbox" type="checkbox" name="telegram_notifications_types[album]" id="telegram_notifications_types[album]" {{ $user->telegram_notifications_types['album'] == 1 ? 'checked' : '' }}>
                </td>
                <td>
                    <label for="telegram_notifications_types[single]" style="margin-top:25px;">Single</label>
                </td>
                <td>
                    <input class="option-input checkbox" type="checkbox" name="telegram_notifications_types[single]" id="telegram_notifications_types[single]" {{ $user->telegram_notifications_types['single'] == 1 ? 'checked' : '' }}>
                </td>
            </tr>
            <tr style="width: 100%;">
                <td>
                    <label for="telegram_notifications_types[appears_on]" style="margin-top:25px;">Appears On</label>
                </td>
                <td>
                    <input class="option-input checkbox" type="checkbox" name="telegram_notifications_types[appears_on]" id="telegram_notifications_types[appears_on]" {{ $user->telegram_notifications_types['appears_on'] == 1 ? 'checked' : '' }}>
                </td>
                <td>
                    <label for="telegram_notifications_types[compilation]" style="margin-top:25px;">Compilation</label>
                </td>
                <td>
                    <input class="option-input checkbox" type="checkbox" name="telegram_notifications_types[compilation]" id="telegram_notifications_types[compilation]" {{ $user->telegram_notifications_types['compilation'] == 1 ? 'checked' : '' }}>
                </td>
            </tr>
        </table>
        <span class="focus-input100"></span>
    </div>
    <br>
    <div class="container-login100-form-btn m-t-17">
        <button class="login100-form-btn" type="submit">Update Settings</button>
    </div>
</form>


@push('scripts')
    <script type="text/javascript">
        let select = document.getElementById("telegram_notifications_period[type]");
        select.onchange=function(){
            if(select.value === "week"){
                document.getElementById("telegram_notifications_period[day]").style.display="block";
            }else{
                document.getElementById("telegram_notifications_period[day]").style.display="none";
            }
        }
    </script>
@endpush

@push('css')
    <style type="text/css">
        .option-input {
            -webkit-appearance: none;
            -moz-appearance: none;
            -ms-appearance: none;
            -o-appearance: none;
            appearance: none;
            position: relative;
            top: 13.33333px;
            right: 0;
            bottom: 0;
            left: 0;
            height: 30px;
            width: 30px;
            transition: all 0.15s ease-out 0s;
            background: #a0a1a2;
            border: none;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            margin-right: 0.5rem;
            outline: none;
            position: relative;
            z-index: 1000;
        }
        .option-input:hover {
            background: #9faab7;
        }
        .option-input:checked {
            background: #626262;
        }
        .option-input:checked::before {
            height: 30px;
            width: 30px;
            position: absolute;
            content: '✔';
            display: inline-block;
            font-size: 26.66667px;
            text-align: center;
            line-height: 30px;
        }
        .option-input:checked::after {
            -webkit-animation: click-wave 0.65s;
            -moz-animation: click-wave 0.65s;
            animation: click-wave 0.65s;
            background: #40e0d0;
            content: '';
            display: block;
            position: relative;
            z-index: 100;
        }
    </style>
@endpush
