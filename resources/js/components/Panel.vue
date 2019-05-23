<template>
    <div class="row left-content-center panel">
        <div class="col-md-3">
            <button type="button" class="btn btn-primary" v-on:click="handleAdd">+</button>
            <button type="button" class="btn btn-primary" v-on:click="handleDelete">-</button>
            <button type="button" class="btn btn-primary" v-on:click="handleEdit">a</button>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success" v-on:click="handleApply">Apply</button>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger" v-on:click="handleReset">Reset</button>
        </div>
    </div>
</template>

<style>
    .panel {
        margin-top: 20px;
    }
</style>

<script>
    import NodeTree from "./NodeTree";

    export default {
        props: {
            treeData: Object,
        },
        components: {
            NodeTree
        },
        methods: {
            handleAdd() {
                this.$emit("event-add");
            },
            handleDelete() {
                this.$emit("event-delete");
            },
            handleEdit() {
                this.$emit("event-edit");
            },
            handleApply() {
                this.$emit("event-apply");
            },
            handleReset() {
                this.$emit("event-reset");
            }
        }
    };
</script>
