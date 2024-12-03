<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;

/**
 * ApiResponse(success:true|false,data?,message?,error?)
 */
class ApiResponse
{

    //200 with data
    static function ofData($data, $message = null, $extra = [])
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $data,
            ...$extra
        ], Response::HTTP_OK);
    }

    //200 with data
    static function ofFailedData($data, $message = null)
    {
        return response([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], Response::HTTP_OK);
    }


    //200 with message, no data
    static function ofMessage($message)
    {
        return response([
            'success' => true,
            'message' => $message
        ], Response::HTTP_OK);
    }

    static function ofPaginatedData($data, $extra = [])
    {
        $meta = [
            'currentPage' => $data->currentPage(),
            'hasMorePages' => $data->hasMorePages(),
            'lastPage' => $data->lastPage(),
            'nextPageUrl' => $data->nextPageUrl(),
            'perPage' => $data->perPage(),
            'previousPageUrl' => $data->previousPageUrl(),
            'total' => $data->total(),
            'url' => $data->path()
        ];

        $responseData = array_merge(['success' => true, 'message' => ""], $meta, ['data' => $data], $extra);
        return response($responseData, 200);
    }

    //401
    static function unauthorized($message = "Unauthorized")
    {
        return response([
            'success' => false,
            'message' => $message
        ], Response::HTTP_FORBIDDEN);
    }

    //400
    static function ofClientError($message, $errors = null, $code = 400)
    {
        return response([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code ?? Response::HTTP_BAD_REQUEST);
    }

    //404
    static function notFound($message = 'Not found')
    {
        return response([
            'success' => false,
            'message' => $message
        ], Response::HTTP_NOT_FOUND);
    }

    //500
    static function ofInternalServerError($message)
    {
        return response([
            'success' => false,
            'message' => $message
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    //403
    static function ofForbidden()
    {
        return response([
            'success' => false,
            'message' => 'Forbidden'
        ], Response::HTTP_FORBIDDEN);
    }
}
