<script>
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import AuthLayout from '@/Layouts/AuthLayout.vue';
import moment from 'moment';


export default {
    components: {
        Head, AuthLayout,Link
    },
    mounted(){
        //scroll to the bottom
        this.scrollBottom();

         //innitalizes the form on component mount
         this.closeIssueForm = useForm({
            close_issue: null,
            issue_id: this.issue.id,
        });
    },
    props:{
        issue : Object,
        messages: Object,
    },
    data(){
        return {
            input_message: null,
            closeIssueForm:Object,
            moment: moment,
        }
    },
    computed:{
        openModal() {
            const modalToOpen = document.getElementById('close_issue');
            modalToOpen.classList.remove('fadeOut');
            modalToOpen.classList.add('fadeIn');
            modalToOpen.style.display = 'flex';
        },
        closeModal() {
            const modalToClose = document.getElementById('close_issue');
            modalToClose.classList.remove('fadeIn');
            modalToClose.classList.add('fadeOut');
            setTimeout(() => {
                modalToClose.style.display = 'none';
            }, 200);
        },
    },
    methods:{
        submitMessage(){

            if(!this.input_message){
                alert('You can not send an empy message');
                return false;
            }

            Inertia.post(route('staff.send-message'),{
            issue_id: this.issue.id,
            issue_code:this.issue.issue_id,
            text: this.input_message,
          },
         {
            onSuccess:(page) => {
            this.input_message=null;
            this.scrollBottom();

          },
         }
          );
        },
        scrollBottom()
        {
        var objDiv = document.getElementById("messages");
         objDiv.scrollTop = objDiv.scrollHeight;
        },
        closeIssue(){
        //  console.log(this.closeIssueForm);
         this.closeIssueForm.post(route('close-issue'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.closeIssueForm.reset();
                    this.closeModal();
                }
            })
        }


    }

}
</script>

<template>

    <Head title="Messages" />
    <AuthLayout>
        <div class="flex justify-center my-8 ">
            <div class="w-full max-w-6xl p-8 rounded bg-gray-50 dark:bg-gray-800">
                <!--header-->
                <div class="flex justify-between">
                    <div class="">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-green-500">#{{issue.issue_id}}- {{ issue.title }}
                        </h2>
                        <p class="font-serif text-xs italic">
                            Issued by {{ issue.issued_by.name }}
                        </p>
                    </div>


                    <div class="">
                        <Link v-if="issue.status == 3"
                        :href="route('staff.messages')" as="button" class="px-6 py-2 ml-4 text-white bg-gray-700 rounded hover:bg-gray-600">BACK
                        </Link>

                        <button v-else @click="openModal"
                         as="button" class="px-6 py-2 ml-4 text-white bg-red-700 rounded hover:bg-red-600">CLOSE
                       </button>

                    </div>

                </div>
                <div class="my-4 border bg-gray-50 dark:bg-gray-800"></div>
                <!--end of header-->

                <!--messages-->
                    <div
                        class="flex flex-col w-full text-gray-800 rounded-md" style="height:35rem;">

                        <div
                            class="flex flex-col flex-grow w-full overflow-hidden bg-gray-700 rounded-lg shadow-xl">
                            <div id="messages" class="flex flex-col flex-grow h-0 p-4 overflow-auto">
                                <div class="flex w-full max-w-xs mt-2 space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gray-300 rounded-full"></div>
                                    <div>
                                        <div class="p-3 bg-gray-300 rounded-r-lg rounded-bl-lg">
                                            <p class="text-sm"> {{ issue.description }}
                                            </p>
                                        </div>
                                        <span class="text-xs leading-none text-gray-500">2 min ago</span>
                                    </div>
                                </div>
                                <!--messages-->
                                <div v-for="message in messages" :key="message.id">

                                    <div v-if="message.user_id == issue.assigned_to.id"
                                    class="flex justify-end w-full max-w-xs mt-2 ml-auto space-x-3">
                                    <div>
                                        <div class="p-3 text-white bg-blue-600 rounded-l-lg rounded-br-lg">
                                            <p class="text-sm">
                                                {{ message.text }}
                                            </p>
                                        </div>
                                        <span class="text-xs leading-none text-gray-500">2 min ago</span>
                                    </div>
                                    <div class="flex-shrink-0 w-10 h-10 bg-gray-300 rounded-full"></div>
                                </div>

                                <div v-if="message.user_id == issue.issued_by.id"
                                class="flex w-full max-w-xs mt-2 space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gray-300 rounded-full"></div>
                                    <div>
                                        <div class="p-3 bg-gray-300 rounded-r-lg rounded-bl-lg">
                                            <p class="text-sm">
                                                {{ message.text }}
                                            </p>
                                        </div>
                                        <span class="text-xs leading-none text-gray-500">2 min ago</span>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <div v-if="issue.status === 2"
                            class="p-4 bg-gray-300">
                                <input @keyup.enter="submitMessage"
                                v-model="input_message"
                                class="flex items-center w-full h-10 px-3 text-sm rounded" type="text"
                                    placeholder="Type your message…">
                            </div>

                            <div v-if="issue.status == 3">
                                <div class="py-2 border-t border-white border-dashed"></div>
                               <div class="p-4 ">
                                <p class="italic text-white"
                                >This issue has been marked closed on {{  moment(issue.updated_At).format('dddd, MMMM Do YYYY') }}</p>
                                <p class="py-1 text-green-500">
                                     Reason :
                                </p>
                                <p class="italic text-white">
                                   {{ issue.closing_description }}
                                </p>
                               </div>
                            </div>
                        </div>
                        <!-- Component End  -->

                    </div>



            </div>
        </div>

         <!-- Close Issue Modal -->
         <div id="close_issue"
            class="fixed inset-0 z-50 items-center justify-center hidden w-full overflow-hidden animated fadeIn faster"
            style="background: rgba(0,0,0,.7);">
            <div
                class="z-50 w-5/12 mx-auto overflow-y-auto border shadow-lg border-white-500 bg-gray-50 dark:bg-gray-800 modal-container md:max-w-11/12 rounded-xl">
                <div class="px-6 py-4 text-left modal-content">
                    <!--Title-->
                    <div class="flex items-center justify-between pb-3">
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-50">Close Issue #{{ issue.issue_id }}</p>
                        <div class="z-50 cursor-pointer modal-close" @click="closeModal">
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
                                    <label for="issue_description" class=" text-md">Closing Text</label>
                                    <textarea v-model="closeIssueForm.close_issue" id="issue_description" cols="30"
                                        rows="5" placeholder="Write if the issue has been closed successfully or a message detailing why the issue was closed"
                                        class="w-full p-5 my-2 mb-5 text-sm text-gray-900 border-2 border-gray-300 rounded-md"></textarea>

                                </div>
                            </div>
                        </form>
                    </div>
                    <!--Footer-->
                    <div class="flex justify-end ">
                        <button class="p-3 px-5 font-semibold text-black bg-gray-100 rounded hover:bg-gray-300"
                            @click="closeModal">Cancel</button>
                        <button @click="closeIssue" type="button"
                            class="p-3 px-4 ml-3 text-white bg-red-700 rounded hover:bg-red-600 ">Mark Closed</button>
                    </div>
                </div>
            </div>
        </div>

    </AuthLayout>



</template>
