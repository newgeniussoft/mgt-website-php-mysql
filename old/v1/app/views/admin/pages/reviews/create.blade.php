@include(admin.partials.head)
<div class="container mt-5">
    <h1>Add Review</h1>
    <form method="POST" action="<?= url_admin('reviews/store') ?>">
        <div class="mb-3">
            <label for="rating" class="form-label">Rating</label>
            <input type="number" min="1" max="5" class="form-control" id="rating" name="rating" required>
        </div>
        <div class="mb-3">
            <label for="name_user" class="form-label">Name</label>
            <input type="text" class="form-control" id="name_user" name="name_user" required>
        </div>
        <div class="mb-3">
            <label for="email_user" class="form-label">Email</label>
            <input type="email" class="form-control" id="email_user" name="email_user" required>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" required></textarea>
        </div>
        <div class="mb-3">
            <label for="pending" class="form-label">Pending</label>
            <select class="form-control" id="pending" name="pending">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="<?= url_admin('reviews') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
