<section class="admin-card">
    <div class="table-head">
        <div class="table-tools"><button>Copy</button><button>Excel</button><button>PDF</button><button>Column visibility⌄</button></div>
        <label>Search: <input type="search"></label>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Serial</th><th>Heading</th><th>Short Content</th><th>Image</th><th>Sort</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($row['heading']); ?></td>
                    <td><?php echo htmlspecialchars($row['short_content']); ?></td>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img class="mini-logo" src="<?php echo htmlspecialchars($row['image']); ?>" alt="">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?php echo (int)$row['sort_order']; ?></td>
                    <td><?php echo adminStatus((int)$row['is_active']); ?></td>
                    <td class="actions">
                        <a class="edit" href="/admin?section=<?php echo urlencode($section); ?>&mode=edit&id=<?php echo (int)$row['id']; ?>">✎</a>
                        <a class="delete" href="/admin?section=<?php echo urlencode($section); ?>&mode=delete&id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this item?')">🗑</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
