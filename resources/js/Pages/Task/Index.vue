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
        Task Index
        <div class="mt-4 -mb-3" v-if="tasks.length > 0">
            <div class="not-prose overflow-auto rounded-lg bg-white outline outline-white/5 dark:bg-gray-950/50">
                <div class="my-8 overflow-hidden">
                    <table class="w-full table-auto border-collapse text-sm">
                        <thead>
                        <tr>
                            <th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">
                                User
                            </th>
                            <th class="border-b border-gray-200 p-4 pt-0 pb-3 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">
                                File
                            </th>
                            <th class="border-b border-gray-200 p-4 pt-0 pr-8 pb-3 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                        <tr v-for="task in tasks" :key="task.id">
                            <pre>{{task}}</pre>
                            <td class="border-b border-gray-100 p-4 pl-8 text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                {{ task.user.data.name }}
                            </td>
                            <td class="border-b border-gray-100 p-4 text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                {{ task.file.data.path }}
                            </td>
                            <td class="border-b border-gray-100 p-4 pr-8 text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                {{ task.status }}
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
