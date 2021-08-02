<?php

namespace App\Ship\Parents\Models;

use App\Ship\Core\Abstracts\Models\UserModel as AbstractModel;
use App\Ship\Core\Traits\HasResourceKeyTrait;

/**
 * Class Model.
 */
abstract class UserModel extends AbstractModel
{
    use HasResourceKeyTrait;
}
