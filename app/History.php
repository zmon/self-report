<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class History extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var array
     */
    protected $casts = [
        'old' => 'json',
        'new' => 'json'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphTo
     */
    public function historyable()
    {
        return $this->morphTo('historyable');
    }


    /**
     * @return array
     *  Example
     * "sentence" => [
     *       "old" => "10000 days",
     *       "new" => "10 1/2 days",
     *       ],
     */
    public function diff()
    {

        $diff = [];
        if ($this->old && $this->new) {
            foreach ($this->new as $column => $value) {
                if ($value !== $this->old[$column]) {
                    $diff[$column] = ['old' => $this->old[$column], 'new' => $value];
                }
            }
        }

        return $diff;
    }
}
