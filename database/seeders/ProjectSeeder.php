<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Icon;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = $this->projectData();

        foreach ($projects as $data) {
            $company = isset($data['company'])
                ? Company::where('name', $data['company'])->first()
                : null;

            $project = Project::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'byline' => $data['byline'] ?? null,
                    'description' => $data['description'] ?? null,
                    'primary_image_src' => $data['primary_image_src'] ?? null,
                    'primary_image_title' => $data['primary_image_title'] ?? null,
                    'primary_image_alt' => $data['primary_image_alt'] ?? null,
                    'bg_image' => $data['bg_image'] ?? null,
                    'bg_position_x' => $data['bg_position_x'] ?? null,
                    'bg_position_y' => $data['bg_position_y'] ?? null,
                    'company_id' => $company?->id,
                    'order' => $data['order'],
                ]
            );

            $project->keyTakeaways()->delete();
            foreach ($data['key_takeaways'] ?? [] as $i => $text) {
                $project->keyTakeaways()->create(['text' => $text, 'order' => $i]);
            }

            $project->images()->delete();
            foreach ($data['images'] ?? [] as $i => $image) {
                $project->images()->create([
                    'src' => $image['src'],
                    'title' => $image['title'] ?? null,
                    'alt' => $image['alt'] ?? null,
                    'order' => $i,
                ]);
            }

            $project->links()->delete();
            foreach ($data['links'] ?? [] as $i => $link) {
                $project->links()->create([
                    'title' => $link['title'],
                    'url' => $link['url'],
                    'order' => $i,
                ]);
            }

            $project->technologies()->delete();
            foreach ($data['technologies'] ?? [] as $i => $tech) {
                $iconId = $this->iconId($tech['icon_type'] ?? null, $tech['icon_name'] ?? null);
                $project->technologies()->create([
                    'name' => $tech['name'],
                    'icon_id' => $iconId,
                    'order' => $i,
                    'is_highlighted' => $tech['is_highlighted'] ?? false,
                ]);
            }

            $project->tools()->delete();
            foreach ($data['tools'] ?? [] as $i => $tool) {
                $iconId = $this->iconId($tool['icon_type'] ?? null, $tool['icon_name'] ?? null);
                $project->tools()->create([
                    'name' => $tool['name'],
                    'icon_id' => $iconId,
                    'order' => $i,
                ]);
            }

            $project->awards()->delete();
            foreach ($data['awards'] ?? [] as $i => $text) {
                $project->awards()->create(['text' => $text, 'order' => $i]);
            }
        }
    }

    private function iconId(?string $type, ?string $name): ?int
    {
        if ($type === null || $name === null) {
            return null;
        }

        return Icon::firstOrCreate(
            ['icon_type' => $type, 'icon_name' => $name]
        )->id;
    }

    private function projectData(): array
    {
        return [
            [
                'slug' => 'project-dod',
                'title' => 'DoD Budgeting Application',
                'byline' => "SaaS budgeting platform supporting the Navy's Strategic Systems Programs.",
                'description' => '<p>At Pioneering Evolution, I worked on a budgeting platform used by the Department of Defense to plan and simulate complex financial scenarios. The system lets analysts manage detailed data, test "what-if" projections, and track approvals across multiple leadership levels.<br><br>I worked full stack across Laravel 11, PHP 8, MySQL 8, Vue 3, and TypeScript, contributing to both the backend architecture and the frontend experience. I was a key contributor to the app\'s migration from Vue 2 to Vue 3, a major overhaul that modernized the codebase and improved long-term maintainability. I also focused on performance-critical areas, reposts API endpoints and optimizing queries to cut response times and reduce memory usage.<br><br>Beyond code, I worked closely with QA and DevOps to ensure a solid product and paired with teammates for mentoring, reviews, and pair programming. The result was a faster, cleaner, and more resilient platform that helped users make smarter, data-driven budget decisions with confidence.</p>',
                'primary_image_src' => 'ssp/ssp-logo.png',
                'primary_image_title' => 'DoD Budgeting Application',
                'primary_image_alt' => 'DoD Budgeting Application Logo',
                'bg_image' => 'ssp/ssp-logo.png',
                'bg_position_x' => '-50px',
                'bg_position_y' => '-30px',
                'company' => 'Pioneering Evolution',
                'order' => 0,
                'key_takeaways' => [
                    'Full stack development with Laravel 11, PHP 8, MySQL 8, Vue 3, and TypeScript',
                    'Key contributor of migration from Vue 2 to Vue 3, modernizing the codebase',
                    'Optimized API endpoints and database queries for improved performance',
                    'Department of Defense platform for complex financial planning and simulations',
                ],
                'images' => [],
                'links' => [
                    ['title' => 'Additional Contract Info (sam.gov)', 'url' => 'https://sam.gov/opp/2256b7a820714afb89915925eed9b856/view'],
                ],
                'technologies' => [
                    ['name' => 'Laravel', 'icon_type' => 'fa', 'icon_name' => 'laravel', 'is_highlighted' => true],
                    ['name' => 'Vue.js', 'icon_type' => 'fa', 'icon_name' => 'vuejs', 'is_highlighted' => false],
                    ['name' => 'PHP', 'icon_type' => 'fa', 'icon_name' => 'php', 'is_highlighted' => true],
                    ['name' => 'MySQL', 'icon_type' => 'si', 'icon_name' => 'MySqlIcon', 'is_highlighted' => true],
                    ['name' => 'TypeScript', 'icon_type' => 'si', 'icon_name' => 'TypeScriptIcon', 'is_highlighted' => true],
                    ['name' => 'Vuex', 'icon_type' => 'fa', 'icon_name' => 'vuejs', 'is_highlighted' => false],
                    ['name' => 'Pinia', 'is_highlighted' => false],
                    ['name' => 'REST APIs', 'is_highlighted' => true],
                    ['name' => 'Node.js', 'icon_type' => 'fa', 'icon_name' => 'js', 'is_highlighted' => true],
                    ['name' => 'BootstrapVue', 'icon_type' => 'fa', 'icon_name' => 'vuejs', 'is_highlighted' => false],
                ],
                'tools' => [
                    ['name' => 'Vite'],
                    ['name' => 'Bitbucket'],
                    ['name' => 'Docker'],
                    ['name' => 'PEST'],
                    ['name' => 'Git'],
                    ['name' => 'NPM'],
                    ['name' => 'Yarn'],
                    ['name' => 'Composer'],
                ],
                'awards' => [],
            ],

            [
                'slug' => 'project-api',
                'title' => 'Enterprise API Gateway & Authentication',
                'byline' => 'Centralized API gateway with OAuth2 authentication, rate limiting, and request routing for multiple client applications',
                'description' => '<p>As a part of the Digital Flag Board implementation, authentication through LDAP was added so users could utilize their existing Active Directory accounts. Once that was up and running, it became clear a more unified way for additional internal apps to talk to each other was needed.<br><br> That work on authentication kick-started the groundwork for what became the Glen Raven API. I dove into research, even traveling to the "API World" conference.  I came up with a proposal for a secure, REST-based API with JWT authentication and Azure pipeline management. That effort set the stage for the Glen Raven API to connect systems, share data, and build future apps on a common foundation. The Glen Raven API allows several different applications to interface with each other (client/vendor orders, distribution, metrics, et al).</p>',
                'primary_image_src' => 'api/api-bg.png',
                'primary_image_title' => 'Enterprise API Gateway & Authentication',
                'primary_image_alt' => 'Enterprise API Gateway Dashboard',
                'bg_image' => 'api/api-bg.png',
                'bg_position_x' => '-30px',
                'bg_position_y' => '-30px',
                'company' => 'Glen Raven, Inc.',
                'order' => 1,
                'key_takeaways' => [
                    'Designed and proposed REST-based API architecture after extensive research',
                    'Implemented JWT authentication and Azure pipeline management',
                    'Integrated LDAP and Active Directory for enterprise authentication',
                    'Unified communication between multiple internal applications',
                ],
                'images' => [],
                'links' => [],
                'technologies' => [
                    ['name' => 'Lumen', 'is_highlighted' => true],
                    ['name' => 'PHP', 'icon_type' => 'fa', 'icon_name' => 'php', 'is_highlighted' => true],
                    ['name' => 'MySQL', 'icon_type' => 'si', 'icon_name' => 'MySqlIcon', 'is_highlighted' => true],
                    ['name' => 'LDAP', 'is_highlighted' => true],
                    ['name' => 'Active Directory', 'is_highlighted' => true],
                    ['name' => 'JWT', 'is_highlighted' => true],
                    ['name' => 'REST APIs', 'is_highlighted' => true],
                ],
                'tools' => [
                    ['name' => 'Github'],
                    ['name' => 'Azure Pipelines'],
                    ['name' => 'NPM'],
                    ['name' => 'Postman'],
                    ['name' => 'PHPUnit', 'icon_type' => 'fa', 'icon_name' => 'php'],
                ],
                'awards' => [],
            ],

            [
                'slug' => 'project-digital',
                'title' => 'Digital Flag Board',
                'byline' => 'Enterprise ticketing system that connects factory operators, technicians, and management in real time.',
                'description' => '<p>At Glen Raven, I led the development of the Digital Flag Board from inception to fruition.  The Digital Flag Board is an enterprise ticketing platform built for factory operations, connecting machine operators, technicians, and management in real time. Operators use it to flag issues directly from kiosks and mobile devices stationed across the factory floor, while technicians track, resolve, and close those tickets through a responsive, purpose-built interface.<br><br>I worked full stack across Laravel, PHP, MySQL, and HTML5/CSS3, Bootstrap, jQuery, and JavaScript on the frontend, building features that kept the platform stable under constant use.<br><br>The system ran as a dedicated web app on every kiosk and device, authenticated through Active Directory and LDAP for secure, role-based access.<br><br>The admin console allowed senior management to review cycle time metrics, run performance reports, manage users, and handle all CRUD operations through a clean, intuitive dashboard. My focus was creating a reliable, high-availability system that kept production flowing and reduced downtime across the plant.</p>',
                'primary_image_src' => 'flag-board/dashboard.png',
                'primary_image_title' => 'Digital Flag Board Dashboard',
                'primary_image_alt' => 'Digital Flag Board Main Dashboard',
                'bg_image' => 'flag-board/flag-board-bg.png',
                'bg_position_x' => '-10px',
                'bg_position_y' => '-135px',
                'company' => 'Glen Raven, Inc.',
                'order' => 2,
                'key_takeaways' => [
                    'Led development from inception to deployment',
                    'Real-time factory operations ticketing system',
                    'Deployed on kiosks and mobile devices across factory floor',
                    'High-availability system reducing production downtime',
                ],
                'images' => [
                    ['src' => 'flag-board/dashboard.png', 'title' => 'Main Dashboard'],
                    ['src' => 'flag-board/login.png', 'title' => 'Admin Login'],
                    ['src' => 'flag-board/reports.png', 'title' => 'Reports'],
                    ['src' => 'flag-board/new-request.png', 'title' => 'New Flag Request'],
                    ['src' => 'flag-board/edit-request.png', 'title' => 'Edit Flag Request'],
                    ['src' => 'flag-board/edit-request-validation.png', 'title' => 'Edit Flag Request Validation'],
                    ['src' => 'flag-board/kiosk-2.jpg', 'title' => 'Old paper-based Flag Board'],
                    ['src' => 'flag-board/kiosk-3.jpg', 'title' => 'Kiosk running the Flag Board on factory floor.'],
                ],
                'links' => [],
                'technologies' => [
                    ['name' => 'Laravel', 'icon_type' => 'fa', 'icon_name' => 'laravel', 'is_highlighted' => true],
                    ['name' => 'PHP', 'icon_type' => 'fa', 'icon_name' => 'php', 'is_highlighted' => true],
                    ['name' => 'MySQL', 'icon_type' => 'si', 'icon_name' => 'MySqlIcon', 'is_highlighted' => true],
                    ['name' => 'REST APIs', 'is_highlighted' => true],
                    ['name' => 'LDAP', 'is_highlighted' => true],
                    ['name' => 'Active Directory', 'is_highlighted' => true],
                    ['name' => 'JWT', 'is_highlighted' => true],
                    ['name' => 'JavaScript', 'icon_type' => 'fa', 'icon_name' => 'js', 'is_highlighted' => false],
                    ['name' => 'jQuery', 'is_highlighted' => false],
                    ['name' => 'Bootstrap', 'is_highlighted' => false],
                    ['name' => 'SCSS', 'icon_type' => 'fa', 'icon_name' => 'css3', 'is_highlighted' => false],
                    ['name' => 'Redis', 'is_highlighted' => false],
                ],
                'tools' => [
                    ['name' => 'Github'],
                    ['name' => 'Azure Pipelines'],
                    ['name' => 'NPM'],
                    ['name' => 'webpack'],
                    ['name' => 'PHPUnit', 'icon_type' => 'fa', 'icon_name' => 'php'],
                    ['name' => 'Composer'],
                ],
                'awards' => [],
            ],

            [
                'slug' => 'project-concert',
                'title' => 'Concert Web',
                'byline' => 'A secure, real-time dashboard for monitoring high-volume cash counting and sorting machines anywhere in the world.',
                'description' => '<p>Concert Web was the web interface for ARCA\'s flagship money counting and sorting machines, deployed in casinos, theme parks, and financial institutions handling high volumes of cash and coins. The machines ran on proprietary software, and Concert Web provided a secure, real-time dashboard that let companies monitor the exact status of machines and safes from anywhere in the world. Users could see precise counts, denominations, and transaction details in real time, giving them full visibility into cash operations.<br><br>I served as a software developer on the platform and led the integration of Fiserv APIs, enabling direct B2B interactions and expanding the system\'s capabilities for financial reporting and reconciliation. I worked across Laravel, PHP, MySQL, and Bootstrap to ensure real-time data flowed smoothly from machines to the web dashboard, while maintaining robust security and performance under heavy transactional loads.<br><br>My contributions helped turn complex, mission-critical cash operations into a streamlined, accessible platform that businesses could rely on for accurate monitoring and decision-making.</p>',
                'primary_image_src' => 'concert-web/concert-bg.png',
                'primary_image_title' => 'Concert Web Dashboard',
                'primary_image_alt' => 'Concert Web Cash Counting Dashboard',
                'bg_image' => 'concert-web/concert-bg.png',
                'bg_position_x' => '-10px',
                'bg_position_y' => '-40px',
                'company' => 'ARCA',
                'order' => 3,
                'key_takeaways' => [
                    'Real-time dashboard for cash counting machines in casinos, theme parks, and financial institutions',
                    'Led Fiserv API integration for B2B financial reporting',
                    'High-volume transactional system with real-time data synchronization',
                    'Mission-critical platform handling cash operations globally',
                ],
                'images' => [
                    ['src' => 'concert-web/concert-nav.png', 'title' => 'Administration'],
                    ['src' => 'concert-web/concert-admin.png', 'title' => 'Administration'],
                ],
                'links' => [],
                'technologies' => [
                    ['name' => 'Laravel', 'icon_type' => 'fa', 'icon_name' => 'laravel', 'is_highlighted' => true],
                    ['name' => 'PHP', 'icon_type' => 'fa', 'icon_name' => 'php', 'is_highlighted' => true],
                    ['name' => 'MySQL', 'icon_type' => 'si', 'icon_name' => 'MySqlIcon', 'is_highlighted' => true],
                    ['name' => 'JavaScript', 'icon_type' => 'fa', 'icon_name' => 'js', 'is_highlighted' => false],
                    ['name' => 'jQuery', 'is_highlighted' => true],
                    ['name' => 'Bootstrap', 'is_highlighted' => false],
                    ['name' => 'Redis', 'is_highlighted' => false],
                    ['name' => 'REST APIs', 'is_highlighted' => true],
                    ['name' => 'Third-party APIs', 'is_highlighted' => true],
                ],
                'tools' => [
                    ['name' => 'Jira'],
                    ['name' => 'Bitbucket'],
                    ['name' => 'Jenkins'],
                    ['name' => 'NPM'],
                    ['name' => 'PHPUnit', 'icon_type' => 'fa', 'icon_name' => 'php'],
                ],
                'awards' => [],
            ],

            [
                'slug' => 'project-sms',
                'title' => 'Enterprise SMS Notification Services',
                'byline' => 'Inclement weather and office closure notification system.',
                'description' => '<p><strong>Glen Raven 511 – SMS Notification Services</strong><br><br>At Glen Raven, I built an internal web application that lets HR and other teams send urgent updates to employees via SMS, covering weather alerts, closures, and other critical notifications. The system ensured messages reached everyone quickly and reliably, helping the company respond in real time to changing circumstances.<br><br>I built the platform using Laravel, PHP, MySQL, JavaScript, jQuery, and Bootstrap, and integrated Nexmo© for sending and receiving SMS messages. My focus was on creating a responsive, easy-to-use interface while maintaining robust backend logic and seamless third-party API communication. The result was a dependable, low-friction tool that kept the entire workforce informed and safe during urgent situations.</p>',
                'primary_image_src' => 'sms/sms-alert.jpg',
                'primary_image_title' => 'SMS Notification Services',
                'primary_image_alt' => 'SMS Alert Notification System',
                'bg_image' => 'sms/sms-alert.jpg',
                'bg_position_x' => '-140px',
                'bg_position_y' => '-50px',
                'company' => 'Glen Raven, Inc.',
                'order' => 4,
                'key_takeaways' => [
                    'SMS notification system for urgent employee communications',
                    'Integrated Nexmo API for reliable message delivery',
                    'Weather alerts and office closures broadcast in real time',
                    'Critical communication tool ensuring workforce safety',
                ],
                'images' => [],
                'links' => [
                    ['title' => 'Nexmo (now Vonage) - Communication APIs', 'url' => 'https://www.vonage.com/communications-apis/'],
                ],
                'technologies' => [
                    ['name' => 'Laravel', 'icon_type' => 'fa', 'icon_name' => 'laravel', 'is_highlighted' => true],
                    ['name' => 'PHP', 'icon_type' => 'fa', 'icon_name' => 'php', 'is_highlighted' => true],
                    ['name' => 'MySQL', 'icon_type' => 'si', 'icon_name' => 'MySqlIcon', 'is_highlighted' => true],
                    ['name' => 'JavaScript', 'icon_type' => 'fa', 'icon_name' => 'js', 'is_highlighted' => true],
                    ['name' => 'jQuery', 'is_highlighted' => true],
                    ['name' => 'Third-party APIs', 'is_highlighted' => true],
                    ['name' => 'Bootstrap', 'is_highlighted' => false],
                ],
                'tools' => [
                    ['name' => 'Github'],
                    ['name' => 'Jenkins'],
                    ['name' => 'Azure Pipelines'],
                ],
                'awards' => [],
            ],

            [
                'slug' => 'project-web',
                'title' => 'Web Request Dashboard',
                'byline' => 'Custom Jira-inspired dashboard that gives teams full visibility and control over tickets without extra licenses.',
                'description' => '<p>At Glen Raven, I built an internal web application that gave teams a full view of Jira Cloud tickets without needing full Jira accounts. As a cost-saving initiative, the company limited Jira licenses, so I recreated the Scrum board experience in a custom frontend while managing user–ticket relationships and roles locally. The interface supported drag-and-drop ticket management, icons, status tracking, and email notifications for ticket events.<br><br>I worked full stack using Laravel, PHP, MySQL, JavaScript, jQuery, and Bootstrap, integrating tightly with Jira Cloud\'s API. Users could create tickets, attach files, comment, and view all ticket details, while the backend handled syncing, data logic, and permissions through dedicated Jira API accounts. The system streamlined workflow, improved visibility across departments, and maintained the familiar Jira experience without incurring extra licensing costs.</p>',
                'primary_image_src' => 'web-requests/web-requests-bg.png',
                'primary_image_title' => 'Web Request Dashboard',
                'primary_image_alt' => 'Web Request Dashboard Interface',
                'bg_image' => 'web-requests/web-requests-bg.png',
                'bg_position_x' => '5px',
                'bg_position_y' => '5px',
                'company' => 'Glen Raven, Inc.',
                'order' => 5,
                'key_takeaways' => [
                    'Cost-saving Jira alternative eliminating need for full licenses',
                    'Custom Scrum board experience with drag-and-drop functionality',
                    'Full ticket management without Jira Cloud accounts',
                    'Streamlined workflow improving visibility across departments',
                ],
                'images' => [],
                'links' => [],
                'technologies' => [
                    ['name' => 'Laravel', 'icon_type' => 'fa', 'icon_name' => 'laravel', 'is_highlighted' => true],
                    ['name' => 'PHP', 'icon_type' => 'fa', 'icon_name' => 'php', 'is_highlighted' => true],
                    ['name' => 'MySQL', 'icon_type' => 'si', 'icon_name' => 'MySqlIcon', 'is_highlighted' => true],
                    ['name' => 'JavaScript', 'icon_type' => 'fa', 'icon_name' => 'js', 'is_highlighted' => false],
                    ['name' => 'AJAX', 'is_highlighted' => true],
                    ['name' => 'jQuery', 'is_highlighted' => true],
                    ['name' => 'Third-party APIs', 'is_highlighted' => true],
                    ['name' => 'Bootstrap', 'is_highlighted' => false],
                ],
                'tools' => [
                    ['name' => 'Jira'],
                    ['name' => 'Github'],
                    ['name' => 'Jenkins'],
                ],
                'awards' => [],
            ],

            [
                'slug' => 'project-manager',
                'title' => 'Project Manager Dashboard',
                'byline' => 'Centralized dashboard that streamlines project tracking, task management, and client service for product installations.',
                'description' => '<p>At Digital Insight, I developed the Project Management Dashboard, a web-based platform designed to streamline how Project Managers on the Automated Implementation Management team oversee product installations. Before the dashboard, PMs had to navigate multiple systems such as QuickBase, the intranet, and Pivotal, a cumbersome CRM, to access critical product details, SOPs, and progress tracking. This fragmented workflow made managing installations, tracking tasks, and logging time inefficient and prone to delays.<br><br>The PM Dashboard consolidated all key information into a single, dynamic interface. Built using PHP, MySQL, MSSQL (for Pivotal integration), XHTML, and JavaScript/AJAX, it delivered a responsive, desktop-like experience without page reloads. I focused on integrating Pivotal data via its database while enabling task-level dependencies, customizable due dates, simplified incident creation, and aggregated resources from QuickBase. Project Managers could now track each project from start to finish, manage tasks, and store project-specific notes—all from one location.<br><br>This solution allowed rapid adaptation to individual project needs, centralized workflow, and simplified access to information that previously required multiple systems. Administrators could configure default task durations and dependencies, while the frontend offered drag-and-drop task management and real-time updates. The architecture supported quick development and deployment of new features, and the platform could be replicated for other teams using Pivotal in different capacities.<br><br>Adoption of the PM Dashboard significantly improved efficiency and project tracking. Surveys showed that 67% of the team used it multiple times per day, while 100% agreed it simplified project management, saved time, and helped deliver better results to clients. Early data indicated a positive trend in customer satisfaction scores after deployment, demonstrating the platform\'s direct impact on operational effectiveness and client service.<br /><br /> * This project is covered by a Non-Disclosure Agreement (NDA), which prevents me from sharing the app or code publicly.</p>',
                'primary_image_src' => 'pm-dashboard/pm-db-1.png',
                'primary_image_title' => 'Project Manager Dashboard',
                'primary_image_alt' => 'Project Manager Dashboard Interface',
                'bg_image' => '/pm-dashboard/pm-db-1.png',
                'bg_position_x' => '0',
                'bg_position_y' => '0',
                'company' => 'Digital Insight (susidiary of Intuit, acquired by NCR)',
                'order' => 6,
                'key_takeaways' => [
                    'Consolidated multiple systems (QuickBase, Pivotal, intranet) into single dashboard',
                    '67% of team used it multiple times per day',
                    '100% agreed it simplified project management and saved time',
                    'Scott Cook Innovation Award Nominee at Intuit',
                ],
                'images' => [
                    ['src' => 'pm-dashboard/pm-db-2.png', 'title' => 'Project Details - Checklist'],
                    ['src' => 'pm-dashboard/pm-db-3.png', 'title' => 'Checklist Item Instructions'],
                    ['src' => 'pm-dashboard/pm-db-4.png', 'title' => 'Extended project details and health'],
                    ['src' => 'pm-dashboard/pm-db-5.png', 'title' => 'Client Tasks'],
                ],
                'links' => [
                    ['title' => 'Scott Cook Innovation Award Nominee', 'url' => '/projects/pm-dashboard-innovation-award-entry.pdf'],
                ],
                'technologies' => [
                    ['name' => 'PHP', 'icon_type' => 'fa', 'icon_name' => 'php', 'is_highlighted' => true],
                    ['name' => 'MySQL', 'icon_type' => 'si', 'icon_name' => 'MySqlIcon', 'is_highlighted' => true],
                    ['name' => 'MSSQL', 'is_highlighted' => true],
                    ['name' => 'JavaScript', 'icon_type' => 'fa', 'icon_name' => 'js', 'is_highlighted' => true],
                    ['name' => 'jQuery', 'is_highlighted' => true],
                    ['name' => 'AJAX', 'is_highlighted' => true],
                    ['name' => 'Third-party APIs', 'is_highlighted' => true],
                ],
                'tools' => [
                    ['name' => 'Pivotal'],
                    ['name' => 'QuickBase'],
                    ['name' => 'LAMP'],
                ],
                'awards' => [
                    'Scott Cook Innovation Award Nominee - Intuit',
                ],
            ],
        ];
    }
}
