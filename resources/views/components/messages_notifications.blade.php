<style>
    /* Message Icon Styles */
    .nav-message-icon {
        position: relative;
        font-size: 1.5rem;
        color: #fff;
        /* width: 10px; */
        /* White to contrast the bg-danger */
    }

    .badge-number {
        position: absolute;
        top: -3px;
        /* Move badge slightly above the icon */
        right: -10px;
        /* Position badge to the right */
        padding: 5px 7px;
        font-size: 0.75rem;
        border-radius: 50%;
        color: #fff;
    }

    /* Dropdown Menu Styling */
    .messages {
        width: 320px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 15px;
    }

    .messages .dropdown-header {
        font-size: 1rem;
        font-weight: bold;
        color: #333;
    }

    .messages .dropdown-divider {
        margin: 10px 0;
    }

    /* Message Item Styling */
    .message-item {
        display: flex;
        align-items: center;
        padding: 10px;
        transition: background-color 0.3s ease;
    }

    .message-item:hover {
        background-color: #f1f1f1;
        border-radius: 8px;
    }

    .message-avatar {
        width: 50px;
        height: 50px;
        margin-right: 15px;
    }

    .message-details h4 {
        font-size: 1rem;
        margin: 0;
        color: #333;
    }

    .message-details p {
        font-size: 0.9rem;
        margin: 4px 0;
        color: #777;
    }

    .time-ago {
        font-size: 0.75rem;
        color: #aaa;
    }

    /* Dropdown Footer */
    .dropdown-footer {
        text-align: center;
        padding-top: 10px;
    }

    .dropdown-footer a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s ease;
    }

    .dropdown-footer a:hover {
        color: #0056b3;
    }

    /* Remove the default dropdown arrow */
    .dropdown-toggle::after {
        display: none;
    }
</style>

<li class="nav-item dropdown " style="list-style-type: none; padding-right: 10px;" >
    <!-- Message Icon (Bootstrap Icon or FontAwesome) -->
    <a class="nav-link nav-message-icon d-flex align-items-center" href="#" id="messageDropdown"
        data-bs-toggle="dropdown" aria-expanded="false">
        <!-- Message Icon -->
        <i class="bi bi-envelope text-black "></i>

        <!-- Unread Notification Badge -->
        @if (auth()->user()->unreadNotifications->count() > 0)
            <span class="badge bg-danger badge-number">{{ auth()->user()->unreadNotifications->count() }}</span>
        @endif
    </a>

    <!-- Dropdown Menu for Messages -->
    <ul class="dropdown-menu dropdown-menu-end messages" aria-labelledby="messageDropdown">
        <li class="dropdown-header">
            <!-- Display unread notifications count -->
            @if (auth()->user()->unreadNotifications->count() > 0)
                You have {{ auth()->user()->unreadNotifications->count() }} new messages
            @else
               <span > Thier is no Notes sent </span>
            @endif

            {{-- <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a> --}}
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        {{-- div scrollable --}}
        <div class="overflow-auto" style="max-height: 300px;">

            @foreach (auth()->user()->unreadNotifications as $notification)
                <li class="message-item">

                    <!-- Form for marking notification as read -->
                    {{-- <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST"
                        id="markAsReadForm-{{ $notification->id }}" style="display: none;">
                        @csrf
                    </form>

                     <a href=""
                        onclick="event.preventDefault(); document.getElementById('markAsReadForm-{{ $notification->id }}').submit();"> --}}
                        <a href="{{ route('notifications.markAsRead', $notification->id) }}">
                            {{-- <a href="{{ url('notifications/read/' . $notification->id ) }}"> --}}
                                
                        <img src="assets/img/messages-1.jpg" alt="User Image" class="rounded-circle message-avatar">
                        <div class="message-details">
                            <h4>{{ $notification->data['sender'] }}</h4>
                            <p>{{ $notification->data['note_title'] }}</p>
                            <span class="time-ago">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </a>

                </li>
            @endforeach
            
        </div>

        <li>
            <hr class="dropdown-divider">
        </li>

        <li class="dropdown-footer">
            <a href="{{ route('recieved.notes') }}">Show all messages</a>
        </li>
    </ul>
</li>
