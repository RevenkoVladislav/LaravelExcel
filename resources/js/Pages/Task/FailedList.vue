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
    <div class="py-6">
        <div class="mb-6">
            <Link
                :href="route('task.index')"
                class="inline-flex items-center gap-x-1 text-sm font-medium text-gray-500 hover:text-sky-600 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Tasks
            </Link>
        </div>

        <div v-if="failedList.length > 0" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse text-sm text-left">
                    <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold text-gray-900 w-16">ID</th>
                        <th class="px-6 py-4 font-semibold text-gray-900 w-24">Row</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Field (Key)</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Error Message</th>
                        <th class="px-6 py-4 font-semibold text-gray-900 w-32">Date</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    <tr v-for="failed in failedList" :key="failed.id" class="hover:bg-red-50/30 transition-colors">
                        <td class="px-6 py-4 text-gray-400 font-mono text-xs">
                            #{{ failed.id }}
                        </td>

                        <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                    Row {{ failed.row }}
                                </span>
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ failed.key || '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-start gap-2 text-red-600">
                                <svg class="h-4 w-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                </svg>
                                <span class="leading-relaxed">{{ failed.message }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ failed.date || '-' }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-else class="text-center py-12 bg-white border border-dashed border-gray-300 rounded-xl">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No issues found</h3>
            <p class="mt-1 text-sm text-gray-500">This import task completed without any row-level errors.</p>
        </div>
    </div>
</template>

<style scoped>

</style>

