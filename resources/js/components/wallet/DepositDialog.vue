<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { type LucideIcon, Check, Circle, Dot } from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import { toTypedSchema } from '@vee-validate/zod';
import * as z from 'zod';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Stepper, StepperDescription, StepperItem, StepperSeparator, StepperTitle, StepperTrigger } from '@/components/ui/stepper';
import { toast } from 'vue-sonner';
import axios from 'axios';
import { useHomeStore } from '@/stores/home/useHomeStore';
import { storeToRefs } from 'pinia';

interface Props {
    btnLabel: string;
    btnIcon?: LucideIcon;
}

defineProps<Props>();

const store = useHomeStore();

const { wallet } = storeToRefs(store)

const open = ref<boolean>(false);
const stepIndex = ref(1);

const formKey = ref<number>(0);

watch(open, (val) => {
    if (!val) {
        stepIndex.value = 1;
        formKey.value += 1;
    }
});

const formSchema = [
    z.object({
        balance: z.preprocess((raw) => {
                if (raw == null) {
                    return undefined;
                }
                if (typeof raw === 'number') {
                    return Number.isFinite(raw) ? raw : undefined;
                }
                if (typeof raw === 'string') {
                    const trimmed = raw.trim();
                    return trimmed === '' ? undefined : trimmed;
                }
                return raw;
            },
            z.union([
                z.number({ required_error: 'Por favor insira um valor.' })
                    .refine((n) => Number.isFinite(n), { message: 'Por favor insira um número válido.' }),
                z.string({ required_error: 'Por favor insira um valor.' })
                    .refine((s) => {
                        const n = Number(s);
                        return !Number.isNaN(n) && Number.isFinite(n);
                    }, { message: 'Por favor insira um número válido.' })
                    .transform((s) => Number(s)),
            ])
                .refine((n) => (n as number) > 0, { message: 'O valor a ser depositado precisa ser maior que zero!' }))
    }),
    z.object({}),
];

const currentValidationSchema = computed(() => {
    const schema = formSchema[stepIndex.value - 1];
    return schema ? toTypedSchema(schema) : undefined;
});

const steps = [
    {
        step: 1,
        title: 'Valor',
        description: 'Insira o valor a ser depositado',
    },
    {
        step: 2,
        title: 'Confirmação',
        description: 'Confirme o valor',
    },
];

const onSubmit = async (values: any) => {
    await axios.post(route('transaction.store'), {
        type: 'deposit',
        amount: values.balance,
    }).finally(() => {
        wallet.value.balance = (Number(wallet.value.balance) + Number(values.balance)).toFixed(2) as unknown as number;

        toast('Valor depositado com sucesso!', {
            description: '',
            action: {
                label: 'Ok',
            },
        });
    })

    open.value = false;
}
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <button
                type="button"
                class="flex rounded-md bg-secondary px-4 py-2 text-sm font-medium text-secondary-foreground shadow hover:opacity-90 focus:ring-2 focus:ring-secondary/50 focus:outline-none"
            >
                <component class="mt-[1.5px] mr-2 h-4 w-4" :is="btnIcon" v-if="btnIcon" :size="18" /> {{ btnLabel }}
            </button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-[525px]">
            <DialogHeader>
                <DialogTitle>Depositar</DialogTitle>
                <DialogDescription> Faça um depósito seguro e rápido. Insira o valor desejado e confirme para continuar. </DialogDescription>
            </DialogHeader>

            <Form :key="formKey" v-slot="{ meta, values, validate }" as="" keep-values :validation-schema="currentValidationSchema">
                <Stepper v-slot="{ isNextDisabled, isPrevDisabled, nextStep, prevStep }" v-model="stepIndex" class="block w-full">
                    <form
                        @submit.prevent="
                            () => {
                                validate();
                                if (stepIndex === steps.length && meta.valid) {
                                    onSubmit(values);
                                }
                            }
                        "
                    >
                        <div class="flex-start flex w-full gap-2">
                            <StepperItem
                                v-for="step in steps"
                                :key="step.step"
                                v-slot="{ state }"
                                class="relative flex w-full flex-col items-center justify-center"
                                :step="step.step"
                            >
                                <StepperSeparator
                                    v-if="step.step !== steps[steps.length - 1].step"
                                    class="absolute top-5 right-[calc(-50%+10px)] left-[calc(50%+20px)] block h-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary"
                                />

                                <StepperTrigger as-child>
                                    <Button
                                        :variant="state === 'completed' || state === 'active' ? 'default' : 'outline'"
                                        size="icon"
                                        class="z-10 shrink-0 rounded-full"
                                        :class="[state === 'active' && 'ring-2 ring-ring ring-offset-2 ring-offset-background']"
                                        :disabled="state !== 'completed' && !meta.valid"
                                    >
                                        <Check v-if="state === 'completed'" class="size-5" />
                                        <Circle v-if="state === 'active'" />
                                        <Dot v-if="state === 'inactive'" />
                                    </Button>
                                </StepperTrigger>

                                <div class="mt-5 flex flex-col items-center text-center">
                                    <StepperTitle
                                        :class="[state === 'active' && 'text-primary']"
                                        class="text-sm font-semibold transition lg:text-base"
                                    >
                                        {{ step.title }}
                                    </StepperTitle>
                                    <StepperDescription
                                        :class="[state === 'active' && 'text-primary']"
                                        class="sr-only text-xs text-muted-foreground transition md:not-sr-only lg:text-sm"
                                    >
                                        {{ step.description }}
                                    </StepperDescription>
                                </div>
                            </StepperItem>
                        </div>

                        <div class="mt-4 flex flex-col gap-4">
                            <template v-if="stepIndex === 1">
                                <FormField v-slot="{ componentField }" name="balance">
                                    <FormItem>
                                        <FormLabel>Valor</FormLabel>
                                        <FormControl>
                                            <Input placeholder="R$ 0,00" min="0.01" step="0.01" type="number" v-bind="componentField" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                            </template>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <Button :disabled="isPrevDisabled" variant="outline" size="sm" @click="prevStep()"> Voltar </Button>
                            <div class="flex items-center gap-3">
                                <Button
                                    v-if="stepIndex !== 2"
                                    :type="meta.valid ? 'button' : 'submit'"
                                    :disabled="isNextDisabled"
                                    size="sm"
                                    @click="meta.valid && nextStep()"
                                >
                                    Continuar
                                </Button>
                                <Button v-if="stepIndex === 2" size="sm" type="submit"> Depositar! </Button>
                            </div>
                        </div>
                    </form>
                </Stepper>
            </Form>
        </DialogContent>
    </Dialog>
</template>
