<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type LucideIcon } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { Form } from '@inertiajs/vue3';

interface Props {
    btnLabel: string;
    btnIcon?: LucideIcon;
}

defineProps<Props>();

const value = ref<number | null>(null);

const isValid = computed((): boolean => {
    return (value.value ?? 0) > 0;
});

function handleSubmit(): void {
    if (!isValid.value) {
        return;
    }


}
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <button
                type="button"
                class="flex rounded-md bg-secondary px-4 py-2 text-sm font-medium text-secondary-foreground shadow hover:opacity-90 focus:ring-2 focus:ring-secondary/50 focus:outline-none"
            >
                <component class="w-4 h-4 mr-2 mt-[1.5px]" :is="btnIcon" v-if="btnIcon" :size="18" /> {{ btnLabel }}
            </button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Depositar</DialogTitle>
                <DialogDescription> Faça um depósito seguro e rápido. Insira o valor desejado e confirme para continuar. </DialogDescription>
            </DialogHeader>

            <Form @submit.prevent="handleSubmit" class="grid gap-4 py-4">
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="amount" class="text-right"> Valor </Label>
                    <Input
                        id="amount"
                        v-model.number="value"
                        type="number"
                        min="0.01"
                        step="0.01"
                        class="col-span-3"
                        aria-invalid="!isValid"
                    />
                </div>

                <div v-if="value !== null && !isValid" class="text-sm text-destructive">
                    Por favor insira um valor maior que 0.
                </div>

                <DialogFooter>
                    <Button type="submit" :disabled="!isValid" :class="{ 'opacity-50 pointer-events-none': !isValid }">
                        Depositar!
                    </Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>
</template>
