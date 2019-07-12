<template>
    <div class="code-generator">
        <h4>Generate Codes</h4>
        <div class="d-flex flex-wrap w-100">
            <label class="sr-only" for="code-value">Value</label>
            <input type="number" min="0" class="form-control mb-2 mr-sm-2" id="code-value" placeholder="Code Value" v-model="codes.value">

            <label class="sr-only" for="code-amount">Amount</label>
            <input type="number" min="0" class="form-control mb-2 mr-sm-2" id="code-amount"
                   placeholder="Amount" v-model="codes.amount">


            <button type="submit" class="btn btn-primary mb-2" @click="generateCodes">Generate</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "code-generator-component",
        data: () => ({
            codes:{
                value: '',
                amount: ''
            }
        }),
        methods: {
            async generateCodes() {
                return await axios.post(route('credits.code.create'), this.codes)
                    .then(response => {
                        this.reset();
                        this.$emit('close');
                        this.flash('Credits will be generated. That can take some minutes.', 'success')
                    })
                    .catch(error => {
                        console.log(error);
                        this.flash('Code generation could not be triggered.', 'error');
                    })
            },
            reset() {
                this.codes.value = '';
                this.codes.amount = '';
            }
        }
    }
</script>

<style scoped>
    .form-control {
        flex: 1;
    }
</style>