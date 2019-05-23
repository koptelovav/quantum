<template>
    <li class="node-tree"
        v-bind:class="{deleted: node.is_deleted, active: node.id === selectedItem}"
    >
        <span class="label" v-on:click="triggerClick">{{ node.name }}</span>

        <ul v-if="node.children">
            <node v-for="child in node.children" v-bind:key="child.id" :node="child" :selected-item="selectedItem"
                  v-on:selected="triggerChildClick"
            ></node>
        </ul>
    </li>
</template>

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
                        name: this.node.name
                    })
                }
            },
            triggerChildClick (item) {
                this.$emit("selected", {
                    id: item.id,
                    name: item.name
                })
            }
        }
    };
</script>
