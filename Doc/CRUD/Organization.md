# Organization - `organizations`

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
php artisan lbv:set-initial-permissions
```

### Components

In `resource/js/components`

Remove

```
Vue.component('organization', require('./components/organization.vue').default);
```

Add

```
Vue.component('organization', () => import(/* webpackChunkName:"organization" */ './components/organizations/organization.vue'));
Vue.component('organization', () => import(/* webpackChunkName:"organization" */ './components/organizations/organization.vue'));
Vue.component('organization', () => import(/* webpackChunkName:"organization" */ './components/organizations/organization.vue'));

```

#### Add to the menu in `resources/views/layouts/crud-nav.blade.php`

##### Menu

```
@can(['organization index'])
<li class="nav-item @php if(isset($nav_path[0]) && $nav_path[0] == 'organization') echo 'active' @endphp">
    <a class="nav-link" href="{{ route('organization.index') }}">organizations <span
            class="sr-only">(current)</span></a>
</li>
@endcan
```

##### Sub Menu

```
@can(['organization index'])
<a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == 'organization') echo 'active' @endphp"
   href="/organization">organizations</a>
@endcan
```

#### Remove dead code

```
rm app/Queries/GridQueries/OrganizationQuery.php
rm resources/js/components/OrganizationGrid.vue
```

###

Remove from routes

```
Route::get('api/owner-all', '\\App\Queries\GridQueries\OwnerQuery@getAllForSelect');
Route::get('api/owner-one', '\\App\Queries\GridQueries\OwnerQuery@selectOne');
```

vi app/Http/Controllers/ApiController.php

Remove the Grid Method

```
// Begin Owner Api Data Grid Method

public function ownerData(Request $request)
{

return GridQuery::sendData($request, 'OwnerQuery');

}

// End Owner Api Data Grid Method
```

#### Code Cleanup

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




## Vue component example.
```
<ui-select-pick-one
    url="/api-organization/options"
    v-model="organizationSelected"
    :selected_id=organizationSelected"
    name="organization">
</ui-select-pick-one>
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

