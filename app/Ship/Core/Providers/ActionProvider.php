<?php


namespace App\Ship\Core\Providers;


use App\Ship\Core\Abstracts\Providers\MainProvider as AbstractMainProvider;
use App\Ship\Core\Foundation\Action;

class ActionProvider extends AbstractMainProvider
{
    public function register()
    {
        parent::register();

        $this->app->alias(Action::class, 'Action');
    }
}
