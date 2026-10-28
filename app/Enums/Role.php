<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Editor = 'editor';
    case Viewer = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'مدير النظام',
            self::Editor => 'محرر',
            self::Viewer => 'مشاهد',
        };
    }

    public function canEdit(): bool
    {
        return $this !== self::Viewer;
    }
}
