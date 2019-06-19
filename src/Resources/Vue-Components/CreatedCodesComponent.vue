<template>
    <div class="codes-created">
        <h4>Created Codes</h4>
        <section class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Value</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tr v-for="code in codes" :class="{'text-muted': !code.exported}">
                    <td class="w-100">{{ code.code }}</td>
                    <td>{{ code.value }}</td>
                    <td>{{ code.created_at | moment('calendar') }}</td>
                </tr>
            </table>
        </section>
    </div>
</template>

<script>
    export default {
        name: "created-codes-component",
        data: () => ({
            codes: []
        }),
        mounted() {
            this.getCreatedCodes();
        },
        methods: {
            async getCreatedCodes() {
                return await axios.get(route('credits.code.index'))
                    .then(response => {
                        this.codes = response.data;
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }
        }
    }
</script>

<style scoped>
    tr {
        white-space: nowrap;
    }
</style>