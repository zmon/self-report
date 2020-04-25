<template>
    <div>
        <fieldset class="mb-4">
            <legend>General Information</legend>
            <div class="row">
                <div class="col-md-6">

                                <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Name                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.name"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Alias                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.alias"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Url Code                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.url_code"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Contact Name                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.contact_name"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Title                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.title"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Phone 1                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.phone_1"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Email                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.email"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Notes                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.notes"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Active                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.active"/>
                    </div>
                </div>
                
                </div>
            </div>
        </fieldset>
    </div>
</template>

<script>
    export default {
        name: "organization-show",
        props: {
            record: {
                type: [Boolean, Object],
                default: false,
            },
        },
    };
</script>
