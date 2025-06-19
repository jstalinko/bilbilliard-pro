<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { BreadcrumbItem } from '@/types';
import { Card, CardContent, CardHeader, CardDescription, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectItem, SelectContent, SelectTrigger, SelectValue, SelectLabel, SelectGroup } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
interface Member {
    id: number;
    full_name: string;
    phone: string;
    point: string;
    total_tx: string;
};

const props = defineProps<{
    member: Member | null;
    isEdit: boolean;
}>();


const form = useForm({
    full_name: props?.member?.full_name || '',
    phone: props?.member?.phone || '',
    point: props?.member?.point || 0,
});

const pageTitle = computed(() => props.isEdit ? 'Edit Member' : 'Create Member');
const submitLabel = computed(() => props.isEdit ? 'Update Member' : 'Create Member');

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Members',
        href: '/dashboard/members',
    },
    {
        title: pageTitle.value,
        href: '#',
    },
];

const submit = () => {
    if (props.isEdit && props.member) {

        console.log(props.member);

        form.post(`/dashboard/members/${props.member?.id}/edit`, {
            onSuccess: () => {
                // Success handled by redirect
            },
        });
    } else {
        form.post('/dashboard/members/create', {
            onSuccess: () => {
                form.reset();
            },
        });
    }
};

const cancel = () => {
    window.history.back();
};
</script>
<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head :title="pageTitle" />
        <div class="space-y-6 p-4">

            <div class=" p-6">
                <Card>
                    <CardHeader>
                        <CardTitle>{{ pageTitle }}</CardTitle>
                        <CardDescription>
                            {{ isEdit ? 'Update Member information' : 'Create a new Member' }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Name Field -->
                            <div class="space-y-2">
                                <Label for="name">Member Name</Label>
                                <Input id="name" v-model="form.full_name" type="text" placeholder="Member Name"
                                    :class="{ 'border-red-500': form.errors.full_name }" required />
                                <div v-if="form.errors.full_name" class="text-sm text-red-600">
                                    {{ form.errors.full_name }}
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label for="phone">Phone</Label>
                                <Input id="phone" v-model="form.phone" type="tel" placeholder="08xxxxx"
                                    :class="{ 'border-red-500': form.errors.phone }" required />
                                <div v-if="form.errors.phone" class="text-sm text-red-600">
                                    {{ form.errors.phone }}
                                </div>
                            </div>
                            <div class="flex gap-4 pt-4">
                                <Button type="submit" :disabled="form.processing" class="flex-1">
                                    <span v-if="form.processing">
                                        {{ isEdit ? 'Updating...' : 'Creating...' }}
                                    </span>
                                    <span v-else>
                                        {{ submitLabel }}
                                    </span>
                                </Button>
                                <Button type="button" variant="outline" @click="cancel" :disabled="form.processing">
                                    Cancel
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>