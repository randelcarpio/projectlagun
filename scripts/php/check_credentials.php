<?php
include 'db_connect.php';

$email = $_POST['email'];
$password = $_POST['password'];

// sanitize inputs
$email = mysqli_real_escape_string($conn, $email);
$password = mysqli_real_escape_string($conn, $password);

// check credentials
$sql = "SELECT * FROM tbl_users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

$response = array();

if (mysqli_num_rows($result) > 0) {
    // email exists
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['user_id'];

    // password is correct
    if (password_verify($password, $row['password_hash'])) {
        // Check if account is disabled
        if ($row['account_status'] == 'disabled') {
            $response['status'] = 'error';
            $response['message'] = 'Your account has been disabled. Please contact support.';
        } else {
            // store user ID and access level in cookies
            setcookie('user_id', $user_id, time() + (86400 * 30), '/'); // 86400 = 1 day
            setcookie('access_level', $row['access_level'], time() + (86400 * 30), '/'); // 86400 = 1 day

            // Debug: Check if cookies are set
            if(isset($_COOKIE['user_id']) && isset($_COOKIE['access_level'])) {
                $response['debug'] = 'Cookies are set: user_id = ' . $_COOKIE['user_id'] . ', access_level = ' . $_COOKIE['access_level'];
            } else {
                $response['debug'] = 'Cookies are not set.';
            }

            // check last 5 login attempts
            $sql = "SELECT * FROM tbl_login_attempts WHERE user_id = $user_id ORDER BY attempt_time DESC LIMIT 5";
            $result = mysqli_query($conn, $sql);

            $failed_attempts = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['attempt_result'] == 'failed') {
                    $failed_attempts++;
                }
            }

            if ($failed_attempts >= 5) {
                // disable account
                $sql = "UPDATE tbl_users SET account_status = 'disabled' WHERE user_id = $user_id";
                mysqli_query($conn, $sql);
                $response['status'] = 'error';
                $response['message'] = 'Your account has been disabled due to too many failed login attempts.';
            } else {
                $response['status'] = 'success';
            }
        }
    } else {
        // password is incorrect
        recordLoginAttempt($conn, $user_id, 'failed');
        $response['status'] = 'error';
        $response['message'] = 'Incorrect username or password.';
    }

} else {
    // email does not exist
    recordLoginAttempt($conn, NULL, 'failed');
    $response['status'] = 'error';
    $response['message'] = 'Incorrect username or password.';
}

function recordLoginAttempt($conn, $user_id, $result) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = "http://ip-api.com/json/{$ip}";
    $ip_info = file_get_contents($url);
    $ip_info = json_decode($ip_info);
    $location = '';
    if(isset($ip_info->city) && isset($ip_info->region) && isset($ip_info->country)){
        $location = $ip_info->city . ', ' . $ip_info->region . ', ' . $ip_info->country;
    }

    if ($user_id !== NULL) {
        $sql = "INSERT INTO tbl_login_attempts (user_id, attempt_result, ip_address, location) VALUES ($user_id, '$result', '$ip', '$location')";
    } else {
        $sql = "INSERT INTO tbl_login_attempts (attempt_result, ip_address, location) VALUES ('$result', '$ip', '$location')";
    }
    mysqli_query($conn, $sql);
}

mysqli_close($conn);

echo json_encode($response);
?>
