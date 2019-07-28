<template>
    <div class="transactions">
        <h4>Transactions</h4>
        <table-component :data="transactions" :searchable="false" sort-by="created_at" sort-order="desc">
            <template #columns>
                <th>{{ $tv('DmsCredits::account.created_at') }}</th>
                <th>{{ $tv('DmsCredits::account.activity') }}</th>
                <th class="text-right">{{ $tv('DmsCredits::account.amount') }}</th>
            </template>
            <template slot-scope="{row, index}">
                <td>{{ row.created_at | moment('calendar') }}</td>
                <td class="w-100">{{ $tv(row.message, {value: Math.abs(row.amount), source: row.source, target: row.target }) }}</td>
                <td :class="{'text-danger': row.amount < 0}" class="text-right">{{ row.amount | number('-') }}</td>
            </template>
        </table-component>
    </div>
</template>

<script>
    import TableComponent from "@/components/TableComponent";

    export default {
        name: "account-transactions",
        components: {
            TableComponent
        },
        props: {
            transactions: {default: () => [], type: [Array]}
        },
    }
</script>

<style scoped>

</style>