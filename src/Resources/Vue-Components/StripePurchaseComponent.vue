<template>
    <div class="stripe-purchase">
        <p class="lead w-100 mb-1">Purchase new credits:</p>
        <form action="" method="post" class="form-inline" ref="stripe">
            <label for="payment-element"></label>
            <div id="payment-element" class="w-75 text-center" ref="card"></div>
            <div id="card-errors" role="alert">{{ this.errors }}</div>
            <button @click.prevent="createSource">Submit Payment</button>
        </form>
    </div>
</template>

<script>
    const stripe = Stripe('pk_test_k0HRqxENPkTqQb55Y14pC64r');
    const elements = stripe.elements();
    const style = {
        base: {
            // Add your base input styles here. For example:
            fontSize: '16px',
            color: "#32325d",
        },
    };
    const ownerInfo = {
        owner: {
            name: 'Jenny Rosen',
            address: {
                line1: 'Nollendorfstraße 27',
                city: 'Berlin',
                postal_code: '10777',
                country: 'DE',
            },
            email: 'jenny.rosen@example.com'
        },
    };

    export default {
        name: "stripe-purchase-component",
        data: () => ({
            card: undefined,
            errors: '',
            source: {}
        }),
        mounted() {
            this.card = elements.create('card', {style});
            this.card.mount(this.$refs.card);
        },
        methods: {
            async createSource(){
                const {source, error} = await stripe.createSource(this.card, ownerInfo);

                if (error) {
                    // Inform the user if there was an error
                    this.errors = error.message;
                } else {
                    // Send the source to your server
                    console.log('payed');
                    this.source = source;
                    this.sourceHandler(source);

                }
            },
            async sourceHandler(source) {
                await axios.post('/credits/purchase', source);
            }
        }
    }
</script>

<style scoped>

</style>