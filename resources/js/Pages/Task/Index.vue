<script>
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    name: "Index",
    layout: MainLayout,

    props: [
        'tasks',
    ],
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
                                            'bg-blue-50 text-blue-700 ring-blue-600/20' : task.status.includes('в процессе'),
                                            'bg-green-50 text-green-700 ring-green-600/20' : task.status.includes('успешно'),
                                            'bg-red-50 text-red-700 ring-red-600/20' : task.status.includes('Ошибка')
                                        }">
                                {{ task.status }}
                                </span>
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

