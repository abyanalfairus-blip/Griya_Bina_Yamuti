<?php

namespace App\Support;

use App\Models\ProgramItem;
use App\Models\Slide;

/**
 * Penyedia data program untuk beranda dan halaman Program.
 * Semua isi dikelola dari panel admin (menu "Program Yayasan" dan "Slider foto").
 */
class Pillars
{
    public static function headline(): string
    {
        return (string) setting('program_headline');
    }

    public static function intro(): array
    {
        return array_values(array_filter([setting('program_intro_1'), setting('program_intro_2')]));
    }

    /** Catatan singkat di bawah judul tiap kelompok. */
    private const NOTES = [
        'gerakan' => 'Gerakan ini menjadi pintu partisipasi masyarakat dalam mendukung berbagai kegiatan YGBY. Setiap penghimpunan dana memiliki tujuan dan pelaporan yang jelas.',
    ];

    private const LETTERS = ['utama' => 'A', 'gerakan' => 'B', 'wakaf' => 'C', 'penguatan' => ''];

    /** Foto slider aktif, berurutan. */
    public static function slides(): array
    {
        try {
            return Slide::active()->orderBy('sort_order')->orderBy('id')->get()->map(fn ($s) => [
                'img' => $s->image,
                'pos' => $s->image_pos ?: 'center',
                'program' => $s->program_slug,
                'caption' => $s->caption,
                'alt' => $s->alt ?: $s->caption,
            ])->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Program aktif yang dikelompokkan. Kelompok kosong tidak ditampilkan.
     *
     * @return array<int, array{key:string,letter:string,title:string,note:?string,programs:array}>
     */
    public static function groups(): array
    {
        try {
            $items = ProgramItem::active()->orderBy('sort_order')->orderBy('id')->get();
        } catch (\Throwable $e) {
            return [];
        }

        $groups = [];
        $no = 0;
        foreach (ProgramItem::GROUPS as $key => $title) {
            $programs = [];
            foreach ($items->where('group', $key) as $p) {
                $programs[] = [
                    'no' => ++$no,
                    'slug' => $p->slug,
                    'icon' => $p->icon ?: 'apps',
                    'title' => $p->title,
                    'focus' => $p->focus,
                    'tagline' => $p->tagline,
                    'desc' => $p->description,
                    'list_title' => $p->list_title,
                    'list' => $p->list,
                    'img' => $p->image,
                    'pos' => $p->image_pos ?: 'center',
                    'alt' => $p->image_alt ?: $p->title,
                    'type' => $p->donation_type ?: 'donasi',
                ];
            }
            if ($programs) {
                $groups[] = ['key' => $key, 'letter' => self::LETTERS[$key] ?? '', 'title' => $title, 'note' => self::NOTES[$key] ?? null, 'programs' => $programs];
            }
        }

        return $groups;
    }
}
