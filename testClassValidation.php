<?php
include './validation.php';
$validation = new Validation();

// echo $validation->stringValidation('');
// echo $validation->intNumberValidation(12.12);
// echo $validation->emailValidation('ram3zan@ihelpbd.com');
echo $validation->phoneValidation('0123677063');
