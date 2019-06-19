<template>
    <div class="codes">
        <code-generator/>
        <hr class="w-100">
        <created-codes />
    </div>
</template>

<script>
    import CodeGenerator from './CodeGeneratorComponent.vue';
    import CreatedCodes from './CreatedCodesComponent.vue';

    export default {
        name: "codes-component",
        components: {
            CodeGenerator,
            CreatedCodes,
        }
    }
</script>

<style scoped>

</style>