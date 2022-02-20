<template>
    <div class="container">
        <div class="nav_comp">Nav component</div>
        <div class="main_content">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "Layout",
};
</script>

<style>
div.container {
    display: flex;
}
div.nav_comp {
    width: 20em;
    height: 100%;
    background: orange;
}
div.main_content {
    width: 100%;
    margin-left: 3em;
}
</style>
