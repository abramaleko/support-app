<script setup>
  import AuthLayout from '@/Layouts/AuthLayout.vue';
  import { Head } from '@inertiajs/inertia-vue3';
  import IssuesCategory from './IssuesCategory.vue';

  defineProps({
    issue_categories:{
        type:Object
    }
  });
</script>

<template>
    <Head title="Settings"/>
    <AuthLayout>
        <div class="mx-4 mb-8">
            <h1 class="py-8 text-3xl font-bold">Admin Settings</h1>
            <div class="grid grid-cols-2 gap-4">
                <div class="">
                    <IssuesCategory :categories="issue_categories"/>
                </div>

                <div class="">
                    <IssuesCategory :categories="issue_categories"/>
                </div>

            </div>
        </div>

    </AuthLayout>
</template>
