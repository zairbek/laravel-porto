<?php

namespace App\Ship\Parents\Models;

use App\Ship\Core\Abstracts\Models\Model as AbstractModel;
use App\Ship\Core\Traits\HasResourceKeyTrait;
use Znck\Eloquent\Traits\BelongsToThrough;

/**
 * Class Model.
 */
abstract class Model extends AbstractModel
{
    use HasResourceKeyTrait;
    use BelongsToThrough;
}
