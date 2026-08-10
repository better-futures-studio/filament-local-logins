<?php

namespace BetterFuturesStudio\FilamentLocalLogins\Tests\Fixtures;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'can_access_panel',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'can_access_panel' => 'boolean',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can_access_panel;
    }
}
