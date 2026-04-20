<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'quantrivien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
include 'zalo.php';
$id_dang_nhap = $_SESSION['id_nguoidung']; // ID của chính bạn

// === XỬ LÝ NÂNG CẤP TÀI KHOẢN (Mới) ===
if (isset($_GET['nangcap'])) {
    $id_nangcap = (int)$_GET['nangcap'];
    // Không cho phép tự nâng cấp chính mình (dù đã ẩn)
    if ($id_nangcap != $id_dang_nhap) {
        $stmt_up = $conn->prepare("UPDATE nguoidung SET vaitro = 'quantrivien' WHERE id = ?");
        $stmt_up->bind_param("i", $id_nangcap);
        $stmt_up->execute();
        $stmt_up->close();
    }
    header("Location: ?" . http_build_query(array_diff_key($_GET, ['nangcap' => 1])));
    exit;
}

// === XỬ LÝ XÓA USER ===
if (isset($_GET['xoa'])) {
    $id_xoa = (int)$_GET['xoa'];
    // Chỉ admin mới có quyền xóa và không được xóa chính mình
    if ($id_xoa != $id_dang_nhap) {
        $stmt_xoa = $conn->prepare("DELETE FROM nguoidung WHERE id = ?");
        $stmt_xoa->bind_param("i", $id_xoa);
        $stmt_xoa->execute();
        $stmt_xoa->close();
    }
    header("Location: ?" . http_build_query(array_diff_key($_GET, ['xoa' => null])));
    exit;
}

// === PHÂN TRANG ===
$limit      = 10;
$page       = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset     = ($page - 1) * $limit;

// === TÌM KIẾM ===
$email_search = trim($_GET['email'] ?? '');
$vaitro_filter = $_GET['vaitro'] ?? '';

// Xây dựng điều kiện WHERE: Hiển thị tất cả TRỪ tài khoản đang đăng nhập
$where = "WHERE id != ?"; 
$params = [$id_dang_nhap];
$types  = "i";

if ($email_search !== '') {
    $where .= " AND email LIKE ?";
    $params[] = "%$email_search%";
    $types   .= "s";
}
if ($vaitro_filter !== '' && in_array($vaitro_filter, ['sinhvien', 'nhatuyendung', 'quantrivien'])) {
    $where .= " AND vaitro = ?";
    $params[] = $vaitro_filter;
    $types   .= "s";
}

// 1. Đếm tổng số bản ghi
$count_sql = "SELECT COUNT(*) as total FROM nguoidung $where";
$stmt_count = $conn->prepare($count_sql);
$stmt_count->bind_param($types, ...$params);
$stmt_count->execute();
$total = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);

// 2. Truy vấn dữ liệu trang hiện tại
$sql = "SELECT * FROM nguoidung $where ORDER BY ngaytao DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$types_limit = $types . "ii";
$params_limit = array_merge($params, [$limit, $offset]);
$stmt->bind_param($types_limit, ...$params_limit);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'include_header.php'; ?>

<div class="container mt-4">
    <a href="ad_trangchu.php" class="btn btn-outline-secondary float-end">Quay lại trang chủ admin</a>
    <h3>Quản lý người dùng</h3>
    <p class="text-muted small">Danh sách hiển thị tất cả người dùng trừ tài khoản của bạn.</p>

    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Tìm theo tên đăng nhập (email)</label>
                <input type="text" name="email" class="form-control" 
                       placeholder="Nhập mã SV hoặc email..." 
                       value="<?php echo htmlspecialchars($email_search); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Vai trò</label>
                <select name="vaitro" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="sinhvien"     <?php if($vaitro_filter=='sinhvien')     echo 'selected'; ?>>Sinh viên</option>
                    <option value="nhatuyendung" <?php if($vaitro_filter=='nhatuyendung') echo 'selected'; ?>>Nhà tuyển dụng</option>
                    <option value="quantrivien"  <?php if($vaitro_filter=='quantrivien')  echo 'selected'; ?>>Quản trị viên khác</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Tên đăng nhập</th>
                        <th>Vai trò</th>
                        <th>Ngày đăng ký</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($nguoidung = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center"><?php echo $nguoidung['id']; ?></td>
                        <td><?php echo htmlspecialchars($nguoidung['hoten']); ?></td>
                        <td><code><?php echo htmlspecialchars($nguoidung['email']); ?></code></td>
                        <td class="text-center">
                            <?php 
                            if ($nguoidung['vaitro'] == 'sinhvien') {
                                echo '<span class="badge bg-success">Sinh viên</span>';
                            } elseif ($nguoidung['vaitro'] == 'nhatuyendung') {
                                echo '<span class="badge bg-primary">Nhà tuyển dụng</span>';
                            } else {
                                echo '<span class="badge bg-danger">Quản trị viên</span>';
                            }
                            ?>
                        </td>
                        <td class="text-center small"><?php echo date('d/m/Y H:i', strtotime($nguoidung['ngaytao'])); ?></td>
                        <td class="text-center">
                            <?php if ($nguoidung['vaitro'] != 'quantrivien'): ?>
                                <a href="?nangcap=<?php echo $nguoidung['id']; ?>&<?php echo http_build_query(array_diff_key($_GET, ['nangcap'=>1])); ?>" 
                                   class="btn btn-sm btn-warning fw-bold" 
                                   onclick="return confirm('Bạn có chắc muốn nâng cấp tài khoản này lên Quản trị viên?')">
                                   Nâng cấp
                                </a>
                            <?php endif; ?>

                            <a href="?xoa=<?php echo $nguoidung['id']; ?>&<?php echo http_build_query(array_diff_key($_GET, ['xoa'=>1])); ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bạn chắc chắn muốn xóa người dùng này?')">Xóa</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page-1])); ?>">« Trước</a>
                </li>
                <?php
                $range = 2; $start = max(1, $page - $range); $end = min($total_pages, $page + $range);
                if ($start > 1): ?>
                    <li class="page-item"><a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>">1</a></li>
                    <?php if ($start > 2): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                    <li class="page-item"><a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $total_pages])); ?>"><?php echo $total_pages; ?></a></li>
                <?php endif; ?>

                <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page+1])); ?>">Sau »</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-info text-center mt-4">
            Không tìm thấy người dùng nào phù hợp với bộ lọc.
        </div>
    <?php endif; ?>
</div>

<?php include 'include_footer.php'; ?>
<?php
if (isset($stmt)) $stmt->close();
if (isset($stmt_count)) $stmt_count->close();
$conn->close();
?>