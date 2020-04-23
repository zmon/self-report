<?php


namespace App\Observers;

use App\SelfReport;

class SelfReportObserver
{
    protected $request;

    public function __construct()
    {
        $this->request = request();
    }

    /**
     * Handle the charge "created" event.
     *
     * @param SelfReport $model
     * @return void
     */
    public function created(SelfReport $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "updated" event.
     *
     * @param SelfReport $model
     * @return void
     */
    public function updated(SelfReport $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "deleted" event.
     *
     * @param SelfReport $model
     * @return void
     */
    public function deleting(SelfReport $model)
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
     * @param SelfReport $model
     * @return void
     */
    public function deleted(SelfReport $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "restored" event.
     *
     * @param SelfReport $model
     * @return void
     */
    public function restored(SelfReport $model)
    {
        //
    }

    /**
     * Handle the charge "force deleted" event.
     *
     * @param SelfReport $model
     * @return void
     */
    public function forceDeleted(SelfReport $model)
    {
        //
    }
}
