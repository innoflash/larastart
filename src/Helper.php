<?php

namespace InnoFlash\LaraStart;

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
     * Gets the limit set in the request or returns a default set in the config.
     *
     * @param Request $request
     * @return \Illuminate\Config\Repository|mixed
     */
    public static function getLimit(Request $request)
    {
        if ($request->has('limit')) return $request->limit;
        else return config('larastart.limit');
    }

    /**
     * Formats the dates to readable formats.
     *
     * @param $date
     * @return array
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

    /**
     * Gets the filename.
     *
     * @param string $fullname
     * @return string
     */
    public static function getFileName(string $fullname): string
    {
        $newName = \str_replace(self::$strRlc, '/', $fullname);
        $pieces = \explode('/', $newName);
        return $pieces[sizeof($pieces) - 1];
    }
}
