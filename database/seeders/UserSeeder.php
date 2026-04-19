<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // =======================
        // EMPLOYER 1
        // =======================
        $employer1 = User::create([
            'name' => 'Ant International',
            'email' => 'hr@antinternational.com',
            'password' => Hash::make('password'),
            'role' => 'employer',
        ]);

        $employer1->employerProfile()->create([
            'phone' => '0123456789',
            'company_name' => 'Ant International',
            'industry' => 'Fintech',
            'company_size' => '5000+',
            'website' => 'https://www.ant-intl.com/en/',
            'description' => 'Leading fintech company in China',
            'logo_path' => null,
            'location' => 'Kuala Lumpur',
            'address' => 'TRX KL',
            'year_established' => 2000,
            'linkedin' => 'antinternational-linkedin',
            'facebook' => null,
            'twitter' => null,
        ]);


        // =======================
        // EMPLOYER 2
        // =======================
        $employer2 = User::create([
            'name' => 'TechCorp HR',
            'email' => 'hr@techcorp.com',
            'password' => Hash::make('password'),
            'role' => 'employer',
        ]);

        $employer2->employerProfile()->create([
            'phone' => '0128888888',
            'company_name' => 'TechCorp',
            'industry' => 'Software Development',
            'company_size' => '50-100',
            'website' => 'https://techcorp.com',
            'description' => 'Leading tech company in Malaysia',
            'logo_path' => null,
            'location' => 'Kuala Lumpur',
            'address' => 'KL Sentral',
            'year_established' => 2015,
            'linkedin' => 'techcorp-linkedin',
            'facebook' => null,
            'twitter' => null,
        ]);

        // =======================
        // SEEKER
        // =======================
        $seeker = User::create([
            'name' => 'Ali Ahmad',
            'email' => 'ali@test.com',
            'password' => Hash::make('password'),
            'role' => 'seeker',
        ]);

        $seeker->seekerProfile()->create([
            'phone' => '0111111111',
            'location' => 'Selangor',
            'job_title' => 'Fresh Graduate Developer',
            'bio' => 'Passionate about web development',
            'skills' => 'PHP, Laravel, JavaScript',
            'resume_path' => null,
            'availability' => 'Immediate',
            'expected_salary' => 4000,
            'education_university' => 'UTAR',
            'education_degree' => 'Bachelor in Software Engineering',
            'education_year' => 2025,
            'work_company' => null,
            'work_position' => null,
            'work_years' => 0,
            'languages' => 'English, Malay',
        ]);
    }
}
