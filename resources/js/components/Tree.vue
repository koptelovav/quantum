<template>
    <div class="tree">
        <ul class="tree-list">
            <node-tree
                :node="treeData"
                v-on:selected="handleClick"
                :selected-item="selectedItem"
            ></node-tree>
        </ul>
    </div>
</template>

<style lang="scss">
    .tree > ul.tree-list:only-child > li:before {
        border: 0;
    }

    .tree-list, .tree-list ul {
        list-style: none;
        margin: 0;
        padding: 2px 5px;
    }

    .tree-list {
        ul {
            margin-left: 7px;
        }

        li {
            margin: 0;
            padding: 0 7px;
            line-height: 20px;
            border-left: 1px solid rgb(230, 230, 230);

            &:last-child {
                border-left: none;
            }

            &:before {
                position: relative;
                top: -0.3em;
                height: 1em;
                width: 12px;
                color: white;
                border-bottom: 1px solid rgb(230, 230, 230);
                content: "";
                display: inline-block;
                left: -7px;
                z-index: -1;
            }

            &:last-child:before {
                border-left: 1px solid rgb(230, 230, 230);
            }
        }
    }
</style>

<script>
    import NodeTree from "./NodeTree";

    export default {
        props: {
            treeData: Object,
            selectedItem: Number
        },
        components: {
            NodeTree
        },
        methods: {
            handleClick(item) {
                this.$emit("selected", item);
            }
        }
    };
</script>
