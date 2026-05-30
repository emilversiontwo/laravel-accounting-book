<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use MoonShine\Laravel\MoonShineAuth;

class MoonShineUserSeeder extends Seeder
{
    public function run(): void
    {
        MoonShineAuth::getModel()::query()->create([
            moonshineConfig()->getUserField('username') => 'admin',
            moonshineConfig()->getUserField('name') => 'admin',
            moonshineConfig()->getUserField('password') => Hash::make('password'),
        ]);

        info('User is created');
    }
}
