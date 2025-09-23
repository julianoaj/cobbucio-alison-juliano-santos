<script setup lang="ts">
import { computed } from 'vue';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import { Loader } from 'lucide-vue-next';

interface Props {
    btnLabel?: string;
    dialogTitle?: string;
    dialogDescription?: string;
    open?: boolean;
    loading?: boolean;
}

const props = defineProps<Props>();

const emits = defineEmits<{
    (e: 'confirm'): void;
    (e: 'update:open', v: boolean): void;
}>();

const modelOpen = computed<boolean>({
    get: () => !!props.open,
    set: (v: boolean) => emits('update:open', v),
});

const onConfirm = (): void => {
    emits('confirm');
    emits('update:open', false);
};
</script>

<template>
    <AlertDialog v-model:open="modelOpen">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ props.dialogTitle ?? 'Confirm' }}</AlertDialogTitle>
                <AlertDialogDescription>
                    {{ props.dialogDescription ?? 'Are you sure?' }}
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel @click="modelOpen = false">Cancelar</AlertDialogCancel>
                <AlertDialogAction v-if="!loading" @click="onConfirm">
                    {{ props.btnLabel ?? 'Continuar' }}
                </AlertDialogAction>
                <AlertDialogAction v-else disabled>
                    <Loader class="w-4 h-4 mr-2 animate-spin" />
                    Processando...
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
