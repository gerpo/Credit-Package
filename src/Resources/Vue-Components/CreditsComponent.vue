<template>
    <div class="container">
        <div class="card">
            <div class="card-header text-capitalize">
                <span>{{ 'Credits' }}</span>
            </div>
            <div class="card-body d-flex flex-wrap align-items-end justify-content-between">
                <div class="balance-wrapper text-center rounded p-3">
                    <p>Your current Balance</p>
                    <div class="balance">{{ this.account.balance }}</div>
                </div>
                <purchase-component/>

                <hr class="w-100">

                <account-transactions :transactions="account.transactions" class="w-100"/>
            </div>
        </div>
    </div>
</template>

<script>
    import PurchaseComponent from './PurchaseComponent.vue';
    import AccountTransactions from './AccountTransactionsComponent.vue';


    export default {
        name: "credits-component",
        components: {
            PurchaseComponent,
            AccountTransactions
        },
        props: {
            account: {
                default: () => {
                }, type: [Object]
            }
        },
        data: () => ({}),
    }
</script>

<style scoped>
    .balance-wrapper{
        border: 2px solid gray;
        background-color: hsla(211, 100%, 35%, 0.1);
    }

    .balance {
        font-size: 2.5rem;
        line-height: 1.3rem;
    }
</style>s