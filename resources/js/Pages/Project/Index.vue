<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import PaymentsModal from "@/Components/PaymentsModal.vue";

export default {
    name: "Index",

    data() {
        return {
            showPayments: false,
            selectedPayments: [],
        }
    },

    components: {
        Head,
        Pagination,
        Link,
        PaymentsModal
    },

    layout: MainLayout,

    props: [
        'projects',
    ],

    methods: {
        /**
         * Форматируем число в валюту (Рубли)
         * Используем стиль - currency
         * тип валюту - рубли
         * Знаки после запятой - 2
         *
         */
        formatCurrency(value) {
            return new Intl.NumberFormat('ru-RU', {
                style: 'currency',
                currency: 'RUB',
                maximumFractionDigits: 2
            }).format(value);
        },

        /**
         * Метод для формирование классов для бейджей
         * Принимает параметр isActive (берем true или false из ресурса) и цвета для бейджиков
         * Формируем базовые цвета в константу base
         * Формируем константу variants с прописанными цветами для кнопок (если не прописан то вернем blue)
         * Формируем константу inactive если isActive - false
         * Составляем все стили и возвращаем
         *
         */
        getBadgeClass(isActive, color) {
            const base = 'px-2 py-0.5 rounded text-[10px] font-bold border transition-all duration-200';

            const variants = {
                purple: 'bg-purple-100 text-purple-700 border-purple-200',
                blue: 'bg-blue-100 text-blue-700 border-blue-200',
                indigo: 'bg-indigo-100 text-indigo-700 border-indigo-200',
                green: 'bg-green-100 text-green-700 border-green-200',
                amber: 'bg-amber-100 text-amber-700 border-amber-200',
            };

            const inactive = 'bg-gray-50 text-gray-300 border-gray-100 opacity-60';

            return `${base} ${isActive ? (variants[color] || variants.blue) : inactive}`;
        },

        openPayments(project) {
            this.selectedPayments = project.payments;
            this.showPayments = true;
        },
    }
}
</script>

<template>
    <Head title="Projects"/>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div v-if="projects.data.length > 0" class="flex flex-col">
            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
                <table class="w-full table-auto border-collapse text-sm text-left">
                    <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-200">
                        <th class="sticky left-0 z-10 bg-gray-50 px-6 py-4 font-semibold text-gray-900 border-r border-gray-200">
                            Project Details
                        </th>
                        <th class="px-6 py-4 font-semibold text-gray-900 text-center">Dates</th>
                        <th class="px-6 py-4 font-semibold text-gray-900 text-center">Attributes</th>
                        <th class="px-6 py-4 font-semibold text-gray-900 text-center">Resources</th>
                        <th class="px-6 py-4 font-semibold text-gray-900 text-center">Payments (Total)</th>
                        <th class="px-6 py-4 font-semibold text-gray-900">Efficiency</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    <tr v-for="project in projects.data" :key="project.id"
                        class="hover:bg-gray-50/80 transition-colors">

                        <!-- блок project details (id, title, type.title -->
                        <td class="sticky left-0 z-10 bg-white px-6 py-4 border-r border-gray-200">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900">#{{ project.id }} {{ project.title }}</span>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">{{ project.type.title }}</span>
                            </div>
                        </td>
                        <!-- конек блока project details -->


                        <!-- блок с date (creation, contracted, deadline -->
                        <td class="px-6 py-4 whitespace-nowrap text-xs">
                            <div class="flex flex-col gap-1">
                                <div class="flex justify-between gap-4">
                                    <span class="text-gray-400">Created:</span>
                                    <span class="text-gray-700 font-medium">{{ project.creation_date }}</span>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span class="text-gray-400">Contract:</span>
                                    <span class="text-gray-700 font-medium">{{ project.contracted_date }}</span>
                                </div>
                                <div v-if="project.deadline" class="flex justify-between gap-4 text-amber-600">
                                    <span>Deadline:</span>
                                    <span class="font-bold">{{ project.deadline }}</span>
                                </div>
                            </div>
                        </td>
                        <!-- конец блока с date-->

                        <!-- Блок с аттрибутами (chain, outsource, investors, сдача в срок - on_time -->
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5 justify-center max-w-[220px] mx-auto">
                                <span :class="getBadgeClass(project.is_chain, 'purple')">
                                    CHAIN
                                </span>

                                <span :class="getBadgeClass(project.has_outsource, 'blue')">
                                    OUTSOURCE
                                </span>

                                <span :class="getBadgeClass(project.has_investors, 'indigo')">
                                    INVESTORS
                                </span>

                                <span :class="getBadgeClass(project.is_on_time, 'green')">
                                    ON TIME
                                </span>
                            </div>
                        </td>
                        <!-- Конец блока с аттрибутами -->

                        <!-- Блок с Resources (workers count, service count) -->
                        <td class="px-6 py-4 text-center">
                            <div
                                class="inline-flex divide-x divide-gray-200 border border-gray-200 rounded-lg overflow-hidden">
                                <div class="px-3 py-1 bg-gray-50" title="Workers">
                                    <span class="text-xs text-gray-500 mr-1">W:</span>
                                    <span class="font-semibold text-gray-700">{{ project.workers_count || 0 }}</span>
                                </div>
                                <div class="px-3 py-1 bg-gray-50" title="Services">
                                    <span class="text-xs text-gray-500 mr-1">S:</span>
                                    <span class="font-semibold text-gray-700">{{ project.services_count || 0 }}</span>
                                </div>
                            </div>
                        </td>
                        <!-- Конец блока с Resources -->

                        <!-- Блок с общей суммой вложений -->
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex flex-col cursor-pointer" @click="openPayments(project)">
                                    <span class="text-gray-900 font-bold hover:underline">
                                        {{ formatCurrency(project.total_payments) }}
                                    </span>
                                <span class="text-[10px] text-gray-400 uppercase">All steps summary</span>
                            </div>
                            <PaymentsModal
                                :show="showPayments"
                                :payments="selectedPayments"
                                @close="showPayments = false"
                            />
                        </td>
                        <!-- Конец блока с общей суммой вложений -->

                        <!-- Шкала эффективности -->
                        <td class="px-6 py-4">
                            <div class="w-full bg-gray-100 rounded-full h-2 min-w-[100px]">
                                <div class="bg-sky-500 h-2 rounded-full"
                                     :style="{ width: project.efficiency_value + '%' }"></div>
                            </div>
                            <span class="text-[10px] font-medium text-gray-500">{{ project.efficiency_value }}%</span>
                        </td>
                        <!-- Конец шкалы эффективности -->
                    </tr>
                    </tbody>
                </table>

                <div class="px-6 border-t border-gray-100">
                    <Pagination :meta="projects.meta"/>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-12 border-2 border-dashed border-gray-200 rounded-xl">
            <p class="text-gray-500 text-lg font-medium">No projects imported yet.</p>
        </div>
    </div>
</template>

<style scoped>

</style>
