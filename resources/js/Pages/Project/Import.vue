<script>
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    name: "Import",

    layout: MainLayout,

    data() {
        return {
            excelFile: null,
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
         * Создаем пустую виртуальную форму
         * кладем в эту форму файл с названием file
         * Отправляем форму на сервер
         */
        importExcel() {
            const formData = new FormData();
            formData.append('file', this.excelFile);

            this.$inertia.post('/projects/import', formData);
        },
    }
}
</script>

<template>
    <div>
        <h1 class="mb-3">Import</h1>

        <div class="flex">
            <form>
                <input @change="setExcel" type="file" ref="file" class="hidden">
                <button @click.prevent="selectExcel" class="block rounded-full w-32 text-center text-white p-2 bg-gradient-to-r from-green-500 to-green-600 hover:bg-gradient-to-bl">Excel</button>
            </form>
            <div v-if="excelFile" class="ml-3">
                <button @click.prevent="importExcel" class="block rounded-full w-32 text-center text-white p-2 bg-gradient-to-r from-sky-500 to-sky-600 hover:bg-gradient-to-bl">Import</button>
            </div>
        </div>
        <div v-if="$page.props.errors.file" class="text-sm text-red-500 mt-2">
            {{ $page.props.errors.file }}
        </div>
    </div>
</template>

<style scoped>

</style>
