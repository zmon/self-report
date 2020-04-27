<template>
    <div>
        <table class="table table-striped table-hover mb-0">
            <thead>
            <tr>
                <th>Organization</th>
                <th class="text-right">Count</th>
            </tr>
            </thead>
            <tbody>

            <tr v-if="!records.length">
                <td colspan="8" class="grid-alert">
                    <div class="alert alert-warning"
                         role="alert">No matching records found.
                    </div>
                </td>
            </tr>

            <tr v-else v-for="row in records" >


                <td data-title="Organization name">{{ row.organization_name }}</td>
                <td data-title="Count of Reports" class="text-right">{{ row.cnt }}</td>

            </tr>


            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "self-report-summary",
        props: {
            records: {
                type: [Boolean, Object, Array],
                default: false,
            },
        }
    }
</script>

<style scoped>

</style>
