<template>
    <div class="container">
        <div class="card">
            <div class="card-header text-capitalize">
                <span>{{ 'Credits' }}</span>
            </div>
            <div class="card-body">
                <nav class="nav nav-tabs nav-fill" v-if="$can('create_codes') || $can('code_statistics')">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#nav-account">
                        Personal
                    </a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#nav-codes"
                       v-if="$can('create_codes')">Codes
                    </a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#nav-statistics"
                       v-if="$can('code_statistics')">Statistics
                    </a>
                </nav>
                <div class="tab-content pt-2" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-account"
                         role="tabpanel">
                        <account :account="account"/>
                    </div>
                    <codes class="tab-pane fade show" id="nav-codes" role="tabpanel" v-if="$can('create_codes')"/>
                    <code-statistics-component class="tab-pane fade show" id="nav-statistics" role="tabpanel"
                                               v-if="$can('code_statistics')"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PurchaseComponent from './PurchaseComponent.vue';
    import Account from './AccountComponent.vue';
    import Codes from './CodesComponent.vue';
    import CodeStatisticsComponent from "./CodeStatisticsComponent";

    export default {
        name: "credits-component",
        components: {
            CodeStatisticsComponent,
            PurchaseComponent,
            Account,
            Codes,
        },
        props: {
            account: {
                default: () => {
                }, type: [Object]
            }
        },
        mounted() {
            const url = window.location.toString();
            if (url.match('#')) {
                $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
            }

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            })
        }
    }
</script>

<style scoped>
    .nav-item.nav-link {
        cursor: pointer;
    }
</style>
