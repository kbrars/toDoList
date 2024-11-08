<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { useToast } from 'vue-toast-notification';

let tasks = ref([]);
let currentPage = ref(1);
let tasksPerPage = ref(5);
let currentTask = ref([]);
let addTaskElment = ref([])
const showModel = ref(false);
const hideModel = ref(true);
const showModelAdd = ref(false);
const hideModelAdd = ref(true);
const showModelDelete = ref(false);
const hideModelDelete = ref(true);
let showBottom = false;
let showTop = false;

const toast = useToast();

onMounted(async () => {
    await getTasks();
});

const getTasks = async () => {
    try {
        const response = await axios.get("/api/tasks");
        tasks.value = response.data.tasks;
    } catch (error) {
        toast.open({
            message: 'Görevler yüklenirken hata oluştu!',
            type: 'error',
            duration: 3000,
        });
    }
};

const totalPages = () => {
    return Math.ceil(tasks.value.length / tasksPerPage.value);
};

const currentTasks = () => {
    const start = (currentPage.value - 1) * tasksPerPage.value;
    const end = start + tasksPerPage.value;
    return tasks.value.slice(start, end);
};

const nextPage = () => {
    if (currentPage.value < totalPages()) {
        currentPage.value++;
    }
};

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const closeModel = () => {
    showModel.value = !hideModel.value;
};

const editTaskButton = (task) => {
    currentTask.value = { ...task };
    showModel.value = !showModel.value;
};

const updateTask = async (currentTask) => {
    try {
        const response = await axios.put(`/api/tasks/${currentTask.id}`, currentTask);
        toast.open({
            message: 'Görev başarıyla güncellendi!',
            type: 'success',
            duration: 3000,
        });
        closeModel();

        const index = tasks.value.findIndex(task => task.id === currentTask.id);
        tasks.value.splice(index, 1);
        tasks.value.unshift(currentTask);
    } catch (error) {
        toast.open({
            message: 'Görev güncellenirken hata oluştu!',
            type: 'error',
            duration: 3000,
        });
    }
};

const closeAddModel = () => {
    showModelAdd.value = !hideModelAdd.value;
};

const addTaskModal = () => {
    addTaskElment.value = {
        title: '',
        context: '',
        date: '',
        due_date: ''
    };
    showModelAdd.value = !showModelAdd.value;
}

const addTask = async () => {
    try {
        const response = await axios.post("/api/tasks", addTaskElment.value);
        toast.open({
            message: 'Görev başarıyla eklendi!',
            type: 'success',
            duration: 3000,
        });

        const newTask = {
            ...addTaskElment.value,
            id: response.data.id // Backend'den dönen ID'yi kullan
        };
        tasks.value.unshift(newTask);
        closeAddModel();
    } catch (error) {
        toast.open({
            message: 'Görev eklenirken hata oluştu!',
            type: 'error',
            duration: 3000,
        });
    }
};

const deleteButton = (task) => {
    currentTask.value = task;
    showModelDelete.value = !showModelDelete.value;
}

const closeDeleteModel = () => {
    showModelDelete.value = !hideModelDelete.value;
};

const deleteTask = async (currentTask) => {
    try {
        await axios.delete(`/api/tasks/${currentTask.id}`);
        toast.open({
            message: 'Görev başarıyla silindi!',
            type: 'success',
            duration: 3000,
        });

        const index = tasks.value.findIndex(task => task.id === currentTask.id);
        tasks.value.splice(index, 1);
        closeDeleteModel();
    } catch (error) {
        toast.open({
            message: 'Görev silinirken hata oluştu!',
            type: 'error',
            duration: 3000,
        });
    }
};
</script>


<template>
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card border-0 disCard" style="min-width: 50%;">
            <div id="bigDiv" class="card-body">
                <div class="container mt-3">
                    <div class="card border-0">
                        <div class="form-check mb-5">

                            <div class="row">

                                <div class="  d-flex justify-content-center align-items-center">
                                    <button type="button" @click="addTaskModal()" class="btn btn-outline-success ">
                                        <i class="bi bi-plus"></i>
                                        Görev Ekle
                                    </button>
                                </div>

                            </div>

                        </div>
                        <!-- Task items -->
                        <div class="d-flex align-items-center mb-3" v-for="(task, index) in currentTasks()" :key="index"
                            style="max-width: 99%;">
                            <div class="card bg-light text-dark flex-grow-1">
                                <div class="card-body">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        {{ task.title }}
                                    </label>
                                </div>
                            </div>
                            <div class="ms-2">
                                <button type="button" class="btn btn-danger edit-btn" @click="deleteButton(task)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="ms-2">
                                <button type="button" class="btn btn-primary edit-btn" @click="editTaskButton(task)">
                                    <i class="bi bi-pencil me-1"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Syfalama -->
                        <div class="pagination-container mt-3 d-flex justify-content-between">
                            <button class="btn btn-primary" @click="previousPage"
                                :disabled="currentPage === 1">Önceki</button>
                            <button class="btn btn-primary" @click="nextPage"
                                :disabled="currentPage === totalPages()">Sonraki</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- The Update Task Modal -->
        <div class="modal fade" id="myModal" :class="{ 'show': showModel, 'd-block': showModel }">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Görev Güncelle</h4>
                        <button type="button" @click="closeModel()" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Başlangıç Tarihi</label>
                            <input id="date" placeholder="dd-mm-yyyy" type="date" class="input form-control"
                                v-model="currentTask.date">
                            <br>
                            <label for="exampleInputEmail1" class="form-label">Bitiş Tarihi</label>
                            <input id="date" placeholder="dd-mm-yyyy" type="date" class="input form-control"
                                v-model="currentTask.due_date">
                            <br>
                            <label for="exampleInputEmail1" class="form-label">Görev Başlığı</label>
                            <input type="text" class="form-control" v-model="currentTask.title" />
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Detaylar</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="5"
                                v-model="currentTask.context"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" @click="updateTask(currentTask)"
                                class="btn btn-outline-success full-width">Güncelle</button>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer d-grid">
                        <button type="button" class="btn btn-danger full-width" @click="closeModel()"
                            data-bs-dismiss="modal">Kapat</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- The Add Task  Modal -->
        <div class="modal fade" id="myModal" :class="{ 'show': showModelAdd, 'd-block': showModelAdd }">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Görev Ekle </h4>
                        <button type="button" @click="closeAddModel()" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Başlangıç Tarihi</label>
                            <input id="date" placeholder="dd-mm-yyyy" type="date" class="input form-control"
                                v-model="addTaskElment.date">
                            <br>
                            <label for="exampleInputEmail1" class="form-label">Bitiş Tarihi</label>
                            <input id="date" placeholder="dd-mm-yyyy" type="date" class="input form-control"
                                v-model="addTaskElment.due_date">
                            <br>
                            <label for="exampleInputEmail1" class="form-label">Görev Başlığı</label>
                            <input type="text" class="form-control" v-model="addTaskElment.title" />
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Detaylar</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="5"
                                v-model="addTaskElment.context"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" @click="addTask()"
                                class="btn btn-outline-success full-width">Ekle</button>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer d-grid">
                        <button type="button" class="btn btn-danger full-width" @click="closeAddModel()"
                            data-bs-dismiss="modal">Kapat</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete  Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1"
            :class="{ 'show': showModelDelete, 'd-block': showModelDelete }" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ currentTask.title }}</h5>
                        <button type="button" @click="closeDeleteModel()" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Kapat"></button>
                    </div>
                    <div class="modal-body">
                        Silmek istediğinize emin misiniz?
                    </div>
                    <div class="modal-footer">

                        <button type="button" @click="deleteTask(currentTask)" class="btn btn-success">Evet</button>
                        <button type="button" @click="closeDeleteModel()" class="btn btn-danger"
                            data-bs-dismiss="modal">Hayır</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
