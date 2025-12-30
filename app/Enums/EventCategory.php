<?php

namespace App\Enums;

enum EventCategory: string
{
    case COMPETITION = 'competition';
    case SEMINAR = 'seminar';
    case COMMUNITY = 'community';
    case QUALIFIER = 'qualifier';

    public function label(): string
    {
        return match ($this) {
            self::COMPETITION => __('text.Competition'),
            self::SEMINAR => __('text.Seminar / Workshop'),
            self::COMMUNITY => __('text.Community meetup'),
            self::QUALIFIER => __('text.Qualifier'),
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
