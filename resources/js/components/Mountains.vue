<template>
    <div class="mountains">
        <div class="land-1"></div>
        <div class="land-2"></div>
        <div class="land-3"></div>
    </div>
</template>

<style>
@import "@/css/app.css";

.mountains {
    position: fixed;
    height: 80px;
    width: 100%;
    bottom: 0;
    left: 0;
    z-index: -1;
}

.mountains > [class^='mountain'],
.mountains > [class^='mountain']:after,
.mountains > [class^='mountain']:before,
.mountains > [class^='land'],
.mountains > [class^='land']:after,
.mountains > [class^='land']:before {
    background: #110124;
    box-shadow: 0 0 50px 5px rgba(255, 255, 255, 0.2);
    position: absolute;
    bottom: 0;
}

.mountain-2 {
    width: 60px;
    height: 60px;
    bottom: -20px !important;
    left: -10px;
    transform: rotate(45deg);
    border-top-left-radius: 10px;
}

.mountain-1 {
    width: 100px;
    height: 100px;
    bottom: -40px !important;
    left: 10px;
    transform: rotate(45deg);
    border-top-left-radius: 10px;
}

.land-1 {
    width: 30%;
    height: 20px;
    border-top-right-radius: 100%;
}

.land-2 {
    width: 60%;
    height: 15px;
    left: 30%;
    border-top-left-radius: 200%;
    border-top-right-radius: 200%;
}

.land-3 {
    width: 20%;
    height: 20px;
    left: 80%;
    border-top-left-radius: 100%;
}
</style>
