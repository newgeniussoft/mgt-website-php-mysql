@import('app/utils/helpers/helper.php')
@include(admin.partials.head)
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
        @include(admin.partials.header)
            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
                <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                    @include(admin.partials.sidebar)
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_content" class="app-content  px-lg-3 ">
                            <div id="kt_app_content_container" class="app-container  container-fluid ">
                            <div class="container mt-4">
    <h2>Edit User Profile</h2>
    <form action="{{ url_admin('users/edit/'.$user->id) }}" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="fullname" class="form-label">Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname" 
                   value="{{ $user->fullname }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="roles">
                <option value="user" {{ $user->roles === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $user->roles === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>


        <div class="mb-3">
            <label for="password" class="form-label">Password <small>(leave blank to keep unchanged)</small></label>
            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="/admin/users" class="btn btn-secondary">Cancel</a>
    </form>
</div>
                            </div>
                        </div>
                    </div>
@include(admin.partials.footer)
<?php if(isset($success)): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success...',
        text: 'User updated successfully',
    });
</script>
<?php endif; ?>


