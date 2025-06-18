<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { BreadcrumbItem } from '@/types';


interface User {
    id?: number;
    name: string;
    email: string;
    created_at?: string;
    updated_at?: string;
}

const props = defineProps<{
    user?: User | null;
    isEdit: boolean;
}>();


const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    password: '',
    password_confirmation: '',
});

const pageTitle = computed(() => props.isEdit ? 'Edit User' : 'Create User');
const submitLabel = computed(() => props.isEdit ? 'Update User' : 'Create User');

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'User Management',
        href: '/dashboard/users',
    },
    {
        title: pageTitle.value,
        href: '#',
    },
];

const submit = () => {
    if (props.isEdit && props.user) {
        form.post(`/dashboard/users/${props.user.id}/edit`, {
            onSuccess: () => {
                // Success handled by redirect
            },
        });
    } else {
        form.post('/dashboard/users/create', {
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

        <div class=" p-6">
            <Card>
                <CardHeader>
                    <CardTitle>{{ pageTitle }}</CardTitle>
                    <CardDescription>
                        {{ isEdit ? 'Update user information' : 'Create a new user account' }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name Field -->
                        <div class="space-y-2">
                            <Label for="name">Full Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Enter full name"
                                :class="{ 'border-red-500': form.errors.name }"
                                required
                            />
                            <div v-if="form.errors.name" class="text-sm text-red-600">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <Label for="email">Email Address</Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="Enter email address"
                                :class="{ 'border-red-500': form.errors.email }"
                                required
                            />
                            <div v-if="form.errors.email" class="text-sm text-red-600">
                                {{ form.errors.email }}
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <Label for="password">
                                Password
                                <span v-if="isEdit" class="text-sm text-gray-500">(leave blank to keep current)</span>
                            </Label>
                            <Input
                                id="password"
                                v-model="form.password"
                                type="password"
                                placeholder="Enter password"
                                :class="{ 'border-red-500': form.errors.password }"
                                :required="!isEdit"
                            />
                            <div v-if="form.errors.password" class="text-sm text-red-600">
                                {{ form.errors.password }}
                            </div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="space-y-2">
                            <Label for="password_confirmation">Confirm Password</Label>
                            <Input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                placeholder="Confirm password"
                                :required="!isEdit || form.password.length > 0"
                            />
                        </div>

                        <!-- Form Actions -->
                        <div class="flex gap-4 pt-4">
                            <Button 
                                type="submit" 
                                :disabled="form.processing"
                                class="flex-1"
                            >
                                <span v-if="form.processing">
                                    {{ isEdit ? 'Updating...' : 'Creating...' }}
                                </span>
                                <span v-else>
                                    {{ submitLabel }}
                                </span>
                            </Button>
                            <Button 
                                type="button" 
                                variant="outline" 
                                @click="cancel"
                                :disabled="form.processing"
                            >
                                Cancel
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>