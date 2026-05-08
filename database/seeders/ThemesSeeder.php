<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemesSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            ['key' => 'modern',       'name' => 'モダン',     'default_color' => '#6366f1'],
            ['key' => 'heisei_pink',  'name' => '平成ピンク', 'default_color' => '#ff66cc'],
            ['key' => 'garake_green', 'name' => 'ガラケー風', 'default_color' => '#00ff88'],
            ['key' => 'oshikatsu',    'name' => '推し活ネオン', 'default_color' => '#ff0080'],
            ['key' => 'pastel',       'name' => 'パステル',   'default_color' => '#fbbf24'],
        ];

        foreach ($themes as $t) {
            Theme::updateOrCreate(
                ['key' => $t['key']],
                $t + ['is_active' => true],
            );
        }
    }
}
