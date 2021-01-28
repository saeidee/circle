<?php

namespace App\Models;

use App\Traits\HasPartner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Campaign
 * @package App\Models
 */
class Campaign extends Model
{
    use SoftDeletes, HasPartner;

    protected $table = 'campaigns';
    protected $guarded = [];

    /**
     * @return belongsToMany
     */
    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class, 'campaign_product');
    }

    /**
     * @return BelongsTo
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
