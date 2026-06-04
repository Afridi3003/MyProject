<section class="admin-card">
    <div class="table-head">
        <div class="table-tools"><button>Copy</button><button>Excel</button><button>PDF</button><button>Column visibility⌄</button></div>
        <label>Search: <input type="search"></label>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Serial</th><th>Name</th><th>Email</th><th>Phone</th><th>Message</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td class="actions">
                        <a class="delete" href="/admin?section=enquiries&mode=delete&id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this enquiry?')">🗑</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
