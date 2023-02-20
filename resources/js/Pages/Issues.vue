<script>

import { Head, useForm,Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import AuthLayout from '@/Layouts/AuthLayout.vue';
import moment from 'moment';

export default {
    components: {
        Head, AuthLayout,Link
    },
    props: {
        issue_categories: Object,
        issues: Object,
        staff_permissions: Array,
        isStaff:Boolean,
    },
    data() {
        return {
            selectedIssue: Object,
            issuesForm: Object,
            moment: moment,
        }
    },
    created() {
        //innitalizes the form on component mount
        this.issuesForm = useForm({
            issue_title: null,
            issue_description: null,
            issue_category: null,
        });
    },
    computed: {

    },
    methods: {
        //create new issue
        submitForm() {
            this.issuesForm.post(route('issues.new'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.issuesForm.reset();
                    this.closeModal('new_issue');
                }
            })
        },
        selectIssueEvent(issue) {
            this.selectedIssue = issue;
            this.openModal('issue_selected');
        },
        openModal(modal) {
            const modalToOpen = document.getElementById(modal);
            modalToOpen.classList.remove('fadeOut');
            modalToOpen.classList.add('fadeIn');
            modalToOpen.style.display = 'flex';
        },
        closeModal(modal) {
            const modalToClose = document.getElementById(modal);
            modalToClose.classList.remove('fadeIn');
            modalToClose.classList.add('fadeOut');
            setTimeout(() => {
                modalToClose.style.display = 'none';
            }, 200);
        },
        issueStatus(status) {
            if (status == 1) {
                return 'Not Assigned'
            }
            if (status == 2) {
                return 'In Progress'
            }
            if (status == 3) {
                return 'Closed'
            }
        },
        getAssigned(){
          Inertia.post(route('staff.get-assigned-issue'),{
            issue_id: this.selectedIssue.id
          });
        }

    }
}
</script>

<template>

    <Head title="All Issues" />

    <AuthLayout>
        <!--staff issues-->
        <div v-if="isStaff"
        class="flex justify-center my-8 ">
            <div class="w-full max-w-6xl p-8 rounded bg-gray-50 dark:bg-gray-800">
                <!--header-->
                <div class="flex justify-between">
                    <div class="">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-50">All Issues
                            <span class="text-xs font-light">230 Tickets</span>
                        </h2>
                    </div>
                    <div class="">
                        <input type="text" name="" id=""
                            class="text-gray-600 border-transparent rounded focus:border-transparent focus:ring-0"
                            placeholder="Enter Issue Id">
                        <button class="px-6 py-2 ml-4 text-white bg-gray-700 rounded hover:bg-gray-600"> Search Issue
                        </button>
                    </div>
                </div>
                <div class="my-4 border bg-gray-50 dark:bg-gray-800"></div>
                <!--end of header-->

                <!--issues-->
                <div class="my-4">
                    <p v-if="!staff_permissions" class="text-sm italic normal-case ">
                        Showing the latest open issues
                    </p>
                    <p v-else class="text-sm italic normal-case ">
                        Showing the latest {{ staff_permissions.toString() }} open issues.
                    </p>

                    <!-- Issue-->
                    <div v-for="issue in issues" :key="issue.id"
                        class="grid grid-cols-4 gap-8 p-4 my-4 border border-gray-100 rounded">
                        <div class="col-span-2 ">
                            <h3 class="text-lg font-light">
                                <span class="font-bold text-green-500 cursor-pointer hover:underline">#{{
                                    issue.issue_id
                                }}</span>
                                - {{ issue.title }}
                            </h3>
                            <div class="flex flex-wrap mt-1">
                                <img src="/images/icons/calendar.png" class="w-5 h-5" alt="calendar" />
                                <p class="ml-2 text-base">{{ moment(issue.created_at).format('d MMMM YYYY') }} </p>
                            </div>

                        </div>


                        <div class="py-2">
                            <p class="text-lg">
                                <img src="/images/icons/open-folder.png" class="inline w-6 h-6 mx-2"
                                    alt="user-assigned to" />
                                {{ issue.issue_category.name }}
                            </p>
                            <div class="flex mt-2 ml-1">
                                <!-- <p class="px-2 py-1 ml-2 text-xs font-light text-white bg-gray-700 rounded-md">Open Issue </p> -->
                                <p :class="{ 'bg-gray-700': issue.status == 1, 'bg-green-500':issue.status==2,'bg-red-500':issue.status==3}"
                                    class="px-2 py-1 ml-2 text-xs font-light text-white rounded-md">
                                    {{ issueStatus(issue.status) }}
                                </p>
                            </div>

                        </div>

                        <div class="py-2">
                            <p class="text-lg">
                                <button @click="selectIssueEvent(issue)"
                                    class="flex items-center font-medium text-white hover:underline" type="button"
                                    style="outline: none;">
                                    Details<span><svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"
                                            class="transition-transform duration-500 ease-in-out transform">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg></span>
                                </button>
                            </p>
                        </div>

                    </div>
                </div>



            </div>
        </div>

        <div v-else
        class="flex justify-center my-8 ">
            <div class="w-full max-w-6xl p-8 rounded bg-gray-50 dark:bg-gray-800">
                <!--header-->
                <div class="flex justify-between">
                    <div class="">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-50">All Issues
                            <span class="text-xs font-light">230 Tickets</span>
                        </h2>
                    </div>

                    <div class="">
                        <button type="button" @click="openModal('new_issue')"
                            class="px-6 py-2 ml-4 text-white bg-gray-700 rounded hover:bg-gray-600">Add New Issue
                        </button>
                    </div>
                </div>
                <div class="my-4 border bg-gray-50 dark:bg-gray-800"></div>
                <!--end of header-->


                <!--filter-->
                <div class="flex justify-between">

                    <div class="">
                        <input type="text" name="" id=""
                            class="text-gray-600 border-transparent rounded focus:border-transparent focus:ring-0"
                            placeholder="Enter Issue Id">
                        <button class="px-6 py-2 ml-4 text-white bg-gray-700 rounded hover:bg-gray-600"> Search Issue
                        </button>
                    </div>

                    <div class="">
                        <select name="filter_options" id="filter_options"
                            class="text-sm bg-gray-700 rounded hover:bg-gray-600">
                            <option value="" selected disabled>Choose ..</option>
                            <option value="0">Latest</option>
                            <option value="1">Open Issues</option>
                            <option value="2">In-Progress Issues</option>
                            <option value="2">Closed</option>
                        </select>
                        <button class="px-4 py-2 ml-4 text-sm bg-gray-700 rounded hover:bg-gray-600"> Filter</button>
                    </div>

                </div>

                <!--issues-->
                <div class="my-8">
                    <p v-if="!staff_permissions" class="text-sm italic lowercase">
                        Showing the latest created issues
                    </p>
                    <!-- Issue-->
                    <div v-for="issue in issues" :key="issue.id"
                        class="grid grid-cols-4 gap-8 p-4 my-4 border border-gray-100 rounded">
                        <div class="col-span-2 ">
                            <h3 class="text-lg font-light">
                                <span class="font-bold text-green-500 cursor-pointer hover:underline">#{{
                                    issue.issue_id
                                }}</span>
                                - {{ issue.title }}
                            </h3>
                            <div class="flex flex-wrap mt-1">
                                <img src="/images/icons/calendar.png" class="w-5 h-5" alt="calendar" />
                                <p class="ml-2 text-base">{{ moment(issue.created_at).format('d MMMM YYYY') }} </p>
                            </div>

                        </div>


                        <div class="py-2">
                            <p class="text-lg">
                                <img src="/images/icons/open-folder.png" class="inline w-6 h-6 mx-2"
                                    alt="user-assigned to" />
                                {{ issue.issue_category.name }}
                            </p>
                            <div class="flex mt-2 ml-1">
                                <!-- <p class="px-2 py-1 ml-2 text-xs font-light text-white bg-gray-700 rounded-md">Open Issue </p> -->
                                <p :class="{ 'bg-gray-700': issue.status == 1, 'bg-green-500':issue.status==2,'bg-red-500':issue.status==3}"
                                    class="px-2 py-1 ml-2 text-xs font-light text-white rounded-md">
                                    {{ issueStatus(issue.status) }}
                                </p>
                            </div>

                        </div>

                        <div class="py-2">
                            <p class="text-lg">
                                <button @click="selectIssueEvent(issue)"
                                    class="flex items-center font-medium text-white hover:underline" type="button"
                                    style="outline: none;">
                                    Details<span><svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"
                                            class="transition-transform duration-500 ease-in-out transform">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg></span>
                                </button>
                            </p>
                        </div>

                    </div>
                </div>



            </div>
        </div>


        <!-- Add New Issue Modal -->
        <div id="new_issue"
            class="fixed inset-0 z-50 items-center justify-center hidden w-full overflow-hidden animated fadeIn faster"
            style="background: rgba(0,0,0,.7);">
            <div
                class="z-50 w-5/12 mx-auto overflow-y-auto border shadow-lg border-white-500 bg-gray-50 dark:bg-gray-800 modal-container md:max-w-11/12 rounded-xl">
                <div class="px-6 py-4 text-left modal-content">
                    <!--Title-->
                    <div class="flex items-center justify-between pb-3">
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-50">Add New Issue</p>
                        <div class="z-50 cursor-pointer modal-close" @click="closeModal('new_issue')">
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
                        <form v-on:submit.prevent="submitForm" class="w-full">
                            <div class="text-gray-900 dark:text-gray-50">

                                <div class="mt-2">
                                    <!--display errors-->
                                    <p class="my-2 font-bold text-red-500" v-if="issuesForm.errors.issue_title">
                                        {{ issuesForm.errors.issue_title }}
                                    </p>

                                    <label for="issue_title" class=" text-md">Issue Title</label>
                                    <input type="text" id="issue_title" v-model="issuesForm.issue_title"
                                        class="w-full h-2 p-5 mt-2 mb-5 text-sm text-gray-900 border-2 border-gray-300 rounded-md "
                                        placeholder="i.e Payments not Received" />
                                </div>

                                <div class="mt-2">
                                    <!--display errors-->
                                    <p class="my-2 font-bold text-red-500" v-if="issuesForm.errors.issue_description">
                                        {{ issuesForm.errors.issue_description }}
                                    </p>

                                    <label for="issue_description" class=" text-md">Description</label>
                                    <textarea v-model="issuesForm.issue_description" id="issue_description" cols="30"
                                        rows="5" placeholder="Write a short brief description about your issue"
                                        class="w-full p-5 my-2 mb-5 text-sm text-gray-900 border-2 border-gray-300 rounded-md"></textarea>

                                </div>
                                <div class="mt-2">
                                    <!--display errors-->
                                    <p class="my-2 font-bold text-red-500" v-if="issuesForm.errors.issue_category">
                                        {{ issuesForm.errors.issue_category }}
                                    </p>
                                    <label for="issue_category" class="block text-md">Issue Category</label>
                                    <select v-model="issuesForm.issue_category" id="issue_category"
                                        class="my-2 text-sm bg-gray-700 rounded hover:bg-gray-600">
                                        <option value="" selected disabled>Choose ..</option>
                                        <option v-for="category in issue_categories" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--Footer-->
                    <div class="flex justify-end ">
                        <button class="p-3 px-5 font-semibold text-black bg-gray-100 rounded hover:bg-gray-300"
                            @click="closeModal('new_issue')">Cancel</button>
                        <button @click="submitForm" type="button"
                            class="p-3 px-4 ml-3 text-white bg-gray-700 rounded hover:bg-gray-600 ">Add Issue</button>
                    </div>
                </div>
            </div>
        </div>


        <!--selected issue modal-->
        <div id="issue_selected" v-if="selectedIssue"
            class="fixed inset-0 z-50 items-center justify-center hidden w-full overflow-hidden animated fadeIn faster"
            style="background: rgba(0,0,0,.7);">
            <div
                class="z-50 w-6/12 mx-auto overflow-y-auto border shadow-lg border-white-500 bg-gray-50 dark:bg-gray-800 modal-container md:max-w-11/12 rounded-xl">
                <div class="px-6 py-4 text-left modal-content">
                    <!--Title-->
                    <div class="flex items-center justify-between pb-3">
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-50">Issue Details</p>
                        <div class="z-50 cursor-pointer modal-close" @click="closeModal('issue_selected')">
                            <svg class="text-gray-500 fill-current hover:text-white" xmlns="http://www.w3.org/2000/svg"
                                width="18" height="18" viewBox="0 0 18 18">
                                <path
                                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <!--Body-->
                    <div class="mx-4 my-5">
                        <p class="mb-2 text-lg font-bold">
                            Title : <span class="font-light text-green-500">
                                {{ selectedIssue.title }}
                            </span>
                        </p>
                        <p class="mb-2 text-lg font-bold">
                            Description : <span class="font-light text-green-500">
                                {{ selectedIssue.description }}
                            </span>
                        </p>
                        <p class="mb-2 text-lg font-bold" v-if="selectedIssue.issue_category">
                            Issue Category : <span class="font-light text-green-500">
                                {{ selectedIssue.issue_category.name }}
                            </span>
                        </p>
                        <p class="mb-2 text-lg font-bold" v-if="selectedIssue.issue_category">
                            Requested By : <span class="font-light text-green-500">
                                {{ selectedIssue.issued_by.name }}
                            </span>
                        </p>
                        <p class="mb-2 text-lg font-bold">
                            Created at : <span class="font-light text-green-500">
                                {{ moment(selectedIssue.created_at).format('MMMM d YYYY, h:mm:ss a') }}
                            </span>
                        </p>
                        <p class="mb-2 text-lg font-bold">
                            Issue status : <span class="font-light text-green-500">
                                {{ issueStatus(selectedIssue.status) }}
                            </span>
                        </p>
                        <div v-if="selectedIssue.status == 2 && !isStaff" class="mt-4">
                            <!--Show only if the user in not staff and the issue has been assigned-->
                           <div class="py-2 border-t border-white border-dashed">
                            <p class="mb-2 text-lg italic">
                            Asssinged to {{ selectedIssue.assigned_to.name}}
                        </p>
                        <Link as="button" class="text-sm italic text-green-500 hover:underline" :href="route('user.view-message',selectedIssue.issue_id)">
                            View messages
                        </Link>
                           </div>

                       </div>
                    </div>
                    <!--Footer-->
                    <div class="flex justify-end ">
                        <button v-if="isStaff && ! selectedIssue.assigned_to" @click="getAssigned"
                        class="p-3 px-4 mr-4 font-semibold text-white bg-blue-500 rounded hover:bg-blue-600">
                           Respond</button>

                        <button class="p-3 px-4 font-semibold text-black bg-gray-200 rounded hover:bg-gray-300"
                            @click="closeModal('issue_selected')">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>


</template>
