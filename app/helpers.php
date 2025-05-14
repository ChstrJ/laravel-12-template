<?php

function responseOne($result, $status = 200)
{
    return response()->json([
        'data' => $result
    ], $status);
}