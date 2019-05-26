<template>
    <li class="node-tree"
        v-bind:class="{deleted: node.is_deleted, active: node.id === selectedItem}"
    >
        <span class="label" v-on:click="triggerClick">{{ node.value }}</span>

        <ul v-if="node.children">
            <node v-for="child in node.children" v-bind:key="child.id" :node="child" :selected-item="selectedItem"
                  v-on:selected="triggerChildClick"
            ></node>
        </ul>
    </li>
</template>

<style lang="scss">
    .node-tree {
        padding: 0;

        .label {
            margin: 0 -7px;
            padding: 0 4px;
            white-space: nowrap;
            user-select: none;
        }

        ul {
            width: 100%;
            margin-top: 0;
        }

        &.deleted > .label{
            background: #b91d19;
            color: #fff;
        }

        &.active > .label {
            background: #5cd08d;
            color: #fff;
        }

        &:not(.deleted) > .label {
            cursor: pointer;
        }

        &:not(.deleted):not(.active) > .label:hover {
            background: #e0ffe6;
        }
    }
</style>

<script>
    export default {
        name: "node",
        props: {
            node: Object,
            selectedItem: Number
        },
        methods: {
            triggerClick() {
                if (!this.node.is_deleted) {
                    this.$emit("selected", {
                        id: this.node.id,
                        value: this.node.value
                    })
                }
            },
            triggerChildClick (item) {
                this.$emit("selected", {
                    id: item.id,
                    value: item.value
                })
            }
        }
    };
</script>
