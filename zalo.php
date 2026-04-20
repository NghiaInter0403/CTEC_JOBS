<!-- ZALO FLOAT BUTTON -->
<a href="https://zalo.me/0333161534" target="_blank" class="zalo-float">
    <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo">
</a>

<style>
.zalo-float {
    position: fixed;
    bottom: 25px;
    right: 25px; /* góc dưới bên phải */
    width: 65px;
    height: 65px;
    z-index: 9999;
    border-radius: 50%;
    background: #ffffff;
    box-shadow: 0 6px 18px rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.3s;
    animation: zaloPulse 1.8s infinite;
}

.zalo-float img {
    width: 42px;
    height: 42px;
}

/* hover nhìn xịn hơn */
.zalo-float:hover {
    transform: scale(1.15);
}

/* hiệu ứng nhẹ */
@keyframes zaloPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.08); }
    100% { transform: scale(1); }
}

/* mobile tối ưu */
@media (max-width: 768px) {
    .zalo-float {
        width: 55px;
        height: 55px;
        bottom: 20px;
        right: 20px;
    }

    .zalo-float img {
        width: 35px;
        height: 35px;
    }
}
</style>