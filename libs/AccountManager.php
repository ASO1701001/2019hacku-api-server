<?php
class AccountManager {
    /**
     * @param $user_id
     * @param $password
     * @param $birthday
     * @param $body_height
     * @param $body_weight
     * @param $gender
     * @return string
     */
    public static function sign_up($user_id, $password, $birthday, $body_height, $body_weight, $gender) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "INSERT INTO user (user_id, password, birthday, body_height, body_weight, gender) VALUES (:user_id, :password, :birthday, :body_height, :body_weight, :gender)";
        $id = $db->insert($sql, array(
            'user_id' => $user_id,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'birthday' => $birthday,
            'body_height' => $body_height,
            'body_weight' => $body_weight,
            'gender' => $gender
        ));
        return $id;
    }

    /**
     * @param $user_id
     * @param $password
     * @return bool
     */
    public static function sign_in($user_id, $password) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "SELECT id,password FROM user WHERE user_id = ?";
        $pdo = $db -> pdo();
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindValue(1,$user_id,PDO::PARAM_STR);
        $stmt -> execute();
        $check = $stmt -> fetch();
        //パスワードが正しければidを返す
        if (password_verify($password, $check['password'])){
            return $check['id'];
        }
    return false;
    }

    /**
     * @param $user_id
     * @return bool
     */
    public static function already_user_id($user_id) {
        require_once 'DatabaseManager.php';
        $db = new DatabaseManager();
        $sql = "SELECT count(*) FROM user WHERE user_id = :user_id";
        $data = $db->fetchColumn($sql, array('user_id' => $user_id));
        return $data == 0 ? true : false;
    }
}
