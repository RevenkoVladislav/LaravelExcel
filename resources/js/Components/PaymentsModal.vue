<script>
export default {
    name: "PaymentsModal",

    props: {
        show: {
            type: Boolean,
            required: true,
        },
        payments: {
            type: Array,
            required: true,
        },
    },

    methods: {
        formatCurrency(value) {
            return new Intl.NumberFormat('ru-RU', {
                style: 'currency',
                currency: 'RUB',
                maximumFractionDigits: 2
            }).format(value);
        },
    }
}
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl w-[400px] p-6">
            <h3 class="text-lg font-semibold mb-4">
                Вложения
            </h3>

            <div class="space-y-2">
                <div
                    v-for="payment in payments"
                    :key="payment.id"
                    class="flex justify-between border-b pb-1"
                >
                    <span>{{ payment.title }}</span>
                    <span class="font-semibold">
                        {{ formatCurrency(payment.value) }}
                    </span>
                </div>
            </div>

            <div class="text-right mt-5">
                <button
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg"
                    @click="$emit('close')"
                >
                    Закрыть
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
