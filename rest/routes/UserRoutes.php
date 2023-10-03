<?php

require_once __DIR__.'/../Config.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/**
* @OA\Post(
*     path="/login",
*     description="Login to the system",
*     tags={"login"},
*     @OA\RequestBody(description="Basic user info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="email", type="string", example="user@gmail.com",	description="Email"),
*    				@OA\Property(property="password", type="string", example="1234",	description="Password" )
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="JWT Token on successful response"
*     ),
*     @OA\Response(
*         response=404,
*         description="Wrong Password | User doesn't exist"
*     )
* )
*/
Flight::route('POST /login', function(){
    $login = Flight::request()->data->getData();
    $user = Flight::userDao()->get_user_by_email($login['email']);
    if (isset($user['id'])){
      if($user['status'] != 'ACTIVE'){

        Flight::json(["message" => "Account is not activated. Check your email!"], 404);
      }
      elseif($user['password'] == md5($login['password'])){
        unset($user['password']);
        $jwt = JWT::encode(["id" => $user["id"], "r" => $user["role"]], Config::JWT_SECRET(), 'HS256');
        Flight::json(['token' => $jwt]);
      }else{
        Flight::json(["message" => "Wrong password"], 404);
      }
    }else{
      Flight::json(["message" => "User doesn't exist"], 404);
    }
});
/**
 * @OA\Post(path="/register",tags={"login"},description="Register to the system",
 * @OA\RequestBody(description="Basic user info", required=true,
 *    @OA\MediaType(
 *      mediaType="application/json",
 *      @OA\Schema(
 *        @OA\Property(property="password", type="string", example="password", description="Users password"),
 *        @OA\Property(property="password_conf", type="string", example="password", description="Users password confirmation"),
 *        @OA\Property(property="user_name", type="string", example="user", description="Users name"),
 *        @OA\Property(property="user_surname", type="string", example="lastname", description="Users surname"),
 *        @OA\Property(property="city", type="string", example="Sarajevo", description="Users city of residence"),
 *        @OA\Property(property="email", type="string", example="username@gmail.com", description="Users email"),
 *        @OA\Property(property="phone", type="string", example="+38761123456", description="Users phone")
 *      )
 *    )
 *   ),
 * @OA\Response(response="200", description="User that has been added to the database"),
 * @OA\Response(
 *         response=400,
 *         description="Email in use | User already exists"
 *     )
 * )
 */
Flight::route('POST /register', function(){
  try {
    $userData = Flight::request()->data->getData();

    // Validate password
    if (!validatePassword($userData['password'])) {
      Flight::json(["message" => "Password too common"], 400);
      return;
    }
    if (strlen($userData['password']) < 6) {
      Flight::json(["message" => "Password must be at least 6 characters long"], 400);
      return;
    }
    if ($userData['password'] != $userData['password_conf']) {
      Flight::json(["message" => "Passwords don't match"], 400);
      return;
    }
  
    // Validate email address
    if (!validateEmail($userData['email'])) {
      Flight::json(["message" => "Invalid email address"], 400);
      return;
    }
    if (!validateEmailUnique($userData['email'])) {
      Flight::json(["message" => "Email already in use"], 400);
      return;
    }

    // Validate phone number
    if (!validatePhoneNumber($userData['phone'])) {
      Flight::json(["message" => "Invalid phone number"], 400);
      return;
    }
    if (!validatePhoneNumberUnique($userData['phone'])) {
      Flight::json(["message" => "Phone number already in use"], 400);
      return;
    }

    // Add the user¸
    $added_user = Flight::userDao()->add($userData);
    Flight::json(['user' => $added_user]);
    // Send confirmation email
    sendConfirmationEmail($added_user['email'], $added_user['token']);

  } catch (\Throwable $t) { 
    Flight::json(["message" => $t->getMessage()], 400);
  }
});

/**
* @OA\Get(path="/activate/{token}", tags={"login"},
*     summary="Activate user account. ",
*     @OA\Parameter(in="path", name="token", example=12354657, description="Token for account activation"),
*     @OA\Response(response="200", description="Fetch individual review")
* )
*/
Flight::route('GET /activate/@token', function ($token) {
  Flight::json(Flight::userDao()->confirm($token));
  header("Location: " . '//' . $_SERVER["SERVER_NAME"] . str_replace("/rest/index.php", "login.html", $_SERVER["SCRIPT_NAME"]));
  exit();
});

/**
 * @OA\Put(path="/password",tags={"user"},security={{"ApiKeyAuth": {}}},
 * @OA\RequestBody(description="Change password", required=true,
 *    @OA\MediaType(
 *      mediaType="application/json",
 *      @OA\Schema(
 *        @OA\Property(property="password", type="string", example="1234", description="Old password"),
 *        @OA\Property(property="new_password", type="string", example="4321", description="New password"),
 *        @OA\Property(property="new_password_conf", type="string", example="4321", description="New password confirmation")
 *      )
 *    )
 *   ),
 * @OA\Response(response="200", description="Success message")
 * )
 */
Flight::route('PUT /password', function(){
  $data = Flight::request()->data->getData();
  $user = Flight::userDao()->get_user_by_id(Flight::get("user")["id"]);
  if($user['password'] != md5($data['password'])){

    Flight::json(["message" => "Incorrect current password"], 404);

  // Validate password
  }elseif (strlen($data['new_password']) < 6) {

    Flight::json(["message" => "Password must be at least 6 characters long"], 400);

  }elseif (!validatePassword($data['new_password'])) {

    Flight::json(["message" => "Password too common"], 400);

  }elseif ($data['new_password'] != $data['new_password_conf']) {

      Flight::json(["message" => "Password not confirmed correctly"], 400);
      
  }else{

    Flight::json(Flight::userDao()->change_password($user["id"],$data["new_password"]));
  }
});

/**
 * @OA\Put(path="/user",tags={"user"},security={{"ApiKeyAuth": {}}},
 * @OA\RequestBody(description="Basic user info", required=true,
 *    @OA\MediaType(
 *      mediaType="application/json",
 *      @OA\Schema(
 *        @OA\Property(property="user_name", type="string", example="user", description="Users name"),
 *        @OA\Property(property="user_surname", type="string", example="lastname", description="Users surname"),
 *        @OA\Property(property="city", type="string", example="Sarajevo", description="Users city of residence"),
 *        @OA\Property(property="email", type="string", example="username@gmail.com", description="Users email"),
 *        @OA\Property(property="phone", type="string", example="+38761123456", description="Users phone")
 *      )
 *    )
 *   ),
 * @OA\Response(response="200", description="User has been updated in the database"),
 * @OA\Response(
 *         response=400,
 *         description="Error while updating"
 *     )
 * )
 */
Flight::route('PUT /user', function(){
  try {
    $userData = Flight::request()->data->getData();
  
    // Validate email address
    $existingEmail = Flight::userDao()->get_user_by_email($userData["email"]);
    if ($existingEmail && $existingEmail["id"] != Flight::get("user")["id"] ) {
      Flight::json(["message" => "Email already in use"], 400);
      return;
    }
    if (!validateEmail($userData['email'])) {
      Flight::json(["message" => "Invalid email address"], 400);
      return;
    }

    // Validate phone number
    if (!validatePhoneNumber($userData['phone'])) {
      Flight::json(["message" => "Invalid phone number"], 400);
      return;
    }
    $existingPhone = Flight::userDao()->get_user_by_phone_number($userData["phone"]);
    if ($existingPhone && $existingPhone["id"] != Flight::get("user")["id"]) {
      Flight::json(["message" => "Phone number already in use"], 400);
      return;
    }

    Flight::json(Flight::userDao()->update_user(Flight::get("user")["id"],$userData));

  } catch (\Throwable $t) { 
    Flight::json(["message" => $t->getMessage()], 400);
  }
});

/**
* @OA\Get(path="/user", tags={"user"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all user data. ",
 *         @OA\Response( response=200, description="User data")
 * )
 */
Flight::route('GET /user', function(){
  Flight::json(Flight::userDao()->get_by_id(Flight::get("user")["id"]));
});

function sendConfirmationEmail($email, $token) {
  $mail = new PHPMailer(true); 
  $host = $_SERVER['HTTP_HOST'];
  $route = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

  try {
    $mail->isSMTP();
    $mail->Host = Config::SMTP_HOST();; 
    $mail->SMTPAuth = true; 
    $mail->Username = Config::SMTP_USERNAME();
    $mail->Password = Config::SMTP_PASSWORD();
    $mail->SMTPSecure = Config::SMTP_ENCRIPTION(); 
    $mail->Port = Config::SMTP_PORT(); 

    // Sender and recipient
    $mail->setFrom('harun.kunovac@stu.ibu.edu.ba', 'MediBa');
    $mail->addAddress($email); 

    // Email content
    $mail->isHTML(true); 
    $mail->Subject = 'Registration Confirmation';
    $mail->Body = 'Dear user,<br><br>'
                . 'Activate account with following link: http://' . $host . $route . "/activate/" . $token;

    // Send the email
    $mail->send();
  } catch (\Exception $e) {
    echo "Error sending verification email: " . $e->getMessage();
  }
}

function validatePassword($password) {

  // Check if the password is a commonly used password
  $hash_password = strtoupper(hash('sha1', $password));
  $first_5_characters = substr($hash_password, 0, 5);
  $other_characters = substr($hash_password, 5);

  $response = file_get_contents('https://api.pwnedpasswords.com/range/'. $first_5_characters);
  if (str_contains($response, $other_characters)) {
    return false;
  }

  return true;
}

function validateEmail($email) {
  // Check if the email address is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // Check mx record
  $domain = explode('@', $email)[1];
  if (!checkdnsrr($domain, 'MX')) {
    return false;
  }

  return true;
}
function validateEmailUnique($email) {

  $existingUser = Flight::userDao()->get_user_by_email($email);
  if ($existingUser) {
    return false;
  }

  return true;
}


function validatePhoneNumber($phoneNumber) {
  $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();

  try {
    // Parse the phone number
    $parsedNumber = $phoneNumberUtil->parse($phoneNumber, null);

    // Check if the phone number is valid
    if (!$phoneNumberUtil->isValidNumber($parsedNumber)) {
      return false;
    }
  } catch (\libphonenumber\NumberParseException $e) {
    return false;
  }

  return true;
}
function validatePhoneNumberUnique($phoneNumber) {

  $existingUser = Flight::userDao()->get_user_by_phone_number($phoneNumber);
  if ($existingUser) {
    return false;
  }

  return true;
}


?>
