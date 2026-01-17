<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Link } from '@inertiajs/vue3';

export default {
    name: "FailedList",
    layout: MainLayout,

    props: [
        'failedList',
    ],

    components: {
        Link,
    },
}
</script>

<template>
    <div>
        <div>
            <Link class="text-sm" :href="route('task.index')">Back</Link>
        </div>
        <div class="mt-4 -mb-3" v-if="failedList.length > 0">
            <div class="overflow-auto rounded-xl bg-white border border-gray-200 shadow-sm">
                <div class="overflow-hidden">
                    <table class="w-full table-auto border-collapse text-sm">
                        <thead>
                        <tr class="bg-gray-50">
                            <th class="border-b border-gray-200 p-4 text-center font-semibold text-gray-600">
                                Id
                            </th>
                            <th class="border-b border-gray-200 p-4 text-center font-semibold text-gray-600">
                                Key
                            </th>
                            <th class="border-b border-gray-200 p-4 text-center font-semibold text-gray-600">
                                Row
                            </th>
                            <th class="border-b border-gray-200 p-4 text-center font-semibold text-gray-600">
                                Message
                            </th>
                            <th class="border-b border-gray-200 p-4 text-center font-semibold text-gray-600">
                                Task Id
                            </th>
                            <th class="border-b border-gray-200 p-4 text-center font-semibold text-gray-600">
                                Date
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                        <tr v-for="failed in failedList" :key="failedList.id" class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 text-center text-gray-700">
                                {{ failed.id || '-' }}
                            </td>
                            <td class="p-4 text-center text-gray-700">
                                {{ failed.key || '-' }}
                            </td>
                            <td class="p-4 text-center text-gray-700">
                                {{ failed.row || '-' }}
                            </td>
                            <td class="p-4 text-center">
                                 <span
                                     class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset 'bg-red-50 text-red-700 ring-red-600/20'">
                                {{ failed.message || '-' }}
                                </span>
                            </td>
                            <td class="p-4 text-center text-gray-700">
                                {{ failed.task_id || '-' }}
                            </td>
                            <td class="p-4 text-center text-gray-700">
                                {{ failed.date || '-' }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>

