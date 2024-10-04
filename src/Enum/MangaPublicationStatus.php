<?php

namespace App\Enum;

enum MangaPublicationStatus: string
{
    case Ongoing = 'ongoing';
    case Finished = 'finished';
    case Pending = 'pending';
    case Abandoned = 'abandoned';
    case Upcoming = 'upcoming';
    case PrePublication = 'pre_publication';

    public function getLabel(): string
    {
        return match ($this) {
            self::Ongoing => 'En cours',
            self::Finished => 'Terminé',
            self::Pending => 'En attente',
            self::Abandoned => 'Abandonné',
            self::Upcoming => 'À paraître',
            self::PrePublication => 'En cours de prépublication',
        };
    }
}
