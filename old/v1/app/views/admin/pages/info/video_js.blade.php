@import('app/utils/helpers/helper.php')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $("#video-create-form").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url_admin('video') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    refreshVideo();
                    $("#video-create-form").find('input').val('');
                    Swal.fire(
                        'Success!',
                        'Video created successfully.',
                        'success'
                    );
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'Failed to create video.',
                        'error'
                    );
                }
            })
        })

        
    function refreshVideo() {
        const contentVideo = document.getElementById('content_video');
        $.ajax({
            url: "{{ url_admin('video') }}",
            type: "GET",
            success: function(response) {
                contentVideo.innerHTML = response;
                var formVideo = $('.form-video');
                formVideo.find('button[type="button"]').on('click', function() {
                    var id = $(this).closest('.form-video').find('input[name="id"]').val();
                    deleteVideo(id);
                });
                formVideo.on('submit', function(e) {
                    e.preventDefault();
                    updateVideo(this);
                });
            },
            error: function(xhr, status, error) {
                Swal.fire(
                    'Error!',
                    'Failed to load videos.',
                    'error'
                );
            }
        })
    }
    refreshVideo();

    function deleteVideo(id) {
        Swal.fire({
                                title: 'Are you sure?',
                                text: "You won't be able to revert this!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {                                $.ajax({
                                        url: '{{ url_admin("video") }}',
                                        type: 'POST',
                                        data: {
                                            id: id,
                                            video_action: 'delete'
                                        },
                                        success: function(response) {
                                            refreshVideo();

                                            Swal.fire(
                                                'Deleted!',
                                                'Video deleted successfully.',
                                                'success'
                                            );
                                        },
                                        error: function(xhr, status, error) {
                                            Swal.fire(
                                                'Error!',
                                                'Failed to delete video.',
                                                'error'
                                            );
                                        }
                                    })
                                }
                            });
                        
    }
    function updateVideo(form) {
        $.ajax({
            url: '{{ url_admin("video") }}',
            type: 'POST',
            data: $(form).serialize(),
            success: function(response) {
                refreshVideo();
                Swal.fire(
                    'Success!',
                    'Video updated successfully.',
                    'success'
                );
            },
            error: function(xhr, status, error) {
                Swal.fire(
                    'Error!',
                    'Failed to update video.',
                    'error'
                );
            }
        })
    }

    });


</script>