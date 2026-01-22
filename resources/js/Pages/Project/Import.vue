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
                    this.excelFile = null;
                    this.$refs.file.value = null;
                }
            });
        },
    }
}
</script>

<template>
    <Head title="Import" />
    <div>
        <h1 class="mb-3">Import</h1>

        <div class="flex">
            <form>
                <div class="mr-2">
                    <select v-model="import_type" class="rounded">
                        <option value="static">Static import</option>
                        <option value="dynamic">Dynamic import</option>
                    </select>
                </div>
                <input @change="setExcel" type="file" ref="file" class="hidden">
                <button @click.prevent="selectExcel" class="block rounded-full w-32 text-center text-white p-2 bg-gradient-to-r from-green-500 to-green-600 hover:bg-gradient-to-bl">Excel</button>
            </form>
            <div v-if="excelFile" class="ml-3">
                <button
                    @click.prevent="importExcel"
                    :disabled="loading"
                    class="block rounded-full w-32 text-center text-white p-2 transition-all"
                    :class="[
                        loading
                        ? 'bg-gray-400 cursor-not-allowed opacity-70'
                        : 'bg-gradient-to-r from-gray-600 to-gray-800 hover:bg-gradient-to-bl',
                    ]"
                >
                    <span v-if="loading">Wait...</span>
                    <span v-else>Import</span>
                </button>
                <div v-if="loading" class="text-center text-gray-600 font-bold mt-1">Uploading...</div>
            </div>
        </div>
        <div v-if="$page.props.errors.file" class="text-sm text-red-500 mt-2">
            {{ $page.props.errors.file }}
        </div>
    </div>
</template>

<style scoped>

</style>
