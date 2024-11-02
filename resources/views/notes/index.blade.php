@extends('layouts.app')

@section('styles')
    <style>
        .div-action {
            gap: 5px;
        }
    </style>
    {{-- this style for toggle button --}}
    <style>
        .wrap-check .switch {
            display: inline-block;
            height: 14px;
            position: relative;
            width: 30px;
            margin: auto;
        }

        .wrap-check .switch input {
            display: none;
        }

        .wrap-check .slider {
            background-color:
                #ccc;
            bottom: 0;
            cursor: pointer;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transition: .4s;
        }

        .wrap-check .slider:before {
            background-color:
                #fff;
            bottom: 1px;
            content: "";
            height: 13px;
            left: 4px;
            position: absolute;
            transition: .4s;
            width: 13px;
        }

        .wrap-check input:checked+.slider {
            background-color:
                #454649;
        }

        .wrap-check input:checked+.slider:before {
            transform: translateX(12px);
        }

        .wrap-check .slider.round {
            border-radius: 34px;
        }

        .wrap-check .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection

@section('content')
    <div id="message-container"></div>
    @include('components.notes_table', ['notes' => $notes])
@endsection

@section('scripts')
    {{-- this for Alerts --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var autoCloseAlert = document.querySelector('.alert');

            if (autoCloseAlert) {
                setTimeout(function() {
                    autoCloseAlert.classList.add('d-none');
                }, 3000); // Adjust the time as needed (3000 milliseconds = 3 seconds)
            }
        });
    </script>

    {{-- other way: this javascript vanilla script for regenerate a new token --}}
    <script>
        function regenerateToken(noteId) {
            fetch(`/notes/${noteId}/regenerate-token`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF token for Laravel
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to regenerate token');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {

                        document.getElementById(`token-${noteId}`).innerText = data.token;
                    } else {
                        alert('Failed to regenerate token.');
                    }
                })
                .catch(error => {
                    alert('Failed to regenerate token.');
                    console.error('Error:', error);
                });
        }
    </script>

    {{-- Refactor the delete button & token regeneration & toggle functionality into a reusable function 
        to make accessable and functional to all pagination page --}}
    <script>
        function initializeNoteFunctionalities() {
            // Token regeneration functionality
            $('.regenerate-token').off('click').on('click', function() {
                var noteId = $(this).data('id');
                var tokenCell = $(this).closest('tr').find('.token');
                var openStatusCell = $(this).closest('tr').find('.openStatus');
                var checkboxToggle = $(this).closest('tr').find('.checkbox-toggle');

                $.ajax({
                    url: '/notes/' + noteId + '/regenerate-token',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            tokenCell.text(response.token);
                            openStatusCell.html(response.is_opened ?
                                '<span class="text-danger"> Opened </span>' :
                                '<span class="text-success"> Not Opened </span>');
                            checkboxToggle.prop('checked', response.is_opened);
                        } else {
                            alert('Failed to regenerate token.');
                        }
                    },
                    error: function() {
                        alert('Error regenerating token.');
                    }
                });
            });

            // Toggle open status functionality
            $('.checkbox-toggle').off('change').on('change', function() {
                var noteId = $(this).data('id');
                var openStatusCell = $(this).closest('tr').find('.openStatus');
                var changeToken = $(this).closest('tr').find('.token');

                $.ajax({
                    url: '/notes/' + noteId + '/toggle-open-status',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the token display based on the open status
                            openStatusCell.html(response.is_opened ?
                                '<span class="text-danger"> Opened </span>' :
                                '<span class="text-success"> Not Opened </span>');
                            // Update the open status text in the table
                            changeToken.html(response.is_opened ?
                                '<del><span class="text-danger" id="token-' + noteId + '">' +
                                response.access_token + '</span></del>' : '<span id="token-' +
                                noteId + '">' + response.access_token + '</span>');

                            // Update the checkbox state if the open status is changed
                            if (response.is_opened) {
                                // Update the checkbox state based on the response
                                $('#checkbox-' + noteId).prop('checked', response.is_opened);
                            }

                        } else {
                            alert('Failed to toggle open status.');
                        }
                    },
                    error: function() {
                        alert('Error toggling open status.');
                        // Reset the checkbox state if the request fails
                        $('#checkbox-' + noteId).prop('checked', !$('#checkbox-' + noteId).prop(
                            'checked'));
                    }
                });
            });

            // // Delete note functionality
            $('.delete-note').on('click', function(e) {
                e.preventDefault();
                var noteId = $(this).data('id');
                var form = $(this).closest('form');
                var actionUrl = form.attr('action');

                if (confirm('Are you sure you want to delete this note?')) {
                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                // Remove the corresponding row from the table
                                $('tr[data-note-id="' + noteId + '"]').remove();

                                $('#message-container').html('<p class="alert alert-danger">' +
                                    response['delete-message'] + '</p>');
                                setTimeout(function() {
                                    $('#message-container').html('');
                                }, 3000);

                            } else {
                                alert('Failed to delete the note.');
                            }
                        },
                        error: function() {
                            alert('Error occurred while deleting the note.');
                        }
                    });
                }
            });
        }
    </script>

    {{-- this jQuery script for stop refreshing the whole page after clicking the next & previous pagenation buttons --}}
    <script>
        $(document).ready(function() {
            // Initial call to activate functionalities on the first load
            initializeNoteFunctionalities();

            // Handle AJAX pagination
            $(document).on('click', '#pagination a', function(event) {
                event.preventDefault(); // Prevent page refresh
                var pageUrl = $(this).attr('href');

                $.ajax({
                    url: pageUrl,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {

                        // Update the h2 count
                        $('#notes-count').text(
                            `All Notes (${response.notes.data.length} Per Page)`)

                        // Replace the table body content with new notes
                        $('#notes-body').html(response.notes.data.map(function(note) {
                            return `
                        <tr data-note-id="${note.id}">
                            <td>${note.title}</td>
                            <td class="openStatus">${note.is_opened ? '<span class="text-danger"> Opened </span>' : '<span class="text-success"> Not Opened </span>'}</td>
                            <td class="d-flex justify-content-around align-items-center">
                                <div class="token">${note.is_opened ? `<span><del class="text-danger del-token" id="token-${note.id}">${note.access_token}</del></span>` : `<span id="token-${note.id}">${note.access_token}</span>`}</div>
                                <div class="button-regenerateToken d-flex justify-content-between align-items-center" style="width:80px;">
                    
                                    <div class="wrap-check">
                                        <label class="switch d-flex align-self-center" for="checkbox-${note.id}">
                                            <input class="checkbox-toggle" type="checkbox" id="checkbox-${note.id}" data-id="${note.id}" ${note.is_opened ? 'checked' : ''} />
                                            <div class="slider round"></div>
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td class="action">
                                <div class="d-flex justify-content-center flex-wrap div-action">
                                    <a href="/notes/${note.id}/edit"><span class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i></span></a>
                                    <a href="/notes/${note.id}" target="_blank"><span class="btn btn-outline-primary btn-sm"><i class="bi bi-eye-fill"></i></span></a>
                                    
                                    <form action="/notes/${note.id}" method="POST" >
                                    @csrf
                                    @method('DELETE')

                                    <a href="#" id="checkbox-${note.id}" class="delete-note"
                                        data-id="${note.id}">

                                        <span class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash-fill"></i>
                                        </span>
                                    </a>
                                </form>
                                        
                                </div>
                            </td>
                        </tr>`;

                        }).join(''));

                        // Replace the pagination links
                        $('#pagination').html(response.links);

                        // Update the URL in the browser without reloading the page
                        window.history.pushState({}, '', pageUrl);

                        // Reinitialize event listeners for the newly loaded content
                        initializeNoteFunctionalities();
                    },
                    error: function() {
                        alert('Error loading the data.');
                    }
                });
            });
        });
    </script>

    {{-- this  jQuery script for re-generate a new token --}}
    {{-- <script>
        $(document).ready(function() {
            // When the button is clicked
            $('.regenerate-token').click(function() {
                var noteId = $(this).data('id');

                // $(this).closest('tr') is for change just specific row 
                // instead $('.token') is will change all the rows in same time when you click on any buttons 
                var tokenCell = $(this).closest('tr').find('.token');
                var openStatusCell = $(this).closest('tr').find('.openStatus');
                var checkboxToggle = $(this).closest('tr').find('.checkbox-toggle'); // Select the checkbox

                // Send an AJAX request to regenerate the token
                $.ajax({
                    url: '/notes/' + noteId + '/regenerate-token',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the token in the table
                            tokenCell.text(response.token);

                            // Update the open status text in the table
                            openStatusCell.html(response.is_opened ?
                                '<span class="text-danger"> Opened </span>' :
                                '<span class="text-success"> Not Opened </span>');
                            // Update the toggle switch based on is_opened status
                            checkboxToggle.prop('checked', response
                                .is_opened); // Change the checkbox state


                        } else {
                            alert('Failed to regenerate token.');
                        }
                    },
                    error: function() {
                        alert('Error regenerating token.');
                    }
                });
            });
        });
    </script> --}}

    {{-- this jQuery script for toggle button to change is_open status --}}
    {{-- <script>
        $(document).ready(function() {
            // When the button is clicked
            $('.checkbox-toggle').on('change', function() {
                var noteId = $(this).data('id');

                // $(this).closest('tr') is for change just specific row 
                // instead $('.token') is will change all the rows in same time when you click on any buttons 
                var openStatusCell = $(this).closest('tr').find('.openStatus');
                var changeToken = $(this).closest('tr').find('.token');

                // Send an AJAX request to toggle the open status
                $.ajax({
                    url: '/notes/' + noteId + '/toggle-open-status',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the open status text in the table
                            openStatusCell.html(response.is_opened ?
                                '<span class="text-danger"> Opened </span>' :
                                '<span class="text-success"> Not Opened </span>');

                            // Update the token display based on the open status
                            changeToken.html(response.is_opened ?
                                '<del><span class="text-danger" id="token-' + noteId +
                                '">' + response.access_token + '</span></del>' :
                                '<span id="token-' + noteId + '">' + response.access_token +
                                '</span>');

                            // Update the checkbox state if the open status is changed
                            if (response.is_opened) {
                                // Update the checkbox state based on the response
                                $('#checkbox-' + noteId).prop('checked', response.is_opened)
                            };

                        } else {
                            alert('Failed to toggle open status.');
                        }
                    },
                    error: function() {
                        alert('Error toggling open status.');
                        // Reset the checkbox state if the request fails
                        $('#checkbox-' + noteId).prop('checked', !$('#checkbox-' + noteId).prop(
                            'checked'));
                    }
                });
            });
        });
    </script> --}}

    {{-- this jQuery script for delete button to delete the row of each notes --}}
    {{-- <script>
        $(document).ready(function() {
            // Event handler for delete button click
            $('.delete-note').on('click', function(e) {
                e.preventDefault(); // Prevent the default action

                var noteId = $(this).data('id'); // Get the note ID from data attribute
                var form = $(this).closest('form'); // Get the closest form element
                var actionUrl = form.attr('action'); // Get the form action (delete URL)

                if (confirm('Are you sure you want to delete this note?')) {
                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {

                            if (response.success) {
                                // Remove the corresponding row from the table
                                $('tr[data-note-id="' + noteId + '"]').remove();

                                $('#message-container').html('<p class="alert alert-danger">' +
                                    response['delete-message'] + '</p>');

                                setTimeout(function() {
                                    $('#message-container').html('');
                                }, 3000);

                            } else {
                                $('#message-container').html(
                                    '<p class="alert alert-danger">Failed to delete the note.</p>'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Error occurred while deleting the note: ' + xhr
                            .responseText);
                        }

                    });
                }
            });

        });
    </script> --}}
@endsection
