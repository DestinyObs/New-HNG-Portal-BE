<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'title' => 'How do I create an account?',
                'content' => 'Click on the "Sign Up" button at the top right corner and fill in your details to create an account.',
                'type' => 'talent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'How do I apply for a job?',
                'content' => 'Browse available jobs, click on the job you\'re interested in, and click the "Apply" button to submit your application.',
                'type' => 'talent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Can I edit my profile after creation?',
                'content' => 'Yes, you can edit your profile at any time by navigating to your profile settings.',
                'type' => 'talent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'How do I post a job listing?',
                'content' => 'After creating a company profile, go to your dashboard and click "Post a Job" to create a new job listing.',
                'type' => 'company',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'How do I verify my company?',
                'content' => 'Upload your company registration documents and official email proof. Our team will review and verify your company within 24-48 hours.',
                'type' => 'company',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'What payment methods are accepted?',
                'content' => 'We accept credit cards, debit cards, and bank transfers for premium features and job postings.',
                'type' => 'company',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'How can I contact support?',
                'content' => 'You can reach our support team through the contact form or email us at support@hnginternship.com',
                'type' => 'talent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Is my data secure?',
                'content' => 'Yes, we use industry-standard encryption and security measures to protect your personal information.',
                'type' => 'talent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::factory()->create(
                [
                    'title' => $faq['title'],
                    'content' => $faq['content'],
                    'type' => $faq['type'],
                ]
            );
        }
    }
}
