<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

trait ApiResponseTrait
{

    private $errorCode = [
        Response::HTTP_BAD_REQUEST => 'Bad Request',
        Response::HTTP_UNAUTHORIZED => 'Unauthorized',
        Response::HTTP_FORBIDDEN => 'Forbidden',
        Response::HTTP_NOT_FOUND => 'Not Found',
        Response::HTTP_METHOD_NOT_ALLOWED => 'Method Not Allowed',
        Response::HTTP_UNPROCESSABLE_ENTITY => 'Unprocessable Entity',
        Response::HTTP_TOO_MANY_REQUESTS => 'Too Many Requests',
        Response::HTTP_INTERNAL_SERVER_ERROR => 'Internal Server Error',
    ];

    protected function responseSuccess($data = null, $code = 200)
    {

        $response = [
            "result" => [
                "isSuccess" => true,
                "statusCode" => $code
            ],
            "data" => $data
        ];

        return response()->json($response, $code)
            ->header('X-XSS-Protection', '1; mode=block');
    }

    protected function responseSuccessNoData($code = 200)
    {

        $response = [
            "result" => [
                "isSuccess" => true,
                "statusCode" => $code
            ]
        ];

        return response()->json($response, $code)
            ->header('X-XSS-Protection', '1; mode=block');
    }

    protected function responseError($code = 500, $message = null, $data = null)
    {

        $error_message = (isset($this->errorCode[$code]) && empty($message)) ?  $this->errorCode[$code] : $message;

        $response = [
            "result" => [
                "isSuccess" => false,
                "statusCode" => $code,
                "message" => $error_message
            ]
        ];

        if ($data) {
            $response["data"] = $data;
        }

        return response()->json($response, $code)
            ->header('X-XSS-Protection', '1; mode=block');
    }

    protected function responseInvalidParameters($error_message, $code = 400)
    {

        // $errors = (isset($this->errorCode[$code])) ?  $this->errorCode[$code] : 'Invalid Parameters';

        $response = [
            "result" => [
                "isSuccess" => false,
                "statusCode" => $code,
                "message" => $error_message,
                "errors" => 'Invalid Parameters'
            ]
        ];

        return response()->json($response, $code)
            ->header('X-XSS-Protection', '1; mode=block');
    }

    protected function responseErrorMessage($message = null, $code = 400)
    {

        $response = [
            "result" => [
                "isSuccess" => false,
                "statusCode" => $code,
                "message" => $message
            ]
        ];
        return response()->json($response, $code)
            ->header('X-XSS-Protection', '1; mode=block');
    }

    protected function responseValidateForm($dataValiadte, $rulesValiadte, $messageValiadte = [], $niceNames = [])
    {
        $errData = [
            "isSuccess" => true,
        ];

        $validator = Validator::make($dataValiadte, $rulesValiadte, $messageValiadte, $niceNames);
        if ($validator->fails()) {
            $errData["isSuccess"] = false;
            $messages = $validator->errors()->messages();
            foreach ($messages as $colKey => $msg) {
                $errData["errors"][$colKey] = $msg[0];
            }
        }

        $responseData["isSuccess"] = $errData["isSuccess"];

        if (!$errData["isSuccess"]) {
            $response = [
                "result" => [
                    "isSuccess" => false,
                    "statusCode" => 400,
                    "errorValidate" => true,
                    "errors" => $errData["errors"],
                ]
            ];
            $responseData["data"] = response()->json($response, 400)
                ->header('X-XSS-Protection', '1; mode=block');
        }

        return $responseData;
    }

}
