<template>
        <div>
        <div class="row justify-content-center">
            <div class="col">
                <h1>Expenses</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Expense Category</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Entry Date</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="expenses_len === 0">
                            <td colspan="4">No Data</td>
                        </tr>
                        <tr data-toggle="modal" data-target="#update_expense_modal" 
                        @click="selected_expense=expense.id;update_category=expense.category_id;update_amount=expense.amount;update_date=expense.entry_date;" v-else v-for="expense in expenses" :key="expense.id">
                            <td>{{expense.category_name}}</td>
                            <td>{{expense.amount}}</td>
                            <td>{{expense.entry}}</td>
                            <td>{{expense.created_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_expense_modal">Add Categories</button>  
            </div>
        </div>

        <div class="modal fade" id="add_expense_modal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form v-on:submit.prevent="addExpense" action="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Expense</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="categoryInput">Expense Category</label>
                                <select v-model="add_category" class="form-control" id="categoryInput" placeholder="Select Category">
                                    <option v-if="categories_len===0" disabled>No Expense Categories Available</option>
                                    <option v-else v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="amountInput">Amount</label>
                                <input v-model="add_amount" type="number" class="form-control" id="amountInput" step="0.01" min="0" oninput="validity.valid||(value='');" placeholder="Enter Amount">
                            </div>
                            <div class="form-group">
                                <label for="dateInput">Date</label>
                                <input v-model="add_date" class="form-control"   id="dateInput" type="date">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="update_expense_modal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form v-on:submit.prevent="updateExpense" action="PATCH">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Edit Expense</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="categoryUpdate">Expense Category</label>
                                <select v-model="update_category" class="form-control" id="categoryUpdate" placeholder="Select Category">
                                    <option v-if="categories_len===0" disabled>No Expense Categories Available</option>
                                    <option v-else v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="amountUpdate">Amount</label>
                                <input v-model="update_amount" type="number" class="form-control" id="amountUpdate" step="0.01" min="0" oninput="validity.valid||(value='');" placeholder="Enter Update Amount">
                            </div>
                            <div class="form-group">
                                <label for="dateUpdate">Date</label>
                                <input v-model="update_date" class="form-control"  id="dateUpdate" type="date">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button @click="update_category='';update_amount='';update_date='';selected_expense='';" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button @click="deleteExpense" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props : ['role_id'],
    data() {
        return{
            expenses : '',
            expenses_len : '',
            categories : '',
            categories_len : '',

            add_amount : '',
            add_category : '',
            add_date : '',
            selected_expense : '',
            update_category : '',
            update_amount : '',  
            update_date : '', 
        }
    },
    methods : {
        initialize : function () {
            this.fetchCategories();
            this.fetchExpenses();
        },
        fetchExpenses : function (page=1) {
            axios.get('api/expenses/index')
            .then(response => {
                this.expenses = response.data;
                this.expenses_len = this.expenses.length;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        fetchCategories : function () {
            axios.get('categories')
            .then(response => {
                this.categories = response.data;
                this.categories_len = this.categories.length;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        addExpense : function () {
            axios.post('api/expenses/store', {
                category : this.add_category,
                amount : this.add_amount,
                date : this.add_date,
            }).then(response => {    
                this.add_category = '';
                this.add_amount = '';
                this.add_date = '';
                console.log(response.data.message);
            })
            .catch(error => {
                console.log(error.response.data);
            });
            this.fetchExpenses();
        },
        updateExpense : function () {
            axios.patch('api/expenses/update', {
                expense_id : this.selected_expense,
                category : this.update_category,
                amount : this.update_amount,
                date : this.update_date
            }).then(response => { 
                console.log(response.data.message);
            })
            .catch(error => {
                console.log(error.response.data);
            });
            this.fetchExpenses();
        },
        deleteExpense : function () {
                axios.delete('api/expenses/destroy/' + this.selected_expense)
            .then(response => {
                this.selected_expense = '';
                console.log(response.data.message);
            })
            .catch(error => {
                console.log(error.response.data);
            });
            this.fetchExpenses();
        }
    },
    mounted() {
        this.initialize();
    }
}
</script>