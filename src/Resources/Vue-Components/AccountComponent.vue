<template>
    <div class="d-flex flex-wrap align-items-end justify-content-between">
        <div class="balance-wrapper text-center rounded p-3">
            <p>{{ $tv('DmsCredits.account.balance_long') }}</p>
            <div class="balance">{{ workingAccount.balance }}</div>
        </div>
        <transfer-Credits-component class="ml-auto mr-2 ml-auto mr-2"></transfer-Credits-component>
        <purchase-component v-on:purchase-success="refetchAccount"/>
        <hr class="w-100">
        <account-transactions :transactions="workingAccount.transactions" class="w-100"/>
    </div>
</template>

<script>
    import AccountTransactions from './AccountTransactionsComponent.vue';
    import TransferCreditsComponent from './TransferCreditsComponent';
    import PurchaseComponent from './PurchaseComponent.vue';

    export default {
        name: "account-component",
        components: {
            TransferCreditsComponent,
            PurchaseComponent,
            AccountTransactions,
        },
        props: {
            account: {
                default: () => {
                }, type: [Object]
            }
        },
        data: () => ({
            workingAccount: {},
        }),
        created() {
            this.workingAccount = this.account
        },
        methods: {
            async refetchAccount() {
                return await axios.get(route('credits.index'))
                    .then(response => {
                        this.workingAccount = response.data
                    })
                    .catch(error => {
                        console.log(error)
                    });
            }
        }
    }
</script>

<style scoped>
    .balance-wrapper {
        border: 2px solid gray;
        background-color: hsla(211, 100%, 35%, 0.1);
    }

    .balance {
        font-size: 2.5rem;
        line-height: 1.3rem;
    }
</style>