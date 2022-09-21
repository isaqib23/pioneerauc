<?php

function throwError($code, $message)
{
    header("content-type: application/json");
    http_response_code($code);
    $errorMsg = json_encode(['error' => ['status' => $code, 'message' => $message]]);
    echo $errorMsg;
    exit;
}

function getAuthorizationHeader()
{
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

function getBearerToken()
{
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    throwError(ATHORIZATION_HEADER_NOT_FOUND, 'Access Token Not found');
}

function validateToken()
{
    // $db = connectToDatabase();
    // $user = new Users($db);
    try {
        $token = getBearerToken();
        $payload = Jwt::decode($token, SECRETE_KEY, ['HS256']);
        $CI = get_instance();
        $CI->load->model('user/Users_model', 'users_model');
        $email_check = $CI->users_model->check_user_email($payload->email);
        $user_row = $CI->users_model->get_user_by_id($payload->user_id);

        if ($email_check == false) {
            returnResponse(INVALID_USER_PASS, "This user is not found in our database.");
        }

        if ($user_row[0]['status'] != 1) {
            returnResponse(USER_NOT_ACTIVE, "This user may be decactived. Please contact to admin.");
        }

        return $payload->user_id;

    } catch (Exception $e) {

        throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
    }
}

function validateToken2()
{
    // $db = connectToDatabase();
    // $user = new Users($db);
    try {
        $token = getBearerToken2();

        if ($token) {

            $payload = Jwt::decode($token, SECRETE_KEY, ['HS256']);
            $CI = get_instance();
            $CI->load->model('user/Users_model', 'users_model');
            $email_check = $CI->users_model->check_user_email($payload->email);
            $user_row = $CI->users_model->get_user_by_id($payload->user_id);

            if ($email_check == false) {
                return "";
            }

            if ($user_row[0]['status'] != 1) {
                return "";
            } else {
                return $payload->user_id;
            }
        } else {
            return "";
        }


    } catch (Exception $e) {

        throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
    }
}

function getBearerToken2()
{
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return [];
}

function getPayload()
{

    try {
        $token = getBearerToken();
        $payload = Jwt::decode($token, SECRETE_KEY, ['HS256']);
        return $payload;

    } catch (Exception $e) {
        throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
    }
}

function returnResponse($code, $data)
{
    header("content-type: application/json");
    $response = json_encode(['response' => ['status' => $code, "result" => $data]]);
    echo $response;
    exit;
}

function dynamic_response($response_code)
{
    switch ($response_code) {
        case '200':
            $message = 'Success! authorized request.';
            break;
        case '201':
            $message = 'Successfully created.';
            break;
        case '422':
            $message = 'Unable to create.';
            break;
        case '400':
            $message = 'Unable to Process Request. Data is incomplete.';
            break;
        case '401':
            $message = 'Unauthorized request, User is not Active/ Email or password is not valid.';
            break;
        case '402':
            $message = 'Payment Required, Unpaid user.';
            break;
        case '404':
            $message = 'No Such Method found.';
            break;
        case '405':
            $message = 'Unauthorized request, Data Not found.';
            break;
        case '406':
            $message = 'Unable to Process Request. Company id is not valid.';
            break;
        case '409':
            $message = 'Email Already Exists.';
            break;
        case '451':
            $message = 'Phone Number Already Exists.';
            break;

        default:
            $message = 'Unauthorized request';
            break;
    }

    return $message;
}

// uses regex that accepts any word character or hyphen in last name
function split_name($name)
{
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim(preg_replace('#' . $last_name . '#', '', $name));
    return array($first_name, $last_name);
}

function generate_code($user_id, $path, $user_details = array())
{
    // how to save PNG codes to server
    $tempDir = FCPATH . $path;
    $tempDir_url = base_url() . "uploads/qrcodes/";

    $codeContents = json_encode($user_details);

    // we need to generate filename somehow,
    // with md5 or with database ID used to obtains $codeContents...
    $fileName = $user_id . '_' . md5($codeContents) . '.png';

    $pngAbsoluteFilePath = $tempDir . $fileName;
    $urlRelativeFilePath = $tempDir_url . $fileName;

    // generating
    if (!file_exists($pngAbsoluteFilePath)) {
        QRcode::png($codeContents, $pngAbsoluteFilePath);
        return $fileName;
    } else {
        return $fileName;
    }

}


// custome error handler to save error in error log table
function errorHandler($errorType, $errorString, $errorFile, $errorLine)
{
    http_response_code(400);
    $error_list = array(
        'status' => FALSE,
        'error_string' => $errorString,
        'errorType' => $errorType,
        'errorFile' => $errorFile,
        'errorLine' => $errorLine,
        'message' => "Bad Request Found",
        'data' => NULL
    );
    echo json_encode($error_list);
    die();
}