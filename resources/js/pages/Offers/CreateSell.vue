<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Sell Offers',
        href: '/offers/sell',
    },
    {
        title: 'Create',
        href: '/offers/sell/create',
    },
];

const form = useForm({
    metal: '',
    weight: '',
    weight_unit: '',
    description: '',
});

function submit() {
    form.post('/offers/sell');
}
</script>

<template>
    <Head title="Create Sell Offer" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle class="text-xl">Create Sell Offer</CardTitle>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="metal">Metal</Label>
                            <select
                                id="metal"
                                v-model="form.metal"
                                class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <option value="">Select a metal</option>
                                <option value="gold">Gold</option>
                                <option value="silver">Silver</option>
                                <option value="platinum">Platinum</option>
                            </select>
                            <InputError :message="form.errors.metal" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="weight">Weight</Label>
                                <Input
                                    id="weight"
                                    v-model="form.weight"
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    placeholder="Enter weight"
                                />
                                <InputError :message="form.errors.weight" />
                            </div>

                            <div class="space-y-2">
                                <Label for="weight_unit">Unit</Label>
                                <select
                                    id="weight_unit"
                                    v-model="form.weight_unit"
                                    class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="">Select unit</option>
                                    <option value="oz">Ounces (oz)</option>
                                    <option value="gram">Grams</option>
                                </select>
                                <InputError :message="form.errors.weight_unit" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Input
                                id="description"
                                v-model="form.description"
                                type="text"
                                placeholder="Describe what you want to sell"
                            />
                            <InputError :message="form.errors.description" />
                        </div>

                        <div class="flex gap-4">
                            <Button type="submit" :disabled="form.processing">
                                Create Offer
                            </Button>
                            <Button
                                type="button"
                                variant="outline"
                                as="a"
                                href="/offers/sell"
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
