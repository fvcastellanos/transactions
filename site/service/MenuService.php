<?php

namespace app\service;

use app\security\model\User;
use \Yii;

class MenuService
{
    public function getMenuOptions()
    {
        $isGuest = Yii::$app->user->isGuest;

        if ($isGuest) 
        {
            return $this->getGuestOptions();
        }
    }

    private function getGuestOptions() {
        return [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'SignUp', 'url' => ['/registry/index']],
            ['label' => 'Login', 'url' => ['/site/login']]                
        ];
    }

    private function getSignedOptions() {

        $user = Yii::$app->user->username;

        return [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'SignUp', 'url' => ['/site/about']],
            ['label' => 'Logout(' . $user . ')', 'url' => ['/site/login']]                
        ];
    }

}