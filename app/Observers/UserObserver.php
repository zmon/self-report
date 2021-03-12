<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    protected $request;

    public function __construct()
    {
        $this->request = request();
    }

    /**
     * Handle the charge "created" event.
     *
     * @param  User  $model
     * @return void
     */
    public function created(User $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "updated" event.
     *
     * @param  User  $model
     * @return void
     */
    public function updated(User $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "deleted" event.
     *
     * @param  User  $model
     * @return void
     */
    public function deleting(User $model)
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
     * @param  User  $model
     * @return void
     */
    public function deleted(User $model)
    {
        $model->saveHistory($this->request, __FUNCTION__);
    }

    /**
     * Handle the charge "restored" event.
     *
     * @param  User  $model
     * @return void
     */
    public function restored(User $model)
    {
        //
    }

    /**
     * Handle the charge "force deleted" event.
     *
     * @param  User  $model
     * @return void
     */
    public function forceDeleted(User $model)
    {
        //
    }
}
