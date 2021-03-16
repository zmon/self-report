<?php

namespace App\Observers;

use App\PreexistingCondition;

class PreexistingConditionObserver
{
    protected $request;

    public function __construct()
    {
        $this->request = request();
    }

    /**
     * Handle the charge "created" event.
     *
     * @param PreexistingCondition $model
     * @return void
     */
    public function created(PreexistingCondition $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "updated" event.
     *
     * @param PreexistingCondition $model
     * @return void
     */
    public function updated(PreexistingCondition $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "deleted" event.
     *
     * @param PreexistingCondition $model
     * @return void
     */
    public function deleting(PreexistingCondition $model)
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
     * @param PreexistingCondition $model
     * @return void
     */
    public function deleted(PreexistingCondition $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "restored" event.
     *
     * @param PreexistingCondition $model
     * @return void
     */
    public function restored(PreexistingCondition $model)
    {
        //
    }

    /**
     * Handle the charge "force deleted" event.
     *
     * @param PreexistingCondition $model
     * @return void
     */
    public function forceDeleted(PreexistingCondition $model)
    {
        //
    }
}
