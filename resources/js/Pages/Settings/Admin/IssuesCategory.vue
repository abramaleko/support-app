<script>
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  props:{
    categories: Object,
  },
  data(){
    return {
        formCategory:Object
    }
  },
  created()
  {
    this.formCategory=useForm({
        issue_category:null
    })
  },
  methods:{
    openModal(){
        const modalToOpen = document.getElementById('category_modal');
            modalToOpen.classList.remove('fadeOut');
            modalToOpen.classList.add('fadeIn');
            modalToOpen.style.display = 'flex';
    },
    closeModal(){
        const modalToClose = document.getElementById('category_modal');
            modalToClose.classList.remove('fadeIn');
            modalToClose.classList.add('fadeOut');
            setTimeout(() => {
                modalToClose.style.display = 'none';
            }, 500);
    },
    submitForm()
    {
        this.formCategory.post(route('settings.issues-newCategory'), {
        preserveScroll: true,
        onSuccess: () => {
            this.formCategory.reset();
            this.closeModal();
        }
        })
    }
  },
}

</script>

<template>
        <div class="w-full min-w-0 mb-4 break-words rounded shadow-lg lg:mb-0 bg-gray-50 dark:bg-gray-800">
            <div class="px-0 mb-0 border-0 rounded-t">
              <div class="flex flex-wrap items-center px-4 py-2">
                <div class="relative flex-1 flex-grow w-full max-w-full">
                  <h3 class="text-base font-semibold text-gray-900 dark:text-gray-50">Issues Categories</h3>
                </div>
                <div class="relative flex-1 flex-grow w-full max-w-full text-right">
                  <button @click="openModal"
                  class="px-3 py-1 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-blue-500 rounded outline-none dark:bg-gray-100 active:bg-blue-600 dark:text-gray-800 dark:active:text-gray-700 focus:outline-none" type="button">Add New</button>
                </div>
              </div>
              <div class="block w-full overflow-x-auto">
                <table class="items-center w-full bg-transparent border-collapse">
                  <thead>
                    <tr>
                      <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase align-middle bg-gray-100 border border-l-0 border-r-0 border-gray-200 border-solid dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500 whitespace-nowrap">Category</th>
                      <th class="px-4 py-3 text-xs font-semibold text-left text-gray-500 uppercase align-middle bg-gray-100 border border-l-0 border-r-0 border-gray-200 border-solid dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500 whitespace-nowrap min-w-140-px"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="category in categories" :key="category.id"
                    class="text-gray-700 dark:text-gray-100">
                      <th class="p-4 px-4 text-xs text-left align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap">{{category.name}}</th>
                      <td class="p-4 px-4 text-xs align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap">
                          <button class="px-2 py-1 bg-red-600 rounded hover:bg-red-800">
                            DELETE
                          </button>
                      </td>
                    </tr>
                    <p v-if=" !categories"
                    class="px-4 py-2 text-sm italic">
                         No data found
                    </p>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- Add New Category Modal -->
          <div id="category_modal" class="fixed inset-0 z-50 items-center justify-center hidden w-full overflow-hidden animated fadeIn faster" style="background: rgba(0,0,0,.7);">
		<div class="z-50 w-4/12 mx-auto overflow-y-auto border shadow-lg border-white-500 bg-gray-50 dark:bg-gray-800 modal-container md:max-w-11/12 rounded-xl">
			<div class="px-6 py-4 text-left modal-content">
				<!--Title-->
				<div class="flex items-center justify-between pb-3">
					<p class="text-2xl font-bold text-gray-900 dark:text-gray-50">Add Issue Category</p>
					<div class="z-50 cursor-pointer modal-close" @click="closeModal">
						<svg class="text-gray-500 fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
							viewBox="0 0 18 18">
							<path
								d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
							</path>
						</svg>
					</div>
				</div>
				<!--Body-->
				<div class="flex justify-center my-5 ml-5 mr-5">
                    <form v-on:submit.prevent="submitForm"  class="w-full">
                        <div class="my-2 font-bold text-red-500"
                        v-if="formCategory.errors.issue_category">
                            {{ formCategory.errors.issue_category }}
                        </div>
                        <div class="text-gray-900 dark:text-gray-50">
                            <div class="">
                                <label for="issue_category" class=" text-md">Issue Name</label>
                            </div>
                            <div class="my-2">
                                <input type="text" id="issue_category" v-model="formCategory.issue_category"  class="w-full h-2 p-5 mb-5 text-gray-900 border-2 border-gray-300 rounded-md"/>
                            </div>
                        </div>
                    </form>
				</div>
				<!--Footer-->
				<div class="flex justify-end ">
					<button
						class="p-3 px-4 font-semibold text-black bg-gray-200 rounded hover:bg-gray-300" @click="closeModal">Cancel</button>
					<button @click="submitForm"
						class="p-3 px-4 ml-3 text-white bg-blue-500 rounded-lg hover:bg-teal-400">Add Issue</button>
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
	</style>
