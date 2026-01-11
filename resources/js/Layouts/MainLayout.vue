<script>
import { Link } from '@inertiajs/vue3';
import Navigation from "@/Components/Navigation.vue";

export default {
    name: "MainLayout",
    components: {
        Link,
        Navigation,
    }
}
</script>

<template>
    <div class="w-full mx-auto">
        <!-- Навигационное меню -->
        <Navigation></Navigation>
        <!-- Конец навигационного меню -->

        <!-- Вывод контента из Pages -->
        <div class="w-4/6 mx-auto">
            <slot />
        </div>
        <!-- Конец вывода контента из Pages -->
    </div>
</template>

<style scoped>

</style>
