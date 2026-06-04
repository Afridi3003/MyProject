<section class="admin-card">
    <div class="table-head">
        <div class="table-tools"><button>Copy</button><button>Excel</button><button>PDF</button><button>Column visibility⌄</button></div>
        <label>Search: <input type="search"></label>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Serial</th><th>Title</th><th>Published Date</th><th>Sort</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['published_date']); ?></td>
                    <td><?php echo (int)$row['sort_order']; ?></td>
                    <td><?php echo adminStatus((int)$row['is_active']); ?></td>
                    <td class="actions">
                        <a class="edit" href="/admin?section=blogs&mode=edit&id=<?php echo (int)$row['id']; ?>">✎</a>
                        <a class="delete" href="/admin?section=blogs&mode=delete&id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this blog?')">🗑</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
