<?php

session_start();

require_once 'login_config.php';

class Login
{
    protected $loginKey;
    protected $accountLoginKey = 'login';
    protected $accountPasswordKey = 'password';
    protected $accountIsAdminKey = 'is_admin';

    function __construct()
    {
        $this->loginKey = LoginConfig::LOGIN_KEY_PREFIX.'login';
    }

    protected function accountInfo($login, $password)
    {
        $accounts = LoginConfig::accounts();

        if ( LoginConfig::LOGIN_USE_USERNAME )
        {
            foreach ($accounts as $a)
            {
                if ($a[$this->accountLoginKey]===$login)
                {
                    if ($a[$this->accountPasswordKey]===$password)
                        return $a;
                    else
                        return false;
                }
            }
        }
        else
        {
            foreach ($accounts as $a)
            {
                if ($a[$this->accountPasswordKey]===$password)
                    return $a;
            }
        }

        return false;
    }

    public function authorization($password, $userName=false, $remember=false)
    {
        $a = $this->accountInfo($userName, $password);

        if ( $a )
        {
            $_SESSION[$this->loginKey] = $a;

            if (LoginConfig::LOGIN_USE_REMEMBER && $remember)
            {
                $t = time()+60*60*24*365;

                $aStr = json_encode( $a );
                setcookie($this->loginKey, $aStr, $t);
            }
        }

        return $a !== false;
    }

    public function logout( $gotoMainPage=true )
    {
        unset( $_SESSION[ $this->loginKey ] );
        setcookie($this->loginKey, '');

        if ( $gotoMainPage )
            header('Location: '.LoginConfig::MAIN_PAGE, true);
    }

    public function isLogged()
    {
        return isset( $_SESSION[ $this->loginKey ] );
    }

    public function loggedInfo()
    {
        return $_SESSION[ $this->loginKey ];
    }

    public function loggedUsername()
    {
        $res = $this->loggedInfo();

        return $res[$this->accountLoginKey];
    }

    public function loggedAdminFlag()
    {
        $res = $this->loggedInfo();

        return $res[$this->accountIsAdminKey];
    }

    public function check()
    {
        $logged = $this->isLogged();

        if (!$logged && LoginConfig::LOGIN_USE_REMEMBER && isset($_COOKIE[$this->loginKey]))
        {
            $a = json_decode($_COOKIE[$this->loginKey], true);
            $logged = $this->authorization($a[$this->accountPasswordKey], $a[$this->accountLoginKey]);
        }

        if ( !$logged )
        {
            include 'login_html.php';
            exit;
        }
    }
}