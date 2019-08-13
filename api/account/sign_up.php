<?php
require_once '../../libs/AccountManager.php';

$error_msg = [];

// 値が入力されているかチェック
if (
    !isset($_POST['user_id']) ||
    !isset($_POST['password']) ||
    !isset($_POST['birthday']) ||
    !isset($_POST['body_height']) ||
    !isset($_POST['body_weight']) ||
    !isset($_POST['gender'])
) {
    $json = ['status' => 'E00', 'msg' => ['REQUIRED_PARAM']];
} else {
    // バリデーション
    require_once '../../libs/Validation.php';
    $validation_user_id = Validation::fire($_POST['user_id'], Validation::$USER_ID);
    $validation_password = Validation::fire($_POST['password'], Validation::$PASSWORD);
    $validation_birthday = Validation::fire($_POST['birthday'], Validation::$BIRTHDAY);
    $validation_body_height = Validation::fire($_POST['body_height'], Validation::$BODY_HEIGHT);
    $validation_body_weight = Validation::fire($_POST['body_weight'], Validation::$BODY_WEIGHT);
    $validation_gender = Validation::fire($_POST['gender'], Validation::$GENDER);
    if (
        !$validation_user_id ||
        !$validation_password ||
        !$validation_birthday ||
        !$validation_body_height ||
        !$validation_body_weight ||
        !$validation_gender
    ) {
        if (!$validation_user_id) $error_msg[] = "VALIDATION_USER_ID";
        if (!$validation_password) $error_msg[] = "VALIDATION_PASSWORD";
        if (!$validation_birthday) $error_msg[] = "VALIDATION_BIRTHDAY";
        if (!$validation_body_height) $error_msg[] = "VALIDATION_BODY_HEIGHT";
        if (!$validation_body_weight) $error_msg[] = "VALIDATION_BODY_WEIGHT";
        if (!$validation_gender) $error_msg[] = "VALIDATION_GENDER";
        $json = ['status' => 'E00', 'msg' => $error_msg];
    } else {
        // ユーザーIDが重複していないかチェック
        $check_user_id = AccountManager::already_user_id($_POST['user_id']);
        if (!$check_user_id) {
            $json = ['status' => 'E00', 'msg' => ["ALREADY_USER_ID"]];
        } else {
            // ユーザーを登録
            $user_id = AccountManager::sign_up(
                $_POST['user_id'],
                $_POST['password'],
                $_POST['birthday'],
                $_POST['body_height'],
                $_POST['body_weight'],
                $_POST['gender']
            );
            // ユーザートークンを追加
            require_once '../../libs/TokenManager.php';
            $token = TokenManager::add_token($user_id);
            $json = ['status' => 'S00', 'data' => ['token' => $token]];
        }
    }
}

// 配列をJSONにエンコードして表示
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=utf-8");
echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);