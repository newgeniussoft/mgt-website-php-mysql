
<div class="container mt-4">
    <h2>User Management</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <td><?php echo $user->id ?></td>
                    <td><?php echo $user->fullname ?></td>
                    <td><?php echo $user->email ?></td>
                    <td><?php echo $user->roles ?></td>
                    <td>
                        <a href="{{ url_admin('users/edit/'.$user->id) }}" 
                           class="btn btn-primary btn-sm">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
