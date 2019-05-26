<template>
    <div class="container">
        <div class="alert-area">
            <alert v-if="alertMessage"
                   v-on:close="closeAlert"
                   :is-error="isError"
            >
                {{ alertMessage }}
            </alert>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">Local cache</div>
                </div>
                <div class="tree-view" v-if="cacheData">
                    <tree v-for="branch in cacheData"
                          v-bind:key="branch.id"
                          v-on:selected="handleSelectedCache"
                          :tree-data="branch"
                          :selected-item="selectedCacheItem ?selectedCacheItem.id : 0"
                    ></tree>
                </div>
                <div v-if="selectedCacheItem">
                    Selected node ID: {{ selectedCacheItem.id }} <br/>
                    <div v-if="editMode">
                        Edit value: <input type="text" v-model="selectedCacheItem.value"/>
                        <button type="button" class="btn btn-primary btn-sm" v-on:click="updateName">Apply</button>
                    </div>
                </div>
            </div>
            <div class="col-md-2 loader">
                <button type="button" class="btn btn-primary" v-on:click="getFromDb"><<<</button>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">Database emulator</div>
                </div>
                <div class="tree-view" v-if="dbData">
                    <tree v-for="branch in dbData"
                          v-bind:key="branch.id"
                          v-on:selected="handleSelectedDb"
                          :tree-data="branch"
                          :selected-item="selectedDbItem ? selectedDbItem.id : 0"
                    ></tree>
                </div>
                <div v-if="selectedDbItem">
                    Selected node ID: {{ selectedDbItem.id }}
                </div>
            </div>
        </div>
        <panel
            v-on:event-add="handleAdd"
            v-on:event-delete="handleDelete"
            v-on:event-edit="handleEdit"
            v-on:event-apply="handleApply"
            v-on:event-reset="handleReset"
        ></panel>
    </div>
</template>

<style lang="scss">
    .alert-area {
        height: 55px;
    }

    .loader {
        text-align: center;
        margin-top: 30vh;
    }

    .tree-view {
        min-height: 60vh;
        border-left: 1px solid #dfdfdf;
        border-right: 1px solid #dfdfdf;
        border-bottom: 1px solid #dfdfdf;
    }
</style>

<script>
    import Tree from "./Tree";
    import Panel from "./Panel";
    import Alert from "./Alert";

    export default {
        mounted() {
            this.updateCacheTree();
            this.updateDbTree();
        },

        data: () => ({
            cacheData: null,
            dbData: null,
            selectedCacheItem: null,
            selectedDbItem: null,
            editMode: true,

            alertMessage: null,
            isError: false
        }),
        methods: {
            updateCacheTree() {
                const self = this;
                axios.get('/api/cache').then(response => this.cacheData = response.data)
                    .catch(error => {
                        self.showMessage(error.response.data.message, true);
                    });
            },
            updateDbTree() {
                const self = this;
                axios.get('/api/db').then(response => this.dbData = response.data)
                    .catch(error => {
                        self.showMessage(error.response.data.message, true);
                    });
            },
            getFromDb() {
                const self = this;
                if (this.selectedDbItem) {
                    axios.get('/api/cache/' + (this.selectedDbItem.id))
                        .then(response => {
                            this.cacheData = response.data;
                            self.showMessage('Node #' + self.selectedDbItem.id + ' loaded from DB');
                        })
                        .catch(error => {
                            self.showMessage(error.response.data.message, true);
                        });
                }
            },
            handleSelectedCache(item) {
                this.selectedCacheItem = item;
                this.editMode = false;
            },
            handleSelectedDb(item) {
                this.selectedDbItem = item;
            },
            handleAdd() {
                const self = this;
                if (this.selectedCacheItem) {
                    axios.post('/api/cache/' + (this.selectedCacheItem.id))
                        .then(response => {
                            this.cacheData = response.data;
                            self.showMessage('Created a new child node for node #' + self.selectedCacheItem.id);
                        })
                        .catch(error => {
                            self.showMessage(error.response.data.message, true);
                        });
                }
            },
            handleDelete() {
                const self = this;
                if (this.selectedCacheItem) {
                    axios.delete('/api/cache/' + (this.selectedCacheItem.id))
                        .then(response => {
                            self.showMessage('Node #' + self.selectedCacheItem.id + ' marked as deleted');
                            self.cacheData = response.data;
                            self.selectedCacheItem.is_deleted = true;
                            self.selectedCacheItem = null;
                        })
                        .catch(error => {
                            self.showMessage(error.response.data.message, true);
                        });
                }
            },
            handleEdit() {
                this.editMode = true;
            },
            handleApply() {
                const self = this;
                axios.put('/api/cache').then(() => {
                    self.updateCacheTree();
                    self.updateDbTree();
                    self.editMode = false;
                    self.selectedCacheItem = null;
                    self.selectedDbItem = null;
                    self.showMessage('Cache successfully saved');
                }).catch(error => {
                    self.showMessage(error.response.data.message, true);
                })

            },
            handleReset() {
                const self = this;
                axios.post('/api/reset').then(function () {
                    self.updateCacheTree();
                    self.updateDbTree();
                    self.editMode = false;
                    self.selectedCacheItem = null;
                    self.selectedDbItem = null;
                    self.showMessage('Application successfully reset');
                }).catch(error => {
                    self.showMessage(error.response.data.message, true);
                })
            },
            updateName() {
                const self = this;
                axios.put('/api/cache/' + (this.selectedCacheItem.id), {
                    value: this.selectedCacheItem.value
                }).then(response => {
                    self.cacheData = response.data;
                    self.editMode = false;
                    self.showMessage('Node #' + self.selectedCacheItem.id + ' updated');
                }).catch(error => {
                    self.showMessage(error.response.data.message, true);
                })
            },
            showMessage(message, isError) {
                this.alertMessage = message;
                this.isError = !!isError;
            },
            closeAlert() {
                this.alertMessage = null;
            },
        },
        components: {
            Alert,
            Tree,
            Panel
        }
    }
</script>
