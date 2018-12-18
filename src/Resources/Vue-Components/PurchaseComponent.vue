<template>
    <div class="purchase-wrapper">
        <button class="btn btn-primary" @click="showPurchaseModal = true">Purchase Credits</button>
        <modal-component :show="showPurchaseModal" @close="showPurchaseModal = false">
            <code-purchase-component />
            <p class="h3 my-4 text-center italic text-secondary">or</p>
            <stripe-purchase-component />
        </modal-component>
    </div>
</template>

<script>
    import ModalComponent from "../../components/ModalComponent";
    import CodePurchaseComponent from "./CodePurchaseComponent";
    import StripePurchaseComponent from "./StripePurchaseComponent";

    export default {
        name: "purchase-component",
        components: {
            StripePurchaseComponent,
            CodePurchaseComponent,
            ModalComponent},
        data: () => ({
            showPurchaseModal: false
        })
    }
</script>

<style scoped>

</style>