<section class="admin-card">
    <div class="table-head">
        <div class="table-tools"><button>Copy</button><button>Excel</button><button>PDF</button><button>Column visibility⌄</button></div>
        <label>Search: <input type="search"></label>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Serial</th><th>Quote</th><th>Name</th><th>Company</th><th>Sort</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($row['quote']); ?></td>
                    <td><?php echo htmlspecialchars($row['person_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['company']); ?></td>
                    <td><?php echo (int)$row['sort_order']; ?></td>
                    <td><?php echo adminStatus((int)$row['is_active']); ?></td>
                    <td class="actions">
                        <a class="edit" href="/admin?section=testimonials&mode=edit&id=<?php echo (int)$row['id']; ?>">✎</a>
                        <a class="delete" href="/admin?section=testimonials&mode=delete&id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this testimonial?')">🗑</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
