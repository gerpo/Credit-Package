<template>
    <div class="purchase-wrapper">
        <button class="btn btn-primary text-capitalize" @click="showPurchaseModal = true">
            {{ $tv('DmsCredits::account.add_credits_btn') }}
        </button>
        <modal-component :show="showPurchaseModal" @close="showPurchaseModal = false">
            <code-purchase-component v-on:success="purchaseMade"/>
        </modal-component>
    </div>
</template>

<script>
    import ModalComponent from "@/components/ModalComponent";
    import CodePurchaseComponent from "./CodePurchaseComponent";

    export default {
        name: "purchase-component",
        components: {
            CodePurchaseComponent,
            ModalComponent
        },
        data: () => ({
            showPurchaseModal: false
        }),
        methods: {
            purchaseMade() {
                this.$emit('purchase-success');
                this.showPurchaseModal = false;
            }
        }
    }
</script>

<style scoped>

</style>