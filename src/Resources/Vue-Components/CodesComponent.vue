<template>
    <div class="codes">
        <button @click="showModal=true" class="btn btn-primary">Generate Codes</button>
        <div class="float-right">
            <a :href="route('credits.code.export')" class="btn btn-primary" target="_blank">Export Codes</a>
            <button @click="$store.dispatch('refreshCreatedCodes')" class="btn btn-primary">
                <svg viewBox="0 0 252.264 252.264" width="16" xmlns="http://www.w3.org/2000/svg">
                    <path d="M248.988 80.693a7.499 7.499 0 0 0-10.422 1.97l-15.314 22.453c-9.679-44.721-49.575-78.354-97.123-78.354-26.544 0-51.498 10.337-70.265 29.108a7.5 7.5 0 0 0 .001 10.606 7.498 7.498 0 0 0 10.606-.001c15.933-15.937 37.12-24.713 59.657-24.713 41.32 0 75.815 29.921 82.98 69.228l-26.606-18.147a7.498 7.498 0 0 0-10.422 1.97 7.5 7.5 0 0 0 1.971 10.422l39.714 27.087.007.005a7.484 7.484 0 0 0 4.219 1.305 7.494 7.494 0 0 0 6.203-3.274l26.764-39.242a7.5 7.5 0 0 0-1.97-10.423zM187.196 184.351c-16.084 16.863-37.77 26.15-61.065 26.15-41.317-.001-75.813-29.921-82.978-69.227l26.607 18.147a7.465 7.465 0 0 0 4.219 1.305 7.5 7.5 0 0 0 4.233-13.696l-39.714-27.087-.006-.005a7.499 7.499 0 0 0-10.422 1.97L1.304 161.149a7.5 7.5 0 0 0 12.392 8.451l15.313-22.453c9.68 44.72 49.577 78.352 97.121 78.352 27.435 0 52.977-10.938 71.919-30.797a7.5 7.5 0 0 0-.25-10.604 7.5 7.5 0 0 0-10.603.253z"
                          fill="white"></path>
                </svg>
            </button>
        </div>
        <created-codes class="mt-2"/>
        <modal-component :show="showModal" @close="showModal = false">
            <code-generator></code-generator>
        </modal-component>
    </div>
</template>

<script>
    import CodeGenerator from './CodeGeneratorComponent.vue';
    import CreatedCodes from './CreatedCodesComponent.vue';
    import ModalComponent from "@/components/ModalComponent";

    export default {
        name: "codes-component",
        components: {
            ModalComponent,
            CodeGenerator,
            CreatedCodes,
        },
        data: () => ({
            showModal: false,
            selectedCodes: []
        })
    }
</script>

<style scoped>

</style>