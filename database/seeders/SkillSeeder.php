<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
    // Languages & Programming
    'Rust',
    'React',
    'C',
    'C++',
    'C/C++',
    'C#',
    '.NET',
    'Java',
    'JavaScript',
    'Kotlin',
    'Mean',
    'Perl',
    'PHP',
    'Python',
    'Ruby',
    'SQL',
    'TypeScript',
    'Go',
    'Objective-C',
    'R',
    'Swift',
    'XML',
    'HTML',
    'CSS',
    'AJAX',
    'ASP.NET',
    'Algorithms',
    'Applications',
    'Coding',

    // Tools & Software
    'Bash',
    'Firefox',
    'Google Suite',
    'Google Workspace',
    'Jira',
    'Monday',
    'Monday.com',
    'Photoshop',
    'Slack',
    'Skype',
    'Smartsheets',
    'Access',
    'Asana',
    'Basecamp',
    'Calendly',
    'ClickUp',
    'CRM',
    'SAP',
    'Oracle',
    'Microsoft Teams',
    'OneNote',
    'Evernote',
    'Salesforce',
    'Pardot',
    'Todoist',
    'Toggl',
    'Trello',
    'Zapier',
    'Blackmagic Resolve',
    'Corel Draw',
    'Free Hand',
    'Illustrator',
    'Sketch',
    'InDesign',
    'WordPress',
    'Yoast',
    'Power BI',

    // Data, Analytics & BI Tools
    'Search engine optimization',
    'Google Analytics',
    'Hootsuite',
    'Twitter',
    'Apache Spark',
    'Datapine',
    'Erwin Data Modeller',
    'Excel',
    'Highcharts',
    'Qualtrics',
    'R-Studio',
    'Rapidminer',
    'SAS',
    'Forecasting',
    'Talend',

    // UX / UI / Design
    'Prototyping',
    'Rapid Prototyping',
    'Responsive design',
    'UX design',
    'UX research',
    'UX Writing',
    'UI Design',
    'Wireframing',
    'Video creation',
    'Figma',
    'Brand Identity',

    // Marketing
    'A/B testing',
    'Automation',
    'Email marketing',
    'Facebook',
    'Instagram',
    'Linkedin',
    'Marketing Strategy',
    'Marketing analytics tools',
    'PPC ads',
    'Content Writing',

    // Development / Engineering Skills
    'Configuration',
    'Debugging',
    'Design',
    'Documentation',
    'Implementation',
    'iOS/Android',
    'Modelling',
    'Security',
    'Testing',
    'QA testing',

    // Product & Project Management
    'Agile methodology',
    'SCRUM methodology',
    'Delegation',
    'Product strategy',
    'Product Roadmaps',
    'Product lifecycle management',
    'Project planning',
    'Task management',
    'Requirements gathering',
    'Requirement gathering',
    'Prioritisation',
    'Prioritization',
    'Risk management',
    'Scheduling',
    'Mentoring & training',
    'Budget planning',
    'Powerpoint',
    'Keynote',
    'User modelling',
];


        // Custom slug mapping for special cases
        $customSlugs = [
            'C++' => 'cpp',
            'C#' => 'csharp',
            'UI/UX Design' => 'ui-ux-design',
            'CI/CD' => 'ci-cd',
        ];

        foreach ($skills as $skill) {
            $slug = $customSlugs[$skill] ?? Str::slug($skill);

            Skill::create([
                'slug' => $slug,
                'name' => $skill,
            ]);
        }
    }
}