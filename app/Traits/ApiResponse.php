<?php

namespace App\Traits;


trait ApiResponse
{
    public function withSuccess($data)
    {
        return [
            'status' => 'success',
            'response' => $data,
        ];
    }

    public function withError($data)
    {
        return [
            'status' => 'error',
            'response' => $data
        ];
    }
}
