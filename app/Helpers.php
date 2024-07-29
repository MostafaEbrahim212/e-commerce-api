<?php
namespace App\Helpers;

class ApiResponseHelper
{
    public static function resData($data = [], $message = '', $status = 200)
    {
        $statusType = self::getStatusType($status);

        // Check if the data contains errors
        $errors = isset($data['errors']) ? $data['errors'] : null;
        $result = isset($data['result']) ? $data['result'] : $data;

        // Prepare the response
        $response = [
            'status_code' => $status,
            'status_type' => $statusType,
            'message' => $message,
            'errors' => $errors ? $errors : null,
            'result' => $errors ? null : $result,
        ];


        return response()->json($response, $status);
    }

    private static function getStatusType($status)
    {
        if ($status >= 100 && $status < 200) {
            return 'informational';
        } elseif ($status >= 200 && $status < 300) {
            return 'success';
        } elseif ($status >= 300 && $status < 400) {
            return 'redirection';
        } elseif (
            $status >= 400 &&
            $status < 500
        ) {
            return 'client_error';
        } elseif ($status >= 500 && $status < 600) {
            return 'server_error';
        } else {
            return 'unknown';
        }
    }
}
