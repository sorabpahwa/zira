<?php
/**
 * Zira project.
 * send.php
 * (c)2015 https://github.com/ziracms/zira
 */

namespace Zira\Forms\User;

use Zira\Form;
use Zira\Helper;
use Zira\Locale;
use Zira\User;

class Send extends Form {
    protected $_id = 'user-send-form';

    public function __construct() {
        parent::__construct($this->_id);
    }

    protected function _init() {
        $this->setTitle(Locale::t('Send confirmation code'));
        $this->setDescription(Locale::t('Enter your username or email'));
    }

    protected function _render() {
        $html = $this->open();
        $html .= $this->input(Locale::t('Username or Email').'*','login');
        $html .= $this->captcha(Locale::t('Anti-Bot').'*');
        $html .= $this->submit(Locale::t('Submit'));
        $html .= $this->close();
        return $html;
    }

    protected function _validate() {
        $login = (string)$this->getValue('login');
        $is_email = strpos($login, '@') !== false;

        $validator = $this->getValidator();
        $validator->registerCaptcha(Locale::t('Wrong CAPTCHA result'));
        if (!$is_email) {
            $validator->registerString('login',User::LOGIN_MIN_CHARS,User::LOGIN_MAX_CHARS,true,Locale::t('Invalid username'));
            $validator->registerRegexp('login', User::REGEXP_LOGIN, Locale::t('Login must contain only letters and numbers'));
        } else {
            $validator->registerEmail('login',true,Locale::t('Invalid email'));
        }
        $validator->registerCustom(array(get_class(), 'isUserExists'), 'login', Locale::t('User not found'));
    }

    public static function isUserExists($login) {
        $user = \Zira\Models\User::findAuthUser($login);
        if (!$user) return false;
        if ($user->verified == \Zira\Models\User::STATUS_VERIFIED) return false;
        User::setCurrent($user);
        return true;
    }
}