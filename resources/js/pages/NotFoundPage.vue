<template>
    <div class="flex-center position-ref full-height">
        <div class="code">404</div>
        <div class="message" style="padding: 10px;">Not Found</div>
    </div>
</template>

<style scoped>
.full-height {
    height: 100vh;
}

.flex-center {
    align-items: center;
    display: flex;
    justify-content: center;
}

.position-ref {
    position: relative;
}

.code {
    border-right: 2px solid;
    font-size: 26px;
    padding: 0 15px 0 15px;
    text-align: center;
}

.message {
    font-size: 18px;
    text-align: center;
}

.report-button {
    margin-left: 10px;
    background: #25A6F7;
    color: white;
    padding: 10px 15px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid #1D87CE;
    box-shadow: 0 1px 1px rgba(0,0,0, .12);
}
</style>
