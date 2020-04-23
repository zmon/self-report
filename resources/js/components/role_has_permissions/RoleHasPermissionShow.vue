<template>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row mb-2 mb-md-0 text-only">
                <label class="col-md-4 col-form-label text-md-right">
                    Permission Id
                </label>
                <div class="col-md-8">
                    <dsp-text v-model="record.permission_id"/>
                </div>
            </div>
            <div class="form-group row mb-2 mb-md-0 text-only">
                <label class="col-md-4 col-form-label text-md-right">
                    Role Id
                </label>
                <div class="col-md-8">
                    <dsp-text v-model="record.role_id"/>
                </div>
            </div>
            <div class="form-group row mb-2 mb-md-0 text-only">
                <label class="col-md-4 col-form-label text-md-right">
                    Deleted At
                </label>
                <div class="col-md-8">
                    <dsp-text v-model="record.deleted_at"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "role-has-permission-show",
        props: {
            record: {
                type: [Boolean, Object],
                default: false
            }
        }
    };
</script>
