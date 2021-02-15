<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> Dashboard</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('user') }}'><i class='nav-icon la la-users'></i> Users</a></li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-spotify"></i> Spotify</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('spotify/artist') }}'><i class='nav-icon la la-microphone-alt'></i> Artists</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('spotify/release') }}'><i class='nav-icon la la-compact-disc'></i> Releases</a></li>
    </ul>
</li>
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-telegram"></i> Telegram</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('telegram/notification') }}'><i class='nav-icon la la-bell'></i> Notifications</a></li>
    </ul>
</li>
