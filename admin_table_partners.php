<section class="admin-card">
    <div class="table-head">
        <div class="table-tools"><button>Copy</button><button>Excel</button><button>PDF</button></div>
        <label>Search: <input type="search"></label>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Serial</th><th>Type</th><th>Name</th><th>Logo</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($row['partner_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><img class="mini-logo" src="<?php echo htmlspecialchars($row['logo']); ?>" alt=""></td>
                    <td><?php echo adminStatus((int)$row['is_active']); ?></td>
                    <td class="actions">
                        <a class="edit" href="/admin?section=partners&mode=edit&id=<?php echo (int)$row['id']; ?>">✎</a>
                        <a class="delete" href="/admin?section=partners&mode=delete&id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this partner?')">🗑</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
