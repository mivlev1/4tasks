<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');
$errorOutput = '';
// Складываем признак ошибок в массив.
$errors = array();
$hasErrors = FALSE;

$defaultValues = [
    'name' => '',
    'email' => '',
    'birthday' => '',
    'gender' => 'O',
    'limbs' => '4',

    'contract' => ''
];
// Складываем предыдущие значения полей в массив, если есть.
$values = array();
foreach (['name', 'email', 'birthday', 'gender', 'limbs', 'contract'] as $key) {
    $values[$key] = !array_key_exists($key . '_value', $_COOKIE) ? $defaultValues[$key] : $_COOKIE[$key . '_value'];
}
foreach (['name', 'email', 'birthday'] as $key) {
    $errors[$key] = empty($_COOKIE[$key . '_error']) ? '' : $_COOKIE[$key . '_error'];
    if ($errors[$key] != '')
        $hasErrors = TRUE;
}
//массив суперспособностей
$values['superpowers'] = array();
$values['superpowers']['0'] = empty($_COOKIE['superpowers_0_value']) ? '' : $_COOKIE['superpowers_0_value'];
$values['superpowers']['1'] = empty($_COOKIE['superpowers_1_value']) ? '' : $_COOKIE['superpowers_1_value'];
$values['superpowers']['2'] = empty($_COOKIE['superpowers_2_value']) ? '' : $_COOKIE['superpowers_2_value'];


// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
    // Выдаем сообщение об успешном сохранении.
    if (!empty($_GET['save'])) {
        // Если есть параметр save, то выводим сообщение пользователю.
        $errorOutput = 'Спасибо, результаты сохранены.<br/>';
    }

    //Проверка полей на пустоту
    if (!empty($errors['name'])) {
        $errorOutput .= 'Заполните имя.<br/>';
    }
    if (!empty($errors['email'])) {
        $errorOutput .= 'Заполните email.<br/>';
    }
    if (!empty($errors['birthday'])) {
        $errorOutput .= 'Заполните дату рождения.<br/>';
    }
    // Включаем содержимое файла form.php.
    // В нем будут доступны переменные $messages, $errors и $values для вывода
    // сообщений, полей с ранее заполненными данными и признаками ошибок.
    include('form.php');
    exit();
}

$trimmedPost = [];
foreach ($_POST as $key => $value)
    if (is_string($value))
        $trimmedPost[$key] = trim($value);
    else
        $trimmedPost[$key] = $value;

if (empty($trimmedPost['name'])) {
    $errorOutput .= 'Заполните имя.<br/>';
    $errors['name'] = TRUE;
    setcookie('name_error', 'true');
    $hasErrors = TRUE;
} else {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('name_error', '', 10000);
    $errors['name'] = '';
}
// Выдаем куку на день с флажком об ошибке в поле.
setcookie('name_value', $trimmedPost['name'], time() + 30 * 24 * 60 * 60);
$values['name'] = $trimmedPost['name'];

if (!preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $trimmedPost['email'])) {
    $errorOutput .= 'Заполните email.<br/>';
    $errors['email'] = TRUE;
    setcookie('email_error', 'true');
    $hasErrors = TRUE;
} else {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('email_error', '', 10000);
    $errors['email'] = '';
}
// Выдаем куку на день с флажком об ошибке в поле.
setcookie('email_value', $trimmedPost['email'], time() + 30 * 24 * 60 * 60);
$values['email'] = $trimmedPost['email'];

if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $trimmedPost['birthday'])) {
    $errorOutput .= 'Заполните дату рождения.<br/>';
    $errors['birthday'] = TRUE;
    setcookie('birthday_error', 'true');
    $hasErrors = TRUE;
} else {
    setcookie('birthday_error', '', 10000);
    $errors['birthday'] = '';
}
setcookie('birthday_value', $trimmedPost['birthday'], time() + 30 * 24 * 60 * 60);
$values['birthday'] = $trimmedPost['birthday'];

if (!preg_match('/^[MFO]$/', $trimmedPost['gender'])) {
    $errorOutput .= 'Заполните пол.<br/>';
    $errors['gender'] = TRUE;
    $hasErrors = TRUE;
}
setcookie('gender_value', $trimmedPost['gender'], time() + 30 * 24 * 60 * 60);
$values['gender'] = $trimmedPost['gender'];

if (!preg_match('/^[0-5]$/', $trimmedPost['limbs'])) {
    $errorOutput .= 'Заполните количество конечностей.<br/>';
    $errors['limbs'] = TRUE;
    $hasErrors = TRUE;
}
setcookie('limbs_value', $trimmedPost['limbs'], time() + 30 * 24 * 60 * 60);
$values['limbs'] = $trimmedPost['limbs'];

foreach (['0', '1', '2'] as $value) {
    setcookie('superpowers_' . $value . '_value', '', 10000);
    $values['superpowers'][$value] = FALSE;
}
if (array_key_exists('superpowers', $trimmedPost)) {
    foreach ($trimmedPost['superpowers'] as $value) {
        if (!preg_match('/[0-2]/', $value)) {
            $errorOutput .= 'Неверные суперспособности.<br/>';
            $errors['superpowers'] = TRUE;
            $hasErrors = TRUE;
        }
        setcookie('superpowers_' . $value . '_value', 'true', time() + 30 * 24 * 60 * 60);
        $values['superpowers'][$value] = TRUE;
    }
}

if (!isset($trimmedPost['contract'])) {
    $errorOutput .= 'Вы не ознакомились с контрактом.<br/>';
    $errors['contract'] = TRUE;
    $hasErrors = TRUE;
}
// При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
if ($hasErrors) {

    include('form.php');
    exit();
}
//Далее обычная работа с бд
$user = 'u41733';
$pass = '6809062';
$db = new PDO('mysql:host=localhost;dbname=u41733', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

try {
    $db->beginTransaction();
    $stmt1 = $db->prepare("INSERT INTO forms SET name = ?, email = ?, birthday = ?, 
    gender = ? , limb_number = ?");
    $stmt1 -> execute([$trimmedPost['name'], $trimmedPost['email'], $trimmedPost['birthday'],
        $trimmedPost['gender'], $trimmedPost['limbs']]);
    $stmt2 = $db->prepare("INSERT INTO form_ability SET form_id = ?, ability_id = ?");
    $id = $db->lastInsertId();
    foreach ($trimmedPost['superpowers'] as $s)
        $stmt2 -> execute([$id, $s]);
    $db->commit();
}
catch(PDOException $e){
    $db->rollBack();
    $errorOutput = 'Error : ' . $e->getMessage();
    include('form.php');
    exit();
}
// Делаем перенаправление.
header('Location: ?save=1');
