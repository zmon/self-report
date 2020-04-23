<?php


namespace App\Observers;

use App\RaceEthnicity;

class RaceEthnicityObserver
{
    protected $request;

    public function __construct()
    {
        $this->request = request();
    }

    /**
     * Handle the charge "created" event.
     *
     * @param RaceEthnicity $model
     * @return void
     */
    public function created(RaceEthnicity $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "updated" event.
     *
     * @param RaceEthnicity $model
     * @return void
     */
    public function updated(RaceEthnicity $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "deleted" event.
     *
     * @param RaceEthnicity $model
     * @return void
     */
    public function deleting(RaceEthnicity $model)
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
     * @param RaceEthnicity $model
     * @return void
     */
    public function deleted(RaceEthnicity $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "restored" event.
     *
     * @param RaceEthnicity $model
     * @return void
     */
    public function restored(RaceEthnicity $model)
    {
        //
    }

    /**
     * Handle the charge "force deleted" event.
     *
     * @param RaceEthnicity $model
     * @return void
     */
    public function forceDeleted(RaceEthnicity $model)
    {
        //
    }
}
