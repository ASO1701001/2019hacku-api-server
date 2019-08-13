<?php
class Validation {
    public static $USER_ID = 'user_id';
    public static $PASSWORD = 'password';
    public static $BIRTHDAY = 'birthday';
    public static $BODY_HEIGHT = 'body_height';
    public static $BODY_WEIGHT = 'body_weight';
    public static $GENDER = 'gender';

    public static function fire($value, string $rule) {
        $regex = '';
        switch ($rule) {
            case self::$USER_ID:
                $regex = '/^[a-zA-Z0-9-_]{4,30}$/';
                break;
            case self::$PASSWORD:
                $regex = '/^.{5,50}$/';
                break;
            case self::$BIRTHDAY:
                $regex = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';
                break;
            case self::$BODY_HEIGHT:
                $regex = '/^13[0-9]|1[4-9][0-9]|2[0-4][0-9]|250$/';
                break;
            case self::$BODY_WEIGHT:
                $regex = '/^[1-9][0-9]|1[0-4][0-9]|150$/';
                break;
            case self::$GENDER:
                $regex = '/^[1-2]$/';
                break;
        }

        return (preg_match($regex, $value)) ? true : false;
    }
}