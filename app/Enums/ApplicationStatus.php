<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case PENDING = 'pending';
    case SHORTLISTED = 'shortlisted';
    case REJECTED = 'rejected';
    case INTERVIEW = 'interview';
    case OFFER = 'offer';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::SHORTLISTED => 'Shortlisted',
            self::REJECTED => 'Rejected',
            self::INTERVIEW => 'Interview',
            self::OFFER => 'Offer',
        };
    }
}