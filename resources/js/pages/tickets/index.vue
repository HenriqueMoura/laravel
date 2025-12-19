<script setup lang="ts">
import DetachedLayout from '@/layouts/DetachedLayout.vue';
import ticketsRoutes from '@/routes/tickets';
import { Form, Head, Link, router } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, ref, watch } from 'vue';

interface TicketRecord {
    id?: number;
    title?: string;
    description?: string;
    status?: string;
    category?: {
        id: number;
        name: string;
    };
    creator?: {
        id: number;
        name: string;
        email: string;
    };
    created_at?: string;
}

interface Category {
    id: number;
    name: string;
}

interface Props {
    records: TicketRecord[] | { data: TicketRecord[] };
    categories?: Category[];
    filters?: {
        search?: string;
        status?: string;
        category_id?: string;
    };
}

const props = defineProps<Props>();

const recordsList = computed(() => {
    if (Array.isArray(props.records)) {
        return props.records;
    }
    return props.records?.data || [];
});

const searchQuery = ref(props.filters?.search || '');

let searchTimeout: number;
let isSearching = false;

const performSearch = () => {
    if (isSearching) return;

    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const params: any = {};
        if (searchQuery.value) {
            params.search = searchQuery.value;
        }

        isSearching = true;
        router.get(ticketsRoutes.index(), params, {
            preserveState: true,
            preserveScroll: true,
            only: ['records'],
            onFinish: () => {
                isSearching = false;
            }
        });
    }, 300);
};

// Remover este watch para evitar conflitos

const showModal = ref(false);
const categorySearch = ref('');
const selectedCategoryId = ref<number | string>('');
const creatingCategory = ref(false);
const localCategories = ref<Category[]>([]);
const showCategoryDropdown = ref(false);

const filteredCategories = computed(() => {
    if (!categorySearch.value) {
        return localCategories.value;
    }
    const search = categorySearch.value.toLowerCase();
    return localCategories.value.filter(cat =>
        cat.name.toLowerCase().includes(search)
    );
});

const showCreateCategoryOption = computed(() => {
    if (!categorySearch.value.trim()) {
        return false;
    }
    const search = categorySearch.value.toLowerCase().trim();
    const exists = localCategories.value.some(cat =>
        cat.name.toLowerCase() === search
    );
    return !exists && search.length > 0;
});

const createCategoryFromSearch = async () => {
    if (!categorySearch.value.trim()) {
        return;
    }

    creatingCategory.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch('/api/categories', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({ name: categorySearch.value.trim() }),
        });

        if (response.ok) {
            const newCategory = await response.json();
            localCategories.value.push(newCategory);
            selectedCategoryId.value = newCategory.id;
        } else {
            const error = await response.json().catch(() => ({ message: 'Erro ao criar categoria' }));
            alert(error.message || 'Erro ao criar categoria');
        }
    } catch (error) {
        console.error('Error creating category:', error);
        alert('Erro ao criar categoria');
    } finally {
        creatingCategory.value = false;
    }
};

const selectCategory = (category: Category) => {
    categorySearch.value = category.name;
    selectedCategoryId.value = category.id;
    showCategoryDropdown.value = false;
};

const handleBlur = () => {
    setTimeout(() => {
        showCategoryDropdown.value = false;
    }, 150);
};

watch(() => selectedCategoryId.value, (newId) => {
    if (newId) {
        const category = localCategories.value.find(cat => cat.id === Number(newId));
        if (category) {
            categorySearch.value = category.name;
        }
    }
});

watch(() => categorySearch.value, (newValue) => {
    if (!newValue) {
        selectedCategoryId.value = '';
        showCategoryDropdown.value = false;
        return;
    }

    const category = localCategories.value.find(cat => cat.name.toLowerCase() === newValue.toLowerCase());
    if (category) {
        selectedCategoryId.value = category.id;
        showCategoryDropdown.value = false;
    } else {
        showCategoryDropdown.value = filteredCategories.value.length > 0;
    }
});

onMounted(() => {
    if (props.categories) {
        localCategories.value = [...props.categories];
    }
    if (props.filters?.search && !searchQuery.value) {
        searchQuery.value = props.filters.search;
    }
});

watch(() => props.categories, (newCategories) => {
    if (newCategories) {
        localCategories.value = [...newCategories];
    }
}, { immediate: true });

watch(() => props.filters?.search, (newSearch) => {
    if (newSearch !== searchQuery.value && !isSearching) {
        searchQuery.value = newSearch || '';
    }
});

const getStatusBadgeClass = (status: string | undefined) => {
    if (!status) {
        return 'bg-soft-secondary text-secondary';
    }
    const statusMap: Record<string, string> = {
        'aberto': 'bg-soft-info text-info',
        'fechado': 'bg-soft-secondary text-secondary',
        'em_andamento': 'bg-soft-warning text-warning',
        'resolvido': 'bg-soft-success text-success',
        'closed': 'bg-soft-secondary text-secondary',
        'open': 'bg-soft-info text-info',
        'in_progress': 'bg-soft-warning text-warning',
        'resolved': 'bg-soft-success text-success',
    };
    return statusMap[status.toLowerCase()] || 'bg-soft-secondary text-secondary';
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' });
};

const openModal = () => {
    showModal.value = true;
    nextTick(() => {
        const modalElement = document.getElementById('createTicketModal');
        if (modalElement && (window as any).bootstrap) {
            const modal = new (window as any).bootstrap.Modal(modalElement);
            modal.show();
        }
    });
};

const closeModal = () => {
    const modalElement = document.getElementById('createTicketModal');
    if (modalElement && (window as any).bootstrap) {
        const modal = (window as any).bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
    }
    showModal.value = false;
    categorySearch.value = '';
    selectedCategoryId.value = '';
};

const handleSuccess = () => {
    closeModal();
    router.reload({ only: ['records'] });
};

</script>

<template>

    <Head title="Tickets" />

    <DetachedLayout>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <button type="button" @click="openModal" class="btn btn-primary">
                            <i class="mdi mdi-plus-circle me-1"></i> Add Ticket
                        </button>
                    </div>
                    <h4 class="page-title">Manage Tickets</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_length">
                                    <label class="form-label">
                                        Show
                                        <select class="form-select form-select-sm"
                                            style="display: inline-block; width: auto;">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        entries
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_filter text-end">
                                    <label class="form-label">
                                        Search:
                                        <input type="search" class="form-control form-control-sm"
                                               v-model="searchQuery"
                                               @input="performSearch"
                                               placeholder="Search tickets..." />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tickets-table"
                                class="table table-striped table-bordered dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Requested By</th>
                                        <th>Subject</th>
                                        <th>Assignee</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="record in recordsList" :key="record?.id || Math.random()">
                                        <td>#{{ record?.id || 'N/A' }}</td>
                                        <td>{{ record?.creator?.name || 'N/A' }}</td>
                                        <td>{{ record?.title || 'N/A' }}</td>
                                        <td>-</td>
                                        <td>
                                            <span class="badge bg-soft-warning text-warning">Medium</span>
                                        </td>
                                        <td>
                                            <span :class="['badge', getStatusBadgeClass(record?.status)]">
                                                {{ record?.status || 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ record?.created_at ? formatDate(record.created_at) : 'N/A' }}</td>
                                        <td>
                                            <Link v-if="record?.id" :href="ticketsRoutes.show({ ticket: record.id })"
                                                class="btn btn-xs btn-light">
                                                <i class="mdi mdi-eye"></i>
                                            </Link>
                                            <Link v-if="record?.id" :href="ticketsRoutes.edit({ ticket: record.id })"
                                                class="btn btn-xs btn-light">
                                                <i class="mdi mdi-pencil"></i>
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Ticket Modal -->
        <div class="modal fade" id="createTicketModal" tabindex="-1" aria-labelledby="createTicketModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTicketModalLabel">Create New Ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <Form v-bind="ticketsRoutes.store.form()" @success="handleSuccess" v-slot="{ errors, processing }">
                        <div class="modal-body">
                            <input type="hidden" name="status" value="aberto">

                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required
                                    placeholder="Enter ticket title" :class="{ 'is-invalid': errors.title }">
                                <div v-if="errors.title" class="invalid-feedback d-block">
                                    {{ errors.title }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="category_search" class="form-label">Category <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="category_search" v-model="categorySearch"
                                        placeholder="Search or create category..."
                                        @focus="showCategoryDropdown = categorySearch.length > 0 && filteredCategories.length > 0"
                                        @blur="handleBlur"
                                        autocomplete="off"
                                        :class="{ 'is-invalid': errors.category_id }">

                                    <!-- Custom dropdown -->
                                    <div v-if="showCategoryDropdown"
                                         class="position-absolute w-100 mt-1 bg-white border border-secondary-subtle rounded shadow-sm"
                                         style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                        <div v-for="category in filteredCategories" :key="category.id"
                                             @click="selectCategory(category)"
                                             class="px-3 py-2 cursor-pointer hover-bg-light"
                                             style="cursor: pointer;">
                                            {{ category.name }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Opção para criar nova categoria -->
                                <div v-if="showCreateCategoryOption" class="mt-2">
                                    <div class="alert alert-info d-flex align-items-center justify-content-between">
                                        <span>
                                            <i class="mdi mdi-information-outline me-1"></i>
                                            Category "<strong>{{ categorySearch }}</strong>" not found. Create it?
                                        </span>
                                        <button type="button" class="btn btn-sm btn-primary"
                                            @click="createCategoryFromSearch" :disabled="creatingCategory">
                                            <span v-if="creatingCategory">Creating...</span>
                                            <span v-else>Create</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Select hidden para validação do formulário -->
                                <select class="d-none" id="category_id" name="category_id" required
                                    v-model="selectedCategoryId">
                                    <option value=""></option>
                                    <option v-for="category in localCategories" :key="category.id"
                                        :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <div v-if="errors.category_id" class="invalid-feedback d-block">
                                    {{ errors.category_id }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required
                                    placeholder="Enter ticket description"
                                    :class="{ 'is-invalid': errors.description }"></textarea>
                                <div v-if="errors.description" class="invalid-feedback d-block">
                                    {{ errors.description }}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                @click="closeModal">Cancel</button>
                            <button type="submit" class="btn btn-primary" :disabled="processing">
                                <span v-if="processing">Creating...</span>
                                <span v-else>Create Ticket</span>
                            </button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </DetachedLayout>
</template>
