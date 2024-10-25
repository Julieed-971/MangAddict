<?php

namespace App\Enum;

enum MangaType: string
{
    case AnimeComics = 'anime_comics';
    case Anthologie = 'anthologie';
    case BDComics = 'bd_comics';
    case GlobalManga = 'global_manga';
    case Hentai = 'hentai';
    case Josei = 'josei';
    case Kodomo = 'kodomo';
    case Manhua = 'manhua';
    case Manhwa = 'manhwa';
    case Parodique = 'parodique';
    case Seinen = 'seinen';
    case Shojo = 'shojo';
    case Shonen = 'shonen';
    case Yaoi = 'yaoi';
    case Yuri = 'yuri';

    public function getLabel(): string
    {
        return match ($this) {
            self::AnimeComics => 'Anime Comics',
            self::Anthologie => 'Anthologie',
            self::BDComics => 'BD / Comics',
            self::GlobalManga => 'Global Manga',
            self::Hentai => 'Hentai',
            self::Josei => 'Josei',
            self::Kodomo => 'Kodomo',
            self::Manhua => 'Manhua',
            self::Manhwa => 'Manhwa',
            self::Parodique => 'Parodique',
            self::Seinen => 'Seinen',
            self::Shojo => 'Shojo',
            self::Shonen => 'Shonen',
            self::Yaoi => 'Yaoi',
            self::Yuri => 'Yuri',
        };
    }
}

