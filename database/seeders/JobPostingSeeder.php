<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPosting;
use App\Models\User;

class JobPostingSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            [
                'title'        => 'Senior Frontend Developer',
                'location'     => 'Kuala Lumpur',
                'salary_min'   => 8000,
                'salary_max'   => 12000,
                'description'  => 'We are looking for an experienced Frontend Developer proficient in React, Vue, and modern CSS frameworks. You will work closely with our design team to build beautiful, responsive web applications.',
                'requirements' => "- 3+ years of experience in frontend development\n- Proficiency in React or Vue.js\n- Strong understanding of HTML, CSS, JavaScript\n- Experience with REST APIs\n- Good communication skills",
                'status'       => 'open',
            ],
            [
                'title'        => 'Backend Engineer (Laravel)',
                'location'     => 'Petaling Jaya',
                'salary_min'   => 6000,
                'salary_max'   => 9000,
                'description'  => 'Join our backend team to build and maintain scalable APIs and services using Laravel. You will collaborate with frontend developers and DevOps to deliver high-quality software.',
                'requirements' => "- 2+ years of Laravel experience\n- Strong knowledge of MySQL or PostgreSQL\n- Experience with RESTful API design\n- Familiarity with Git and agile workflows",
                'status'       => 'open',
            ],
            [
                'title'        => 'Product Designer (UI/UX)',
                'location'     => 'Remote',
                'salary_min'   => 5000,
                'salary_max'   => 8000,
                'description'  => 'We need a creative Product Designer to craft intuitive user experiences for our mobile and web platforms. You will own the design process from research to final handoff.',
                'requirements' => "- Portfolio showcasing UI/UX projects\n- Proficiency in Figma\n- Experience with user research and usability testing\n- Strong visual design sense",
                'status'       => 'open',
            ],
            [
                'title'        => 'Data Analyst',
                'location'     => 'Shah Alam',
                'salary_min'   => 4000,
                'salary_max'   => 6000,
                'description'  => 'Analyze business data to provide insights and recommendations. You will work with large datasets and present findings to stakeholders to support data-driven decisions.',
                'requirements' => "- Proficiency in SQL and Excel\n- Experience with Python or R for data analysis\n- Familiarity with Tableau or Power BI\n- Strong analytical and problem-solving skills",
                'status'       => 'open',
            ],
            [
                'title'        => 'DevOps Engineer',
                'location'     => 'Cyberjaya',
                'salary_min'   => 7000,
                'salary_max'   => 11000,
                'description'  => 'Manage and improve our cloud infrastructure on AWS. You will automate deployments, monitor systems, and ensure high availability of our services.',
                'requirements' => "- Experience with AWS or GCP\n- Proficiency in Docker and Kubernetes\n- Strong scripting skills (Bash, Python)\n- Experience with CI/CD pipelines",
                'status'       => 'open',
            ],
            [
                'title'        => 'Mobile Developer (React Native)',
                'location'     => 'Kuala Lumpur',
                'salary_min'   => 5000,
                'salary_max'   => 8000,
                'description'  => 'Build and maintain cross-platform mobile applications using React Native. You will work with product and backend teams to deliver smooth mobile experiences.',
                'requirements' => "- 2+ years of React Native experience\n- Published apps on App Store or Google Play\n- Knowledge of mobile performance optimization\n- Experience with REST APIs and state management",
                'status'       => 'open',
            ],
        ];

        foreach ($jobs as $job) {
        // Pick a random employer for each job
        $randomEmployer = User::where('role', 'employer')->inRandomOrder()->first();

        if ($randomEmployer) {
            JobPosting::create(array_merge($job, [
                'employer_id' => $randomEmployer->id,
            ]));
        }
    }
        $this->command->info('6 job postings seeded successfully!');
    }
}