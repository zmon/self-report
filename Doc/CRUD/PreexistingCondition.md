# PreexistingCondition - `preexistingconditions`

## To create or replace missing CRUD

```
php artisan make:crud preexistingconditions  --display-name="PreexistingConditions" --grid-columns="name"   # --force --skip-append
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
        Permission::findOrCreate('preexisting_condition index');
        Permission::findOrCreate('preexisting_condition view');
        Permission::findOrCreate('preexisting_condition export-pdf');
        Permission::findOrCreate('preexisting_condition export-excel');
        Permission::findOrCreate('preexisting_condition add');
        Permission::findOrCreate('preexisting_condition edit');
        Permission::findOrCreate('preexisting_condition delete');
```

From the bottom of the file, add these to admin

```
'preexisting_condition index',
'preexisting_condition view',
'preexisting_condition export-pdf',
'preexisting_condition export-excel',
'preexisting_condition add',
'preexisting_condition edit',
'preexisting_condition delete',
```

From the bottom of the file, add these to read-only

```
        'preexisting_condition index',
        'preexisting_condition view',
```

Then run the following to install the permissions

```
php artisan app:set-initial-permissions
```

### Components

In `resource/js/components`


Add

```
Vue.component('preexisting-condition-grid', () => import(/* webpackChunkName:"preexisting-condition-grid" */ './components/preexisting_conditions/PreexistingConditionGrid.vue'));
Vue.component('preexisting-condition-form', () => import(/* webpackChunkName:"preexisting-condition-form" */ './components/preexisting_conditions/PreexistingConditionForm.vue'));
Vue.component('preexisting-condition-show', () => import(/* webpackChunkName:"preexisting-condition-show" */ './components/preexisting_conditions/PreexistingConditionShow.vue'));

```

### Routes

In `routes/web.php


Add

```
Route::get('/api-preexisting-condition', 'PreexistingConditionApi@index');
Route::get('/api-preexisting-condition/options', 'PreexistingConditionApi@getOptions');
Route::get('/preexisting-condition/download', 'PreexistingConditionController@download')->name('preexisting-condition.download');
Route::get('/preexisting-condition/print', 'PreexistingConditionController@print')->name('preexisting-condition.print');
Route::resource('/preexisting-condition', 'PreexistingConditionController');
```

#### Add to the menu in `resources/views/layouts/crud-nav.blade.php`

##### Menu

```
@can(['preexisting_condition index'])
<li class="nav-item @php if(isset($nav_path[0]) && $nav_path[0] == 'preexisting-condition') echo 'active' @endphp">
    <a class="nav-link" href="{{ route('preexisting-condition.index') }}">Preexisting Conditions <span
            class="sr-only">(current)</span></a>
</li>
@endcan
```

##### Sub Menu

```
@can(['preexisting_condition index'])
<a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == 'preexisting-condition') echo 'active' @endphp"
   href="/preexisting-condition">Preexisting Conditions</a>
@endcan
```



## Code Cleanup


```
app/Exports/PreexistingConditionExport.php
app/Http/Controlers/PreexistingConditionControler.php
app/Http/Controlers/PreexistingConditionApi.php
app/Http/Requests/PreexistingConditionFormRequest.php
app/Http/Requests/PreexistingConditionIndexRequest.php
app/Lib/Import/ImportPreexistingCondition.php
app/Observers/PreexistingConditionObserver.php
app/PreexistingCondition.php
resources/js/components/preexisting_conditionsresources/views/preexisting_conditions
node_modules/.bin/prettier --write resources/js/components/preexisting_conditions/" . [[modelname]] . 'Grid.vue'
node_modules/.bin/prettier --write resources/js/components/preexisting_conditions/" . [[modelname]] . 'Form.vue'
node_modules/.bin/prettier --write resources/js/components/preexisting_conditions/" . [[modelname]] . 'Show.vue'
```




## FORM Vue component example.
```
<std-form-group
    label="PreexistingCondition"
    label-for="preexisting_condition_id"
    :errors="form_errors.preexisting_condition_id">
    <ui-select-pick-one
        url="/api-preexisting-condition/options"
        v-model="form_data.preexisting_condition_id"
        :selected_id="form_data.preexisting_condition_id"
        name="preexisting_condition_id"
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
    label="PreexistingCondition"
    label-for="preexisting_condition_id"
    :errors="form_errors.preexisting_condition_id">
    <ui-select-pick-one
        url="/api-preexisting-condition/options"
        v-model="form_data.preexisting_condition_id"
        :selected_id="form_data.preexisting_condition_id"
        name="preexisting_condition_id"
        blank_text="-- Select One --"
        blank_value="0"
        additional_classes="mb-2 grid-filter">
    </ui-select-pick-one>
</search-form-group>
```
## Blade component example.

### In Controller

```
$preexisting_condition_options = \App\PreexistingCondition::getOptions();
```


### In View

```
@component('../components/select-pick-one', [
'fld' => 'preexisting_condition_id',
'selected_id' => $RECORD->preexisting_condition_id,
'first_option' => 'Select a PreexistingConditions',
'options' => $preexisting_condition_options
])
@endcomponent
```

## Old Stuff that can be ignored

#### Components
 
 In `resource/js/components`
 
Remove

```
Vue.component('preexisting_condition', require('./components/preexisting_condition.vue').default);
```

#### Remove dead code

```
rm app/Queries/GridQueries/PreexistingConditionQuery.php
rm resources/js/components/PreexistingConditionGrid.vue
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
