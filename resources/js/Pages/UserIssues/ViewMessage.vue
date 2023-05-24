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
                            Assigned to {{ issue.assigned_to.name }}
                        </p>
                        <p v-if="userOnline"
                        class="pt-2 text-sm font-light text-green-500">
                             Online
                        </p>
                    </div>


                    <div class="">
                        <Link :href="route('messages')" as="button" class="px-6 py-2 ml-4 text-white bg-gray-700 rounded hover:bg-gray-600">BACK
                        </Link>
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
                                        <span class="text-xs leading-none text-gray-500">{{ moment(issue.created_at).startOf('min').fromNow() }}</span>
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
                                        <span class="text-xs leading-none text-gray-500">{{ moment(message.created_at).startOf('min').fromNow() }}</span>
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
                                        <span class="text-xs leading-none text-gray-500">{{ moment(message.created_at).startOf('min').fromNow() }}</span>
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

    </AuthLayout>
</template>

<script>
import { Head,Link,usePage } from '@inertiajs/inertia-vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import moment from 'moment';

export default {
    components: {
        Head, AuthLayout,Link
    },
    mounted(){
        if (this.issue.status !=3) {
            window.Echo
                .join(`Chat.${this.issue.id}`)
                .here(users => {
                        users.forEach(user => {
                            if (user.id == this.issue.assigned_to.id) {
                            this.userOnline=true;
                            }
                        });
                        })
                    .joining(user => {
                        if (user.id == this.issue.assigned_to.id) {
                            this.userOnline=true;
                        }
                    })
                    .leaving(user => {
                        console.log('A user left the channel: ', user);
                        if (user.id == this.issue.assigned_to.id) {
                        this.userOnline=false;
                        }
                    })
                    .listen('.new-message', (e) => {
                        this.messages.push({
                            text: e.message,
                            user_id: e.sender_id
                        });
                        this.scrollBottom();
                    });
        }

        //scroll to the bottom
        this.scrollBottom();
    },
    props:{
        issue : Object,
        messages: Object,
    },
    data(){
        return {
            input_message: null,
            moment: moment,
            userOnline:false,
        }
    },
    methods:{
        submitMessage(){

            if(!this.input_message){
                alert('You can not send an empy message');
                return false;
            }

            //adds message to list
            this.messages.push({
                text: this.input_message,
                user_id: usePage().props.value.auth.user.id,
            });

            //saves to database
            this.$inertia.visit(route('user.send-message'),{
                    method: 'post',
                    preserveScroll:true,
                    only: [],
                    data:{
                        issue_id: this.issue.id,
                        issue_code:this.issue.issue_id,
                        text: this.input_message,
                    },
                    headers:{
                    'X-Socket-ID' : window.Echo.socketId(),
                    },
                    onSuccess: page => {
                        this.input_message=null;
                        this.scrollBottom();
                    }
                })

            this.scrollBottom();
        },
        scrollBottom()
        {
        var objDiv = document.getElementById("messages");
         objDiv.scrollTop = objDiv.scrollHeight;
        }
    }

}
</script>
