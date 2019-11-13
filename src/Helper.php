<?php

namespace InnoFlash\LaraStart\Http;

use Illuminate\Http\Request;

class Helper
{
    public static function getLimit(Request $request)
    {
        if ($request->has('limit')) return $request->limit;
        else return config('larastart.limit');
    }

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
}
