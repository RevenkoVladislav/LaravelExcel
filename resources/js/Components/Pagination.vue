<script>
import {Link} from '@inertiajs/vue3';

export default {
    name: "Pagination",

    props: [
        'meta',
    ],

    components: {
        Link,
    }
}
</script>

<template>
    <div v-if="meta.links.length > 3" class="flex items-center justify-between bg-transparent px-2 py-6">
        <div>
            <p class="text-sm text-gray-500 ml-1">
                Showing <span class="font-semibold text-gray-700">{{ meta.from }}</span> to <span
                class="font-semibold text-gray-700">{{ meta.to }}</span> of <span
                class="font-semibold text-gray-700">{{ meta.total }}</span> results
            </p>
        </div>
        <div>
            <nav class="isolate inline-flex gap-x-1.5 pr-1" aria-label="Pagination">
                <template v-for="(link, key) in meta.links" :key="key">

                        <span v-if="link.url === null"
                              v-html="link.label"
                              class="relative inline-flex items-center px-3.5 py-2 text-sm font-medium text-gray-400 border border-gray-200 rounded-lg bg-gray-50/50">
                        </span>

                    <Link v-else
                          :href="link.url"
                          v-html="link.label"
                          class="relative inline-flex items-center px-3.5 py-2 text-sm font-semibold transition-all duration-200 rounded-lg border"
                          :class="[
                                  link.active
                                    ? 'z-10 bg-gray-800 border-gray-800 text-white shadow-sm'
                                    : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50 hover:border-gray-400'
                              ]"
                    />
                </template>
            </nav>
        </div>
    </div>
</template>

<style scoped>

</style>
