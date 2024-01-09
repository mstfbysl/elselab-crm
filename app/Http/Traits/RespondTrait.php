<?php

namespace App\Http\Traits;

trait RespondTrait
{
    protected function respondSuccess(mixed $data = [], string $message = '', int $status = 200)
    {
        return response([
            'status' => true,
            'message' => __('responses.' . $message),
            'data' => $data
        ], $status);
    }

    protected function respondSuccessDatatable($data = [], $draw = 1, $recordsTotal = 0, $hidden_columns = [])
    {
        return response([
            'status' => true,
            'message' => 'Success',
            'data' => $data,
            'draw' => $draw,
            'recordsFiltered' => $recordsTotal,
            'recordsTotal' => $recordsTotal,
            'hidden_columns' => $hidden_columns
        ], 200);
    }

    protected function respondFail($message = null, $status = 422)
    {
        return response([
            'status' => false,
            'message' => __('responses.' . $message)
        ], $status);
    }
}