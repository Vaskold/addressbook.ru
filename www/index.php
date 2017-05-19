<?php
header("Content-Type: text/html; charset=utf-8");
include 'actions.php';

/*
 * не готово:
 * Поиск
 * Валидация
 * Сортировка
 * Удаление
 * Функции
 * */

?>

<html>
<head>
    <title>An Address Book</title>
</head>
<body>

<style type="text/css">
    table
    {
        width: 100%; /* Ширина таблицы */
        border: 1px solid #399; /* Граница вокруг таблицы */
        border-spacing: 7px 5px; /* Расстояние между границ */
        color: white; /* Цвет текста */
    }
    td
    {
        empty-cells: hide; /* hide empty cells */
        padding: 5px; /* Поля в ячейках */
        border: 1px solid;
        background: #224b80; /* Цвет фона ячеек */
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
            <input type="text" name="flast_name" placeholder="Кого вы ищете?">
            <input type="button" name="finder" value="Найти">
        </td>
    </tr>
    <tr>
        <td>
            <h3>Новый контакт</h3> <br>
            <form action="actions.php" method="post">
                <input type="text" name="last_name" placeholder="Фамилия">
                <input type="text" name="first_name" placeholder="Имя">
                <input type="text" name="phone_number" placeholder="Телефон">
                <input type="submit" name="saver" value="Добавить контакт">
            </form>
        </td>
    </tr>
    <tr>
        <td>
            <h3>Телефонная книга</h3> <br>
            <?php
            $str = nl2br(file_get_contents($file));
            echo $str;
            ?>
        </td>
    </tr>

</table>

</body>
</html>