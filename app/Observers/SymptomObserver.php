<?php

namespace App\Observers;

use App\Symptom;

class SymptomObserver
{
    protected $request;

    public function __construct()
    {
        $this->request = request();
    }

    /**
     * Handle the charge "created" event.
     *
     * @param Symptom $model
     * @return void
     */
    public function created(Symptom $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "updated" event.
     *
     * @param Symptom $model
     * @return void
     */
    public function updated(Symptom $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "deleted" event.
     *
     * @param Symptom $model
     * @return void
     */
    public function deleting(Symptom $model)
    {

//        $user = \Auth::User();
//        if ( $user ) {
//            $model->purged_by = $user->id;
//        } else {
//            $model->purged_by = -1;
//        }
//        $model->save();
    }

    /**
     * Handle the charge "deleted" event.
     *
     * @param Symptom $model
     * @return void
     */
    public function deleted(Symptom $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "restored" event.
     *
     * @param Symptom $model
     * @return void
     */
    public function restored(Symptom $model)
    {
        //
    }

    /**
     * Handle the charge "force deleted" event.
     *
     * @param Symptom $model
     * @return void
     */
    public function forceDeleted(Symptom $model)
    {
        //
    }
}
