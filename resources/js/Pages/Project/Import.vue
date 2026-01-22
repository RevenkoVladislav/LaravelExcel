<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head} from "@inertiajs/vue3";


export default {
    name: "Import",

    components: {
        Head
    },

    layout: MainLayout,

    data() {
        return {
            excelFile: null,
            loading: false,
            import_type: 'static',
        }
    },

    methods: {

        /**
         * Загрузка файла при клике на кнопку
         */
        selectExcel() {
            this.$refs.file.click();
        },

        /**
         * Запишем в data загруженный файл
         */
        setExcel(file) {
            this.excelFile = file.target.files[0];
        },

        /**
         * Защита, если уже грузим файл - то выходим из метода
         *
         * Создаем пустую виртуальную форму
         * кладем в эту форму файл с названием file
         * добавляем тип импорта
         *
         * Отправляем форму на сервер
         * Управление индикатором загрузки
         * При успехе обнуляем файл и убираем его из инпута
         */
        importExcel() {
            if (this.loading) return;

            const formData = new FormData();
            this.loading = true;
            formData.append('file', this.excelFile);
            formData.append('import_type', this.import_type)

            this.$inertia.post('/projects/import', formData, {
                onStart: () => this.loading = true,
                onFinish: () => this.loading = false,
                onError: () => this.loading = false,
                onSuccess: () => {
                    this.loading = false;
                    this.resetFile();
                }
            });
        },

        /**
         * метод сброса файла из DOM дерева
         */
        resetFile() {
            this.excelFile = null;

            if (this.$refs.file) {
               this.$refs.file.value = null;
            }
        },
    }
}
</script>

<template>
    <Head title="Import" />
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h1 class="text-xl font-bold text-gray-800 mb-4">Project Import</h1>

        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg p-1">
                <select v-model="import_type"
                        class="bg-transparent border-none text-sm font-medium text-gray-700 focus:ring-0 cursor-pointer px-3 py-2"
                >
                    <option value="static">Static Import</option>
                    <option value="dynamic">Dynamic Import</option>
                </select>

                <div class="w-px h-6 bg-gray-300 mx-1"></div>

                <input @change="setExcel" type="file" ref="file" class="hidden">
                <button
                    @click.prevent="selectExcel"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 hove:text-gray-900 transition-colors"
                >
                    <span v-if="!excelFile">Choose Excel</span>
                    <span v-else class="text-green-600 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Selected
                    </span>
                </button>
            </div>

            <div v-if="excelFile" class="flex items-center">
                <button
                    @click.prevent="importExcel"
                    :disabled="loading"
                    class="min-w-[120px] h-11 flex items-center justify-center rounded-lg text-white font-bold transition-all duration-200 shadow-sm"
                    :class="[
                        loading
                        ? 'bg-gray-400 cursor-not-allowed'
                        : 'bg-gray-800 hover:bg-gray-900 active:scale-95'
                    ]"
                >
                    <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ loading ? 'Processing' : 'Start Import'}}</span>
                </button>

                <button v-if="!loading" @click="resetFile" class="ml-2 text-gray-400 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <p v-if="excelFile && !loading" class="text-xs text-gray-500 mt-2 ml-1 italic">
            Ready to upload: <span class="text-gray-700 font-medium">{{ excelFile.name }}</span>
        </p>

        <div v-if="$page.props.errors.file" class="text-sm text-red-500 mt-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $page.props.errors.file }}
        </div>
    </div>
</template>

<style scoped>

</style>
