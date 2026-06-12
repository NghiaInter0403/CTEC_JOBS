<!-- ZALO MENU -->
<div class="zalo-container">
    
    <div class="zalo-list" id="zaloList">
        <a href="https://zalo.me/0333161534" target="_blank" class="zalo-item">
            👨‍💼 Admin
        </a>

        <a href="https://zalo.me/0352755926" target="_blank" class="zalo-item">
            🎧 Hỗ trợ
        </a>
    </div>

    <button class="zalo-main-btn" onclick="toggleZalo()">
        <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo">
    </button>

</div>

<style>
.zalo-container{
    position:fixed;
    right:25px;
    bottom:25px;
    z-index:9999;
}

/* Nút chính */
.zalo-main-btn{
    width:65px;
    height:65px;
    border:none;
    border-radius:50%;
    background:#fff;
    box-shadow:0 6px 18px rgba(0,0,0,.25);
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    animation:zaloPulse 1.8s infinite;
}

.zalo-main-btn img{
    width:42px;
    height:42px;
}

.zalo-main-btn:hover{
    transform:scale(1.1);
}

/* Danh sách tài khoản */
.zalo-list{
    position:absolute;
    bottom:80px;
    right:0;
    display:none;
    flex-direction:column;
    gap:10px;
}

.zalo-list.show{
    display:flex;
}

.zalo-item{
    background:#fff;
    color:#333;
    text-decoration:none;
    padding:10px 15px;
    border-radius:30px;
    box-shadow:0 4px 12px rgba(0,0,0,.15);
    font-size:14px;
    font-weight:600;
    white-space:nowrap;
    transition:.3s;
}

.zalo-item:hover{
    transform:translateX(-5px);
    background:#f5f5f5;
}

/* Hiệu ứng rung nhẹ */
@keyframes zaloPulse{
    0%{transform:scale(1);}
    50%{transform:scale(1.08);}
    100%{transform:scale(1);}
}

@media(max-width:768px){
    .zalo-main-btn{
        width:55px;
        height:55px;
    }

    .zalo-main-btn img{
        width:35px;
        height:35px;
    }

    .zalo-item{
        font-size:13px;
        padding:8px 12px;
    }
}
</style>

<script>
function toggleZalo() {
    document.getElementById('zaloList').classList.toggle('show');
}

// Ẩn menu khi click ra ngoài
document.addEventListener('click', function(e){
    const container = document.querySelector('.zalo-container');

    if(!container.contains(e.target)){
        document.getElementById('zaloList').classList.remove('show');
    }
});
</script>