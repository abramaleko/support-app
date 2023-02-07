<script>
import { useForm } from '@inertiajs/inertia-vue3';
import moment from 'moment';

export default {
    props: {
        staffs: Object,
        permissions: Object,
    },
    data() {
        return {
            formUser: Object,
            updatePermissionsForm: Object,
            moment: moment,
            selectedUser: Object,
            updatePermissions: false,
        }
    },
    created() {
        //innitalizes the form for creating users
        this.formUser = useForm({
            staff_name: null,
            staff_email: null,
            staff_permissions: [],
        })

        this.updatePermissionsForm = useForm({
            current_permissions: [],
            user_id : null,
        })
    },
    methods: {
        openModal(modelName) {
            const modalToOpen = document.getElementById(modelName);
            modalToOpen.classList.remove('fadeOut');
            modalToOpen.classList.add('fadeIn');
            modalToOpen.style.display = 'flex';
        },
        closeModal(modalName) {
            const modalToClose = document.getElementById(modalName);
            modalToClose.classList.remove('fadeIn');
            modalToClose.classList.add('fadeOut');
            setTimeout(() => {
                modalToClose.style.display = 'none';
            }, 500);
        },
        submitNewStaffForm() {
            //this creates a new staff user
            this.formUser.post(route('settings.newStaff'), {
                preserveScroll: true,
                onSuccess: () => {
                   this.formUser.reset();
                    this.closeModal();
                }
            })
        },
        submitUpdatePermissionsForm() {
            //assigns the user_id for updating permissions
            this.updatePermissionsForm.user_id=this.selectedUser.id;

            //this updates the staff permissions
            this.updatePermissionsForm.post(route('settings.staff.updatePermissions'), {
                preserveScroll: true,
                onSuccess: () => {
                    //hides the update permissions section
                   this.updatePermissions = false;
                   //assigns the new permissions
                   let new_permissions=this.updatePermissionsForm.current_permissions;
                   this.selectedUser.stringifiedPermissions=new_permissions.toString();
                }
            })
        },
        deleteStaff() {
            if (!this.selectedUser.stringifiedPermissions) {
                alert('You can not delete a Super Administrator');
                return false;
            }
        },
        selectUserEvent(user) {
            //hides the update permissions section
            this.updatePermissions = false;

            //this will hold the selected user
            this.selectedUser = user;

            //assigns the current permissions to the existing user
            user.permissions.forEach(permission => {
                this.updatePermissionsForm.current_permissions.push(permission.name);
            });

            this.openModal('user_selected');
        },

    },
}

</script>

<template>
    <div class="w-full min-w-0 mb-4 overflow-y-scroll rounded shadow-lg lg:mb-0 bg-gray-50 dark:bg-gray-800 lg:h-72">
        <div class="px-0 mb-0 border-0 rounded-t">
            <div class="flex flex-wrap items-center px-4 py-2">
                <div class="relative flex-1 flex-grow w-full max-w-full">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-50">Registered Staff</h3>
                </div>
                <div class="relative flex-1 flex-grow w-full max-w-full text-right">
                    <button @click="openModal('user_modal')"
                        class="px-3 py-1 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-blue-500 rounded outline-none dark:bg-gray-100 active:bg-blue-600 dark:text-gray-800 dark:active:text-gray-700 focus:outline-none"
                        type="button">Add New</button>
                </div>
            </div>
            <div class="block w-full overflow-x-auto">
                <table class="items-center w-full bg-transparent border-collapse">
                    <thead>
                        <tr>
                            <th
                                class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase align-middle bg-gray-100 border border-l-0 border-r-0 border-gray-200 border-solid dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500 whitespace-nowrap">
                                Full Name</th>
                            <th
                                class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase align-middle bg-gray-100 border border-l-0 border-r-0 border-gray-200 border-solid dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500 whitespace-nowrap min-w-140-px">
                                Registerd On</th>
                            <th
                                class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase align-middle bg-gray-100 border border-l-0 border-r-0 border-gray-200 border-solid dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500 whitespace-nowrap min-w-140-px">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="staff in staffs" :key="staff.id" class="text-gray-700 dark:text-gray-100">
                            <th
                                class="flex p-4 px-4 text-base text-left align-middle border-t-0 border-l-0 border-r-0 flex-nowrap">
                                <img src="/images/user.png" class="w-6 h-6 mr-2" alt="user icon">
                                <div class="">
                                    <p class="text-lg">
                                        {{ staff.name }}
                                    </p>
                                    <p class="text-xs font-light">
                                        {{ staff.email }}
                                    </p>

                                </div>
                            </th>
                            <td
                                class="p-4 px-4 text-base align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap">
                                {{ moment(staff.created_at).format('MMMM d YYYY') }}
                            </td>
                            <td>
                                <a @click.stop.prevent="selectUserEvent(staff)"
                                    class="flex items-center font-medium text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-500 hover:underline"
                                    href="#" style="outline: none;">
                                    More<span><svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"
                                            class="transition-transform duration-500 ease-in-out transform">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg></span>
                                </a>
                            </td>
                        </tr>
                        <p v-if="!staffs" class="px-4 py-2 text-sm italic">
                            No data found
                        </p>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add New User Modal -->
    <div id="user_modal"
        class="fixed inset-0 z-50 items-center justify-center hidden w-full overflow-hidden animated fadeIn faster"
        style="background: rgba(0,0,0,.7);">
        <div
            class="z-50 w-6/12 mx-auto overflow-y-auto border shadow-lg border-white-500 bg-gray-50 dark:bg-gray-800 modal-container md:max-w-11/12 rounded-xl">
            <div class="px-6 py-4 text-left modal-content">
                <!--Title-->
                <div class="flex items-center justify-between pb-3">
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-50">Add New Staff</p>
                    <div class="z-50 cursor-pointer modal-close" @click="closeModal('user_modal')">
                        <svg class="text-gray-500 fill-current hover:text-white" xmlns="http://www.w3.org/2000/svg"
                            width="18" height="18" viewBox="0 0 18 18">
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                            </path>
                        </svg>
                    </div>
                </div>
                <!--Body-->
                <div class="flex justify-center my-5 ml-5 mr-5">
                    <form v-on:submit.prevent="submitNewStaffForm" class="w-full">

                        <div class="text-gray-900 dark:text-gray-50">
                            <div>
                                <label for="staff_name" class=" text-md">Full Name</label>
                                <div class="my-2">
                                    <input type="text" id="staff_name" v-model="formUser.staff_name"
                                        placeholder="i.e John Doe"
                                        class="w-full h-2 p-5 mb-5 text-gray-900 border-2 border-gray-300 rounded-md" />
                                    <div class="mt-1 font-bold text-red-500" v-if="formUser.errors.staff_name">
                                        {{ formUser.errors.staff_name }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="staff_email" class=" text-md">Email</label>
                                <div class="my-2">
                                    <input type="text" id="staff_email" v-model="formUser.staff_email"
                                        placeholder="myemail@gmail.com"
                                        class="w-full h-2 p-5 mb-5 text-gray-900 border-2 border-gray-300 rounded-md" />
                                    <div class="mt-1 font-bold text-red-500" v-if="formUser.errors.staff_email">
                                        {{ formUser.errors.staff_email }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="staff_permissions" class="text-lg ">Assign Permissions</label>
                                <div class="grid my-2 text-sm grid-col-1 md:grid-cols-2">
                                    <div v-for="permission in permissions" :key="permission.id" class="my-1">
                                        <input v-model="formUser.staff_permissions" :value="permission.name"
                                            :id="'checkbox-' + permission.id" aria-describedby="checkbox-1"
                                            type="checkbox"
                                            class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                                        <label :for="'checkbox-' + permission.id" class="ml-3 ">{{
                                            permission.name
                                        }}</label>
                                    </div>
                                </div>
                                <div class="mt-1 font-bold text-red-500" v-if="formUser.errors.staff_permissions">
                                    {{ formUser.errors.staff_permissions }}
                                </div>
                            </div>
                            <div class="my-4 border border-gray-900 border-dashed dark:border-gray-50"></div>
                            <p class="italic ">
                                <b> Note:</b> New staff memebers are assigned a default password set in the
                                configuration file
                            </p>
                        </div>
                    </form>
                </div>
                <!--Footer-->
                <div class="flex justify-end ">
                    <button class="p-3 px-4 font-semibold text-black bg-gray-200 rounded hover:bg-gray-300"
                        @click="closeModal('user_modal')">Cancel</button>
                    <button @click="submitNewStaffForm"
                        class="p-3 px-4 ml-3 text-white bg-gray-700 rounded hover:bg-gray-600">Register</button>
                </div>
            </div>
        </div>
    </div>


    <!--selected User modal-->
    <div id="user_selected"
        class="fixed inset-0 z-50 items-center justify-center hidden w-full overflow-hidden animated fadeIn faster"
        style="background: rgba(0,0,0,.7);">
        <div
            class="z-50 w-6/12 mx-auto overflow-y-auto border shadow-lg border-white-500 bg-gray-50 dark:bg-gray-800 modal-container md:max-w-11/12 rounded-xl">
            <div class="px-6 py-4 text-left modal-content">
                <!--Title-->
                <div class="flex items-center justify-between pb-3">
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-50">Staff Details</p>
                    <div class="z-50 cursor-pointer modal-close" @click="closeModal('user_selected')">
                        <svg class="text-gray-500 fill-current hover:text-white" xmlns="http://www.w3.org/2000/svg"
                            width="18" height="18" viewBox="0 0 18 18">
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                            </path>
                        </svg>
                    </div>
                </div>
                <!--Body-->
                <div class="my-5 ml-5 mr-5 ">
                    <div class="flex flex-wrap mb-4 text-lg">
                        <p>
                            Name : <span class="font-light text-green-500">{{ selectedUser.name }}</span>
                        </p>
                        <p class="lg:ml-28 ">
                            Email : <span class="font-light text-green-500 ">{{ selectedUser.email }}</span>
                        </p>
                    </div>

                    <p class="mb-4 text-lg">
                        Registred On : <span class="font-light text-green-500">{{
                            moment(selectedUser.created_at).format('MMMM d YYYY, h:mm:ss a')
                        }}</span>
                    </p>

                    <p class="mb-2 text-lg">
                        Assigned Permissions :
                        <span v-if="selectedUser.stringifiedPermissions" class="font-light text-green-500">{{
                            selectedUser.stringifiedPermissions
                        }}</span>
                        <span v-else class="font-light text-green-500">
                            Super Administrator
                        </span>
                    </p>

                    <a v-if="selectedUser.stringifiedPermissions"
                        @click.stop.prevent="updatePermissions = !updatePermissions" href="#"
                        class="mb-4 text-xs italic font-light cursor-pointer hover:underline">
                        Update Permissions
                    </a>

                    <Transition>
                        <div v-show="updatePermissions">
                            <div class="my-4 border border-gray-900 border-dashed dark:border-gray-50"></div>
                            <div>
                                <div class="grid my-2 text-sm grid-col-1 md:grid-cols-2">
                                    <div v-for="permission in permissions" :key="permission.id" class="my-1">
                                        <input v-model="updatePermissionsForm.current_permissions"
                                            :value="permission.name" :id="'checkbox-' + permission.id" type="checkbox"
                                            class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                                        <label :for="'checkbox-' + permission.id" class="ml-3 ">{{
                                            permission.name
                                        }}</label>
                                    </div>
                                </div>
                                <div class="mt-1 font-bold text-red-500" v-if="updatePermissionsForm.errors.current_permissions">
                                    {{ updatePermissionsForm.errors.current_permissions }}
                                </div>
                            </div>
                            <input type="hidden" v-model="updatePermissionsForm.user_id">
                        </div>
                    </Transition>


                </div>
                <!--Footer-->
                <div class="flex justify-end ">
                    <button class="p-3 px-4 font-semibold text-black bg-gray-200 rounded hover:bg-gray-300"
                        @click="closeModal('user_selected')">Close</button>
                    <button v-show="!updatePermissions" @click="deleteStaff"
                        class="p-3 px-4 ml-3 text-white bg-red-700 rounded hover:bg-red-600">Delete Staff</button>
                    <button v-show="updatePermissions" @click="submitUpdatePermissionsForm"
                        class="p-3 px-4 ml-3 text-white bg-gray-700 rounded hover:bg-gray-600">Update
                        Permissions</button>
                </div>
            </div>
        </div>
    </div>

</template>


<style scoped>
.animated {
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
}

.animated.faster {
    -webkit-animation-duration: 500ms;
    animation-duration: 500ms;
}

.fadeIn {
    -webkit-animation-name: fadeIn;
    animation-name: fadeIn;
}

.fadeOut {
    -webkit-animation-name: fadeOut;
    animation-name: fadeOut;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }

    to {
        opacity: 0;
    }
}

.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
