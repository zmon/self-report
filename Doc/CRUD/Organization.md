# Organization - `organizations`

## To create or replace missing CRUD

```
php artisan make:crud organizations  --display-name="Organizations" --grid-columns="name"   # --force --skip-append
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
        Permission::findOrCreate('organization index');
        Permission::findOrCreate('organization view');
        Permission::findOrCreate('organization export-pdf');
        Permission::findOrCreate('organization export-excel');
        Permission::findOrCreate('organization add');
        Permission::findOrCreate('organization edit');
        Permission::findOrCreate('organization delete');
```

From the bottom of the file, add these to admin

```
'organization index',
'organization view',
'organization export-pdf',
'organization export-excel',
'organization add',
'organization edit',
'organization delete',
```

From the bottom of the file, add these to read-only

```
        'organization index',
        'organization view',
```

Then run the following to install the permissions

```
php artisan app:set-initial-permissions
```

### Components

In `resource/js/components`


Add

```
Vue.component('organization-grid', () => import(/* webpackChunkName:"organization-grid" */ './components/organizations/OrganizationGrid.vue'));
Vue.component('organization-form', () => import(/* webpackChunkName:"organization-form" */ './components/organizations/OrganizationForm.vue'));
Vue.component('organization-show', () => import(/* webpackChunkName:"organization-show" */ './components/organizations/OrganizationShow.vue'));

```

### Routes

In `routes/web.php


Add

```
Route::get('/api-organization', 'OrganizationApi@index');
Route::get('/api-organization/options', 'OrganizationApi@getOptions');
Route::get('/organization/download', 'OrganizationController@download')->name('organization.download');
Route::get('/organization/print', 'OrganizationController@print')->name('organization.print');
Route::resource('/organization', 'OrganizationController');
```

#### Add to the menu in `resources/views/layouts/crud-nav.blade.php`

##### Menu

```
@can(['organization index'])
<li class="nav-item @php if(isset($nav_path[0]) && $nav_path[0] == 'organization') echo 'active' @endphp">
    <a class="nav-link" href="{{ route('organization.index') }}">Organizations <span
            class="sr-only">(current)</span></a>
</li>
@endcan
```

##### Sub Menu

```
@can(['organization index'])
<a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == 'organization') echo 'active' @endphp"
   href="/organization">Organizations</a>
@endcan
```



## Code Cleanup


```
app/Exports/OrganizationExport.php
app/Http/Controlers/OrganizationControler.php
app/Http/Controlers/OrganizationApi.php
app/Http/Requests/OrganizationFormRequest.php
app/Http/Requests/OrganizationIndexRequest.php
app/Lib/Import/ImportOrganization.php
app/Observers/OrganizationObserver.php
app/Organization.php
resources/js/components/organizationsresources/views/organizations
node_modules/.bin/prettier --write resources/js/components/organizations/" . [[modelname]] . 'Grid.vue'
node_modules/.bin/prettier --write resources/js/components/organizations/" . [[modelname]] . 'Form.vue'
node_modules/.bin/prettier --write resources/js/components/organizations/" . [[modelname]] . 'Show.vue'
```




## FORM Vue component example.
```
<std-form-group
    label="Organization"
    label-for="organization_id"
    :errors="form_errors.organization_id">
    <ui-select-pick-one
        url="/api-organization/options"
        v-model="form_data.organization_id"
        :selected_id="form_data.organization_id"
        name="organization_id"
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
    label="Organization"
    label-for="organization_id"
    :errors="form_errors.organization_id">
    <ui-select-pick-one
        url="/api-organization/options"
        v-model="form_data.organization_id"
        :selected_id="form_data.organization_id"
        name="organization_id"
        blank_text="-- Select One --"
        blank_value="0"
        additional_classes="mb-2 grid-filter">
    </ui-select-pick-one>
</search-form-group>
```
## Blade component example.

### In Controller

```
$organization_options = \App\Organization::getOptions();
```


### In View

```
@component('../components/select-pick-one', [
'fld' => 'organization_id',
'selected_id' => $RECORD->organization_id,
'first_option' => 'Select a Organizations',
'options' => $organization_options
])
@endcomponent
```

## Old Stuff that can be ignored

#### Components
 
 In `resource/js/components`
 
Remove

```
Vue.component('organization', require('./components/organization.vue').default);
```

#### Remove dead code

```
rm app/Queries/GridQueries/OrganizationQuery.php
rm resources/js/components/OrganizationGrid.vue
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
