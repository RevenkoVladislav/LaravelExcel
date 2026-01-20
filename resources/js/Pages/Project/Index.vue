<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";

export default {
    name: "Index",

    components: {
        Head,
        Pagination,
        Link
    },

    layout: MainLayout,

    props: [
        'projects',
    ]
}
</script>

<template>
    <Head title="Projects" />

    <div class="py-6">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm" v-if="projects.data.length > 0">
            <div class="overflow-hidden">
                <table class="w-full table-auto border-collapse text-sm text-left">
                    <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold text-gray-900">Id</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Type</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Title</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Creation date</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Contracted date</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Deadline</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Is chain</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Is on time</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Outsource</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Investors</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Workers</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Services</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Payment first</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Payment second</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Payment third</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Payment fourth</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Comment</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Efficiency value</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    <tr v-for="project in projects.data" :key="project.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.id }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.type.title }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.title || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.creation_date || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.contracted_date || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.deadline || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.is_chain || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.is_on_time || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.has_outsource || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.has_investors || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.workers_count || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.services_count || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.payment_first_step || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.payment_second_step || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.payment_third_step || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.payment_fourth_step || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.comment || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ project.efficiency_value || '-' }}
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <Pagination :meta="projects.meta"></Pagination>
            </div>
        </div>

        <div v-else class="text-center py-12 border-2 border-dashed border-gray-200 rounded-xl">
            <p class="text-gray-500">No projects found.</p>
        </div>
    </div>
</template>

<style scoped>

</style>
