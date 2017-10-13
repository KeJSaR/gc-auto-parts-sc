<?php
namespace SCL\Classes;

defined('SCL_SAFETY_CONST') or die;

class Auth
{
    private $dbh;
    private $user_data = array(
        'user_id'    => '',
        'user_name'  => '',
        'user_login' => '',
        'role_id'    => '',
        'users'      => '',
        'warning'    => false,
    );

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function init()
    {
        session_start();

        // 1. Clear expired tokens
        $clear_tokens = new \SCL\Lib\ClearDbAuthTokens($this->dbh);
        $clear_tokens->init();

        // 2.1. Check POST
        if ( $this->post_auth_data_exists() ) {
            $this->check_post();
        // 2.2. Check Cookie
        } elseif ( $this->cookie_auth_data_exists() ) {
            $this->check_cookie();
        // 2.3. Check Session
        } elseif ( isset($_SESSION['user_id']) ) {
            $this->set_sesstion_data();
        }
        
        // 3. Check Logout
        $this->check_logout();

        return $this->user_data;
    }

    // Check POST ##############################################################
    private function post_auth_data_exists()
    {
        if (   filter_has_var(INPUT_POST, 'login')
            && filter_has_var(INPUT_POST, 'password')
        ) {
            return true;
        }
        return false;
    }

    private function check_post()
    {
        $check_post = new \SCL\Classes\Checks\CheckPost($this->dbh);
        $db_user_data = $check_post->init();

        $remember = $db_user_data['remember'];
        unset($db_user_data['remember']);

        if ( !empty($db_user_data['id'])) {

            if ( $remember ) {
                $cookie = new \SCL\Classes\Cookie($this->dbh);
                $cookie->init($db_user_data['id']);
            } else {
                $session = new \SCL\Classes\Session();
                $session->init($db_user_data);
            }

            $this->fill_user_data($db_user_data);

        } else {

            $this->user_data['warning'] = true;
            
        }
    } // end of Check POST

    // Check Cookie ############################################################
    private function cookie_auth_data_exists()
    {
        if ( filter_has_var(INPUT_COOKIE, 'scl_auth') ) {
            return true;
        }
        return false;
    }

    private function check_cookie()
    {
        $check_cookie = new \SCL\Classes\Checks\CheckCookie($this->dbh);
        $db_user_data = $check_cookie->init();

        if ( !empty($db_user_data) ) {
            $this->fill_user_data($db_user_data);
        }
    } // end of Check Cookie

    // Set sesstion data #######################################################
    private function set_sesstion_data()
    {
        $db_user_data['id']      = $_SESSION['user_id'];
        $db_user_data['name']    = $_SESSION['user_name'];
        $db_user_data['login']   = $_SESSION['user_login'];
        $db_user_data['role_id'] = $_SESSION['role_id'];

        $this->fill_user_data($db_user_data);
    } // end of Set sesstion data

    // Fill User Data ##########################################################
    private function fill_user_data($db_user_data)
    {
        $this->user_data['user_id']    = $db_user_data['id'];
        $this->user_data['user_name']  = $db_user_data['name'];
        $this->user_data['user_login'] = $db_user_data['login'];
        $this->user_data['role_id']    = $db_user_data['role_id'];
        $this->user_data['users']      = $this->get_users();
    }

    private function get_users()
    {
        $sql = 'SELECT id, name, login, role_id
                    FROM user
                    ORDER BY role_id ASC, login ASC';

        $sth = $this->dbh->prepare($sql);

        $sth->execute();

        return $sth->fetchAll();
    } // end of Fill User Data

    // Check Logout ############################################################
    private function check_logout()
    {
        if ( filter_has_var(INPUT_POST, 'logout')
            && (filter_input(INPUT_POST, 'logout') === 'on')
        ) {
            session_destroy();
            if ( filter_input(INPUT_COOKIE, 'scl_auth') ) {
                setcookie("scl_auth", "", time() - 3600);
                if ( isset($this->user_data['user_id']) ) {
                    $this->clear_auth_data();
                }
            }
            new \SCL\Classes\Login();
        }
    }
    
    private function clear_auth_data()
    {
        $sql = 'DELETE FROM auth_token
                    WHERE user_id = :user_id';

        $sth = $this->dbh->prepare($sql);

        $sth->execute(array(
            ':user_id' => $this->user_data['user_id']
        ));
    } // end of Check Logout
}
