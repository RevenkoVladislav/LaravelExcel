<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Link } from '@inertiajs/vue3';

export default {
    name: "Index",
    layout: MainLayout,

    props: [
        'tasks',
    ],

    components: {
        Link,
    },
}
</script>

<template>
    <div>
        <div class="mt-4 -mb-3" v-if="tasks.length > 0">
            <div class="overflow-auto rounded-xl bg-white border border-gray-200 shadow-sm">
                <div class="overflow-hidden">
                    <table class="w-full table-auto border-collapse text-sm">
                        <thead>
                        <tr class="bg-gray-50">
                            <th class="border-b border-gray-200 p-4 text-left font-semibold text-gray-600">
                                User
                            </th>
                            <th class="border-b border-gray-200 p-4 text-left font-semibold text-gray-600">
                                File
                            </th>
                            <th class="border-b border-gray-200 p-4 text-left font-semibold text-gray-600">
                                Status
                            </th>
                            <th class="border-b border-gray-200 p-4 text-left font-semibold text-gray-600">
                                Failed rows
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                        <tr v-for="task in tasks" :key="task.id" class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 pl-6 text-gray-700">
                                {{ task.user.data.name || '-' }}
                            </td>
                            <td class="p-4 text-gray-600 font-mono text-xs text-left">
                                {{ task.file.data.path || '-' }}
                            </td>
                            <td class="p-4 text-left">
                                <span
                                    class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset"
                                    :class="{
                                            'bg-blue-50 text-blue-700 ring-blue-600/20' : task.status.includes('in progress'),
                                            'bg-green-50 text-green-700 ring-green-600/20' : task.status.includes('imported successfully'),
                                            'bg-red-50 text-red-700 ring-red-600/20' : task.status.includes('Error during import')
                                        }">
                                {{ task.status }}
                                </span>
                            </td>
                            <td class="p-4 text-gray-600 font-mono text-xs text-left">
                                <Link v-if="task.failed_rows_count > 0" class="text-sky-500" :href="route('task.failedList', task.id)">Failed Row</Link>
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

