# User - `users`

## To create or replace missing CRUD

```
php artisan make:crud users  --display-name="Users" --grid-columns="name"   # --force --skip-append
```

You will want to adjust the grid-columns to add more columns  for example to add alias

```
--grid-columns="name:alias"
```

To replace one file, remove it and rerun the above command

To replace all files, uncomment `--force`


## After running Crud Generator


#### Setup Permissions in `app/Lib/InitialPermissons.php`

From the bottom of the file put these at the top in alpha order

```
        Permission::findOrCreate('user index');
        Permission::findOrCreate('user view');
        Permission::findOrCreate('user export-pdf');
        Permission::findOrCreate('user export-excel');
        Permission::findOrCreate('user add');
        Permission::findOrCreate('user edit');
        Permission::findOrCreate('user delete');
```

From the bottom of the file, add these to admin

```
'user index',
'user view',
'user export-pdf',
'user export-excel',
'user add',
'user edit',
'user delete',
```

From the bottom of the file, add these to read-only

```
        'user index',
        'user view',
```

Then run the following to install the permissions

```
php artisan app:set-initial-permissions
```

### Components

In `resource/js/components`


Add

```
Vue.component('user-grid', () => import(/* webpackChunkName:"user-grid" */ './components/users/UserGrid.vue'));
Vue.component('user-form', () => import(/* webpackChunkName:"user-form" */ './components/users/UserForm.vue'));
Vue.component('user-show', () => import(/* webpackChunkName:"user-show" */ './components/users/UserShow.vue'));

```

### Routes

In `routes/web.php


Add

```
Route::get('/api-user', 'UserApi@index');
Route::get('/api-user/options', 'UserApi@getOptions');
Route::get('/user/download', 'UserController@download')->name('user.download');
Route::get('/user/print', 'UserController@print')->name('user.print');
Route::resource('/user', 'UserController');
```

#### Add to the menu in `resources/views/layouts/crud-nav.blade.php`

##### Menu

```
@can(['user index'])
<li class="nav-item @php if(isset($nav_path[0]) && $nav_path[0] == 'user') echo 'active' @endphp">
    <a class="nav-link" href="{{ route('user.index') }}">Users <span
            class="sr-only">(current)</span></a>
</li>
@endcan
```

##### Sub Menu

```
@can(['user index'])
<a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == 'user') echo 'active' @endphp"
   href="/user">Users</a>
@endcan
```



## Code Cleanup


```
app/Exports/UserExport.php
app/Http/Controlers/UserControler.php
app/Http/Controlers/UserApi.php
app/Http/Requests/UserFormRequest.php
app/Http/Requests/UserIndexRequest.php
app/Lib/Import/ImportUser.php
app/Observers/UserObserver.php
app/User.php
resources/js/components/usersresources/views/users
node_modules/.bin/prettier --write resources/js/components/users/" . [[modelname]] . 'Grid.vue'
node_modules/.bin/prettier --write resources/js/components/users/" . [[modelname]] . 'Form.vue'
node_modules/.bin/prettier --write resources/js/components/users/" . [[modelname]] . 'Show.vue'
```




## FORM Vue component example.
```
<std-form-group
    label="User"
    label-for="user_id"
    :errors="form_errors.user_id">
    <ui-select-pick-one
        url="/api-user/options"
        v-model="form_data.user_id"
        :selected_id="form_data.user_id"
        name="user_id"
        :blank_value="0">
    </ui-select-pick-one>
</std-form-group>


import UiSelectPickOne from "../SS/UiSelectPickOne";

components: { UiSelectPickOne },
```

## GRID Vue Component example

```
<search-form-group
    class="mb-0"
    label="User"
    label-for="user_id"
    :errors="form_errors.user_id">
    <ui-select-pick-one
        url="/api-user/options"
        v-model="form_data.user_id"
        :selected_id="form_data.user_id"
        name="user_id"
        blank_text="-- Select One --"
        blank_value="0"
        additional_classes="mb-2 grid-filter">
    </ui-select-pick-one>
</search-form-group>
```
## Blade component example.

### In Controller

```
$user_options = \App\User::getOptions();
```


### In View

```
@component('../components/select-pick-one', [
'fld' => 'user_id',
'selected_id' => $RECORD->user_id,
'first_option' => 'Select a Users',
'options' => $user_options
])
@endcomponent
```

## Old Stuff that can be ignored

#### Components
 
 In `resource/js/components`
 
Remove

```
Vue.component('user', require('./components/user.vue').default);
```

#### Remove dead code

```
rm app/Queries/GridQueries/UserQuery.php
rm resources/js/components/UserGrid.vue
```


#### Remove from routes

```
Route::get('api/owner-all', '\\App\Queries\GridQueries\OwnerQuery@getAllForSelect');
Route::get('api/owner-one', '\\App\Queries\GridQueries\OwnerQuery@selectOne');
```

#### Remove the Grid Method
vi app/Http/Controllers/ApiController.php


```
// Begin Owner Api Data Grid Method

public function ownerData(Request $request)
{

return GridQuery::sendData($request, 'OwnerQuery');
 
}
 
// End Owner Api Data Grid Method
```
