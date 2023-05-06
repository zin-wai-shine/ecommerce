<?php
namespace App\Traits;

trait ResponseJson
{
    public function responseJson( $responses,int $statusCode=200,array $headers=[],int $options=0)

    {
        return response()->json($responses,$statusCode,$headers,$options);
    }

    public function paginateJson($data,int $statusCode=200,array $headers=[],int $options=0)
    {
        return response()->json([
            'data' => $data,
            'meta' => [
                'total' => $data->total(),
                'currentPage' => $data->currentPage(),
                'lastPage' => $data->lastPage(),
                'perPage' => $data->perPage(),
            ],
            'links' => [
                'first' => $data->url(1),
                'last' => $data->url($data->lastPage()),
                'prev' => $data->previousPageUrl(),
                'next' => $data->nextPageUrl(),
            ],
        ]);
    }
}
