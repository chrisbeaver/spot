<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ChevronDown } from 'lucide-vue-next';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const isOpen = ref(false);

interface Filters {
    metal: string;
    weight: string;
    gtlt: string;
    units: string;
    orderby: string;
    direction: string;
    keywords: string;
}

const props = defineProps<{
    filters: Filters;
    baseUrl: string;
}>();

const search = ref({
    metal: props.filters.metal,
    weight: props.filters.weight,
    gtlt: props.filters.gtlt,
    units: props.filters.units,
    orderby: props.filters.orderby,
    direction: props.filters.direction,
    keywords: props.filters.keywords,
});

function submitSearch() {
    const params: Record<string, string> = {};
    if (search.value.metal) params.metal = search.value.metal;
    if (search.value.weight) params.weight = search.value.weight;
    if (search.value.gtlt) params.gtlt = search.value.gtlt;
    if (search.value.units) params.units = search.value.units;
    if (search.value.orderby) params.orderby = search.value.orderby;
    if (search.value.direction) params.direction = search.value.direction;
    if (search.value.keywords) params.keywords = search.value.keywords;

    router.get(props.baseUrl, params, { preserveState: true });
}

function clearSearch() {
    search.value = {
        metal: '',
        weight: '',
        gtlt: '',
        units: '',
        orderby: '',
        direction: '',
        keywords: '',
    };
    router.get(props.baseUrl);
}
</script>

<template>
    <Collapsible v-model:open="isOpen">
        <Card>
            <CollapsibleTrigger as-child>
                <CardHeader class="cursor-pointer select-none hover:bg-muted/50 transition-colors">
                    <div class="flex items-center justify-between">
                        <CardTitle class="text-lg">Search Filters</CardTitle>
                        <ChevronDown 
                            class="h-5 w-5 text-muted-foreground transition-transform duration-200"
                            :class="{ 'rotate-180': isOpen }"
                        />
                    </div>
                </CardHeader>
            </CollapsibleTrigger>
            <CollapsibleContent>
                <CardContent>
                    <form @submit.prevent="submitSearch" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            <div class="space-y-2">
                                <Label for="metal">Metal</Label>
                                <select
                                    id="metal"
                                    v-model="search.metal"
                                    class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="">All metals</option>
                                    <option value="gold">Gold</option>
                                    <option value="silver">Silver</option>
                                    <option value="platinum">Platinum</option>
                                </select>
                            </div>

                            <!-- Weight Filter Group -->
                            <div class="space-y-2 rounded-md border p-3 md:col-span-2 lg:col-span-1">
                                <Label>Weight Filter</Label>
                                <div class="flex gap-2">
                                    <select
                                        id="gtlt"
                                        v-model="search.gtlt"
                                        class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-20 rounded-md border px-2 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <option value="">--</option>
                                        <option value="gte">≥</option>
                                        <option value="lte">≤</option>
                                    </select>
                                    <Input
                                        id="weight"
                                        v-model="search.weight"
                                        type="number"
                                        step="0.01"
                                        min="0.01"
                                        placeholder="Weight"
                                        class="flex-1"
                                    />
                                    <select
                                        id="units"
                                        v-model="search.units"
                                        class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-24 rounded-md border px-2 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <option value="">Unit</option>
                                        <option value="oz">oz</option>
                                        <option value="gram">gram</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="keywords">Keywords</Label>
                                <Input
                                    id="keywords"
                                    v-model="search.keywords"
                                    type="text"
                                    placeholder="Search description..."
                                />
                            </div>

                            <div class="space-y-2">
                                <Label for="orderby">Order By</Label>
                                <select
                                    id="orderby"
                                    v-model="search.orderby"
                                    class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="">Default</option>
                                    <option value="id">ID</option>
                                    <option value="metal">Metal</option>
                                    <option value="weight">Weight</option>
                                    <option value="description">Description</option>
                                    <option value="created_at">Created Date</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <Label for="direction">Direction</Label>
                                <select
                                    id="direction"
                                    v-model="search.direction"
                                    class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="">Default</option>
                                    <option value="asc">Ascending</option>
                                    <option value="desc">Descending</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <Button type="submit">Search</Button>
                            <Button type="button" variant="outline" @click="clearSearch">
                                Clear
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </CollapsibleContent>
        </Card>
    </Collapsible>
</template>
