<template>
    <div>
        <div class="row justify-content-center">
            <div class="col">
                <h1>Dashboard</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-5">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Expense Category</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="expenses_len === 0">
                            <td colspan="2">No Data</td>
                        </tr>
                        <tr data-toggle="modal" data-target="#update_expense_modal" 
                        v-else v-for="(data, index) in dataset" :key="index">
                            <td>{{index}}</td>
                            <td>{{data}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-7">
                  <GChart style="width: 800px; height: 500px;"
                    type="PieChart"
                    :data="chartData"
                    :options="chartOptions"
                    :resizeDebounce="500"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { GChart } from 'vue-google-charts'

export default {
    components: {
        GChart
    },
    props : ['role_id'],
    data() {
        return{
            expenses : {},
            expenses_len : '',
            dataset : {},
            chartData : [],
            chartOptions : {
                chart: {
                    title: 'Expense Tracker'
                }
            }
        }
    },
    methods : {
        initialize : function () {
            this.getDashboardData();
        },
        getDashboardData : function () {
            axios.get('dashboard')
            .then(response => {
                this.expenses = response.data;
                this.expenses_len = this.expenses.length;
                var index = 0;
                this.expenses.forEach(element => {
                    if(typeof this.dataset[element.category_name] === 'undefined'){
                        this.dataset[element.category_name] = 0;
                    }
                    this.dataset[element.category_name] = this.dataset[element.category_name] + element.amount;
                });
                this.chartData.push(["Expense Category", "Total"]); 
                for (let [key, value] of Object.entries(this.dataset)) {
                    var array = [key, value];
                    this.chartData.push(array);
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    },
    mounted() {
        this.initialize();
    }
}
</script>