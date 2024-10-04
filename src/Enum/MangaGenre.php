<?php

// src/Enum/MangaGenre.php

namespace App\Enum;

enum MangaGenre: string
{
    case Action = 'action';
    case Adventure = 'adventure';
    case Bara = 'bara';
    case Biographical = 'biographical';
    case Comedy = 'comedy';
    case Crossover = 'crossover';
    case Drama = 'drama';
    case Ecchi = 'ecchi';
    case Erotic = 'erotic';
    case Fantastic = 'fantastic';
    case Fantasy = 'fantasy';
    case Furyo = 'furyo';
    case Gekiga = 'gekiga';
    case ShortStories = 'short_stories';
    case Historical = 'historical';
    case Horror = 'horror';
    case Isekai = 'isekai';
    case Mature = 'mature';
    case Mystery = 'mystery';
    case Nekketsu = 'nekketsu';
    case Psychological = 'psychological';
    case Romance = 'romance';
    case SchoolLife = 'school_life';
    case ScienceFantasy = 'science_fantasy';
    case ScienceFiction = 'science_fiction';
    case ShoujoAi = 'shoujo_ai';
    case ShonenAi = 'shonen_ai';
    case SliceOfLife = 'slice_of_life';
    case Supernatural = 'supernatural';
    case Thriller = 'thriller';
    case Tragic = 'tragic';
    case Yonkoma = 'yonkoma';

    public function getLabel(): string
    {
        return match ($this) {
            self::Action => 'Action',
            self::Adventure => 'Aventure',
            self::Bara => 'Bara',
            self::Biographical => 'Biographique',
            self::Comedy => 'Comédie',
            self::Crossover => 'Crossover',
            self::Drama => 'Drame',
            self::Ecchi => 'Ecchi',
            self::Erotic => 'Érotique',
            self::Fantastic => 'Fantastique',
            self::Fantasy => 'Fantasy',
            self::Furyo => 'Furyo',
            self::Gekiga => 'Gekiga',
            self::ShortStories => 'Histoires courtes',
            self::Historical => 'Historique',
            self::Horror => 'Horreur',
            self::Isekai => 'Isekai',
            self::Mature => 'Mature',
            self::Mystery => 'Mystère',
            self::Nekketsu => 'Nekketsu',
            self::Psychological => 'Psychologique',
            self::Romance => 'Romance',
            self::SchoolLife => 'School Life',
            self::ScienceFantasy => 'Science-Fantasy',
            self::ScienceFiction => 'Science-fiction',
            self::ShoujoAi => 'Shôjo-aï',
            self::ShonenAi => 'Shônen-aï',
            self::SliceOfLife => 'Slice of Life',
            self::Supernatural => 'Surnaturel',
            self::Thriller => 'Thriller',
            self::Tragic => 'Tragique',
            self::Yonkoma => 'Yonkoma',
        };
    }
}
