@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center flex-column">

        <h3 class="bg-info text-light text-center rounded  mt-5 p-2">{{ $note->title }}</h3>

        <div class="m-4 ">
            <p class="">{!! $note->content !!}</p>
        </div>
        @can('show', $note)
            <a class="btn btn-secondary btn-sm align-self-center" href="#" data-bs-toggle="modal"
                data-bs-target="#sendNoteModal" style=" font-size:20px; vertical-align: middle;">
                <i class="bi bi-send"></i>Send</a>
        @endcan
    </div>


    <!-- Modal Structure -->
    <div class="modal fade" id="sendNoteModal" tabindex="-1" aria-labelledby="sendNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendNoteModalLabel">Choose a User to Send the Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                </div>
                <form action="{{ route('send.notes', ['id' => $note->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">


                        {{-- @method('POST') --}}

                        <!-- User List (This could be dynamically generated) -->
                        <ul class="list-group">
                            @foreach ($users as $user)
                                <li class="list-group-item">
                                    <input type="checkbox" name="users[]" value="{{ $user->id }}"> {{ $user->name }}
                                </li>
                            @endforeach
                            <!-- Add more users as needed -->
                        </ul>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-primary">Send Note</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Bootstrap JS (Include Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
@endsection
