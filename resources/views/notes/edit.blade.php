@extends('layouts.app')

@section('styles')
    <!-- Use either the local or CDN CSS but not both for Trumbowyg -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.25.1/dist/ui/trumbowyg.min.css">
@endsection

@section('content')
    <div class="container pt-5">

        <form action="{{ route('notes.update', ['note' => $note->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="col-md-12 mb-3">
                <label for="title" class="form-label">Title :</label>
                <input type="text" class="form-control" id="title" name="title" 
                                   placeholder="Title"  value="{{ old('title', $note->title) }}" >
            </div>
            {{-- {!! $errors->first('content', '<p class="help-block">:message</p>') !!} --}}
            <div class="col-md-12 mb-3">
                <label class="form-label">Note :</label>
                {{-- this textarea with editor's tools by install a package with this command npm install Trumbowyg --}}
                <div>
                    <textarea id="editor" name="content" placeholder="Start your note...">{{ old('content', $note->content) }}</textarea>

                </div>

            </div>

            <div class="col-md-12 mb-3">
                <button class="btn btn-warning "> <i class="bi bi-box-arrow-down" style="vertical-align: middle;"></i>
                    Update Note</button>
            </div>

        </form>

    </div>
@endsection

@section('scripts')
    <!-- Import jQuery and it is mandatory for Trumbowyg -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Import Trumbowyg from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.25.1/dist/trumbowyg.min.js"></script>

    <script>
        $('#editor').trumbowyg({

            lang: ['fr', 'ar'],
            linkTargets: ['_blank', '_self'], //Link targets 
            minimalLinks: true, //Reduce the link overlay to use only url and text fields
            imageWidthModalEdit: true, //Add a field in image insert/edit modal which allow users to set the image width.
            autogrow: true, //The text editing zone can extend itself when writing a long text
            // hideButtonTexts: true,
            tagsToRemove: ['script', 'link',
                'style'
            ], //You must do the sanitize server-side too to avoid some security issues like XSS
            btnsDef: {
                image: {
                    dropdown: ['insertImage', 'base64'],
                    ico: 'insertImage'
                },

            },
            btns: [
                // ['viewHTML'], //this may be unsecure for the application against xss attacks
                ['formatting'],
                ['strong', 'em', 'del'],
                ['superscript', 'subscript'],
                ['link'],
                // ['image'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['removeformat'],
                ['fullscreen'],
                ['emoji'],
            ]

        });
    </script>
@endsection
