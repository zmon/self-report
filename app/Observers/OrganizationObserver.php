<?php


namespace App\Observers;

use App\Organization;

class OrganizationObserver
{
    protected $request;

    public function __construct()
    {
        $this->request = request();
    }

    /**
     * Handle the charge "created" event.
     *
     * @param  Organization  $model
     * @return void
     */
    public function created(Organization $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "updated" event.
     *
     * @param  Organization  $model
     * @return void
     */
    public function updated(Organization $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "deleted" event.
     *
     * @param  Organization  $model
     * @return void
     */
    public function deleting(Organization $model)
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
     * @param  Organization  $model
     * @return void
     */
    public function deleted(Organization $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "restored" event.
     *
     * @param  Organization  $model
     * @return void
     */
    public function restored(Organization $model)
    {
        //
    }

    /**
     * Handle the charge "force deleted" event.
     *
     * @param  Organization  $model
     * @return void
     */
    public function forceDeleted(Organization $model)
    {
        //
    }
}
