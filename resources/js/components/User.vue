<template>
    <div>
        <div class="row justify-content-center">
            <div class="col">
                <h1>Users</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email Adress</th>
                            <th scope="col">Role</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="users_len === 0">
                            <td colspan="4">No Data</td>
                        </tr>
                        <tr data-toggle="modal" data-target="#update_user_modal" 
                        @click="selected_user=user;update_id=user.id;update_name=user.name;update_email=user.email;update_role=selected_user.role_id;" v-else v-for="user in users" :key="user.id">
                            <td>{{user.name}}</td>
                            <td>{{user.email}}</td>
                            <td>{{user.role_name}}</td>
                            <td>{{user.created_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" v-show="role_id===1">
            <div class="col">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_user_modal">Add User</button>  
            </div>
        </div>

        <div class="modal fade" id="add_user_modal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form v-on:submit.prevent="addUser" action="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nameInput">Name</label>
                                <input v-model="add_name" type="text" class="form-control" id="nameInput" placeholder="Enter User Name">
                            </div>
                            <div class="form-group">
                                <label for="emailInput">Email</label>
                                <input v-model="add_email" type="email" class="form-control" id="emailInput" placeholder="Enter Email">
                            </div>
                            <div class="form-group">
                                <label for="passwordInput">Password</label>
                                <input v-model="add_password" type="password" class="form-control" id="passwordInput" placeholder="Enter Password">
                            </div>
                            <div class="form-group">
                                <label for="roleInput">Role</label>
                                <select v-model="add_role" class="form-control" id="roleInput" placeholder="Select Role">
                                    <option v-if="roles_len===0" disabled>No available roles</option>
                                    <option v-else v-for="role in roles" :key="role.id" :value="role.id">{{role.name}}</option>
                                </select>
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

        <div class="modal fade" id="update_user_modal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form v-on:submit.prevent="updateUser" action="PATCH">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateModalLabel">Update User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nameUpdate">Update User Name</label>
                                <input v-if="update_name!=='administrator'&&role_id===1" v-model="update_name" type="text" class="form-control" id="nameUpdate" placeholder="Enter New User Name">
                                <input v-else v-model="update_name" type="text" class="form-control" id="nameUpdate" placeholder="Enter New User Name" readonly>
                            </div>
                            <div class="form-group">
                                <label for="descriptionUpdate">Update Email</label>
                                <input v-if="update_name!=='administrator'&&role_id===1" v-model="update_email" type="email" class="form-control" id="emailUpdate" placeholder="Enter New Email">
                                <input v-else v-model="update_email" type="email" class="form-control" id="emailUpdate" placeholder="Enter New Email" readonly>
                            </div>
                            <div class="form-group">
                                <label for="passwordUpdate">Password</label>
                                <input v-model="update_password" type="password" class="form-control" id="passwordUpdate" placeholder="Enter New Password">
                            </div>
                            <div v-if="selected_user.role_id!==1" class="form-group">
                                <label for="roleUpdate">Role</label>
                                <select v-model="update_role" class="form-control" id="roleUpdate" placeholder="Select Role">
                                    <option v-if="roles_len===0" disabled>No Roles Available</option>
                                    <option v-else v-for="role in roles" :key="role.id" :value="role.id">{{role.name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button @click="update_name='';update_email='';update_password='';update_role='';" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button v-if="selected_user.role_id !== 1" @click="deleteUser" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
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
            roles : '',
            roles_len : '',
            users : '',
            users_len : '',
            add_name : '',
            add_email : '',
            add_password : '',
            add_role : '',

            selected_user : '',
            update_id : '',
            update_name : '',
            update_email : '',
            update_password : '',
            update_role : '',
        }
    },
    methods : {
        initialize : function () {
            this.fetchRoles();
            this.fetchUsers();
        },
        fetchRoles : function () {
            axios.get('role')
            .then(response => {
                this.roles = response.data;
                this.roles_len = this.roles.length;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        fetchUsers : function () {
            axios.get('api/users/index')
            .then(response => {
                this.users = response.data;
                this.users_len = this.users.length;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        addUser : function () {
            axios.post('api/users/store', {
                name : this.add_name,
                email : this.add_email,
                password : this.add_password,
                role : this.add_role
            }).then(response => {    
                this.add_name = '';
                this.add_email = '';
                this.add_password = '';
                this.add_role = '';
                console.log(response.data.message);
            })
            .catch(error => {
                console.log(error.response.data);
            });
            this.fetchUsers(); 
        },
        updateUser : function () {  
            axios.patch('api/users/update', {
                user_id : this.update_id,
                name : this.update_name,
                email : this.update_email,
                password : this.update_password,
                role : this.update_role
            }).then(response => {   
                console.log(response.data.message);
            })
            .catch(error => {
                console.log(error.response.data);
            });
            this.fetchUsers(); 
        },
        deleteUser : function () {
            axios.delete('api/users/destroy/' + this.selected_user.id)
            .then(response => {
                this.selected_user = '';
                this.update_id = '';
                this.update_name = '';
                this.update_email = '';
                this.update_password = '';
                this.update_role = '';
                console.log(response.data.message);
            })
            .catch(error => {
                console.log(error.response.data);
            });
            this.fetchUsers();
        }
    },
    mounted() {
        this.initialize();
    }
}
</script>

<style scoped>

</style>