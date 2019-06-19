<template>
    <div class="code-purchase form-inline">
        <p class="lead w-100 mb-1">{{ $tv('DmsCredits::code.redeem_code') }}:</p>
        <input type="text" title="redeem code" class="form-control flex-grow-1 mr-2" v-model="code.code">
        <button class="btn btn-primary" @click="redeemCode">Redeem</button>
    </div>
</template>

<script>
    export default {
        name: "code-purchase-component",
        data: () => ({
            code: {
                code: ''
            }
        }),
        methods: {
            async redeemCode() {
                if (!this.code.code) {
                    return this.flash('You need to provide a code.', 'info')
                }
                return axios.post(route('credits.code.redeem'), this.code)
                    .then(response => {
                        this.flash('Code was successfully redeemed.', 'success');
                        this.$emit('success');
                        this.code = '';
                    })
                    .catch(error => {
                        this.flash('Code could not be redeemed.', 'danger');
                    })
            }
        }
    }
</script>

<style scoped>

</style>