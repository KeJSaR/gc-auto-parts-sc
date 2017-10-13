<!DOCTYPE html>
<html lang="en">
    <head>
<?php include_once(SCL_PARTS_DIR . "head.php"); ?>
    </head>
    <body id="login-page">

        <div class="container">
            <p>Станция техобслуживания ЛИДЕР</p>
            <h1>Складской учёт</h1>

            <form method="post" action="<?php echo SCL_URL ?>">

                <?php if ($this->warning === true) : ?>
                    <div class="alert">
                        <p><strong>Неверное имя пользователя или пароль</strong></p>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <!-- <label for="scl-login">Имя пользователя</label> -->
                    <input type="text" name="login" id="scl-login" placeholder="Введите свой логин">
                </div>

                <div class="form-group">
                    <!-- <label for="scl-password">Пароль</label> -->
                    <input type="password" name="password" id="scl-password" placeholder="Введите свой пароль">
                </div>

                <div class="form-check">
                    <label>
                        <input type="checkbox" name="remember">
                        запомнить меня
                    </label>
                </div>

                <div class="submit">
                    <button type="submit">Войти</button>
                </div>

            </form>
        </div>

<?php include_once(SCL_PARTS_DIR . "scripts.php"); ?>
    </body>
</html>
