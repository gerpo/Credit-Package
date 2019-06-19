<template>
    <div class="container">
        <div class="card">
            <div class="card-header text-capitalize">
                <span>{{ 'Credits' }}</span>
            </div>
            <div class="card-body">
                <nav class="nav nav-tabs nav-fill">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#nav-account">
                        Personal
                    </a>
                    <a class="nav-item nav-link" v-if="$can('create_codes')" data-toggle="tab"
                       href="#nav-codes">Codes
                    </a>
                </nav>
                <div class="tab-content pt-2" id="nav-tabContent">
                    <div id="nav-account" class="tab-pane fade show active"
                         role="tabpanel">
                        <account :account="account"/>
                    </div>
                    <codes id="nav-codes" class="tab-pane fade show" role="tabpanel"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PurchaseComponent from './PurchaseComponent.vue';
    import Account from './AccountComponent.vue';
    import Codes from './CodesComponent.vue';

    export default {
        name: "credits-component",
        components: {
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
                window.location.hash =  e.target.hash;
            })
        }
    }
</script>

<style scoped>
    .nav-item.nav-link {
        cursor: pointer;
    }
</style>
