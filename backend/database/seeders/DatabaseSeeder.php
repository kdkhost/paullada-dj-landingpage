<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@paullada.com.br',
            'password' => bcrypt('admin123'),
        ]);

        $settings = [
            ['chave' => 'site_name', 'valor' => 'Paullada DJ', 'tipo' => 'text'],
            ['chave' => 'site_description', 'valor' => 'O melhor do flashback! Músicas que marcaram as décadas de 70, 80, 90 e 2000.', 'tipo' => 'text'],
            ['chave' => 'site_keywords', 'valor' => 'DJ, flashback, música, anos 70, anos 80, anos 90, anos 2000, festa, show', 'tipo' => 'text'],
            ['chave' => 'instagram_url', 'valor' => 'https://www.instagram.com/paulladadjrj', 'tipo' => 'text'],
            ['chave' => 'whatsapp_number', 'valor' => '', 'tipo' => 'text'],
            ['chave' => 'email_contato', 'valor' => '', 'tipo' => 'text'],
            ['chave' => 'logo', 'valor' => 'images/logo.png', 'tipo' => 'text'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }
    }
}
