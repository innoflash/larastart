<?php

namespace InnoFlash\LaraStart\Http;

use Illuminate\Http\Request;

class Helper
{
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
            'time' => $date->format('H:i:s')
        ];
    }

    public static function getFileName(string $fullname): string
    {
        $newName = \str_replace(['\\', '/', '//'], '/', $fullname);
        $pieces = \explode('/', $newName);
        return $pieces[sizeof($pieces) - 1];
    }
}
