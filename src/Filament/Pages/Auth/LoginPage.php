<?php

namespace BetterFuturesStudio\FilamentLocalLogins\Filament\Pages\Auth;

use BetterFuturesStudio\FilamentLocalLogins\Concerns\HasLocalLogins;
use Filament\Pages\Auth\Login;

class LoginPage extends Login
{
    use HasLocalLogins;
}
