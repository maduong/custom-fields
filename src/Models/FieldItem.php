<?php namespace Edutalk\Base\CustomFields\Models;

use Edutalk\Base\CustomFields\Models\Contracts\FieldItemModelContract;
use Edutalk\Base\Models\EloquentBase as BaseModel;

class FieldItem extends BaseModel implements FieldItemModelContract
{
    protected $table = 'field_items';

    protected $primaryKey = 'id';

    protected $fillable = [
        'field_group_id',
        'parent_id',
        'order',
        'title',
        'slug',
        'type',
        'instructions',
        'options',
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fieldGroup()
    {
        return $this->belongsTo(FieldGroup::class, 'field_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(FieldItem::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child()
    {
        return $this->hasMany(FieldItem::class, 'parent_id');
    }
}
