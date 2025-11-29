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
            'JavaScript',
            'TypeScript',
            'Python',
            'Java',
            'PHP',
            'Ruby',
            'Go',
            'Rust',
            'C++',
            'C#',
            'React',
            // 'Vue.js',
            // 'Angular',
            // 'Next.js',
            // 'Svelte',
            // 'Node.js',
            // 'Express.js',
            // 'Django',
            // 'Flask',
            // 'Laravel',
            // 'Ruby on Rails',
            // 'Spring Boot',
            // 'React Native',
            // 'Flutter',
            // 'Swift',
            // 'Kotlin',
            // 'Android',
            // 'iOS',
            // 'PostgreSQL',
            // 'MySQL',
            // 'MongoDB',
            // 'Redis',
            // 'ElasticSearch',
            // 'Docker',
            // 'Kubernetes',
            // 'AWS',
            // 'Azure',
            // 'Google Cloud',
            // 'Terraform',
            // 'Jenkins',
            // 'Git',
            // 'GitHub',
            // 'GitLab',
            // 'CI/CD',
            // 'Figma', 'Adobe XD', 'Sketch', 'UI/UX Design', 'Wireframing', 'Prototyping',
            // 'Machine Learning', 'TensorFlow', 'PyTorch', 'Data Analysis', 'SQL', 'Tableau',
            // 'Agile', 'Scrum', 'Jira', 'Project Management',
            // 'REST API', 'GraphQL', 'Microservices', 'System Design',
            // 'HTML', 'CSS', 'Tailwind CSS', 'Bootstrap', 'SASS',
            // 'Testing', 'Jest', 'Cypress', 'Selenium', 'Unit Testing',
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