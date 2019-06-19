<template>
    <div class="transactions">
        <h4>Transactions</h4>
        <table-component :data="transactions"
                         table-class="table table-sm table-hover"
                         :show-filter="false"
                         :show-caption="false"
                         sort-by="created_at"
                         sort-order="desc">
            <table-column :label="$tv('DmsCredits::account.created_at')" header-class="text-capitalize" show="created_at">
                <template slot-scope="row">
                    {{ row.created_at | moment('calendar') }}
                </template>
            </table-column>
            <table-column :label="$tv('DmsCredits::account.activity')"
                          header-class="w-75 text-capitalize" child-class="w-75">
                <template slot-scope="row">
                    <div>{{ $tv(row.message, {value: row.amount}) }}</div>
                </template>
            </table-column>
            <table-column :label="$tv('DmsCredits::account.amount')" header-class="text-capitalize" show="amount" data-type="numeric"/>
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