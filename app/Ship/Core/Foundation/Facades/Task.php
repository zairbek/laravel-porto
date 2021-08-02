<?php

namespace App\Ship\Core\Foundation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Action
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class Task extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Task';
    }
}
