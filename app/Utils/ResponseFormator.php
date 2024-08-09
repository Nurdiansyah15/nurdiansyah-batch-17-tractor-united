<?php

namespace App\Utils;

class ResponseFormator
{
    protected static $response =  [
        'status' =>  null,
        'message' => null,
        'data' => null,
    ];

    public static function create($status = null, $message = null, $data = null)
    {
        self::$response['status'] = $status;
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['status']);
    }
}
