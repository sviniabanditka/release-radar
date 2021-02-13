<br>
<form action="{{ route('telegram.update.post') }}" method="post">
    @csrf
    <div>Notifications period (UTC +2):</div>
    <div class="wrap-input m-b-16">
        <select name="telegram_notifications_period[type]" id="telegram_notifications_period[type]" class="input">
            <option value="day" {{ $user->telegram_notifications_period['type'] == 'day' ? 'selected' : '' }}>Daily</option>
            <option value="week" {{ $user->telegram_notifications_period['type'] == 'week' ? 'selected' : '' }}>Weekly</option>
        </select>
        <span class="focus-input"></span>
    </div>
    <div class="wrap-input m-b-16" id="telegram_notifications_period[day]" style="{{ $user->telegram_notifications_period['type'] == 'day' ? 'display:none;' : '' }}">
        <select name="telegram_notifications_period[day]" class="input">
            @foreach(\Carbon\Carbon::getDays() as $key => $day)
                <option value="{{ $key }}" {{ $user->telegram_notifications_period['day'] == $key ? 'selected' : '' }}>{{ $day }}</option>
            @endforeach
        </select>
        <span class="focus-input"></span>
    </div>
    <div class="wrap-input m-b-16">
        <select name="telegram_notifications_period[time]" id="telegram_notifications_period[time]" class="input">
            @foreach (range(1,23) as $hour)
                <option value="{{ $hour }}" {{ $user->telegram_notifications_period['time'] == $hour ? 'selected' : '' }}>{{ \Illuminate\Support\Str::length($hour) == 1 ? '0'.$hour.':00' : $hour.':00' }}</option>
            @endforeach
        </select>
        <span class="focus-input"></span>
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
        <span class="focus-input"></span>
    </div>
    <br>
    <div class="container-form-btn m-t-17">
        <button class="form-btn" type="submit">Update Settings</button>
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
