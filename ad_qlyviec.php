<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'quantrivien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';

// === PHÂN TRANG ===
$limit = 10; // Số tin mỗi trang
$page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// === TÌM KIẾM & LỌC ===
$tukhoa    = trim($_GET['tukhoa'] ?? '');
$trangthai = $_GET['trangthai'] ?? '';

// Xây dựng điều kiện WHERE
$where = "WHERE 1=1";
$params = [];
$types  = "";

if ($tukhoa !== '') {
    $where .= " AND (j.tieude LIKE ? OR j.tencongty LIKE ?)";
    $search = "%$tukhoa%";
    $params[] = $search;
    $params[] = $search;
    $types   .= "ss";
}

if ($trangthai !== '' && in_array($trangthai, ['choxuly', 'daduyet', 'tuchoi'])) {
    $where .= " AND j.trangthai = ?";
    $params[] = $trangthai;
    $types   .= "s";
}

// Đếm tổng số tin
$count_sql = "SELECT COUNT(*) as total FROM vieclam j $where";
$stmt_count = $conn->prepare($count_sql);
if ($types) $stmt_count->bind_param($types, ...$params);
$stmt_count->execute();
$total = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);

// Lấy dữ liệu trang hiện tại
$sql = "SELECT j.*, u.hoten as nhatuyendung 
        FROM vieclam j 
        JOIN nguoidung u ON j.idnhatuyendung = u.id 
        $where 
        ORDER BY j.ngaydang DESC 
        LIMIT ? OFFSET ?";
$types_limit = $types . "ii";
$params_limit = array_merge($params, [$limit, $offset]);

$stmt = $conn->prepare($sql);
$stmt->bind_param($types_limit, ...$params_limit);
$stmt->execute();
$result = $stmt->get_result();

// Xử lý duyệt / từ chối
if (isset($_GET['daduyet'])) {
    $id = (int)$_GET['daduyet'];
    $stmt_duyet = $conn->prepare("UPDATE vieclam SET trangthai = 'daduyet' WHERE id = ?");
    $stmt_duyet->bind_param("i", $id);
    $stmt_duyet->execute();
    header("Location: ?" . http_build_query(array_merge($_GET, ['daduyet' => null])));
    exit;
}

if (isset($_GET['tuchoi'])) {
    $id = (int)$_GET['tuchoi'];
    $stmt_tuchoi = $conn->prepare("UPDATE vieclam SET trangthai = 'tuchoi' WHERE id = ?");
    $stmt_tuchoi->bind_param("i", $id);
    $stmt_tuchoi->execute();
    header("Location: ?" . http_build_query(array_merge($_GET, ['tuchoi' => null])));
    exit;
}
?>

<?php include 'include_header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Quản lý tin tuyển dụng</h3>
        <a href="ad_trangchu.php" class="btn btn-outline-secondary">Quay lại</a>
    </div>

    <!-- Form tìm kiếm -->
    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-bold">Tìm tiêu đề / công ty</label>
                <input type="text" name="tukhoa" class="form-control" 
                       placeholder="Nhập từ khóa..." 
                       value="<?php echo htmlspecialchars($tukhoa); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="trangthai" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="choxuly" <?php echo $trangthai === 'choxuly' ? 'selected' : ''; ?>>Chờ xử lý</option>
                    <option value="daduyet" <?php echo $trangthai === 'daduyet' ? 'selected' : ''; ?>>Đã duyệt</option>
                    <option value="tuchoi"  <?php echo $trangthai === 'tuchoi'  ? 'selected' : ''; ?>>Từ chối</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
        </div>
    </form>

    <!-- Bảng dữ liệu -->
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Tiêu đề</th>
                        <th>Công ty</th>
                        <th>Mô tả</th>
                        <th>Ngày đăng</th>
                        <th>Trạng thái</th>
                        <th>Duyệt / Từ chối</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($vieclam = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($vieclam['tieude']); ?></td>
                        <td><?php echo htmlspecialchars($vieclam['tencongty']); ?></td>
                        <td><?php 
                            $mota = htmlspecialchars(substr($vieclam['mota'], 0, 120));
                            echo nl2br($mota) . (strlen($vieclam['mota']) > 120 ? '...' : ''); 
                        ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($vieclam['ngaydang'])); ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                echo $vieclam['trangthai'] === 'daduyet' ? 'success' : 
                                     ($vieclam['trangthai'] === 'tuchoi' ? 'danger' : 'warning');
                            ?>">
                                <?php 
                                echo $vieclam['trangthai'] === 'daduyet' ? 'Đã duyệt' : 
                                     ($vieclam['trangthai'] === 'tuchoi' ? 'Từ chối' : 'Chờ xử lý');
                                ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($vieclam['trangthai'] === 'choxuly'): ?>
                                <a href="?daduyet=<?php echo $vieclam['id']; ?>&<?php echo http_build_query(array_diff_key($_GET, ['daduyet'=>''])); ?>" 
                                   class="btn btn-sm btn-success">Duyệt</a>
                                <a href="?tuchoi=<?php echo $vieclam['id']; ?>&<?php echo http_build_query(array_diff_key($_GET, ['tuchoi'=>''])); ?>" 
                                   class="btn btn-sm btn-danger">Từ chối</a>
                            <?php else: ?>
                                <span class="text-muted">Đã xử lý</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="ad_xoatin.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $vieclam['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Bạn chắc chắn muốn xóa tin này?');">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page-1])); ?>">« Trước</a>
                </li>

                <?php
                $range = 2;
                $start = max(1, $page - $range);
                $end   = min($total_pages, $page + $range);

                if ($start > 1): ?>
                    <li class="page-item"><a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>">1</a></li>
                    <?php if ($start > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
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
            Không tìm thấy tin tuyển dụng nào phù hợp.
        </div>
    <?php endif; ?>
</div>

<?php include 'include_footer.php'; ?>

<?php
// Đóng kết nối
if (isset($stmt)) $stmt->close();
if (isset($stmt_count)) $stmt_count->close();
$conn->close();
?>