<template>
    <div>
        <div class="row justify-content-center">
            <div class="col">
                <h1>Expense Categories</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Display Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="categories_len == 0">
                            <td colspan="3">No Data</td>
                        </tr>
                        <tr data-toggle="modal" data-target="#update_category_modal" 
                        @click="update_name=category.name;update_description=category.description;selected_category=category.id" v-else v-for="category in categories" :key="category.id">
                            <td>{{category.name}}</td>
                            <td>{{category.description}}</td>
                            <td>{{category.created_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" v-show="role_id===1">
            <div class="col">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_category_modal">Add Categories</button>  
            </div>
        </div>

        <div class="modal fade" id="add_category_modal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form v-on:submit.prevent="addCategory" action="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                <div class="form-group">
                                    <label for="nameInput">Display Name</label>
                                    <input v-if="role_id===1" v-model="add_name" type="text" class="form-control" id="nameInput" placeholder="Enter Category Name">
                                    <input v-else type="text" class="form-control" id="nameInput" placeholder="Only Administrator Users can add role" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="descriptionInput">Description</label>
                                    <input v-if="role_id===1" v-model="add_description" type="text" class="form-control" id="descriptionInput" placeholder="Enter Category Description">
                                    <input v-else type="text" class="form-control" id="descriptionInput" placeholder="Only Administrator Users can add role" readonly>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button v-if="role_id===1" type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="update_category_modal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form v-on:submit.prevent="updateCategory" action="PATCH">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateModalLabel">Update Role</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nameUpdate">Update Display Name</label>
                                <input v-if="role_id===1" v-model="update_name" type="text" class="form-control" id="nameUpdate" placeholder="Enter New Role Name">
                                <input v-else v-model="update_name" type="text" class="form-control" id="nameUpdate" placeholder="Enter New Role Name" readonly>
                            </div>
                            <div class="form-group">
                                <label for="descriptionUpdate">Update Description</label>
                                <input v-if="role_id===1" v-model="update_description" type="text" class="form-control" id="descriptionUpdate" placeholder="Enter New Role Description">
                                <input v-else v-model="update_description" type="text" class="form-control" id="descriptionUpdate" placeholder="Enter New Role Description" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button @click="update_name='';update_description='';selected_category='';" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button v-if="role_id===1" @click="deleteCategory" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
                            <button type="submit" class="btn btn-primary" v-if="role_id===1">Save</button>
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
                categories : '',
                categories_len : '',

                add_name : '',
                add_description : '',
                selected_category : '',
                update_name : '',
                update_description : '',   
            }
        },
        methods : {
            initialize : function () {
                this.fetchCategories();
            },
            fetchCategories : function (page=1) {
                axios.get('api/categories/index')
                .then(response => {
                    this.categories = response.data;
                    this.categories_len = this.categories.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            addCategory : function () {
                axios.post('api/categories/store', {
                    name : this.add_name,
                    description : this.add_description
                }).then(response => {    
                    this.add_name = '';
                    this.add_description = '';
                    console.log(response.data.message);
                })
                .catch(error => {
                    console.log(error.response.data);
                });
                this.fetchCategories();
            },
            updateCategory : function () {
                axios.patch('api/categories/update', {
                    category_id : this.selected_category,
                    name : this.update_name,
                    description : this.update_description
                }).then(response => { 
                    this.update_name = this.update_name;
                    this.update_description = this.update_description;
                    console.log(response.data.message);
                })
                .catch(error => {
                    console.log(error.response.data);
                });
                this.fetchCategories();
            },
            deleteCategory : function () {
                 axios.delete('api/categories/destroy/' + this.selected_category)
                .then(response => {
                    this.selected_category = '';
                    console.log(response.data.message);
                })
                .catch(error => {
                    console.log(error.response.data);
                });
                this.fetchCategories();
            }
        },
        mounted() {
            this.initialize();
        }
}
</script>