<?php

namespace InnoFlash\LaraStart\Http;

use Carbon\Carbon;
use Illuminate\Http\Request;

class Helper
{

    public static $strRlc = [
        '/',
        '//',
        '\\',
        '\\\\',
    ];

    /**
     * Gets the limit set in the request or returns a default set in the config
     */
    public static function getLimit(Request $request)
    {
        if ($request->has('limit')) return $request->limit;
        else return config('larastart.limit');
    }

    /**
     * Formats the dates to readable formats
     */
    public static function getDates($date)
    {
        if (!$date instanceof Carbon)
            $date = Carbon::parse($date);
        return [
            'approx' => $date->diffForHumans(),
            'formatted' => $date->format('D d M Y'),
            'exact' => $date,
            'time' => $date->format('H:i')
        ];
    }

    public static function getFileName(string $fullname): string
    {
        $newName = \str_replace(self::$strRlc, '/', $fullname);
        $pieces = \explode('/', $newName);
        return $pieces[sizeof($pieces) - 1];
    }

    public static function getDirName(string $fullname, bool $includeSlash = false): string
    {
        $newName = \str_replace(self::$strRlc, '/', $fullname);
        $pieces = \explode('/', $newName);
        array_pop($pieces);
        if (sizeof($pieces)) {
            if ($includeSlash) return '\\' . implode('\\', $pieces);
            else return implode('\\', $pieces);
        } else return '';
    }

    public static function getModelNamespace(string $modelName): string
    {
        return 'App\\' . \str_replace(self::$strRlc, '\\', $modelName);
    }
}
