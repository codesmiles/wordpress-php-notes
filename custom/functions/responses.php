<?php

/*
|--------------------------------------------------------------------------
| error response
|--------------------------------------------------------------------------
*/
function format_error_response($error)
{
    $status = $error->get_error_code();
    $message = $error->get_error_message();
    // Set HTTP status code based on the error code
    switch ($status) {
        case 400:
            status_header(400);
            break;
        case 401:
            status_header(401);
            break;
        case 404:
            status_header(404);
            break;
        case 500:
            status_header(500);
            break;
        default:
            status_header(500);
            break;
    }

    $response = array(
        'error' => true,
        'message' => $message,
        'data' => [
            'status' => $status,
        ],
    );

    return $response;
}

/*
|--------------------------------------------------------------------------
| success response
|--------------------------------------------------------------------------
*/
function sendResponseMessage($payload)
{

    return [
        'error' => $payload["error"],
        'message' => $payload["message"],
        'data' => $payload["data"] ?? []
    ];

}



function sendResponse(array $payload): array
{
    // send the response
    return [
        "error" => $payload["error"] ?? null,
        "message" => $payload["message"] ?? null,
        "data" => $payload["data"] ?? [],
    ];
}

 ResponseHelper.php
 routes
 AuthRoute.php
