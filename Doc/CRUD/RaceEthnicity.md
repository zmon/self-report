# RaceEthnicity - `raceethnicities`

## To create or replace missing CRUD

```
php artisan make:crud raceethnicities  --display-name="RaceEthnicities" --grid-columns="name"   # --force --skip-append
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
        Permission::findOrCreate('race_ethnicity index');
        Permission::findOrCreate('race_ethnicity view');
        Permission::findOrCreate('race_ethnicity export-pdf');
        Permission::findOrCreate('race_ethnicity export-excel');
        Permission::findOrCreate('race_ethnicity add');
        Permission::findOrCreate('race_ethnicity edit');
        Permission::findOrCreate('race_ethnicity delete');
```

From the bottom of the file, add these to admin

```
'race_ethnicity index',
'race_ethnicity view',
'race_ethnicity export-pdf',
'race_ethnicity export-excel',
'race_ethnicity add',
'race_ethnicity edit',
'race_ethnicity delete',
```

From the bottom of the file, add these to read-only

```
        'race_ethnicity index',
        'race_ethnicity view',
```

Then run the following to install the permissions

```
php artisan app:set-initial-permissions
```

### Components

In `resource/js/components`


Add

```
Vue.component('race-ethnicity-grid', () => import(/* webpackChunkName:"race-ethnicity-grid" */ './components/race_ethnicities/RaceEthnicityGrid.vue'));
Vue.component('race-ethnicity-form', () => import(/* webpackChunkName:"race-ethnicity-form" */ './components/race_ethnicities/RaceEthnicityForm.vue'));
Vue.component('race-ethnicity-show', () => import(/* webpackChunkName:"race-ethnicity-show" */ './components/race_ethnicities/RaceEthnicityShow.vue'));

```

### Routes

In `routes/web.php


Add

```
Route::get('/api-race-ethnicity', 'RaceEthnicityApi@index');
Route::get('/api-race-ethnicity/options', 'RaceEthnicityApi@getOptions');
Route::get('/race-ethnicity/download', 'RaceEthnicityController@download')->name('race-ethnicity.download');
Route::get('/race-ethnicity/print', 'RaceEthnicityController@print')->name('race-ethnicity.print');
Route::resource('/race-ethnicity', 'RaceEthnicityController');
```

#### Add to the menu in `resources/views/layouts/crud-nav.blade.php`

##### Menu

```
@can(['race_ethnicity index'])
<li class="nav-item @php if(isset($nav_path[0]) && $nav_path[0] == 'race-ethnicity') echo 'active' @endphp">
    <a class="nav-link" href="{{ route('race-ethnicity.index') }}">Rax Ethnicity <span
            class="sr-only">(current)</span></a>
</li>
@endcan
```

##### Sub Menu

```
@can(['race_ethnicity index'])
<a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == 'race-ethnicity') echo 'active' @endphp"
   href="/race-ethnicity">Rax Ethnicity</a>
@endcan
```



## Code Cleanup


```
app/Exports/RaceEthnicityExport.php
app/Http/Controlers/RaceEthnicityControler.php
app/Http/Controlers/RaceEthnicityApi.php
app/Http/Requests/RaceEthnicityFormRequest.php
app/Http/Requests/RaceEthnicityIndexRequest.php
app/Lib/Import/ImportRaceEthnicity.php
app/Observers/RaceEthnicityObserver.php
app/RaceEthnicity.php
resources/js/components/race_ethnicitiesresources/views/race_ethnicities
node_modules/.bin/prettier --write resources/js/components/race_ethnicities/" . [[modelname]] . 'Grid.vue'
node_modules/.bin/prettier --write resources/js/components/race_ethnicities/" . [[modelname]] . 'Form.vue'
node_modules/.bin/prettier --write resources/js/components/race_ethnicities/" . [[modelname]] . 'Show.vue'
```




## FORM Vue component example.
```
<std-form-group
    label="RaceEthnicity"
    label-for="race_ethnicity_id"
    :errors="form_errors.race_ethnicity_id">
    <ui-select-pick-one
        url="/api-race-ethnicity/options"
        v-model="form_data.race_ethnicity_id"
        :selected_id="form_data.race_ethnicity_id"
        name="race_ethnicity_id"
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
    label="RaceEthnicity"
    label-for="race_ethnicity_id"
    :errors="form_errors.race_ethnicity_id">
    <ui-select-pick-one
        url="/api-race-ethnicity/options"
        v-model="form_data.race_ethnicity_id"
        :selected_id="form_data.race_ethnicity_id"
        name="race_ethnicity_id"
        blank_text="-- Select One --"
        blank_value="0"
        additional_classes="mb-2 grid-filter">
    </ui-select-pick-one>
</search-form-group>
```
## Blade component example.

### In Controller

```
$race_ethnicity_options = \App\RaceEthnicity::getOptions();
```


### In View

```
@component('../components/select-pick-one', [
'fld' => 'race_ethnicity_id',
'selected_id' => $RECORD->race_ethnicity_id,
'first_option' => 'Select a RaceEthnicities',
'options' => $race_ethnicity_options
])
@endcomponent
```

## Old Stuff that can be ignored

#### Components
 
 In `resource/js/components`
 
Remove

```
Vue.component('race_ethnicity', require('./components/race_ethnicity.vue').default);
```

#### Remove dead code

```
rm app/Queries/GridQueries/RaceEthnicityQuery.php
rm resources/js/components/RaceEthnicityGrid.vue
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
