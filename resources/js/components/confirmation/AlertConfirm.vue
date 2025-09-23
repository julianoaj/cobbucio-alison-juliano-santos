<!-- language: vue -->
<!-- file: resources/js/components/confirmation/AlertConfirm.vue -->
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

interface Props {
    btnLabel?: string;
    dialogTitle?: string;
    dialogDescription?: string;
    open?: boolean;
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
                <AlertDialogAction @click="onConfirm">
                    {{ props.btnLabel ?? 'Continuar' }}
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
