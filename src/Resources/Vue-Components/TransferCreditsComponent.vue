<template>
    <div class="transfer-wrapper">
        <button @click="showTransferModal = true" class="btn btn-primary text-capitalize">
            {{ $tv('DmsCredits::account.transfer_credits_btn') }}
        </button>
        <modal-component :show="showTransferModal" @close="showTransferModal = false">
            <div class="credit-transfer form-inline">
                <p class="lead w-100 mb-1">{{ $tv('DmsCredits::account.transfer_credits') }}:</p>
                <input :class="{'is-invalid': this.errors.hasOwnProperty('target')}" class="form-control flex-grow-1 mr-2" placeholder="Username"
                       title="transfer credits" type="text"
                       v-model="target">
                <input :class="{'is-invalid': this.errors.hasOwnProperty('amount')}" class="form-control flex-grow-1 mr-2" min="1"
                       placeholder="Amount" title="amount" type="number"
                       v-model="amount">
                <button @click="transferCredits" class="btn btn-primary">
                    {{ $tv('DmsCredits::account.send_credits') }}
                    <span aria-hidden="true" class="spinner-border spinner-border-sm ml-1" role="status"
                          v-if="isLoading"></span>
                </button>
            </div>
        </modal-component>
    </div>
</template>

<script>
    import ModalComponent from "@/components/ModalComponent";

    export default {
        name: "TransferCreditsComponent",
        components: {
            ModalComponent
        },
        data: () => ({
            showTransferModal: false,
            target: '',
            amount: '',
            isLoading: false,
            errors: {},
        }),
        methods: {
            purchaseMade() {
                this.$emit('transfer-success');
                this.showTransferModal = false;
            },
            async transferCredits() {
                this.isLoading = true;
                await axios.post(route('credits.transfer'), {target: this.target, amount: this.amount})
                    .then(response => {
                        this.$notify({text: 'Credits successful transferred.', type: 'success'});
                        this.purchaseMade();
                    })
                    .catch(error => {
                        this.$notify({text: 'Transfer could not be completed.', type: 'error'});
                        this.errors = error.response.data.errors;
                    }).finally(() => this.isLoading = false)
            }
        }
    }
</script>

<style scoped>

</style>