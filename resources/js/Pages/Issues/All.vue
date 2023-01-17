<script setup>

import { Head } from '@inertiajs/inertia-vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

</script>

<template>

<Head title="All Issues"/>

<AuthLayout>
  <div class="flex justify-center my-16 ">
    <div class="w-full max-w-6xl p-8 rounded bg-gray-50 dark:bg-gray-800">
      <!--header-->
         <div class="flex justify-between">
          <div class="">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-50">All Issues
                <span class="text-xs font-light">230 Tickets</span>
            </h2>
         </div>

         <div class="">
            <input type="text" name="" id="" class="text-gray-600 border-transparent rounded focus:border-transparent focus:ring-0" placeholder="Enter Issue Id">
           <button class="px-6 py-2 ml-4 text-white bg-gray-700 rounded hover:bg-gray-600"> Search Issue </button>
         </div>
         </div>
         <div class="my-4 border bg-gray-50 dark:bg-gray-800"></div>
         <!--end of header-->


         <!--filter-->
         <div class="">
          <select name="filter_options" id="filter_options" class="text-sm bg-gray-700 rounded hover:bg-gray-600">
            <option  value="" selected disabled>Choose ..</option>
            <option value="0">Latest</option>
            <option value="1">Open Issues</option>
            <option value="2">In-Progress Issues</option>
            <option value="2">Closed</option>
          </select>
          <button class="px-4 py-2 ml-4 text-sm bg-gray-700 rounded hover:bg-gray-600"> Filter</button>
         </div>

         <!--issues-->
        <div class="my-8">
          <p class="text-sm italic">
            Showing the latest posts
         </p>
          <!-- Issue-->
          <div class="grid grid-cols-4 gap-8 p-4 my-4 border border-gray-100 rounded">
            <div class="col-span-2 ">
               <h3 class="text-lg font-light"><span class="font-bold ">#30933</span> - Design Admin Dashboard</h3>
               <div class="flex flex-wrap">
                <p class="text-base">Assigned to </p>
               <img src="/images/me.jpg" class="w-6 h-6 mx-3 rounded-full" alt="user-assigned to"/>
               <p class="text-base"> Abraham Maleko</p>
               <img src="/images/icons/calendar.png" class="w-5 h-5 ml-4" alt="assigned-on"/>
               <p class="ml-2 text-base">Today 08:00 PM </p>
               </div>
            </div>

            <div class="py-2">
              <p class="text-lg">
                <img src="/images/me.jpg" class="inline w-8 h-8 mx-3 rounded-full" alt="user-assigned to"/>
                Andrew Tate
              </p>
            </div>

            <div class="py-2">
              <p class="text-lg">
                <img src="/images/icons/open-folder.png" class="inline w-6 h-6 mx-2" alt="user-assigned to"/>
                WireFrame
              </p>
            </div>

         </div>

         <div class="grid grid-cols-4 gap-8 p-4 my-4 border border-gray-100 rounded">
            <div class="col-span-2 ">
               <h3 class="text-lg font-light"><span class="font-bold ">#30933</span> - Design Admin Dashboard</h3>
               <div class="flex flex-wrap">
                <p class="text-base">Assigned to </p>
               <img src="/images/me.jpg" class="w-6 h-6 mx-3 rounded-full" alt="user-assigned to"/>
               <p class="text-base"> Abraham Maleko</p>
               <img src="/images/icons/calendar.png" class="w-5 h-5 ml-4" alt="assigned-on"/>
               <p class="ml-2 text-base">Today 08:00 PM </p>
               </div>
            </div>

            <div class="py-2">
              <p class="text-lg">
                <img src="/images/me.jpg" class="inline w-8 h-8 mx-3 rounded-full" alt="user-assigned to"/>
                Andrew Tate
              </p>
            </div>

            <div class="py-2">
              <p class="text-lg">
                <img src="/images/icons/open-folder.png" class="inline w-6 h-6 mx-2" alt="user-assigned to"/>
                WireFrame
              </p>
            </div>

         </div>

         <div class="grid grid-cols-4 gap-8 p-4 my-4 border border-gray-100 rounded">
            <div class="col-span-2 ">
               <h3 class="text-lg font-light"><span class="font-bold ">#30933</span> - Design Admin Dashboard</h3>
               <div class="flex flex-wrap">
                <p class="text-base">Assigned to </p>
               <img src="/images/me.jpg" class="w-6 h-6 mx-3 rounded-full" alt="user-assigned to"/>
               <p class="text-base"> Abraham Maleko</p>
               <img src="/images/icons/calendar.png" class="w-5 h-5 ml-4" alt="assigned-on"/>
               <p class="ml-2 text-base">Today 08:00 PM </p>
               </div>
            </div>

            <div class="py-2">
              <p class="text-lg">
                <img src="/images/me.jpg" class="inline w-8 h-8 mx-3 rounded-full" alt="user-assigned to"/>
                Andrew Tate
              </p>
            </div>

            <div class="py-2">
              <p class="text-lg">
                <img src="/images/icons/open-folder.png" class="inline w-6 h-6 mx-2" alt="user-assigned to"/>
                WireFrame
              </p>
            </div>

         </div>


         <div class="grid grid-cols-4 gap-8 p-4 my-4 border border-gray-100 rounded">
            <div class="col-span-2 ">
               <h3 class="text-lg font-light"><span class="font-bold ">#30933</span> - Design Admin Dashboard</h3>
               <div class="flex flex-wrap">
                <p class="text-base">Assigned to </p>
               <img src="/images/me.jpg" class="w-6 h-6 mx-3 rounded-full" alt="user-assigned to"/>
               <p class="text-base"> Abraham Maleko</p>
               <img src="/images/icons/calendar.png" class="w-5 h-5 ml-4" alt="assigned-on"/>
               <p class="ml-2 text-base">Today 08:00 PM </p>
               </div>
            </div>

            <div class="py-2">
              <p class="text-lg">
                <img src="/images/me.jpg" class="inline w-8 h-8 mx-3 rounded-full" alt="user-assigned to"/>
                Andrew Tate
              </p>
            </div>

            <div class="py-2">
              <p class="text-lg">
                <img src="/images/icons/open-folder.png" class="inline w-6 h-6 mx-2" alt="user-assigned to"/>
                WireFrame
              </p>
            </div>

         </div>



        </div>



   </div>
  </div>
</AuthLayout>


</template>
