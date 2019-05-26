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

<style>
    .tree-list ul {
        padding-left: 16px;
        margin: 6px 0;
    }

    .tree-view {
        min-height: 60vh;
        border-left: 1px solid #dfdfdf;
        border-right: 1px solid #dfdfdf;
        border-bottom: 1px solid #dfdfdf;
    }
    .node-tree {
        padding: 0;
    }

    .node-tree .label {
        margin: 0 -7px;
        padding: 0 4px;
        white-space: nowrap;
        user-select: none;
    }

    .node-tree ul {
        width: 100%;
        margin-top: 0;
    }

    .node-tree:not(.deleted) > .label {
        cursor: pointer;
    }

    .node-tree:not(.deleted):not(.active) > .label:hover {
        background: #e0ffe6;
    }

    .node-tree.deleted > .label{
        background: #b91d19;
        color: #fff;
    }

    .node-tree.active > .label {
        background: #5cd08d;
        color: #fff;
    }

    ul.tree-list, ul.tree-list ul {
        list-style: none;
        margin: 0;
        padding: 5px;
    }
    ul.tree-list ul {
        margin-left: 10px;
    }
    ul.tree-list li {
        margin: 0;
        padding: 0 7px;
        line-height: 20px;
        border-left:1px solid rgb(230,230,230);
    }
    ul.tree-list li:last-child {
        border-left:none;
    }
    ul.tree-list li:before {
        position:relative;
        top:-0.3em;
        height:1em;
        width:12px;
        color:white;
        border-bottom:1px solid rgb(230,230,230);
        content:"";
        display:inline-block;
        left:-7px;
    }
    ul.tree-list li:last-child:before {
        border-left:1px solid rgb(230,230,230);
    }

    .tree > ul.tree-list:only-child > li:before{
        border: 0;
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
            handleClick (item) {
                this.$emit("selected", item);
            }
        }
    };
</script>
