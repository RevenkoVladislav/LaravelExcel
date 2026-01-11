<script>
import {Link} from '@inertiajs/vue3';

export default {
    name: "Navigation",

    components: {
        Link,
    }
}
</script>

<template>
    <div class="mb-4">
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
                <div class="relative flex h-16 items-center justify-between">
                    <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                        <div class="hidden sm:ml-6 sm:block">
                            <div class="flex space-x-4">
                                <Link :class="['px-3 py-2 rounded-md text-sm font-medium',
                                $page.component === 'Project/Index'
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white']"
                                      :href="route('project.index')">
                                    Projects
                                </Link>
                                <Link :class="['px-3 py-2 rounded-md text-sm font-medium',
                                $page.component === 'Project/Import'
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white']"
                                      :href="route('project.import')">
                                    Import
                                </Link>
                                <Link :class="['px-3 py-2 rounded-md text-sm font-medium',
                                $page.component === 'Task/Index'
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white']"
                                      :href="route('task.index')">
                                    Tasks
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</template>

<style scoped>

</style>
