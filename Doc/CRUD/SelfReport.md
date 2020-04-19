# SelfReport - `selfreports`

## To create or replace missing CRUD

```
php artisan make:crud selfreports  --display-name="SelfReports" --grid-columns="name"   # --force --skip-append
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
        Permission::findOrCreate('self_report index');
        Permission::findOrCreate('self_report view');
        Permission::findOrCreate('self_report export-pdf');
        Permission::findOrCreate('self_report export-excel');
        Permission::findOrCreate('self_report add');
        Permission::findOrCreate('self_report edit');
        Permission::findOrCreate('self_report delete');
```

From the bottom of the file, add these to admin

```
'self_report index',
'self_report view',
'self_report export-pdf',
'self_report export-excel',
'self_report add',
'self_report edit',
'self_report delete',
```

From the bottom of the file, add these to read-only

```
        'self_report index',
        'self_report view',
```

Then run the following to install the permissions

```
php artisan app:set-initial-permissions
```

### Components

In `resource/js/components`


Add

```
Vue.component('self-report-grid', () => import(/* webpackChunkName:"self-report-grid" */ './components/self_reports/SelfReportGrid.vue'));
Vue.component('self-report-form', () => import(/* webpackChunkName:"self-report-form" */ './components/self_reports/SelfReportForm.vue'));
Vue.component('self-report-show', () => import(/* webpackChunkName:"self-report-show" */ './components/self_reports/SelfReportShow.vue'));

```

### Routes

In `routes/web.php


Add

```
Route::get('/api-self-report', 'SelfReportApi@index');
Route::get('/api-self-report/options', 'SelfReportApi@getOptions');
Route::get('/self-report/download', 'SelfReportController@download')->name('self-report.download');
Route::get('/self-report/print', 'SelfReportController@print')->name('self-report.print');
Route::resource('/self-report', 'SelfReportController');
```

#### Add to the menu in `resources/views/layouts/crud-nav.blade.php`

##### Menu

```
@can(['self_report index'])
<li class="nav-item @php if(isset($nav_path[0]) && $nav_path[0] == 'self-report') echo 'active' @endphp">
    <a class="nav-link" href="{{ route('self-report.index') }}">Self Reports <span
            class="sr-only">(current)</span></a>
</li>
@endcan
```

##### Sub Menu

```
@can(['self_report index'])
<a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == 'self-report') echo 'active' @endphp"
   href="/self-report">Self Reports</a>
@endcan
```



## Code Cleanup


```
app/Exports/SelfReportExport.php
app/Http/Controlers/SelfReportControler.php
app/Http/Controlers/SelfReportApi.php
app/Http/Requests/SelfReportFormRequest.php
app/Http/Requests/SelfReportIndexRequest.php
app/Lib/Import/ImportSelfReport.php
app/Observers/SelfReportObserver.php
app/SelfReport.php
resources/js/components/self_reportsresources/views/self_reports
node_modules/.bin/prettier --write resources/js/components/self_reports/" . [[modelname]] . 'Grid.vue'
node_modules/.bin/prettier --write resources/js/components/self_reports/" . [[modelname]] . 'Form.vue'
node_modules/.bin/prettier --write resources/js/components/self_reports/" . [[modelname]] . 'Show.vue'
```




## FORM Vue component example.
```
<std-form-group
    label="SelfReport"
    label-for="self_report_id"
    :errors="form_errors.self_report_id">
    <ui-select-pick-one
        url="/api-self-report/options"
        v-model="form_data.self_report_id"
        :selected_id="form_data.self_report_id"
        name="self_report_id"
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
    label="SelfReport"
    label-for="self_report_id"
    :errors="form_errors.self_report_id">
    <ui-select-pick-one
        url="/api-self-report/options"
        v-model="form_data.self_report_id"
        :selected_id="form_data.self_report_id"
        name="self_report_id"
        blank_text="-- Select One --"
        blank_value="0"
        additional_classes="mb-2 grid-filter">
    </ui-select-pick-one>
</search-form-group>
```
## Blade component example.

### In Controller

```
$self_report_options = \App\SelfReport::getOptions();
```


### In View

```
@component('../components/select-pick-one', [
'fld' => 'self_report_id',
'selected_id' => $RECORD->self_report_id,
'first_option' => 'Select a SelfReports',
'options' => $self_report_options
])
@endcomponent
```

## Old Stuff that can be ignored

#### Components
 
 In `resource/js/components`
 
Remove

```
Vue.component('self_report', require('./components/self_report.vue').default);
```

#### Remove dead code

```
rm app/Queries/GridQueries/SelfReportQuery.php
rm resources/js/components/SelfReportGrid.vue
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
