<template>
    <div class="codes-created">
        <table-component :data="codes" :loading="isLoading" :searchable="false">
            <template #columns>
                <th v-if="copyPossible"></th>
                <th>Code</th>
                <th>Value</th>
                <th>Date</th>
            </template>
            <template slot-scope="{row, index}">
                <td v-if="copyPossible">
                    <button @click="copyToClipboard(`code_${index}`)" class="btn btn-link p-0">
                        <svg viewBox="0 0 561 561" width="16" xmlns="http://www.w3.org/2000/svg">
                            <path d="M395.25 0h-306c-28.05 0-51 22.95-51 51v357h51V51h306V0zm76.5 102h-280.5c-28.05 0-51 22.95-51 51v357c0 28.05 22.95 51 51 51h280.5c28.05 0 51-22.95 51-51V153c0-28.05-22.95-51-51-51zm0 408h-280.5V153h280.5v357z"></path>
                        </svg>
                    </button>
                </td>
                <td :id="`code_${index}`" class="w-100">{{ row.code }}</td>
                <td>{{ row.value | number }}</td>
                <td>{{ row.created_at | moment('calendar') }}</td>
            </template>
        </table-component>
    </div>
</template>

<script>
    import TableComponent from "@/components/TableComponent";
    import {mapState} from 'vuex';

    const createdCodeModule = {
        state: {
            codes: [],
            isLoading: false,
        },
        mutations: {
            addCreatedCodes(state, codes) {
                state.codes = codes;
            },
            refreshCreatedCodes(state, codes) {
                state.codes = codes;
            },
            isLoading(state) {
                state.isLoading = true;
            },
            isNotLoading(state) {
                state.isLoading = false;
            }
        },
        actions: {
            async addCreatedCodes(context) {
                const codes = await context.dispatch('fetchCodes');
                context.commit('addCreatedCodes', codes);
            },
            async refreshCreatedCodes(context) {
                const codes = await context.dispatch('fetchCodes');
                context.commit('refreshCreatedCodes', codes);
            },
            async fetchCodes() {
                this.commit('isLoading');
                return await axios.get(route('credits.code.index'))
                    .then(response => {
                        this.commit('isNotLoading');
                        setTimeout(5000);
                        return response.data;
                    })
                    .catch(error => {
                        console.log(error);
                        this.commit('isNotLoading');
                    });
            }
        },
    };

    export default {
        name: "created-codes-component",
        components: {TableComponent},
        data: () => ({
            copyPossible: (navigator.clipboard && typeof navigator.clipboard.writeText === 'function'),
        }),
        beforeCreate() {
            this.$store.registerModule('createdCodeModule', createdCodeModule);
        },
        created() {
            this.$store.dispatch("addCreatedCodes");
        },
        computed: {
            ...mapState({
                codes: state => state.createdCodeModule.codes,
                isLoading: state => state.createdCodeModule.isLoading,
            }),
        },
        methods: {
            copyToClipboard(id) {
                const text = document.getElementById(id).innerText;
                navigator.clipboard.writeText(text);
            }
        }
    }
</script>

<style scoped>
    tr {
        white-space: nowrap;
    }
</style>