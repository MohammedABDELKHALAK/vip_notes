{{-- @if (session()->has('status'))

<div class="alert alert-success">
    {{ session()->get('status') }}
</div>
--}}

@if (session('success-send'))
    <div class="alert alert-info">
        {{ session('success-send') }}
    </div>
@elseif(session('success-update'))
    <div class="alert alert-warning">
        {{ session('success-update') }}
    </div>
@elseif(session('success-create'))
    <div class="alert alert-success">
        {{ session('success-create') }}
    </div>
{{-- @elseif(session('success-delete'))
    <div class="alert alert-danger">
        {{ session('success-delete') }}
    </div> --}}
@endif

<div class="pt-5">

    {{-- <form id="perPageForm" method="get" action="{{ route('pagination-perpage') }}" class="mb-3"
style="width: 100px; float:right;">

<select name="perPage" id="perPage" class="form-select" onchange="this.form.submit()">

    <option value="{{ $products->total() }}" {{ $products->perPage() == $products->total() ? 'selected' : '' }}>
        All</option>

    @for ($i = 10; $i <= ceil($products->total() / 10) * 10; $i += 10)
        <option value="{{ $i }}" {{ $products->perPage() == $i ? 'selected' : '' }}>
            {{ $i }}</option>
    @endfor

</select>

</form> --}}


    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 id="note-count">All Notes</h2>

        <a href="{{ route('notes.create') }}" type="button" class="btn btn-primary"
            style=" font-size:15px; vertical-align: middle;">
            <i class="bi bi-plus"></i>Create a Note</a>
    </div>


    <table class="table table-bordered table-striped table-hover text-center ">
        <thead>
            <tr>
                <th scope="col">Titles</th>
                <th scope="col">Status</th>
                <th scope="col">Tokens</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>

        <tbody id="notes-body">

            @if ($notes->count())
                @foreach ($notes as $note)
                    <tr data-note-id="{{ $note->id }}">
                        {{-- this TD for title --}}
                        <td class="">{{ $note->title }}</td>
                        {{-- this TD for status --}}
                        <td class="openStatus ">
                            {!! $note->is_opened
                                ? '<span class="text-danger"> Opened </span>'
                                : '<span class="text-success"> Not Opened </span>' !!}
                        </td>
                        {{-- this TD for tokens --}}
                        <td class="d-flex justify-content-around flex-wrap gap-3 ">
                            {{-- this for token text column --}}
                            <div class="token">

                                @if ($note->is_opened)
                                    <span><del class="text-danger del-token"
                                            id="token-{{ $note->id }}">{{ $note->access_token }}</del></span>
                                @else
                                    <span id="token-{{ $note->id }}">{{ $note->access_token }}</span>
                                @endif

                            </div>
                            {{-- this for regenerateToken & toggle checkbox column --}}
                            {{-- this button is for javascript vanilla script --}}
                            {{-- <a class="btn text-secondary float-end" href="javascript:void(0);" onclick="regenerateToken({{ $note->id }})">
                                <i class="bi bi-arrow-clockwise"></i></a> --}}

                            {{-- this for re-generate token button for jQuery script --}}
                            {{-- <button class="regenerate-token text-secondary bg-transparent border-0"
                                data-id="{{ $note->id }}">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button> --}}

                            {{-- this for toggle checkbox to change the status --}}
                            <div class="wrap-check">
                                <label class="switch " for="checkbox-{{ $note->id }}">
                                    <input class="checkbox-toggle" type="checkbox" id="checkbox-{{ $note->id }}"
                                        data-id="{{ $note->id }}" {{ $note->is_opened ? 'checked' : '' }} />

                                    <div class="slider round"></div>
                                </label>
                            </div>
                        </td>
                        {{-- this TD for action button edit, show and delete --}}
                        <td class="action">

                            <div class="d-flex justify-content-center flex-wrap div-action">
                                {{-- edit button --}}
                                <a class="" href=" {{ route('notes.edit', ['note' => $note->id]) }}">
                                    <span class="btn btn-outline-warning btn-sm "><i
                                            class="bi bi-pencil-square"></i></i></span>
                                </a>
                                {{-- show button --}}
                                <a class=""
                                    href="{{ route('notes.show', ['id' => $note->id, 'token' => $note->access_token]) }}">
                                    <span class="btn btn-outline-primary btn-sm"><i class="bi bi-eye-fill"></i></span>
                                </a>
                                {{-- delete button --}}
                                <form class="delete-note-form" method="POST"
                                    action="{{ route('notes.destroy', ['note' => $note->id]) }}">
                                    @csrf
                                    @method('DELETE')

                                    <a href="#" id="checkbox-{{ $note->id }}" class="delete-note"
                                        data-id="{{ $note->id }}">

                                        <span class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash-fill"></i>
                                        </span>
                                    </a>
                                </form>
                            </div>

                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="fw-bold" colspan="5">There is no notes yet!!</td>

                </tr>
            @endif

        </tbody>
    </table>
    <!-- End Table with stripped rows -->

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center" id="pagination">
        {{ $notes->links() }}
    </div>

</div>
