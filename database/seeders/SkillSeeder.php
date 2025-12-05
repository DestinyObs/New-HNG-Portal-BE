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
            'Ruby',
            'Rust',
            'React',
            'C/C++',
            "Bash",
            "C++",
            "C",
            "C#",
            ".NET",
            "Java",
            "JavaScript",
            "Kotlin",
            "Mean",
            "Perl",
            "PHP",
            "Python",
            "Ruby",
            "SQL",
            "TypeScript",
            "Asana",
            "Firefox",
            "Google Suite",
            "Jira",
            "Monday",
            "Photoshop",
            "Slack",
            "Skype",
            "Smartsheets",
            "Trello",
            "Zapier",
            "Search engine optimization",
            "Access",
            "Asana",
            "Basecamp",
            "Calendly",
            "ClickUp",
            "CRM",
            "SAP",
            "Oracle",
            "Google Workspace",
            "Microsoft Teams",
            "OneNote",
            "Evernote",
            "Salesforce",
            "Slack",
            "Todoist",
            "Toggl",
            "Trello",
            "Zapier",
            "AJAX",
            "ASP.NET",
            "CSS",
            "Go",
            "HTML",
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