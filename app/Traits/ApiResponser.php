<?php

namespace App\Traits;
use Illuminate\Http\Response;

trait ApiResponser
{

    public function SuccessResponse($data, $code = Response::HTTP_OK)
    {
        return response()->json(['data' => $data], $code);
    }

    public function ErrorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

}
