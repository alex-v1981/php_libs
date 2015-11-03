<?php
    require_once 'login.class.php';

    if ( isset( $_POST['password'] ) )
    {
        $login = new Login();

        $user = isset( $_POST['login'] ) ? $_POST['login'] : false;
        $password = $_POST['password'];
        $remember = isset( $_POST['remember'] ) ? $_POST['remember'] : false;

        if ( $login->authorization($password, $user, $remember) )
            exit('<html><head><meta http-equiv="refresh" content="0; url='.LoginConfig::MAIN_PAGE.'"></head><body></body></html>');
    }
?>

<html>
<head>
    <title><?php echo LoginConfig::TITLE; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="<?php echo LoginConfig::LOGIN_LIB_PATH; ?>login.css" rel="stylesheet">
</head>

<body>
    <div id="login">
        <h3><?php echo LoginConfig::TITLE; ?></h3>

        <form method="POST">
            <div>
                <table>
                    <?php if ( LoginConfig::LOGIN_USE_USERNAME ) : ?>
                        <tr>
                            <td align="right">Логин:</td>
                            <td><input name="login" type="text"></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td align="right">Пароль:</td>
                        <td><input name="password" type="password"></td>
                    </tr>
                    <?php if ( LoginConfig::LOGIN_USE_REMEMBER ) : ?>
                        <tr>
                            <td align="right">Запомнить:</td>
                            <td><input name="remember" type="checkbox"></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Войти"></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>
</html>