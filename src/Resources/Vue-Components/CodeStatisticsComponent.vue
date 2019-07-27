<template>
    <div class="codes-statistics">

        <div class="d-flex flex-wrap align-content-center justify-content-center mb-2">
            <select @change="setDefaultDates();fetchData()" class="custom-select text-capitalize w-auto mr-2 mb-2"
                    v-model="requestData.type">
                <option class="text-capitalize" selected value="monthly">{{ $tv('DmsCredits.statistics.monthly') |
                    capitalize}}
                </option>
                <option class="text-capitalize" value="daily">{{ $tv('DmsCredits.statistics.daily') |capitalize}}
                </option>
            </select>
            <p class="mr-2 my-auto lead">{{ $tv('DmsCredits.statistics.data')}} {{
                $tv('DmsCredits.statistics.from')}}</p>
            <input @change="fetchData" class="form-control w-auto mr-2 mb-2" type="date"
                   v-model="requestData.start_timestamp">
            <p class="mr-2 my-auto lead">{{ $tv('DmsCredits.statistics.to')}}</p>
            <input @change="fetchData" class="form-control w-auto mr-2" placeholder="End"
                   type="date" v-model="requestData.end_timestamp">
        </div>

        <h4 class="text-capitalize">{{ $tv('DmsCredits.statistics.value_development') }}</h4>
        <div class="chart-wrapper">
            <line-chart :chart-data="valueDataCollection" :options="chartOptions" :styles="chartStyle"></line-chart>
        </div>

        <h4 class="text-capitalize">{{ $tv('DmsCredits.statistics.codes_development') }}</h4>
        <div class="chart-wrapper">
            <line-chart :chart-data="codesDataCollection" :options="chartOptions" :styles="chartStyle"></line-chart>
        </div>

        <h4 class="text-capitalize">{{ $tv('DmsCredits.statistics.general_info') }}</h4>
        <div class="d-flex flex-wrap">
            <div class="mr-3 mb-2 px-2 lead bg-primary-light border rounded">
                <div class="d-inline-block mr-2 first-cap">
                    {{ $tv('DmsCredits.statistics.total_created_codes')}}:
                </div>
                <div class="d-inline-block">
                    {{generalData.created_codes |number }}
                </div>
            </div>
            <div class="mr-3 mb-2 px-2 lead bg-primary-light border rounded">
                <div class="d-inline-block mr-2 first-cap">
                    {{ $tv('DmsCredits.statistics.total_created_value')}}:
                </div>
                <div class="d-inline-block">
                    {{generalData.created_value |number }}
                </div>
            </div>
            <div class="mr-3 mb-2 px-2 lead bg-primary-light border rounded">
                <div class="d-inline-block mr-2 first-cap">
                    {{ $tv('DmsCredits.statistics.total_used_codes')}}:
                </div>
                <div class="d-inline-block">
                    {{generalData.used_codes |number }}
                </div>
            </div>
            <div class="mr-3 mb-2 px-2 lead bg-primary-light border rounded">
                <div class="d-inline-block mr-2 first-cap">
                    {{ $tv('DmsCredits.statistics.total_used_value')}}:
                </div>
                <div class="d-inline-block">
                    {{generalData.used_value |number }}
                </div>
            </div>
        </div>
        <table-component :data="creatorData" :searchable="false">
            <template #columns>
                <th class="text-capitalize">{{ $tv('DmsCredits.statistics.user')}}</th>
                <th class="text-capitalize">{{ $tv('DmsCredits.statistics.codes')}}</th>
                <th class="text-capitalize">{{ $tv('DmsCredits.statistics.value')}}</th>
            </template>
            <template slot-scope="{row, index}">
                <td :class="{'text-muted': row.exported}" class="w-100">
                    <a :href="route('users.show', row.creator.username)" v-if="row.creator">
                        {{ row.creator.firstname }} {{ row.creator.lastname }}
                    </a>
                    <span class="text-capitalize" v-else>Deleted</span>
                </td>
                <td :class="{'text-muted': row.exported}">
                    <span :title="$tv('DmsCredits.statistics.created_codes') | capitalize">{{ row.created_codes | number }}</span>
                    /
                    <span :title="$tv('DmsCredits.statistics.used_codes') | capitalize">{{ row.used_codes | number }}</span>
                </td>
                <td :class="{'text-muted': row.exported}">
                    <span :title="$tv('DmsCredits.statistics.created_value') | capitalize">{{ row.created_value | number }}</span>
                    /
                    <span :title="$tv('DmsCredits.statistics.used_value') | capitalize">{{ row.used_value | number }}</span>
                </td>
            </template>
        </table-component>
    </div>
</template>

<script>
    import LineChart from './LineChart'
    import TableComponent from '@/components/TableComponent'

    export default {
        name: "CodeStatisticsComponent",
        components: {
            LineChart,
            TableComponent
        },
        data: () => ({
            valueDataCollection: {},
            codesDataCollection: {},
            height: 450,
            requestData: {
                start_timestamp: '',
                end_timestamp: '',
                type: 'monthly',
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                spanGaps: true,
                cubicInterpolationMode: 'monotone',
                legend: {
                    position: 'bottom'
                },
            },
            generalData: {
                created_codes: 0,
                created_value: 0,
                used_codes: 0,
                used_value: 0,
            },
            creatorData: []
        }),
        computed: {
            chartStyle() {
                return {
                    height: `${this.height}px`,
                    position: 'relative'
                }
            }
        },
        mounted() {
            this.setDefaultDates();
            this.fetchData();
        },
        methods: {
            async fetchData() {
                return await axios.get(route('credits.statistics.index', this.requestData))
                    .then(response => {
                        this.transformPeriodData(response.data);
                        this.transformTotalData(response.data);
                        this.creatorData = response.data.creator_data;
                    })
            },
            transformPeriodData(data) {
                const labels = this.getLabels(data);
                const timescaleData = labels.map(item => (data.period_data.hasOwnProperty(item)) ? data.period_data[item] : {});

                const created_codes = timescaleData.map(item => item.created_codes);
                const created_value = timescaleData.map(item => item.created_value);
                const used_codes = timescaleData.map(item => item.used_codes);
                const used_value = timescaleData.map(item => item.used_value);

                this.valueDataCollection = {
                    labels, datasets: [
                        {
                            label: this.$options.filters.capitalize(this.$tv('DmsCredits.statistics.created_value')),
                            data: created_value
                        },
                        {
                            label: this.$options.filters.capitalize(this.$tv('DmsCredits.statistics.used_value')),
                            data: used_value
                        },
                    ]
                };

                this.codesDataCollection = {
                    labels, datasets: [
                        {
                            label: this.$options.filters.capitalize(this.$tv('DmsCredits.statistics.created_codes')),
                            data: created_codes
                        },
                        {
                            label: this.$options.filters.capitalize(this.$tv('DmsCredits.statistics.used_codes')),
                            data: used_codes
                        },
                    ]
                };
            },
            getLabels(data) {
                const dateStart = this.$moment(data.start_timestamp);
                const dateEnd = this.$moment(data.end_timestamp);
                const interim = dateStart.clone();
                const labels = [];
                const format = (data.type === 'monthly') ? 'MM.YY' : 'DD.MM.YY';
                const offset = (data.type === 'monthly') ? 'month' : 'day';

                while (dateEnd > interim || interim.format(format) === dateEnd.format(format)) {
                    labels.push(interim.format(format));
                    interim.add(1, offset);
                }

                return labels;
            },
            setDefaultDates() {
                this.requestData.end_timestamp = this.$moment().add(1, 'day').format('YYYY-MM-DD');
                this.requestData.start_timestamp = (this.requestData.type === 'daily') ?
                    this.$moment().subtract(1, 'month').format('YYYY-MM-DD') :
                    this.$moment().subtract(1, 'year').format('YYYY-MM-DD');
            },
            transformTotalData(data) {
                this.generalData.created_codes = data.total_data.created_codes;
                this.generalData.created_value = data.total_data.created_value;
                this.generalData.used_codes = data.total_data.used_codes;
                this.generalData.used_value = data.total_data.used_value;
            }
        }
    }
</script>

<style scoped>
    .first-cap::first-letter {
        text-transform: capitalize;
    }
</style>