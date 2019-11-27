<template>
    <div>
        <div class="row justify-content-center">
            <div class="col">
                <h1>Roles</h1>
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
                        <tr v-if="roles_len == 0">
                            <td colspan="3">No Data</td>
                        </tr>
                        <tr data-toggle="modal" data-target="#update_role_modal" 
                        @click="update_name=role.name;update_description=role.description;selected_role=role.id" v-else v-for="role in roles.data" :key="role.id">
                            <td>{{role.name}}</td>
                            <td>{{role.description}}</td>
                            <td>{{role.created_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" v-show="role_id===1">
            <div class="col">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_role_modal">Add Role</button>  
            </div>
        </div>

        <div class="modal fade" id="add_role_modal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form v-on:submit.prevent="addRole" action="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Role</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                <div class="form-group">
                                    <label for="nameInput">Display Name</label>
                                    <input v-if="role_id===1" v-model="add_name" type="text" class="form-control" id="nameInput" placeholder="Enter Role Name">
                                    <input v-else type="text" class="form-control" id="nameInput" placeholder="Only Administrator Users can add role" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="descriptionInput">Description</label>
                                    <input v-if="role_id===1" v-model="add_description" type="text" class="form-control" id="descriptionInput" placeholder="Enter Role Description">
                                    <input v-else type="text" class="form-control" id="descriptionInput" placeholder="Only Administrator Users can add role" readonly>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button v-if="role_id===1" type="submit" class="btn btn-primary">Save</button>
                            <button v-else type="button" class="btn btn-primary disabled">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="update_role_modal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form v-on:submit.prevent="updateRole" action="PATCH">
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
                                <input v-if="update_name!=='administrator'&&role_id===1" v-model="update_name" type="text" class="form-control" id="nameUpdate" placeholder="Enter New Role Name">
                                <input v-else v-model="update_name" type="text" class="form-control" id="nameUpdate" placeholder="Enter New Role Name" readonly>
                            </div>
                            <div class="form-group">
                                <label for="descriptionUpdate">Update Description</label>
                                <input v-if="update_name!=='administrator'&&role_id===1" v-model="update_description" type="text" class="form-control" id="descriptionUpdate" placeholder="Enter New Role Description">
                                <input v-else v-model="update_description" type="text" class="form-control" id="descriptionUpdate" placeholder="Enter New Role Description" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button @click="update_name='';update_description='';selected_role='';" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button v-if="role_id===1" @click="deleteRole" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
                            <button type="submit" class="btn btn-primary" v-if="update_name!=='administrator'&&role_id===1">Save</button>
                            <button type="button" class="btn btn-primary disabled" v-else>Save</button>
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
                roles : '',
                roles_len : '',
                add_name : '',
                add_description : '',
                update_name : '',
                update_description : '',
                selected_role : '',
            }
        },
        methods : {
            initialize : function () {
                this.fetchRoles();
            },
            fetchRoles : function (page=1) {
                axios.get('api/roles/index?page='+page)
                .then(response => {
                    this.roles = response.data;
                    this.roles_len = this.roles.data.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            addRole : function () {
                axios.post('api/roles/store', {
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
                this.fetchRoles();
            },
            updateRole : function () {
                axios.patch('api/roles/update', {
                    role_id : this.selected_role,
                    name : this.update_name,
                    description : this.update_description
                }).then(response => {   
                    this.selected_role = ''; 
                    this.update_name = this.update_name;
                    this.update_description = this.update_description;
                    console.log(response.data.message);
                })
                .catch(error => {
                    console.log(error.response.data);
                });
                this.fetchRoles();
            },
            deleteRole : function () {
                 axios.delete('api/roles/destroy/' + this.selected_role)
                .then(response => {
                    this.selected_role = '';
                    console.log(response.data.message);
                })
                .catch(error => {
                    console.log(error.response.data);
                });
                this.fetchRoles();
            }
        },
        mounted() {
            this.initialize();
        }
    }
</script>
