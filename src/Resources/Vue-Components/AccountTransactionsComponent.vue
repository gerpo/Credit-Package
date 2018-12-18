<template>
    <div class="transactions">
        <h4>Transactions</h4>
        <table-component :data="transactions"
                         table-class="table table-sm table-hover"
                         :show-filter="false"
                         sort-by="created_at"
                         sort-order="desc">
            <table-column show="created_at">
                <template slot-scope="row">
                    {{ row.created_at | moment('calendar') }}
                </template>
            </table-column>
            <table-column show="message" header-class="w-75" child-class="w-75"></table-column>
            <table-column show="amount" data-type="numeric"></table-column>
        </table-component>
    </div>
</template>

<script>
    import {TableColumn, TableComponent} from 'vue-table-component';

    export default {
        name: "account-transactions",
        components: {
            TableComponent,
            TableColumn
        },
        props: {
            transactions: {default: () => [], type: [Array]}
        },

    }
</script>

<style scoped>

</style>