<?php
class Validation
{
    public function stringValidation($string)
    {
        if ($string == '' || $string == null) {
            echo "String can not be null or empty";
        }
    }
    public function numericNumberValidation($number)
    {
        if (!is_numeric($number) || $number == '' || $number == null) {
            echo "( " . $number . " ) - This number is not numeric value";
        }
    }
    public function intNumberValidation($number)
    {
        if (!is_int($number) || $number == '' || $number == null) {
            echo "( " . $number . " ) - This number is not integer format. Please provide valid integer number";
        }
    }
    public function floatNumberValidation($number)
    {
        if ($number == '' || $number == null || !is_float($number)) {
            echo "( " . $number . " ) - This number is not valid float format. Please provide valid float number";
        }
    }
    public function emailValidation($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "( " . $email . " ) - This email is not valid.";
        }
    }

    public function phoneValidation($phone)
    {
        $getThird = substr($phone, 2, 1);
        if (!preg_match('/^[3-9 +-]*$/', $getThird)) {
            $operator = 0;
        } else {
            $operator = 1;
        }
        $firstTwo = substr($phone, 0, 2);
        if (!is_numeric($phone) || strlen($phone) != 11 || $firstTwo !== '01' || $operator == 0) {
            echo "Phone number is not valid";
        }
    }
}
