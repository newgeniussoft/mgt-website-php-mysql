@import('app/utils/helpers/helper.php')

<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const contentSocial = document.getElementById('content_social');
                        const socialCreateForm = document.getElementById('social-create-form');
                        $("#social-create-form").submit(function(e) {
                            e.preventDefault();
                            const formData = new FormData(this);
                            $.ajax({
                                url: '{{ url_admin('social-media') }}',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    refresh();
                                    Swal.fire(
                                        'Success!',
                                        'Social media created successfully.',
                                        'success'
                                    );
                                    socialCreateForm.reset();
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to create social media.',
                                        'error'
                                    );
                                }
                            });
                        });

                        function refresh() {

                        $.ajax({
                            url: '{{ url_admin('social-media') }}',
                            type: 'GET',
                            success: function(response) {
                                contentSocial.innerHTML = response;
                                var formSocial = $('.form-social');
                                formSocial.find('button[type="button"]').on('click', function() {
                                    var id = $(this).closest('.form-social').find('input[name="id"]').val();
                                    
                            Swal.fire({
                                title: 'Are you sure?',
                                text: "You won't be able to revert this!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '{{ url_admin("social-media") }}',
                                        type: 'POST',
                                        data: {
                                            id: id,
                                            social_action: 'delete'
                                        },
                                        success: function(response) {
                                            console.log(response);
                                            refresh();
                                            Swal.fire(
                                                'Success!',
                                                'Social media deleted successfully.',
                                                'success'
                                            );
                                        },
                                        error: function(xhr, status, error) {
                                            console.log(error);
                                            Swal.fire(
                                                'Error!',
                                                'Failed to delete social media.',
                                                'error'
                                            );
                                        }
                                    });
                                }
                            });
                                });

                                formSocial.on('submit', function(e) {
                                    e.preventDefault();
                                    updateSocialMedia(this);
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to load social media.',
                                    'error'
                                );
                            }
                        });

                            
                        }

                        
                        function updateSocialMedia(form) {
                            const formData = new FormData(form);
                            $.ajax({
                                url: '{{ url_admin('social-media') }}',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    if (response.success) {
                                        refresh();
                                        Swal.fire(
                                            'Success!', 
                                            'Social media updated successfully.',
                                            'success'
                                        );
                                        form.reset();
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'Failed to update social media.',
                                            'error'
                                        );
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to update social media.',
                                        'error'
                                    );
                                }
                            });   
                        }
                        
                        refresh();
                    });
                </script>