<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Poppins:wght@500&display=swap" rel="stylesheet">
    <title>4tasks</title>
</head>
<body>
<div class="main">
    <?php
    if ($errorOutput) {
        print('<section id="messages">');
        if ($hasErrors)
            print('<h2>Ошибка!</h2>');
        else
            print('<h2>Успешно!</h2>');
        print($errorOutput);
        print('</section>');
    }
    ?>
    <section id="form">
        <h2>Форма</h2>
        <form action="."
              method="POST">
            <label>
                Имя<br />
                <input name="name" <?php if (!empty($errors['name'])) {print 'class="error"';} ?>
                       value="<?php print $values['name']; ?>"/>
            </label><br />

            <label>
                E-mail:<br />
                <input name="email" <?php if (!empty($errors['email'])) {print 'class="error"';} ?>
                       value="<?php print $values['email']; ?>"
                       type="email" />
            </label><br />

            <label>
                Дата рождения:<br />
                <input name="birthday" <?php if (!empty($errors['birthday'])) {print 'class="error"';} ?>
                       value="<?php print $values['birthday']; ?>"
                       type="date" />
            </label><br />

            Пол:<br />
            <label><input type="radio"
                          name="gender" value="M" <?php if ($values['gender'] == 'M') {print 'checked';} ?>/>
                мужской
            </label>
            <label><input type="radio"
                          name="gender" value="F" <?php if ($values['gender'] == 'F') {print 'checked';} ?> />
                женский
            </label>
            <br />

            Количество конечностей:<br />
            <label><input type="radio" <?php if ($values['limbs'] == '2') {print 'checked';} ?>
                          name="limbs" value="2" />
                2
            </label>
            <label><input type="radio" <?php if ($values['limbs'] == '3') {print 'checked';} ?>
                          name="limbs" value="3" />
                3
            </label>
            <label><input type="radio" <?php if ($values['limbs'] == '4') {print 'checked';} ?>
                          name="limbs" value="4" />
                4
            </label>
            <br />
            <label>
                Сверхспособности:
                <br />
                <select name="superpowers[]"
                        multiple="multiple">
                    <option value="0" <?php if ($values['superpowers']['0']) {print 'selected';} ?>>Бессмертие</option>
                    <option value="1" <?php if ($values['superpowers']['1']) {print 'selected';} ?>>Прохождение сквозь стены</option>
                    <option value="2" <?php if ($values['superpowers']['2']) {print 'selected';} ?>>Левитация</option>
                </select>
            </label><br />
            <br />
            <label <?php if (array_key_exists('contract', $errors)) {print 'class="error"';} ?>>
                <input type="checkbox"
                       name="contract" <?php if ($values['contract']) {print 'checked';} ?>/>
                С условиями ознакомлен
            </label><br />
            <input id="submit" type="submit" value="Отправить" />
        </form>
    </section>
</div>
</body>
</html>
