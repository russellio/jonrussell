<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * The `description` field holds curated resume copy as sanitized HTML
     * (an optional lead line + a bullet list). Only positions with a
     * description are surfaced in the Experience timeline; the two earliest
     * roles are intentionally kept in the DB but hidden from display.
     */
    public function run(): void
    {
        $positions = [
            [
                'company' => 'AYCON',
                'title' => 'Software Engineer',
                'start_date' => '2004-05-01',
                'end_date' => '2007-01-01',
            ],
            [
                'company' => 'DI - WSD',
                'title' => 'Web Software Developer',
                'start_date' => '2007-02-01',
                'end_date' => '2009-05-01',
            ],
            [
                'company' => 'Digital Insight',
                'title' => 'Project Manager – Automated Installations Management',
                'start_date' => '2009-06-01',
                'end_date' => '2012-07-01',
                'bullets' => [
                    'Managed post-sale onboarding for 30+ financial institutions simultaneously, serving as the primary post-sale contact.',
                    'Delivered training sessions for client staff to drive adoption of internet banking add-ons.',
                    'Collaborated with support and engineering to resolve deployment issues quickly, maintaining strong client relationships.',
                    'Created automation tools that reduced onboarding timelines and client effort by 20%.',
                ],
            ],
            [
                'company' => 'Digital Insight',
                'title' => 'Manager – Project Management',
                'start_date' => '2012-08-01',
                'end_date' => '2015-01-01',
                'lead' => 'Digital Banking SaaS, acquired by NCR — $330M/yr',
                'bullets' => [
                    'Directed a team of 8 client-facing project managers delivering websites and digital banking solutions (mobile apps, mobile deposit, secure support, SSO).',
                    'Reduced project cycle times and manual effort by analyzing team metrics and driving efficiency initiatives.',
                    'Spearheaded internal automation tools that eliminated manual workflows and reduced delivery cycle time.',
                ],
            ],
            [
                'company' => 'ARCA',
                'title' => 'Web Software Developer / Build Engineer',
                'start_date' => '2015-01-01',
                'end_date' => '2016-10-01',
                'bullets' => [
                    'Engineered enterprise-grade software to automate cash processes for a global client base, leveraging expertise in Laravel, PHP, MySQL, and JavaScript.',
                    'Managed release cycles and implemented CI/CD pipelines with Jenkins as Build Engineer.',
                    'Established processes that increased test coverage, reduced defects, and improved the stability and reliability of production releases.',
                    'Acted as hybrid Product Owner, coordinating vendor deliverables, project plans, and stakeholder communication.',
                ],
            ],
            [
                'company' => 'Glen Raven, Inc.',
                'title' => 'Senior Web Application Developer / Certified ScrumMaster',
                'start_date' => '2016-11-01',
                'end_date' => '2023-05-01',
                'lead' => 'Textiles, Sunbrella brand — $900M/yr',
                'bullets' => [
                    'Led end-to-end development of enterprise web apps using Laravel, PHP, MySQL, REST APIs, JavaScript, and Bootstrap.',
                    'Built a multi-plant machine repair tracking system that reduced cycle time by 38% and cut labor demands by 50% for 300+ technicians.',
                    'Introduced Agile practices; served as scrum master, facilitating sprints, retrospectives, and planning across multiple teams.',
                    'Partnered with the Cloud Infrastructure team to design pipeline architecture.',
                    'Automated CI/CD with GitHub and Azure Pipelines, improving deployment speed and reliability.',
                    'Introduced automated testing; authored and reviewed unit-tested code to uphold performance, security, and maintainability standards.',
                ],
            ],
            [
                'company' => 'Pioneering Evolution',
                'title' => 'Software Engineer',
                'start_date' => '2024-03-01',
                'end_date' => '2025-08-01',
                'lead' => 'DoD Software & Consulting — $8.1M/yr',
                'bullets' => [
                    'Developed and optimized advanced features for a DoD budgeting web app using Laravel 11 & 12, PHP 8, MySQL 8, Vue 3, JavaScript, TypeScript, Node.js, and Bootstrap.',
                    'Key contributor to the Vue 2 → Vue 3 migration, ensuring seamless modernization.',
                    'Optimized endpoints and memory usage to reduce API response times in critical workflows.',
                    'Provided technical consulting and code reviews to improve application security, architecture, and long-term maintainability.',
                ],
            ],
            [
                'company' => 'Russell I/O, LLC',
                'title' => 'Senior Full Stack Engineer / Software Developer',
                'start_date' => '2025-08-01',
                'end_date' => null, // Current position, no end date
                'bullets' => [
                    'Built and launched LawnWriter, a production iOS application using React Native, Expo, Laravel, and MySQL, and published an open-source Vue 3 component (vue-background-stars) to npm.',
                    'Build and maintain full-stack web applications for clients acquired entirely through organic referrals, using Laravel, PHP, MySQL, JavaScript, React, Bootstrap, and Tailwind CSS.',
                    'Partner directly with stakeholders to gather requirements, design solutions, and deliver scalable applications with a focus on usability, maintainability, and performance.',
                    'Provide technical consulting and code reviews to improve application security, architecture, and long-term maintainability.',
                ],
            ],
        ];

        foreach ($positions as $positionData) {
            $company = Company::where('name', $positionData['company'])->first();

            if (! $company) {
                continue;
            }

            Position::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'start_date' => $positionData['start_date'],
                ],
                [
                    'title' => $positionData['title'],
                    'end_date' => $positionData['end_date'],
                    'description' => $this->buildDescription($positionData),
                ]
            );
        }
    }

    /**
     * Build sanitized HTML description from an optional lead line and bullets.
     *
     * @param  array<string, mixed>  $positionData
     */
    private function buildDescription(array $positionData): ?string
    {
        $bullets = $positionData['bullets'] ?? [];

        if (empty($bullets)) {
            return null;
        }

        $html = '';

        if (! empty($positionData['lead'])) {
            $html .= '<p>'.e($positionData['lead']).'</p>';
        }

        $items = collect($bullets)
            ->map(fn (string $bullet): string => '<li>'.e($bullet).'</li>')
            ->implode('');

        return $html.'<ul>'.$items.'</ul>';
    }
}
