<script setup>

import { Head, useForm } from '@inertiajs/inertia-vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { onMounted } from 'vue';
import moment from 'moment';


const props=defineProps({
    issue_categories: Object,
    issues: Object
})

const IssuesForm=useForm({
    issue_title:null,
    issue_description:null,
    issue_category:null,
});

function openModal() {
    const modalToOpen = document.getElementById('new_issue');
    modalToOpen.classList.remove('fadeOut');
    modalToOpen.classList.add('fadeIn');
    modalToOpen.style.display = 'flex';
}

function closeModal(){
        const modalToClose = document.getElementById('new_issue');
            modalToClose.classList.remove('fadeIn');
            modalToClose.classList.add('fadeOut');
            setTimeout(() => {
                modalToClose.style.display = 'none';
            }, 500);
    }

    function submitForm(){
        IssuesForm.post(route('issues.new'), {
        preserveScroll: true,
        onSuccess: () => {
            IssuesForm.reset();
            closeModal();
        }
        })
    }



</script>

<template>

    <Head title="All Issues" />

    <AuthLayout>
        <div class="flex justify-center my-8 ">
            <div class="w-full max-w-6xl p-8 rounded bg-gray-50 dark:bg-gray-800">
                <!--header-->
                <div class="flex justify-between">
                    <div class="">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-50">All Issues
                            <span class="text-xs font-light">230 Tickets</span>
                        </h2>
                    </div>

                    <div class="">
                        <button type="button" @click="openModal"
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
                    <p class="text-sm italic">
                        Showing the latest issues
                    </p>
                    <!-- Issue-->
                    <div v-for="issue in issues" :key="issue.id"
                    class="grid grid-cols-3 gap-8 p-4 my-4 border border-gray-100 rounded">
                        <div class="col-span-2 ">
                            <h3 class="text-lg font-light">
                                <span class="font-bold text-green-500 cursor-pointer hover:underline">#{{ issue.issue_id }}</span >
                                     - {{ issue.title }}
                            </h3>
                            <div v-if="issue.assigned_to"
                            class="flex flex-wrap mt-1">
                                <p class="text-base">Assigned to </p>
                                <img src="/images/me.jpg" class="w-6 h-6 mx-3 rounded-full" alt="user-assigned to" />
                                <p class="text-base"> Abraham Maleko</p>
                                <img src="/images/icons/calendar.png" class="w-5 h-5 ml-4" alt="calendar" />
                                <p class="ml-2 text-base">Today 08:00 PM </p>
                            </div>

                            <div v-else class="flex flex-wrap mt-1">
                                <img src="/images/icons/calendar.png" class="w-5 h-5" alt="calendar" />
                                <p class="ml-2 text-base">{{ moment(issue.created_at).format('MMMM d YYYY') }}</p>

                                <p class="ml-2 font-light">, Not Assigned Yet (Open Issue) </p>


                            </div>
                        </div>


                        <div class="py-2">
                            <p class="text-lg">
                                <img src="/images/icons/open-folder.png" class="inline w-6 h-6 mx-2"
                                    alt="user-assigned to" />
                                {{issue.issue_category.name}}
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
                        <div class="z-50 cursor-pointer modal-close" @click="closeModal">
                            <svg class="text-gray-500 fill-current" xmlns="http://www.w3.org/2000/svg" width="18"
                                height="18" viewBox="0 0 18 18">
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
                                    <p class="my-2 font-bold text-red-500" v-if="IssuesForm.errors.issue_title">
                                        {{ IssuesForm.errors.issue_title }}
                                    </p>

                                    <label for="issue_title" class=" text-md">Issue Title</label>
                                    <input type="text" id="issue_title" v-model="IssuesForm.issue_title"
                                        class="w-full h-2 p-5 mt-2 mb-5 text-sm text-gray-900 border-2 border-gray-300 rounded-md " placeholder="i.e Payments not Received" />
                                </div>

                                <div class="mt-2">
                                    <!--display errors-->
                                    <p class="my-2 font-bold text-red-500" v-if="IssuesForm.errors.issue_description">
                                        {{ IssuesForm.errors.issue_description }}
                                    </p>

                                    <label for="issue_description" class=" text-md">Description</label>
                                    <textarea v-model="IssuesForm.issue_description"
                                    id="issue_description" cols="30" rows="5"
                                    placeholder="Write a short brief description about your issue"
                                    class="w-full p-5 my-2 mb-5 text-sm text-gray-900 border-2 border-gray-300 rounded-md"></textarea>

                                </div>
                                <div class="mt-2">
                                    <!--display errors-->
                                    <p class="my-2 font-bold text-red-500" v-if="IssuesForm.errors.issue_category">
                                        {{ IssuesForm.errors.issue_category }}
                                    </p>
                                    <label for="issue_category" class="block text-md">Issue Category</label>
                                    <select v-model="IssuesForm.issue_category"  id="issue_category"
                                        class="my-2 text-sm bg-gray-700 rounded hover:bg-gray-600">
                                        <option value="" selected disabled>Choose ..</option>
                                        <option v-for="category in issue_categories"
                                        :value="category.id">
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
                            @click="closeModal">Cancel</button>
                        <button @click="submitForm" type="button"
                            class="p-3 px-4 ml-3 text-white bg-gray-700 rounded hover:bg-gray-600 ">Add Issue</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>


</template>
