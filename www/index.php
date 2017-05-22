<?php
header("Content-Type: text/html; charset=utf-8");

/*
 * не готово:
 * Поиск
 * Валидация
 * Сортировка
 * Удаление
 * Функции
 * */

function clear($value)
{
    $result = strip_tags(trim($value));
    return $result;
}

function save($name, $lastName, $phone)
{
    $name = clear($name);
    $lastName = clear($lastName);
    $phone = clear($phone);

    $errors = array();

    if (empty($name))
        $errors[] = "Поле Имя не должно быть пустым";
    if (empty($lastName))
        $errors[] = "Поле Фамилия не должно быть пустым";
    if (empty($lastName))
        $errors[] = "Поле Телефон не должно быть пустым";

    if (count($errors) != 0)
        return $errors;

    if (!preg_match('/^[а-яёА-ЯЁ\-]+$/u', $name))
        $errors[] = "Неправильный формат Имени!";
    if (!preg_match('/^[а-яёА-ЯЁ\-]+$/u', $lastName))
        $errors[] = "Неправильный формат Фамилии!";
    if (!preg_match('/^8[0-9]{10}+$/', $phone))
        $errors[] = "Неправильный формат Телефона!";

    if (count($errors) != 0)
        return $errors;

    $line = "$name|$lastName|$phone";
    file_put_contents('addressbook.txt', "\r\n" . $line);
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $errors = save($_POST["first_name"], $_POST["last_name"], $_POST["phone"]);
    if(!isset($errors))
    {
        header("Location: index.php");
        exit(0);
    }
}

function myCmp($a, $b)
{
    if ($a['LAST_NAME'] == $b['LAST_NAME'])
        return 0;
    return $a['LAST_NAME'] > $b['LAST_NAME'] ? 1 : -1;
}

function getList()
{
    $result = Array();
    $lines = file('addressbook.txt');
    $i = 0;
    foreach ($lines as $line_num => $line) {
        $values = explode("|", $line);
        $result[$i]["NAME"] = $values[0];
        $result[$i]["LAST_NAME"] = $values[1];
        $result[$i]["PHONE"] = $values[2];
        $result[$i]["LINE_NUM"] = $line_num;
        $i++;
    }
    usort($result, 'myCmp');
    return $result;
}

function search($lastName) {
    $result = Array();
    $lines = file('addressbook.txt');
    $i = 0;
    foreach ($lines as $line_num => $line) {
        $values = explode("|", $line);
        $pos = stripos($values[1], $lastName);
        if(strlen($lastName) >= 3 && $pos !== false) {
            $result[$i]["NAME"] = $values[0];
            $result[$i]["LAST_NAME"] = $values[1];
            $result[$i]["PHONE"] = $values[2];
            $result[$i]["LINE_NUM"] = $line_num;
            $i++;
        }
    }
    usort($result, 'myCmp');
    return $result;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = save($_POST["first_name"], $_POST["last_name"], $_POST["phone"]);
    if(!isset($errors)) {
        header("Location: index.php");
        exit(0);
    }
}

$deleteId = strip_tags(trim($_GET["delete_id"]));
$search = strip_tags(trim($_GET["search"]));

if($search == "")
    $addressbook = getList();
else
    $addressbook = search($search);

if($deleteId != "")
{
    $lines = file('addressbook.txt');
    unset($lines[(int)$deleteId]);

    $f = fopen('addressbook.txt',"w");
    for($i = 0; $i < count($lines) + 1; $i++)
    {
        fwrite($f, $lines[$i]);
    }
    fclose($f);
    header("Location: index.php");
}
?>

<html>
<head>
    <title>An Address Book</title>
</head>
<body>

<style type="text/css">
    table
    {
        width: 100%;
        border: 1px solid #399;
        border-spacing: 7px 5px;
        color: white;
    }
    td
    {
        empty-cells: hide;
        padding: 5px;
        border: 1px solid;
        background: #224b80;
    }
    h3 {
        margin-bottom: 1px;
        border-bottom: 2px solid white;
    }
</style>

<table>
    <tr>
        <td>
            <h3>Поиск контакта</h3> <br>
            <form method="get">
                <input type="text" name="name" placeholder="Кого вы ищете?">
                <input type="button" name="finder" value="Найти">
            </form>

        </td>
    </tr>

    <tr>
        <td>
            <h3>Новый контакт</h3> <br>
            <form method="POST">
                <input type="text" name="first_name" placeholder="Имя" value="<?=$_POST['first_name']?>">
                <input type="text" name="last_name" placeholder="Фамилия" value="<?=$_POST['last_name']?>">
                <input type="text" name="phone" placeholder="Телефон" value="<?=$_POST['phone']?>">
                <input type="submit" name="save" value="Добавить контакт">
                <?if(isset($errors)):?>
                    <?foreach ($errors as $error): ?>
                        <p><?=$error;?></p>
                    <?endforeach;?>
                <? endif;?>
            </form>
        </td>
    </tr>

    <tr>
        <td>
            <h3>Телефонная книга</h3> <br>
            <table>
                <tr>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Номер</th>
                    <th>Действие</th>
                </tr>
                <? foreach ($addressbook as $line_num => $line):?>
                    <tr>
                        <td><?=$line["NAME"];?></td>
                        <td><?=$line["LAST_NAME"];?></td>
                        <td><?=$line["PHONE"];?></td>
                        <td><a href="?delete_id=<?=$line['LINE_NUM'];?>">Удалить</a></td>
                    </tr>
                <?endforeach;?>
            </table>
        </td>
    </tr>
</table>

</body>
</html>