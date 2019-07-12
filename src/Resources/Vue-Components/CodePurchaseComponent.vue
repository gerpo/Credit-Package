<template>
    <div class="code-purchase form-inline">
        <p class="lead w-100 mb-1">{{ $tv('DmsCredits::code.redeem_code') }}:</p>
        <input class="form-control flex-grow-1 mr-2" id="code-redeem-field" title="redeem code" type="text"
               v-model="code.code">
        <button @click="redeemCode" class="btn btn-primary">
            Redeem
            <span aria-hidden="true" class="spinner-border spinner-border-sm ml-1" role="status"
                  v-if="isLoading"></span>
        </button>
    </div>
</template>

<script>
    export default {
        name: "code-purchase-component",
        data: () => ({
            code: {
                code: '',
            },
            isLoading: false,
        }),
        mounted() {
            document.getElementById('code-redeem-field').focus();
        },
        methods: {
            async redeemCode() {
                if (!this.code.code) {
                    return this.$notify('You need to provide a code.')
                }
                this.isLoading = true;
                return axios.post(route('credits.code.redeem'), this.code)
                    .then(response => {
                        this.$notify({text: 'Code was successfully redeemed.', type: 'success'});
                        this.$emit('success');
                        this.code = '';
                    })
                    .catch(error => {
                        this.$notify({text: 'Code could not be redeemed.', type: 'error'});
                    }).finally(() =>
                        this.isLoading = false
                    )
            }
        }
    }
</script>

<style scoped>

</style>