# Symptom - `symptoms`

## To create or replace missing CRUD

```
php artisan make:crud symptoms  --display-name="Symptoms" --grid-columns="name"   # --force --skip-append
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
        Permission::findOrCreate('symptom index');
        Permission::findOrCreate('symptom view');
        Permission::findOrCreate('symptom export-pdf');
        Permission::findOrCreate('symptom export-excel');
        Permission::findOrCreate('symptom add');
        Permission::findOrCreate('symptom edit');
        Permission::findOrCreate('symptom delete');
```

From the bottom of the file, add these to admin

```
'symptom index',
'symptom view',
'symptom export-pdf',
'symptom export-excel',
'symptom add',
'symptom edit',
'symptom delete',
```

From the bottom of the file, add these to read-only

```
        'symptom index',
        'symptom view',
```

Then run the following to install the permissions

```
php artisan app:set-initial-permissions
```

### Components

In `resource/js/components`


Add

```
Vue.component('symptom-grid', () => import(/* webpackChunkName:"symptom-grid" */ './components/symptoms/SymptomGrid.vue'));
Vue.component('symptom-form', () => import(/* webpackChunkName:"symptom-form" */ './components/symptoms/SymptomForm.vue'));
Vue.component('symptom-show', () => import(/* webpackChunkName:"symptom-show" */ './components/symptoms/SymptomShow.vue'));

```

### Routes

In `routes/web.php


Add

```
Route::get('/api-symptom', 'SymptomApi@index');
Route::get('/api-symptom/options', 'SymptomApi@getOptions');
Route::get('/symptom/download', 'SymptomController@download')->name('symptom.download');
Route::get('/symptom/print', 'SymptomController@print')->name('symptom.print');
Route::resource('/symptom', 'SymptomController');
```

#### Add to the menu in `resources/views/layouts/crud-nav.blade.php`

##### Menu

```
@can(['symptom index'])
<li class="nav-item @php if(isset($nav_path[0]) && $nav_path[0] == 'symptom') echo 'active' @endphp">
    <a class="nav-link" href="{{ route('symptom.index') }}">Symptoms <span
            class="sr-only">(current)</span></a>
</li>
@endcan
```

##### Sub Menu

```
@can(['symptom index'])
<a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == 'symptom') echo 'active' @endphp"
   href="/symptom">Symptoms</a>
@endcan
```



## Code Cleanup


```
app/Exports/SymptomExport.php
app/Http/Controlers/SymptomControler.php
app/Http/Controlers/SymptomApi.php
app/Http/Requests/SymptomFormRequest.php
app/Http/Requests/SymptomIndexRequest.php
app/Lib/Import/ImportSymptom.php
app/Observers/SymptomObserver.php
app/Symptom.php
resources/js/components/symptomsresources/views/symptoms
node_modules/.bin/prettier --write resources/js/components/symptoms/" . [[modelname]] . 'Grid.vue'
node_modules/.bin/prettier --write resources/js/components/symptoms/" . [[modelname]] . 'Form.vue'
node_modules/.bin/prettier --write resources/js/components/symptoms/" . [[modelname]] . 'Show.vue'
```




## FORM Vue component example.
```
<std-form-group
    label="Symptom"
    label-for="symptom_id"
    :errors="form_errors.symptom_id">
    <ui-select-pick-one
        url="/api-symptom/options"
        v-model="form_data.symptom_id"
        :selected_id="form_data.symptom_id"
        name="symptom_id"
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
    label="Symptom"
    label-for="symptom_id"
    :errors="form_errors.symptom_id">
    <ui-select-pick-one
        url="/api-symptom/options"
        v-model="form_data.symptom_id"
        :selected_id="form_data.symptom_id"
        name="symptom_id"
        blank_text="-- Select One --"
        blank_value="0"
        additional_classes="mb-2 grid-filter">
    </ui-select-pick-one>
</search-form-group>
```
## Blade component example.

### In Controller

```
$symptom_options = \App\Symptom::getOptions();
```


### In View

```
@component('../components/select-pick-one', [
'fld' => 'symptom_id',
'selected_id' => $RECORD->symptom_id,
'first_option' => 'Select a Symptoms',
'options' => $symptom_options
])
@endcomponent
```

## Old Stuff that can be ignored

#### Components
 
 In `resource/js/components`
 
Remove

```
Vue.component('symptom', require('./components/symptom.vue').default);
```

#### Remove dead code

```
rm app/Queries/GridQueries/SymptomQuery.php
rm resources/js/components/SymptomGrid.vue
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
