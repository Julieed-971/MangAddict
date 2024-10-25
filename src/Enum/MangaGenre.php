<?php

// src/Enum/MangaGenre.php

namespace App\Enum;

enum MangaGenre: string
{
    case Action = 'action';
    case Aventure = 'aventure';
    case Bara = 'bara';
    case Biographique = 'biographique';
    case Comédie = 'comédie';
    case Crossover = 'crossover';
    case Drame = 'drame';
    case Ecchi = 'ecchi';
    case Erotique = 'erotique';
    case Fantastique = 'fantastique';
    case Fantasy = 'fantasy';
    case Furyo = 'furyo';
    case Gekiga = 'gekiga';
    case HistoiresCourtes = 'histoires courtes';
    case Historique = 'historique';
    case Horreur = 'horreur';
    case Isekai = 'isekai';
    case Mature = 'mature';
    case Mystère = 'mystère';
    case Nekketsu = 'nekketsu';
    case Psychologique = 'psychologique';
    case Romance = 'romance';
    case SchoolLife = 'school_life';
    case ScienceFantasy = 'science_fantasy';
    case ScienceFiction = 'science_fiction';
    case ShoujoAi = 'shoujo_ai';
    case ShonenAi = 'shonen_ai';
    case SliceOfLife = 'slice_of_life';
    case Surnaturel = 'surnaturel';
    case Thriller = 'thriller';
    case Tragique = 'tragique';
    case Yonkoma = 'yonkoma';

    public function getLabel(): string
    {
        return match ($this) {
            self::Action => 'Action',
            self::Aventure => 'Aventure',
            self::Bara => 'Bara',
            self::Biographique => 'Biographique',
            self::Comédie => 'Comédie',
            self::Crossover => 'Crossover',
            self::Drame => 'Drame',
            self::Ecchi => 'Ecchi',
            self::Erotique => 'Érotique',
            self::Fantastique => 'Fantastique',
            self::Fantasy => 'Fantasy',
            self::Furyo => 'Furyo',
            self::Gekiga => 'Gekiga',
            self::HistoiresCourtes => 'Histoires courtes',
            self::Historique => 'Historique',
            self::Horreur => 'Horreur',
            self::Isekai => 'Isekai',
            self::Mature => 'Mature',
            self::Mystère => 'Mystère',
            self::Nekketsu => 'Nekketsu',
            self::Psychologique => 'Psychologique',
            self::Romance => 'Romance',
            self::SchoolLife => 'School Life',
            self::ScienceFantasy => 'Science-Fantasy',
            self::ScienceFiction => 'Science-fiction',
            self::ShoujoAi => 'Shôjo-aï',
            self::ShonenAi => 'Shônen-aï',
            self::SliceOfLife => 'Slice of Life',
            self::Surnaturel => 'Surnaturel',
            self::Thriller => 'Thriller',
            self::Tragique => 'Tragique',
            self::Yonkoma => 'Yonkoma',
        };
    }
}
