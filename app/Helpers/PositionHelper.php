<?php

namespace App\Helpers;

class PositionHelper
{
    /**
     * Convert a full position title to its acronym.
     *
     * Examples:
     *   "Municipal Agrarian Reform Program Officer (MARPO)" → "MARPO"
     *   "Senior Agrarian Reform Program Officer" → "SARPO"
     *   "Agrarian Reform Program Technologist (ARPT)" → "ARPT"
     *   "OIC MARPO" → "OIC MARPO" (already an acronym, no change)
     */
    public static function toAcronym(?string $position): string
    {
        if (empty(trim($position ?? ''))) {
            return '';
        }

        $position = trim($position);

        // If it already has an acronym in parentheses, extract it
        if (preg_match('/\(([A-Z]{2,})\)/', $position, $matches)) {
            return $matches[1];
        }

        // If it's already short / all caps words (e.g. "OIC MARPO", "SARPO"), return as-is
        $words = preg_split('/\s+/', $position);
        if (count($words) <= 3 && self::isAcronym($position)) {
            return $position;
        }

        // Generate acronym from first letters of each significant word
        return self::generateAcronym($words);
    }

    private static function isAcronym(string $text): bool
    {
        $words = preg_split('/\s+/', trim($text));
        foreach ($words as $word) {
            if (!preg_match('/^[A-Z]{2,}$/', $word) && !in_array($word, ['OIC', 'II', 'III', 'IV', 'V', 'VI'])) {
                return false;
            }
        }
        return true;
    }

    private static function generateAcronym(array $words): string
    {
        $skip = ['of', 'the', 'and', 'in', 'a', 'an', 'for', 'to', 'de'];
        $acronym = '';

        foreach ($words as $word) {
            if (in_array(strtolower($word), $skip)) {
                continue;
            }
            // Handle Roman numerals at the end (e.g. "Officer II" → append " II")
            if (preg_match('/^[IVXLCDM]+$/', $word)) {
                $acronym .= ' ' . $word;
                continue;
            }
            $acronym .= strtoupper($word[0]);
        }

        return trim($acronym);
    }
}
