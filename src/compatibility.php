<?php

use BetterFuturesStudio\FilamentLocalLogins\Filament\Pages\Auth\BaseLoginPage;

$filamentLoginPages = [
    'Filament\\Auth\\Pages\\Login',
    'Filament\\Pages\\Auth\\Login',
];

foreach ($filamentLoginPages as $filamentLoginPage) {
    if (! class_exists($filamentLoginPage)) {
        continue;
    }

    if (! class_exists(BaseLoginPage::class, false)) {
        class_alias($filamentLoginPage, BaseLoginPage::class);
    }

    break;
}

if (! class_exists(BaseLoginPage::class, false)) {
    throw new LogicException('A supported Filament login page could not be found.');
}
