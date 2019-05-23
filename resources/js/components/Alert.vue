<template>
    <div v-bind:class="[isError ? errorClass : successClass]"
         class="alert alert-dismissible fade show"
         role="alert"
    >
        <slot></slot>
        <button type="button" class="close" v-on:click="close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<style>

</style>

<script>
    export default {
        data: function () {
            return {
                errorClass: 'alert-danger',
                successClass: 'alert-success',
            }
        },
        props: {
            isError: Boolean
        },
        methods: {
            close() {
                this.$emit('close');
            }
        }
    };
</script>
