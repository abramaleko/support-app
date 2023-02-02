<script setup>
defineProps({
    permissions: Object,
});
</script>

<template>
        <div class="w-full min-w-0 mb-4 overflow-y-scroll break-words rounded shadow-lg h-72 lg:mb-0 bg-gray-50 dark:bg-gray-800">
            <div class="px-0 mb-0 border-0 rounded-t">
              <div class="flex flex-wrap items-center px-4 py-2">
                <div class="relative w-full max-w-full">
                  <h3 class="text-base font-semibold text-gray-900 dark:text-gray-50">Permissions</h3>
                  <p class="text-sm">
                        This permissions are created automatically when creating issue categories
                    </p>
                </div>
              </div>
              <div class="block w-full overflow-x-auto">
                <table class="items-center w-full bg-transparent border-collapse">
                  <thead>
                    <tr>
                      <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase align-middle bg-gray-100 border border-l-0 border-r-0 border-gray-200 border-solid dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500 whitespace-nowrap">Permission Name</th>
                      <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase align-middle bg-gray-100 border border-l-0 border-r-0 border-gray-200 border-solid dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500 whitespace-nowrap min-w-140-px"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="permission in permissions" :key="permission.id"
                    class="text-gray-700 dark:text-gray-100">
                      <th class="col-span-2 p-4 px-4 text-xs text-left align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap">{{permission.name}}</th>

                    </tr>
                    <p v-if=" !permissions"
                    class="px-4 py-2 text-sm italic">
                         No data found
                    </p>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
</template>

