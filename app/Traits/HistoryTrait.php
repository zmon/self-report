<?php


namespace App\Traits;


use App\History;


trait HistoryTrait
{

    /**
     * Auditable boot logic.
     *
     * @return void
     */
    public static function bootHistoryTrait()
    {

        $name = '\App\Observers' . substr(static::class, 3) . "Observer";
        static::observe(new $name());

    }

    public function histories()
    {
        return $this->morphMany(History::class, 'historyable');
    }

    public function saveHistory($request, $action = 'updated')
    {

        $data = [
            'user_id' => auth()->user()->id ?? 1,
            //         'reason_for_change' => $request->reason_for_change ?? null,
            'action' => $action
        ];

        /*
         * We only save the values listed in fillable for old and new
         */
        /// if not created add old values
        if ($action !== 'created') {
            $data['old'] = collect($this->getOriginal())->only($this->fillable);
        }
        /// if not deleted add new values
        if ($action !== 'deleted') {
            $data['new'] = $request->only($this->fillable);
        }

        return $this->histories()->create($data);
    }
}
