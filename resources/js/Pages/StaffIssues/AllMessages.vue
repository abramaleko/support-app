<script>

import { Head, Link } from '@inertiajs/inertia-vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import moment from 'moment';

export default {
    components: {
        Head, AuthLayout, Link
    },
    props: {
        issues: Object,
        user_id: Number,
    },
    data() {
        return {
            moment: moment,
        }
    },
    methods: {
        checkLatestMsg(chat) {
            if (chat[0]) {
                return chat[0].text;
            }
            else {
                return issue.description;
            }
        },
        newMessageBadge(issue){
          //show the badge only if the last message is not current user
         if (this.user_id != issue.chats[0].user_id) {
            //show badge when the last message is not read or there are no last messages
          if (!issue.chats[0].read || !issue.chats) {
             return true;
          }
        }
        return false;
        }
    }
}
</script>

<template>
    <Head title="Issue Messages" />

    <AuthLayout>
        <div class="flex justify-center my-8 ">
            <div class="w-full max-w-6xl p-8 rounded bg-gray-50 dark:bg-gray-800">
                <!--header-->
                <div class="flex justify-between">
                    <div class="">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-50">Issue Messages
                            <span v-if="issues.length > 0"
                            class="text-xs font-light">( {{ issues.length }}  )</span>
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

                <!--messages-->
                <div class="my-8">
                    <p class="text-sm italic ">
                        Showing all the latest assigned issues
                    </p>

                    <!-- Issue-->
                    <div v-for="issue in issues" :key="issue.id"
                        class="grid grid-cols-3 gap-8 p-4 my-4 border border-gray-100 rounded">
                        <div class="col-span-2 ">
                            <h3 class="text-lg font-light">
                                <span class="font-bold text-green-500 cursor-pointer hover:underline">#{{
                                    issue.issue_id
                                }}</span>
                                - {{ issue.title }}
                                <span v-if="newMessageBadge(issue)"
                                    class="px-2 py-1 ml-2 text-xs text-white bg-blue-500 rounded-md"> New</span>
                            </h3>
                            <p class="text-sm font-light">{{ user_id == issue.chats[0].user_id ? 'Me' : 'Message' }}:
                                {{ checkLatestMsg(issue.chats) }}</p>
                            <p class="pt-1 text-xs italic font-light text-green-500">Issued on {{
                                moment(issue.created_at).format('d MMMM YYYY') }}</p>
                        </div>
                        <div class="py-3">
                            <Link :href="route('staff.view-message', issue.issue_id)" as="button"
                                class="px-6 py-2 text-base text-white bg-gray-500 rounded-md hover:bg-gray-600">
                            View
                            </Link>
                        </div>


                    </div>
                </div>



            </div>
        </div>
    </AuthLayout>
</template>

