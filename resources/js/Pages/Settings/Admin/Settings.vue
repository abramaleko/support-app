<script setup>
  import AuthLayout from '@/Layouts/AuthLayout.vue';
  import { Head } from '@inertiajs/inertia-vue3';
  import IssuesCategory from './IssuesCategory.vue';
  import Permissions from './Permissions.vue';
  import Staff from './Staff.vue';

  defineProps({
    issue_categories:{
        type:Object
    },
    permissions: {
        type:Object,
    },
    staffs : {
        type:Object
    }
  });
</script>

<template>
    <Head title="Settings"/>
    <AuthLayout>
        <div class="mx-4 mb-8">
            <h1 class="py-8 text-3xl font-bold">Admin Settings</h1>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <IssuesCategory :categories="issue_categories"/>
                </div>

                <div>
                    <Permissions :permissions="permissions"/>
                </div>
            </div>
            <div class="mb-4">
               <Staff :staffs="staffs" :permissions="permissions"/>
            </div>
        </div>

    </AuthLayout>
</template>
