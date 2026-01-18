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
    <div class="py-6">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm" v-if="tasks.length > 0">
                <div class="overflow-hidden">
                    <table class="w-full table-auto border-collapse text-sm text-left">
                        <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-200">
                            <th class="px-6 py-4 font-semibold text-gray-900">User</th>
                            <th class="px-6 py-4 font-semibold text-gray-900">File Reference</th>
                            <th class="px-6 py-4 font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-4 font-semibold text-gray-900">Issues</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        <tr v-for="task in tasks" :key="task.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ task.user.data.name || '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <div class="truncate font-mono text-xs text-gray-500">
                                    {{ task.file.data.path || '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset"
                                    :class="{
                                            'bg-blue-50 text-blue-700 ring-blue-600/20' : task.status.includes('in progress'),
                                            'bg-green-50 text-green-700 ring-green-600/20' : task.status.includes('imported successfully'),
                                            'bg-red-50 text-red-700 ring-red-600/20' : task.status.includes('Error during import')
                                        }">
                                {{ task.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <Link
                                    v-if="task.failed_rows_count > 0"
                                    :href="route('task.failedList', task.id)"
                                    class="inline-flex items-center gap-x-1.5 text-xs font-semibold text-red-600 hover:text-red-500 transition-colors"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                    View {{ task.failed_rows_count }} errors
                                </Link>
                                <span v-else class="text-gray-400 text-xs italic">No errors</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        <div v-else class="text-center py-12 border-2 border-dashed border-gray-200 rounded-xl">
            <p class="text-gray-500">No import tasks found.</p>
        </div>
    </div>
</template>

<style scoped>

</style>
